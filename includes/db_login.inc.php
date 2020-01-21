<?php

try{
	$pdo = new PDO('mysql:host=localhost;dbname=logintest', 'login_admin', 'xm6zu3er9');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$pdo->exec('SET NAMES "utf8"'); 
}

catch (PDOException $e){ 
	$error = 'Error fetching books: ' . 
		$e->getMessage(); 
	include 'error.html.php';
	exit(); 
}