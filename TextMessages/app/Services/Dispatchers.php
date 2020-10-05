<?php

namespace App\Services;

use App\Dto\TextMessageDto;
use App\Jobs\SendDeliveredTextMessage;
use App\Models\TextMessage;

class Dispatchers
{
    public function afterSendDeliveredTextMessage(TextMessageDto $textMessageDto)
    {
        SendDeliveredTextMessage::dispatch($textMessageDto->getPhoneNumber())
            ->delay(now()->addMinutes(TextMessage::DELIVERED_MESSAGE_DELAY_MINUTES));
    }
}
