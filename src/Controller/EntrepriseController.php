<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class EntrepriseController extends AbstractController
{


    #[Route('/entreprise/edit/{id}', name: 'app_entreprise_edit')]
    public function index_edit(EntrepriseRepository $entrepriseRepository, $id, Entreprise $ent, Request $request): Response
    {

        $form = $this->CreateFormBuilder($ent)
        ->add('nom', TextType::class)
        ->add('valider',SubmitType::class, array('attr'=>array('class'=>'btn btn-primary')))
        ->add('annuler',ResetType::class, array('attr'=>array('class'=>'btn btn-secondary')))
        ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entrepriseRepository->save($ent, true);

            return $this->redirectToRoute('app_entreprise', [], Response::HTTP_SEE_OTHER);}

        return $this->renderForm('entreprise/edit.html.twig', [
            'form' => $form,
        ]);
    }
   
    #[Route('/entreprise/new', name: 'app_entreprise_new')]
    public function index_new(EntrepriseRepository $entrepriseRepository, Request $request): Response
    {
        $ent = new Entreprise();
        $form = $this->CreateFormBuilder($ent)
        ->add('nom', TextType::class)
        ->add('valider',SubmitType::class, array('attr'=>array('class'=>'btn btn-primary')))
        ->add('annuler',ResetType::class, array('attr'=>array('class'=>'btn btn-secondary')))
        ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entrepriseRepository->save($ent, true);

            return $this->redirectToRoute('app_entreprise', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('entreprise/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/entreprise/{id}', name: 'app_entreprise_detail')]
    public function index_detail(Entreprise $entreprise, $id): Response
    {
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise, 'employes'=> $entreprise->getPersonnes(),
            'projets'=> $entreprise->getProjets()
        ]);
    }

    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entrepriseRepository->findAll()
        ]);
    }

    #[Route('/entreprise/{id}', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntrepriseRepository $entrepriseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entrepriseRepository->remove($entreprise, true);
        }

        return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
    }
}
