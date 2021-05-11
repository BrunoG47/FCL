<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : '';
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO estados VALUES (?, ?)');
    $stmt->execute([$id, $opcoes]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=read.php">
<?php }
?>
<?= template_header('SosToners-Criar') ?>

<div class="content update">
    <h2>Criar Cliente</h2>
    <form action="create.php" method="post">
        <label for="id">Número Cliente</label>
        <label for="opcoes">Nome</label>
        <input type="text" name="id" placeholder="26" value="auto" id="id" autocomplete="off" readonly>
        <input type="text" name="opcoes" placeholder="Nova Opção" id="opcoes" autocomplete="off">
        <input type="submit" value="Criar Cliente">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>