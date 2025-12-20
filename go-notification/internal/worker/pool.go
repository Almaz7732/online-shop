package worker

import (
	"context"
	"fmt"
	"log"
	"sync"
	"time"

	"notification/internal/config"
	"notification/internal/domain"
	"notification/internal/notifier"
	"notification/internal/rabbitmq"
)

// Pool manages a pool of workers that process notifications
type Pool struct {
	config   *config.Config
	consumer *rabbitmq.Consumer
	notifier *notifier.TelegramNotifier
	workers  int
	wg       sync.WaitGroup
	ctx      context.Context
	cancel   context.CancelFunc
	msgChan  chan *rabbitmq.MessageWrapper
}

// NewPool creates a new worker pool
func NewPool(cfg *config.Config) (*Pool, error) {
	consumer, err := rabbitmq.NewConsumer(&cfg.RabbitMQ)
	if err != nil {
		return nil, err
	}

	notifier := notifier.NewTelegramNotifier(&cfg.Telegram)

	ctx, cancel := context.WithCancel(context.Background())

	return &Pool{
		config:   cfg,
		consumer: consumer,
		notifier: notifier,
		workers:  cfg.Worker.WorkersCount,
		ctx:      ctx,
		cancel:   cancel,
		msgChan:  make(chan *rabbitmq.MessageWrapper, cfg.Worker.WorkersCount*2),
	}, nil
}

// Start starts the worker pool
func (p *Pool) Start() error {
	log.Printf("Starting worker pool with %d workers", p.workers)

	// Start worker goroutines
	for i := 0; i < p.workers; i++ {
		p.wg.Add(1)
		go p.worker(i)
	}

	// Start message consumer
	p.wg.Add(1)
	go p.messageConsumer()

	return nil
}

// messageConsumer consumes messages from RabbitMQ and sends them to workers
func (p *Pool) messageConsumer() {
	defer p.wg.Done()

	for {
		select {
		case <-p.ctx.Done():
			close(p.msgChan)
			return
		default:
			// Consume messages and send them to the channel
			err := p.consumer.Consume(p.msgChan)

			if err != nil {
				log.Printf("Error consuming messages: %v", err)
				log.Println("Attempting to reconnect...")

				// Try to reconnect
				if err := p.consumer.Reconnect(); err != nil {
					log.Printf("Failed to reconnect: %v", err)
					// Wait before retrying
					select {
					case <-p.ctx.Done():
						close(p.msgChan)
						return
					case <-time.After(10 * time.Second):
					}
				} else {
					log.Println("Reconnected successfully")
				}
			}
		}
	}
}

// worker is a single worker goroutine that processes notifications
func (p *Pool) worker(id int) {
	defer p.wg.Done()
	log.Printf("Worker %d started", id)

	for {
		select {
		case <-p.ctx.Done():
			log.Printf("Worker %d stopped", id)
			return
		case msgWrapper, ok := <-p.msgChan:
			if !ok {
				log.Printf("Worker %d: message channel closed", id)
				return
			}

			if err := p.handleNotification(msgWrapper.Notification); err != nil {
				log.Printf("Worker %d: Failed to process notification: %v", id, err)
				// Nack with requeue for retry
				if err := msgWrapper.Delivery.Nack(false, true); err != nil {
					log.Printf("Worker %d: Error nacking message: %v", id, err)
				}
			} else {
				// Acknowledge successful processing
				if err := msgWrapper.Delivery.Ack(false); err != nil {
					log.Printf("Worker %d: Error acknowledging message: %v", id, err)
				}
			}
		}
	}
}

// handleNotification processes a notification
func (p *Pool) handleNotification(notification *domain.Notification) error {
	log.Printf("Processing notification: %s", truncateString(notification.Message, 100))

	// Retry logic
	var lastErr error
	for attempt := 0; attempt < p.config.Worker.RetryCount; attempt++ {
		if attempt > 0 {
			log.Printf("Retry attempt %d/%d", attempt+1, p.config.Worker.RetryCount)
			select {
			case <-p.ctx.Done():
				return context.Canceled
			case <-time.After(p.config.Worker.RetryDelay):
			}
		}

		if err := p.notifier.SendMessage(notification.Message); err != nil {
			lastErr = err
			log.Printf("Error sending notification (attempt %d/%d): %v", attempt+1, p.config.Worker.RetryCount, err)
			continue
		}

		log.Println("Notification sent successfully")
		return nil
	}

	return fmt.Errorf("failed to send notification after %d attempts: %w", p.config.Worker.RetryCount, lastErr)
}

// Stop stops the worker pool gracefully
func (p *Pool) Stop() {
	log.Println("Stopping worker pool...")
	p.cancel()
	p.wg.Wait()

	if err := p.consumer.Close(); err != nil {
		log.Printf("Error closing consumer: %v", err)
	}

	log.Println("Worker pool stopped")
}

// truncateString truncates a string to a maximum length
func truncateString(s string, maxLen int) string {
	if len(s) <= maxLen {
		return s
	}
	return s[:maxLen] + "..."
}
