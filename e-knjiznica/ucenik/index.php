<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

$user_id = $_SESSION["ucenik_id"];

try{
	$result = $pdo->query('SELECT * FROM vrste');
}
catch (PDOException $e){
	$error = 'Error listing vrste: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

foreach($result as $row){
	$vrste[] = array(
		'id' => $row['id'],
		'ime' => $row['vrsta_ime']
	);
}

if (!isset($_GET['text'])) {

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try{
		$s = $pdo->query('SELECT * FROM knjige');
	}
	catch (PDOException $e){
		$error = 'Error listing knjige ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	
} elseif (isset($_GET['text'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	
	try {
		$sql = 'SELECT * FROM knjige WHERE CONCAT(knjiga_naslov, knjiga_autor, knjiga_vrsta) LIKE :search';
		$s = $pdo->prepare($sql);
		$s->bindValue(':search', '%' . $_GET['text'] . '%');
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
	if ($s->rowCount() == 0) {
    header("Location: .?search=nomatch");
    exit();
  }
} 

if (isset($_GET['filter_vrsta'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try {
		$sql = 'SELECT * FROM knjige WHERE knjiga_vrsta = :vrsta';
		$s = $pdo->prepare($sql);
		$s->bindValue(':vrsta', $_GET['filter_vrsta']);
		$s->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

}

if (isset($_GET['rezerviraj_knjigu'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	$sql = "SELECT id, ucenik_br_rezervacija, ucenik_br_rezervacija_mjesecnih, ucenik_br_posudjenih_knjiga 
			FROM ucenici WHERE id = '" . $_SESSION['ucenik_id'] . "'";
	$result = $pdo->query($sql);
	$data = $result->fetchAll(PDO::FETCH_ASSOC);
	$trenutni_ucenik = $data[0];

	// Provjeri jeli ucenik vec rezervirao ovu knjigu
	$sql = "SELECT rezervirano_ucenik_id, rezervirano_knjiga_id
			FROM rezervirane_knjige 
			WHERE rezervirano_ucenik_id = '" . $_SESSION['ucenik_id'] . "' 
			AND rezervirano_knjiga_id = '" . $_POST['id'] . "'";
	$result2 = $pdo->query($sql);
	if ($result2->rowCount() != 0) {
		header("Location: .?rezervacija=ista_knjiga");
		exit();
	}

	// filter table propale_rezervacije
	$sql = "SELECT propale_rezervacije_ucenik_id, propale_rezervacije_knjiga_id FROM propale_rezervacije
			WHERE propale_rezervacije_ucenik_id = :uc_id AND propale_rezervacije_knjiga_id = :knj_id";
	$s = $pdo->prepare($sql);
	$s->bindValue(':uc_id', $trenutni_ucenik['id']);
	$s->bindValue(':knj_id', $_POST['id']);
	$s->execute();

	foreach ($s as $row) {
		$knjiga_iz_propale_rezervacije[] = array ('uc' => $row['propale_rezervacije_ucenik_id'], 'knj' => $row['propale_rezervacije_knjiga_id']);
	}

	$trenutno_knjige = $trenutni_ucenik['ucenik_br_rezervacija'] + $trenutni_ucenik['ucenik_br_posudjenih_knjiga'];
	if ((2 - $trenutno_knjige) > 0 and $trenutni_ucenik['ucenik_br_rezervacija_mjesecnih'] < 5 and empty($knjiga_iz_propale_rezervacije)) {
		try {
			$sql = "SELECT knjiga_dostupno FROM knjige WHERE id = '" . $_POST['id'] . "'";
			$result = $pdo->query($sql);
			$data = $result->fetchAll(PDO::FETCH_ASSOC);
			$dostupnost = $data[0];

			if ($dostupnost['knjiga_dostupno'] == 0) {
				header('Location: .?rezervacija=posudjeno');
				exit();
			} else {
				$sql = "UPDATE knjige SET 
				knjiga_dostupno = knjiga_dostupno - 1
				WHERE id = '" . $_POST['id'] . "'";
				$s = $pdo->prepare($sql);
				$s->execute();

				$sql = "INSERT INTO rezervirane_knjige SET rezervirano_ucenik_id = '" . $user_id . "', rezervirano_knjiga_id = :id";
				$s = $pdo->prepare($sql);
				$s->bindValue(':id', $_POST['id']);
				$s->execute();

				$sql = "UPDATE ucenici SET 
				ucenik_br_rezervacija = ucenik_br_rezervacija + 1,
				ucenik_br_rezervacija_mjesecnih = ucenik_br_rezervacija_mjesecnih + 1
				WHERE id = '" . $user_id . "'";
				$s = $pdo->prepare($sql);
				$s->execute();

				header('Location: .?rezervacija=uspjesna');
				exit();
			}
		} catch (PDOException $e) {
			$error = 'Error tijekom rezerviranja: ' . $e->getMessage();
			include 'error.html.php';
			exit();
		}
	} elseif (!empty($knjiga_iz_propale_rezervacije)) {
		header('Location: .?rezervacija=propala');
		exit();
	} else {
		header('Location: .?rezervacija=overloaded');
		exit();
	}
	
	

	
	
}

if (isset($_POST['logout'])) {
	session_start(); // to make sure it is same session
	session_destroy();
	header('Location: ../');
	exit();
}

foreach ($s as $row) {
	$knjige[] = array(
		'id' => $row['id'],
		'naslov' => $row['knjiga_naslov'],
		'autor' => $row['knjiga_autor'],
		'vrsta' => $row['knjiga_vrsta'],
		'date' => date('d/m/Y', strtotime($row['knjiga_date'])),
		'kolicina' => $row['knjiga_kolicina'],
		'dostupno' => $row['knjiga_dostupno']
	);
}

include 'ucenik.html.php';