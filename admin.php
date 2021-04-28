<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
if ($_SESSION["role"] == 'U') {
	header('Location: home.php');
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
$stmt = $con->prepare('SELECT users.n_cliente, users.nome, fichas.n_ficha, fichas.estado, fichas.created_at FROM users INNER JOIN fichas ON users.n_cliente = fichas.n_cliente');
// In this case we can use the account ID to get the account info.
$stmt->execute();
$stmt->bind_result($n_cliente, $nome, $n_ficha, $estado, $created_at);
$stmt->fetch();
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
	<title>SosToners-Admin</title>
	<link href="style1.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link href="style2.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>

<body class="loggedin">
	<nav style="background-color: #2f3947;" class="navtop">
		<div>
			<a href="admin.php"><i class="fas fa-home"></i>Página Inicial</a>
			<a href="read.php"><i style="margin-left: 490px" class="fas fa-address-book"></i>Clientes</a>
			<a href="logout.php"><i style="margin-left: 50px" class="fas fa-sign-out-alt"></i>Desconectar</a>
		</div>
	</nav>
	<div class="content read">
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
					border: 2px solid #dddddd;
					text-align: center;
					padding: 8px;
				}

				tr:nth-child(even) {
					background-color: #dddddd;
				}
			</style>
			<a href="create.php" class="create-contact">Criar Ficha</a>
			<h2 style="color: #4a536e; font-weight: bold; font-size: 22px;">Fichas</h2>
			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Procurar">
			<tr>
				<th>Nº Cliente:</th>
				<th>Nome Cliente:</th>
				<th>Nº Ficha:</th>
				<th>Estado:</th>
				<th>Data de Criação:</th>
				<th>Data de Estado:</th>
				<th>Problema inicial:</th>
			</tr>
			<tr>
				<?php
				include "config.php";
				$rec = mysqli_query($link, 'SELECT users.n_cliente, users.nome, fichas.n_ficha, fichas.estado, fichas.created_at FROM users INNER JOIN fichas ON users.n_cliente = fichas.n_cliente');
				while ($dat = mysqli_fetch_array($rec)) {
				?>
			<tr>
				<td><?php echo $dat['n_cliente']; ?></td>
				<td><?php echo $dat['nome']; ?></td>
				<td><?php echo $dat['n_ficha']; ?></td>
				<td><?php echo $dat['estado']; ?></td>
				<td><?php echo $dat['created_at']; ?></td>
				<td></td>
				<td></td>
				<td class="btt"><a href="edit.php?n_ficha=<?php echo $dat['n_ficha'];
															echo $dat['estado'] ?>">Edit</a></td>
			</tr>
		<?php
				}
		?>
		</table>
		<script>
			function myFunction() {
				var input, filter, table, tr, td, i;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("myTable");
				tr = table.getElementsByTagName("tr");
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[0]; // for column one
					td1 = tr[i].getElementsByTagName("td")[1];
					td2 = tr[i].getElementsByTagName("td")[2];
					td3 = tr[i].getElementsByTagName("td")[3]; // for column two
					/* ADD columns here that you want you to filter to be used on */
					if (td) {
						if ((td.innerHTML.toUpperCase().indexOf(filter) > -1) || (td1.innerHTML.toUpperCase().indexOf(filter) > -1) || (td2.innerHTML.toUpperCase().indexOf(filter) > -1) || (td3.innerHTML.toUpperCase().indexOf(filter) > -1)) {
							tr[i].style.display = "";
						} else {
							tr[i].style.display = "none";
						}
					}
				}
			}
		</script>
	</div>
</body>

</html>