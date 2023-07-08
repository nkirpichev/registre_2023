<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Statut;
use App\Entity\Tache;
use App\Entity\TacheStatut;
use App\Form\TacheType;
use App\Repository\PersonneRepository;
use App\Repository\ProjetRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheStatutRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/tache')]
class TacheController extends AbstractController
{
    #[Route('/', name: 'app_tache_index', methods: ['GET'])]
    public function index(TacheRepository $tacheRepository): Response
    {
        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findAll(),
        ]);
    }
   
    #[Route('/api', name: 'api_taches', methods: ['GET'])]
    public function api_taches(TacheRepository $tacheRepository, SerializerInterface $serializer) : JsonResponse
    {
        $taches = $tacheRepository->findAll();
        $tachesJson = $serializer->serialize($taches , 'json', ['groups' => ['tache', 'projet', 'personne', 'statut']]);
        
        return new JsonResponse($tachesJson, Response::HTTP_OK,[],true);
    }

    #[Route('/api/{id}', name: 'api_tache', methods: ['GET'])]
    public function api_tache(TacheRepository $tacheRepository, SerializerInterface $serializer, $id) : JsonResponse
    {
        $tache = $tacheRepository->find($id);
        if(isset($tache)){
            $tacheJson = $serializer->serialize($tache , 'json', ['groups' => ['tache', 'projet', 'personne', 'statut']]);
            return new JsonResponse($tacheJson, Response::HTTP_OK,[],true);
        }
       
        return new Response('', Response::HTTP_NOT_FOUND);
    }

    #[Route('/encours', name: 'app_tache_encours_index', methods: ['GET', 'POST'])]
    public function indexStatus(TacheRepository $tacheRepository, TacheStatutRepository $tacheStatutRepository): Response
    {
        $taches = array();
        $tacheStatut1 = $tacheStatutRepository->findLastByStatut(2);
        $tacheStatut=$tacheRepository->findLastByStatus(1);
        foreach($tacheStatut as $ts){
            $taches[]=$tacheRepository->find($ts['tache_id']);
        }
       
        foreach($tacheStatut1 as $ts){
            $taches[]=$ts->getTache();
        }

        return $this->render('tache/index.html.twig', [
            'taches' => $taches, 'statut'=>'à faire'
        ]);
    }

    #[Route('/new/{projet_id<\d+>}', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonneRepository $personneRepository,TacheRepository $tacheRepository, ProjetRepository $projetRepository, $projet_id, StatutRepository $statutRepository, TacheStatutRepository $tacheStatutRepository): Response
    {
        $tache = new Tache();
        $tache->setDateDebut(new DateTime('now'));
        if(isset($projet_id) && $projet_id != 0) {
            $tache->setProjet($projetRepository->find($projet_id));
            $emploeys = $tache->getProjet()->getEntreprise()->getPersonnes();
        }else{
            $emploeys = $personneRepository->findAll();
        };
        
       
        $form = $this->createForm(TacheType::class, $tache,["emploeys"=>$emploeys]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $tacheRepository->save($tache, true);
            
            $tacheStatut = new TacheStatut();
            $tacheStatut->setTache($tache);
            $statutNouvelle = $statutRepository->find(1);
            $tacheStatut->setStatut($statutNouvelle);
            $tacheStatut->setDateChangement(new DateTime('now'));
            $tacheStatutRepository->save($tacheStatut, true);
           
            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/new.html.twig', [
            'tache' => $tache,
            'form' => $form
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_tache_show', methods: ['GET'])]
    public function show(Tache $tache): Response
    {
        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tache $tache, TacheRepository $tacheRepository): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tacheRepository->save($tache, true);

            return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/edit.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, TacheRepository $tacheRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tache->getId(), $request->request->get('_token'))) {
            $tacheRepository->remove($tache, true);
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
}
