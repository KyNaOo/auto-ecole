<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->loginUser($this->createUser('admin@test.fr', ['ROLE_ADMIN']));
    }

    public function testPagesAdmin(): void
    {
        $moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR']);
        $vehicule = $this->createVehicule($this->createCategorie());
        $this->createLecon($vehicule, new \DateTime('2030-01-15 10:00'), [$this->createUser('eleve@test.fr'), $moniteur]);

        foreach (['/admin', '/admin/edit', '/admin/stats', '/admin/graph'] as $page) {
            $this->client->request('GET', $page);
            self::assertResponseIsSuccessful($page);
        }
    }

    public function testStatistiquesSansAucuneLecon(): void
    {
        $this->client->request('GET', '/admin/stats');

        self::assertResponseIsSuccessful();
    }

    public function testCreerUnMoniteur(): void
    {
        $this->client->request('GET', '/registerMoniteur');
        $this->client->submitForm('Valider', [
            'registration_moniteur[email]' => 'nouveau.moniteur@test.fr',
            'registration_moniteur[password]' => 'secret123',
            'registration_moniteur[nom]' => 'Martin',
            'registration_moniteur[prenom]' => 'Luc',
            'registration_moniteur[sexe]' => 'Homme',
        ]);

        self::assertResponseRedirects('/admin');
        $moniteur = $this->em()->getRepository(User::class)->findOneBy(['email' => 'nouveau.moniteur@test.fr']);
        self::assertContains('ROLE_MONITEUR', $moniteur->getRoles());
        self::assertSame('Luc', $moniteur->getPrenom());
        self::assertTrue(static::getContainer()->get(UserPasswordHasherInterface::class)->isPasswordValid($moniteur, 'secret123'));
    }
}
