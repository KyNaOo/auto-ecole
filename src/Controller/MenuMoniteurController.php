<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuMoniteurController extends AbstractController
{
    #[Route('/menu/moniteur', name: 'app_menu_moniteur')]
    public function index(): Response
    {
        return $this->render('menu_moniteur/index.html.twig', [
            'controller_name' => 'MenuMoniteurController',
        ]);
    }
}
