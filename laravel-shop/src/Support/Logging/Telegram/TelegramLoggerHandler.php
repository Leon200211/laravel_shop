<?php

declare(strict_types=1);

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;

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
        //app(TelegramBotApiContract::class)::sendMessage($this->token, $this->chatId, $record['formatted']);
        TelegramBotApi::sendMessage($this->token, $this->chatId, $record['formatted']);
    }
}
