<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoniteurController extends AbstractController
{
    #[Route('/moniteur', name: 'app_moniteur')]
    public function index(): Response
    {
        return $this->render('moniteur/index.html.twig', [
            'controller_name' => 'MoniteurController',
        ]);
    }
}
