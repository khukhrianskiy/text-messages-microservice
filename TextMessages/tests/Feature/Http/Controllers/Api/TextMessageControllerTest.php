<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Services\TextMessageSender;
use Mockery;
use Tests\TestCase;

class TextMessageControllerTest extends TestCase
{
    /**
     * @test
     */
    public function testSend(): void
    {
        $this->instance(TextMessageSender::class, Mockery::mock(TextMessageSender::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('send')->once();
        }));

        $response = $this->post(route('text-messages.send-order-confirmation'), [
            'restaurant_name' => 'Text restaurant',
            'delivery_time'   => '2020-09-23 09:36',
            'phone_number'    => 'test_number_name',
        ]);

        $response->assertStatus(200);
    }
}
