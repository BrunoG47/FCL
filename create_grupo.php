<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
  $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
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
  $stmt = $pdo->prepare('INSERT INTO grupo (id, name, create_ficha, ver_ficha, edit_ficha, create_cli, ver_cli, edit_cli, dlt_cli, create_opc, edit_opc, dlt_opc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $stmt->execute([$id, $name, $create_ficha, $ver_ficha, $edit_ficha, $create_cli, $ver_cli, $edit_cli, $dlt_cli, $create_opc, $edit_opc,$dlt_opc]);
  $msg = 'Criação Concluida!'; ?>
  <meta http-equiv="refresh" content="0.5;url=settings.php">
<?php }
?>
<?= template_header('SosToners-Criar Opção') ?>
<div class="content update">
    <h2>Criaçao de Grupos</h2>
    <form action="create_grupo.php" method="post">
    <input type="text" name="name" placeholder="Nome Grupo" id="name" autocomplete="off" required>
      <table>
        <style>
          table {font-family: arial, sans-serif; border-collapse: collapse; width: 100%;}
          td,th {border: 2px solid #dddddd; text-align: center; padding: 8px; width: 50%;}
          tr:nth-child(even) {background-color: #dddddd;}
        </style>
        <h2>Grupos</h2>
        <tr>
          <th>Descrição:</th>
          <th>Permissões:</th>
        </tr>
        <tr>
          <td>Criar Fichas</td>
          <td>
            <input type="checkbox" id="create_ficha" name="create_ficha" value="create_ficha" />
          </td>
        </tr>
        <tr>
          <td>Ver Fichas</td>
          <td>
            <input type="checkbox" id="ver_ficha" name="ver_ficha" value="ver_ficha" />
          </td>
        </tr>
        <tr>
          <td>Editar Fichas</td>
          <td>
            <input type="checkbox" id="edit_ficha" name="edit_ficha" value="edit_ficha" />
          </td>
        </tr>
        <tr>
          <td>Criar Clientes</td>
          <td>
            <input type="checkbox" id="create_cli" name="create_cli" value="create_cli" />
          </td>
        </tr>
        <tr>
          <td>Ver Clientes</td>
          <td>
            <input type="checkbox" id="ver_cli" name="ver_cli" value="ver_cli"/>
          </td>
        </tr>
        <tr>
          <td>Editar Clientes</td>
          <td>
            <input type="checkbox" id="edit_cli" name="edit_cli" value="edit_cli" />
          </td>
        </tr>
        <tr>
          <td>Apagar Clientes</td>
          <td>
            <input type="checkbox" id="dlt_cli" name="dlt_cli" value="dlt_cli" />
          </td>
        </tr>
        <tr>
          <td>Criar Opções</td>
          <td>
            <input type="checkbox" id="create_opc" name="create_opc" value="create_opc" />
          </td>
        </tr>
        <tr>
          <td>Editar Opções</td>
          <td>
            <input type="checkbox" id="edit_opc" name="edit_opc" value="edit_opc" />
          </td>
        </tr>
        <tr>
          <td>Apagar Opções</td>
          <td>
            <input type="checkbox" id="dlt_opc" name="dlt_opc" value="dlt_opc" />
          </td>
        </tr>
      </table>
      <input type="submit" value="Criar Grupo">
    </form>
    <div class="content update">
    <?= template_footer() ?>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>