<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260929203040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Schéma initial (regroupe les migrations 2022-2023)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE lecon (id INT AUTO_INCREMENT NOT NULL, reglee INT NOT NULL, date_start DATETIME NOT NULL, codevehicule_id INT NOT NULL, INDEX IDX_94E6242E1AF388F (codevehicule_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE lecon_user (lecon_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3955EC52EC1308A5 (lecon_id), INDEX IDX_3955EC52A76ED395 (user_id), PRIMARY KEY (lecon_id, user_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE licence (id INT AUTO_INCREMENT NOT NULL, dateobtention DATE DEFAULT NULL, codecategorie_id INT DEFAULT NULL, codeuser_id INT DEFAULT NULL, INDEX IDX_1DAAE648AB09BC63 (codecategorie_id), INDEX IDX_1DAAE64821DB6CB4 (codeuser_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, sexe VARCHAR(25) DEFAULT NULL, telephone VARCHAR(15) DEFAULT NULL, adresse VARCHAR(100) DEFAULT NULL, ville VARCHAR(100) DEFAULT NULL, codepostale VARCHAR(10) DEFAULT NULL, datenaissance DATE DEFAULT NULL, is_verified TINYINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vehicule (id INT AUTO_INCREMENT NOT NULL, immatriculation VARCHAR(50) NOT NULL, marque VARCHAR(50) NOT NULL, modele VARCHAR(50) NOT NULL, annee INT NOT NULL, codecategorie_id INT NOT NULL, INDEX IDX_292FFF1DAB09BC63 (codecategorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242E1AF388F FOREIGN KEY (codevehicule_id) REFERENCES vehicule (id)');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52EC1308A5 FOREIGN KEY (lecon_id) REFERENCES lecon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecon_user ADD CONSTRAINT FK_3955EC52A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE648AB09BC63 FOREIGN KEY (codecategorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE64821DB6CB4 FOREIGN KEY (codeuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DAB09BC63 FOREIGN KEY (codecategorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242E1AF388F');
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52EC1308A5');
        $this->addSql('ALTER TABLE lecon_user DROP FOREIGN KEY FK_3955EC52A76ED395');
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE648AB09BC63');
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE64821DB6CB4');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DAB09BC63');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE lecon');
        $this->addSql('DROP TABLE lecon_user');
        $this->addSql('DROP TABLE licence');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vehicule');
    }
}
