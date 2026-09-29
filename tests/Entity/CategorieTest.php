<?php

namespace App\Tests\Entity;

use App\Entity\Categorie;
use App\Entity\Lecon;
use App\Entity\User;
use App\Entity\Vehicule;
use PHPUnit\Framework\TestCase;

final class CategorieTest extends TestCase
{
    public function testToStringRetourneLeLibelle(): void
    {
        self::assertSame('Moto', (string) (new Categorie())->setLibelle('Moto'));
    }

    public function testAddEtRemoveVehicule(): void
    {
        $categorie = new Categorie();
        $vehicule = new Vehicule();

        $categorie->addVehicule($vehicule);
        self::assertSame($categorie, $vehicule->getCodecategorie());
        self::assertCount(1, $categorie->getVehicules());

        $categorie->removeVehicule($vehicule);
        self::assertNull($vehicule->getCodecategorie());
        self::assertCount(0, $categorie->getVehicules());
    }

    public function testVehiculeToStringRetourneLaMarque(): void
    {
        self::assertSame('Peugeot', (string) (new Vehicule())->setMarque('Peugeot'));
    }

    public function testLeconNAjoutePasDeuxFoisLeMemeParticipant(): void
    {
        $lecon = new Lecon();
        $user = new User();

        $lecon->addCodeuser($user);
        $lecon->addCodeuser($user);

        self::assertCount(1, $lecon->getCodeuser());
    }
}
