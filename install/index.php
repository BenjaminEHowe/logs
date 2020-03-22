<?php

namespace logs\install;
require_once "../vendor/autoload.php";

use Mysqli;
use Ulid\Ulid;

require_once "../utils/db.php";

?>

<!doctype html>
<html lang="en" class="h-100">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>Logs Installer</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha256-L/W5Wfqfa0sdBNIKN9cG6QA5F2qx4qICmU2VgLruv9Y=" crossorigin="anonymous" />
	<style>
		.footer {
			background-color: #f5f5f5;
			text-align: center;
		}
	</style>
</head>
<body class="d-flex flex-column h-100">
<main role="main" class="flex-shrink-0">
	<div class="container">
		<h1 class="mt-5">Logs Installer</h1>
		<p class="lead">This tool will guide you through the installation of logs. Before continuing, please ensure you have written a config.php (see <a href="https://github.com/BenjaminEHowe/logs/blob/master/config-sample.php">config-sample.php</a> for how to to so), or the installation will fail.</p>
		<p>Database: <?php require "create_tables.php"; ?></p>
		<?php
		//$userCount = null;
		if ($result = $connection->query("SELECT * FROM ${TABLE_PREFIX}_users")) {
			$userCount = $result->num_rows;
			$result->free();
		}
		if ($userCount) { ?>
			<a class="btn btn-success" href="/" role="button">Continue</a>
		<?php } else { ?>
			<h2>Create User</h2>
			<form action="create_user.php" method="post">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Username" />
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password" />
				</div>
				<button type="submit" class="btn btn-primary">Create User</button>
			</form>
		<?php } ?>
	</div>
</main>
<footer class="footer mt-auto py-3">
	<div class="container"><span class="text-muted">Created by <a href="https://www.bh96.uk/">Benjamin Howe</a></div>
</footer>
</body>
</html>
