<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\TextMessage;
use App\Services\TextMessageSender;
use Mockery;
use Tests\TestCase;

class TextMessageControllerTest extends TestCase
{
    /**
     * @test
     */
    public function testSendOrderConfirmation(): void
    {
        $this->instance(TextMessageSender::class, Mockery::mock(TextMessageSender::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('send')->twice();
        }));

        $response = $this->postJson(route('text-messages.send-order-confirmation'), [
            'restaurant_name' => 'Text restaurant',
            'delivery_time'   => '2020-09-23 09:36',
            'phone_number'    => 'test_number_name',
        ]);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function testSendOrderConfirmationWithInvalidParams(): void
    {
        $response = $this->postJson(route('text-messages.send-order-confirmation'), [
            'restaurant_name' => null,
            'delivery_time'   => null,
            'phone_number'    => null,
        ]);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'restaurant_name',
                'delivery_time',
                'phone_number',
            ],
        ]);
    }

    /**
     * @test
     */
    public function testLatest(): void
    {
        TextMessage::factory()->count(60)->create();

        $response = $this->getJson(route('text-messages.latest', ['limit' => 50]));

        $response->assertStatus(200);
        $response->assertJsonCount(50);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'body',
                'status',
                'phone_number',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * @test
     */
    public function testLatestFails(): void
    {
        TextMessage::factory()->count(60)->create();

        $response = $this->getJson(route('text-messages.latest', ['limit' => 'failed']));

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'limit',
            ],
        ]);
    }

    /**
     * @test
     */
    public function testFailed(): void
    {
        TextMessage::factory()->count(30)->create();

        TextMessage::factory()->state([
            'status' => TextMessage::STATUS_DELIVERED,
        ])->count(10)->create();

        $this->assertDatabaseCount('text_messages', 40);

        $response = $this->getJson(route('text-messages.failed'));

        $response->assertStatus(200);
        $response->assertJsonCount(30);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'body',
                'status',
                'phone_number',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
