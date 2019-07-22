<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211142637 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->addColumn('password', 'string');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("user");
        $table->dropColumn('password');

    }
}
