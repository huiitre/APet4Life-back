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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Service\GeolocApi;

class PartType extends AbstractType
{
    private $department;

    public function __construct(GeolocApi $department)
    {
        $this->department = $department;
    }

    /**
     * Propriétés et vérifications nécessaires au formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $department = $this->department->fetch("departements");
        $builder
            /* ->add('type') */
            ->add('mail', EmailType::class, ['label' => 'E-Mail*','attr' => ['placeholder' => 'saisissez votre e-mail']])
            ->add('firstname', TextType::class, ['label' => 'prénom','attr' => ['placeholder' => 'saisissez votre Prénom'], 'required' => false])
            ->add('lastname', TextType::class, ['label' => 'Nom de famille','attr' => ['placeholder' => 'saisissez votre nom de famille'], 'required' => false])
            // ->add('password', PasswordType::class, ['label' => 'Mot de passe', 'required' => false])
            // ->add('department', TextType::class, ['label' => 'Département', 'required' => false])
            ->add('department', ChoiceType::class, ['label' => 'département', 'choices' => $department])
            ->add('phone_number', TextType::class, ['label' => 'Numero de téléphone', 'required' => false])
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
