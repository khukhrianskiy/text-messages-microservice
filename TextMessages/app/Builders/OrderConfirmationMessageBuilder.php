<?php

namespace App\Builders;

use App\Models\TextMessage;

class OrderConfirmationMessageBuilder implements TextMessageBuilderInterface
{
    public const TEXT_MESSAGE_BODY_TEMPLATE = 'Your order from restaurant %s will be delivered %s';

    private string $restaurantName;

    private string $deliveryTime;

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

    public function setRestaurantName(string $restaurantName): self
    {
        $this->restaurantName = $restaurantName;

        return $this;
    }

    public function setDeliveryTime(string $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    public function buildBody(): self
    {
        $this->textMessage->body = sprintf(
            self::TEXT_MESSAGE_BODY_TEMPLATE, $this->restaurantName, $this->deliveryTime);

        return $this;
    }

    public function get(): TextMessage
    {
        return $this->textMessage;
    }
}
