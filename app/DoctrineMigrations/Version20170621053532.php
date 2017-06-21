<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170621053532 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE automailer (id INT AUTO_INCREMENT NOT NULL, from_email VARCHAR(255) NOT NULL, from_name VARCHAR(255) NOT NULL, to_email VARCHAR(255) NOT NULL, subject LONGTEXT NOT NULL, body LONGTEXT NOT NULL, alt_body LONGTEXT NOT NULL, swift_message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME DEFAULT NULL, started_sending_at DATETIME DEFAULT NULL, is_html TINYINT(1) NOT NULL, is_sending TINYINT(1) DEFAULT NULL, is_sent TINYINT(1) DEFAULT NULL, is_failed TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE automailer');
    }
}
