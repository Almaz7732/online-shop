package rabbitmq

import (
	"encoding/json"
	"fmt"
	"log"
	"time"

	"notification/internal/config"
	"notification/internal/domain"

	amqp "github.com/rabbitmq/amqp091-go"
)

// Consumer handles RabbitMQ message consumption
type Consumer struct {
	conn    *amqp.Connection
	channel *amqp.Channel
	config  *config.RabbitMQConfig
	queue   amqp.Queue
}

// NewConsumer creates a new RabbitMQ consumer with retry logic
func NewConsumer(cfg *config.RabbitMQConfig) (*Consumer, error) {
	var conn *amqp.Connection
	var err error

	connectionURL := fmt.Sprintf("amqp://%s:%s@%s:%d%s",
		cfg.User, cfg.Password, cfg.Host, cfg.Port, cfg.VHost)

	// Retry connection with exponential backoff
	maxRetries := cfg.MaxRetries
	if maxRetries <= 0 {
		maxRetries = 10 // Default fallback
	}
	retryDelay := cfg.RetryDelay
	if retryDelay <= 0 {
		retryDelay = 3 * time.Second // Default fallback
	}

	for attempt := 0; attempt < maxRetries; attempt++ {
		if attempt > 0 {
			log.Printf("Retrying RabbitMQ connection (attempt %d/%d)...", attempt+1, maxRetries)
			time.Sleep(retryDelay)
		}

		conn, err = amqp.Dial(connectionURL)
		if err == nil {
			log.Printf("Successfully connected to RabbitMQ on attempt %d", attempt+1)
			break
		}

		log.Printf("Failed to connect to RabbitMQ (attempt %d/%d): %v", attempt+1, maxRetries, err)
	}

	if err != nil {
		return nil, fmt.Errorf("failed to connect to RabbitMQ after %d attempts: %w", maxRetries, err)
	}

	ch, err := conn.Channel()
	if err != nil {
		conn.Close()
		return nil, fmt.Errorf("failed to open channel: %w", err)
	}

	// Declare exchange (Laravel uses topic exchange by default)
	err = ch.ExchangeDeclare(
		cfg.Exchange, // name
		"topic",      // type
		true,         // durable
		false,        // auto-deleted
		false,        // internal
		false,        // no-wait
		nil,          // arguments
	)
	if err != nil {
		ch.Close()
		conn.Close()
		return nil, fmt.Errorf("failed to declare exchange: %w", err)
	}

	// Declare queue
	queue, err := ch.QueueDeclare(
		cfg.Queue, // name
		true,      // durable
		false,     // delete when unused
		false,     // exclusive
		false,     // no-wait
		nil,       // arguments
	)
	if err != nil {
		ch.Close()
		conn.Close()
		return nil, fmt.Errorf("failed to declare queue: %w", err)
	}

	// Bind queue to exchange
	err = ch.QueueBind(
		queue.Name,   // queue name
		cfg.Queue,    // routing key
		cfg.Exchange, // exchange
		false,
		nil,
	)
	if err != nil {
		ch.Close()
		conn.Close()
		return nil, fmt.Errorf("failed to bind queue: %w", err)
	}

	// Set QoS to process one message at a time per worker
	err = ch.Qos(
		1,     // prefetch count
		0,     // prefetch size
		false, // global
	)
	if err != nil {
		ch.Close()
		conn.Close()
		return nil, fmt.Errorf("failed to set QoS: %w", err)
	}

	return &Consumer{
		conn:    conn,
		channel: ch,
		config:  cfg,
		queue:   queue,
	}, nil
}

// MessageWrapper wraps a notification with its delivery for acknowledgment
type MessageWrapper struct {
	Notification *domain.Notification
	Delivery     amqp.Delivery
}

// Consume starts consuming messages from the queue and sends them to the provided channel
func (c *Consumer) Consume(msgChan chan<- *MessageWrapper) error {
	msgs, err := c.channel.Consume(
		c.queue.Name, // queue
		"",           // consumer
		false,        // auto-ack (we'll manually ack after processing)
		false,        // exclusive
		false,        // no-local
		false,        // no-wait
		nil,          // args
	)
	if err != nil {
		return fmt.Errorf("failed to register consumer: %w", err)
	}

	log.Printf("Started consuming messages from queue: %s", c.queue.Name)

	for msg := range msgs {
		notification, err := c.parseMessage(msg.Body)
		if err != nil {
			log.Printf("Error parsing message: %v", err)
			msg.Nack(false, false) // Reject and don't requeue
			continue
		}

		// Send to channel for processing
		msgChan <- &MessageWrapper{
			Notification: notification,
			Delivery:     msg,
		}
	}

	return nil
}

// parseMessage parses RabbitMQ message body
func (c *Consumer) parseMessage(body []byte) (*domain.Notification, error) {
	var n domain.Notification

	if err := json.Unmarshal(body, &n); err != nil {
		return nil, fmt.Errorf("invalid notification payload: %w", err)
	}

	if n.Message == "" {
		return nil, fmt.Errorf("missing required fields in notification")
	}

	return &n, nil
}

// Close closes the RabbitMQ connection
func (c *Consumer) Close() error {
	if c.channel != nil {
		if err := c.channel.Close(); err != nil {
			return err
		}
	}
	if c.conn != nil {
		return c.conn.Close()
	}
	return nil
}

// Reconnect attempts to reconnect to RabbitMQ with retry logic
func (c *Consumer) Reconnect() error {
	c.Close()

	// Use NewConsumer which already has retry logic built-in
	newConsumer, err := NewConsumer(c.config)
	if err != nil {
		return err
	}

	c.conn = newConsumer.conn
	c.channel = newConsumer.channel
	c.queue = newConsumer.queue

	return nil
}
