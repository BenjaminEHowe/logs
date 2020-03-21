<?php

$DB_NAME = "db";
$DB_USER = "user";
$DB_PASS = "password";
$DB_HOST = "127.0.0.1";
$TABLE_PREFIX = "logs";
$ARGON2_HOST_SECRET_OPTIONS = ['memory_cost' => 32 * 1024, 'time_cost' => 1, 'threads' => 4];
$ARGON2_USER_SECRET_OPTIONS = ['memory_cost' => 64 * 1024, 'time_cost' => 10, 'threads' => 4];

?>
