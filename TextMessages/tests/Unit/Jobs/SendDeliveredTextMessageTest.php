<?php

namespace Tests\Unit\Jobs;

use App\Builders\TextMessageDirector;
use App\Jobs\SendDeliveredTextMessage;
use App\Models\TextMessage;
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

        $textMessage = new TextMessage(['phone_number' => $phoneNumber]);

        $textMessageDirectorMock  = $this->createMock(TextMessageDirector::class);
        $textMessageDirectorMock
            ->expects(self::once())
            ->method('buildOrderDeliveredMessage')
            ->with($phoneNumber)
            ->willReturnSelf();

        $textMessageDirectorMock
            ->expects(self::once())
            ->method('get')
            ->willReturn($textMessage);

        $textMessageSenderMock = $this->createMock(TextMessageSender::class);
        $textMessageSenderMock
            ->expects(self::once())
            ->method('send')
            ->with($textMessage);

        $textMessagePersisterMock = $this->createMock(TextMessagePersister::class);
        $textMessagePersisterMock
            ->expects(self::once())
            ->method('save')
            ->with($textMessage);

        $sendDeliveredTextMessageJob = new SendDeliveredTextMessage($phoneNumber);

        $sendDeliveredTextMessageJob->handle(
            $textMessageDirectorMock,
            $textMessageSenderMock,
            $textMessagePersisterMock
        );
    }
}
