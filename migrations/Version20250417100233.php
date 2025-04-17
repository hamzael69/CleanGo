<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417100233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cleaning_request (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, professional_id INT DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', number_of_rooms INT NOT NULL, living_space INT NOT NULL, price INT NOT NULL, description LONGTEXT DEFAULT NULL, is_accepted TINYINT(1) NOT NULL, INDEX IDX_17C9023619EB6921 (client_id), INDEX IDX_17C90236DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cleaning_request_service (cleaning_request_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_A5CF349296294493 (cleaning_request_id), INDEX IDX_A5CF3492ED5CA9E6 (service_id), PRIMARY KEY(cleaning_request_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cleaning_request ADD CONSTRAINT FK_17C9023619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE cleaning_request ADD CONSTRAINT FK_17C90236DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE cleaning_request_service ADD CONSTRAINT FK_A5CF349296294493 FOREIGN KEY (cleaning_request_id) REFERENCES cleaning_request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cleaning_request_service ADD CONSTRAINT FK_A5CF3492ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cleaning_request DROP FOREIGN KEY FK_17C9023619EB6921');
        $this->addSql('ALTER TABLE cleaning_request DROP FOREIGN KEY FK_17C90236DB77003');
        $this->addSql('ALTER TABLE cleaning_request_service DROP FOREIGN KEY FK_A5CF349296294493');
        $this->addSql('ALTER TABLE cleaning_request_service DROP FOREIGN KEY FK_A5CF3492ED5CA9E6');
        $this->addSql('DROP TABLE cleaning_request');
        $this->addSql('DROP TABLE cleaning_request_service');
    }
}
