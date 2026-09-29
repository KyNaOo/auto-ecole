<?php

namespace App\Tests\Controller;

use App\Entity\Lecon;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Espace élève : réservation de leçons, mot de passe, planning et statistiques.
 */
final class UserControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;
    private User $eleve;
    private User $moniteur;
    private Vehicule $vehicule;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->eleve = $this->createUser('eleve@test.fr');
        $this->moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR'], prenom: 'Paul');
        $this->vehicule = $this->createVehicule($this->createCategorie());
        $this->client->loginUser($this->eleve);
    }

    public function testReserverUneLecon(): void
    {
        $this->reserver('2030-01-15', 10);

        self::assertResponseRedirects('/user', 303);
        $lecon = $this->em()->getRepository(Lecon::class)->findOneBy([]);
        self::assertSame('2030-01-15 10:00', $lecon->getDateStart()->format('Y-m-d H:i'));
        $participants = $lecon->getCodeuser()->map(fn (User $u) => $u->getEmail())->toArray();
        self::assertEqualsCanonicalizing(['eleve@test.fr', 'moniteur@test.fr'], $participants);
    }

    public function testReservationRefuseeSiLEleveADejaUneLecon(): void
    {
        $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve, $this->moniteur]);

        $this->reserver('2030-01-15', 10);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Il y a déjà une leçon à cette date/heure');
        self::assertSame(1, $this->em()->getRepository(Lecon::class)->count([]));
    }

    public function testReservationRefuseeSiLeMoniteurEstPrisEtPropositionDUnAutre(): void
    {
        $this->createUser('libre@test.fr', ['ROLE_MONITEUR'], prenom: 'Julie');
        $autreEleve = $this->createUser('autre@test.fr');
        $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$autreEleve, $this->moniteur]);

        $this->reserver('2030-01-15', 10);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Moniteur indisponible, essayer avec Julie');
        self::assertSame(1, $this->em()->getRepository(Lecon::class)->count([]));
    }

    public function testChangerSonMotDePasse(): void
    {
        $this->client->request('POST', '/user/edit/pass', ['pass' => 'nouveau123', 'pass2' => 'nouveau123']);

        self::assertResponseIsSuccessful();
        $eleve = $this->em()->find(User::class, $this->eleve->getId());
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        self::assertTrue($hasher->isPasswordValid($eleve, 'nouveau123'));
    }

    public function testChangementDeMotDePasseRefuseSiConfirmationDifferente(): void
    {
        $this->client->request('POST', '/user/edit/pass', ['pass' => 'nouveau123', 'pass2' => 'autre']);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'les deux mots de passe ne sont pas identiques');
        $eleve = $this->em()->find(User::class, $this->eleve->getId());
        self::assertTrue(static::getContainer()->get(UserPasswordHasherInterface::class)->isPasswordValid($eleve, 'azerty123'));
    }

    public function testPlanningContientLesLecons(): void
    {
        $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve, $this->moniteur]);

        $this->client->request('GET', '/user/planningE');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('2030-01-15 10:00:00', $this->client->getResponse()->getContent());
    }

    public function testPagesStatistiquesEtGraphiques(): void
    {
        $this->createLecon($this->vehicule, new \DateTime('2030-01-15 10:00'), [$this->eleve, $this->moniteur]);

        foreach (['/user', '/user/edit', '/user/stats', '/user/graph'] as $page) {
            $this->client->request('GET', $page);
            self::assertResponseIsSuccessful($page);
        }
    }

    public function testStatistiquesSansAucuneLecon(): void
    {
        $this->client->request('GET', '/user/stats');

        self::assertResponseIsSuccessful();
    }

    private function reserver(string $date, int $heure): void
    {
        $this->client->request('GET', '/'.$this->eleve->getId().'/newLecon');
        $this->client->submitForm('Save', [
            'lecon[dateStart][date]' => $date,
            'lecon[dateStart][time][hour]' => (string) $heure,
            'lecon[dateStart][time][minute]' => '0',
            'lecon[codevehicule]' => (string) $this->vehicule->getId(),
            'lecon[reglee]' => '0',
            'lecon[codeuser]' => (string) $this->moniteur->getId(),
        ]);
    }
}
