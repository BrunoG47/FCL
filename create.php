<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$role = 'U';
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION["role"] == 'U') {
    header('Location: home.php');
    exit;
}
if (!empty($_POST)) {
    $n_cliente = isset($_POST['n_cliente']) && !empty($_POST['n_cliente']) && $_POST['n_cliente'] != 'auto' ? $_POST['n_cliente'] : NULL;
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $nif = isset($_POST['nif']) ? $_POST['nif'] : '';
    $morada = isset($_POST['morada']) ? $_POST['morada'] : '';
    $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
    $param_password = password_hash($telefone, PASSWORD_DEFAULT);
    $sqq = $pdo->prepare('SELECT telefone FROM users WHERE telefone = ?');
    $sqq->execute([$telefone]);
    $c = $sqq->fetch(PDO::FETCH_ASSOC);
    if(empty($c)){
    $stmt = $pdo->prepare('INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$n_cliente, $email, $param_password, $nome, $telefone, $nif, $morada, $codigo, $role, NULL, $created_at]);
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=read.php">
<?php } else {
    exit('Existe um utilizador em este telefone');
} 
}
?>
<?= template_header('Criar Cliente') ?>
    <div class="content update">
    <h2>Criar Cliente</h2>
    <form action="create.php" method="post">
        <label for="nome">Nome</label>
        <input type="text" name="n_cliente" placeholder="26" value="auto" id="n_cliente" autocomplete="off" readonly hidden>
        <input type="text" name="nome" placeholder="Nome cliente" id="nome" autocomplete="off" style="margin-left: -425px; margin-top: 40px;">
        <label for="email">Email</label>
        <label for="telefone">Telefone</label>
        <input type="text" name="email" placeholder="Email cliente" id="email" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px;">
        <input type="text" name="telefone" placeholder="Contacto cliente" id="telefone" autocomplete="off">
        <label for="nif" style="margin-top: -40px;">Nif</label>
        <label for="morada">Morada</label>
        <input type="text" name="nif" placeholder="Nif cliente" id="title" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px;">
        <input type="text" name="morada" placeholder="Morada cliente" id="morada" autocomplete="off">
        <label for="codigo" style="margin-top: -40px;">Cogido</label>
        <input type="text" name="codigo" placeholder="Codigo Postal cliente" id="codigo" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px; margin-left: 425px;">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at" hidden>
        <input type="submit" value="Criar Cliente">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>