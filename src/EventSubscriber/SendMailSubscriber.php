<?php

namespace App\EventSubscriber;

use App\Event\MyWorkflow\TerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SendMailSubscriber implements EventSubscriberInterface
{
    public function __construct
    (
        private readonly MailerInterface $mailer
    )
    {
    }

    public function onTerminate(TerminateEvent $event): void
    {
        $email = (new Email())
            ->from(new Address('webmaster@dawan.fr', 'Dawan Webmaster'))
            ->to("smenut@dawan.fr")
            ->cc('david.delehelle@univ-reims.fr')
            ->subject('Formation Symfony Avancé')
            ->text("Bonjour,\nNous venons d'envoyer un email rattaché à un événement personnalisé.\n\nBonne journée :D")
            ->html("<p>Bonjour</p><p>Nous venons d'envoyer un email rattaché à un événement personnalisé.</p><p>Bonne journée :D</p>");

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // TerminateEvent::NAME => 'onTerminate',
        ];
    }
}
