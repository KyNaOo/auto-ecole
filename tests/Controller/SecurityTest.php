<?php

namespace App\Tests\Controller;

use App\Tests\Support\EntityFactoryTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPageDeConnexionAccessible(): void
    {
        $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('input[name="_username"]');
        self::assertSelectorExists('input[name="_password"]');
    }

    public static function roleProvider(): iterable
    {
        yield 'élève' => [['ROLE_USER'], '/user'];
        yield 'moniteur' => [['ROLE_MONITEUR'], '/moniteur'];
        yield 'admin' => [['ROLE_ADMIN'], '/admin'];
    }

    #[DataProvider('roleProvider')]
    public function testConnexionRedirigeVersLEspaceDuRole(array $roles, string $espace): void
    {
        $this->createUser('compte@test.fr', $roles);

        $this->client->request('POST', '/', ['_username' => 'compte@test.fr', '_password' => 'azerty123']);
        self::assertResponseRedirects('http://localhost/redirect');

        $this->client->followRedirect();
        self::assertResponseRedirects($espace);

        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function testConnexionAvecMauvaisMotDePasse(): void
    {
        $this->createUser('eleve@test.fr');

        $this->client->request('POST', '/', ['_username' => 'eleve@test.fr', '_password' => 'mauvais']);

        self::assertResponseRedirects('http://localhost/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('body', 'Invalid credentials');
    }

    public function testDeconnexion(): void
    {
        $this->client->loginUser($this->createUser('eleve@test.fr'));

        $this->client->request('GET', '/logout');

        self::assertResponseRedirects('http://localhost/');
        $this->client->request('GET', '/user');
        self::assertResponseRedirects('http://localhost/');
    }

    public static function pageProtegeeProvider(): iterable
    {
        foreach (['/admin', '/categorie/', '/vehicule/', '/licence/', '/lecon/', '/registerMoniteur', '/moniteur', '/user', '/1/newLecon'] as $path) {
            yield $path => [$path];
        }
    }

    #[DataProvider('pageProtegeeProvider')]
    public function testAnonymeRedirigeVersLaConnexion(string $path): void
    {
        $this->client->request('GET', $path);

        self::assertResponseRedirects('http://localhost/');
    }

    public static function accesInterditProvider(): iterable
    {
        foreach (['/admin', '/categorie/', '/vehicule/', '/licence/', '/lecon/', '/registerMoniteur', '/moniteur'] as $path) {
            yield "élève sur $path" => [['ROLE_USER'], $path];
        }
        foreach (['/admin', '/categorie/', '/vehicule/', '/licence/', '/lecon/', '/registerMoniteur'] as $path) {
            yield "moniteur sur $path" => [['ROLE_MONITEUR'], $path];
        }
    }

    #[DataProvider('accesInterditProvider')]
    public function testAccesInterditSelonLeRole(array $roles, string $path): void
    {
        $this->client->loginUser($this->createUser('compte@test.fr', $roles));

        $this->client->request('GET', $path);

        self::assertResponseStatusCodeSame(403);
    }
}
