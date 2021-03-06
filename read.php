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
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM users WHERE role = "U" ORDER BY n_cliente');
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
?>
<?= template_header('Clientes') ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
  
<div class="content read">
    <a href="create.php" class="create-contact">Criar Cliente</a>
    <h2 style="color: #4a536e;">Clientes</h2>
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
        <thead>
            <tr>
                <th style="text-align: center;">N?? Cliente</th>
                <th style="text-align: center;">Email</th>
                <th style="text-align: center;">Nome</th>
                <th style="text-align: center;">Telefone</th>
                <th style="text-align: center;">NIF</th>
                <th style="text-align: center;">Data de Cria????o</th>
                <th style="text-align: center;">Ver Fichas/Criar Ficha/Editar/Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td style="text-align: center;"><?= $contact['n_cliente'] ?></td>
                    <td style="text-align: center;"><?= $contact['email'] ?></td>
                    <td style="text-align: center;"><?= $contact['nome'] ?></td>
                    <td style="text-align: center;"><?= $contact['telefone'] ?></td>
                    <td style="text-align: center;"><?= $contact['nif'] ?></td>
                    <td style="text-align: center;"><?= $contact['created_at'] ?></td>
                    <td class="actions">
                        <a href="fichas.php?n_cliente=<?= $contact['n_cliente'] ?>" class="add"><i style="color: black; margin-left: 30px;" class="fas fa-paperclip fa-xs"></i></a>
                        <a href="create_ficha1.php?n_cliente=<?= $contact['n_cliente'] ?>" class="add"><i style="color: black; margin-left: 30px;" class="fas fa-pen fa-xs"></i></a>
                        <a href="update.php?n_cliente=<?= $contact['n_cliente'] ?>" class="edit"><i style="color: black; margin-left: 30px" class="fas fa-user-edit fa-xs"></i></a>
                        <a href="delete.php?n_cliente=<?= $contact['n_cliente'] ?>" class="trash"><i style="color: black; margin-left: 30px" class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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