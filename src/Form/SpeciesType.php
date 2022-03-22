<?php

namespace App\Form;

use App\Entity\Species;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpeciesType extends AbstractType
{
    /**
     * Propriétés et vérifications nécessaires au formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            //->add('users')
        ;
    }

    /**
     * La class concerné par le formulaire
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Species::class,
        ]);
    }
}
