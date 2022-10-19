<?php

namespace App\Form;

use App\Entity\Moniteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoniteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nommoniteur')
            ->add('prenommoniteur')
            ->add('sexemoniteur')
            ->add('maissancemoniteur')
            ->add('adressemoniteur')
            ->add('codepostalemoniteur')
            ->add('villemoniteur')
            ->add('telephonemoniteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moniteur::class,
        ]);
    }
}
