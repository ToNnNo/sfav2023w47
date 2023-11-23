<?php

namespace App\MessageHandler;

use App\Message\SavePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class SavePostHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer
    )
    {
    }

    public function __invoke(SavePost $message): void
    {
        $post = $message->getPost();

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $email = (new Email())
            ->from('notification@dawan.fr')
            ->to('smenut@dawan.fr')
            ->replyTo('noreply@dawan.fr')
            ->subject("Nouvel article")
            ->text(sprintf("Un nouvel article (%s) vient d'être enregistré", $post->getTitle()));

        $this->mailer->send($email);
    }
}
