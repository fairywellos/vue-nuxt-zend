<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190321080342 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_category ADD color_code VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE main_category SET color_code = "#32aedc" WHERE id=1');
        $this->addSql('UPDATE main_category SET color_code = "#F7BC31" WHERE id=2');
        $this->addSql('UPDATE main_category SET color_code = "#dc4250" WHERE id=3');
        $this->addSql('UPDATE main_category SET color_code = "#2DBD9B" WHERE id=4');
        $this->addSql('UPDATE main_category SET color_code = "#32aedc" WHERE id=5');
        $this->addSql('UPDATE main_category SET color_code = "#F7BC31" WHERE id=6');
        $this->addSql('UPDATE main_category SET color_code = "#dc4250" WHERE id=7');
        $this->addSql('UPDATE main_category SET color_code = "#2DBD9B" WHERE id=8');
        $this->addSql('UPDATE main_category SET color_code = "#32aedc" WHERE id=9');
        $this->addSql('UPDATE main_category SET color_code = "#F7BC31" WHERE id=10');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_category DROP color_code');
    }
}
