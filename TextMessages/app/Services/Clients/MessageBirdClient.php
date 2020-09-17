<?php

namespace App\Services\Clients;

use App\Factories\SendMessageFactory;
use App\Models\TextMessage;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use Illuminate\Support\Facades\Log;
use MessageBird\Exceptions\MessageBirdException;
use MessageBird\Objects\Conversation\SendMessageResult;

class MessageBirdClient implements TextMessageClientInterface
{
    private Client $client;

    private SendMessageFactory $factory;

    public function __construct(SendMessageFactory $factory, Client $client)
    {
        $this->factory = $factory;
        $this->client  = $client;
    }

    public function sendMessage(TextMessage $textMessage): TextMessageResponse
    {
        $sendMessage = $this->factory->fromTextMessage($textMessage);

        try {
            $response = $this->client->conversationSend->send($sendMessage);

            return $this->processResponse($response);
        } catch (AuthenticateException $exception) {
            Log::error("Authentication error: {$exception->getMessage()}");
        } catch (MessageBirdException $exception) {
            Log::critical("Message Bird error: {$exception->getMessage()}");
        } catch (\Throwable $exception) {
            Log::critical("Unknown error: {$exception->getMessage()}");
        }

        return new TextMessageResponse();
    }

    private function processResponse(SendMessageResult $sendMessageResult): TextMessageResponse
    {
        return new TextMessageResponse($sendMessageResult->status);
    }
}
