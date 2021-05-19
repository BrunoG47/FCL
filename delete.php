<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if ($_SESSION["role"] == 'U') {
    header('Location: home.php');
    exit;
}
// Check that the contact n_cliente exists
if (isset($_GET['n_cliente'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM users WHERE n_cliente = ?');
    $stmt->execute([$_GET['n_cliente']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Não existe nenhum cliente com esse número de cliente!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'Sim') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM users WHERE n_cliente = ?');
            $stmt->execute([$_GET['n_cliente']]);
            $msg = 'Cliente Eliminado!'; ?>
            <meta http-equiv="refresh" content="0.5;url=read.php">
<?php } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('Nenhum número de cliente selecionado!');
}
?>
<?= template_header('Eliminar Cliente') ?>

<div class="content delete">
    <h2>Eliminar Cliente #<?= $contact['n_cliente'] ?></h2>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php else : ?>
        <p>Tem a certeza que pretende eliminar o cliente #<?= $contact['n_cliente'] ?>?</p>
        <div class="yesno">
            <a href="delete.php?n_cliente=<?= $contact['n_cliente'] ?>&confirm=Sim">Sim</a>
            <a href="delete.php?n_cliente=<?= $contact['n_cliente'] ?>&confirm=Não">Não</a>
        </div>
    <?php endif; ?>
</div>

<?= template_footer() ?>