<?php

namespace Database\Factories;

use App\Models\TextMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TextMessageFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = TextMessage::class;

    public function definition(): array
    {
        return [
            'body'         => 'Text message body',
            'status'       => 'new',
            'phone_number' => 'test_phone_number',
        ];
    }
}
