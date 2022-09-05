<?php

namespace App\Controller;

use App\Form\SearchForm;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="app_event")
     */
    public function index(EventRepository $repository, Request $request): Response
    {
        $form = $this->createForm(SearchForm::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            $events = $repository->findSearch($form->getData());
            dump($events);
        }

        return $this->render('event/index.html.twig', [
            'events' => isset($events) ? $events : null,
            'form' => $form->createView()
        ]);
    }
}
