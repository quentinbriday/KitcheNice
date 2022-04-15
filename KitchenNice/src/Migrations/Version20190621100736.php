<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190621100736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recette_cahier (recette_id INT NOT NULL, cahier_id INT NOT NULL, INDEX IDX_9D75E0E289312FE9 (recette_id), INDEX IDX_9D75E0E21E0D9F2 (cahier_id), PRIMARY KEY(recette_id, cahier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_cahier ADD CONSTRAINT FK_9D75E0E289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_cahier ADD CONSTRAINT FK_9D75E0E21E0D9F2 FOREIGN KEY (cahier_id) REFERENCES cahier (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE cahier_recette');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cahier_recette (cahier_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_46EF1B4689312FE9 (recette_id), INDEX IDX_46EF1B461E0D9F2 (cahier_id), PRIMARY KEY(cahier_id, recette_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cahier_recette ADD CONSTRAINT FK_46EF1B461E0D9F2 FOREIGN KEY (cahier_id) REFERENCES cahier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cahier_recette ADD CONSTRAINT FK_46EF1B4689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE recette_cahier');
    }
}
