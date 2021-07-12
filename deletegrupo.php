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
if ($_SESSION["role"] == 'F') {
    header('Location: settings.php');
    exit;
}
if (isset($_GET['id'])) {
    $sqq = $pdo->prepare('SELECT grupo FROM users WHERE grupo = ?');
    $sqq->execute([$_GET['id']]);
    $c = $sqq->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare('SELECT * FROM grupo WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Não existe nenhum grupo com esse id!');
    }
    // Make sure the user confirms beore deletion
    if(empty($c)){
        if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'Sim') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM grupo WHERE id = ?');
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
        exit('Existem funcionários com este grupo');
    }
} else {
    exit('Nenhum grupo selecionado!');
}
?>
<?= template_header('Eliminar Grupo') ?>

<div class="content delete">
    <h2>Eliminar grupo: <?= $contact['name'] ?></h2>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php else : ?>
        <p>Tem a certeza que pretende eliminar o grupo: <?= $contact['name'] ?>?</p>
        <div class="yesno">
            <a href="deletegrupo.php?id=<?= $contact['id'] ?>&confirm=Sim">Sim</a>
            <a href="deletegrupo.php?id=<?= $contact['id'] ?>&confirm=Não">Não</a>
        </div>
    <?php endif; ?>
</div>

<?= template_footer() ?>