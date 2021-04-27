<?php
include 'functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM users ORDER BY n_cliente LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_contacts = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
?>
<?= template_header('Read') ?>

<div class="content read">
    <h2>Clientes</h2>
    <a href="create.php" class="create-contact">Criar Cliente</a>
    <table>
        <thead>
            <tr>
                <td>Número Cliente</td>
                <td>Email</td>
                <td>Nome</td>
                <td>Telefone</td>
                <td>NIF</td>
                <td>Morada</td>
                <td>Código Postal</td>
                <td>Data de Criação</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact) : ?>
                <tr>
                    <td><?= $contact['n_cliente'] ?></td>
                    <td><?= $contact['email'] ?></td>
                    <td><?= $contact['nome'] ?></td>
                    <td><?= $contact['telefone'] ?></td>
                    <td><?= $contact['nif'] ?></td>
                    <td><?= $contact['morada'] ?></td>
                    <td><?= $contact['codigo'] ?></td>
                    <td><?= $contact['created_at'] ?></td>
                    <td class="actions">
                        <a href="update.php?n_cliente=<?= $contact['n_cliente'] ?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                        <a href="delete.php?n_cliente=<?= $contact['n_cliente'] ?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1) : ?>
            <a href="read.php?page=<?= $page - 1 ?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page * $records_per_page < $num_contacts) : ?>
            <a href="read.php?page=<?= $page + 1 ?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?= template_footer() ?>