<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Knjižničar</title>
		<link rel="shortcut icon" type="image/png" href="../../img/admin_favicon.ico">
		<link rel="stylesheet" type="text/css" href="../../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	</head>
	<body>

		<a href="../admin.html.php" id="back"><img src="../../img/back.png" alt="back_icon"><span> Natrag</span></a>
		<a href="../../" id="logout"><span>Logout </span><img src="../../img/logout.jpg" alt="logout_icon"></a>

		<div id="site_content">

			<?php
				$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($fullUrl, "insert_knjiznicar=empty") == true) { echo "<p class='error'>Popunite sva polja!</p>"; }
			?>

			<form action="" method="POST">
				<input type="text" name="set_ime_knjiznicara" placeholder="Ime...">
				<input type="text" name="set_prezime_knjiznicara" placeholder="Prezime...">
				<input type="text" name="set_uid_knjiznicara" placeholder="Korisničko ime...">
				<input type="text" name="set_pwd_knjiznicara" placeholder="Lozinka...">
				<input type="submit" value="Dodaj">
			</form>
			<div id="table_content">


				
				<form action="" method="GET">
					<input type="text" name="text" placeholder="Ime, prezime, korisničko ime...">
					<input type="submit" name="search" value="Traži">
				</form>

				<a href="index.php"><img src="../../img/refresh.png" alt="refresh_icon" class="refresh"></a>

				<table>
					<?php if (empty($knjiznicari)){
							echo '<p class="error error_nomatch">Nema unesenih osoba.</p>';
						} else { ?>
							<tr>
								<th>Ime</th>
								<th>Prezime</th>
								<th>Datum unosa</th>
								<th>Korisnička oznaka</th>
								<th>Lozinka</th>
							</tr>
							<?php
								$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								if (strpos($fullUrl, "search=nomatch") == true) { echo "<p class='error error_nomatch' style='margin-left:5%'>Navedena osoba nije u osoblju knjižnice.</p>"; }
								else { 
									foreach ($knjiznicari as $knjiznicar): ?>
										<tr class="data_tr">
											<th> <?php htmlout($knjiznicar['name']) ?> </th>
											<th> <?php htmlout($knjiznicar['surname']) ?> </th>
											<th> <?php htmlout($knjiznicar['date']) ?> </th>
											<th> <?php htmlout($knjiznicar['username']) ?> </th>
											<th> <?php htmlout($knjiznicar['password']) ?> </th>
											<th>
												<form action="?deleteknjiznicar" method="POST" class="delete_button">
													<input type="hidden" name="id" value="<?php echo $knjiznicar['id']; ?>">
													<input type="submit" value="Izbriši">
												</form>
											</th>
										</tr>
							<?php endforeach; }  ?>
						<?php }  ?>

				</table>
			</div>
		</div>




	</body>
</html>
