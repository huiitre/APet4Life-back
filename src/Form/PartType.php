<?php

namespace App\Form;

use App\Entity\Species;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartType extends AbstractType
{
    /**
     * Propriétés et vérifications nécessaires au formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* ->add('type') */
            ->add('firstname', TextType::class, ['label' => 'prénom','attr' => ['placeholder' => 'saisissez votre Prénom']])
            ->add('lastname', TextType::class, ['label' => 'Nom de famille','attr' => ['placeholder' => 'saisissez votre nom de famille']])
            ->add('mail', EmailType::class, ['label' => 'E-Mail','attr' => ['placeholder' => 'saisissez votre e-mail']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('department', TextType::class, ['label' => 'Département'])
            ->add('phone_number', TextType::class, ['label' => 'Numero de téléphone'])
            ;
    }

    /**
     * La class concerné par le formulaire
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
