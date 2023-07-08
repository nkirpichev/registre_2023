<?php

namespace App\Form;

use App\Entity\Personne;
use App\Entity\Tache;
use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $allowedEmp = $options['emploeys'];
        $builder
            ->add('description')
            ->add('commentaire')
            ->add('dateDebut',DateType::class,array( 'widget'=>'single_text', 'attr'=>['class'=>'w-25']))
            ->add('dateAchevement',DateType::class,array( 'widget'=>'single_text' , "required" =>false,'attr'=>['class'=>'w-25']))
            ->add('nombreHeure',IntegerType::class,array("label" => "Nombre heure",'attr'=>['class'=>'w-25']))
            ->add('projet', EntityType::class, ['class'=>Projet::class, 'expanded'=>false,'attr'=>['class'=>'w-25']])
           // ->add('prestataire') ->add('employe')
            ->add('prestataire', EntityType::class, ['class'=>Personne::class, 'expanded'=>false, "required" =>false, "choice_filter" =>'isPrestataire','attr'=>['class'=>'w-25']])
            ->add('employe', EntityType::class, ['class'=>Personne::class, 'expanded'=>false, "required" =>false, 'choices' => $allowedEmp,'attr'=>['class'=>'w-25'] ])
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
            'emploeys' => null,
        ]);
    }
}