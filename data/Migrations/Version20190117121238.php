<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190117121238 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO `main_category`(`id`, `name`, `icon`) VALUES (1,'art-collectibles','art-collectibles.svg'),(2,'arts-crafts','arts-crafts.svg'),(3,'experiences','experiences.svg'),(4,'motors','motors.svg'),(5,'clothing-accessories','clothing-accessories.svg'),(6,'homes-housing','homes-housing.svg'),(7,'electronics','electronics.svg'),(8,'services','services.svg'),(9,'sporting-goods','sporting-goods.svg'),(10,'business-industrial','business-industrial.svg');");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("TRUNCATE `main_category`");
    }
}
