<?php

namespace App\Services\Notifications;

use App\Contracts\NotificationInterface;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class DebugNotificationService implements NotificationInterface
{
    /**
     * Send notification message via Telegram
     *
     * @param string $message
     * @return void
     */
    public function send(string $message): void
    {
        try {
            Log::debug($message);
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification: ' . $e->getMessage());
            throw $e;
        }
    }
}
