<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423212038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, duree_preparation TIME NOT NULL, difficulte VARCHAR(255) NOT NULL, duree_cuisson TIME NOT NULL, nb_personnes INT NOT NULL, cout DOUBLE PRECISION NOT NULL, remarque VARCHAR(255) DEFAULT NULL, is_public TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, date_derniere_modif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, recette_id INT NOT NULL, numero INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_285F75DD89312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, membre_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_7D053A936A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_recette (menu_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_C1607E2ECCD7E912 (menu_id), INDEX IDX_C1607E2E89312FE9 (recette_id), PRIMARY KEY(menu_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cahier (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, is_public TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, date_derniere_modif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_recette (type_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_619A0C65C54C8C93 (type_id), INDEX IDX_619A0C6589312FE9 (recette_id), PRIMARY KEY(type_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_membre (type_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_D5639011C54C8C93 (type_id), INDEX IDX_D56390116A99F74A (membre_id), PRIMARY KEY(type_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantite (id INT AUTO_INCREMENT NOT NULL, recette_id INT NOT NULL, ingredient_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, unite_mesure VARCHAR(255) NOT NULL, INDEX IDX_8BF24A7989312FE9 (recette_id), INDEX IDX_8BF24A79933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, date_derniere_modif DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allergene (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allergene_recette (allergene_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_8A90E5624646AB2 (allergene_id), INDEX IDX_8A90E56289312FE9 (recette_id), PRIMARY KEY(allergene_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ustensil (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ustensil_recette (ustensil_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_D76D4BF6E62544B2 (ustensil_id), INDEX IDX_D76D4BF689312FE9 (recette_id), PRIMARY KEY(ustensil_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A936A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE menu_recette ADD CONSTRAINT FK_C1607E2ECCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_recette ADD CONSTRAINT FK_C1607E2E89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_recette ADD CONSTRAINT FK_619A0C65C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_recette ADD CONSTRAINT FK_619A0C6589312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_membre ADD CONSTRAINT FK_D5639011C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_membre ADD CONSTRAINT FK_D56390116A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A7989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A79933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE allergene_recette ADD CONSTRAINT FK_8A90E5624646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE allergene_recette ADD CONSTRAINT FK_8A90E56289312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ustensil_recette ADD CONSTRAINT FK_D76D4BF6E62544B2 FOREIGN KEY (ustensil_id) REFERENCES ustensil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ustensil_recette ADD CONSTRAINT FK_D76D4BF689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD89312FE9');
        $this->addSql('ALTER TABLE menu_recette DROP FOREIGN KEY FK_C1607E2E89312FE9');
        $this->addSql('ALTER TABLE type_recette DROP FOREIGN KEY FK_619A0C6589312FE9');
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A7989312FE9');
        $this->addSql('ALTER TABLE allergene_recette DROP FOREIGN KEY FK_8A90E56289312FE9');
        $this->addSql('ALTER TABLE ustensil_recette DROP FOREIGN KEY FK_D76D4BF689312FE9');
        $this->addSql('ALTER TABLE menu_recette DROP FOREIGN KEY FK_C1607E2ECCD7E912');
        $this->addSql('ALTER TABLE type_recette DROP FOREIGN KEY FK_619A0C65C54C8C93');
        $this->addSql('ALTER TABLE type_membre DROP FOREIGN KEY FK_D5639011C54C8C93');
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A79933FE08C');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A936A99F74A');
        $this->addSql('ALTER TABLE type_membre DROP FOREIGN KEY FK_D56390116A99F74A');
        $this->addSql('ALTER TABLE allergene_recette DROP FOREIGN KEY FK_8A90E5624646AB2');
        $this->addSql('ALTER TABLE ustensil_recette DROP FOREIGN KEY FK_D76D4BF6E62544B2');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_recette');
        $this->addSql('DROP TABLE cahier');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_recette');
        $this->addSql('DROP TABLE type_membre');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE quantite');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE allergene');
        $this->addSql('DROP TABLE allergene_recette');
        $this->addSql('DROP TABLE ustensil');
        $this->addSql('DROP TABLE ustensil_recette');
    }
}
