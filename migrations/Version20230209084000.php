<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209084000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reparation (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(40) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(40) NOT NULL, date DATETIME NOT NULL, INDEX IDX_8FDF219D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(60) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(30) NOT NULL, last_names VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, profile VARCHAR(255) NOT NULL, role VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219D7E3C61F9');
        $this->addSql('DROP TABLE reparation');
        $this->addSql('DROP TABLE user');
    }
}
