<?php

namespace Tests\Unit\Factories;

use App\Factories\SendMessageFactory;
use App\Models\TextMessage;
use MessageBird\Objects\Conversation\SendMessage;
use PHPUnit\Framework\TestCase;

class SendMessageFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function testFromTextMessage(): void
    {
        $channelId   = 'test_channel_id';
        $phoneNumber = 'test_phone_number';
        $body        = 'Text message body';

        $textMessage = new TextMessage([
            'phone_number' => $phoneNumber,
            'body'         => $body,
        ]);

        $sendMessageFactory = new SendMessageFactory($channelId);

        $sendMessage = $sendMessageFactory->fromTextMessage($textMessage);

        $this->assertInstanceOf(SendMessage::class, $sendMessage);
        $this->assertSame('text', $sendMessage->type);
        $this->assertSame($channelId, $sendMessage->from);
        $this->assertSame($phoneNumber, $sendMessage->to);
        $this->assertSame($body, $sendMessage->content->text);
    }
}
