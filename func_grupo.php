<?php
include "functions.php"; 
$pdo = pdo_connect_mysql();
$msg = '';
$role = 'F';
include "config.php";
session_start();
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
if (isset($_GET['n_cliente'])) {
    if (!empty($_POST)) {
        $grupo = isset($_POST['grupo']) ? $_POST['grupo'] : '';
        $stmt = $pdo->prepare('UPDATE users SET grupo = ? WHERE n_cliente = ?');
        $stmt->execute([$grupo, $_GET['n_cliente']]);
        $msg = 'Edição bem sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=func.php">
<?php }
        $sql = $pdo->prepare('SELECT n_cliente, nome, telefone, grupo FROM users WHERE n_cliente = ?');
        $sql->execute([$_GET['n_cliente']]);
        $contact = $sql->fetch(PDO::FETCH_ASSOC);
        if (!$contact) {
        exit('Cliente Não tem número de cliente');
        }
            } else {
                exit('Nenhum número cliente selecionado!');
            }
        ?>
<?= template_header('Grupo funcionário') ?>
<div class="content update">
    <h2>Grupos de funcionários</h2>
    <form action="func_grupo.php?n_cliente=<?= $contact['n_cliente'] ?>" method="post">
    <table id="Table">
        <style>
            #Table {
                border-collapse: collapse;
                width: 90%;
                border: 1px solid #ddd;
                font-size: 18px;
            }

            #Table th,
            #Table td {
                text-align: center;
                padding: 5px;
            }

            #Table tr {
                border-bottom: 1px solid #ddd;
            }

            #Table tr.header,
            #Table tr:hover {
                background-color: #f1f1f1;
            }

            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 90%;
            }

            tr {
                width: 50px;
            }

            td,
            th {
                border: 2px solid #dddddd;
                text-align: center;
                padding: 6px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
        <thead>
            <tr>
                <th>Nº Cliente</th>
                <th>Nome Funcionário</th>
                <th>Telefone Funcionário</th>
                <th>Grupo Funcionário</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td><?= $contact['n_cliente'] ?></td>
                    <td><?= $contact['nome'] ?></td>
                    <td><?= $contact['telefone'] ?></td>
                    <td><?= $contact['grupo'] ?></td>
                    <td>
                    <select name="grupo" placeholder="Escolha grupo" id="grupo" style="border: 1px solid #000000;" autocomplete="off">
                    <option disabled selected>-- Grupos --</option>
                    <?php
                    $records = mysqli_query($link, "SELECT id, name FROM grupo");  // Use select query here 
                    foreach($records as $value) :
                        if($value['id'] == $contact['grupo'])
                        {
                        echo "<option selected='selected' value='".$value['id']."'>".$value['name']."</option>";
                        } else {
                        echo "<option value='".$value['id']."'>".$value['name']."</option>";
                        }
                    endforeach;
            ?>
        </select> </td>
                </tr>
        </tbody>
    </table>
    <input type="submit" value="Atualizar grupo" style="margin-top: 30px;">
    </form>
</div>
<?= template_footer() ?>