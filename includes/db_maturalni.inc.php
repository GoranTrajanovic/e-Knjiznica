<?php

try{
	$pdo = new PDO('mysql:host=localhost;dbname=maturalni_beta', 'admin', 'admin1234');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$pdo->exec('SET NAMES "utf8"'); 
}

catch (PDOException $e){ 
	$error = 'Error connecting to the database: ' . 
		$e->getMessage(); 
	include 'error.html.php';
	exit(); 
}