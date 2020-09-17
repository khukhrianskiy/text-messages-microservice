<?php

namespace Tests\Unit\Services\Clients;

use App\Factories\SendMessageFactory;
use App\Models\TextMessage;
use App\Services\Clients\MessageBirdClient;
use App\Services\Clients\TextMessageResponse;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\ServerException;
use MessageBird\Objects\Conversation\SendMessage;
use MessageBird\Objects\Conversation\SendMessageResult;
use MessageBird\Resources\Conversation\Send;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class MessageBirdClientTest extends TestCase
{
    /**
     * @test
     */
    public function testSendMessageWithSuccessResult(): void
    {
        $textMessageMock = $this->createMock(TextMessage::class);
        $sendMessageMock = $this->createMock(SendMessage::class);

        $sendMessageFactoryMock = $this->getSendMessageFactoryMock($textMessageMock, $sendMessageMock);
        $clientMock   = $this->getClientMock($sendMessageMock);

        $messageBirdClient = new MessageBirdClient($sendMessageFactoryMock, $clientMock);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('send'));
    }

    /**
     * @test
     */
    public function testSendMessageWithAuthenticateError(): void
    {
        $textMessageMock = $this->createMock(TextMessage::class);
        $sendMessageMock = $this->createMock(SendMessage::class);

        $sendMessageFactoryMock = $this->getSendMessageFactoryMock($textMessageMock, $sendMessageMock);
        $clientMock   = $this->getClientMock($sendMessageMock, new AuthenticateException());

        $messageBirdClient = new MessageBirdClient($sendMessageFactoryMock, $clientMock);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @test
     */
    public function testSendMessageWithMessageBirdException(): void
    {
        $textMessageMock = $this->createMock(TextMessage::class);
        $sendMessageMock = $this->createMock(SendMessage::class);

        $sendMessageFactoryMock = $this->getSendMessageFactoryMock($textMessageMock, $sendMessageMock);
        $clientMock   = $this->getClientMock($sendMessageMock, new ServerException());

        $messageBirdClient = new MessageBirdClient($sendMessageFactoryMock, $clientMock);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @test
     */
    public function testSendMessageWithUnknownException(): void
    {
        $textMessageMock = $this->createMock(TextMessage::class);
        $sendMessageMock = $this->createMock(SendMessage::class);

        $sendMessageFactoryMock = $this->getSendMessageFactoryMock($textMessageMock, $sendMessageMock);
        $clientMock   = $this->getClientMock($sendMessageMock, new \Exception());

        $messageBirdClient = new MessageBirdClient($sendMessageFactoryMock, $clientMock);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @return MockObject|SendMessageFactory
     */
    private function getSendMessageFactoryMock(TextMessage $textMessageMock, SendMessage $sendMessageMock): MockObject
    {
        $sendMessageFactoryMock = $this->createMock(SendMessageFactory::class);
        $sendMessageFactoryMock
            ->expects(self::once())
            ->method('fromTextMessage')
            ->with($textMessageMock)
            ->willReturn($sendMessageMock);

        return $sendMessageFactoryMock;
    }

    /**
     * @return MockObject|Client
     */
    private function getClientMock(SendMessage $sendMessage, ?\Throwable $exception = null): MockObject
    {
        $conversationSendMock = $this->createMock(Send::class);

        if ($exception) {
            $conversationSendMock
                ->expects(self::once())
                ->method('send')
                ->willThrowException($exception)
                ->with($sendMessage);
        } else {
            $conversationSendMock
                ->expects(self::once())
                ->method('send')
                ->with($sendMessage)
                ->willReturn($this->getSendMessageResult());
        }

        $clientMock = $this->createMock(Client::class);
        $clientMock->conversationSend = $conversationSendMock;

        return $clientMock;
    }

    private function getSendMessageResult(): SendMessageResult
    {
        $sendMessageResult         =  new SendMessageResult();
        $sendMessageResult->id     = '123456';
        $sendMessageResult->status = 'send';

        return $sendMessageResult;
    }
}
