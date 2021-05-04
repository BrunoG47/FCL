<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$role = 'U';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['n_cliente'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $n_cliente = isset($_POST['n_cliente']) ? $_POST['n_cliente'] : NULL;
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
        $nif = isset($_POST['nif']) ? $_POST['nif'] : '';
        $morada = isset($_POST['morada']) ? $_POST['morada'] : '';
        $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
        $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
        // Update the record
        $stmt = $pdo->prepare('UPDATE users SET n_cliente = ?, email = ?, nome = ?, telefone = ?, nif = ?, morada = ?, codigo = ?, role = ?, created_at = ? WHERE n_cliente = ?');
        $stmt->execute([$n_cliente, $email, $nome, $telefone, $nif, $morada, $codigo, $role, $created_at, $_GET['n_cliente']]);
        $msg = 'Edição Bem Sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=read.php">
<?php }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM users WHERE n_cliente = ?');
    $stmt->execute([$_GET['n_cliente']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Cliente Não tem número de cliente');
    }
} else {
    exit('Nenhum número cliente selecionado!');
}
?>
<?= template_header('SosToners-Editar Cliente') ?>

<div class="content update">
    <h2>Editar Cliente #<?= $contact['n_cliente'] ?></h2>
    <form action="update.php?n_cliente=<?= $contact['n_cliente'] ?>" method="post">
        <label for="n_cliente">Número Cliente</label>
        <label for="nome">Nome</label>
        <input type="text" name="n_cliente" placeholder="26" value="<?= $contact['n_cliente'] ?>" id="n_cliente" autocomplete="off" readonly>
        <input type="text" name="nome" placeholder="Nome cliente" value="<?= $contact['nome'] ?>" id="nome" autocomplete="off">
        <label for="email">Email</label>
        <label for="telefone">Telefone</label>
        <input type="text" name="email" placeholder="Email cliente" value="<?= $contact['email'] ?>" id="email" autocomplete="off">
        <input type="text" name="telefone" placeholder="Contacto cliente" value="<?= $contact['telefone'] ?>" id="telefone" autocomplete="off">
        <label for="nif">Nif</label>
        <label for="morada">Morada</label>
        <input type="text" name="nif" placeholder="Nif cliente" value="<?= $contact['nif'] ?>" id="nif" autocomplete="off">
        <input type="text" name="morada" placeholder="Morada cliente" value="<?= $contact['morada'] ?>" id="morada" autocomplete="off">
        <label for="codigo">Cógido</label>
        <label for="created_at">Data de Criação</label>
        <input type="text" name="codigo" placeholder="Código Postal cliente" value="<?= $contact['codigo'] ?>" id="codigo" autocomplete="off">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i', strtotime($contact['created_at'])) ?>" id="created_at">
        <input type="submit" value="Editar">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>