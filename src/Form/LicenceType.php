<?php

namespace App\Form;

use App\Entity\Licence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codemoniteur')
            ->add('codecategorie')
            ->add('dateobtention', DateType::Class, array(
                'years' => range(date('Y'), date('Y')-50),))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licence::class,
        ]);
    }
}
