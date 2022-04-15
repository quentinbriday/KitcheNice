<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190604081058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE membre_membre (membre_source INT NOT NULL, membre_target INT NOT NULL, INDEX IDX_6CBD6E90C583EACA (membre_source), INDEX IDX_6CBD6E90DC66BA45 (membre_target), PRIMARY KEY(membre_source, membre_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membre_membre ADD CONSTRAINT FK_6CBD6E90C583EACA FOREIGN KEY (membre_source) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre_membre ADD CONSTRAINT FK_6CBD6E90DC66BA45 FOREIGN KEY (membre_target) REFERENCES membre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE membre_membre');
    }
}
