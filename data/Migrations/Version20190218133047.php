<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190218133047 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('conversation');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('conversation_user');
        $table->addColumn('user_id', 'integer');
        $table->addColumn('conversation_id', 'integer');
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['user_id', 'conversation_id']);
        $table->addForeignKeyConstraint('conversation', array("conversation_id"), array("id"),[], 'FK_conversation_user_conversation');
        $table->addForeignKeyConstraint('user', array("user_id"), array("id"),[], 'FK_conversation_user_user');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('message');
        $table->addColumn('id', 'integer', [ 'autoincrement' => true]);
        $table->addColumn('conversation_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->addColumn('text', 'text');
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('conversation', array("conversation_id"), array("id"),[], 'FK_message_conversation');
        $table->addForeignKeyConstraint('user', array("user_id"), array("id"),[], 'FK_message_user');
        $table->addOption('engine', 'InnoDB');

        $table = $schema->createTable('message_status');
        $table->addColumn('message_id', 'integer');
        $table->addColumn('user_id', 'integer');
        $table->addColumn('status', 'integer');
        $table->addColumn('date_added', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('date_modified', 'datetimetz', [ 'default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['message_id', 'user_id']);
        $table->addForeignKeyConstraint('message', array("message_id"), array("id"),[], 'FK_message_status_message');
        $table->addForeignKeyConstraint('user', array("user_id"), array("id"),[], 'FK_message_status_user');
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('conversation');
        $schema->dropTable('conversation_user');
        $schema->dropTable('message');
        $schema->dropTable('message_status');
    }
}
