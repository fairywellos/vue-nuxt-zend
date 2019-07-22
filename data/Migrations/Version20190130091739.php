<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130091739 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $table = $schema->getTable("listing");
        $table->changeColumn('views',['default' => 0]);

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needsgit

    }
}
