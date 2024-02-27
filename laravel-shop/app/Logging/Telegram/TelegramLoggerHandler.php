<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    private string $token;
    private int $chatId;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);
        $this->token = $config['token'];
        $this->chatId = (int)$config['chat_id'];

        parent::__construct($level);
    }

    /**
     * @param array $record
     *
     * @return void
     */
    protected function write(array $record): void
    {
        TelegramBotApi::sendMessage($this->token, $this->chatId, $record['formatted']);
    }
}
