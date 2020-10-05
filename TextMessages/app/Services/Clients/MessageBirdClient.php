<?php

namespace App\Services\Clients;

use App\Dto\TextMessageDto;
use MessageBird\Client;
use MessageBird\Exceptions\AuthenticateException;
use Illuminate\Support\Facades\Log;
use MessageBird\Exceptions\MessageBirdException;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\SendMessage;
use MessageBird\Objects\Conversation\SendMessageResult;

class MessageBirdClient implements TextMessageClientInterface
{
    private Client $client;

    private ?string $channelId;

    public function __construct(Client $client, ?string $channelId)
    {
        $this->client     = $client;
        $this->channelId  = $channelId;
    }

    public function sendMessage(TextMessageDto $textMessageDto): TextMessageResponse
    {
        $sendMessage = $this->getSendMessageObject($textMessageDto);

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

    private function getSendMessageObject(TextMessageDto $textMessageDto): SendMessage
    {
        $content       = new Content();
        $content->text = $textMessageDto->getMessage();

        $sendMessage          = new SendMessage();
        $sendMessage->type    = 'text';
        $sendMessage->from    = $this->channelId;
        $sendMessage->to      = $textMessageDto->getPhoneNumber();
        $sendMessage->content = $content;

        return $sendMessage;
    }
}
