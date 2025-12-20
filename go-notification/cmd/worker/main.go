package main

import (
	"log"
	"os"

	"notification/internal/config"
	"notification/internal/shutdown"
	"notification/internal/worker"
)

func main() {
	log.Println("Starting notification worker...")

	// Load configuration
	cfg := config.Load()

	// Validate required configuration
	if cfg.Telegram.Token == "" {
		log.Fatal("TELEGRAM_BOT_TOKEN environment variable is required")
	}
	if cfg.Telegram.ChatID == "" {
		log.Fatal("TELEGRAM_CHAT_ID environment variable is required")
	}

	// Create worker pool
	pool, err := worker.NewPool(cfg)
	if err != nil {
		log.Fatalf("Failed to create worker pool: %v", err)
	}

	// Start worker pool
	if err := pool.Start(); err != nil {
		log.Fatalf("Failed to start worker pool: %v", err)
	}

	log.Println("Notification worker started successfully")

	// Handle graceful shutdown
	shutdown.HandleSignals(func() {
		log.Println("Received shutdown signal, stopping worker...")
		pool.Stop()
		log.Println("Worker stopped")
		os.Exit(0)
	})
}
