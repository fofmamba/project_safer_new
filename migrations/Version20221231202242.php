<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221231202242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bien ADD is_in_home TINYINT(1) NOT NULL, CHANGE descriptif descriptif VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE departement CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris CHANGE numero numero VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_home CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE contenue contenue VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(255) DEFAULT \'NULL\', CHANGE mail mail VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE bien DROP is_in_home, CHANGE descriptif descriptif VARCHAR(255) DEFAULT \'NULL\', CHANGE image image VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE departement CHANGE code_postal code_postal VARCHAR(255) DEFAULT \'NULL\', CHANGE ville ville VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE favoris CHANGE numero numero VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE slide_home CHANGE image image VARCHAR(255) DEFAULT \'NULL\', CHANGE titre titre VARCHAR(255) DEFAULT \'NULL\', CHANGE contenue contenue VARCHAR(255) DEFAULT \'NULL\'');
    }
}
