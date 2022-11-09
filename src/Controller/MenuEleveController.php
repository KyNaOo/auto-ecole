<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuEleveController extends AbstractController
{
    #[Route('/menu/eleve', name: 'app_menu_eleve')]
    public function index(): Response
    {
        return $this->render('menu_eleve/index.html.twig', [
            'controller_name' => 'MenuEleveController',
        ]);
    }
}
