<?php

namespace App\Form;

use App\Core\SexeChoice;
use App\Entity\Lecon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class LeconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart',DateTimeType::class,[
                'date_widget'=>'single_text'
            ])
            ->add('dateEnd',DateTimeType::class,[
                'date_widget'=>'single_text'
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
