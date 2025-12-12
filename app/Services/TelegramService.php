<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramService
{
    public static function sendMessage($chatName, $message): void
    {
        $config = app('config')->get('telegram')['ids'];
        $token = $config['token'];
        $chatId = ($config[$chatName]);

        try {
            $telegram = new Api($token);

            // Disable SSL verification for local development
            if (config('app.env') === 'local') {
                $telegram->setAsyncRequest(false);
                $guzzleClient = new \GuzzleHttp\Client([
                    'verify' => false,
                    'timeout' => 30,
                ]);
                $telegram->setHttpClientHandler(new \Telegram\Bot\HttpClients\GuzzleHttpClient($guzzleClient));
            }

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'parse_mode' => 'HTML',
                'text' => $message,
                'disable_web_page_preview' => true,
            ]);
        }catch (\Exception $exception){
            Log::error("TELEGRAM sendMessage: {$exception->getMessage()}");
        }

    }

    public static function sendMessageByChatId($token, $chatId, $message)
    {
        try {
            $telegram = new Api($token);

            // Disable SSL verification for local development
            if (config('app.env') === 'local') {
                $telegram->setAsyncRequest(false);
                $guzzleClient = new \GuzzleHttp\Client([
                    'verify' => false,
                    'timeout' => 30,
                ]);
                $telegram->setHttpClientHandler(new \Telegram\Bot\HttpClients\GuzzleHttpClient($guzzleClient));
            }

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'parse_mode' => 'HTML',
                'text' => $message,
                'disable_web_page_preview' => true
            ]);
        }catch (\Exception $exception){
            Log::error("TELEGRAM sendMessageByChatId: {$exception->getMessage()}");
        }
    }
}
