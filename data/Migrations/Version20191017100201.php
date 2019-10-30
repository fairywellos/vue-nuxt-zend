<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191017100201 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE etsy_tracking_listings ADD url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE etsy_tracking_categories ADD name VARCHAR(255) NOT NULL');
        $this->addSql("
            INSERT INTO `etsy_tracking_categories` (`category_id`, `etsy_id`, `name`) VALUES 
            (5,69150467,'Accessories'), 
            (1,68887312,'Art'), 
            (5,69150455,'Bags and Purses'), 
            (9,68887336,'Bath and Beauty'), 
            (1,69150385,'Books and Zines'), 
            (2,69150375,'Candles'), 
            (1,69150451,'Ceramics and Pottery'), 
            (5,69150405,'Children'), 
            (5,69150353,'Clothing'), 
            (2,69150341,'Crochet'), 
            (1,69150415,'Dolls and Miniatures'), 
            (10,68887416,'Everything Else'), 
            (2,68887430,'Furniture'), 
            (7,69150359,'Geekery'), 
            (2,69150361,'Glass'), 
            (3,68887366,'Holidays'), 
            (6,69150425,'Housewares'), 
            (1,68887482,'Jewelry'), 
            (2,68887400,'Knitting'), 
            (7,68887460,'Music'), 
            (2,68887406,'Needlecraft'), 
            (2,69150367,'Paper Goods'), 
            (2,68887486,'Patterns'), 
            (5,68887434,'Pets'), 
            (2,68887346,'Plants and Edibles'), 
            (2,68887502,'Quilts'), 
            (10,69150433,'Supplies'), 
            (7,69150393,'Toys'), 
            (1,69150437,'Vintage'), 
            (2,68887494,'Weddings'), 
            (2,68887388,'Woodworking')
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
