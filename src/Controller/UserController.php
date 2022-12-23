<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\LeconRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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

    //Planning
    #[Route('/user/planning', name: 'app_user_planning', methods: ['GET'])]
    public function planningUser(UserRepository $userRepository, LeconRepository $leconRepository): Response
    {
        $event= $leconRepository->findAll();
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
    #[Route('/user/graph', name: 'app_user_count', methods: ['GET'])]
    public function countUser(UserRepository $userRepository): Response
    {
        $result = $userRepository->blabla();

        //dd($result);
        return $this->render('user/graph.html.twig', [
            'result'=>$result
        ]);
    }



    //conflit git
}
