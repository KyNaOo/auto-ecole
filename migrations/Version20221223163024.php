<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223163024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon DROP date, DROP heure, CHANGE date_end date_end DATETIME NULL, CHANGE date_start date_start DATETIME NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon ADD date DATE NOT NULL, ADD heure VARCHAR(10) NOT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE date_start date_start DATETIME DEFAULT NULL');
    }
}
