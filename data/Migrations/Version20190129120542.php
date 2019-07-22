<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129120542 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("listing");
        $table->addColumn('views', 'integer');


    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("listing");
        $table->dropColumn("views");

    }
}
