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
     * barre de recherche multi filtre
     * @Route("/", name="app_home")
     */
    public function index(EventRepository $repository, Request $request): Response
    {
        $searchEvent = $this->createForm(SearchForm::class, null);
        // crée le formulaire configuré dans le dossier FORM
        $searchEvent->handleRequest($request);
        // traite les données du formulaire


        if ($searchEvent->isSubmitted() && $searchEvent->isValid()) {
            // si le formulaire est envoyé et validé alors :
            $events = $repository->findSearch($searchEvent->getData());
            // on passe le formulaire a la fonction du repository qui est un tableau classic : EventRepository.php
        };

        // $events = $doctrine->getRepository(Event::class)->findBy([], ['date_event' => 'DESC']);

        // $entityManager = $doctrine->getManager();
        // $createEvent = $this->createform(EventType::class, $event);
        // $createEvent->handleRequest($request);

        // $futurevents = $doctrine->getRepository(Event::class)->FuturEvent();
        // $pastevents = $doctrine->getRepository(Event::class)->PastEvent();
        // $presentevents = $doctrine->getRepository(Event::class)->PresentEvent();

        // if ($createEvent->isSubmitted() && $createEvent->isValid()) {
        //     $event = $createEvent->getData();
        //     // recupère l'objet user
        //     $user = $this->getUser();
        //     // bdd
        //     $event->setUser($user);
        //     $entityManager->persist($event);
        //     $entityManager->flush();

        // return $this->redirectToRoute('app_home');
        // }

        return $this->render('home/index.html.twig', [
            // 'futurevents' => $futurevents,
            // 'pastevents' => $pastevents,
            // 'presentevents' => $presentevents,
            // 'createEvent' => $createEvent->createView(),
            'events' => isset($events) ? $events : null,
            'searchEvent' => $searchEvent->createView()
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
     * s'inscrire à un évènement
     * @Route("/home/participate/{idEvent}", name="participate_event")
     *
     * @ParamConverter("event", options={"mapping": {"idEvent" : "id"}})
     */
    public function participate(ManagerRegistry $doctrine, Event $event)
    {
        if ($event->getNbPlaces() > 0) { // si il reste de la place
            $event->addParticipant($this->getUser()); // ajout d'un user
            $doctrine->getManager()->flush(); // bdd
        }

        return $this->redirectToRoute('app_home'); // redirection vers la page d'accueil 
    }

    /**
     * se désinscrire d'un evenement
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

    /**
     * ajout d'un commentaire à un évnènement 
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
     * Supprimer un commentaire
     * @Route("/home/deleteComment/{idEvent}/{idComment}", name="delete_comment")
     * 
     * @ParamConverter("event", options={"mapping" = {"idEvent" : "id"}})
     * @ParamConverter("comment", options={"mapping" = {"idComment" : "id"}})
     */
    public function deleteComment(ManagerRegistry $doctrine, Comment $comment, Event $event)
    {
        $entityManager = $doctrine->getManager();
        $event->removeComment($comment);

        $entityManager->persist($event);
        $entityManager->flush();
        return $this->redirectToRoute('show_event', ['id' => $event->getId()]);
    }
}
