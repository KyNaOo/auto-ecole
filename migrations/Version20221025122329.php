<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025122329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon ADD codeeleve_id INT NOT NULL, ADD codemoniteur_id INT NOT NULL, DROP codemoniteur, DROP codeeleve');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242E46836253 FOREIGN KEY (codeeleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE lecon ADD CONSTRAINT FK_94E6242EE9D1A84D FOREIGN KEY (codemoniteur_id) REFERENCES moniteur (id)');
        $this->addSql('CREATE INDEX IDX_94E6242E46836253 ON lecon (codeeleve_id)');
        $this->addSql('CREATE INDEX IDX_94E6242EE9D1A84D ON lecon (codemoniteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242E46836253');
        $this->addSql('ALTER TABLE lecon DROP FOREIGN KEY FK_94E6242EE9D1A84D');
        $this->addSql('DROP INDEX IDX_94E6242E46836253 ON lecon');
        $this->addSql('DROP INDEX IDX_94E6242EE9D1A84D ON lecon');
        $this->addSql('ALTER TABLE lecon ADD codemoniteur INT NOT NULL, ADD codeeleve INT NOT NULL, DROP codeeleve_id, DROP codemoniteur_id');
    }
}
