<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private $EncoderPassword;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher= $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user =new \App\Entity\User();
        $this->loadAdmin($manager);
        $this->loadUser($manager, ["ROLE_ADMIN"], 1, "admin");
        $this->loadUser($manager, ["ROLE_MONITEUR"], 10, "moniteur");
        $this->loadUser($manager, ["ROLE_USER"], 50, "user");

//        $user->setEmail("yacob.tra@gmail.com")->setPassword("1234");
//        $manager->flush();
    }
        public function loadUser(ObjectManager $manager, array $role, int $nbEntite, String $motdepasse): void{
            $faker = Factory::create();

            for($i = 1; $i <= $nbEntite; $i++){
                $user=new User();
                $user->setEmail($faker->email());
                $user->setNom($faker->lastName());
                $user->setPrenom($faker->firstName());
                $user->setPassword($this->hasher->hashPassword($user, $motdepasse));
                $user->setSexe("homme");
                $user->setRoles($role);
                $manager->persist($user);
            }
            $manager->flush();
        }

//    private function loadUser(ObjectManager $manager, array $role, int $nbItem, string $password){
//        $user=new User();
//        $faker = Factory::create
//    }
}
