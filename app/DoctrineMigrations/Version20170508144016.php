<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170508144016 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_957A647938CEDFBE ON fos_user');
        $this->addSql('ALTER TABLE fos_user CHANGE date_of_birth date_of_birth DATE DEFAULT NULL, CHANGE registration_number registration_number VARCHAR(255) DEFAULT NULL, CHANGE year_of_study year_of_study VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user CHANGE registration_number registration_number VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE date_of_birth date_of_birth DATE NOT NULL, CHANGE year_of_study year_of_study VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A647938CEDFBE ON fos_user (registration_number)');
    }
}
