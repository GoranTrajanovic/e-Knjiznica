<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Knjižničar</title>
		<link rel="shortcut icon" type="image/png" href="../../img/knjiznicar_favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="../../js/main.js"></script>
	</head>
	<body>
		<a href="../knjiznicar.html.php" id="back"><img src="../../img/back.png" alt="back_icon"><span> Natrag</span></a>
		<a href="../../" id="logout"><span>Logout </span><img src="../../img/logout.jpg" alt="logout_icon"></a>

		<div id="site_content" class="wide_content">

			<?php
			$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					 if (strpos($fullUrl, "odabir=empty") == true) { echo "<p class='error'>Popunite sva polja.</p>"; }
					 if (strpos($fullUrl, "odabir=error") == true) { echo "<p class='error'>Odaberite vrstu knjige.</p>"; }
			?>

			<form action="" method="POST" id="set_knjige">
				<input type="text" name="set_naslov_knjige" placeholder="Naslov djela...">
				<input type="text" name="set_autor_knjige" placeholder="Autor djela...">
				<input type="text" name="set_kolicina_knjige" placeholder="Broj knjiga...">
				<select name="set_vrsta_knjige">
					<option value="non-option">Odaberite vrstu</option>
					<?php foreach($vrste as $vrsta): ?>
						<option value="<?php htmlout($vrsta['ime']) ?>">
							<?php htmlout($vrsta['ime']) ?>
						</option>
					<?php endforeach ?>
				</select>
				<input type="submit" value="Dodaj Knjigu">
			</form>

			<div>
				<form action="" method="POST" id="set_vrsta">
					<input type="text" name="set_vrsta" placeholder="Nova vrsta knjige...">
					<input type="submit" value="Dodaj">
				</form>

				<div class="dropdown">
					<button onclick="myFunction()" class="dropbtn">Postojeće vrste<img src="../../img/arrow.png" alt="arrow_icon" onclick="myFunction()"></button>
				  <div id="myDropdown" class="dropdown-content">
				  		<?php if (empty($vrste)){
								echo '<p class="error">Nema unesenih vrsta.</p>';
							} else { ?>
								<?php foreach ($vrste as $vrsta): ?>
									<div class="vrsta">
										<a>
											<?php htmlout($vrsta['ime']) ?>
											<form action="?deletevrsta" method="POST">
												<input type="hidden" name="id" value="<?php echo $vrsta['id']; ?>">
												<input type="submit" value="Izbriši">
											</form>
										</a>
									</div>
								<?php endforeach ?>
							<?php } ?>
				  </div>
				</div>




			<div id="table_content">
				<form action="" method="GET">
					<input type="text" name="text" placeholder="Naslov, autor, vrsta knjige...">
					<input type="submit" name="search" value="Traži">
				</form>

				<a href="index.php"><img src="../../img/refresh.png" alt="refresh_icon" class="refresh"></a>

				<table>
					<?php if (empty($knjige)){
							echo '<p class="error error_nomatch">Nema unesenih knjiga.</p>';
						} else { ?>
							<tr>
								<th>Naslov knjige</th>
								<th>Autor knjige</th>
								<th>Vrsta knjige</th>
								<th>Datum unosa</th>
								<th>Dostupno Knjiga</th>
							</tr>

							<?php
								$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								if (strpos($fullUrl, "search=nomatch") == true) { echo "<p class='error error_nomatch'>Navedena knjiga nije u knjižnici.</p>"; }
								else { 
									foreach ($knjige as $knjiga): ?>
										<tr class="data_tr">
											<th> <?php htmlout($knjiga['naslov']) ?> </th>
											<th> <?php htmlout($knjiga['autor']) ?> </th>
											<th> <?php htmlout($knjiga['vrsta']) ?> </th>
											<th> <?php htmlout($knjiga['date']) ?> </th>
											<?php
												if ($knjiga['dostupno'] != 0) {
													echo '<th style="color: green; font-weight: 600">' . html($knjiga["dostupno"]) . '</th>';
												} else {
													echo '<th style="color: red; font-weight: 600">' . html($knjiga["dostupno"]) . '</th>';
												}
											?>
											<th>
												<form action="?deleteknjiga" method="POST" class="delete_button">
													<input type="hidden" name="id" value="<?php echo $knjiga['id']; ?>">
													<input type="submit" value="Izbriši">
												</form>
											</th>
										</tr>
							<?php endforeach; } ?>
						<?php } ?>
				</table>
			</div>
		</div>

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
	</body>
</html>
