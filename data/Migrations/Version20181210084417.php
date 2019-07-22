<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181210084417 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('trade');
        $table->dropColumn('user_id');
        $table->dropColumn('cash_value');

        $schema->dropTable('trade_item');

        $table = $schema->createTable('trade_offer');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('trade_id', 'integer');
        $table->addColumn('listing_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->addColumn('cash_value', 'decimal', ['scale' => 2, 'notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addIndex(['trade_id'], 'trade_id_index');
        $table->addIndex(['user_id'], 'user_id_index');
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("trade");
        $table->addColumn('user_id', 'integer');
        $table->addColumn('cash_value', 'integer', ['notnull' => false]);

        $table = $schema->createTable('trade_item');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('trade_id', 'integer');
        $table->addColumn('listing_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addIndex(['trade_id'], 'trade_id_index');
        $table->addOption('engine', 'InnoDB');

        $schema->dropTable("trade_offer");
    }
}
