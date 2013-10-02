<?php

namespace BladeTester\HandyTestsBundle\Model;

use Doctrine\DBAL\Connection;

class TableTruncator {

	public static function truncate(array $tables, Connection $connection) {
        $platform = $connection->getDatabasePlatform();
        $connection->query("SET foreign_key_checks = 0");
        foreach ($tables as $table) {
            $connection->executeUpdate($platform->getTruncateTableSQL($table));
        }
        $connection->query("SET foreign_key_checks = 1");
	}
}