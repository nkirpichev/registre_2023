<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateProjet',DateType::class,array( 'widget'=>'single_text','attr'=>['class'=>'w-25']))
            ->add('tauxHeure',IntegerType::class,array("label" => "Taux heure, $",'attr'=>['class'=>'w-25']))
            ->add('entreprise', EntityType::class, ['class'=>Entreprise::class, 'expanded'=>false,'attr'=>['class'=>'w-25']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
