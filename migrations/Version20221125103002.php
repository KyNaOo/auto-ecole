<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221125103002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence ADD codeuser_id INT NOT NULL');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE64821DB6CB4 FOREIGN KEY (codeuser_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1DAAE64821DB6CB4 ON licence (codeuser_id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD sexe VARCHAR(25) DEFAULT NULL, ADD telephone VARCHAR(15) DEFAULT NULL, ADD adresse VARCHAR(100) DEFAULT NULL, ADD ville VARCHAR(100) DEFAULT NULL, ADD codepostale VARCHAR(10) DEFAULT NULL, ADD datenaissance DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE64821DB6CB4');
        $this->addSql('DROP INDEX IDX_1DAAE64821DB6CB4 ON licence');
        $this->addSql('ALTER TABLE licence DROP codeuser_id');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP sexe, DROP telephone, DROP adresse, DROP ville, DROP codepostale, DROP datenaissance');
    }
}
