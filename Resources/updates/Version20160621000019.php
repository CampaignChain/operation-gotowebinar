<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160621000019 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE campaignchain_operation_gotowebinar_webinar (id INT AUTO_INCREMENT NOT NULL, operation_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, organizerKey VARCHAR(255) DEFAULT NULL, webinarKey VARCHAR(255) DEFAULT NULL, timeZone VARCHAR(40) NOT NULL, description LONGTEXT NOT NULL, registrationUrl VARCHAR(255) DEFAULT NULL, createdDate DATETIME NOT NULL, modifiedDate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_59F5E82EFBCE3E7A (subject), UNIQUE INDEX UNIQ_59F5E82E44AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campaignchain_operation_gotowebinar_webinar ADD CONSTRAINT FK_59F5E82E44AC3583 FOREIGN KEY (operation_id) REFERENCES campaignchain_operation (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE campaignchain_operation_gotowebinar_webinar');
    }
}
