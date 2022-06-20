<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\EditProfilType;
use App\Form\EditRegisterType;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="app_profil")
     */
    public function index(User $user): Response
    {
        $event = $user->getEvents();
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'events' => $event
        ]);
    }

    /**
     * @Route("/profil/editProfil/{id}", name="edit_profil")
     */
    public function editProfil(ManagerRegistry $doctrine, User $user, Request $request, SluggerInterface $slugger): Response
    {
        // $user = new User();
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

            return $this->redirectToRoute('app_profil', ["id" => $this->getUser()->getId()]);
        }

        return $this->render('profil/edit_profil.html.twig', [
            'editProfilForm' => $form->createView()
        ]);

        if (!$user) {
            $user = new User();
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView()
            ]);
        }
    }

    /**
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

            return $this->redirectToRoute('app_profil', ["id" => $this->getUser()->getId()]);
        }

        return $this->render('profil/edit_register.html.twig', [
            'editRegisterForm' => $form->createView()
        ]);

        if (!$user) {
            $user = new User();
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView()
            ]);
        }
    }
    /**
     * @Route("/profil/delete/{id}", name="delete_profil")
     */
    public function delete(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute("app_accueil");
    }
}
