<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021102248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence CHANGE codemoniteur codemoniteur_id INT NOT NULL');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE648E9D1A84D FOREIGN KEY (codemoniteur_id) REFERENCES moniteur (id)');
        $this->addSql('CREATE INDEX IDX_1DAAE648E9D1A84D ON licence (codemoniteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE648E9D1A84D');
        $this->addSql('DROP INDEX IDX_1DAAE648E9D1A84D ON licence');
        $this->addSql('ALTER TABLE licence CHANGE codemoniteur_id codemoniteur INT NOT NULL');
    }
}
