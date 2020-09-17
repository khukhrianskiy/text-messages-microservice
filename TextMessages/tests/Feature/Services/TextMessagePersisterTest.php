<?php

namespace Tests\Feature\Services;

use App\Models\TextMessage;
use App\Services\TextMessagePersister;
use Tests\TestCase;

class TextMessagePersisterTest extends TestCase
{
    /**
     * @test
     */
    public function testSave(): void
    {
        $textMessage = TextMessage::factory()->make();

        $this->assertNull($textMessage->id);

        $persister = new TextMessagePersister();

        $persister->save($textMessage);

        $this->assertIsInt($textMessage->id);
    }

    /**
     * @test
     */
    public function testUpdateStatus(): void
    {
        $textMessage = TextMessage::factory()->create();

        $persister = new TextMessagePersister();

        $persister->updateStatus($textMessage, 'sent');

        $this->assertDatabaseHas('text_messages', [
            'id'     => $textMessage->id,
            'status' => 'sent',
        ]);
    }
}
