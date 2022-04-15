<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190720213507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE message_prive (id INT AUTO_INCREMENT NOT NULL, envoyeur_id INT NOT NULL, receveur_id INT NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, is_seen TINYINT(1) NOT NULL, INDEX IDX_2DB3B264795A786 (envoyeur_id), INDEX IDX_2DB3B26B967E626 (receveur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_prive ADD CONSTRAINT FK_2DB3B264795A786 FOREIGN KEY (envoyeur_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE message_prive ADD CONSTRAINT FK_2DB3B26B967E626 FOREIGN KEY (receveur_id) REFERENCES membre (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE message_prive');
    }
}
