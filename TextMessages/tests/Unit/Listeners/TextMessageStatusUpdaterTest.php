<?php

namespace Tests\Unit\Listeners;

use App\Events\TextMessageStatusUpdate;
use App\Listeners\TextMessageStatusUpdater;
use App\Models\TextMessage;
use App\Services\TextMessagePersister;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Mockery;
use PHPUnit\Framework\TestCase;

class TextMessageStatusUpdaterTest extends TestCase
{
    use InteractsWithDatabase;

    /**
     * @test
     */
    public function testHandle(): void
    {
        $textMessage = new TextMessage();

        /** @var Mockery\MockInterface|TextMessagePersister $textMessagePersisterMock */
        $textMessagePersisterMock = Mockery::mock(TextMessagePersister::class);
        $textMessagePersisterMock->expects('updateStatus')->with($textMessage, 'sent');

        $textMessageStatusUpdater = new TextMessageStatusUpdater($textMessagePersisterMock);

        $event = new TextMessageStatusUpdate($textMessage, 'sent');

        $textMessageStatusUpdater->handle($event);

        $this->assertTrue(true);
    }
}
