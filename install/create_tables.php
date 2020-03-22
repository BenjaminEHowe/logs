<?php

namespace install\create_tables;
require_once "../vendor/autoload.php";

require_once "../utils/db.php";

// create tables (if they don't exist) with only ID columns
$sql = "
	CREATE TABLE IF NOT EXISTS {$TABLE_PREFIX}_hosts (id char(26) NOT NULL PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	CREATE TABLE IF NOT EXISTS {$TABLE_PREFIX}_logs (id char(26) NOT NULL PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	CREATE TABLE IF NOT EXISTS {$TABLE_PREFIX}_secrets (id char(26) NOT NULL PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
	CREATE TABLE IF NOT EXISTS {$TABLE_PREFIX}_users (id char(26) NOT NULL PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// add columns for hosts table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_hosts ADD IF NOT EXISTS name varchar(253) NOT NULL DEFAULT '';
	ALTER TABLE {$TABLE_PREFIX}_hosts ADD IF NOT EXISTS secret char(26) NOT NULL DEFAULT '00000000000000000000000000';
";

// add columns for logs table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_logs ADD IF NOT EXISTS timestamp datetime NOT NULL;
	ALTER TABLE {$TABLE_PREFIX}_logs ADD IF NOT EXISTS host char(26) NOT NULL DEFAULT '00000000000000000000000000';
	ALTER TABLE {$TABLE_PREFIX}_logs ADD IF NOT EXISTS type varchar(64) NOT NULL DEFAULT '';
	ALTER TABLE {$TABLE_PREFIX}_logs ADD IF NOT EXISTS content longtext NOT NULL DEFAULT '{}';
";

// add columns for secrets table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_secrets ADD IF NOT EXISTS value text NOT NULL DEFAULT '';
	ALTER TABLE {$TABLE_PREFIX}_secrets ADD IF NOT EXISTS isActive tinyint(1) NOT NULL DEFAULT 0;
";

// add columns for users table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_users ADD IF NOT EXISTS username varchar(36) UNIQUE NOT NULL;
	ALTER TABLE {$TABLE_PREFIX}_users ADD IF NOT EXISTS secret char(26) NOT NULL DEFAULT '00000000000000000000000000';
	ALTER TABLE {$TABLE_PREFIX}_users ADD IF NOT EXISTS permissions longtext NOT NULL DEFAULT '{}';
";

// create default record in hosts table
$sql .= "INSERT INTO {$TABLE_PREFIX}_hosts VALUES ('00000000000000000000000000', '', '00000000000000000000000000') ON DUPLICATE KEY UPDATE id='00000000000000000000000000';";

// create default record in secrets table
$sql .= "INSERT INTO {$TABLE_PREFIX}_secrets VALUES ('00000000000000000000000000', '', 0) ON DUPLICATE KEY UPDATE id='00000000000000000000000000';";

// add constraints to hosts table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_hosts ADD CONSTRAINT {$TABLE_PREFIX}_fk_HOST_secret FOREIGN KEY (secret) REFERENCES {$TABLE_PREFIX}_secrets (id);
";

// add constraints to logs table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_logs ADD CONSTRAINT {$TABLE_PREFIX}_chk_log_content CHECK (json_valid(content));
	ALTER TABLE {$TABLE_PREFIX}_logs ADD CONSTRAINT {$TABLE_PREFIX}_fk_log_host FOREIGN KEY (host) REFERENCES {$TABLE_PREFIX}_hosts (id);
";

// add constraints to users table
$sql .= "
	ALTER TABLE {$TABLE_PREFIX}_users ADD CONSTRAINT {$TABLE_PREFIX}_chk_user_permissions_json CHECK (json_valid(permissions));
	ALTER TABLE {$TABLE_PREFIX}_users ADD CONSTRAINT {$TABLE_PREFIX}_fk_user_secret FOREIGN KEY (secret) REFERENCES {$TABLE_PREFIX}_secrets (id);
";

// execute SQL
if ($connection->multi_query($sql)) {
	do {
		if ($result = $connection->store_result()) {
			$result->free();
		}
	} while ($connection->next_result());
	echo "all ok.";
} else {
	die("error: {$connection->error}");
}

?>
