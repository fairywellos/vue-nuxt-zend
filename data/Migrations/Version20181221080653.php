<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181221080653 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->addColumn('credits', 'integer', ['default' => 3]);
        $table->addColumn('membership_expires', 'datetimetz', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('location', 'string', ['notnull' => false]);
        $table->addColumn('username', 'string', ['notnull' => false]);
        $table->addColumn('photo', 'string', ['notnull' => false]);

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->dropColumn('credits');
        $table->dropColumn('membership_expires');
        $table->dropColumn('location');
        $table->dropColumn('username');
        $table->dropColumn('photo');

    }
}
