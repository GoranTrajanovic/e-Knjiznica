<?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';



if (!isset($_GET['text'])) {

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';



	try{
		$result = $pdo->query('SELECT * FROM knjiznicar');
	}
	catch (PDOException $e){
		$error = 'Error listing knjiznicare ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

} else {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try {
		$sql = 'SELECT * FROM knjiznicar WHERE CONCAT(id, knjiznicar_name, knjiznicar_surname, knjiznicar_date, knjiznicar_username, knjiznicar_password) LIKE :search';
		$result = $pdo->prepare($sql);
		$result->bindValue(':search', '%' . $_GET['text'] . '%');
		$result->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching knjiznicare: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	if ($result->rowCount() == 0) {
		header("Location: .?search=nomatch");
		exit();
	}

}



if (isset($_POST['set_ime_knjiznicara'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	if (empty($_POST['set_ime_knjiznicara']) || empty($_POST['set_prezime_knjiznicara']) 
		|| empty($_POST['set_uid_knjiznicara']) || empty($_POST['set_pwd_knjiznicara'])) {
	    header("Location: .?insert_knjiznicar=empty");
	  } else {
			try {
				$sql = 'INSERT INTO knjiznicar SET
					knjiznicar_name = :name,
					knjiznicar_surname = :surname,
					knjiznicar_date = CURDATE(),
					knjiznicar_username = :uid,
					knjiznicar_password = :pwd';
				$s = $pdo->prepare($sql);
				$s->bindValue(':name', $_POST['set_ime_knjiznicara']);
				$s->bindValue(':surname', $_POST['set_prezime_knjiznicara']);
				$s->bindValue(':uid', $_POST['set_uid_knjiznicara']);
				$s->bindValue(':pwd', $_POST['set_pwd_knjiznicara']);
				$s->execute();
			} catch (PDOException $e){
				$error = 'Error adding knjiznicare ' . $e->getMessage();
				include 'error.html.php';
				exit();
			}
			header("Location: .");
			exit();
		}
}

if (isset($_GET['deleteknjiznicar'])) {
	try{
		$sql = 'DELETE FROM knjiznicar WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e){
		$error = 'Error deleting knjiznicar: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	header("Location: .");
	exit();

}

foreach ($result as $row) {
	$knjiznicari[] = array(
		'id' => $row['id'],
		'name' => $row['knjiznicar_name'],
		'surname' => $row['knjiznicar_surname'],
		'date' => date('d/m/Y', strtotime($row['knjiznicar_date'])),
		'username' => $row['knjiznicar_username'],
		'password' => $row['knjiznicar_password']
	);
}

include 'knjiznicar.html.php';
