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
