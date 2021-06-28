<!--
    Criar ficha se for selecionado um cliente
-->
<?php

include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
include 'config.php';
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
} 
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
$estado_ficha = "Aberta";
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $n_cliente = isset($_POST['n_cliente']) ? $_POST['n_cliente'] : '';
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
    $problema = isset($_POST['problema']) ? $_POST['problema'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO fichas(n_cliente, n_ficha, problema, estado_ficha, created_at) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$n_cliente, $n_ficha, $problema, $estado_ficha, $created_at]);
    $ult_id = $pdo->lastInsertId();
    $sql = $pdo->prepare('INSERT INTO notas (ficha, user_id, estado, nota, created_at) VALUES ( ?, ?, ?, ?, ?)');
    $sql->execute([$ult_id, $n_cliente, $estado, $nota, $created_at]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=admin.php">
<?php }
?>
<?= template_header('Criar Ficha') ?>

<div class="content update">
    <h2>Criar Ficha</h2>
    <form action="create_ficha1.php" method="post">
        <label for="n_cliente">Número Cliente</label>
        <input type="text" name="n_cliente" placeholder="Número Cliente" id="n_cliente" value="<?= $_GET['n_cliente'] ?>" autocomplete="off" style="margin-left: -425px; margin-top: 40px;" required readonly>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="automático" id="n_ficha" autocomplete="off" readonly hidden>
        <label for="estado">Estado</label>
        <label for="nota">Nota</label>
        <select name="estado" placeholder="Insira estado" id="selectBoxId" style="border: 1px solid #000000; width: 400px; height: 43px; margin-top: -58px;" autocomplete="off">
            <option disabled selected>-- Opções --</option>
            <?php  // Using database connection file here
            $records = mysqli_query($link, "SELECT id, opcoes FROM estados");  // Use select query here 

            while ($data = mysqli_fetch_array($records)) {
                echo "<option value='" . $data['id'] . "'>" . $data['opcoes'] . "</option>";  // displaying data in option menu
            }
            ?>
        </select>
        <input type="text" name="nota" placeholder="Nota" style="width: 400px; height: 43px;" id="nota" autocomplete="off">
        <label for="problema" style="margin-top: -40px;">Problema Inicial</label>
        <label for="estado_ficha">Estado da ficha</label>
        <input type="text" name="problema" placeholder="Problema Inicial" id="problema" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px;">
        <select name="estado_ficha" id="selectId" style="border: 1px solid #000000; width: 400px; height: 43px;" autocomplete="off" readonly>
            <option>Aberta</option>
        </select>
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at" style="margin-left: -425px; margin-top: 40px;" hidden>
        <input type="submit" value="Criar Ficha" style="width: 300px; height: 43px; margin-left: 40px; margin-top: -3px;">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>