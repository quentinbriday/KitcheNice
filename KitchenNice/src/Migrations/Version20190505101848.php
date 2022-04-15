<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505101848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recette_ustensil (recette_id INT NOT NULL, ustensil_id INT NOT NULL, INDEX IDX_5012769D89312FE9 (recette_id), INDEX IDX_5012769DE62544B2 (ustensil_id), PRIMARY KEY(recette_id, ustensil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_type (recette_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_C1DEBD7289312FE9 (recette_id), INDEX IDX_C1DEBD72C54C8C93 (type_id), PRIMARY KEY(recette_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette_allergene (recette_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_20F5442B89312FE9 (recette_id), INDEX IDX_20F5442B4646AB2 (allergene_id), PRIMARY KEY(recette_id, allergene_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_ustensil ADD CONSTRAINT FK_5012769D89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_ustensil ADD CONSTRAINT FK_5012769DE62544B2 FOREIGN KEY (ustensil_id) REFERENCES ustensil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_type ADD CONSTRAINT FK_C1DEBD7289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_type ADD CONSTRAINT FK_C1DEBD72C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_allergene ADD CONSTRAINT FK_20F5442B89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_allergene ADD CONSTRAINT FK_20F5442B4646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE allergene_recette');
        $this->addSql('DROP TABLE type_recette');
        $this->addSql('DROP TABLE ustensil_recette');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allergene_recette (allergene_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_8A90E5624646AB2 (allergene_id), INDEX IDX_8A90E56289312FE9 (recette_id), PRIMARY KEY(allergene_id, recette_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type_recette (type_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_619A0C65C54C8C93 (type_id), INDEX IDX_619A0C6589312FE9 (recette_id), PRIMARY KEY(type_id, recette_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ustensil_recette (ustensil_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_D76D4BF6E62544B2 (ustensil_id), INDEX IDX_D76D4BF689312FE9 (recette_id), PRIMARY KEY(ustensil_id, recette_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE allergene_recette ADD CONSTRAINT FK_8A90E5624646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergene_recette ADD CONSTRAINT FK_8A90E56289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_recette ADD CONSTRAINT FK_619A0C6589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_recette ADD CONSTRAINT FK_619A0C65C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ustensil_recette ADD CONSTRAINT FK_D76D4BF689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ustensil_recette ADD CONSTRAINT FK_D76D4BF6E62544B2 FOREIGN KEY (ustensil_id) REFERENCES ustensil (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE recette_ustensil');
        $this->addSql('DROP TABLE recette_type');
        $this->addSql('DROP TABLE recette_allergene');
    }
}
