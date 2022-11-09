<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Categorie extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $listCat = [
            0=>["libelle"=>"BUS", "PRIX"=>"1.5"],
            1=>["libelle"=>"TROTINETTE", "PRIX"=>"0.5"],
            2=>["libelle"=>"VELO", "PRIX"=>"0.1"],
            3=>["libelle"=>"ROLLER", "PRIX"=>"0.1"],
            4=>["libelle"=>"CAMION", "PRIX"=>"5"],
            5=>["libelle"=>"BATEAU", "PRIX"=>"15"],
            6=>["libelle"=>"VOITURE", "PRIX"=>"5"],
            7=>["libelle"=>"MOTO", "PRIX"=>"2.5"],
        ];
        foreach ($listCat as $item){
            $cat = new \App\Entity\Categorie();
            $cat->setLibelle($item["libelle"])->setPrix($item['PRIX']);
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
