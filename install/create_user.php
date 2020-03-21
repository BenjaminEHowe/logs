<?php

namespace logs\install;

require_once '../vendor/autoload.php';

use Mysqli;
use Ulid\Ulid;

require_once "../config.php";

$connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($connection->connect_error) {
	die("Connection failed: {$connection->connect_error}");
}

// die if users already exist
if (mysqli_num_rows($connection->query("SELECT * FROM `${TABLE_PREFIX}_users`")) !== 0) {
	die("Users already exist");
}

echo "<br />";
echo "id: ";
echo (string) Ulid::generate();
echo "<br />";
echo "username: {$_POST['username']}";
echo "<br />";
echo "password: ";
echo password_hash($_POST['password'], PASSWORD_ARGON2ID, $ARGON2_USER_SECRET_OPTIONS);

$connection->close();

?>
