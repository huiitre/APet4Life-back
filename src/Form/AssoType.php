<?php

namespace App\Form;

use App\Entity\Species;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use App\Service\GeolocApi;

class AssoType extends AbstractType
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
        $region = $this->department->fetch("regions");
        $builder
            /* ->add('type') */
            ->add('name', TextType::class, ['label' => 'Nom de l\'association'])
            ->add('siret', TextType::class, [
                'constraints' => [new Length(['min' => 17, 'max' => 17])]
            ])
            ->add('mail', EmailType::class, ['label' => 'E-Mail'])
            // ->add('password', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('adress', TextType::class, ['label' => 'Adresse'])
            ->add('zipcode', TextType::class, ['label' => 'Code postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            // ->add('department', TextType::class, ['label' => 'Département'])
            ->add('department', ChoiceType::class, ['label' => 'Département', 'choices' => $department])
            ->add('region', ChoiceType::class, ['label' => 'Région', 'choices' => $region])
            ->add('phone_number', TextType::class, ['label' => 'Numero de téléphone'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('picture', TextareaType::class, ['label' => 'Photo'])
            ->add('website', TextareaType::class, ['label' => 'Site web'])
            /* ->add('roles', CollectionType ::class, ['label' => 'Rôle']) */
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]);
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
