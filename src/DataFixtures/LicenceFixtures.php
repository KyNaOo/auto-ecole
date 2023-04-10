<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Licence;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
class LicenceFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Categorie::class)->findAll();
        $moniteurs = $manager->getRepository(User::class)->findUsersByRole('ROLE_MONITEUR');
        $faker = Faker\Factory::create('fr_FR');

        foreach ($categories as $category) {
            $nbLicence = 0;
            $random = random_int(0, count($moniteurs));
            foreach ($moniteurs as $moniteur) {
                if ($nbLicence === $random){
                    break;
                }
                $licence = new Licence();
                $licence->setCodeuser($moniteur);
                $licence->setDateObtention($faker->dateTimeBetween("-20 years", "now"));
                $licence->setCodeCategorie($category);
                $manager->persist($licence);
                ++$nbLicence;
            }
        }

        $manager->flush();
    }

    public function getOrder(): int{
        return 4;
    }
}