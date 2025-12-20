package domain

// Notification represents a notification message
type Notification struct {
	Message string
}

// NewNotification creates a new notification instance
func NewNotification(message string) *Notification {
	return &Notification{
		Message: message,
	}
}
