<?php

namespace App\Tests\Support;

use App\Entity\Categorie;
use App\Entity\Lecon;
use App\Entity\Licence;
use App\Entity\User;
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Crée des données de test déterministes, persistées dans la base de test.
 * Chaque test tourne dans une transaction annulée à la fin (dama/doctrine-test-bundle).
 */
trait EntityFactoryTrait
{
    protected function em(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }

    protected function createUser(string $email, array $roles = ['ROLE_USER'], string $password = 'azerty123', ?string $prenom = null): User
    {
        $user = (new User())
            ->setEmail($email)
            ->setRoles($roles)
            ->setNom('Test')
            ->setPrenom($prenom ?? ucfirst(strstr($email, '@', true)));
        $user->setPassword(static::getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($user, $password));

        return $this->save($user);
    }

    protected function createCategorie(string $libelle = 'Automobile', float $prix = 50.0): Categorie
    {
        return $this->save((new Categorie())->setLibelle($libelle)->setPrix($prix));
    }

    protected function createVehicule(Categorie $categorie, string $marque = 'Renault', string $modele = 'Clio'): Vehicule
    {
        return $this->save((new Vehicule())
            ->setCodecategorie($categorie)
            ->setMarque($marque)
            ->setModele($modele)
            ->setImmatriculation('AB-123-CD')
            ->setAnnee(2020));
    }

    /**
     * @param User[] $participants élève et moniteur
     */
    protected function createLecon(Vehicule $vehicule, \DateTimeInterface $dateStart, array $participants, int $reglee = 0): Lecon
    {
        $lecon = (new Lecon())
            ->setCodevehicule($vehicule)
            ->setDateStart($dateStart)
            ->setReglee($reglee);
        foreach ($participants as $user) {
            $lecon->addCodeuser($user);
        }

        return $this->save($lecon);
    }

    protected function createLicence(User $moniteur, Categorie $categorie): Licence
    {
        return $this->save((new Licence())
            ->setCodeuser($moniteur)
            ->setCodecategorie($categorie)
            ->setDateobtention(new \DateTime('2015-06-01')));
    }

    /**
     * @template T of object
     *
     * @param T $entity
     *
     * @return T
     */
    private function save(object $entity): object
    {
        $this->em()->persist($entity);
        $this->em()->flush();

        return $entity;
    }
}
