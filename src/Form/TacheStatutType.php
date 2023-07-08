<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\Tache;
use App\Entity\TacheStatut;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TacheStatutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $titleStatut = $options['titleStatut'];
        
        $builder
            //->add('dateChangement',DateType::class ,['widget'=>'single_text'])
            ->add('tache', EntityType::class, ['class'=>Tache::class, 'expanded'=>false, 'disabled'=>true])
            ->add('statut', EntityType::class, ['class'=>Statut::class, 'expanded'=>false, 'label'=>$titleStatut ])   //  'Change statut a'       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TacheStatut::class,
            'titleStatut'=>'Statut',
        ]);
    }
}
