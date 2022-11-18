<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Moniteur extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $listMoni=[
            0=>["nommoniteur"=>"Jean","prenommoniteur"=>"Luc","sexemoniteur"=>"homme","maissancemoniteur"=>"2003-01-09","adressemoniteur"=>"2 rue Porte de Montreuil","codepostalmoniteur"=>"93140","villemoniteur"=>"Montreuil","telephonemoniteur"=>"0712512383","loginmoni"=>"coucou","mdpmoni"=>"1234"],
        1=>["nommoniteur"=>"Wu","prenommoniteur"=>"QinHao","sexemoniteur"=>"homme","maissancemoniteur"=>"2003-01-23","adressemoniteur"=>"1 rue de la Noue","codepostalmoniteur"=>"93170","villemoniteur"=>"Bagnolet","telephonemoniteur"=>"0728862393","loginmoni"=>"sheesh","mdpmoni"=>"wsh"],
        2=>["nommoniteur"=>"Rado","prenommoniteur"=>"Xavier","sexemoniteur"=>"homme","maissancemoniteur"=>"2001-02-12","adressemoniteur"=>"1 rue Trocadero","codepostalmoniteur"=>"75203","villemoniteur"=>"Paris","telephonemoniteur"=>"0623142313","loginmoni"=>"laLaitière","mdpmoni"=>"dqsa"],
        3=>["nommoniteur"=>"Khader","prenommoniteur"=>"Abdel","sexemoniteur"=>"homme","maissancemoniteur"=>"2000-03-09","adressemoniteur"=>"2 rue de la fontaine","codepostalmoniteur"=>"77123","villemoniteur"=>"Romainville","telephonemoniteur"=>"0723917278","loginmoni"=>"lmao","mdpmoni"=>"HJBBJ"],
        4=>["nommoniteur"=>"Bellaiche","prenommoniteur"=>"Ethan","sexemoniteur"=>"Femme","maissancemoniteur"=>"2002-12-10","adressemoniteur"=>"2 rue de la Kalash","codepostalmoniteur"=>"94213","villemoniteur"=>"Champigny sur Marne","telephonemoniteur"=>"0788293108","loginmoni"=>"SIUUUU","mdpmoni"=>"dsqdsqd"],
        5=>["nommoniteur"=>"Jesus","prenommoniteur"=>"Christ","sexemoniteur"=>"homme","maissancemoniteur"=>"2000-01-01","adressemoniteur"=>"2 rue Porte du Paradis","codepostalmoniteur"=>"00000","villemoniteur"=>"Paradis","telephonemoniteur"=>"077777777","loginmoni"=>"RaaaakBam","mdpmoni"=>"volerNéPaBon"],
        6=>["nommoniteur"=>"Rabenou","prenommoniteur"=>"Moshe","sexemoniteur"=>"homme","maissancemoniteur"=>"1999-08-14","adressemoniteur"=>"2 rue Porte du gan Eden","codepostalmoniteur"=>"77777","villemoniteur"=>"Mont Sinai","telephonemoniteur"=>"0707070707","loginmoni"=>"SaDiKuaLekip","mdpmoni"=>"meHakPaSTP"],
            7=>["nommoniteur"=>"Ketchum","prenommoniteur"=>"Sacha","sexemoniteur"=>"homme","maissancemoniteur"=>"2004-01-12","adressemoniteur"=>"3 rue de Bourpalette","codepostalmoniteur"=>"11111","villemoniteur"=>"Bourpalette","telephonemoniteur"=>"07101010101","loginmoni"=>"AtrapéLéTous","mdpmoni"=>"SiTuMeHakTéGay"]
        ];
        // $product = new Product();
        // $manager->persist($product);
        foreach ($listMoni as $item){
            $moni = new \App\Entity\Moniteur();
            $moni->setNommoniteur($item["nommoniteur"])->setPrenommoniteur($item['prenommoniteur'])
                ->setSexemoniteur($item['sexemoniteur'])->setMaissancemoniteur($item['maissancemoniteur'])
                ->setAdressemoniteur($item['adressemoniteur'])->setCodepostalemoniteur($item['codepostalmoniteur'])
                ->setVillemoniteur($item['villemoniteur'])->setTelephonemoniteur($item['telephonemoniteur'])->setLoginmoni($item['loginmoni'])->setMdpmoni($item['mdpmoni']);
            $manager->persist($moni);
        }

        $manager->flush();
    }
}
