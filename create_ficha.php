<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $n_cliente = isset($_POST['n_cliente']) && !empty($_POST['n_cliente']) && $_POST['n_cliente'] != 'auto' ? $_POST['n_cliente'] : NULL;
    $n_ficha = isset($_POST['n_ficha']) && !empty($_POST['n_ficha']) && $_POST['n_ficha'] != 'auto' ? $_POST['n_ficha'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $problema = isset($_POST['problema']) ? $_POST['problema'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO fichas VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$n_cliente, $n_ficha, $nome, $problema, $estado, $nota, $created_at]);
    // Output message
    $msg = 'Criação Concluida!';
}
?>
<?= template_header('SosToners-Criar') ?>

<div class="content update">
    <h2>Criar Cliente</h2>
    <form action="create_ficha.php" method="post">
        <label for="n_cliente">Número Cliente</label>
        <label for="n_ficha">Número Ficha</label>
        <input type="text" name="n_cliente" placeholder="26" value="auto" id="n_cliente" readonly> <!-- Vai buscar n_cliente que selecionou -->
        <input type="text" name="n_ficha" placeholder="Nº Ficha" value="auto" id="n_ficha" readonly> <!-- Auto-incrementa n_ficha -->
        <label for="nome">Nome cliente</label>
        <label for="problema">Problema inicial</label>
        <input type="text" name="nome" placeholder="Nome cliente" id="nome"> <!-- Vai buscar come do cliente que selecionou -->
        <input type="text" name="problema" placeholder="Problema Inicial" id="problema">
        <label for="estado">Estado</label>
        <label for="nota">Nota</label>
        <input type="text" name="estado" placeholder="Estado Ficha" id="estado">
        <input type="text" name="nota" placeholder="Nota Ficha" id="nota">
        <label for="created_at">Data de Criação</label>
        <input style="margin-right: 200px;" type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at">
        <input type="submit" value="Criar Ficha">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>