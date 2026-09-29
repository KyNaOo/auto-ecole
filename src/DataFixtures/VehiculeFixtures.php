<?php
namespace App\DataFixtures;

use App\Entity\Vehicule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Faker;
use Faker\Provider\FakeCar;

class VehiculeFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Faker\Provider\FakeCar($faker));

        $categories = $manager->getRepository(\App\Entity\Categorie::class)->findAll();

        foreach ($categories as $category) {
            $nbVehicule = 0;
            while ($nbVehicule < 10) {
                $vehicule = new Vehicule();
                $vehicule->setCodeCategorie($category)
                    ->setAnnee($faker->numberBetween(2016, 2022))
                    ->setImmatriculation($faker->vehicleRegistration('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'))
                    ->setMarque($faker->vehicleBrand)
                    ->setModele($faker->vehicleModel)
                ;
                $manager->persist($vehicule);
                ++$nbVehicule;
            }
        }

        $manager->flush();
    }
    public function getOrder(): int{
        return 3;
    }
}