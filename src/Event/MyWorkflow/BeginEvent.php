<?php

namespace App\Event\MyWorkflow;

use Symfony\Contracts\EventDispatcher\Event;

class BeginEvent extends Event
{
    public const NAME = 'my_workflow.begin';

    public function __construct(private ?string $message)
    {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
