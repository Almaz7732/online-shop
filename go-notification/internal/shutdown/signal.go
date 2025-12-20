package shutdown

import (
	"os"
	"os/signal"
	"syscall"
)

// HandleSignals sets up signal handling for graceful shutdown
func HandleSignals(shutdown func()) {
	sigChan := make(chan os.Signal, 1)
	signal.Notify(sigChan, os.Interrupt, syscall.SIGTERM)

	<-sigChan
	shutdown()
}
