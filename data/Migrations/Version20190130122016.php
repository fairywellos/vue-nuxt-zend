<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130122016 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("main_category");
        $table->addColumn("slug", "string");

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable("main_category");
        $table->dropColumn("slug");

    }
}
