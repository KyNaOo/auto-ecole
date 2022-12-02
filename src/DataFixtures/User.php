<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture
{
    private $EncoderPassword;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->EncoderPassword = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user =new \App\Entity\User();
        $this->loadAdmin($manager);
        $this->loadUser($manager, ["ROLE_MONITEUR"], nbItem:10, password:"oto");
        $this->loadUser($manager, ["ROLE_USER"], nbItem:100, password:"oto");
//        $user->setEmail("yacob.tra@gmail.com")->setPassword("1234");

//        $manager->flush();
    }
//    private function loadUser(ObjectManager $manager, array $role, int $nbItem, string $password){
//        $user=new User();
//        $faker = Factory::create
//    }
}
