<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190721213853 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, membre1_id INT NOT NULL, membre2_id INT NOT NULL, titre LONGTEXT NOT NULL, INDEX IDX_8A8E26E9400F1CEA (membre1_id), INDEX IDX_8A8E26E952BAB304 (membre2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9400F1CEA FOREIGN KEY (membre1_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E952BAB304 FOREIGN KEY (membre2_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE message_prive DROP FOREIGN KEY FK_2DB3B26B967E626');
        $this->addSql('DROP INDEX IDX_2DB3B26B967E626 ON message_prive');
        $this->addSql('ALTER TABLE message_prive DROP receveur_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE conversation');
        $this->addSql('ALTER TABLE message_prive ADD receveur_id INT NOT NULL');
        $this->addSql('ALTER TABLE message_prive ADD CONSTRAINT FK_2DB3B26B967E626 FOREIGN KEY (receveur_id) REFERENCES membre (id)');
        $this->addSql('CREATE INDEX IDX_2DB3B26B967E626 ON message_prive (receveur_id)');
    }
}
