<?php

namespace App\Builders;

use App\Models\TextMessage;

class OrderDeliveredMessageBuilder implements TextMessageBuilderInterface
{
    public const TEXT_MESSAGE_BODY_TEMPLATE = 'Your order has been delivered. Bon appetite!';

    private TextMessage $textMessage;

    public function __construct()
    {
        $this->create();
    }

    public function create(): self
    {
        $this->textMessage = new TextMessage([
            'status' => TextMessage::STATUS_NEW,
        ]);

        return $this;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->textMessage->phone_number = $phoneNumber;

        return $this;
    }

    public function buildBody(): self
    {
        $this->textMessage->body = self::TEXT_MESSAGE_BODY_TEMPLATE;

        return $this;
    }

    public function get(): TextMessage
    {
        return $this->textMessage;
    }
}
