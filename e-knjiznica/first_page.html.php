<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>e-knjižnica-etss</title>
		<link rel="shortcut icon" type="image/png" href="img/favicon.png">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
	</head>
	<body>

		<div class="login">
			<div class="login_card">
				<div class="login_card_border">
					<!-- <a href="knjiznicar.html.php">Knjižničar</a> -->
					<div class="login_card_icon">
						<div class="login_label_white">
							<span>Knjižničar</span>
						</div>
						<img src="img/knjiznicar.png" alt="knjiznicar_icon">
					</div>
					<div class="login_label_blue">
						<span>Knjižničar</span>
					</div>

					<form action="index.php" method="POST">
						<input type="text" name="uid" placeholder="Korisnička oznaka...">
						<input type="password" name="pwd" placeholder="Lozinka...">
						<button type="submit" name="login_knjiznicar">Prijava</button>
					</form>
				</div>
			</div>



			<div class="login_card">
				<div class="login_card_border">
					<!-- <a href="admin.html.php">Administrator</a> -->
					<div class="login_card_icon">
						<div class="login_label_white">
							<span>Administrator</span>
						</div>
						<img src="img/admin.png" alt="admin_icon">
					</div>

					<div class="login_label_blue">
						<span>Administrator</span>
					</div>
					<form action="index.php" method="POST">
						<input type="text" name="uid" placeholder="Korisnička oznaka...">
						<input type="password" name="pwd" placeholder="Lozinka...">
						<button type="submit" name="login_admin">Prijava</button>
					</form>
				</div>
			</div>


			<div class="login_card">
				<div class="login_card_border">
					<!-- <a href="ucenik.html.php">Učenik</a> -->
					<div class="login_card_icon">
						<div class="login_label_white">
							<span>Učenici</span>
						</div>
						<img src="img/ucenik.png" alt="ucenik_icon">
					</div>

					<div class="login_label_blue">
						<span>Učenici</span>
					</div>
					<form action="index.php" method="POST">
						<input type="text" name="uid" placeholder="Korisnička oznaka...">
						<input type="password" name="pwd" placeholder="Lozinka...">
						<button type="submit" name="login_ucenik">Prijava</button>
					</form>
				</div>
			</div>
		</div>

		<?php
		$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // get url which generated this
				 if (strpos($fullUrl, "login_knjiznicar=empty") == true) { echo "<div class='center'><p class='error'>Molimo vas popunite sva polja!</p></div>"; }
				 elseif (strpos($fullUrl, "login_knjiznicar=error") == true) { echo "<div class='center'><p class='error'>Krivi unos.<br> Pokušajte ponovno.</p></div>"; }
		?>

		<?php
		$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // get url which generated this
				 if (strpos($fullUrl, "login_admin=empty") == true) { echo "<div class='center'><p class='error'>Molimo vas popunite sva polja!</p></div>"; }
				 elseif (strpos($fullUrl, "login_admin=error") == true) { echo "<div class='center'><p class='error'>Krivi unos.<br> Pokušajte ponovno.</p></div>"; }
		?>

		<?php
		$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // get url which generated this
				 if (strpos($fullUrl, "login_ucenik=empty") == true) { echo "<div class='center'><p class='error'>Molimo vas popunite sva polja!</p></div>"; }
				 elseif (strpos($fullUrl, "login_ucenik=error") == true) { echo "<div class='center'><p class='error'>Krivi unos.<br> Pokušajte ponovno.</p></div>"; }
		?>

	</body>
</html>
