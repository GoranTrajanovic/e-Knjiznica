<?php 
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 

// adding admin, first time 
// include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

// $hashed = password_hash('admin1234', PASSWORD_DEFAULT);

// try {
// 	$sql = 'INSERT INTO admin SET
// 		admin_username = "admin",
// 		admin_password = :pass';
// 	$s = $pdo->prepare($sql);
// 	$s->bindValue(':pass', $hashed);
// 	$s->execute();
// } catch (PDOException $e){
// 	$error = 'Error adding admin: ' . $e->getMessage();
// 	include 'error.html.php';
// 	exit();
// }
			
if (isset($_POST['login_admin'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	
	if (empty($_POST['uid']) || empty($_POST['pwd'])) {
		header("Location: /e-knjiznica/first_page.html.php?login_admin=empty");
		exit();
	} else {
		// Check if there is a user like this in db
		$stmt = $pdo->prepare("SELECT * FROM admin WHERE admin_username = ?");
		if ($stmt->execute(array($_POST['uid']))) {
		    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $firstRow = $data[0];
		    if (password_verify($_POST['pwd'], $firstRow['admin_password'])) {
				header("Location: /e-knjiznica/admin/admin.html.php");
				exit();
		    } else {
		    	header("Location: /e-knjiznica/first_page.html.php?login_admin=error");
				exit();
		    }
		}
	}
	 
} elseif (isset($_POST['login_knjiznicar'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	
	if (empty($_POST['uid']) || empty($_POST['pwd'])) {
		header("Location: /e-knjiznica/first_page.html.php?login_knjiznicar=empty");
		exit();
	} else {
		// Check if there is a user like this in db
		$stmt = $pdo->prepare("SELECT * FROM knjiznicar WHERE knjiznicar_username = ?");
		if ($stmt->execute(array($_POST['uid']))) {
		    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $firstRow = $data[0];
		    if ($_POST['pwd'] == $firstRow['knjiznicar_password']) {
				header("Location: /e-knjiznica/knjiznicar/knjiznicar.html.php");
				exit();
		    } else {
		    	header("Location: /e-knjiznica/first_page.html.php?login_knjiznicar=error");
				exit();
		    }
		}
	}
} elseif (isset($_POST['login_ucenik'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';
	
	if (empty($_POST['uid']) || empty($_POST['pwd'])) {
		header("Location: /e-knjiznica/first_page.html.php?login_ucenik=empty");
		exit();
	} else {
		// Check if there is a user like this in db
		$stmt = $pdo->prepare("SELECT * FROM ucenici WHERE ucenik_username = ?");
		if ($stmt->execute(array($_POST['uid']))) {
		    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		    $firstRow = $data[0];
		    if ($_POST['pwd'] == $firstRow['ucenik_password']) {

		    	$_SESSION['ucenik_id'] = $firstRow['id'];
		    	$_SESSION['ucenik_name'] = $firstRow['ucenik_name'];
		    	$_SESSION['ucenik_surname'] = $firstRow['ucenik_surname'];
		    	$_SESSION['ucenik_email'] = $firstRow['ucenik_email'];
		    	$_SESSION['ucenik_oznaka'] = $firstRow['ucenik_oznaka'];
		    	$_SESSION['ucenik_dani_posudbe'] = $firstRow['ucenik_dani_posudbe'];
		    	$_SESSION['ucenik_br_rezervacija'] = $firstRow['ucenik_br_rezervacija'];
		    	$_SESSION['ucenik_br_posudjenih_knjiga'] = $firstRow['ucenik_br_posudjenih_knjiga'];
				header("Location: /e-knjiznica/ucenik/index.php");
				exit();
		    } else {
		    	header("Location: /e-knjiznica/first_page.html.php?login_ucenik=error");
				exit();
		    }
		}
	}
} else {
	include 'first_page.html.php';
	exit();
}

