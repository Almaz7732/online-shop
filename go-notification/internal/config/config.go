package config

import (
	"os"
	"strconv"
	"time"
)

// Config holds all configuration for the application
type Config struct {
	RabbitMQ RabbitMQConfig
	Telegram TelegramConfig
	Worker   WorkerConfig
}

// RabbitMQConfig holds RabbitMQ connection configuration
type RabbitMQConfig struct {
	Host         string
	Port         int
	User         string
	Password     string
	VHost        string
	Queue        string
	Exchange     string
	MaxRetries   int
	RetryDelay   time.Duration
}

// TelegramConfig holds Telegram Bot API configuration
type TelegramConfig struct {
	Token  string
	ChatID string
}

// WorkerConfig holds worker pool configuration
type WorkerConfig struct {
	WorkersCount int
	RetryCount   int
	RetryDelay   time.Duration
}

// Load loads configuration from environment variables
func Load() *Config {
	return &Config{
		RabbitMQ: RabbitMQConfig{
			Host:       getEnv("RABBITMQ_HOST", "rabbitmq"),
			Port:       getEnvAsInt("RABBITMQ_PORT", 5672),
			User:       getEnv("RABBITMQ_USER", "guest"),
			Password:   getEnv("RABBITMQ_PASSWORD", "guest"),
			VHost:      getEnv("RABBITMQ_VHOST", "/"),
			Queue:      getEnv("RABBITMQ_QUEUE", "default"),
			Exchange:   getEnv("RABBITMQ_EXCHANGE", "laravel"),
			MaxRetries: getEnvAsInt("RABBITMQ_MAX_RETRIES", 10),
			RetryDelay: getEnvAsDuration("RABBITMQ_RETRY_DELAY", 3*time.Second),
		},
		Telegram: TelegramConfig{
			Token:  getEnv("TELEGRAM_BOT_TOKEN", ""),
			ChatID: getEnv("TELEGRAM_CHAT_ID", ""),
		},
		Worker: WorkerConfig{
			WorkersCount: getEnvAsInt("WORKER_COUNT", 5),
			RetryCount:   getEnvAsInt("RETRY_COUNT", 3),
			RetryDelay:   getEnvAsDuration("RETRY_DELAY", 60*time.Second),
		},
	}
}

// getEnv gets an environment variable or returns a default value
func getEnv(key, defaultValue string) string {
	if value := os.Getenv(key); value != "" {
		return value
	}
	return defaultValue
}

// getEnvAsInt gets an environment variable as integer or returns a default value
func getEnvAsInt(key string, defaultValue int) int {
	if value := os.Getenv(key); value != "" {
		if intValue, err := strconv.Atoi(value); err == nil {
			return intValue
		}
	}
	return defaultValue
}

// getEnvAsDuration gets an environment variable as duration or returns a default value
func getEnvAsDuration(key string, defaultValue time.Duration) time.Duration {
	if value := os.Getenv(key); value != "" {
		if duration, err := time.ParseDuration(value); err == nil {
			return duration
		}
	}
	return defaultValue
}
