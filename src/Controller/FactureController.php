<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Repository\ProjetRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FactureRepository $factureRepository,ProjetRepository $projetRepository): Response
    {
        $facture = new Facture();
        $facture->setDateFacture(new DateTime('now'));

        $projets = $projetRepository->findProjetSansFacture();
        
        $form = $this->createForm(FactureType::class, $facture,["projets"=>$projets]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }
   
    #[Route('/new/{projet_id<\d+>}', name: 'app_facture_new_from_projet', methods: ['GET', 'POST'])]
    public function newFromProjet(Request $request, FactureRepository $factureRepository,ProjetRepository $projetRepository, $projet_id): Response
    {
        $facture = new Facture();
        $facture->setDateFacture(new DateTime('now'));
        if(isset($projet_id) && $projet_id != 0) {
            $projet = $projetRepository->find($projet_id);
            $facture->setProjet($projet);
            $facture->setTotal($projet->getTotal());
        }; 
        $projets = $projetRepository->findProjetSansFacture();

        $form = $this->createForm(FactureType::class, $facture,["projets"=>$projets]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factureRepository->save($facture, true);

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, FactureRepository $factureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $factureRepository->remove($facture, true);
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
