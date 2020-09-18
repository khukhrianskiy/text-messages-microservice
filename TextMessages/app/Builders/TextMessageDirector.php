<?php


namespace App\Builders;

use App\Models\TextMessage;

class TextMessageDirector
{
    private TextMessageBuilderInterface $builder;

    public function setBuilder(TextMessageBuilderInterface $builder): void
    {
        $this->builder = $builder;
    }

    public function buildOrderConfirmationMessage(string $restaurantName, string $deliveryTime, string $phoneNumber): self
    {
        $this->setBuilder(new OrderConfirmationMessageBuilder());

        $this->builder->setRestaurantName($restaurantName);
        $this->builder->setDeliveryTime($deliveryTime);
        $this->builder->setPhoneNumber($phoneNumber);
        $this->builder->buildBody();

        return $this;
    }

    public function buildOrderDeliveredMessage(string $phoneNumber): self
    {
        $this->setBuilder(new OrderDeliveredMessageBuilder());

        $this->builder->setPhoneNumber($phoneNumber);
        $this->builder->buildBody();

        return $this;
    }

    public function get(): TextMessage
    {
        return $this->builder->get();
    }
}
