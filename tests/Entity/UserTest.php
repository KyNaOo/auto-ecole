<?php

namespace App\Tests\Entity;

use App\Entity\Lecon;
use App\Entity\Licence;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGetRolesAjouteToujoursRoleUser(): void
    {
        $user = (new User())->setRoles(['ROLE_MONITEUR']);

        self::assertSame(['ROLE_MONITEUR', 'ROLE_USER'], $user->getRoles());
    }

    public function testGetRolesNeDupliquePasRoleUser(): void
    {
        $user = (new User())->setRoles(['ROLE_USER']);

        self::assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testUserIdentifierEstLEmail(): void
    {
        $user = (new User())->setEmail('eleve@test.fr');

        self::assertSame('eleve@test.fr', $user->getUserIdentifier());
    }

    public function testNonVerifieParDefaut(): void
    {
        self::assertFalse((new User())->isVerified());
    }

    public function testAddLicenceRenseigneLeProprietaire(): void
    {
        $user = new User();
        $licence = new Licence();

        $user->addLicence($licence);
        $user->addLicence($licence);

        self::assertCount(1, $user->getLicences());
        self::assertSame($user, $licence->getCodeuser());
    }

    public function testRemoveLicenceDetacheLeProprietaire(): void
    {
        $user = new User();
        $licence = new Licence();
        $user->addLicence($licence);

        $user->removeLicence($licence);

        self::assertCount(0, $user->getLicences());
        self::assertNull($licence->getCodeuser());
    }

    public function testAddLeconSynchroniseLesDeuxCotes(): void
    {
        $user = new User();
        $lecon = new Lecon();

        $user->addLecon($lecon);

        self::assertTrue($user->getLecons()->contains($lecon));
        self::assertTrue($lecon->getCodeuser()->contains($user));

        $user->removeLecon($lecon);

        self::assertFalse($user->getLecons()->contains($lecon));
        self::assertFalse($lecon->getCodeuser()->contains($user));
    }
}
