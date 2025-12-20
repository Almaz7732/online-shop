package notifier

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"time"

	"notification/internal/config"
)

// TelegramNotifier sends notifications via Telegram Bot API
type TelegramNotifier struct {
	config *config.TelegramConfig
	client *http.Client
	apiURL string
}

// NewTelegramNotifier creates a new Telegram notifier instance
func NewTelegramNotifier(cfg *config.TelegramConfig) *TelegramNotifier {
	return &TelegramNotifier{
		config: cfg,
		client: &http.Client{
			Timeout: 30 * time.Second,
		},
		apiURL: "https://api.telegram.org/bot",
	}
}

// SendMessage sends a message to Telegram
func (tn *TelegramNotifier) SendMessage(message string) error {
	if tn.config.Token == "" || tn.config.ChatID == "" {
		return fmt.Errorf("telegram token or chat ID is not configured")
	}

	url := fmt.Sprintf("%s%s/sendMessage", tn.apiURL, tn.config.Token)

	payload := map[string]interface{}{
		"chat_id":                  tn.config.ChatID,
		"text":                     message,
		"parse_mode":               "HTML",
		"disable_web_page_preview": true,
	}

	jsonData, err := json.Marshal(payload)
	if err != nil {
		return fmt.Errorf("failed to marshal payload: %w", err)
	}

	req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonData))
	if err != nil {
		return fmt.Errorf("failed to create request: %w", err)
	}

	req.Header.Set("Content-Type", "application/json")

	resp, err := tn.client.Do(req)
	if err != nil {
		return fmt.Errorf("failed to send request: %w", err)
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return fmt.Errorf("failed to read response: %w", err)
	}

	if resp.StatusCode != http.StatusOK {
		return fmt.Errorf("telegram API error: status %d, body: %s", resp.StatusCode, string(body))
	}

	// Parse response to check for Telegram API errors
	var telegramResp struct {
		OK          bool   `json:"ok"`
		Description string `json:"description,omitempty"`
	}

	if err := json.Unmarshal(body, &telegramResp); err != nil {
		return fmt.Errorf("failed to parse response: %w", err)
	}

	if !telegramResp.OK {
		return fmt.Errorf("telegram API error: %s", telegramResp.Description)
	}

	return nil
}
