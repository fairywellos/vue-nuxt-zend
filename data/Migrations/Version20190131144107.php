<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190131144107 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("listing");
        $table->addColumn("local_trades_only",'boolean',["default" => 0]);
        $table->addColumn("year", "string",['notnull' => false]);
        $table->addColumn("available_date", "string",['notnull' => false]);
        $table->addColumn("notes", "string",['notnull' => false]);

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("listing");
        $table->dropColumn("local_trades_only");
        $table->dropColumn("year");
        $table->dropColumn("available_date");
        $table->dropColumn("notes");

    }
}
