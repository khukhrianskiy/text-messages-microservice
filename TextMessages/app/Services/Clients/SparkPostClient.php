<?php

namespace App\Services\Clients;

use App\Dto\TextMessageDto;
use Illuminate\Support\Facades\Log;
use SparkPost\SparkPost;
use SparkPost\SparkPostPromise;

class SparkPostClient implements TextMessageClientInterface
{
    private const DEFAULT_RECIPIENT_NAME = 'Customer';

    private SparkPost $sparkPost;

    private array $from;

    public function __construct(SparkPost $sparkPost, array $from)
    {
        $this->sparkPost = $sparkPost;
        $this->from      = $from;
    }

    public function sendMessage(TextMessageDto $textMessageDto): TextMessageResponse
    {
        try {
            $this->sparkPost->transmissions->post($this->getParameters($textMessageDto));

            $response = $this->sparkPost->transmissions->get();

            return $this->prepareResponse($response);
        } catch (\Exception $exception) {
            Log::error("Something went wrong. {$exception->getMessage()}");
        }

        return new TextMessageResponse();
    }

    private function getParameters(TextMessageDto $textMessageDto): array
    {
        return [
            'content' => [
                'from' => $this->from,
                'subject' => $textMessageDto->getSubject(),
                'text' => $textMessageDto->getMessage(),
            ],
            'recipients' => [
                [
                    'address' => [
                        'name' => self::DEFAULT_RECIPIENT_NAME,
                        'email' => $textMessageDto->getPhoneNumber(),
                    ],
                ],
            ],
        ];
    }

    private function prepareResponse(SparkPostPromise $response): TextMessageResponse
    {
        return new TextMessageResponse($response->getState());
    }
}
