<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190215082802 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE email CHANGE date_created date_created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_success date_success DATETIME DEFAULT NULL, CHANGE date_updated date_updated DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE membership_expires membership_expires DATETIME NOT NULL, CHANGE activation_expires activation_expires DATETIME NOT NULL');
        $this->addSql('ALTER TABLE listing CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE availability availability DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email CHANGE date_created date_created DATETIME NOT NULL, CHANGE date_success date_success DATETIME NOT NULL, CHANGE date_updated date_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE listing CHANGE date_added date_added DATETIME NOT NULL, CHANGE date_modified date_modified DATETIME NOT NULL, CHANGE availability availability DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trade CHANGE date_added date_added DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE date_added date_added DATETIME NOT NULL, CHANGE date_modified date_modified DATETIME NOT NULL, CHANGE membership_expires membership_expires DATETIME NOT NULL, CHANGE activation_expires activation_expires DATETIME NOT NULL');
    }
}
