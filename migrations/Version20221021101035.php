<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021101035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence CHANGE codecategorie codecategorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE648AB09BC63 FOREIGN KEY (codecategorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_1DAAE648AB09BC63 ON licence (codecategorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE648AB09BC63');
        $this->addSql('DROP INDEX IDX_1DAAE648AB09BC63 ON licence');
        $this->addSql('ALTER TABLE licence CHANGE codecategorie_id codecategorie INT NOT NULL');
    }
}
