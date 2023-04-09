<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $listCat = [
            0 => ['libelle' => 'Automobile', 'prix' => 50],
            1 => ['libelle' => 'Bus', 'prix' => 200.50],
            2 => ['libelle' => 'Camion', 'prix' => 85.50],
            3 => ['libelle' => 'Bateau', 'prix' => 300],
            4 => ['libelle' => 'Moto', 'prix' => 100]
        ];

        foreach ($listCat as $item){
            $cat = new \App\Entity\Categorie();
            $cat->setLibelle($item["libelle"])->setPrix($item["prix"]);
            $manager->persist($cat);
        }

        $manager->flush();
    }

    public function getOrder(): int{
        return 2;
    }
}