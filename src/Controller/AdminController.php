<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * Liste des utilisateurs du site
     * 
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function usersList(UserRepository $users) //injection de dépendance, donne accès aux méthodes
    {
        return $this->render("admin/index.html.twig", [
            'users' => $users->findAll()
        ]);
    }

    /**
     * @Route("/utilisateurs/delete/{id}", name="delete_utilisateur")
     */
    public function editUser(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_utilisateurs');
    }

    /**
     * Liste des événements pour un utilisateur
     * 
     * @Route("/events/{id}", name="events")
     */
    public function eventsList(User $user)
    {
        return $this->render("admin/events.html.twig", [
            'user' => $user,
        ]);
    }

    /**
     * Supprimer un evenement 
     * @Route("/events/delete/{id}", name="delete_event")
     */
    public function delete(ManagerRegistry $doctrine, Event $event)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute("admin_events", ['id' => $event->getId()]);
    }
}
