<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="app_comment")
     */
    public function index(): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $user = $this->getUser();
            $now = new \DateTime();
            $comment->setUsers($user);
            $comment->setCreateComment($now);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil', ["id" => $this->getUser()->getId()]);
        }

        return $this->render('comment/index.html.twig', [
            'formComment' => $form->createView(),
        ]);
    }
}
