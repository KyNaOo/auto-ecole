<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'login')]
     public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
                         'last_username' => $lastUsername,
                         'error'         => $error,
        ]);

    }

    #[Route('/redirect', name:'app_redirect')]
    public function redirect_section(){
        if($this->getUser()){
            $user=$this->getUser();
            switch ($user->getRoles()[0]){
                case "ROLE_ADMIN":
                    return $this->redirectToRoute('app_admin');
                case "ROLE_MONITEUR":
                    return $this->redirectToRoute('');
                case "ROLE_USER":
                    return $this->redirectToRoute('app_user');
            }
        }
        return $this->redirectToRoute('login');
    }
}
