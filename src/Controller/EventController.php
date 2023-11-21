<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $name = $request->attributes->get('name');

        return $this->render('event/index.html.twig', [
            'name' => $name
        ]);
    }

    #[Route('/new-way', name: 'new_way')]
    public function newWay(): Response
    {
        return $this->render('event/new-way.html.twig');
    }
}
