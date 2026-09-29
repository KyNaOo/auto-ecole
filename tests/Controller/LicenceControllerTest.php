<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use App\Entity\Licence;
use App\Entity\User;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LicenceControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;
    private User $moniteur;
    private Categorie $categorie;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->loginUser($this->createUser('admin@test.fr', ['ROLE_ADMIN']));
        $this->moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR'], prenom: 'Paul');
        $this->categorie = $this->createCategorie('Moto');
    }

    public function testIndexAfficheLeMoniteur(): void
    {
        $this->createLicence($this->moniteur, $this->categorie);

        $this->client->request('GET', '/licence/');

        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextContains('td', 'Paul Test');
        self::assertAnySelectorTextContains('td', 'Moto');
    }

    public function testNew(): void
    {
        $this->client->request('GET', '/licence/new');
        $this->client->submitForm('Save', [
            'licence[codecategorie]' => (string) $this->categorie->getId(),
            'licence[dateobtention][year]' => '2018',
            'licence[dateobtention][month]' => '3',
            'licence[dateobtention][day]' => '12',
            'licence[codeuser]' => (string) $this->moniteur->getId(),
        ]);

        self::assertResponseRedirects('/licence/', 303);
        $licence = $this->em()->getRepository(Licence::class)->findOneBy([]);
        self::assertSame('2018-03-12', $licence->getDateobtention()->format('Y-m-d'));
        self::assertSame($this->moniteur->getId(), $licence->getCodeuser()->getId());
    }

    public function testShow(): void
    {
        $licence = $this->createLicence($this->moniteur, $this->categorie);

        $this->client->request('GET', '/licence/'.$licence->getId());

        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextContains('td', 'Paul Test');
        self::assertAnySelectorTextContains('td', '2015-06-01');
    }

    public function testEdit(): void
    {
        $id = $this->createLicence($this->moniteur, $this->categorie)->getId();

        $this->client->request('GET', "/licence/$id/edit");
        $this->client->submitForm('Update', ['licence[dateobtention][year]' => '2010']);

        self::assertResponseRedirects('/licence/', 303);
        self::assertSame('2010', $this->em()->find(Licence::class, $id)->getDateobtention()->format('Y'));
    }

    public function testDelete(): void
    {
        $id = $this->createLicence($this->moniteur, $this->categorie)->getId();

        $this->client->request('GET', "/licence/$id");
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/licence/', 303);
        self::assertNull($this->em()->find(Licence::class, $id));
    }
}
