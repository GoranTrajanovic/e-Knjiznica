<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<title>Učenici koji kasne</title>
		<link rel="shortcut icon" type="image/png" href="../../../img/knjiznicar_favicon.png">
    <link rel="stylesheet" type="text/css" href="../../../css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
	</head>
	<body>
		<a href="../index.php" id="back"><img src="../../../img/back.png" alt="back_icon"><span> Natrag</span></a>
		<a href="../../../index.php" id="logout"><span>Logout </span><img src="../../../img/logout.jpg" alt="logout_icon"></a>

		<div id="site_content" class="wide_content">
			<div id="table_content" style="margin-bottom: 5%">
				<table>
					<tr>
						<th>Ime</th>
						<th>Prezime</th>
						<th>Oznaka</th>
						<th>Naslov knjige</th>
						<th>Autor knjige</th>
						<th>Kasni</th>
					</tr>
					<?php foreach ($knjige_koje_kasne as $knjiga): ?>
						<tr>
							<th> <?php htmlout($knjiga['uc_ime']) ?> </th>
							<th> <?php htmlout($knjiga['uc_prezime']) ?> </th>
							<th> <?php htmlout($knjiga['uc_oznaka']) ?> </th>
							<th> <?php htmlout($knjiga['naslov_knjige']) ?> </th>
							<th> <?php htmlout($knjiga['autor_knjige']) ?> </th>
							<th>
								<?php htmlout($knjiga['br_dana_kasnjenja']) ?>
								<?php if (html($knjiga['br_dana_kasnjenja']) == 1 || html($knjiga['br_dana_kasnjenja']) == 21){
										echo 'dan';
									} else {
										echo 'dana';
									}
								?>
							</th>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
			<button onclick="printThis()" id="print">Isprintaj stranicu</button>
		</div>

		<script>
			function printThis() {
			    window.print();
			}
		</script>
	</body>
</html>
