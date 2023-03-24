<?php

namespace App\Controller;

use App\Entity\Licence;
use App\Entity\User;
use App\Form\LicenceType;
use App\Repository\LeconRepository;
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

    #[Route('/moniteur/addLicence', name: 'app_addLicence')]
    public function addLicence(\Symfony\Component\HttpFoundation\Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
       // dd($user->getId());
        $licence = new Licence();
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $licence->setCodeuser($user);
            $entityManager->persist($licence);
            $entityManager->flush();
            $this->addFlash('message', 'Ajout effectué');
        }

        return $this->renderForm('moniteur/addLicence.html.twig', [
            'licence' => $licence,
            'form' => $form
        ]);
    }

    #[Route('/moniteur/stats', name: 'app_moniteur_stats', methods: ['GET'])]
    public function statsMoniteur(UserRepository $userRepository): Response
    {
        $result1 = $userRepository->getNbLeconByMoniteur($this->getUser()->getId());
        $result2 = $userRepository->getCATotByMoniteur($this->getUser()->getId());
        $result3 = $userRepository->getNbLeconByMoniByCateg($this->getUser()->getId());
        $nbLecon = $result1[0]["nbLecon"];
        $CATot = $result2[0]["CATot"];
        return $this->render('user/statsMoniteur.html.twig', [
            'nbLecon'=>$nbLecon,
            'CATot'=>$CATot,
            'categories'=>$result3,
        ]);
    }

    #[Route('/moniteur/graph', name: 'app_moniteur_graph', methods: ['GET'])]
    public function graphMoni(UserRepository $userRepository): Response
    {
        $result = $userRepository->prixCateg($this->getUser()->getId());
        $result2 = $userRepository->nbLeconCateg($this->getUser()->getId());
        //dd($result);
        return $this->render('moniteur/graph.html.twig', [
            'result'=>$result,
            'result2'=>$result2

        ]);
    }
    //Planning
    #[Route('/user/planning', name: 'app_user_planningMoniteur', methods: ['GET'])]
    public function planningUser(UserRepository $userRepository, LeconRepository $leconRepository): Response
    {
        $event = $leconRepository->userCalendar($this->getUser()->getId());
        //$event= $leconRepository->findByExampleField($this->getUser()->getId());
        $lecon = [];
        foreach ($event as $event){
            $lecon[]=[
                'id'=>$event->getId(),
                'start'=>$event->getDateStart()->format('Y-m-d H:i:s'),
                'end'=>$event->getDateEnd()->format('Y-m-d H:i:s'),
                'title'=>"COURS",
                'backgroundColor'=>"rgb(0, 255, 0)",
                'borderColor'=>"pink",
                'textColor'=>"black"
            ];
        }
        $data=json_encode($lecon);
        return $this->render('user/planningEleve.html.twig',compact('data')
        );
    }
}
//correction conflit
