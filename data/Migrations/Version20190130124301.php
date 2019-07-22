<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130124301 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("TRUNCATE `main_category`");
        $this->addSql("INSERT INTO `main_category`(`id`, `name`, `icon`, `slug`) VALUES (1,'Art & Collectibles','art-collectibles.svg','art-collectibles'),(2,'Arts & Crafts','arts-crafts.svg','arts-crafts'),(3,'Experiences','experiences.svg','experiences'),(4,'Motors','motors.svg','motors'),(5,'Clothing & Accessories','clothing-accessories.svg','clothing-accessories'),(6,'Homes & Housing','homes-housing.svg','homes-housing'),(7,'Electronics','electronics.svg','electronics'),(8,'Services','services.svg','services'),(9,'Sporting Goods','sporting-goods.svg', 'sporting-goods'),(10,'Business & industrial','business-industrial.svg','business-industrial');");

    }

    public function down(Schema $schema) : void
    {
        $this->addSql("TRUNCATE `main_category`");
        $this->addSql("INSERT INTO `main_category`(`id`, `name`, `icon`) VALUES (1,'art-collectibles','art-collectibles.svg'),(2,'arts-crafts','arts-crafts.svg'),(3,'experiences','experiences.svg'),(4,'motors','motors.svg'),(5,'clothing-accessories','clothing-accessories.svg'),(6,'homes-housing','homes-housing.svg'),(7,'electronics','electronics.svg'),(8,'services','services.svg'),(9,'sporting-goods','sporting-goods.svg'),(10,'business-industrial','business-industrial.svg');");
    }
}
