<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314085823 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366D4619D1A');
        $this->addSql('ALTER TABLE trade CHANGE listing_id listing_id INT DEFAULT NULL, CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418D4619D1A');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDD4619D1A');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743A76ED395');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743D4619D1A');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listing_tags DROP FOREIGN KEY FK_384243F3D4619D1A');
        $this->addSql('ALTER TABLE listing_tags ADD CONSTRAINT FK_384243F3D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE listing_tags DROP FOREIGN KEY FK_384243F3D4619D1A');
        $this->addSql('ALTER TABLE listing_tags ADD CONSTRAINT FK_384243F3D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418D4619D1A');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743A76ED395');
        $this->addSql('ALTER TABLE saved_listing DROP FOREIGN KEY FK_41018743D4619D1A');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE saved_listing ADD CONSTRAINT FK_41018743D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366D4619D1A');
        $this->addSql('ALTER TABLE trade CHANGE listing_id listing_id INT NOT NULL, CHANGE date_added date_added DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366D4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDD4619D1A');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id)');
    }
}
