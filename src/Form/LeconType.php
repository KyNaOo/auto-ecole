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
                'years' => range(date('Y')-50, date('Y')+50),
                'label' => 'Date'))

            ->add('heure', null, [
                'help'=>'Veuillez saisir renseigner une heure',
                'label' => 'Heure'])

            ->add('codemoniteur',null, [
                'label' => 'Moniteur'])

            ->add('codeeleve',null, [
                'label' => 'Eleve'])

            ->add('codevehicule',null, [
                'label' => 'Vehicule'])

            ->add('reglee', null, [
                'help'=>'Veuillez saisir indiquer le statut de paiement de la lecon',
                'label' => 'Reglée ?'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecon::class,
        ]);
    }
}
