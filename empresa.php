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
if ($_SESSION["role"] == 'A'){
    header('Location: admin.php');
    exit;
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT users.n_cliente, users.nome, users.email, users.telefone, users.nif, fichas.n_ficha, fichas.problema, fichas.estado_ficha,fichas.marca, fichas.modelo, fichas.equipamento, fichas.criador,
n1.estado, n1.nota, n1.created_at 
FROM users
INNER JOIN fichas ON users.n_cliente = fichas.n_cliente
INNER JOIN notas AS n1 on n1.ficha = fichas.n_ficha 
LEFT JOIN notas as n2
ON (n1.ficha = n2.ficha AND n1.created_at < n2.created_at) WHERE n2.created_at IS NULL');
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_header('Fichas') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
  
<div class="content read">
	<a href="create_ficha.php" class="create-contact">Criar Ficha</a>
	<h2 style="color: #4a536e;">Fichas</h2>
	<table id="myTable">
		<style>
			#myTable {
				border-collapse: collapse;
				width: 100%;
				border: 1px solid #ddd;
				font-size: 15px;
			}

			#myTable th,
			#myTable td {
				text-align: center;
				padding: 8px;
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
				padding: 4px;
			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
		</style>
		<thead>
			<tr>
			    <th style="text-align: center;">Estado</th>
			    <th style="text-align: center;">Número</th>
				<th style="text-align: center;">Nome</th>
				<th style="text-align: center;" hidden>Email</th>
				<th style="text-align: center;" hidden>Nif</th>
                <th style="text-align: center;">Problema</th>
				<th style="text-align: center;">Ponto</th>
				<th style="text-align: center;">Marca</th>
				<th style="text-align: center;">Modelo</th>
				<th style="text-align: center;">Técnico</th>
				<th style="text-align: center;">Criação</th>
				<th style="text-align: center;">Editar</th>
			</tr>
		</thead>
		<tbody>
		     <?php foreach ($contacts as $contact) : ?>
				<tr>
				    <td style="text-align: center;"><?= $contact['estado_ficha'] ?></td>
				    <td style="text-align: center;"><?= $contact['n_ficha'] ?></td>
					<td style="text-align: center;"><?= $contact['nome'] ?></td>
					<td style="text-align: center;" hidden><?= $contact['email'] ?></td>
					<td style="text-align: center;" hidden><?= $contact['nif'] ?></td>
					<td style="text-align: center;"><?= $contact['problema'] ?></td>
					<td style="text-align: center;"><?= $contact['estado'] ?></td>
					<td style="text-align: center;"><?= $contact['marca'] ?></td>
					<td style="text-align: center;"><?= $contact['modelo'] ?></td>
					<td style="text-align: center;"><?= $contact['criador'] ?></td>
					<td style="text-align: center; font-size: 13px;"><?= $contact['created_at'] ?></td>
				    <td class="actions">
						<a href="edit.php?n_ficha=<?= $contact['n_ficha'] ?>&n_cliente=<?= $contact['n_cliente'] ?>" class="add"><i style="color: black;" class="fas fa-pen fa-xs"></i></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	   </form>
	<script>
	$(document).ready(function() {
    $('#myTable').DataTable( {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json'
        }
    } );
} );
	</script>
</div>

<?= template_footer() ?>