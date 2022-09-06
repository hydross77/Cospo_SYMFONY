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
        // crée le formulaire configuré dans le dossier FORM
        $form->handleRequest($request);
        // traite les données du formulaire


        if ($form->isSubmitted() && $form->isValid()) {
            // si le formulaire est envoyé et validé alors :
            $events = $repository->findSearch($form->getData());
            // on passe le formulaire a la fonction du repository qui est un tableau classic : EventRepository.php
        }

        return $this->render('event/index.html.twig', [
            'events' => isset($events) ? $events : null,
            'form' => $form->createView()
            //affiche le formulaire
        ]);
    }
}
