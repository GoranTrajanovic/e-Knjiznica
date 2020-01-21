<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Učenici</title>
		<link rel="shortcut icon" type="image/png" href="../../img/knjiznicar_favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	</head>
	<body>
		<a href="../knjiznicar.html.php" id="back"><img src="../../img/back.png" alt="back_icon"><span> Natrag</span></a>
		<a href="../../" id="logout"><span>Logout </span><img src="../../img/logout.jpg" alt="logout_icon"></a>

		<div id="site_content" class="wide_content">

			<form action="" method="POST">
				<?php
				if (!empty($get_br_knjiga)) {
					if ($n == 1 || $n == 21) {
						echo '<a href="kasne_ucenici/index.php" id="warrning"><img src="../../img/warning (1).png" alt="warning_sign"><span>Kasni <u>' . $n . '</u> učenik</span></a>';
					} elseif ($n == 2 || $n == 3 || $n == 4) {
						echo '<a href="kasne_ucenici/index.php" id="warrning"><img src="../../img/warning (1).png" alt="warning_sign"><span>Kasne <u>' . $n . '</u> učenika</span></a>';
					}else {
						echo '<a href="kasne_ucenici/index.php" id="warrning"><img src="../../img/warning (1).png" alt="warning_sign"><span>Kasni <u>' . $n . '</u> učenik</span></a>';
					}
				}
				?>
			</form>

			<?php
			$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					 if (strpos($fullUrl, "set_ucenik=empty") == true) { echo "<p class='error'>Popunite sva polja.</p>"; }
			?>




			<form action="" method="POST" id="set_ucenik">
				<input type="text" name="set_ozn_ucenika" placeholder="Učenička oznaka...">
				<input type="text" name="set_ime_ucenika" placeholder="Ime...">
				<input type="text" name="set_prezime_ucenika" placeholder="Prezime...">
				<input type="text" name="set_email_ucenika" placeholder="Email...">
				<input type="text" name="set_uid_ucenika" placeholder="Korisničko ime...">
				<input type="text" name="set_pwd_ucenika" placeholder="Lozinka...">
				<input type="submit" value="Dodaj">
			</form>

			<div id="table_content">
				<form action="" method="GET">
					<input type="text" name="text" style="width: 60%; margin-top: 25px" placeholder="Ime, prezime, korisničko ime, email...">
					<input type="submit" name="search" value="Traži">
				</form>

				<a href="index.php"><img src="../../img/refresh.png" alt="refresh_icon" class="refresh"></a>

				<table>
					<?php if (empty($ucenici)){
							echo '<p class="error error_nomatch">Nema unesenih osoba.</p>';
						} else { ?>
							<tr>
								<th>Oznaka</th>
								<th>Ime</th>
								<th>Prezime</th>
								<th>Email</th>
								<th>Datum unosa</th>
								<th>Korisnička oznaka</th>
							</tr>
							<?php
								$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								if (strpos($fullUrl, "search=nomatch") == true) { echo "<p class='error error_nomatch'>Navedeni učenik nije član knjižnice.</p>"; }
								else { 
									foreach ($ucenici as $ucenik): ?>
										<tr>
											<th> <?php htmlout($ucenik['oznaka']) ?> </th>
											<th> <?php htmlout($ucenik['name']) ?> </th>
											<th> <?php htmlout($ucenik['surname']) ?> </th>
											<th> <?php htmlout($ucenik['email']) ?> </th>
											<th> <?php htmlout($ucenik['date']) ?> </th>
											<th> <?php htmlout($ucenik['username']) ?> </th>
											<th style="border:none">
												<form action="ucenik/index.php?inspectucenik" method="POST" class="interaction_button">
													<input type="hidden" name="id" value="<?php echo $ucenik['id']; ?>">
													<input type="submit" value="Detalji">
												</form>
											</th>
											<th>
												<form action="?deleteucenik" method="POST" class="delete_button">
													<input type="hidden" name="id" value="<?php echo $ucenik['id']; ?>">
													<input type="submit" value="Izbriši">
												</form>
											</th>
										</tr>
							<?php endforeach; } ?>
						<?php } ?>
				</table>
			</div>
		</div>


	</body>
</html>
