<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\EventType;
use App\Form\SearchForm;
use App\Form\CommentType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, EventRepository $repository, Request $request, Event $event = null): Response
    {
        $form = $this->createForm(SearchForm::class, null);
        // crée le formulaire configuré dans le dossier FORM
        $form->handleRequest($request);
        // traite les données du formulaire


        if ($form->isSubmitted() && $form->isValid()) {
            // si le formulaire est envoyé et validé alors :
            $events = $repository->findSearch($form->getData());
            // on passe le formulaire a la fonction du repository qui est un tableau classic : EventRepository.php
        };

        $events = $doctrine->getRepository(Event::class)->findBy([], ['date_event' => 'DESC']);

        $entityManager = $doctrine->getManager();
        $formEvent = $this->createform(EventType::class, $event);
        $formEvent->handleRequest($request);

        // $futurevents = $doctrine->getRepository(Event::class)->FuturEvent();
        // $pastevents = $doctrine->getRepository(Event::class)->PastEvent();
        // $presentevents = $doctrine->getRepository(Event::class)->PresentEvent();

        if ($formEvent->isSubmitted() && $formEvent->isValid()) {
            $event = $formEvent->getData();
            // recupère l'objet user
            $user = $this->getUser();
            // bdd
            $event->setUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            // 'futurevents' => $futurevents,
            // 'pastevents' => $pastevents,
            // 'presentevents' => $presentevents,
            'events' => isset($events) ? $events : null,
            'formEvent' => $formEvent->createView(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/condition-generale-utilisation", name="app_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig', [
            'titre' => ' - Condition générale d\'utilisation',
        ]);
    }

    /**
     * @Route("/home/{id}", name="show_event")
     */
    public function show(ManagerRegistry $doctrine, Request $request, Event $event, Comment $comment = null): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $now = new \DateTime();
            $event->addComment($comment);
            $comment->setUsers($this->getUser());
            $comment->setCreateComment($now);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_event', ['id' => $event->getId()]);
        }

        return $this->render('home/show.html.twig', [
            'event' => $event,
            'formComment' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/participate/{idEvent}", name="participate_event")
     *
     * @ParamConverter("event", options={"mapping": {"idEvent" : "id"}})
     */
    public function participate(ManagerRegistry $doctrine, Event $event)
    {
        if ($event->getNbPlaces() > 0) {
            $event->addParticipant($this->getUser());
            $doctrine->getManager()->flush();
        }

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/home/unsubscribe/{idEvent}", name="unsubscribe")
     *
     * @ParamConverter("event", options={"mapping" = {"idEvent" : "id"}})
     */
    public function unsubscribe(ManagerRegistry $doctrine, Event $event)
    {
        $entityManager = $doctrine->getManager();
        $event->removeParticipant($this->getUser());
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('show_event', ['id' => $event->getId()]);
    }
}
