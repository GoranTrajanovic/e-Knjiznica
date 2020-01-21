<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login</title>
</head>
<body>

<form action="index.php" method="POST">
	<div>
		<input type="text" name="uid" placeholder="Username">
	</div>
	<div>
		<input type="password" name="pwd" placeholder="Password">
	</div>
	<button type="submit" name="login">Login</button>
</form>


</body>
</html>