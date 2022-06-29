<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

// to use MySQL database:
//   1. copy this file to "db.php"
//   2. uncomment the line below (and update the configuration if needed)
//   3. remove the Sqlite code from the new file
// $db = \Phlex\Data\Persistence\Sql::connect('mysql:dbname=phlex_test__ui;host=mysql', 'phlex_test', 'phlex_pass');

$sqliteFile = __DIR__ . '/_demo-data/db.sqlite';
if (!file_exists($sqliteFile)) {
    throw new \Exception('Sqlite database does not exist, create it first.');
}
$db = \Phlex\Data\Persistence\Sql::connect('sqlite:' . $sqliteFile);
unset($sqliteFile);
