<?php

namespace App\Form;

use App\Entity\Lecon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('heure')
            ->add('codemoniteur')
            ->add('codeeleve')
            ->add('immatriculation')
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
