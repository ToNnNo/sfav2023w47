<?php

namespace App\Service\Workflow;

use App\Event\MyWorkflow\BeginEvent;
use App\Event\MyWorkflow\TerminateEvent;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class MyWorkflow
{
    private ?string $message = null;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
    }

    public function run(string $message): void
    {
        $this->message = $message;

        $beginEvent = new BeginEvent($this->message);
        $this->dispatcher->dispatch($beginEvent, BeginEvent::NAME);

        $this->myFirstAction();
        $this->mySecondAction();

        $terminateEvent = new TerminateEvent($this->message);
        // le dispatcher envoie l'événement (MyWorkflowEvent) à tous les listener ou subscriber qui se sont abonnés à l'évènement nommé MyWorkflowEvent::TERMINATE
        // le dispatcher fait appel explicitement aux subscriber de notre application
        $this->dispatcher->dispatch($terminateEvent, TerminateEvent::NAME);
    }

    private function myFirstAction(): void
    {
        $this->message = ucfirst($this->message);
        $this->logger->info(sprintf("Première action sur le message: %s", $this->message));
    }

    private function mySecondAction(): void
    {
        $this->message .= " !";
        $this->logger->info(sprintf("Deuxième action sur le message: %s", $this->message));
    }

}
