<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
include 'config.php';
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
} 
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION["role"] == 'U') {
    header('Location: home.php');
    exit;
}
$estado_ficha = "Aberta";

if (!isset($_GET['n_cliente'])) {
    echo '<script>';
    echo 'window.onload=function() {';
    echo 'document.getElementById("myButton").style.display = "none";';
    echo 'document.getElementById("n_ficha").style.display = "none";';
    echo 'document.getElementById("n_cliente1").style.display = "none";';
    echo 'document.getElementById("n_cliente").style.display = "none";';
    echo 'document.getElementById("selectBoxId").style.display = "none";';
    echo 'document.getElementById("lbl_ponto").style.display = "none";';
    echo 'document.getElementById("lbl_problema").style.display = "none";';
    echo 'document.getElementById("lbl_estado").style.display = "none";';
    echo 'document.getElementById("lbl_nota").style.display = "none";';
    echo 'document.getElementById("nota").style.display = "none";';
    echo 'document.getElementById("problema").style.display = "none";';
    echo 'document.getElementById("selectId").style.display = "none"; }';
    echo '</script>';
} else {
    echo '<script>';
    echo 'window.onload=function() {';
    echo 'document.getElementById("teste").style.display = "none";';
    echo 'document.getElementById("t").style.display = "none";';
    echo 'document.getElementById("myInput").style.display = "none"; }';
    echo '</script>';
}
$criador = $pdo->prepare('SELECT nome FROM users WHERE n_cliente = ?');
$criador->execute([$_SESSION["n_cliente"]]);
$responsavel = $criador->fetch(PDO::FETCH_ASSOC);
echo($responsavel['nome']);
if (!empty($_POST)) {
    $cliente = isset($_POST['cliente']) ? $_POST['cliente'] : '';
    $query = $pdo->prepare('SELECT * FROM users WHERE CONCAT(n_cliente, email, nome, telefone, nif) LIKE ?');
    $query->execute(['%' . $cliente . '%']);
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_POST['n_cliente'])) {
        $n_cliente = isset($_POST['n_cliente']) ? $_POST['n_cliente'] : '';
        $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : '';
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
        $problema = isset($_POST['problema']) ? $_POST['problema'] : '';
        $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
        $stmt = $pdo->prepare('INSERT INTO fichas(n_cliente, n_ficha, problema, estado_ficha, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$n_cliente, $n_ficha, $problema, $estado_ficha, $created_at]);
        $ult_id = $pdo->lastInsertId();
        $sql = $pdo->prepare('INSERT INTO notas (ficha, user_id, estado, nota, created_at) VALUES ( ?, ?, ?, ?, ?)');
        $sql->execute([$ult_id, $n_cliente, $estado, $nota, $created_at]);
        $msg = 'Criação Concluida!';
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
        <input type="button" id="t" value="Novo Cliente" onclick="location.href='create.php';">
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
        <input type="text" name="n_cliente" placeholder="Número Cliente" id="n_cliente" autocomplete="off" value="<?= isset($_GET['n_cliente']) ? $_GET['n_cliente'] : "" ?>" style="margin-left: -425px; margin-top: 40px;" readonly>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="automático" id="n_ficha" autocomplete="off" readonly hidden>
        <label for="estado" id="lbl_ponto">Ponto</label>
        <label for="problema" id="lbl_problema">Problema Inicial</label>
        <select name="estado" placeholder="Insira estado" id="selectBoxId" style="border: 1px solid #000000; width: 400px; height: 43px; margin-top: -58px;" autocomplete="off">
            <option disabled selected>-- Opções --</option>
            <?php
            $records = mysqli_query($link, "SELECT id, opcoes FROM estados");

            while ($data = mysqli_fetch_array($records)) {
                echo "<option value='" . $data['id'] . "'>" . $data['opcoes'] . "</option>"; 
            }
            ?>
        </select>
        
        <input type="text" name="problema" placeholder="Problema Inicial" id="problema" autocomplete="off">
        <label for="nota" style="margin-top: -40px;" id="lbl_nota">Nota</label>
        <label for="estado_ficha" id="lbl_estado">Estado da ficha</label>
        <input type="text" name="nota" placeholder="Nota" id="nota" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px;">
        <select name="estado_ficha" id="selectId" style="border: 1px solid #000000; width: 400px; height: 43px;" autocomplete="off" readonly>
            <option>Aberta</option>
        </select>
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at" style="margin-left: -425px; margin-top: 40px;" hidden>
        <input type="submit" id="myButton" style="width: 300px; height: 43px; margin-left: 40px; margin-top: -25px;" value="Criar Ficha">
    </form>

    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>
<?= template_footer() ?>