<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
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
    $stmt = $pdo->prepare('INSERT INTO fichas VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$n_cliente, $n_ficha, $estado, $nota, $problema, $created_at]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=admin.php">
<?php }
?>
<?= template_header('SosToners-Criar') ?>

<div class="content update">
    <h2>Criar Ficha</h2>
    <form action="create_ficha.php" method="post">
        <label for="n_cliente">Número Cliente</label>
        <label for="n_ficha">Número Ficha</label>
        <input type="text" name="n_cliente" placeholder="Número Cliente" id="n_cliente" value="<?= $_GET['n_cliente'] ?>" autocomplete="off" required readonly>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="automático" id="n_ficha" autocomplete="off" readonly>
        <label for="estado">Estado</label>
        <label for="nota">Nota</label>
        <input type="text" name="estado" placeholder="Estado" id="estado" autocomplete="off">
        <input type="text" name="nota" placeholder="Nota" id="nota" autocomplete="off">
        <label for="problema">Problema Inicial</label>
        <label for="created_at">Data de Criação</label>
        <input type="text" name="problema" placeholder="Problema Inicial" id="problema" autocomplete="off">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at">
        <input type="submit" value="Criar Ficha">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>