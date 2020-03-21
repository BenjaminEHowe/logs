<?php

// first, create tables
//require "create_tables.php";

// then, display a basic form to create a user account
?>
<form action="create_user.php" method="post">
	<label for="username">Username:</label> <input type="text" id="username" name="username"><br /><br />
	<label for="password">Password:</label>	<input type="password" id="password" name="password"><br /><br />
	<input type="submit" value="Submit">
</form>
