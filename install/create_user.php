<?php

namespace logs\install;
require_once "../vendor/autoload.php";

use Mysqli;
use Ulid\Ulid;

require_once "../utils/db.php";

// die if users already exist
if (mysqli_num_rows($connection->query("SELECT * FROM `${TABLE_PREFIX}_users`")) !== 0) {
	die("Users table is not empty");
}

// generate IDs and hash
$secretId = (string) Ulid::generate();
$secretHash = password_hash($_POST['password'], PASSWORD_ARGON2ID, $ARGON2_USER_SECRET_OPTIONS);
$isActive = 1;
$userId = (string) Ulid::generate();
$permissions = "{}";

// insert secret into DB
$statement = $connection->prepare("INSERT INTO {$TABLE_PREFIX}_secrets VALUES (?, ?, ?)");
$statement->bind_param("ssi", $secretId, $secretHash, $isActive);
$statement->execute();

// insert user into DB
$statement = $connection->prepare("INSERT INTO {$TABLE_PREFIX}_users VALUES (?, ?, ?, ?)");
$statement->bind_param("ssss", $userId, $_POST['username'], $secretId, $permissions);
$statement->execute();

?>
