<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223173408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparation ADD reparator_id INT NOT NULL');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D3F25F9C3 FOREIGN KEY (reparator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8FDF219D3F25F9C3 ON reparation (reparator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219D3F25F9C3');
        $this->addSql('DROP INDEX IDX_8FDF219D3F25F9C3 ON reparation');
        $this->addSql('ALTER TABLE reparation DROP reparator_id');
    }
}
