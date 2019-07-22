<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314133931 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDC2D9760');
        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDD4619D1A');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDC2D9760 FOREIGN KEY (trade_id) REFERENCES trade (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDC2D9760');
        $this->addSql('ALTER TABLE trade_offer DROP FOREIGN KEY FK_3DB57DDD4619D1A');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDC2D9760 FOREIGN KEY (trade_id) REFERENCES trade (id)');
        $this->addSql('ALTER TABLE trade_offer ADD CONSTRAINT FK_3DB57DDD4619D1A FOREIGN KEY (listing_id) REFERENCES listing (id) ON DELETE SET NULL');
    }
}
