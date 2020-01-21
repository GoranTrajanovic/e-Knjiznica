<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

try{
	$result_vrste = $pdo->query('SELECT * FROM vrste');
}
catch (PDOException $e){
	$error = 'Error listing vrste: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

foreach($result_vrste as $row){
	$vrste[] = array(
		'id' => $row['id'],
		'ime' => $row['vrsta_ime']
	);
}

if (!isset($_GET['text'])) {

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try{
		$result = $pdo->query('SELECT * FROM knjige');
	}
	catch (PDOException $e){
		$error = 'Error listing knjige ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
} else {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try {
		$sql = 'SELECT * FROM knjige WHERE CONCAT(knjiga_naslov, knjiga_autor, knjiga_vrsta) LIKE :search';
		$result = $pdo->prepare($sql);
		$result->bindValue(':search', '%' . $_GET['text'] . '%');
		$result->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
	if ($result->rowCount() == 0) {
    header("Location: .?search=nomatch");
    exit();
  }
}



if (isset($_POST['set_naslov_knjige'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	if (empty($_POST['set_naslov_knjige']) || empty($_POST['set_autor_knjige']) || empty($_POST['set_vrsta_knjige']) || empty($_POST['set_kolicina_knjige'])) {
		header('Location: .?odabir=empty');
	} elseif ($_POST['set_vrsta_knjige'] == 'non-option') {
		header('Location: .?odabir=error');
	} else {
		try {
			$sql = 'INSERT INTO knjige SET
				knjiga_naslov = :naslov,
				knjiga_autor = :autor,
				knjiga_vrsta = :vrsta,
				knjiga_date = CURDATE(),
				knjiga_kolicina = :kol,
				knjiga_dostupno = :kol';
			$result = $pdo->prepare($sql);
			$result->bindValue(':naslov', $_POST['set_naslov_knjige']);
			$result->bindValue(':autor', $_POST['set_autor_knjige']);
			$result->bindValue(':vrsta', $_POST['set_vrsta_knjige']);
			$result->bindValue(':kol', $_POST['set_kolicina_knjige']);
			$result->execute();
		} catch (PDOException $e){
			$error = 'Error adding knjige ' . $e->getMessage();
			include 'error.html.php';
			exit();
		}

		header("Location: .");
		exit();
	}
}

if (isset($_POST['set_vrsta'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try {
		$sql = 'INSERT INTO vrste SET
			vrsta_ime = :ime';
		$s = $pdo->prepare($sql);
		$s->bindValue(':ime', $_POST['set_vrsta']);
		$s->execute();
	} catch (PDOException $e){
		$error = 'Error adding vrste: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	header("Location: .");
	exit();

}


if (isset($_GET['deleteknjiga'])) {
	try{
		$sql = 'DELETE FROM knjige WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e){
		$error = 'Error deleting knjige: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	header("Location: .");
	exit();

}

if (isset($_GET['deletevrsta'])) {
	try{
		$sql = 'DELETE FROM vrste WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e){
		$error = 'Error deleting vrste: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	header("Location: .");
	exit();

}

foreach ($result as $row) {
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

include 'knjiga.html.php';
