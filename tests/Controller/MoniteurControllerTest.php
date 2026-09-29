<?php

namespace App\Tests\Controller;

use App\Entity\Categorie;
use App\Entity\Licence;
use App\Entity\User;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Espace moniteur : licences, planning et statistiques.
 */
final class MoniteurControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;
    private User $moniteur;
    private Categorie $categorie;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->moniteur = $this->createUser('moniteur@test.fr', ['ROLE_MONITEUR'], prenom: 'Paul');
        $this->categorie = $this->createCategorie('Moto');
        $this->client->loginUser($this->moniteur);
    }

    public function testAjouterUneLicence(): void
    {
        $this->ajouterLicence();

        self::assertResponseRedirects('/moniteur', 303);
        $licence = $this->em()->getRepository(Licence::class)->findOneBy([]);
        self::assertSame($this->moniteur->getId(), $licence->getCodeuser()->getId());
        self::assertSame('Moto', $licence->getCodecategorie()->getLibelle());
    }

    public function testAjoutRefuseSiLeMoniteurADejaLaLicence(): void
    {
        $this->createLicence($this->moniteur, $this->categorie);

        $this->ajouterLicence();

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Vous avez déjà cette licence');
        self::assertSame(1, $this->em()->getRepository(Licence::class)->count([]));
    }

    public function testListeDeSesLicences(): void
    {
        $this->createLicence($this->moniteur, $this->categorie);
        $this->createLicence($this->createUser('autre@test.fr', ['ROLE_MONITEUR']), $this->createCategorie('Bus'));

        $this->client->request('GET', '/moniteur/VosLicences');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Moto', $this->client->getResponse()->getContent());
        self::assertStringNotContainsString('Bus', $this->client->getResponse()->getContent());
    }

    public function testPagesStatistiquesEtGraphiques(): void
    {
        $vehicule = $this->createVehicule($this->categorie);
        $this->createLecon($vehicule, new \DateTime('2030-01-15 10:00'), [$this->createUser('eleve@test.fr'), $this->moniteur]);

        foreach (['/moniteur', '/moniteur/edit', '/moniteur/stats', '/moniteur/graph', '/user/planning'] as $page) {
            $this->client->request('GET', $page);
            self::assertResponseIsSuccessful($page);
        }
    }

    private function ajouterLicence(): void
    {
        $this->client->request('GET', '/moniteur/addLicence');
        $this->client->submitForm('Save', [
            'licence[codecategorie]' => (string) $this->categorie->getId(),
            'licence[dateobtention][year]' => '2019',
            'licence[dateobtention][month]' => '5',
            'licence[dateobtention][day]' => '20',
        ]);
    }
}
