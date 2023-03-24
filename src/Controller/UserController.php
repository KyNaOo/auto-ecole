<?php

namespace App\Controller;

use App\Entity\Lecon;
use App\Entity\User;
use App\Form\EditUserType;
use App\Form\LeconType;
use App\Repository\LeconRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/edit', name: 'app_user_edit')]
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

    #[Route('/user/edit/pass', name: 'app_user_editPass')]
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

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don t forget to activate logout in security.yaml');
    }
    #[Route('/{id}/newLecon', name: 'app_lecon_new_eleve', methods: ['GET', 'POST'])]
    public function newLecon(Request $request, User $user, LeconRepository $leconRepository,UserRepository $userRepository): Response
    {
        $user= $this->getUser();
        $lecon = new Lecon();
        $form = $this->createForm(LeconType::class, $lecon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $moniteur=$userRepository->findOneBy(['id'=>$request->request->get('lecon')['codeuser']]);
            $lecon->addCodeuser($user);
            $lecon->addCodeuser($moniteur);
            $leconRepository->save($lecon, true);
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lecon/newLecon.html.twig', [
            'lecon' => $lecon,
            'form' => $form,
            'user'=>$user
        ]);
    }

    //Planning
    #[Route('/user/planning', name: 'app_user_planning', methods: ['GET'])]
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



    //graph
    #[Route('/user/graph', name: 'app_user_graph', methods: ['GET'])]
    public function countUser(UserRepository $userRepository): Response
    {
        $result = $userRepository->nbCategorie($this->getUser()->getId());
        $result2 = $userRepository->nbLeconMoniteur($this->getUser()->getId());
//        dd($result2);

//        foreach ($result2 as $lecon){
//            foreach ($lecon->getCodeuser() as $user){
//                dump($user);
//            }
//
//        }
        return $this->render('user/graph.html.twig', [
            'result'=>$result,
            'result2'=>$result2
        ]);
    }

    #[Route('/user/stats', name: 'app_user_stats', methods: ['GET'])]
    public function statsUser(UserRepository $userRepository): Response
    {

        $result1 = $userRepository->getMontantPermis($this->getUser()->getId());
        $result2 = $userRepository->getMontantRestant($this->getUser()->getId());
        $result3 = $userRepository->getNbLeconByEleve($this->getUser()->getId());
        $result4 = $userRepository->getVehiculeUseByEleve($this->getUser()->getId());
//        dd($result4);
        $boolCountVeh = false;
        $nbLecon = $result3[0]["nbLecon"];
        $prixRestant = $result2[0]["prixRestant"];
        $prixPermis = $result1[0]["prix"];
        if (count($result4)>1){
            $boolCountVeh = true;
        }
        $countVeh = $result4[0]['nbLecon'];
        return $this->render('user/stats.html.twig', [
            'prix'=>$prixPermis,
            'prixRestant'=>$prixRestant,
            'nbLecon'=>$nbLecon,
            'vehicules'=>$result4,
            'verifVeh'=>$boolCountVeh,
            'nbUseVeh'=>$countVeh,
        ]);
    }
}
