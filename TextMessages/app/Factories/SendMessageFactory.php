<?php

namespace App\Factories;

use App\Models\TextMessage;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\SendMessage;

class SendMessageFactory
{
    private ?string $channelId;

    public function __construct(?string $channelId)
    {
        $this->channelId = $channelId;
    }

    public function fromTextMessage(TextMessage $textMessage): SendMessage
    {
        $content       = new Content();
        $content->text = $textMessage->body;

        $sendMessage          = new SendMessage();
        $sendMessage->type    = 'text';
        $sendMessage->from    = $this->channelId;
        $sendMessage->to      = $textMessage->phone_number;
        $sendMessage->content = $content;

        return $sendMessage;
    }
}
