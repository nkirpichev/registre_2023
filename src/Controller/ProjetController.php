<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\EntrepriseRepository;
use App\Repository\FactureRepository;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projet')]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/new/?{entreprise_id<\d+>}', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjetRepository $projetRepository, EntrepriseRepository $entrepriseepository, int $entreprise_id=0): Response
    {
        $projet = new Projet();
        if(isset($entreprise_id) && $entreprise_id != 0) {
            $entreprise = $entrepriseepository->find($entreprise_id);
            $projet->setEntreprise($entreprise);
        }; 

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projetRepository->save($projet, true);

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/encours', name: 'app_projet_encours_index', methods: ['GET', 'POST'])]
    public function indexStatus(ProjetRepository $projetRepository): Response
    {
        $taches = array();
        $projets = $projetRepository->findBy(['termine'=>false]);//findProjetSansFacture();

        return $this->render('projet/index.html.twig', [
            'projets' => $projets, 'statut'=>'en cours'
        ]);
    }  

    #[Route('/afacturer', name: 'app_projet_afacturer_index', methods: ['GET', 'POST'])]
    public function indexAFacturer(ProjetRepository $projetRepository): Response
    {
        $taches = array();
        $projets = $projetRepository->findProjetSansFacture();

        return $this->render('projet/index.html.twig', [
            'projets' => $projets, 'statut'=>'à facturer'
        ]);
    }  

    #[Route('/{id}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet, FactureRepository $factureRepository): Response
    {
        $facture = $factureRepository->findOneByProjet($projet);
        return $this->render('projet/show.html.twig', [
            'projet' => $projet, 'facture' => $facture
        ]);
    }
 
    #[Route('/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projetRepository->save($projet, true);

            return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
        }

  
        return $this->renderForm('projet/edit.html.twig', [
            'projet' => $projet, 
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, ProjetRepository $projetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $projetRepository->remove($projet, true);
        }

        return $this->redirectToRoute('app_projet_index', [], Response::HTTP_SEE_OTHER);
    }
}
