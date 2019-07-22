<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190212091506 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("trade_offer");
        $table->changeColumn("listing_id",['notnull' => false]);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("trade_offer");
        $table->changeColumn("listing_id",['notnull' => true]);

    }
}
