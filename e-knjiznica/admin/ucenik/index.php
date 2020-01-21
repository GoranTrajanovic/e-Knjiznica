 <?php

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';

if (isset($_POST['set_ime_ucenika'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

  if (empty($_POST['set_ozn_ucenika']) || empty($_POST['set_ime_ucenika']) || empty($_POST['set_prezime_ucenika']) || empty($_POST['set_email_ucenika']) || empty($_POST['set_uid_ucenika']) || empty($_POST['set_pwd_ucenika'])) {
    header("Location: .?insert_ucenik=empty");
  } else {
    try {
  		$sql = 'INSERT INTO ucenici SET
  			ucenik_oznaka = :oznaka,
  			ucenik_name = :name,
  			ucenik_surname = :surname,
  			ucenik_email = :email,
  			ucenik_date = CURDATE(),
  			ucenik_username = :uid,
  			ucenik_password = :pwd';
  		$s = $pdo->prepare($sql);
  		$s->bindValue(':oznaka', $_POST['set_ozn_ucenika']);
  		$s->bindValue(':name', $_POST['set_ime_ucenika']);
  		$s->bindValue(':surname', $_POST['set_prezime_ucenika']);
  		$s->bindValue(':email', $_POST['set_email_ucenika']);
  		$s->bindValue(':uid', $_POST['set_uid_ucenika']);
  		$s->bindValue(':pwd', $_POST['set_pwd_ucenika']);
  		$s->execute();
  	} catch (PDOException $e){
  		$error = 'Error adding ucenike: ' . $e->getMessage();
  		include 'error.html.php';
  		exit();
  	}

  	header("Location: .");
  	exit();
  }
}

if (!isset($_GET['text'])) {

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try{
		$result = $pdo->query('SELECT * FROM ucenici');
	}
	catch (PDOException $e){
		$error = 'Error listing ucenike ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
} else {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

	try {
		$sql = 'SELECT * FROM ucenici WHERE CONCAT(ucenik_oznaka, ucenik_name, ucenik_surname, ucenik_email, ucenik_username) LIKE :search';
		$result = $pdo->prepare($sql);
		$result->bindValue(':search', '%' . $_GET['text'] . '%');
		$result->execute();
	} catch (PDOException $e) {
		$error = 'Error fetching ucenike: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}
  if ($result->rowCount() == 0) {
    header("Location: .?search=nomatch");
    exit();
  }
  
}

if (isset($_GET['deleteucenik'])) {
	try{
		$sql = 'DELETE FROM ucenici WHERE id = :id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':id', $_POST['id']);
		$s->execute();
	}
	catch (PDOException $e){
		$error = 'Error deleting ucenik: ' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	header("Location: .");
	exit();

}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';


foreach ($result as $row) {
  $ucenici[] = array(
    'id' => $row['id'],
    'oznaka' => $row['ucenik_oznaka'],
    'name' => $row['ucenik_name'],
    'surname' => $row['ucenik_surname'],
    'email' => $row['ucenik_email'],
    'date' => date('d/m/Y', strtotime($row['ucenik_date'])),
    'username' => $row['ucenik_username'],
    'password' => $row['ucenik_password'],
    'br_posudjenih_knjiga' => $row['ucenik_br_posudjenih_knjiga'],
    'br_rezervacija' => $row['ucenik_br_rezervacija']
  );
}


include 'ucenik.html.php';
