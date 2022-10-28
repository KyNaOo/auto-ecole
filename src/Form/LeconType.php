<?php

namespace App\Form;

use App\Entity\Lecon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::Class, array(
                'years' => range(date('Y')-50, date('Y')+50),))
            ->add('heure')
            ->add('codemoniteur')
            ->add('codeeleve')
            ->add('codevehicule')
            ->add('reglee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecon::class,
        ]);
    }
}
