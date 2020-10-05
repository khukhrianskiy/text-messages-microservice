<?php

namespace App\Jobs;

use App\Factories\OrderDeliveredMessageDtoFactory;
use App\Services\TextMessagePersister;
use App\Services\TextMessageSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDeliveredTextMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function handle(
        OrderDeliveredMessageDtoFactory $orderDeliveredMessageDtoFactory,
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister
    ): void {
        $textMessageDto = $orderDeliveredMessageDtoFactory
            ->create($this->phoneNumber);

        $status = $textMessageSender->send($textMessageDto);

        $textMessageDto->setStatus($status);

        $textMessagePersister->saveFromDto($textMessageDto);
    }
}
