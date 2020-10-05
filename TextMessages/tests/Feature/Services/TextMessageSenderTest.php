<?php

namespace Tests\Feature\Services;

use App\Dto\TextMessageDto;
use App\Models\TextMessage;
use App\Services\Clients\TextMessageClientInterface;
use App\Services\Clients\TextMessageResponse;
use App\Services\TextMessageSender;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TextMessageSenderTest extends TestCase
{
    /**
     * @test
     */
    public function testSend(): void
    {
        /** @var TextMessage $textMessage */
        $textMessage = TextMessage::factory()->create();

        $textMessageDto = new TextMessageDto($textMessage->body, $textMessage->phone_number, 'SUBJECT');

        $this->assertSame(TextMessage::STATUS_NEW, $textMessage->status);

        $testMessageResponse = new TextMessageResponse('error');

        /** @var TextMessageClientInterface|MockObject $textMessageClientMock */
        $textMessageClientMock = $this->createMock(TextMessageClientInterface::class);
        $textMessageClientMock
            ->expects(self::once())
            ->method('sendMessage')
            ->with($textMessageDto)
            ->willReturn($testMessageResponse);

        $textMessageSender = new TextMessageSender($textMessageClientMock);

        $textMessageSender->send($textMessageDto);

        $this->assertDatabaseHas('text_messages', [
            'id'     => $textMessage->id,
            'status' => 'new',
        ]);
    }
}
