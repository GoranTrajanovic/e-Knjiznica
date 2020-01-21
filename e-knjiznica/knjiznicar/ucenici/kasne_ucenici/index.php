<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

try {
	$sql = "SELECT br_preostalih_dana, ucenik_oznaka, ucenik_name, ucenik_surname, knjiga_naslov, knjiga_autor
			FROM posudjene_knjige INNER JOIN ucenici
				ON posudba_ucenik_id = ucenici.id
			INNER JOIN knjige
				ON posudba_knjiga_id = knjige.id
			WHERE br_preostalih_dana < 0";
	$result = $pdo->query($sql);
} catch (PDOException $e) {
	$error = 'Error fetching zakašnjele knjige: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

foreach ($result as $row) {
	$knjige_koje_kasne[] = array(
		'uc_ime' => $row['ucenik_name'],
		'uc_prezime' => $row['ucenik_surname'],
		'uc_oznaka' => $row['ucenik_oznaka'],
		'naslov_knjige' => $row['knjiga_naslov'],
		'autor_knjige' => $row['knjiga_autor'],
		'br_dana_kasnjenja' => abs($row['br_preostalih_dana'])
	);
}

include 'kasne_ucenici.html.php';