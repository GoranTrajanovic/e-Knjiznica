<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 


include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';


if (!isset($_SESSION['once']) and isset($_GET['inspectucenik'])) {
	$_SESSION['once'] = 1;
	$_SESSION['current_user_id'] = $_POST['id'];
}

$sql = "SELECT id, ucenik_oznaka, ucenik_name, ucenik_surname FROM ucenici WHERE id = '" . $_SESSION['current_user_id'] . "'";
$result = $pdo->query($sql);
$data = $result->fetchAll(PDO::FETCH_ASSOC);
$user = $data[0];


try {
	
	$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
		FROM knjige INNER JOIN rezervirane_knjige
			ON knjige.id = rezervirano_knjiga_id
		INNER JOIN ucenici
			ON rezervirano_ucenik_id = ucenici.id
		WHERE ucenici.id = '" . $_SESSION['current_user_id'] . "'";
	$result1 = $pdo->query($sql);

} catch (PDOException $e){
	$error = 'Error fetching knjige: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

try {
	$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
		FROM knjige INNER JOIN posudjene_knjige
			ON knjige.id = posudba_knjiga_id
		INNER JOIN ucenici
			ON posudba_ucenik_id = ucenici.id
		WHERE ucenici.id = '" . $_SESSION['current_user_id'] . "'";
	$result2 = $pdo->query($sql);

} catch (PDOException $e){
	$error = 'Error fetching knjige: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}


/*if (isset($_GET['inspectucenik'])) {
	
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	// Create table rezervirane_knjige
	try {
		
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN rezervirane_knjige
				ON knjige.id = rezervirano_knjiga_id
			INNER JOIN ucenici
				ON rezervirano_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $_POST['id'] . "'";
		$result1 = $pdo->query($sql);

	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	// Create table posudjene_knjige
	try {
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN posudjene_knjige
				ON knjige.id = posudba_knjiga_id
			INNER JOIN ucenici
				ON posudba_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $_POST['id'] . "'";
		$result2 = $pdo->query($sql);

	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
}*/

if (isset($_POST['set_posudba'])) {
	
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	/*list($value1, $value2) = explode('|', $_POST['set_posudba']);
	try{
		$sql = "INSERT INTO posudjene_knjige (posudba_ucenik_id, posudba_knjiga_id, br_preostalih_dana)
			SELECT rezervirano_ucenik_id, rezervirano_knjiga_id, 21
			FROM rezervirane_knjige
			WHERE rezervirano_ucenik_id = :uc_id AND rezervirano_knjiga_id = :knj_id;
			UPDATE ucenici 
			SET ucenik_br_posudjenih_knjiga = ucenik_br_posudjenih_knjiga + 1,
				ucenik_br_rezervacija = ucenik_br_rezervacija - 1
			WHERE id = :uc_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error posudjivanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}*/

	list($value1, $value2, $value3) = explode('|', $_POST['set_posudba']);
	/*try{
		$sql = "INSERT INTO posudjene_knjige (posudba_ucenik_id, posudba_knjiga_id, br_preostalih_dana)
				VALUES (:uc_id, :knj_id, 21);
				UPDATE ucenici, knjige 
				SET ucenik_br_posudjenih_knjiga = ucenik_br_posudjenih_knjiga + 1,
					(CASE WHEN '" . $value3 . "' != false 
						THEN ucenik_br_rezervacija = ucenik_br_rezervacija - 1 
						ELSE knjiga_dostupno = knjiga_dostupno - 1 END)
				WHERE id = :uc_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		// $s->bindValue(':decrement', $value3);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error posudjivanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}*/
	$sql = "SELECT knjiga_dostupno FROM knjige WHERE id = '" . $value1 . "'";
	$result_s1 = $pdo->query($sql);
	$data = $result_s1->fetchAll(PDO::FETCH_ASSOC);
	$dostupnost = $data[0];

	$sql = "SELECT ucenik_br_posudjenih_knjiga FROM ucenici WHERE id = '" . $value2 . "'";
	$result_s2 = $pdo->query($sql);
	$data = $result_s2->fetchAll(PDO::FETCH_ASSOC);
	$br_knjiga = $data[0];

	if ($dostupnost['knjiga_dostupno'] <= 0 and $value3 == false) {
		header('Location: .?knjiga=posudjeno');
		include 'ucenik.html.php';
		exit();
	} elseif ($br_knjiga['ucenik_br_posudjenih_knjiga'] == 2) {
		header('Location: .?knjiga=overloaded');
		include 'ucenik.html.php';
		exit();
	}

	try{
		$sql = "INSERT INTO posudjene_knjige (posudba_ucenik_id, posudba_knjiga_id, br_preostalih_dana)
				VALUES (:uc_id, :knj_id, 21)";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		// $s->bindValue(':decrement', $value3);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error posudjivanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	try{
		$sql = "UPDATE ucenici SET ucenik_br_posudjenih_knjiga = ucenik_br_posudjenih_knjiga + 1 WHERE id = :uc_id;";
		if ($value3 == false) {
			$sql .= " UPDATE knjige SET knjiga_dostupno = knjiga_dostupno - 1 WHERE id = :knj_id";
		} else {
			$sql .= " UPDATE ucenici SET ucenik_br_rezervacija = ucenik_br_rezervacija - 1 WHERE id = :uc_id";
		}
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error posudjivanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	try {
		$sql = "DELETE FROM rezervirane_knjige WHERE rezervirano_ucenik_id = :uc_id AND rezervirano_knjiga_id = :knj_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error posudjivanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}


	// Recreate table rezervirane_knjige
	try {
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN rezervirane_knjige
				ON knjige.id = rezervirano_knjiga_id
			INNER JOIN ucenici
				ON rezervirano_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $value2 . "'";
		$result1 = $pdo->query($sql);

	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	// Recreate table posudjene_knjige
	try {
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN posudjene_knjige
				ON knjige.id = posudba_knjiga_id
			INNER JOIN ucenici
				ON posudba_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $value2 . "'";
		$result2 = $pdo->query($sql);
	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
}

if (isset($_POST['set_vrati_knjigu'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	list($value1, $value2) = explode('|', $_POST['set_vrati_knjigu']);

	try {
		$sql = "DELETE FROM posudjene_knjige WHERE posudba_ucenik_id = :uc_id AND posudba_knjiga_id = :knj_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->bindValue(':knj_id', $value1);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error vraćanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	try {
		$sql ="UPDATE knjige SET knjiga_dostupno = knjiga_dostupno + 1 WHERE id = :knj_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':knj_id', $value1);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error vraćanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	try {
		$sql ="UPDATE ucenici SET ucenik_br_posudjenih_knjiga = ucenik_br_posudjenih_knjiga - 1 WHERE id = :uc_id";
		$s = $pdo->prepare($sql);
		$s->bindValue(':uc_id', $value2);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error vraćanje knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}



	// Recreate table rezervirane_knjige
	try {
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN rezervirane_knjige
				ON knjige.id = rezervirano_knjiga_id
			INNER JOIN ucenici
				ON rezervirano_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $value2 . "'";
		$result1 = $pdo->query($sql);

	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	// Recreate table posudjene_knjige
	try {
		$sql = "SELECT knjige.id, knjiga_naslov, knjiga_autor
			FROM knjige INNER JOIN posudjene_knjige
				ON knjige.id = posudba_knjiga_id
			INNER JOIN ucenici
				ON posudba_ucenik_id = ucenici.id
			WHERE ucenici.id = '" . $value2 . "'";
		$result2 = $pdo->query($sql);
	} catch (PDOException $e){
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

}

foreach ($result2 as $row) {
	$posudjene_knjige[] = array(
		'id' => $row['id'],
		'naslov' => $row['knjiga_naslov'],
		'autor' => $row['knjiga_autor']
	);
}

foreach ($result1 as $row) {
	$rezervirane_knjige[] = array(
		'id' => $row['id'],
		'naslov' => $row['knjiga_naslov'],
		'autor' => $row['knjiga_autor']
	);
}

if (isset($_GET['search'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	
	try {
		$sql = 'SELECT id, knjiga_naslov, knjiga_autor, knjiga_dostupno FROM knjige WHERE CONCAT(knjiga_naslov, knjiga_autor) LIKE :search';
		$s = $pdo->prepare($sql);
		$s->bindValue(':search', '%' . $_GET['text'] . '%');
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	foreach ($s as $row) {
		$knjige[] = array(
			'id' => $row['id'],
			'naslov' => $row['knjiga_naslov'],
			'autor' => $row['knjiga_autor'],
			'dostupno' => $row['knjiga_dostupno']
		);
	}
}

include 'ucenik.html.php';