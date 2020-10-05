<?php

namespace Tests\Unit\Jobs;

use App\Dto\TextMessageDto;
use App\Factories\OrderDeliveredMessageDtoFactory;
use App\Jobs\SendDeliveredTextMessage;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Tests\TestCase;

class SendDeliveredTextMessageTest extends TestCase
{
    /**
     * @test
     */
    public function testHandle(): void
    {
        $phoneNumber = 'text_phone_number';

        $textMessage = new TextMessageDto('message', $phoneNumber);

        $orderDeliveredMessageDtoFactoryMock = $this->createMock(OrderDeliveredMessageDtoFactory::class);
        $orderDeliveredMessageDtoFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with($phoneNumber)
            ->willReturn($textMessage);

        $textMessageSenderMock = $this->createMock(TextMessageSender::class);
        $textMessageSenderMock
            ->expects(self::once())
            ->method('send')
            ->with($textMessage);

        $textMessagePersisterMock = $this->createMock(TextMessagePersister::class);
        $textMessagePersisterMock
            ->expects(self::once())
            ->method('saveFromDto')
            ->with($textMessage);

        $sendDeliveredTextMessageJob = new SendDeliveredTextMessage($phoneNumber);

        $sendDeliveredTextMessageJob->handle(
            $orderDeliveredMessageDtoFactoryMock,
            $textMessageSenderMock,
            $textMessagePersisterMock
        );
    }
}
