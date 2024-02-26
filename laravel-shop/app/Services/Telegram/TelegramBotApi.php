<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    /**
     * Отправка сообщения ботов в чат
     *
     * @param string $token
     * @param int $chatId
     * @param string $text
     *
     * @return void
     */
    public static function sendMessage(string $token, int $chatId, string $text): void
    {
        Http::get(self::HOST . $token . '/sendMessage', [
            'chat_id' => $chatId,
            'text'    => $text,
        ]);
    }
}
