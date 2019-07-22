<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190123090437 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->addColumn('activation_code', 'string');
        $table->addColumn('activation_expires', 'datetimetz', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addIndex(['activation_code'], 'activation_code_index');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->dropIndex("activation_code_index");
        $table->dropColumn('activation_code');
        $table->dropColumn('activation_expires');
    }
}
