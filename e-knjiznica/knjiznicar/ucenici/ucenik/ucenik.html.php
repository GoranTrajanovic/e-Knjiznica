<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Učenici</title>
		<link rel="shortcut icon" type="image/png" href="../../../img/knjiznicar_favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	</head>
	<body>
		<a href="../index.php" id="back"><img src="../../../img/back.png" alt="back_icon"><span> Natrag</span></a>
		<a href="../../../" id="logout"><span>Logout </span><img src="../../../img/logout.jpg" alt="logout_icon"></a>

		<div id="ucenik_content">
			<h1><?php htmlout($user['ucenik_name']) ?> <?php htmlout($user['ucenik_surname']) ?> - <?php htmlout($user['ucenik_oznaka']) ?></h1>
			<img src="../../../img/user_pic.png">
			<div id="ucenik_details">

				<h2>Posuđene knjige</h2>
				<div class="ucenik_table">
					<table>
						<?php if (empty($posudjene_knjige)){
							echo '<p class="error error_nomatch">Nema posuđenih knjiga.</p>';
						} else { ?>
							<tr>
								<th>Naslov</th>
								<th>Autor</th>
							</tr>
							<?php foreach ($posudjene_knjige as $posudjena_knjiga): ?>
								<tr>
									<th><?php htmlout($posudjena_knjiga['naslov']) ?></th>
									<th><?php htmlout($posudjena_knjiga['autor']) ?></th>
									<th>
										<form action="index.php" method="POST" class="interaction_button">
											<input type="hidden" name="set_vrati_knjigu" value="<?php echo $posudjena_knjiga['id']; ?>|<?php echo $user['id']; ?>">
											<input type="submit" value="Vrati">
										</form>
									</th>
								</tr>
							<?php endforeach ?>
						<?php } ?>
					</table>
				</div>

				<?php
				$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		         if (strpos($fullUrl, "knjiga=overloaded") == true) { echo "<p class='error'>Učenik je već posudio 2 knjige.</p>"; }
				?>
				<h2>Rezervirane knjige</h2>
				<div class="ucenik_table">
					<table>

						<?php if (empty($rezervirane_knjige)){
							echo '<p class="error error_nomatch">Nema rezerviranih knjiga.</p>';
						} else { ?>
							<tr>
								<th>Naslov</th>
								<th>Autor</th>
							</tr>
							<?php foreach ($rezervirane_knjige as $rezervirana_knjiga): ?>
								<tr>
									<th><?php htmlout($rezervirana_knjiga['naslov']) ?></th>
									<th><?php htmlout($rezervirana_knjiga['autor']) ?></th>
									<th>
										<form action="" method="POST" class="interaction_button">
											<input type="hidden" name="set_posudba" value="<?php echo $rezervirana_knjiga['id']; ?>|<?php echo $user['id']; ?>|<?php echo true; ?>">
											<input type="submit" value="Posudi">
										</form>
									</th>
								</tr>
							<?php endforeach ?>
						<?php } ?>
					</table>
				</div>
				

				<form action="" method="GET">
					<input type="text" name="text" placeholder="Naslov, autor...">
					<input type="submit" name="search" value="Traži">
				</form>

				<?php
				$fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		         if (strpos($fullUrl, "knjiga=posudjeno") == true) { echo "<p class='error' style='color: red'>Knjiga je već posuđena.</p>"; }
		         elseif (strpos($fullUrl, "knjiga=overloaded") == true) { echo "<p class='error' style='color: red'>Učenik je već posudio 2 knjige.</p>"; }
				?>

				<div class="ucenik_table">
					<table>

						<?php if (empty($knjige) and isset($_GET['search'])){
							echo '<p class="error error_nomatch">Knjiga ne postoji u našoj knjižnici.</p>';
						} elseif (empty($knjige)) {
							echo '<p class="error error_nomatch" style="color: #000; text-decoration: none">Čekamo na upit. Koristite tražilicu.</p>';
						} else { ?>
							<?php foreach ($knjige as $knjiga): ?>
								<tr>
									<th>Naslov knjige</th>
									<th>Autor knjige</th>
									<th>Dostupnost</th>
								</tr>
								<tr>
									<th> <?php htmlout($knjiga['naslov']) ?> </th>
									<th> <?php htmlout($knjiga['autor']) ?> </th>
									<?php
										if ($knjiga['dostupno'] != 0) {
											echo '<th style="color: green;">Dostupno</th>';
										} else {
											echo '<th style="color: red;">Posuđeno</th>';
										}
									?>
									<th>
										<form action="" method="POST" class="interaction_button">
											<input type="hidden" name="set_posudba" value="<?php echo $knjiga['id']; ?>|<?php echo $user['id']; ?>|<?php echo false; ?>">
											<input type="submit" value="Posudi">
										</form>
									</th>
								</tr>
							<?php endforeach ?>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>




	</body>
</html>
