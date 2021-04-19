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
$stmt = $con->prepare('SELECT password, email, nome, morada, codigo, nif, telefone, n_cliente FROM users WHERE n_cliente = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['n_cliente']);
$stmt->execute();
$stmt->bind_result($password, $email, $nome, $morada, $codigo, $nif, $telefone, $n_cliente);
$stmt->fetch();
$stmt->close();
$_SESSION['i'] = $_SESSION['n_cliente'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Perfil</title>
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
		<form class="content" action="insert.php" method="post">
			<h2>Perfil</h2>
			<div>
				<p>Detalhes da conta:</p>
				<table>					
					<tr>
						<td>Nome:</td>
						<td><?=$nome?></td>
					</tr>
					<tr>
						<td>Nº Cliente:</td>
						<td><?=$n_cliente?></td>
					</tr>	
					<tr>
						<td>Morada:</td>
						<td><?=$morada?></td>
					</tr>
					<tr>
						<td>Código Postal:</td>
						<td><?=$codigo?></td>
					</tr>
					<tr>
						<td>Telefone:</td>
						<td><?=$telefone?></td>
					</tr>
					<tr>
						<td>NIF:</td>
						<td><?=$nif?></td>
					</tr>		
					<tr>
						<td>Email:</td>
						<td><?=$_SESSION['email']?></td>
					</tr>
				</table>
			</div>
		</form>
	</body>
</html>