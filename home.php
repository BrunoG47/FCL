<?php

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
if ($_SESSION["role"] == 'G') {
    header('Location: empresa.php');
    exit;
}
if ($_SESSION["role"] == 'F') {
    header('Location: empresa.php');
    exit;
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT DISTINCT users.nome, users.n_cliente, fichas.n_ficha, fichas.problema, fichas.estado_ficha,
n1.estado, n1.nota, n1.created_at 
FROM users
INNER JOIN fichas ON users.n_cliente = fichas.n_cliente
INNER JOIN notas AS n1 on n1.ficha = fichas.n_ficha 
LEFT JOIN notas as n2
ON (n1.ficha = n2.ficha AND n1.created_at < n2.created_at) WHERE users.n_cliente = ?');
$stmt->execute([$_SESSION['n_cliente']]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
	<link href="style1.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<a href="home.php"><i style="padding: 0" class="fas"></i>Página Inicial</a>
			<a href="profile.php"><i style="margin-left: 600px" class="fas fa-user-circle"></i>Perfil</a>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Desconectar</a>
		</div>
	</nav>
	<div class="content">
		<table id="myTable">
			<style>
			#myTable {
				border-collapse: collapse;
				width: 100%;
				border: 1px solid #ddd;
				font-size: 18px;
			}

			#myTable th,
			#myTable td {
				text-align: left;
				padding: 12px;
			}

			#myTable tr {
				border-bottom: 1px solid #ddd;
			}

			#myTable tr.header,
			#myTable tr:hover {
				background-color: #f1f1f1;
			}

			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}

			td,
			th {
				border: 1px solid #000000;
				text-align: center;
				padding: 8px;
				border-bottom: 1px solid #000000;
			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
		</style>
			<p>Bem-vindo, <?= $contacts[0]['nome'] ?>!</p>
			<h2>Fichas</h2>
			<tr>
				<th>Estado Ficha:</th>
				<th>Nº Ficha:</th>
				<th>Problema:</th>
				<th>Ponto:</th>
				<th>Nota:</th>
				<th>Data de Criação:</th>
			</tr>
			<tr>
					<?php foreach ($contacts as $contact) : ?>
				<tr>
				    <td style="text-align: center;"><?= $contact['estado_ficha'] ?></td>
				    <td style="text-align: center;"><?= $contact['n_ficha'] ?></td>
					<td style="text-align: center;"><?= $contact['problema'] ?></td>
					<td style="text-align: center;"><?= $contact['estado'] ?></td>
					<td style="text-align: center;"><?= $contact['nota'] ?></td>
					<td style="text-align: center;"><?= $contact['created_at'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tr>
		</table>
	</div>
</body>

</html>