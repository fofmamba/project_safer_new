<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228155558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bien ADD category_id INT NOT NULL, ADD prix VARCHAR(255) NOT NULL, CHANGE descriptif descriptif VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC38612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_45EDC38612469DE2 ON bien (category_id)');
        $this->addSql('ALTER TABLE departement CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_home CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE contenue contenue VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(255) DEFAULT \'NULL\', CHANGE mail mail VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC38612469DE2');
        $this->addSql('DROP INDEX IDX_45EDC38612469DE2 ON bien');
        $this->addSql('ALTER TABLE bien DROP category_id, DROP prix, CHANGE descriptif descriptif VARCHAR(255) DEFAULT \'NULL\', CHANGE image image VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE departement CHANGE code_postal code_postal VARCHAR(255) DEFAULT \'NULL\', CHANGE ville ville VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE slide_home CHANGE image image VARCHAR(255) DEFAULT \'NULL\', CHANGE titre titre VARCHAR(255) DEFAULT \'NULL\', CHANGE contenue contenue VARCHAR(255) DEFAULT \'NULL\'');
    }
}
