<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE estados SET opcoes = ? WHERE id = ?');
        $stmt->execute([$opcoes, $_GET['id']]);
        $msg = 'Edição Bem Sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=settings.php">
<?php }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM estados WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Opção Não tem número');
    }
} else {
    exit('Nenhuma opção selecionada!');
}
?>
<?= template_header('SosToners-Editar Opção') ?>

<div class="content update">
    <h2>Editar opção: <?= $contact['opcoes'] ?></h2>
    <form action="editopc.php?id=<?= $contact['id'] ?>" method="post">
        <label for="opcoes">Opção</label>
        <label for=""></label>
        <input type="text" name="opcoes" placeholder="Opção" value="<?= $contact['opcoes'] ?>" id="opcoes" autocomplete="off">
        <input type="submit" value="Editar">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>