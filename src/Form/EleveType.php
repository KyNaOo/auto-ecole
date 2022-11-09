<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomeleve',null,[
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])

            ->add('prenomeleve',null,[
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])

            ->add('sexeeleve',null,[
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'Genre'
                ]
            ])

            ->add('datenaissance', DateType::Class, array(
        'years' => range(date('Y')-16, date('Y')-100),))

            ->add('adresse',null,[
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])

            ->add('codepostale',null,[
                'label' => 'Code Postale',
                'attr' => [
                    'placeholder' => 'Code Postale'
                ]
            ])

            ->add('ville',null,[
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])

            ->add('telephone',null,[
                'label' => 'N° téléphone',
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])

            ->add('login',null,[
                'label' => 'Identifiant',
                'attr' => [
                    'placeholder' => 'Login'
                ]
            ])

            ->add('mdp',PasswordType::class,[
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
