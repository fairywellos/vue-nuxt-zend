<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015100434 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE etsy_tracking_users (user_id INT NOT NULL, etsy_id INT NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etsy_tracking_listings (listing_id INT NOT NULL, etsy_id INT NOT NULL, PRIMARY KEY(listing_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etsy_tracking_categories (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, etsy_id INT NOT NULL, INDEX IDX_E182EB1C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etsy_tracking_users ADD CONSTRAINT FK_8C8857BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etsy_tracking_listings ADD CONSTRAINT FK_441B797FD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etsy_tracking_categories ADD CONSTRAINT FK_E182EB1C12469DE2 FOREIGN KEY (category_id) REFERENCES main_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE etsy_tracking_users');
        $this->addSql('DROP TABLE etsy_tracking_listings');
        $this->addSql('DROP TABLE etsy_tracking_categories');
    }
}
