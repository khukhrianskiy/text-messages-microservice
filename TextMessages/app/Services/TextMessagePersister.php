<?php

namespace App\Services;

use App\Dto\TextMessageDto;
use App\Models\TextMessage;

class TextMessagePersister
{
    public function save(TextMessage $textMessage): void
    {
        $textMessage->save();
    }

    public function updateStatus(TextMessage $textMessage, string $status): void
    {
        $textMessage->update([
            'status' => $status,
        ]);
    }

    public function saveFromDto(TextMessageDto $textMessageDto): void
    {
        $textMessage = new TextMessage([
            'body'         => $textMessageDto->getMessage(),
            'status'       => $textMessageDto->getStatus(),
            'phone_number' => $textMessageDto->getPhoneNumber(),
        ]);

        $this->save($textMessage);
    }
}
