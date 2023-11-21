<?php

namespace App\Event\MyWorkflow;

use Symfony\Contracts\EventDispatcher\Event;

final class TerminateEvent extends Event
{
    public const NAME = 'my_workflow.terminate';

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
