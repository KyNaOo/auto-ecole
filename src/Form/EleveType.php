<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomeleve')
            ->add('prenomeleve',null,['help'=>'Veuillez saisir votre prenom'])
            ->add('sexeeleve',null,['help'=>'Veuillez saisir votre genre'])
            ->add('datenaissance', DateType::Class, array(
        'years' => range(date('Y')-16, date('Y')-100),))
            ->add('adresse',null,['help'=>'Veuillez saisir votre adresse'])
            ->add('codepostale',null,['help'=>'Veuillez saisir votre code postale'])
            ->add('ville',null,['help'=>'Veuillez saisir votre ville'])
            ->add('telephone',null,['help'=>'Veuillez saisir votre numero de telephone'])
            ->add('login',null,['help'=>'Veuillez saisir votre login'])
            ->add('mdp',null,['help'=>'Saisir un mot de passe'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
