<?php

namespace Tests\Unit\Builders;

use App\Builders\OrderConfirmationMessageBuilder;
use App\Builders\OrderDeliveredMessageBuilder;
use App\Builders\TextMessageDirector;
use App\Models\TextMessage;
use PHPUnit\Framework\TestCase;

class TextMessageDirectorTest extends TestCase
{
    /**
     * @test
     */
    public function testBuildOrderConfirmationMessage(): void
    {
        $restaurantName = 'Test restaurant';
        $deliveryTime   = '2020-09-23 09:36';
        $phoneNumber    = 'text_phone_number';

        $textMessageDirector = new TextMessageDirector();

        $textMessage = $textMessageDirector->buildOrderConfirmationMessage(
            $restaurantName,
            $deliveryTime,
            $phoneNumber
        )->get();

        $this->assertInstanceOf(TextMessage::class, $textMessage);

        $this->assertSame(
            sprintf(OrderConfirmationMessageBuilder::TEXT_MESSAGE_BODY_TEMPLATE, $restaurantName, $deliveryTime),
            $textMessage->body
        );
        $this->assertSame($phoneNumber, $textMessage->phone_number);
        $this->assertSame(TextMessage::STATUS_NEW, $textMessage->status);
    }

    /**
     * @test
     */
    public function testBuildOrderDeliveredMessage(): void
    {
        $phoneNumber = 'text_phone_number';

        $textMessageDirector = new TextMessageDirector();

        $textMessage = $textMessageDirector->buildOrderDeliveredMessage($phoneNumber)->get();

        $this->assertInstanceOf(TextMessage::class, $textMessage);

        $this->assertSame(OrderDeliveredMessageBuilder::TEXT_MESSAGE_BODY_TEMPLATE, $textMessage->body);
        $this->assertSame($phoneNumber, $textMessage->phone_number);
        $this->assertSame(TextMessage::STATUS_NEW, $textMessage->status);
    }
}
