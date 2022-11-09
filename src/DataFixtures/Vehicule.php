<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Vehicule extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $result = $manager->getRepository(\App\Entity\Categorie::class)->findAll();
        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }
}
