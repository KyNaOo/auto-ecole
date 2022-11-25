<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class User extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user =new \App\Entity\User();
        $user->setEmail("yacob.tra@gmail.com")->se

        $manager->flush();
    }
}
