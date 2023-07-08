<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Projet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $allowedProjets = $options['projets'];
        $builder
            ->add('dateFacture',DateType::class,array( 'widget'=>'single_text','attr'=>['class'=>'w-25']))
            ->add('total',IntegerType::class,array("label" => "Total, $",'attr'=>['class'=>'w-25']))
            ->add('projet', EntityType::class, ['class'=>Projet::class, 'expanded'=>false, "required" =>true, 'choices' => $allowedProjets,'attr'=>['class'=>'w-25']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'projets' => null,
        ]);
    }
}
