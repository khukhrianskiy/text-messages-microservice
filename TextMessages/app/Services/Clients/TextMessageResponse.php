<?php

namespace App\Services\Clients;

class TextMessageResponse
{
    private string $status;

    public function __construct(string $status = 'error')
    {
        $this->status  = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
