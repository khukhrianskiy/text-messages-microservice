<?php

namespace App\Jobs;

use App\Builders\TextMessageDirector;
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
        TextMessageDirector $textMessageDirector,
        TextMessageSender $textMessageSender,
        TextMessagePersister $textMessagePersister
    ): void {
        $textMessage = $textMessageDirector
            ->buildOrderDeliveredMessage($this->phoneNumber)
            ->get();

        $textMessagePersister->save($textMessage);
        $textMessageSender->send($textMessage);
    }
}
