<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
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
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : '';
    // Insert new record into the contacts table
    $sql = $pdo->prepare('ALTER TABLE estados AUTO_INCREMENT = 1');
    $sql->execute();
    $stmt = $pdo->prepare('INSERT INTO estados VALUES (?, ?)');
    $stmt->execute([$id, $opcoes]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=settings.php">
<?php }
?>
<?= template_header('Criar Opção') ?>

<div class="content update">
    <h2>Criar Opção</h2>
    <form action="createopc.php" method="post">
        <label for="id">Id Opção</label>
        <label for="opcoes">Nome</label>
        <input type="text" name="id" placeholder="26" value="auto(não editável)" id="id" autocomplete="off" readonly>
        <input type="text" name="opcoes" placeholder="Nova Opção" id="opcoes" autocomplete="off">
        <input type="submit" value="Criar Opção">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>