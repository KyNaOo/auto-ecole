<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Les statistiques sont écrites en SQL brut : ces tests vérifient qu'elles
 * s'exécutent sous MySQL 8 (mode ONLY_FULL_GROUP_BY) et renvoient les bons chiffres.
 */
final class UserRepositoryTest extends KernelTestCase
{
    use EntityFactoryTrait;

    private UserRepository $repository;
    private User $eleve;
    private User $moniteur;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = $this->em()->getRepository(User::class);

        // 3 leçons auto (50 €, dont 2 non réglées) et 1 leçon moto (100 €, réglée)
        $this->eleve = $this->createUser('eleve@test.fr');
        $this->moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR'], prenom: 'Paul');
        $clio = $this->createVehicule($this->createCategorie('Automobile', 50), 'Renault', 'Clio');
        $mt07 = $this->createVehicule($this->createCategorie('Moto', 100), 'Yamaha', 'MT-07');
        $this->createLecon($clio, new \DateTime('2030-01-15 10:00'), [$this->eleve, $this->moniteur], reglee: 0);
        $this->createLecon($clio, new \DateTime('2030-01-16 10:00'), [$this->eleve, $this->moniteur], reglee: 0);
        $this->createLecon($clio, new \DateTime('2030-01-17 10:00'), [$this->eleve, $this->moniteur], reglee: 1);
        $this->createLecon($mt07, new \DateTime('2030-01-18 10:00'), [$this->eleve, $this->moniteur], reglee: 1);
    }

    public function testFindUsersByRole(): void
    {
        $moniteurs = $this->repository->findUsersByRole('ROLE_MONITEUR');

        self::assertSame(['moniteur@test.fr'], array_map(fn (User $u) => $u->getEmail(), $moniteurs));
    }

    public function testStatistiquesEleve(): void
    {
        $id = $this->eleve->getId();

        self::assertEquals(4, $this->repository->getNbLeconByEleve($id)[0]['nbLecon']);
        self::assertEquals(250, $this->repository->getMontantPermis($id)[0]['prix']);
        self::assertEquals(100, $this->repository->getMontantRestant($id)[0]['prixRestant']);

        $vehicule = $this->repository->getVehiculeUseByEleve($id);
        self::assertCount(1, $vehicule);
        self::assertSame('Clio', $vehicule[0]['Modele']);
        self::assertEquals(3, $vehicule[0]['nbLecon']);
    }

    public function testStatistiquesMoniteur(): void
    {
        $id = $this->moniteur->getId();

        self::assertEquals(4, $this->repository->getNbLeconByMoniteur($id)[0]['nbLecon']);
        self::assertEquals(250, $this->repository->getCATotByMoniteur($id)[0]['CATot']);
        self::assertEqualsCanonicalizing(
            [['libelle' => 'Automobile', 'nbLecon' => 3], ['libelle' => 'Moto', 'nbLecon' => 1]],
            $this->repository->getNbLeconByMoniByCateg($id),
        );
    }

    public function testStatistiquesAdmin(): void
    {
        $moniteurs = $this->repository->getMoniteurMaxUse();
        self::assertCount(1, $moniteurs);
        self::assertEquals(4, $moniteurs[0]['nbLecon']);

        $vehicules = $this->repository->getVehiculeMaxUse();
        self::assertCount(1, $vehicules);
        self::assertSame('Clio', $vehicules[0]['modele']);

        self::assertEqualsCanonicalizing(
            [['modele' => 'Clio', 'nbLecon' => 3], ['modele' => 'MT-07', 'nbLecon' => 1]],
            $this->repository->nbLeconByModele(),
        );
    }

    public function testStatistiquesGraphiques(): void
    {
        self::assertCount(2, $this->repository->nbCategorie($this->eleve->getId()));
        self::assertEquals(4, $this->repository->nbLeconMoniteur($this->eleve->getId())[0]['nbLecons']);
        self::assertCount(2, $this->repository->prixCateg($this->moniteur->getId()));
        self::assertCount(2, $this->repository->nbLeconCateg($this->moniteur->getId()));
        self::assertEquals(4, $this->repository->nbLeconByMoniteur()[0]['nbLecon2']);
    }
}
