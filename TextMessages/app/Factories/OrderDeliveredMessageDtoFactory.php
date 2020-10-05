<?php

namespace App\Factories;

use App\Dto\TextMessageDto;

class OrderDeliveredMessageDtoFactory
{
    public function create(string $phoneNumber): TextMessageDto
    {
        $message = view('text-messages.order-delivered-message');

        return new TextMessageDto($message, $phoneNumber);
    }
}
