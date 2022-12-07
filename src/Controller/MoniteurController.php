<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class MoniteurController extends AbstractController
{
    #[Route('/moniteur', name: 'app_moniteur')]
    public function index(): Response
    {
        return $this->render('moniteur/index.html.twig', [
            'controller_name' => 'MoniteurController',
        ]);
    }

    #[Route('/moniteur/edit', name: 'app_moniteur_edit')]
    public function editUser(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Changement validé!');

        return $this->render('user/editUser.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }
    #[Route('/moniteur/edit/pass', name: 'app_moniteur_editPass')]
    public function editUserMDP(\Symfony\Component\HttpFoundation\Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser();

            if ($request->request->get('pass') != '' && $request->request->get('pass') === $request->request->get('pass2')) {
                $user->setPassword($passwordHasher->hashPassword($user, $request->request->get('pass')));
                $userRepository->save($user, true);
                $this->addFlash('message', 'Mot de passe mis à jour avec succès!');

                return $this->render('user/index.html.twig');
            } else {
                $this->addFlash('error', 'les deux mots de passe ne sont pas identiques ou vide');
            }
        }
        return $this->render('user/editUserMDP.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/moniteur/add/licence', name: 'app_moniteur_add_licence')]
    public function addLicence(): Response
    {
        return $this->render('moniteur/addLicence.html.twig', [
            'controller_name' => 'MoniteurController',
        ]);
    }
}
