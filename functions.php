<?php
session_start();
if ($_SESSION["role"] == 'F') {
    echo '<script>';
    echo 'window.onload=function() {';
    echo 'document.getElementById("settings").style.display = "none";';
    echo 'document.getElementById("logout").setAttribute("style","margin-left: 550px"); ';
    echo 'document.getElementById("func").style.display = "none"; }';
    echo '</script>';
}
function pdo_connect_mysql()
{
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'tugaspot_tugaspot';
	$DATABASE_PASS = 'Pra@513285776@@@@';
	$DATABASE_NAME = 'tugaspot_empresa';
	try {
		return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
	} catch (PDOException $exception) {
		// If there is an error with the connection, stop the script and display the error.
		exit('Failed to connect to database!');
	}
}
function template_header($title)
{
	echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style2.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav style="background-color: #2f3947;" class="navtop">
    	<div>
			<a href="admin.php"><i style="margin-left: -30px;" class="fas fa-home"></i>Página Inicial</a>
			<a href="read.php"><i style="margin-left: 18px" class="fas fa-address-book"></i>Clientes</a>
			<a href="func.php" id="func"><i style="margin-left: 18px" class="fas fa-book"></i>Funcionários</a>
			<a href="settings.php" id="settings"><i style="margin-left: 200px" class="fas fa-cog"></i>Definições</a>
			<a href="logout.php" id="logout"><i style="margin-left: 18px" class="fas fa-sign-out-alt"></i>Desconectar</a>
    	</div>
    </nav>
EOT;
}
function template_footer()
{
	echo <<<EOT
    </body>
</html>
EOT;
}