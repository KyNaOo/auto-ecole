<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture implements OrderedFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $roles = ["ROLE_USER","ROLE_MONITEUR"];
        $count = 0;

        $validPostCode= function ($postCode) {
            return strlen($postCode) === 5;
        };
        while ($count<40) {
            $user = new \App\Entity\User();
            $user->setEmail($faker->unique()->email())
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setDateNaissance($faker->dateTimeBetween('-60 years', '-18 years'))
                ->setAdresse($faker->streetAddress)
                ->setVille($faker->city)
                ->setCodepostale($faker->valid($validPostCode)->postcode)
                ->setTelephone($faker->e164PhoneNumber)
                ->setSexe($faker->randomElement(['Femme', 'Homme', 'Autre', 'Ne se prononce pas']))
            ;
            $user->setPassword($this->hasher->hashPassword($user, $faker->password));
            $user->setRoles((array)$roles[random_int(0, 1)]);
            $user->setIsVerified((bool) random_int(0, 1));
            $manager->persist($user);
            ++$count;
        }
        $user2 = $this->addUser('qinhao@wu.com', ['ROLE_USER']);
        $manager->persist($user2);
        $user3 = $this->addUser('jacob@trabelsi.com', ['ROLE_MONITEUR']);
        $manager->persist($user3);
        $user4 = $this->addUser('ethanbellaiche0@gmail.com', ['ROLE_ADMIN']);
        $manager->persist($user4);



        $manager->flush();
    }
public function addUser(string $email, array $role){
    $validPostCode= function ($postCode) {
        return strlen($postCode) === 5;
    };
    $faker = Factory::create('fr_FR');
    $user = new \App\Entity\User();
    $user->setEmail($email)
        ->setRoles($role)
        ->setNom($faker->lastName)
        ->setPrenom($faker->firstName)
        ->setDateNaissance($faker->dateTimeBetween('-60 years', '-18 years'))
        ->setAdresse($faker->streetAddress)
        ->setVille($faker->city)
        ->setCodepostale($faker->valid($validPostCode)->postcode)
        ->setTelephone($faker->e164PhoneNumber)
        ->setSexe($faker->randomElement(['Femme', 'Homme', 'Autre', 'Ne se prononce pas']))
        ->setDatenaissance($faker->dateTimeInInterval($startDate = '- 80 years', $interval = '- 18 years', $timezone = null));
    $user->setPassword($this->hasher->hashPassword($user, 'azerty123'));
    return $user;
}
    public function getOrder(): int {
        return 1;
    }
}
