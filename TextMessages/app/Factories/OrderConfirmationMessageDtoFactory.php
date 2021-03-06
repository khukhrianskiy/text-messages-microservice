<?php

namespace App\Factories;

use App\Dto\TextMessageDto;

class OrderConfirmationMessageDtoFactory
{
    private const SUBJECT = 'Order Confirmation Message';

    public function create(array $request): TextMessageDto
    {
        $message = view('text-messages.order-confirmation-message', [
            'restaurant_name' => $request['restaurant_name'],
            'delivery_time'   => $request['delivery_time'],
        ]);

        return new TextMessageDto($message, $request['phone_number'], self::SUBJECT);
    }
}
