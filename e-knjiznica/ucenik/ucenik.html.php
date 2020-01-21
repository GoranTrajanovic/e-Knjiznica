<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Učenik</title>
		<link rel="shortcut icon" type="image/png" href="../img/ucenik_favicon.png">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="../js/main.js"></script>
	</head>
	<body>
		<a href="../../" id="logout" style="line-height: 2em">
			<form action="" method="POST">
				<span><input type="submit" name="logout" value="Logout"></span>
				<img src="../img/logout.jpg" alt="logout_icon">
			</form>
		</a>

		<p id="welcome_message"><b><?php echo $_SESSION['ucenik_name']; ?> <?php echo $_SESSION['ucenik_surname'] ?></b>, dobrodošli.</p>
		<div id="site_content">
			<?php
				$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if (strpos($fullUrl, "rezervacija=uspjesna") == true){
					echo "<p class='success'>Uspiješna rezervacija.</p>";
				} elseif (strpos($fullUrl, "rezervacija=propala") == true){
					echo "<p class='error'>Knjigu ste već rezervirali ali niste došli po nju. Molimo vas dođite posuditi kod knjižničara</p>";
				} elseif (strpos($fullUrl, "rezervacija=overloaded") == true){
					echo "<p class='error'>Prešli ste broj rezervacija.</p>";
				} elseif (strpos($fullUrl, "rezervacija=ista_knjiga") == true){
					echo "<p class='error'>Već ste rezervirali ovu knjigu.</p>";
				} 
			?>
			<div id="table_content" class="user_ucenik">
				<form action="" method="GET">
					<input type="text" name="text" placeholder="Naslov, autor, vrsta knjige...">
					<input type="submit" name="search" value="Traži">
				</form>

				<div class="dropdown">
					<button onclick="myFunction()" class="dropbtn">Filtriraj po vrsti knjige<img src="../img/arrow.png" alt="arrow_icon" onclick="myFunction()"></button>
				  <div id="myDropdown" class="dropdown-content">
						<form action="" method="GET">
							<?php if (empty($vrste)){
								echo '<p class="error">Nema unesenih vrsta.</p>';
							} else { ?>
								<?php foreach ($vrste as $vrsta): ?>
										<span>
												<input type="submit" name="filter_vrsta" value="<?php htmlout($vrsta['ime']); ?>">
										</span>
								<?php endforeach ?>
							<?php } ?>
						</form>
				  </div>
				</div>

				<a href="index.php"><img src="../img/refresh.png" alt="refresh_icon" class="refresh"></a>


				<table>
					<?php if (empty($knjige)){
							echo '<p class="error error_nomatch">Nema knjiga u knjižnici.</p>';
						} else { ?>
							<tr>
								<th>Naslov knjige</th>
								<th>Autor knjige</th>
								<th>Vrsta knjige</th>
								<th>Dostupnost</th>
							</tr>

							<?php
								$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								if (strpos($fullUrl, "search=nomatch") == true) { echo "<p class='error error_nomatch'>Navedena knjiga nije u knjižnici.</p>"; }
								else { 
									foreach ($knjige as $knjiga): ?>
										<tr>
											<th> <?php htmlout($knjiga['naslov']) ?> </th>
											<th> <?php htmlout($knjiga['autor']) ?> </th>
											<th> <?php htmlout($knjiga['vrsta']) ?> </th>
											<?php
												if ($knjiga['dostupno'] != 0) {
													echo '<th style="color: green;">Dostpuno</th>';
												} else {
													echo '<th style="color: red;">Posuđeno</th>';
												}
											?>
											<th>
												<form action="?rezerviraj_knjigu" method="POST">
													<input type="hidden" name="id" value="<?php echo $knjiga['id']; ?>">
													<?php 
														if($knjiga['dostupno'] == 0) {
															echo '<span class="unavailable">Rezerviraj</span>';
														} else { ?>
															<input type="submit" value="Rezerviraj" class="interaction_button">
													<?php } ?>
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
