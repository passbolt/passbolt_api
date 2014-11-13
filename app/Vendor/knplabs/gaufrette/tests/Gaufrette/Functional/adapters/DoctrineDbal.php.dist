<?php

use Doctrine\DBAL\DriverManager;
use Gaufrette\Adapter\DoctrineDbal;

$connection = DriverManager::getConnection(array(
    'driver' => 'pdo_sqlite',
    'memory' => true,
));

$platform = $connection->getDatabasePlatform();

$schemaManager = $connection->getSchemaManager();

if (in_array('gaufrette', $schemaManager->listTableNames())) {
    $schemaManager->dropTable('gaufrette');
}

$schema = $schemaManager->createSchema();

$table = $schema->createTable('gaufrette');
$table->addColumn('key', 'string', array('unique' => true));
$table->addColumn('content', 'blob');
$table->addColumn('mtime', 'integer');
$table->addColumn('checksum', 'string', array('length' => 32));

foreach ($schema->toSql($platform) as $query) {
    $connection->exec($query);
}

return new DoctrineDbal($connection, 'gaufrette');
