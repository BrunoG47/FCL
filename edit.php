<?php
include "functions.php";    // Using database connection file here
$pdo = pdo_connect_mysql();
$msg = '';
include "config.php";
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
if (isset($_GET['n_ficha'])) {
    if (!empty($_POST)) {
        $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : NULL;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
        $estado_ficha = isset($_POST['estado_ficha']) ? $_POST['estado_ficha'] : '';
        $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
        //error_log ($created_at, 0);
        $stmt = $pdo->prepare('UPDATE fichas SET estado_ficha = ? where n_ficha = ?');
        $stmt->execute([$estado_ficha, $_GET['n_ficha']]);
        //$stmt = $pdo->prepare('UPDATE notas SET estado = ?, nota = ? where ficha = ?');
        //$stmt->execute([$estado, $nota, $_GET['n_ficha']]);
        $sql = $pdo->prepare('INSERT INTO notas (ficha, user_id, estado, nota, created_at) VALUES (?, ?, ?, ?, ?)');
        $sql->execute([$n_ficha, $_GET['n_cliente'], $estado, $nota, $created_at]);
        $msg = 'Edição bem sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=admin.php">
<?php }
    $stmt = $pdo->prepare('SELECT fichas.n_ficha, notas.estado, notas.nota, fichas.estado_ficha FROM fichas INNER JOIN notas ON notas.ficha = fichas.n_ficha WHERE n_ficha = ?');
    $stmt->execute([$_GET['n_ficha']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Ficha não tem número de ficha');
    }
} else {
    exit('Nenhuma ficha selecionada!');
}
?>
<?= template_header('Editar Ficha') ?>
<div class="content update">
    <h2>Editar Ficha #<?= $contact['n_ficha'] ?></h2>
    <form action="edit.php?n_ficha=<?= $contact['n_ficha'] ?>&n_cliente=<?= $_GET['n_cliente'] ?>" method="post">
        <label for="estado">Ponto</label>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="<?= $contact['n_ficha'] ?>" id="n_ficha" readonly hidden>
         <select name="estado" placeholder="Insira estado" id="selectBoxId" style="border: 1px solid #000000; width: 400px; height: 43px; margin-left: -425px; margin-top: 40px;" autocomplete="off">
            <option disabled selected>-- Opções --</option>
            <?php  // Using database connection file here
            $records = mysqli_query($link, "SELECT * FROM estados");  // Use select query here 
            foreach($records as $value)
            {
                if($value['opcoes'] == $contact['estado'])
                {
                     echo "<option selected='selected' value='".$value['id']."'>".$value['opcoes']."</option>";
                }
                else
                {
                    echo "<option value='".$value['id']."'>".$value['opcoes']."</option>";
                }
        }
            ?>
        </select>
        <script type="text/javascript">
            var test = "<?= $contact['estado']; ?>";
            if (test != '' && parseInt(test)) {
                document.getElementById('selectBoxId').selectedIndex = test;
            }
        </script>
        <label for="nota" style="margin-left: 25px;">Nota</label>
        <input type="text" name="nota" placeholder="Nota Ficha" value="<?= $contact['nota'] ?>" id="nota" style="border: 1px solid #000000; margin-top: -43px; margin-left: 425px;" autocomplete="off">
        <label for="estado_ficha">Estado da ficha</label>
        <select name="estado_ficha" placeholder="Insira estado" value="<?= $contact['estado_ficha'] ?>" id="selectId" style="border: 1px solid #000000; width: 400px; height: 43px; margin-left: -425px; margin-top: 40px;" autocomplete="off">

            <option <?= $contact['estado_ficha'] == 'Aberta' ? 'selected="selected"' : ''; ?> value="Aberta">Aberta</option>
            <option <?= $contact['estado_ficha'] == 'Fechada' ? 'selected="selected"' : ''; ?> value="Fechada">Fechada</option>
        </select>
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at" hidden>
        <input type="submit" value="Editar" style="margin-top: 43px; margin-left: 40px">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>
<?= template_footer() ?>