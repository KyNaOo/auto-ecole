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

            ->add('prenomeleve',null,[
                'help'=>'Veuillez saisir votre prenom',
                'label' => 'Prenom'])

            ->add('sexeeleve',null,[
                'help'=>'Veuillez saisir votre genre',
                'label' => 'Genre'])

            ->add('datenaissance', DateType::Class, array(
        'years' => range(date('Y')-16, date('Y')-100),))

            ->add('adresse',null,[
                'help'=>'Veuillez saisir votre adresse',
                'label' => 'Moniteur'])

            ->add('codepostale',null,[
                'help'=>'Veuillez saisir votre code postale',
                'label' => 'Moniteur'])

            ->add('ville',null,[
                'help'=>'Veuillez saisir votre ville',
                'label' => 'Moniteur'])

            ->add('telephone',null,[
                'help'=>'Veuillez saisir votre numero de telephone',
                'label' => 'Moniteur'])

            ->add('login',null,[
                'help'=>'Veuillez saisir votre login',
                'label' => 'Moniteur'])

            ->add('mdp',null,[
                'help'=>'Saisir un mot de passe',
                'label' => 'Moniteur'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
