<?php

require_once "../config.php";

$connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($connection->connect_error) {
	die("Connection failed: {$connection->connect_error}");
}

function tryToCreateTable($name, $sql) {
	global $connection;
	if ($connection->query($sql) === TRUE) {
		echo "Table {$name} created successfully";
		echo "<br />";
	} else {
		die("Error creating table {$name}: {$connection->error}\n");
	}
}

// create secrets table
$sql = "
	CREATE TABLE `{$TABLE_PREFIX}_secrets` (
		`id` char(26) NOT NULL,
		`value` text NOT NULL,
		`isActive` tinyint(1) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";
tryToCreateTable("{$TABLE_PREFIX}_secrets", $sql);

// create users table
$sql = "
	CREATE TABLE `{$TABLE_PREFIX}_users` (
		`id` char(26) NOT NULL,
		`username` varchar(36) NOT NULL,
		`secret` char(26) NOT NULL,
		`permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
		PRIMARY KEY (`id`),
		CONSTRAINT `{$TABLE_PREFIX}_fk_user_secret` FOREIGN KEY (`secret`) REFERENCES `{$TABLE_PREFIX}_secrets` (`id`),
		CONSTRAINT `{$TABLE_PREFIX}_chk_user_permissions_json` CHECK (json_valid(`permissions`))
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";
tryToCreateTable("{$TABLE_PREFIX}_secrets", $sql);

// create hosts table
$sql = "
	CREATE TABLE `{$TABLE_PREFIX}_hosts` (
		`id` char(26) NOT NULL,
		`name` varchar(253) NOT NULL,
		`secret` char(26) NOT NULL,
		PRIMARY KEY (`id`),
		CONSTRAINT `{$TABLE_PREFIX}_fk_HOST_secret` FOREIGN KEY (`secret`) REFERENCES `{$TABLE_PREFIX}_secrets` (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";
tryToCreateTable("${TABLE_PREFIX}_hosts", $sql);

// create logs table
$sql = "
	CREATE TABLE `{$TABLE_PREFIX}_logs` (
		`id` char(26) NOT NULL,
		`timestamp` datetime NOT NULL,
		`host` char(26) NOT NULL,
		`type` varchar(64) NOT NULL,
		`content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
		PRIMARY KEY (`id`),
		CONSTRAINT `{$TABLE_PREFIX}_fk_log_host` FOREIGN KEY (`host`) REFERENCES `{$TABLE_PREFIX}_hosts` (`id`),
		CONSTRAINT `{$TABLE_PREFIX}_chk_log_content` CHECK (json_valid(`content`))
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";
tryToCreateTable("{$TABLE_PREFIX}_logs", $sql);

$connection->close();

?>
