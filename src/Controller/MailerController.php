<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mailer', name: 'mailer_')]
class MailerController extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer
    )
    {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        dump($this->getParameter('kernel.project_dir'));

        $email = (new TemplatedEmail())
            ->from("webmaster@dawan.fr")
            ->to("smenut@dawan.fr")
            ->subject("Bienvenue sur notre plateforme")
            ->htmlTemplate('email/welcome.html.twig')
            ->context(["name" => "John doe"])
            ->addPart(new DataPart(new File($this->getParameter('kernel.project_dir') . "/public/build/images/grogu.jpg")))
        ;

        $this->mailer->send($email);

        return $this->render('mailer/index.html.twig');
    }
}
