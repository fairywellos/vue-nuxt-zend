<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191028103224 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tracking_requests (id INT AUTO_INCREMENT NOT NULL, site_name VARCHAR(255) NOT NULL, request INT NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TRIGGER `tracking_request_trigger` BEFORE UPDATE ON `tracking_requests` FOR EACH ROW BEGIN SET NEW.`request` = if(DATE_FORMAT(OLD.`date`, "%Y-%m-%d") < DATE_FORMAT(CURDATE(), "%Y-%m-%d"), 0, NEW.`request`); SET NEW.`date` = CURDATE(); END');
        $this->addSql("INSERT INTO `tracking_requests` (`id`, `site_name`, `request`, `date`) VALUES (NULL, 'etsy', '0', CURRENT_TIMESTAMP)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tracking_requests');
        $this->addSql('DROP TRIGGER IF EXISTS `tracking_requests_trigger`;');

    }
}
