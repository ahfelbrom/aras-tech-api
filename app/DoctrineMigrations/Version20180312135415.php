<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180312135415 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beacon ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE beacon ADD CONSTRAINT FK_244829E7FE54D947 FOREIGN KEY (group_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_244829E7FE54D947 ON beacon (group_id)');
        $this->addSql('ALTER TABLE media ADD beacon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF6AD5578 FOREIGN KEY (beacon_id) REFERENCES beacon (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CF6AD5578 ON media (beacon_id)');
        $this->addSql('ALTER TABLE quizz ADD beacon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DF6AD5578 FOREIGN KEY (beacon_id) REFERENCES beacon (id)');
        $this->addSql('CREATE INDEX IDX_7C77973DF6AD5578 ON quizz (beacon_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE beacon DROP FOREIGN KEY FK_244829E7FE54D947');
        $this->addSql('DROP INDEX IDX_244829E7FE54D947 ON beacon');
        $this->addSql('ALTER TABLE beacon DROP group_id');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CF6AD5578');
        $this->addSql('DROP INDEX IDX_6A2CA10CF6AD5578 ON media');
        $this->addSql('ALTER TABLE media DROP beacon_id');
        $this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973DF6AD5578');
        $this->addSql('DROP INDEX IDX_7C77973DF6AD5578 ON quizz');
        $this->addSql('ALTER TABLE quizz DROP beacon_id');
    }
}
