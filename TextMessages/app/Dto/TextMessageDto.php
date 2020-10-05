<?php

namespace App\Dto;

class TextMessageDto
{
    private string $message;

    private string $phoneNumber;

    private string $status;

    public function __construct(string $message, string $phoneNumber)
    {
        $this->message     = $message;
        $this->phoneNumber = $phoneNumber;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
