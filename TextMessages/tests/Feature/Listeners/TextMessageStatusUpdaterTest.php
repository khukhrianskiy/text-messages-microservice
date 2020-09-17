<?php

namespace Tests\Feature\Listeners;

use App\Events\TextMessageStatusUpdate;
use App\Listeners\TextMessageStatusUpdater;
use App\Models\TextMessage;
use App\Services\TextMessagePersister;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Tests\TestCase;

class TextMessageStatusUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function testHandle(): void
    {
        $textMessage = TextMessage::factory()->create();

        $textMessageStatusUpdater = new TextMessageStatusUpdater(new TextMessagePersister());

        $event = new TextMessageStatusUpdate($textMessage, 'sent');

        $textMessageStatusUpdater->handle($event);

        $this->assertDatabaseHas('text_messages', [
            'id'     => $textMessage->id,
            'status' => 'sent',
        ]);
    }
}
