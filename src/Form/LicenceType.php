<?php

namespace App\Form;

use App\Entity\Licence;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codecategorie',null, [
                'label' => 'Catégorie'])

            ->add('dateobtention', DateType::class, array(
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-50),
                'label' => 'Date d\'obtention'))

            ->add('codeuser', EntityType::class, array(
                'class'=>User::class,
                'choice_label' => function (User $user) {
                    return $user->getPrenom();
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licence::class,
        ]);
    }
}
