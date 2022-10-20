<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\ApiAdresseDataGouvService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EventController extends AbstractController
{
    /**
     * Créer un évènement 
     * @Route("/eventcreate", name="create")
     */
    public function create(ManagerRegistry $doctrine, Request $request, Event $event = null): Response
    {
        $event = new Event;
        $entityManager = $doctrine->getManager();
        $createEvent = $this->createform(EventType::class, $event);
        $createEvent->handleRequest($request);

        if ($createEvent->isSubmitted() && $createEvent->isValid()) {
            $event = $createEvent->getData();
            // recupère l'objet user
            $user = $this->getUser();
            // bdd
            $event->setUser($user);
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash("message", "Votre évènement est créée !");
            return $this->redirectToRoute('app_profil', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('event/index.html.twig', [
            'createEvent' => $createEvent->createView(),
        ]);
    }
}
