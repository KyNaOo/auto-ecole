<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use App\Entity\Vehicule;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VehiculeControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;
    private Categorie $categorie;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->loginUser($this->createUser('admin@test.fr', ['ROLE_ADMIN']));
        $this->categorie = $this->createCategorie('Automobile');
    }

    public function testIndex(): void
    {
        $this->createVehicule($this->categorie, 'Peugeot', '208');

        $this->client->request('GET', '/vehicule/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Vehicule index');
        self::assertAnySelectorTextContains('td', 'Peugeot');
    }

    public function testNew(): void
    {
        $this->client->request('GET', '/vehicule/new');
        $this->client->submitForm('Save', [
            'vehicule[immatriculation]' => 'EF-456-GH',
            'vehicule[marque]' => 'Citroën',
            'vehicule[modele]' => 'C3',
            'vehicule[annee]' => '2021',
            'vehicule[codecategorie]' => (string) $this->categorie->getId(),
        ]);

        self::assertResponseRedirects('/vehicule/', 303);
        $vehicule = $this->em()->getRepository(Vehicule::class)->findOneBy(['immatriculation' => 'EF-456-GH']);
        self::assertNotNull($vehicule);
        self::assertSame('C3', $vehicule->getModele());
        self::assertSame($this->categorie->getId(), $vehicule->getCodecategorie()->getId());
    }

    public function testNewRefuseUnFormulaireIncomplet(): void
    {
        $this->client->request('GET', '/vehicule/new');

        $this->client->submitForm('Save', ['vehicule[immatriculation]' => '', 'vehicule[marque]' => '']);

        self::assertResponseStatusCodeSame(422);
        self::assertSame(0, $this->em()->getRepository(Vehicule::class)->count([]));
    }

    public function testShow(): void
    {
        $vehicule = $this->createVehicule($this->categorie, 'Toyota', 'Yaris');

        $this->client->request('GET', '/vehicule/'.$vehicule->getId());

        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextContains('td', 'Yaris');
        self::assertAnySelectorTextContains('td', 'Automobile');
    }

    public function testEdit(): void
    {
        $id = $this->createVehicule($this->categorie)->getId();

        $this->client->request('GET', "/vehicule/$id/edit");
        $this->client->submitForm('Update', ['vehicule[modele]' => 'Megane', 'vehicule[annee]' => '2023']);

        self::assertResponseRedirects('/vehicule/', 303);
        $vehicule = $this->em()->find(Vehicule::class, $id);
        self::assertSame('Megane', $vehicule->getModele());
        self::assertSame(2023, $vehicule->getAnnee());
    }

    public function testDelete(): void
    {
        $id = $this->createVehicule($this->categorie)->getId();

        $this->client->request('GET', "/vehicule/$id");
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/vehicule/', 303);
        self::assertNull($this->em()->find(Vehicule::class, $id));
    }
}
