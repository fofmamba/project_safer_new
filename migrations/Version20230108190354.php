<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108190354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE bien ADD surface VARCHAR(255) NOT NULL, ADD departement VARCHAR(255) NOT NULL, CHANGE descriptif descriptif VARCHAR(255) DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris CHANGE numero numero VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur CHANGE nom nom VARCHAR(255) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(255) DEFAULT \'NULL\', CHANGE mail mail VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE bien DROP surface, DROP departement, CHANGE descriptif descriptif VARCHAR(255) DEFAULT \'NULL\', CHANGE image image VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE favoris CHANGE numero numero VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
