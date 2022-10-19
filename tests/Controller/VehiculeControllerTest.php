<?php

namespace App\Test\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehiculeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private VehiculeRepository $repository;
    private string $path = '/vehicule/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Vehicule::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'vehicule[immatriculation]' => 'Testing',
            'vehicule[marque]' => 'Testing',
            'vehicule[modele]' => 'Testing',
            'vehicule[annee]' => 'Testing',
            'vehicule[codecategorie]' => 'Testing',
        ]);

        self::assertResponseRedirects('/vehicule/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setAnnee('My Title');
        $fixture->setCodecategorie('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Vehicule');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Vehicule();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setAnnee('My Title');
        $fixture->setCodecategorie('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'vehicule[immatriculation]' => 'Something New',
            'vehicule[marque]' => 'Something New',
            'vehicule[modele]' => 'Something New',
            'vehicule[annee]' => 'Something New',
            'vehicule[codecategorie]' => 'Something New',
        ]);

        self::assertResponseRedirects('/vehicule/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getImmatriculation());
        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getModele());
        self::assertSame('Something New', $fixture[0]->getAnnee());
        self::assertSame('Something New', $fixture[0]->getCodecategorie());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Vehicule();
        $fixture->setImmatriculation('My Title');
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setAnnee('My Title');
        $fixture->setCodecategorie('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/vehicule/');
    }
}
