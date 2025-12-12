<?php

namespace App\Contracts;

interface NotificationInterface
{
    /**
     * Send notification message
     *
     * @param string $message
     * @return void
     */
    public function send(string $message): void;
}
