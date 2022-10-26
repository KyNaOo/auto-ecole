<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025130815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon CHANGE immatriculation codevehicule_id INT NOT NULL');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242E1AF388F FOREIGN KEY (codevehicule_id) REFERENCES vehicule (id)');
        $this->addSql('CREATE INDEX IDX_94E6242E1AF388F ON lecon (codevehicule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242E1AF388F');
        $this->addSql('DROP INDEX IDX_94E6242E1AF388F ON lecon');
        $this->addSql('ALTER TABLE lecon ADD immatriculation VARCHAR(50) NOT NULL, DROP codevehicule_id');
    }
}
