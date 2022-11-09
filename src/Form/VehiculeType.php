<?php

namespace App\Form;

use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('immatriculation', null, [
                'help'=>'Veuillez saisir le numéro de plaque',
                'label' => 'Immatriculation'])

            ->add('marque', null, [
                'help'=>'Veuillez saisir la marque',
                'label' => 'Marque'])

            ->add('modele', null, [
                'help'=>'Veuillez saisir le model',
                'label' => 'Modèle'])

            ->add('annee', null, [
                'help'=>'Veuillez saisir l\'année',
                'label' => 'Année'])

            ->add('codecategorie',null, [
                'label' => 'Catégorie'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
