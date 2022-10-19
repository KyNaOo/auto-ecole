<?php

namespace App\Test\Controller;

use App\Entity\Licence;
use App\Repository\LicenceRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LicenceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private LicenceRepository $repository;
    private string $path = '/licence/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Licence::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Licence index');

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
            'licence[codelicence]' => 'Testing',
            'licence[codemoniteur]' => 'Testing',
            'licence[codecategorie]' => 'Testing',
            'licence[dateobtention]' => 'Testing',
        ]);

        self::assertResponseRedirects('/licence/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Licence();
        $fixture->setCodelicence('My Title');
        $fixture->setCodemoniteur('My Title');
        $fixture->setCodecategorie('My Title');
        $fixture->setDateobtention('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Licence');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Licence();
        $fixture->setCodelicence('My Title');
        $fixture->setCodemoniteur('My Title');
        $fixture->setCodecategorie('My Title');
        $fixture->setDateobtention('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'licence[codelicence]' => 'Something New',
            'licence[codemoniteur]' => 'Something New',
            'licence[codecategorie]' => 'Something New',
            'licence[dateobtention]' => 'Something New',
        ]);

        self::assertResponseRedirects('/licence/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCodelicence());
        self::assertSame('Something New', $fixture[0]->getCodemoniteur());
        self::assertSame('Something New', $fixture[0]->getCodecategorie());
        self::assertSame('Something New', $fixture[0]->getDateobtention());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Licence();
        $fixture->setCodelicence('My Title');
        $fixture->setCodemoniteur('My Title');
        $fixture->setCodecategorie('My Title');
        $fixture->setDateobtention('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/licence/');
    }
}
