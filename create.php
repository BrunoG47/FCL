<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$role = 'U';

if ($_SESSION["role"] == 'U') {
    header('Location: home.php');
    exit;
}
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $n_cliente = isset($_POST['n_cliente']) && !empty($_POST['n_cliente']) && $_POST['n_cliente'] != 'auto' ? $_POST['n_cliente'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $nif = isset($_POST['nif']) ? $_POST['nif'] : '';
    $morada = isset($_POST['morada']) ? $_POST['morada'] : '';
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $param_password = password_hash($telefone, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$n_cliente, $email, $param_password, $nome, $telefone, $nif, $morada, $codigo, $role, $created_at]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=read.php">
<?php }
?>
<?= template_header('Criar Cliente') ?>

<div class="content update">
    <h2>Criar Cliente</h2>
    <form action="create.php" method="post">
        <label for="n_cliente">Número Cliente</label>
        <label for="nome">Nome</label>
        <input type="text" name="n_cliente" placeholder="26" value="auto" id="n_cliente" autocomplete="off" readonly>
        <input type="text" name="nome" placeholder="Nome cliente" id="nome" autocomplete="off">
        <label for="email">Email</label>
        <label for="telefone">Telefone</label>
        <input type="text" name="email" placeholder="Email cliente" id="email" autocomplete="off">
        <input type="text" name="telefone" placeholder="Contacto cliente" id="telefone" autocomplete="off">
        <label for="nif">Nif</label>
        <label for="morada">Morada</label>
        <input type="text" name="nif" placeholder="Nif cliente" id="title" autocomplete="off">
        <input type="text" name="morada" placeholder="Morada cliente" id="morada" autocomplete="off">
        <label for="codigo">Cógido</label>
        <label for="created_at">Data de Criação</label>
        <input type="text" name="codigo" placeholder="Código Postal cliente" id="codigo" autocomplete="off">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at">
        <input type="submit" value="Criar Cliente">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>