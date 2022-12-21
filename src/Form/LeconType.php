<?php

namespace App\Form;

use App\Core\SexeChoice;
use App\Entity\Lecon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::Class, array(
                'years' => range(date('Y')-50, date('Y')+50),
                'label' => 'Date'))

            ->add('heure', ChoiceType::class,[
                'choices' => SexeChoice::heure,
                'attr' => [
                    'placeholder' => 'Veuillez saisir une heure'
                ]
            ])

            ->add('codevehicule',null, [
                'label' => 'Vehicule'])

            ->add('reglee', ChoiceType::class, [
                'choices'=>SexeChoice::reglee,
                'label' => 'Reglée ?',
                'attr' => [
                    'placeholder' => '1 pour regler 0 pour non regler'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecon::class,
        ]);
    }
}
