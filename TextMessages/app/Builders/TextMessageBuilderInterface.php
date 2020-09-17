<?php

namespace App\Builders;

use App\Models\TextMessage;

interface TextMessageBuilderInterface
{
    public function create(): self;

    public function setPhoneNumber(string $phoneNumber): self;

    public function buildBody(): self;

    public function get(): TextMessage;
}
