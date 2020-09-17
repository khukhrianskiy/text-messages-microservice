<?php

namespace App\Listeners;

use App\Events\TextMessageStatusUpdate;
use App\Services\TextMessagePersister;

class TextMessageStatusUpdater
{
    private TextMessagePersister $textMessagePersister;

    public function __construct(TextMessagePersister $textMessagePersister)
    {
        $this->textMessagePersister = $textMessagePersister;
    }

    public function handle(TextMessageStatusUpdate $event): void
    {
        $this->textMessagePersister->updateStatus($event->getTextMessage(), $event->getStatus());
    }
}
