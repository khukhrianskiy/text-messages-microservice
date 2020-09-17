<?php

namespace App\Services;

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
}
