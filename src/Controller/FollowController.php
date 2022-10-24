<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/unfollow/{id}", name="delete_follow")
     * @ParamConverter("user", options={"mapping" = {"idTarget" : "id"}})
     */
    public function deleteFollow(ManagerRegistry $doctrine, User $target, Request $request)
    {
        $entityManager = $doctrine->getManager();
        $target->removeFollower($this->getUser());
        $entityManager->persist($target);
        $entityManager->flush();
        $this->addFlash("message", "Tu t'es désabonné.");

        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}
