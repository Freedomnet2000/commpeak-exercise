<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220702124125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cdr (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, call_date LONGTEXT NOT NULL, duration_in_seconds INT NOT NULL, dialed_phone_number INT NOT NULL, customer_ip LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `geo_names` (
          `id` int(11) NOT NULL,
          `iso` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `country` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `continent` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
          `phone` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cdr');
    }
}
