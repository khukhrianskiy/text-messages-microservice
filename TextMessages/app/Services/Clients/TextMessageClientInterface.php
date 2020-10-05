<?php

namespace App\Services\Clients;

use App\Dto\TextMessageDto;

interface TextMessageClientInterface
{
    public function sendMessage(TextMessageDto $textMessageDto): TextMessageResponse;
}
