<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Form\EditProfilType;
use App\Form\EditRegisterType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="app_profil")
     */
    public function index(User $user, EventRepository $ef, EventRepository $ep, EventRepository $en): Response
    {

        if ($this->getUser() != $user) {
            return $this->render('profil/error.html.twig');
        } else {
            $eventFutur = $ef->FuturEvent();
            $eventPast = $ep->PastEvent();
            $eventNow = $en->NowEvent();
            $event = $user->getParticipate();
            $follow = $user->getFollow();
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'events' => $event,
            'eventNow' => $eventNow,
            'eventFutur' => $eventFutur,
            'eventPast' => $eventPast,
            'follow' => $follow,
        ]);
    }

    /**
     * Les évènements de l'utilisateur connecté
     * @Route("/profil/event/{id}", name="create_event")
     */
    public function createEvent(User $user): Response
    {
        $event = $user->getEvents();
        // méthode magique get : afficher la collection

        return $this->render('profil/user_event.html.twig', [
            'events' => $event,
        ]);
    }

    /**
     * supprimer son évènement
     * @Route("/profil/delete/event/{id}", name="delete_event")
     */
    public function deleteEvent(ManagerRegistry $doctrine, Event $event)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($event);
        $entityManager->flush();
        $this->addFlash("message", "Supprimé.");

        return $this->redirectToRoute('create_event', ['id' => $this->getUser()->getId()]);
    }



    /**
     * Le profil d'un utilisateur lambda
     * @Route("/profil!/view/{pseudo}", name="show_profil")
     *
     * @ParamConverter("user", options={"mapping": {"pseudo" : "pseudo"}})
     */
    public function show(Event $event, User $user): Response
    {
        $event = $user->getEvents();

        return $this->render('profil/show.html.twig', [
            'user' => $user,
            'events' => $event,
        ]);
    }

    /**
     * Modifier le profil (bio, pseudo etc..)
     * @Route("/profil/editProfil/{id}", name="edit_profil")
     */
    public function editProfil(ManagerRegistry $doctrine, User $user, Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureProfil = $form->get('picture_profil')->getData();

            if ($pictureProfil) {
                $originalFilename = pathinfo($pictureProfil->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureProfil->guessExtension();

                try {
                    $pictureProfil->move(
                        $this->getParameter('photo_profil'),
                        $newFilename
                    );
                } catch (FileException $error) {
                }
                $user->setPictureProfil($newFilename);
            }

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("message", "Profil modifié avec succès.");

            return $this->redirectToRoute('app_profil', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('profil/edit_profil.html.twig', [
            'editProfilForm' => $form->createView(),
        ]);

        if (!$user) {
            $user = new User();

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    }

    /**
     * Modifier le compte (mail, mot de passe)
     * @Route("/profil/editRegister/{id}", name="edit_register")
     */
    public function editRegister(ManagerRegistry $doctrine, User $user = null, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(EditRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil', ['id' => $this->getUser()->getId()]);
        }

        return $this->render('profil/edit_register.html.twig', [
            'editRegisterForm' => $form->createView(),
        ]);

        if (!$user) {
            $user = new User();

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    }

    /**
     * supprimer un profil
     * @Route("/profil/delete/{id}", name="delete_profil")
     */
    public function delete(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * s'abonner à un utilisateur
     * @Route("/profil/follow/{idUser}", name="user_follow")
     *
     * @ParamConverter("user", options={"mapping": {"idUser" : "id"}})
     */
    public function follow(ManagerRegistry $doctrine, User $user, Request $request)
    {
        $user->addFollower($this->getUser()); // ajout d'un user
        $doctrine->getManager()->flush(); // bdd
        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}
