<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowController extends AbstractController
{
    /**
     * afficher les abonnées de l'utilisateur
     * @Route("/follow/{id}", name="app_follow")
     */
    public function index(User $user): Response
    {
        $follow = $user->getFollow();

        return $this->render('follow/index.html.twig', [
            'follow' => $follow,
        ]);
    }

    /**
     * se désabonner d'un utilisateur
     * @Route("/follow/follow/{id}", name="delete_follow")
     */
    public function deleteFollow(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash("message", "Tu t'es désabonné.");

        return $this->redirectToRoute('app_follow', ['id' => $this->getUser()->getId()]);
    }
}
