<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190215081153 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, template VARCHAR(50) NOT NULL, subject VARCHAR(255) NOT NULL, `to` VARCHAR(255) NOT NULL, priority INT NOT NULL, sent TINYINT(1) NOT NULL, tries INT NOT NULL, date_created DATETIME NOT NULL, date_success DATETIME NOT NULL, date_updated DATETIME NOT NULL, data LONGTEXT NOT NULL, INDEX sent_index (sent), INDEX priority_index (priority), INDEX date_created_index (date_created), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trade CHANGE status status SMALLINT NOT NULL, CHANGE trade_type trade_type SMALLINT NOT NULL, CHANGE date_added date_added DATETIME NOT NULL');
        $this->addSql('ALTER TABLE photo CHANGE photo_order photo_order SMALLINT NOT NULL, CHANGE main main TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE trade_offer CHANGE listing_id listing_id INT DEFAULT NULL, CHANGE cash_value cash_value NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE user_type user_type SMALLINT NOT NULL, CHANGE rating rating NUMERIC(2, 2) DEFAULT NULL, CHANGE public public TINYINT(1) NOT NULL, CHANGE date_added date_added DATETIME NOT NULL, CHANGE date_modified date_modified DATETIME NOT NULL, CHANGE credits credits SMALLINT DEFAULT 3 NOT NULL, CHANGE membership_expires membership_expires DATETIME NOT NULL, CHANGE activation_expires activation_expires DATETIME NOT NULL');
        $this->addSql('ALTER TABLE saved_listing DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('CREATE INDEX IDX_41018743A76ED395 ON saved_listing (user_id)');
        $this->addSql('CREATE INDEX IDX_41018743D4619D1A ON saved_listing (listing_id)');
        $this->addSql('ALTER TABLE saved_listing ADD PRIMARY KEY (user_id, listing_id)');
        $this->addSql('ALTER TABLE listing CHANGE listing_type listing_type SMALLINT NOT NULL, CHANGE status status SMALLINT NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE qty qty SMALLINT NOT NULL, CHANGE trade_or_cash trade_or_cash SMALLINT NOT NULL, CHANGE shipping shipping TINYINT(1) NOT NULL, CHANGE date_added date_added DATETIME NOT NULL, CHANGE date_modified date_modified DATETIME NOT NULL, CHANGE availability availability DATETIME NOT NULL, CHANGE location location VARCHAR(255) NOT NULL, CHANGE brand brand VARCHAR(255) DEFAULT NULL, CHANGE color color VARCHAR(255) DEFAULT NULL, CHANGE material material VARCHAR(255) DEFAULT NULL, CHANGE listing_condition listing_condition SMALLINT NOT NULL, CHANGE views views INT NOT NULL, CHANGE local_trades_only local_trades_only TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE listing_tags ADD CONSTRAINT FK_384243F3D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE listing_tags ADD CONSTRAINT FK_384243F3BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE email');
        $this->addSql('ALTER TABLE listing CHANGE listing_type listing_type SMALLINT DEFAULT 1 NOT NULL, CHANGE status status SMALLINT DEFAULT 1 NOT NULL, CHANGE title title VARCHAR(75) NOT NULL COLLATE utf8_unicode_ci, CHANGE description description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE qty qty SMALLINT DEFAULT 0 NOT NULL, CHANGE listing_condition listing_condition VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE trade_or_cash trade_or_cash SMALLINT DEFAULT 3 NOT NULL, CHANGE shipping shipping TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE location location VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE brand brand VARCHAR(45) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE color color VARCHAR(45) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE material material VARCHAR(45) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE availability availability DATETIME NOT NULL, CHANGE local_trades_only local_trades_only TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE views views INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE listing_tags DROP FOREIGN KEY FK_384243F3D4619D1A');
        $this->addSql('ALTER TABLE listing_tags DROP FOREIGN KEY FK_384243F3BAD26311');
        $this->addSql('ALTER TABLE photo CHANGE photo_order photo_order SMALLINT DEFAULT 1 NOT NULL, CHANGE main main TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743A76ED395');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743D4619D1A');
        $this->addSql('DROP INDEX IDX_41018743A76ED395 ON saved_listing');
        $this->addSql('DROP INDEX IDX_41018743D4619D1A ON saved_listing');
        $this->addSql('ALTER TABLE saved_listing DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE saved_listing ADD PRIMARY KEY (listing_id, user_id)');
        $this->addSql('ALTER TABLE trade CHANGE status status SMALLINT DEFAULT 1 NOT NULL, CHANGE trade_type trade_type SMALLINT DEFAULT 1 NOT NULL, CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE trade_offer CHANGE listing_id listing_id INT DEFAULT NULL, CHANGE cash_value cash_value NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE first_name first_name VARCHAR(145) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE name name VARCHAR(145) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE email email VARCHAR(145) NOT NULL COLLATE utf8_unicode_ci, CHANGE user_type user_type SMALLINT DEFAULT 2 NOT NULL, CHANGE rating rating NUMERIC(5, 2) DEFAULT NULL, CHANGE public public TINYINT(1) DEFAULT \'2\' NOT NULL, CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_modified date_modified DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE membership_expires membership_expires DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE credits credits INT DEFAULT 3 NOT NULL, CHANGE activation_expires activation_expires DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
