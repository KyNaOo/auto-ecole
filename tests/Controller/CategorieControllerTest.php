<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CategorieControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->loginUser($this->createUser('admin@test.fr', ['ROLE_ADMIN']));
    }

    public function testIndex(): void
    {
        $this->createCategorie('Moto', 100);

        $this->client->request('GET', '/categorie/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Liste des categories');
        self::assertAnySelectorTextContains('td', 'Moto');
    }

    public function testNew(): void
    {
        $this->client->request('GET', '/categorie/new');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Save', [
            'categorie[libelle]' => 'Bateau',
            'categorie[prix]' => '300.5',
        ]);

        self::assertResponseRedirects('/categorie/', 303);
        $categorie = $this->em()->getRepository(Categorie::class)->findOneBy(['libelle' => 'Bateau']);
        self::assertNotNull($categorie);
        self::assertSame(300.5, $categorie->getPrix());
    }

    public function testNewRefuseUnFormulaireIncomplet(): void
    {
        $this->client->request('GET', '/categorie/new');

        $this->client->submitForm('Save', ['categorie[libelle]' => '', 'categorie[prix]' => '']);

        self::assertResponseStatusCodeSame(422);
        self::assertSame(0, $this->em()->getRepository(Categorie::class)->count([]));
    }

    public function testShow(): void
    {
        $categorie = $this->createCategorie('Camion', 85.5);

        $this->client->request('GET', '/categorie/'.$categorie->getId());

        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextContains('td', 'Camion');
        self::assertAnySelectorTextContains('td', '85.5');
    }

    public function testEdit(): void
    {
        $id = $this->createCategorie('Auto', 50)->getId();

        $this->client->request('GET', "/categorie/$id/edit");
        $this->client->submitForm('Update', [
            'categorie[libelle]' => 'Automobile',
            'categorie[prix]' => '55',
        ]);

        self::assertResponseRedirects('/categorie/', 303);
        $categorie = $this->em()->find(Categorie::class, $id);
        self::assertSame('Automobile', $categorie->getLibelle());
        self::assertSame(55.0, $categorie->getPrix());
    }

    public function testDelete(): void
    {
        $id = $this->createCategorie('Bus', 200)->getId();

        $this->client->request('GET', "/categorie/$id");
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/categorie/', 303);
        self::assertNull($this->em()->find(Categorie::class, $id));
    }
}
