<?php

namespace App\Form;

use App\Core\SexeChoice;
use App\Entity\Lecon;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


class LeconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart',DateTimeType::class,[
                'date_widget'=>'single_text',
                'time_widget'=>'choice',
                'hours'=> range(8,17),
                'constraints'=>[
                    new GreaterThanOrEqual(value: 'today', message: 'Vous ne pouvez pas sélectionner une date antérieure!')
                ],
                'data'=>(new \DateTime())->setTime(8,0)
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
            ->add('codeuser', EntityType::class, array(
                'class'=>User::class,
                'query_builder'=>function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->select('u')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%ROLE_MONITEUR%');
                },
                'mapped'=> false,
                'choice_label' => function(User $user){
                    return $user->getPrenom().' '.$user->getNom() ;
                    }
            ));

        //$stmt = $conn->prepare($sql);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecon::class,
        ]);
    }
}
