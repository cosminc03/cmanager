<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170508141525 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user ADD registration_number VARCHAR(255) NOT NULL, ADD nationality VARCHAR(255) NOT NULL, ADD citizenship VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD year_of_study VARCHAR(255) NOT NULL, ADD gender VARCHAR(20) NOT NULL, ADD skype VARCHAR(255) DEFAULT NULL, ADD linked_in VARCHAR(255) DEFAULT NULL, ADD twitter VARCHAR(255) DEFAULT NULL, ADD gplus VARCHAR(255) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE NOT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647938CEDFBE ON fos_user (registration_number)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_957A647938CEDFBE ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP registration_number, DROP nationality, DROP citizenship, DROP description, DROP year_of_study, DROP gender, DROP skype, DROP linked_in, DROP twitter, DROP gplus, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE phone phone VARCHAR(128) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL');
    }
}
