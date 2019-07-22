<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181128130856 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('main_category');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->addColumn('icon', 'string');
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('tag');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->addColumn('parent_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['parent_id'], 'parent_id_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 145]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 145]);
        $table->addColumn('email', 'string', ['length' => 145]);
        $table->addColumn('user_type', 'smallint', [ 'default' => 2]);
        $table->addColumn('status', 'smallint', [ 'default' => 1]);
        $table->addColumn('rating', 'decimal', ['notnull' => false, 'precision' => 5, 'scale' => 2]);
        $table->addColumn('public', 'boolean', [ 'default' => 2]);
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['email'], 'email_index');
        $table->addIndex(['user_type'], 'user_type_index');
        $table->addIndex(['status'], 'status_index');
        $table->addIndex(['public'], 'public_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('listing');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('main_category_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->addColumn('listing_type', 'smallint', [ 'default' => 1]);
        $table->addColumn('status', 'smallint', [ 'default' => 1]);
        $table->addColumn('title', 'string', ['length' => 75]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('price', 'decimal', ['scale' => 2]);
        $table->addColumn('qty','smallint',[ 'default' => 0]);
        $table->addColumn('condition','smallint',[ 'default' => 1]);
        $table->addColumn('trade_or_cash','smallint',[ 'default' => 3]);
        $table->addColumn('shipping', 'boolean', [ 'default' => 1]);
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('availability', 'datetimetz');
        $table->addColumn('location', 'string', ['notnull' => false]);
        $table->addColumn('brand', 'string', ['notnull' => false, 'length' => 45]);
        $table->addColumn('color', 'string', ['notnull' => false, 'length' => 45]);
        $table->addColumn('material', 'string', ['notnull' => false, 'length' => 45]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['main_category_id'], 'main_category_id_index');
        $table->addIndex(['user_id'], 'user_id_index');
        $table->addIndex(['listing_type'], 'listing_type_index');
        $table->addIndex(['status'], 'status_index');
        $table->addIndex(['condition'], 'condition_index');
        $table->addIndex(['trade_or_cash'], 'trade_or_cash_index');
        $table->addIndex(['shipping'], 'shipping_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('photo');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('name', 'string');
        $table->addColumn('order','smallint',[ 'default' => 1]);
        $table->addColumn('main', 'boolean', [ 'default' => 0]);
        $table->addColumn('listing_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['main'], 'main_index');
        $table->addIndex(['order'], 'order_index');
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('listing_tags');
        $table->addColumn('listing_id', 'integer');
        $table->addColumn('tag_id', 'integer');
        $table->setPrimaryKey(['listing_id','tag_id']);
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addIndex(['tag_id'], 'tag_id_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('trade');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('user_id', 'integer');
        $table->addColumn('listing_id', 'integer');
        $table->addColumn('status','smallint',[ 'default' => 1]);
        $table->addColumn('trade_type','smallint',[ 'default' => 1]);
        $table->addColumn('cash_value', 'integer', ['notnull' => false]);
        $table->addColumn('notes', 'string', ['notnull' => false]);
        $table->addColumn('rated', 'boolean', [ 'default' => 0]);
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'user_id_index');
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addIndex(['status'], 'status_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('trade_item');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('trade_id', 'integer');
        $table->addColumn('listing_id', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addIndex(['listing_id'], 'listing_id_index');
        $table->addIndex(['trade_id'], 'trade_id_index');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('saved_listing');
        $table->addColumn('listing_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->setPrimaryKey(['listing_id','user_id']);
        $table->addOption('engine', 'InnoDB');

    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('main_category');
        $schema->dropTable('tag');
        $schema->dropTable('user');
        $schema->dropTable('listing');
        $schema->dropTable('photo');
        $schema->dropTable('listing_tags');
        $schema->dropTable('trade');
        $schema->dropTable('trade_item');
        $schema->dropTable('saved_listing');
        $schema->dropTable('message');

    }
}
