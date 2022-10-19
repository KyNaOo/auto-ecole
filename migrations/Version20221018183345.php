<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018183345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, codecategorie INT NOT NULL, libelle VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, ideleve INT NOT NULL, nomeleve VARCHAR(50) NOT NULL, prenomeleve VARCHAR(50) NOT NULL, sexeeleve VARCHAR(25) NOT NULL, datenaissance DATE NOT NULL, adresse VARCHAR(50) NOT NULL, codepostale VARCHAR(5) NOT NULL, ville VARCHAR(50) NOT NULL, telephone VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecon (id INT AUTO_INCREMENT NOT NULL, codelecon INT NOT NULL, date DATE NOT NULL, heure VARCHAR(10) NOT NULL, codemoniteur INT NOT NULL, codeeleve INT NOT NULL, immatriculation VARCHAR(50) NOT NULL, reglee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licence (id INT AUTO_INCREMENT NOT NULL, codelicence INT NOT NULL, codemoniteur INT NOT NULL, codecategorie INT NOT NULL, dateobtention DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moniteur (id INT AUTO_INCREMENT NOT NULL, codemoniteur INT NOT NULL, nommoniteur VARCHAR(50) NOT NULL, prenommoniteur VARCHAR(50) NOT NULL, sexemoniteur VARCHAR(25) NOT NULL, maissancemoniteur DATE NOT NULL, adressemoniteur VARCHAR(255) NOT NULL, codepostalemoniteur VARCHAR(5) NOT NULL, villemoniteur VARCHAR(50) NOT NULL, telephonemoniteur VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, immatriculation VARCHAR(50) NOT NULL, marque VARCHAR(50) NOT NULL, modele VARCHAR(50) NOT NULL, annee INT NOT NULL, codecategorie INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE lecon');
        $this->addSql('DROP TABLE licence');
        $this->addSql('DROP TABLE moniteur');
        $this->addSql('DROP TABLE vehicule');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
