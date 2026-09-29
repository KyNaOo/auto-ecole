<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Support\EntityFactoryTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegistrationControllerTest extends WebTestCase
{
    use EntityFactoryTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testInscriptionCreeUnEleveEtEnvoieLeMailDeConfirmation(): void
    {
        $this->client->request('GET', '/register');
        $this->client->submitForm('Register', ['registration_form[email]' => 'nouvel.eleve@test.fr']);

        self::assertResponseRedirects('/');
        self::assertEmailCount(1);
        $email = self::getMailerMessage();
        self::assertEmailAddressContains($email, 'To', 'nouvel.eleve@test.fr');
        self::assertEmailHtmlBodyContains($email, '/verify/email');

        $eleve = $this->em()->getRepository(User::class)->findOneBy(['email' => 'nouvel.eleve@test.fr']);
        self::assertSame(['ROLE_USER'], $eleve->getRoles());
        self::assertFalse($eleve->isVerified());
    }

    public function testInscriptionRefuseeSiLEmailExisteDeja(): void
    {
        $this->createUser('deja@test.fr');

        $this->client->request('GET', '/register');
        $this->client->submitForm('Register', ['registration_form[email]' => 'deja@test.fr']);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'There is already an account with this email');
        self::assertEmailCount(0);
        self::assertSame(1, $this->em()->getRepository(User::class)->count(['email' => 'deja@test.fr']));
    }
}
