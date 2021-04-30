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
include 'functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT users.n_cliente, users.nome, fichas.n_ficha, fichas.estado, fichas.nota, fichas.created_at, fichas.problema FROM users INNER JOIN fichas ON users.n_cliente = fichas.n_cliente ORDER BY n_ficha LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_contacts = $pdo->query('SELECT COUNT(*) FROM fichas')->fetchColumn();
?>
<?= template_header('SosToners-Fichas') ?>

<div class="content read">
	<a href="create_ficha.php" class="create-contact">Criar Ficha</a>
	<h2 style="color: #4a536e;">Fichas</h2>
	<table id="myTable">
		<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Procurar">
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
		<thead>
			<tr>
				<th>Nº Cliente:</th>
				<th>Nome Cliente:</th>
				<th>Nº Ficha:</th>
				<th>Estado:</th>
				<th>Nota:</th>
				<th>Data de Criação</th>
				<th>Problema inicial:</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contacts as $contact) : ?>
				<tr>
					<td><?= $contact['n_cliente'] ?></td>
					<td><?= $contact['nome'] ?></td>
					<td><?= $contact['n_ficha'] ?></td>
					<td><?= $contact['estado'] ?></td>
					<td><?= $contact['nota'] ?></td>
					<td><?= $contact['created_at'] ?></td>
					<td><?= $contact['problema'] ?></td>
					<td class="actions">
						<a href="edit.php?n_ficha=<?= $contact['n_ficha'] ?>" class="add"><i style="color: black;" class="fas fa-pen fa-xs"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
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
				td4 = tr[i].getElementsByTagName("td")[4];
				if (td) {
					if ((td.innerHTML.toUpperCase().indexOf(filter) > -1) || (td1.innerHTML.toUpperCase().indexOf(filter) > -1) || (td2.innerHTML.toUpperCase().indexOf(filter) > -1) || (td3.innerHTML.toUpperCase().indexOf(filter) > -1) || (td4.innerHTML.toUpperCase().indexOf(filter) > -1)) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}
			}
		}
	</script>
	<div class="pagination">
		<?php if ($page > 1) : ?>
			<a href="read.php?page=<?= $page - 1 ?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page * $records_per_page < $num_contacts) : ?>
			<a href="read.php?page=<?= $page + 1 ?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?= template_footer() ?>