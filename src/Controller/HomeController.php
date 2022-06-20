<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, Request $request, Event $event = null): Response
    {
        $events = $doctrine->getRepository(Event::class)->findBy([], ['date_event' => 'DESC']);

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = $form->getData();
            // recupère l'objet user
            $user = $this->getUser();
            // bdd
            $event->setUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'formEvent' => $form->createView(),
            'events' => $events

        ]);
        // }
        // /**
        //  * @Route("/home/unsubscribe/{idSession}/{idStagiaire}", name="delete_stagiaire_session")
        //  * 
        //  * @ParamConverter("session", options={"mapping" = {"idSession" : "id"}})
        //  * @ParamConverter("stagiaire", options={"mapping" = {"idStagiaire" : "id"}})
        //  */
        // public function unsubscribe(ManagerRegistry $doctrine, User $user, Event $event)
        // {
        //     $entityManager = $doctrine->getManager();
        //     $session->removeStagiaire($stagiaire);
        //     $entityManager->persist($session);
        //     $entityManager->flush();
        //     return $this->redirectToRoute('home/index.html.twig', ['id' => $session->getId()]);
        // }
        // /**
        //  * @Route("/session/participate/{idSession}/{idStagiaire}", name="programmer_session")
        //  * 
        //  * @ParamConverter("session", options={"mapping": {"idSession" : "id"}})
        //  * @ParamConverter("stagiaire", options={"mapping": {"idStagiaire" : "id"}})
        //  */
        // public function participate(ManagerRegistry $doctrine, Session $session, Stagiaire $stagiaire)
        // {
        //     if ($session->getPlaceRestante() > 0) {
        //         $session->addStagiaire($stagiaire);
        //         $doctrine->getManager()->flush();
        //         return $this->redirectToRoute('home/index.html.twig', ['id' => $session->getId()]);
        //     }
    }
}
