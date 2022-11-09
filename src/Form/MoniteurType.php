<?php

namespace App\Form;

use App\Entity\Moniteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoniteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nommoniteur', null, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])

            ->add('prenommoniteur', null, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'Prenom'
                ]
            ])

            ->add('sexemoniteur', null, [
                'label' => 'Genre',
                'attr' => [
                    'placeholder' => 'Genre'
                ]
            ])

            ->add('maissancemoniteur', DateType::Class, array(
                'years' => range(date('Y')-16, date('Y')-100),
                'label' => 'Date de naissance'))

            ->add('adressemoniteur', null, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Adresse'
                ]
            ])

            ->add('codepostalemoniteur', null, [
                'label' => 'Code Postale',
                'attr' => [
                    'placeholder' => 'Code Postale'
                ]
            ])

            ->add('villemoniteur', null, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Ville'
                ]
            ])

            ->add('telephonemoniteur', null, [
                'label' => 'N° telephone',
                'attr' => [
                    'placeholder' => 'Téléphone'
                ]
            ])

            ->add('loginmoni', null, [
                'label' => 'Identifiant',
                'attr' => [
                    'placeholder' => 'Login'
                ]
            ])

            ->add('mdpmoni', PasswordType::class, [
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
            'data_class' => Moniteur::class,
        ]);
    }
}
