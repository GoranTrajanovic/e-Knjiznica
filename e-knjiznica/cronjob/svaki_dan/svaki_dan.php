<?php 

include $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

try {
	$sql = "UPDATE posudjene_knjige SET 
		br_preostalih_dana = br_preostalih_dana - 1";
	$s = $pdo->prepare($sql);
	$s->execute();	
} catch (PDOException $e){
	$error = 'Error decrementing: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

// Set DOSTUPNOST back to the book after rezervation went off
try{
	$sql = "UPDATE knjige
			INNER JOIN rezervirane_knjige
				ON id = rezervirano_knjiga_id
			SET knjiga_dostupno = knjiga_dostupno + (SELECT COUNT(id) FROM rezervirane_knjige WHERE id = rezervirano_knjiga_id) 
	";
	$s = $pdo->prepare($sql);
	$s->execute();
} catch (PDOException $e){
	$error = 'Error incrementing: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

// SEND DATA TO propale_rezervacije TABLE
try{
	$sql = "INSERT INTO propale_rezervacije (propale_rezervacije_ucenik_id, propale_rezervacije_knjiga_id)
			SELECT rezervirano_ucenik_id, rezervirano_knjiga_id
			FROM rezervirane_knjige";
	$s = $pdo->prepare($sql);
	$s->execute();
} catch (PDOException $e){
	$error = 'Error updating propale_rezervacije: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}



// DELETE ENTRY FOR RESERVATION
// first count how many reservations ucenik has
/*try{
	$s = $pdo->prepare('SELECT COUNT(*) FROM rezervirane_knjige WHERE ');
	$s->execute();
} catch (PDOException $e){
	$error = 'Error shifting: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}*/
try{
	$sql = "UPDATE ucenici
			INNER JOIN rezervirane_knjige
				ON id = rezervirano_ucenik_id
			SET ucenik_br_rezervacija = ucenik_br_rezervacija - (SELECT COUNT(id) FROM rezervirane_knjige WHERE id = rezervirano_ucenik_id) 
	";
	$s = $pdo->prepare($sql);
	$s->execute();
} catch (PDOException $e){
	$error = 'Error decrementing br_rezervacija: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}

// DELETE rezervirane_knjige table
try{
	$s = $pdo->prepare('TRUNCATE TABLE rezervirane_knjige');
	$s->execute();
} catch (PDOException $e){
	$error = 'Error deleting rezervirane_knjige table: ' . $e->getMessage();
	include 'error.html.php';
	exit();
}


include 'output.html.php';

