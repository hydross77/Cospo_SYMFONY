<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="app_message")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $received = $doctrine->getRepository(Message::class)->findBy(["recipient" => $this->getUser()], ['created_at' => 'DESC']);
        return $this->render('message/index.html.twig', [
            'received' => $received,
        ]);
    }

    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request, ManagerRegistry $doctrine): Response
    {
        $message = new Message;
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("app_message");
        }

        return $this->render("message/send.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/received", name="received")
     */
    public function received(ManagerRegistry $doctrine): Response
    {
        $received = $doctrine->getRepository(Message::class)->findBy(['created_at' => 'DESC']);
        return $this->render('message/index.html.twig', [
            'received' => $received,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(ManagerRegistry $doctrine, Message $message): Response
    {

        $message->setIsRead(true);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->render('message/read.html.twig', compact("message"));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ManagerRegistry $doctrine, Message $message): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($message);
        $entityManager->flush();
        $this->addFlash("message", "Message supprimé.");

        return $this->redirectToRoute("app_message");
    }

    /**
     * @Route("/sent", name="sent")
     */
    public function sent(ManagerRegistry $doctrine): Response
    {
        $sender = $doctrine->getRepository(Message::class)->findBy(["sender" => $this->getUser()], ['created_at' => 'DESC']);
        return $this->render('message/sent.html.twig', [
            'sender' => $sender,
        ]);
    }
}
