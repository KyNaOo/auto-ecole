<?php
namespace App\DataFixtures;

use App\Entity\Lecon;
use App\Entity\User;
use App\Entity\Vehicule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LeconFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->clear();
        $faker = Factory::create();
        $moniteurs = $manager->getRepository(User::class)->findUsersByRole('ROLE_MONITEUR');
        $eleves = $manager->getRepository(User::class)->findUsersByRole('ROLE_USER');
        $vehicules = $manager->getRepository(Vehicule::class)->findAll();
        $hours = [
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00'
        ];
        $nbLecons = 0;
        while ($nbLecons < 100) {
            $lecon = new Lecon();
            $lecon->setHeure($hours[array_rand($hours)])
                ->setCodevehicule($vehicules[array_rand($vehicules)])
                ->setDate($faker->dateTimeBetween('-2 years', '+1 years'))
                ->setReglee($faker->numberBetween(0, 1))
            ;
            $lecon->addCodeuser($moniteurs[array_rand($moniteurs)]);
            $lecon->addCodeUser($eleves[array_rand($eleves)]);
            $manager->getRepository(Lecon::class)->save($lecon, true);
            ++$nbLecons;
        }
    }

    public function getOrder()
    {
        return 5;
    }
}