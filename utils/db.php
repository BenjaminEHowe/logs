<?php

namespace utils\db;

require_once "../vendor/autoload.php";

use Mysqli;

require_once "../config.php";

$connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($connection->connect_error) {
	die("Error connecting to database: {$connection->connect_error}");
}

?>
