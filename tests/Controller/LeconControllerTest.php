<?php

namespace App\Tests\Controller;

use App\Entity\Lecon;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LeconControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;
    private User $eleve;
    private User $moniteur;
    private Vehicule $vehicule;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->loginUser($this->createUser('admin@test.fr', ['ROLE_ADMIN']));
        $this->eleve = $this->createUser('eleve@test.fr');
        $this->moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $this->vehicule = $this->createVehicule($this->createCategorie());
    }

    public function testIndex(): void
    {
        $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve, $this->moniteur]);

        $this->client->request('GET', '/lecon/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Liste de leçon');
    }

    public function testNew(): void
    {
        $this->client->request('GET', '/lecon/new');
        $this->client->submitForm('Save', $this->formData('2030-01-15', 10));

        self::assertResponseRedirects('/lecon/', 303);
        $lecon = $this->em()->getRepository(Lecon::class)->findOneBy([]);
        self::assertSame('2030-01-15 10:00', $lecon->getDateStart()->format('Y-m-d H:i'));
        self::assertSame(1, $lecon->getReglee());
    }

    public function testNewRefuseUneDatePassee(): void
    {
        $this->client->request('GET', '/lecon/new');
        $this->client->submitForm('Save', $this->formData('2020-01-15', 10));

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('body', 'Vous ne pouvez pas sélectionner une date antérieure!');
        self::assertSame(0, $this->em()->getRepository(Lecon::class)->count([]));
    }

    public function testShow(): void
    {
        $lecon = $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve]);

        $this->client->request('GET', '/lecon/'.$lecon->getId());

        self::assertResponseIsSuccessful();
        self::assertAnySelectorTextContains('td', '2030-01-15 10:00:00');
    }

    public function testEdit(): void
    {
        $id = $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve])->getId();

        $this->client->request('GET', "/lecon/$id/edit");
        $this->client->submitForm('Update', $this->formData('2030-02-20', 14));

        self::assertResponseRedirects('/lecon/', 303);
        self::assertSame('2030-02-20 14:00', $this->em()->find(Lecon::class, $id)->getDateStart()->format('Y-m-d H:i'));
    }

    public function testDelete(): void
    {
        $id = $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve])->getId();

        $this->client->request('GET', "/lecon/$id");
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/lecon/', 303);
        self::assertNull($this->em()->find(Lecon::class, $id));
    }

    private function formData(string $date, int $heure): array
    {
        return [
            'lecon[dateStart][date]' => $date,
            'lecon[dateStart][time][hour]' => (string) $heure,
            'lecon[dateStart][time][minute]' => '0',
            'lecon[codevehicule]' => (string) $this->vehicule->getId(),
            'lecon[reglee]' => '1',
            'lecon[codeuser]' => (string) $this->moniteur->getId(),
        ];
    }
}
