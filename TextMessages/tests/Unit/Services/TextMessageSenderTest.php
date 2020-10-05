<?php

namespace Tests\Unit\Services;

use App\Dto\TextMessageDto;
use App\Services\Clients\TextMessageClientInterface;
use App\Services\Clients\TextMessageResponse;
use App\Services\TextMessageSender;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TextMessageSenderTest extends TestCase
{
    /**
     * @test
     */
    public function testSend(): void
    {
        Event::fake();

        $textMessageResponse = new TextMessageResponse('accepted');

        $textMessage = new TextMessageDto('message', 'phone number');

        /** @var MockObject|TextMessageClientInterface $textMessageClientMock */
        $textMessageClientMock = $this->createMock(TextMessageClientInterface::class);
        $textMessageClientMock
            ->expects(self::once())
            ->method('sendMessage')
            ->with($textMessage)
            ->willReturn($textMessageResponse);

        $textMessageSender = new TextMessageSender($textMessageClientMock);

        $textMessageSender->send($textMessage);
    }
}
