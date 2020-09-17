<?php

namespace App\Services;

use App\Events\TextMessageStatusUpdate;
use App\Models\TextMessage;
use App\Services\Clients\TextMessageClientInterface;

class TextMessageSender
{
    private TextMessageClientInterface $textMessageClient;

    public function __construct(TextMessageClientInterface $textMessageClient)
    {
        $this->textMessageClient = $textMessageClient;
    }

    public function send(TextMessage $textMessage): void
    {
        $response = $this->textMessageClient->sendMessage($textMessage);

        event(new TextMessageStatusUpdate($textMessage, $response->getStatus()));
    }
}
