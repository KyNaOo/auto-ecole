<?php

namespace App\Controller;

use App\Entity\Moniteur;
use App\Form\MoniteurType;
use App\Repository\MoniteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/moniteur')]
class MoniteurController extends AbstractController
{
    #[Route('/', name: 'app_moniteur_index', methods: ['GET'])]
    public function index(MoniteurRepository $moniteurRepository): Response
    {
        return $this->render('moniteur/index.html.twig', [
            'moniteurs' => $moniteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_moniteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MoniteurRepository $moniteurRepository): Response
    {
        $moniteur = new Moniteur();
        $form = $this->createForm(MoniteurType::class, $moniteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moniteurRepository->save($moniteur, true);

            return $this->redirectToRoute('app_moniteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moniteur/new.html.twig', [
            'moniteur' => $moniteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moniteur_show', methods: ['GET'])]
    public function show(Moniteur $moniteur): Response
    {
        return $this->render('moniteur/show.html.twig', [
            'moniteur' => $moniteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_moniteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Moniteur $moniteur, MoniteurRepository $moniteurRepository): Response
    {
        $form = $this->createForm(MoniteurType::class, $moniteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moniteurRepository->save($moniteur, true);

            return $this->redirectToRoute('app_moniteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moniteur/edit.html.twig', [
            'moniteur' => $moniteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moniteur_delete', methods: ['POST'])]
    public function delete(Request $request, Moniteur $moniteur, MoniteurRepository $moniteurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moniteur->getId(), $request->request->get('_token'))) {
            $moniteurRepository->remove($moniteur, true);
        }

        return $this->redirectToRoute('app_moniteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
