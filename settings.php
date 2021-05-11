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
$stmt = $pdo->prepare('SELECT * FROM estados');
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
?>
<?= template_header('SosToners-Clientes') ?>

<div class="content read">
    <a href="createopc.php" class="create-contact">Criar Opção</a>
    <h2 style="color: #4a536e;">Opções de Estados</h2>
    <table id="myTable">
        <style>
            #myTable {
                border-collapse: collapse;
                width: 80%;
                border: 1px solid #ddd;
                font-size: 18px;
            }

            #myTable th,
            #myTable td {
                text-align: center;
                padding: 5px;
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
                width: 80%;
            }

            td,
            th {
                border: 2px solid #dddddd;
                text-align: center;
                padding: 6px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
        <thead>
            <tr>
                <th>Opções</th>
                <th>Editar/Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td hidden><?= $contact['id'] ?></td>
                    <td><?= $contact['opcoes'] ?></td>
                    <td class="actions">
                        <a href="editopc.php?id=<?= $contact['id'] ?>" class="edit"><i style="color: black;" class="fas fa-edit fa-xs"></i></a>
                        <a href="deleteopc.php?id=<?= $contact['id'] ?>" class="trash"><i style="color: black;" class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= template_footer() ?>