<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214161646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence CHANGE codecategorie_id codecategorie_id INT DEFAULT NULL, CHANGE codeuser_id codeuser_id INT DEFAULT NULL, CHANGE dateobtention dateobtention DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence CHANGE codecategorie_id codecategorie_id INT NOT NULL, CHANGE codeuser_id codeuser_id INT NOT NULL, CHANGE dateobtention dateobtention DATE NOT NULL');
    }
}
