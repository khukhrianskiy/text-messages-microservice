<?php

namespace App\Services;

use App\Dto\TextMessageDto;
use App\Services\Clients\TextMessageClientInterface;

class TextMessageSender
{
    private TextMessageClientInterface $textMessageClient;

    public function __construct(TextMessageClientInterface $textMessageClient)
    {
        $this->textMessageClient = $textMessageClient;
    }

    public function send(TextMessageDto $textMessageDto): string
    {
        $response = $this->textMessageClient->sendMessage($textMessageDto);

        return $response->getStatus();
    }
}
