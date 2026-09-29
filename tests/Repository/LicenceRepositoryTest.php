<?php

namespace App\Tests\Repository;

use App\Entity\Licence;
use App\Repository\LicenceRepository;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LicenceRepositoryTest extends KernelTestCase
{
    use EntityFactoryTrait;

    private LicenceRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = $this->em()->getRepository(Licence::class);
    }

    public function testFindConflictingLicensesDetecteUneLicenceEnDouble(): void
    {
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $auto = $this->createCategorie('Automobile');
        $moto = $this->createCategorie('Moto');
        $this->createLicence($moniteur, $auto);

        self::assertCount(1, $this->repository->findConflictingLicenses((new Licence())->setCodecategorie($auto), $moniteur->getId()));
        self::assertCount(0, $this->repository->findConflictingLicenses((new Licence())->setCodecategorie($moto), $moniteur->getId()));
    }

    public function testFindConflictingLicensesEstPropreAuMoniteur(): void
    {
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $autre = $this->createUser('autre@test.fr', ['ROLE_MONITEUR']);
        $auto = $this->createCategorie('Automobile');
        $this->createLicence($autre, $auto);

        self::assertCount(0, $this->repository->findConflictingLicenses((new Licence())->setCodecategorie($auto), $moniteur->getId()));
    }

    public function testUserLicence(): void
    {
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $this->createLicence($moniteur, $this->createCategorie('Automobile'));
        $this->createLicence($moniteur, $this->createCategorie('Moto'));
        $this->createLicence($this->createUser('autre@test.fr', ['ROLE_MONITEUR']), $this->createCategorie('Bus'));

        self::assertCount(2, $this->repository->userLicence($moniteur->getId()));
    }
}
