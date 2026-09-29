<?php

namespace App\Form;

use App\Core\SexeChoice;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class,[
                'choices'=>SexeChoice::choice
            ])
            ->add('telephone')
            ->add('adresse')
            ->add('ville')
            ->add('codepostale')
            ->add('datenaissance', DateType::class, array(
                'widget' => 'choice',
                'years' => range(date('Y')-100, date('Y')-16),
                'label' => 'Date'))
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
//->add('adresse', \Symfony\Component\Form\Extension\Core\Type\TextType::class,[
//    'attr' => [
//        'class' => 'adresse'
//    ],
//    'label_attr' => [
//        'style' => 'color:red;'
//    ]
//])