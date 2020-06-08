<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('category', EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'titre'
            ])
            ->add('photo')
            ->add('prix')
            ->add('pseudo')
            ->add('contenu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
