<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'test';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT users.n_cliente, fichas.n_ficha, fichas.estado, users.nome, fichas.created_at FROM users INNER JOIN fichas ON users.n_cliente = fichas.n_cliente WHERE users.n_cliente = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['n_cliente']);
$stmt->execute();
$stmt->bind_result($n_cliente, $n_ficha, $estado, $nome, $created_at);
$stmt->fetch();
$_SESSION['i'] = $_SESSION['n_cliente'];
$stmt->execute();
$records = array();

$result = $stmt->get_result();
while ($data = $result->fetch_assoc()) {
	$records[] = $data;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home Page</title>
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
		<table>
			<style>
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				td,
				th {
					border: 2px solid #dddddd;
					text-align: center;
					padding: 8px;
				}

				tr:nth-child(even) {
					background-color: #dddddd;
				}
			</style>
			<p>Bem-vindo, <?= $nome ?>!</p>
			<h2>Fichas</h2>
			<tr>
				<th>Nº Cliente:</th>
				<th>Nº Ficha:</th>
				<th>Estado:</th>
				<th>Data de Criação:</th>
			</tr>
			<tr>
					<?php foreach ($records as $show) { ?>
							<td><?php echo $show['n_cliente']; ?></td>
							<td><?php echo $show['n_ficha']; ?></td>
							<td><?php echo $show['estado']; ?></td>
							<td><?php echo $show['created_at']; ?></td>
							<tr></tr>
				<?php } ?>
			</tr>
		</table>
	</div>
</body>

</html>