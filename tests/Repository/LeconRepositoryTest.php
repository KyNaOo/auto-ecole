<?php

namespace App\Tests\Repository;

use App\Entity\Lecon;
use App\Repository\LeconRepository;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LeconRepositoryTest extends KernelTestCase
{
    use EntityFactoryTrait;

    private LeconRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = $this->em()->getRepository(Lecon::class);
    }

    public function testFindConflictingLessonsDetecteUnCreneauDejaPris(): void
    {
        $eleve = $this->createUser('eleve@test.fr');
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $vehicule = $this->createVehicule($this->createCategorie());
        $date = new \DateTime('2030-01-15 10:00');
        $this->createLecon($vehicule, $date, [$eleve, $moniteur]);

        $nouvelle = (new Lecon())->setDateStart(new \DateTime('2030-01-15 10:00'));
        self::assertCount(1, $this->repository->findConflictingLessons($nouvelle, $eleve->getId()));

        $autreCreneau = (new Lecon())->setDateStart(new \DateTime('2030-01-15 11:00'));
        self::assertCount(0, $this->repository->findConflictingLessons($autreCreneau, $eleve->getId()));
    }

    public function testFindConflictingLessonsIgnoreLaLeconElleMeme(): void
    {
        $eleve = $this->createUser('eleve@test.fr');
        $lecon = $this->createLecon($this->createVehicule($this->createCategorie()), new \DateTime('2030-01-15 10:00'), [$eleve]);

        self::assertCount(0, $this->repository->findConflictingLessons($lecon, $eleve->getId()));
    }

    public function testCheckMoniteur(): void
    {
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $autreMoniteur = $this->createUser('autre@test.fr', ['ROLE_MONITEUR']);
        $this->createLecon($this->createVehicule($this->createCategorie()), new \DateTime('2030-01-15 10:00'), [$moniteur]);

        self::assertCount(1, $this->repository->CheckMoniteur($moniteur, new \DateTime('2030-01-15 10:00')));
        self::assertCount(0, $this->repository->CheckMoniteur($autreMoniteur, new \DateTime('2030-01-15 10:00')));
        self::assertCount(0, $this->repository->CheckMoniteur($moniteur, new \DateTime('2030-01-15 14:00')));
    }

    public function testUserCalendarNeRetourneQueLesLeconsDeLUtilisateur(): void
    {
        $eleve = $this->createUser('eleve@test.fr');
        $autre = $this->createUser('autre@test.fr');
        $vehicule = $this->createVehicule($this->createCategorie());
        $this->createLecon($vehicule, new \DateTime('2030-01-15 10:00'), [$eleve]);
        $this->createLecon($vehicule, new \DateTime('2030-01-16 10:00'), [$eleve]);
        $this->createLecon($vehicule, new \DateTime('2030-01-17 10:00'), [$autre]);

        self::assertCount(2, $this->repository->userCalendar($eleve->getId()));
    }
}
