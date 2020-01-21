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

class PersonForLogin{
	private $personWhosLogging = "";

	public static function startLoginPage() {
		if(!isset($_POST["login_admin"]) && !isset($_POST["login_knjiznicar"]) && !isset($_POST["login_ucenik"])){
			include 'first_page.html.php';
			exit();
		}
	}

	public function setPerson(){
		if(isset($_POST["login_admin"])){
			$this->personWhosLogging = "admin";
			echo "{$this->personWhosLogging} je!";
		} else if (isset($_POST["login_knjiznicar"])) {
			$this->personWhosLogging = "knjiznicar";
			echo "{$this->personWhosLogging} je!";
		} else if (isset($_POST["login_ucenik"])) {
			$this->personWhosLogging = "ucenik";
			echo "{$this->personWhosLogging} je!";
		}
		else {
			header("Location: /e-knjiznica/first_page.html.php?login_none=empty");
			exit();
		}
	}

	public function attemptLogin() {
		$p = $this->personWhosLogging;
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/db_maturalni.inc.php';

		if (empty($_POST['uid']) || empty($_POST['pwd'])) {
			header("Location: /e-knjiznica/first_page.html.php?login_{$p}=empty");
			exit();
		} else {
			// Check if there is a user like this in db
			$stmt = $pdo->prepare("SELECT * FROM {$p} WHERE {$p}_username = ?");
			if ($stmt->execute(array($_POST['uid']))) {
				$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$firstRow = $data[0];
				if($p == 'admin' && password_verify($_POST['pwd'], $firstRow['admin_password'])){
					header("Location: /e-knjiznica/{$p}/{$p}.html.php");
					exit();	
				} else if ($p == 'knjiznicar' && $_POST['pwd'] == $firstRow["{$p}_password"]) {
					header("Location: /e-knjiznica/{$p}/{$p}.html.php");
					exit();
				} else if ($p == 'ucenik' && $_POST['pwd'] == $firstRow["{$p}_password"]) {
					$_SESSION['ucenik_id'] = $firstRow['id'];
					$_SESSION['ucenik_name'] = $firstRow['ucenik_name'];
					$_SESSION['ucenik_surname'] = $firstRow['ucenik_surname'];
					$_SESSION['ucenik_email'] = $firstRow['ucenik_email'];
					$_SESSION['ucenik_oznaka'] = $firstRow['ucenik_oznaka'];
					$_SESSION['ucenik_dani_posudbe'] = $firstRow['ucenik_dani_posudbe'];
					$_SESSION['ucenik_br_rezervacija'] = $firstRow['ucenik_br_rezervacija'];
					$_SESSION['ucenik_br_posudjenih_knjiga'] = $firstRow['ucenik_br_posudjenih_knjiga'];
					header("Location: /e-knjiznica/{$p}/index.php");
					exit();
				} else {
					header("Location: /e-knjiznica/first_page.html.php?login_{$p}=error");
					exit();
				}
			}
		}
	}
}

PersonForLogin::startLoginPage();

$person = new PersonForLogin;
/* if ($person->setPerson()){
	include 'first_page.html.php';
	exit();
} */
$person->setPerson();
$person->attemptLogin();



