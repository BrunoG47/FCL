<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index1.html');
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
$stmt = $con->prepare('SELECT fichas.n_cliente, fichas.n_ficha, fichas.estado, users.nome FROM fichas, users WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($n_cliente, $n_ficha, $estado, $nome);
$stmt->fetch();
$stmt->close();
$_SESSION['i'] = $_SESSION['id'];
echo $_SESSION['i']. " ";
echo $n_cliente;

$sql = "SELECT n_ficha FROM fichas, users WHERE fichas.n_cliente = users.n_cliente";
$result = mysqli_query($con, $sql);
$n_fichas = array();
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$n_fichas[] = $row;
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
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
					border: 1px solid #dddddd;
					text-align: left;
					padding: 8px;
				}

				tr:nth-child(even) {
					background-color: #dddddd;
				}
			</style>
			<p>Bem-vindo, <?= $nome ?>!</p>
			<h2>Fichas</h2>
			<tr>
				<th>Nº Ficha:</th>
				<th>Estado:</th>
			</tr>
			<tr>
				<?php
				foreach ($n_fichas as $n_ficha) {
					echo $n_ficha['n_ficha']. " ";
				}
				?>
			</tr>
			<tr>
			</tr>
		</table>
	</div>
</body>

</html>