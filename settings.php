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
if ($_SESSION["role"] == 'F') {
    header('Location: empresa.php');
    exit;
}
include 'functions.php';
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM estados');
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = $pdo->prepare('SELECT id, name FROM grupo');
$sql->execute();
$grupo = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_header('Definições') ?>

<div class="content read">
    <a href="createopc.php" class="create-contact" style="margin-left: -78px;">Criar Opção</a>
    <?php
    if ($_SESSION["role"] != 'F') {
    echo '<a href="create_grupo.php" class="create-contact" style="margin-left: 465px;" id="fun">Criar Grupo</a>';}
    ?>
    <a href="reset-password.php" class="create-contact" style="margin-left: 220px;">Alterar palavra-passe</a>
    <h2 style="color: #4a536e; margin-left: -80px;">Opções de Estados</h2>
    <table id="myTable">
        <style>
            #myTable {
                border-collapse: collapse;
                width: 50%;
                border: 1px solid #ddd;
                font-size: 18px;
                margin-left: -80px;
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
                width: 50%;
            }
            
            tr{
                width: 50px;
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
    <h2 style="color: #4a536e; margin-left: 50%; margin-top: -599px;">Grupos</h2>
    <table id="Table">
        <style>
            #Table {
                border-collapse: collapse;
                width: 50%;
                border: 1px solid #ddd;
                font-size: 18px;
                margin-left: 50%;
            }

            #Table th,
            #Table td {
                text-align: center;
                padding: 5px;
            }

            #Table tr {
                border-bottom: 1px solid #ddd;
            }

            #Table tr.header,
            #Table tr:hover {
                background-color: #f1f1f1;
            }

            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 50%;
            }
            
            tr{
                width: 50px;
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
                <th>Nome dos Grupos</th>
                <th>Editar/Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grupo as $cicle) : ?>
                <tr>
                    <td><?= $cicle['name'] ?></td>
                    <td class="actions">
                        <a href="grupo.php?id=<?= $cicle['id'] ?>" class="edit"><i style="color: black;" class="fas fa-edit fa-xs"></i></a>
                        <a href="deletegrupo.php?id=<?= $cicle['id'] ?>" class="trash"><i style="color: black;" class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= template_footer() ?>