<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
if ($_SESSION["role"] == 'G') {
    header('Location: empresa.php');
    exit;
}
if ($_SESSION["role"] == 'F') {
    header('Location: empresa.php');
    exit;
}
include 'config.php';
if (mysqli_connect_errno()) {
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $link->prepare('SELECT password, email, nome, morada, codigo, nif, telefone, n_cliente FROM users WHERE n_cliente = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['n_cliente']);
$stmt->execute();
$stmt->bind_result($password, $email, $nome, $morada, $codigo, $nif, $telefone, $n_cliente);
$stmt->fetch();
$stmt->close();
$_SESSION['i'] = $_SESSION['n_cliente'];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Perfil</title>
  <link href="style1.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="loggedin">
  <nav class="navtop">
    <div>
      <a href="home.php"><i style="padding: 0" class="fas"></i>Página Inicial</a>
      <a href="profile.php"><i style="margin-left: 600px" class="fas fa-user-circle"></i>Perfil</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Desconectar</a>
    </div>
  </nav>
  <form class="content" action="mail.php" method="post">
    <h2>Perfil</h2>
    <div>
      <p>Detalhes da conta:</p>
      <table>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Nome:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $nome ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Nº Cliente:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $n_cliente ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Morada:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $morada ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Código Postal:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $codigo ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Telefone:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $telefone ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">NIF:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $nif ?></td>
        </tr>
        <tr>
          <td style="display: inline-block; margin-top: -450px;">Email:</td>
          <td style="display: inline-block; margin-top: -450px;"><?= $_SESSION['email'] ?></td>
        </tr>
        <form class="content" action="mail.php" method="post">
        <h4 style="margin-left: 650px; color: #4a536e;">Se desejar contactar-nos, envie-nos uma mensagem</h4>
        <input type="text" name="nome" value="<?= $nome ?>" hidden>
        <input type="email" name="email" value="<?= $email ?>" hidden>
        <input type="tel" name="telefone" pattern="[0-9]{9}" value="<?= $telefone ?>" hidden>
        <h6 style="margin-left: 550px; display: flex; margin-top: 55px; color: #4a536e;">Mensagem</h6>
        <textarea name="message" placeholder="Escreva a sua mensagem" style="margin-left: 650px; height:100px; width:300px; margin-top: -57px; margin-bottom: 55px;"></textarea>
        <input type="submit" value="Enviar" style="display: flex; margin-left: 900px; margin-top: -40px;">
</form>
      </table>
      <a href="reset-password.php?n_cliente=<?= $_SESSION['n_cliente'] ?>" style="display: flex; text-align: center;
				background-color: #bec5d1;
				border: 2px solid black;
				padding: 2px 4px 2px 4px;
				width: 180px;
				margin-top: -200px;
				   font-size: 16px;
  color: #4a536e;
  font-weight: bold;
  font-family: -apple-system, BlinkMacSystemFont, " segoe ui", roboto, oxygen, ubuntu, cantarell, "fira sans" , "droid sans" , "helvetica neue" , Arial, sans-serif;"><i>Alterar Palavra-Passe</i></a>
    </div>
  </form>
</body>

</html>