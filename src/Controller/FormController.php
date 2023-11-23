<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\MonthYearType;
use App\Form\PostType;
use App\Message\SavePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form', name: 'form_')]
class FormController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus
    )
    {
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $delay = (new \DateTime())
                ->setTime(12, 30, 00);

            // envoie le message vers le broker
            $this->bus->dispatch(
                new SavePost($post),
                [DelayStamp::delayUntil($delay)]
            );

            /*$this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', "L'article a bien été enregistré");*/

            $this->addFlash('success', "Votre demande d'enregistrement a bien été prise en compte et sera traitée plus tard");

            return $this->redirectToRoute('form_index');
        }

        return $this->render('form/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', "L'article a bien été modifié");

            return $this->redirectToRoute('form_edit', ['id' => $post->getId()]);
        }

        return $this->render('form/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/data-mapper', name: 'data_mapper')]
    public function dataMapper(Request $request): Response
    {
        $date = new \DateTime();
        $form = $this->createForm(MonthYearType::class, $date);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData();
        }

        return $this->render('form/data-mapper.html.twig', [
            'form' => $form,
            'date' => $date
        ]);
    }
}
