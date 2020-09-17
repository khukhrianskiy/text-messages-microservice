<?php

namespace App\Events;

use App\Models\TextMessage;

class TextMessageStatusUpdate
{
    private string $status;

    private TextMessage $textMessage;

    public function __construct(TextMessage $textMessage, string $status)
    {
        $this->status      = $status;
        $this->textMessage = $textMessage;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTextMessage(): TextMessage
    {
        return $this->textMessage;
    }
}
