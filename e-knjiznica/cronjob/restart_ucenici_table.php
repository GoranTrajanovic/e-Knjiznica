<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

try{
	$sql = "UPDATE ucenici SET
			ucenik_br_rezervacija = 0,
			ucenik_br_rezervacija_mjesecnih = 0,
			ucenik_br_posudjenih_knjiga = 0
	";
	$s = $pdo->prepare($sql);
	$s->execute();
} catch (PDOException $e){
	$error = 'Error resetting ucenici: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

try{
	$sql = "UPDATE knjige SET
			knjiga_dostupno = 7 WHERE id = 1;
			UPDATE knjige SET
			knjiga_dostupno = 3 WHERE id = 2;
			UPDATE knjige SET
			knjiga_dostupno = 5 WHERE id = 3;
			UPDATE knjige SET
			knjiga_dostupno = 2 WHERE id = 6;
	";
	$s = $pdo->prepare($sql);
	$s->execute();
} catch (PDOException $e){
	$error = 'Error resetting knjige: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

$s = $pdo->prepare('TRUNCATE TABLE propale_rezervacije');
$s->execute();
$s = $pdo->prepare('TRUNCATE TABLE rezervirane_knjige');
$s->execute();
$s = $pdo->prepare('TRUNCATE TABLE posudjene_knjige');
$s->execute();
