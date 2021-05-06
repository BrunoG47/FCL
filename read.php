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
$stmt = $pdo->prepare('SELECT * FROM users WHERE n_cliente != "2259" ORDER BY n_cliente');
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
?>
<?= template_header('SosToners-Clientes') ?>

<div class="content read">
    <a href="create.php" class="create-contact">Criar Cliente</a>
    <h2 style="color: #4a536e;">Clientes</h2>
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
                <th>Nº Cliente</th>
                <th>Email</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>NIF</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td><?= $contact['n_cliente'] ?></td>
                    <td><?= $contact['email'] ?></td>
                    <td><?= $contact['nome'] ?></td>
                    <td><?= $contact['telefone'] ?></td>
                    <td><?= $contact['nif'] ?></td>
                    <td><?= $contact['created_at'] ?></td>
                    <td class="actions">
                        <a href="create_ficha1.php?n_cliente=<?= $contact['n_cliente'] ?>" class="add"><i style="color: black;" class="fas fa-paperclip fa-xs"></i></a>
                        <a href="update.php?n_cliente=<?= $contact['n_cliente'] ?>" class="edit"><i style="color: black;" class="fas fa-user-edit fa-xs"></i></a>
                        <a href="delete.php?n_cliente=<?= $contact['n_cliente'] ?>" class="trash"><i style="color: black;" class="fas fa-trash fa-xs"></i></a>
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
</div>

<?= template_footer() ?>