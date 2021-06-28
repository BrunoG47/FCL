<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$role = 'U';
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
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        if(!empty($_POST['create_ficha'])){$create_ficha = 1;}else{$create_ficha = 0;}
        if(!empty($_POST['ver_ficha'])){$ver_ficha = 1;}else{$ver_ficha = 0;}
        if(!empty($_POST['edit_ficha'])){$edit_ficha = 1;}else{$edit_ficha = 0;}
        if(!empty($_POST['create_cli'])){$create_cli = 1;}else{$create_cli = 0;}
        if(!empty($_POST['ver_cli'])){$ver_cli = 1;}else{$ver_cli = 0;}
        if(!empty($_POST['edit_cli'])){$edit_cli= 1;}else{$edit_cli = 0;}
        if(!empty($_POST['dlt_cli'])){$dlt_cli = 1;}else{$dlt_cli = 0;}
        if(!empty($_POST['create_opc'])){$create_opc = 1;}else{$create_opc = 0;}
        if(!empty($_POST['edit_opc'])){$edit_opc = 1;}else{$edit_opc = 0;}
        if(!empty($_POST['dlt_opc'])){$dlt_opc = 1;}else{$dlt_opc = 0;}
        $stmt = $pdo->prepare('UPDATE grupo SET name = ?, create_ficha = ?, ver_ficha = ?, edit_ficha = ?, create_cli = ?, ver_cli = ?, edit_cli = ?, dlt_cli = ?, create_opc = ?, edit_opc = ?, dlt_opc = ? WHERE id = ?');
        $stmt->execute([$name, $create_ficha, $ver_ficha, $edit_ficha, $create_cli, $ver_cli, $edit_cli, $dlt_cli, $create_opc, $edit_opc, $dlt_opc, $_GET['id']]);
        $msg = 'Edição Bem Sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=settings.php">
<?php }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM grupo WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Grupo não tem id');
    }
} else {
    exit('Nenhum grupo selecionado!');
}
?>
<?= template_header('Editar Grupo') ?>

<div class="content update">
    <h2>Editar Grupo: <?= $contact['name'] ?></h2>
    <form action="grupo.php?id=<?= $contact['id'] ?>" method="post">
    <input type="text" name="name" placeholder="Nome Grupo" id="name" autocomplete="off" value="<?= $contact['name'] ?>" required>
        <table>
        <style>
          table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}
          td,th {border: 2px solid #dddddd; text-align: center; padding: 8px; width: 50%;}
          tr:nth-child(even) {background-color: #dddddd;}
        </style>
        <tr>
          <th>Descrição:</th>
          <th>Permissões:</th>
        </tr>
        <tr>
          <td>Criar Fichas</td>
          <td>
            <input type="checkbox" id="create_ficha" name="create_ficha" value="create_ficha" <?php if($contact['create_ficha'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Ver Fichas</td>
          <td>
            <input type="checkbox" id="ver_ficha" name="ver_ficha" value="ver_ficha" <?php if($contact['ver_ficha'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Editar Fichas</td>
          <td>
            <input type="checkbox" id="edit_ficha" name="edit_ficha" value="edit_ficha" <?php if($contact['edit_ficha'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Criar Clientes</td>
          <td>
            <input type="checkbox" id="create_cli" name="create_cli" value="create_cli" <?php if($contact['create_cli'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Ver Clientes</td>
          <td>
            <input type="checkbox" id="ver_cli" name="ver_cli" value="ver_cli" <?php if($contact['ver_cli'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Editar Clientes</td>
          <td>
            <input type="checkbox" id="edit_cli" name="edit_cli" value="edit_cli" <?php if($contact['edit_cli'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Apagar Clientes</td>
          <td>
            <input type="checkbox" id="dlt_cli" name="dlt_cli" value="dlt_cli" <?php if($contact['dlt_cli'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Criar Opções</td>
          <td>
            <input type="checkbox" id="create_opc" name="create_opc" value="create_opc" <?php if($contact['create_opc'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Editar Opções</td>
          <td>
            <input type="checkbox" id="edit_opc" name="edit_opc" value="edit_opc" <?php if($contact['edit_opc'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
        <tr>
          <td>Apagar Opções</td>
          <td>
            <input type="checkbox" id="dlt_opc" name="dlt_opc" value="dlt_opc" <?php if($contact['dlt_opc'] == "1") echo "checked"; ?>/>
          </td>
        </tr>
      </table>
      <input type="submit" value="Editar Grupo">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>