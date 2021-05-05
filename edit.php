<?php
include "functions.php";    // Using database connection file here
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_GET['n_ficha'])) {
    if (!empty($_POST)) {
        $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : NULL;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $nota = isset($_POST['nota']) ? $_POST['nota'] : '';

        $stmt = $pdo->prepare('UPDATE fichas SET n_ficha = ?, estado = ?, nota = ? where n_ficha = ?');
        $stmt->execute([$n_ficha, $estado, $nota, $_GET['n_ficha']]);
        $msg = 'Edição bem sucedida!'; ?>
        <meta http-equiv="refresh" content="0.5;url=admin.php">
<?php }
    $stmt = $pdo->prepare('SELECT n_ficha, estado, nota FROM fichas WHERE n_ficha = ?');
    $stmt->execute([$_GET['n_ficha']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Ficha não tem número de ficha');
    }
} else {
    exit('Nenhuma ficha selecionada!');
}
?>
<?= template_header('SosToners-Editar Ficha') ?>
<div class="content update">
    <h2>Editar Ficha #<?= $contact['n_ficha'] ?></h2>
    <form action="edit.php?n_ficha=<?= $contact['n_ficha'] ?>" method="post">
        <label for="n_ficha">Número Ficha</label>
        <label for="estado" style="margin-left: 40px;">Estado</label>
        <input type="text" name="n_ficha" placeholder="Número Ficha" value="<?= $contact['n_ficha'] ?>" id="n_ficha" readonly>
        <select name="estado" method="post" placeholder="Insira estado" id="selectBoxId" style="margin-left: 40px;" autocomplete="off" Required>
            <option <?= $contact['estado'] == 'Para diagnóstico' ? 'selected="selected"' : ''; ?> value="Para diagnóstico">Para diagnóstico</option>
            <option <?= $contact['estado'] == 'Em diagnóstico' ? 'selected="selected"' : ''; ?> value="Em diagnóstico">Em diagnóstico</option>
            <option <?= $contact['estado'] == 'Em testes' ? 'selected="selected"' : ''; ?> value="Em testes">Em testes</option>
            <option <?= $contact['estado'] == 'Aguarda aprovação' ? 'selected="selected"' : ''; ?> value="Aguarda aprovação">Aguarda aprovação</option>
            <option <?= $contact['estado'] == 'Aguarda peças' ? 'selected="selected"' : ''; ?> value="Aguarda peças">Aguarda peças</option>
            <option <?= $contact['estado'] == 'Em laboratório' ? 'selected="selected"' : ''; ?> value="Em laboratório">Em laboratório</option>
            <option <?= $contact['estado'] == 'Em reparação' ? 'selected="selected"' : ''; ?> value="Em reparação">Em reparação</option>
            <option <?= $contact['estado'] == 'Em controlo' ? 'selected="selected"' : ''; ?> value="Em controlo">Em controlo</option>
            <option <?= $contact['estado'] == 'Pronto para entrega' ? 'selected="selected"' : ''; ?> value="Pronto para entrega">Pronto para entrega</option>
            <option <?= $contact['estado'] == 'Entregue' ? 'selected="selected"' : ''; ?> value="Entregue">Entregue</option>
            <option <?= $contact['estado'] == 'Sem reparação' ? 'selected="selected"' : ''; ?> value="Sem reparação">Sem reparação</option>
            <option <?= $contact['estado'] == 'Para devolução' ? 'selected="selected"' : ''; ?> value="Para devolução">Para devolução</option>
        </select>
        <script type="text/javascript">
            var test = "<?= $estado; ?>";
            if (test != '' && parseInt(test)) {
                document.getElementById('selectBoxId').selectedIndex = test;
            }
        </script>
        <label for="nota">Nota</label>
        <input type="text" name="nota" placeholder="Nota Ficha" value="<?= $contact['nota'] ?>" id="nota" style="margin-left: -425px; margin-top: 40px;" autocomplete="off">
        <input type="submit" value="Editar" style="margin-top: 30px; margin-left: 40px">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>
<?= template_footer() ?>