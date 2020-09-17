<?php

namespace App\Services\Clients;

use App\Models\TextMessage;

interface TextMessageClientInterface
{
    public function sendMessage(TextMessage $textMessage): TextMessageResponse;
}
