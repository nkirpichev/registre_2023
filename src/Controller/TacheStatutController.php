<?php

namespace App\Controller;

use App\Entity\TacheStatut;
use App\Form\TacheStatutType;
use App\Repository\TacheRepository;
use App\Repository\TacheStatutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

#[Route('/tache/statut')]
class TacheStatutController extends AbstractController
{
    #[Route('/', name: 'app_tache_statut_index', methods: ['GET'])]
    public function index(TacheStatutRepository $tacheStatutRepository): Response
    {
        return $this->render('tache_statut/index.html.twig', [
            'tache_statuts' => $tacheStatutRepository->findAll(),
        ]);
    }

    #[Route('/new/{tache_id<\d+>}', name: 'app_tache_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TacheStatutRepository $tacheStatutRepository, TacheRepository $tacheRepository,$tache_id): Response
    {
        $tacheStatut = new TacheStatut();
        $tacheStatut->setDateChangement(new DateTime('now'));
        $titleStatus = 'Statut actuel - undéfeni - changer statut en';
        if(isset($tache_id) && $tache_id != 0) {
            $tacheStatut->setTache($tacheRepository->find($tache_id));
             $titleStatus = 'Statut actuel - '.$tacheStatut->getTache()->getLastStatut().'. Changer statut en';
        }

        $form = $this->createForm(TacheStatutType::class, $tacheStatut, ['titleStatut' => $titleStatus]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tacheStatutRepository->save($tacheStatut, true);

            return $this->redirectToRoute('app_tache_show', ['id' => $tache_id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache_statut/new.html.twig', [
            'tache_statut' => $tacheStatut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tache_statut_show', methods: ['GET'])]
    public function show(TacheStatut $tacheStatut): Response
    {
        return $this->render('tache_statut/show.html.twig', [
            'tache_statut' => $tacheStatut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TacheStatut $tacheStatut, TacheStatutRepository $tacheStatutRepository): Response
    {
        $form = $this->createForm(TacheStatutType::class, $tacheStatut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tacheStatut->setDateChangement(new DateTime('now'));
            $tacheStatutRepository->save($tacheStatut, true);

            return $this->redirectToRoute('app_tache_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache_statut/edit.html.twig', [
            'tache_statut' => $tacheStatut,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_tache_statut_delete', methods: ['POST'])]
    public function delete(Request $request, TacheStatut $tacheStatut, TacheStatutRepository $tacheStatutRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tacheStatut->getId(), $request->request->get('_token'))) {
            $tacheStatutRepository->remove($tacheStatut, true);
        }

        return $this->redirectToRoute('app_tache_statut_index', [], Response::HTTP_SEE_OTHER);
    }
}
