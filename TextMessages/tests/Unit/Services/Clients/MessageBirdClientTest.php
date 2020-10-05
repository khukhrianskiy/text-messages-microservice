<?php

namespace Tests\Unit\Services\Clients;

use App\Dto\TextMessageDto;
use App\Services\Clients\MessageBirdClient;
use App\Services\Clients\TextMessageResponse;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\ServerException;
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
        $clientMock = $this->getClientMock();

        $messageBirdClient = new MessageBirdClient($clientMock, 'test');

        $textMessageDtoMock = $this->createMock(TextMessageDto::class);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageDtoMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('send'));
    }

    /**
     * @test
     */
    public function testSendMessageWithAuthenticateError(): void
    {
        $clientMock   = $this->getClientMock(new AuthenticateException());

        $messageBirdClient = new MessageBirdClient($clientMock, 'test');

        $textMessageDtoMock = $this->createMock(TextMessageDto::class);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageDtoMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @test
     */
    public function testSendMessageWithMessageBirdException(): void
    {
        $clientMock   = $this->getClientMock(new ServerException());

        $messageBirdClient = new MessageBirdClient($clientMock, 'test');

        $textMessageDtoMock = $this->createMock(TextMessageDto::class);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageDtoMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @test
     */
    public function testSendMessageWithUnknownException(): void
    {
        $clientMock   = $this->getClientMock(new \Exception());

        $messageBirdClient = new MessageBirdClient($clientMock, 'test');

        $textMessageDtoMock = $this->createMock(TextMessageDto::class);

        $textMessageResponse = $messageBirdClient->sendMessage($textMessageDtoMock);

        $this->assertEquals($textMessageResponse, new TextMessageResponse('error'));
    }

    /**
     * @return MockObject|Client
     */
    private function getClientMock(?\Throwable $exception = null): MockObject
    {
        $conversationSendMock = $this->createMock(Send::class);

        if ($exception) {
            $conversationSendMock
                ->expects(self::once())
                ->method('send')
                ->willThrowException($exception);
        } else {
            $conversationSendMock
                ->expects(self::once())
                ->method('send')
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
