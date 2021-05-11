<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact n_cliente exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM estados WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Não existe nenhuma opção com esse número!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'Sim') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM estados WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Opção Eliminada!'; ?>
            <meta http-equiv="refresh" content="0.5;url=settings.php">
<?php } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: settings.php');
            exit;
        }
    }
} else {
    exit('Nenhuma opção selecionada!');
}
?>
<?= template_header('SosToners-Eliminar Opção') ?>

<div class="content delete">
    <h2>Eliminar opção: <?= $contact['opcoes'] ?></h2>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php else : ?>
        <p>Tem a certeza que pretende eliminar a opção: <?= $contact['opcoes'] ?>?</p>
        <div class="yesno">
            <a href="deleteopc.php?id=<?= $contact['id'] ?>&confirm=Sim">Sim</a>
            <a href="deleteopc.php?id=<?= $contact['id'] ?>&confirm=Não">Não</a>
        </div>
    <?php endif; ?>
</div>

<?= template_footer() ?>