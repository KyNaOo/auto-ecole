<?php

namespace App\Controller;

use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/edit', name: 'app_admin_edit')]
    public function editUser(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('message', 'Changement validé!');

        return $this->render('admin/editAdmin.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/stats', name: 'app_admin_edit', methods: ['GET'])]
    public function statAdmin(UserRepository $userRepository): Response
    {
        $result1 = $userRepository->getMoniteurMaxUse();
        $result2 = $userRepository->getVehiculeMaxUse();

        return $this->render('admin/statsAdmin.html.twig', [
            'moniteurs'=>$result1,
            'vehicules'=>$result2,
        ]);
    }


}
