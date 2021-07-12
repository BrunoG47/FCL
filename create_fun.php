<?php
include 'config.php';
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

session_start();
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE role = "F"');
    $stmt->execute();
    $contacts = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $n_fun = isset($_POST['n_fun']) && !empty($_POST['n_fun']) && $_POST['n_fun'] != 'auto' ? $_POST['n_fun'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $grupo = isset($_POST['grupo']) ? $_POST['grupo'] : '';
    $nif = "---";
    $morada = "---";
    $codigo = "---";
    $role = "F";
    $created_at = isset($_POST['created_at']) ? $_POST['created_at'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $param_password = password_hash($telefone, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$n_fun, $email, $param_password, $nome, $telefone, $nif, $morada, $codigo, $role, $grupo, $created_at]);
    // Output message
    $msg = 'Criação Concluida!'; ?>
    <meta http-equiv="refresh" content="0.5;url=func.php">
<?php }
?>
<?= template_header('Criar Funcionário') ?>
    <div class="content update">
    <h2 id="lbl" style="color: #4a536e;">Criar Funcionário</h2>
    <?php
    if ($contacts['COUNT(*)'] >= 3) {
        exit('Atingiu o limite de funcionários, contacte o administrador');
} ?>
    <form action="create_fun.php" method="post">
        <label for="nome" id="lbl_nome">Nome</label>
        <input type="text" name="n_fun" placeholder="Número funcionário" value="auto" id="n_fun" autocomplete="off" readonly hidden>
        <input type="text" name="nome" placeholder="Nome funcionário" id="nome" autocomplete="off" style="margin-left: -425px; margin-top: 40px;">
        <label for="email" id="lbl_email">Email</label>
        <label for="telefone" id="lbl_telefone">Telefone</label>
        <input type="text" name="email" placeholder="Email funcionário" id="email" autocomplete="off" style="width: 400px; height: 43px; margin-top: -58px;">
        <input type="text" name="telefone" placeholder="Contacto funcionário" id="telefone" autocomplete="off" style="width: 400px; height: 43px;">
        <input type="datetime-local" name="created_at" value="<?= date('Y-m-d\TH:i') ?>" id="created_at" hidden>
        <label for="grupo" id="lbl_grupo" style="margin-top: -40px;">Grupo</label>
        <select name="grupo" placeholder="Escolha grupo" id="grupo" style="border: 1px solid #000000; width: 150px; height: 43px; margin-left: -425px; " autocomplete="off">
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
        </select>
        <input type="submit" value="Criar Funcionário" id="btn" style="margin-left: -575px; margin-top: 70px;">
    </form>
    <?php if ($msg) : ?>
        <p><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>