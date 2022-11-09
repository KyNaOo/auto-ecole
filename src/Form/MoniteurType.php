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
                'help'=>'Veuillez saisir votre nom',
                'label' => 'Nom'])

            ->add('prenommoniteur', null, [
                'help'=>'Veuillez saisir votre prénom',
                'label' => 'Prenom'])

            ->add('sexemoniteur', null, [
                'help'=>'Veuillez saisir votre genre',
                'label' => 'Genre'])

            ->add('maissancemoniteur', DateType::Class, array(
                'years' => range(date('Y')-16, date('Y')-100),
                'label' => 'Date de naissance'))

            ->add('adressemoniteur', null, [
                'help'=>'Veuillez saisir votre adresse',
                'label' => 'Adresse'])

            ->add('codepostalemoniteur', null, [
                'help'=>'Veuillez saisir votre code postale',
                'label' => 'Code Postale'])

            ->add('villemoniteur', null, [
                'help'=>'Veuillez saisir votre ville',
                'label' => 'Ville'])

            ->add('telephonemoniteur', null, [
                'help'=>'Veuillez saisir votre numéro de téléphone',
                'label' => 'N° telephone'])

            ->add('loginmoni', null, [
                'help'=>'Veuillez renseigner votre identifiant',
                'label' => 'Identifiant'])

            ->add('mdpmoni', PasswordType::class, [
                'help'=>'Veuillez saisir choisir un mot de passe',
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Veuillez saisir choisir un mot de passe'
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
