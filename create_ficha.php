<?php

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$db = mysqli_connect("localhost", "tugaspot_tugaspot", "Pra@513285776@@@@", "tugaspot_fcl");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SESSION["role"] == 'U') {
    header('Location: home.php');
    exit;
}
// Check if POST data is not empty
if (!isset($_GET['n_cliente'])) {
    echo '<script>';
    echo 'window.onload=function() {';
    echo 'document.getElementById("myButton").style.display = "none";';
    echo 'document.getElementById("n_ficha").style.display = "none";';
    echo 'document.getElementById("n_ficha1").style.display = "none";';
    echo 'document.getElementById("n_cliente1").style.display = "none";';
    echo 'document.getElementById("n_cliente").style.display = "none"; }';
    echo '</script>';
} else {
    echo '<script>';
    echo 'window.onload=function() {';
    echo 'document.getElementById("teste").style.display = "none";';
    echo 'document.getElementById("myInput").style.display = "none"; }';
    echo '</script>';
}
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $cliente = isset($_POST['cliente']) ? $_POST['cliente'] : '';
    // Insert new record into the contacts table
    $query = $pdo->prepare('SELECT * FROM users WHERE CONCAT(n_cliente, email, nome, telefone, nif) LIKE ?');
    $query->execute(['%' . $cliente . '%']);
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    //<meta http-equiv="refresh" content="0.5;url=admin.php">-->
    if (isset($_POST['n_cliente'])) {
        echo "Teste";
        $n_cliente = isset($_POST['n_cliente']) ? $_POST['n_cliente'] : '';
        // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
        $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : '';
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
        $problema = isset($_POST['problema']) ? $_POST['problema'] : '';
        $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
        $stmt = $pdo->prepare('INSERT INTO fichas VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$n_cliente, $n_ficha, $estado, $nota, $problema, $created_at]);
        // Output message
        $msg = 'Criação Concluida!';
                echo $estado;
        header('Location: admin.php');
    }
}

?>
<?= template_header('Criar Ficha') ?>

<div class="content update">
    <h2>Criar Ficha</h2>
    <form action="create_ficha.php" method="POST">
        <input type="text" id="myInput" placeholder="Procurar" name="cliente" id="cliente" autocomplete="off">
        <input type="submit" id="teste" value="Procurar cliente">
    </form>
    <form action="create_ficha.php" method="post">
        <?php if (isset($contacts)) { ?>
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
                                <a href="create_ficha.php?n_cliente=<?= $contact['n_cliente'] ?>" class="add"><i style="color: black;" class="fas fa-paperclip fa-xs"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } ?>
        <label for="cliente" id="n_cliente1">Número Cliente</label>
        <label for="n_ficha" id="n_ficha1">Número Ficha</label>
        <input type="text" name="n_cliente" placeholder="Número Cliente" id="n_cliente" autocomplete="off" value="<?= isset($_GET['n_cliente']) ? $_GET['n_cliente'] : "" ?>" readonly>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="automático" id="n_ficha" autocomplete="off" readonly>
        <label for="estado">Estado</label>
        <label for="problema">Problema Inicial</label>
        <select name="estado" placeholder="Insira estado" id="selectBoxId" style="width: 400px; height: 43px;" autocomplete="off">
            <option disabled selected>-- Opções --</option>
            <?php  // Using database connection file here
            $records = mysqli_query($db, "SELECT opcoes FROM estados");  // Use select query here 

            while ($data = mysqli_fetch_array($records)) {
                echo "<option value='" . $data['opcoes'] . "'>" . $data['opcoes'] . "</option>";  // displaying data in option menu
            }
            ?>
        </select>
        <input type="text" name="problema" placeholder="Problema Inicial" id="problema" style=" margin-left: 25px;" autocomplete="off">
        <label for="nota">Nota</label>
        <label for="created_at">Data de Criação</label>
        <input type="text" name="nota" placeholder="Nota" id="nota" autocomplete="off">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at">
        <input type="submit" id="myButton" value="Criar Ficha">
    </form>

    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>
<?= template_footer() ?>