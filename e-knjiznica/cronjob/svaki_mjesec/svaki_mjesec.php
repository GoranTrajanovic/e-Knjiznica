<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

try {
	$sql = "UPDATE ucenici SET 
		ucenik_br_rezervacija_mjesecnih = 0";
	$s = $pdo->prepare($sql);
	$s->execute();	
} catch (PDOException $e){
	$error = 'Error decrementing: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

