<?php

include "config.php"; // Using database connection file here
if (isset($_GET['n_ficha'])) {
    $n_ficha = $_GET['n_ficha']; // get id through query string
}
$qry = mysqli_query($link, "SELECT estado FROM fichas WHERE n_ficha = '$n_ficha'"); // select query

$dat = mysqli_fetch_array($qry); // fetch data

if (isset($_POST['update'])) // when click on Update button
{
    $estado = $_POST['Estado'];

    $edit = mysqli_query($link, "UPDATE fichas set estado='$estado' where n_ficha = '$n_ficha'");

    if ($edit) {
        mysqli_close($link); // Close connection
        header("location:admin.php"); // redirects to all records page
        exit;
    } else {
        echo mysqli_error($error);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Admin</title>
	<link href="style1.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<h2 style="color: mintcream; margin-top:7px">Editar dados</a>
		</div>
	</nav>
<form method="POST" style="margin-top: 2rem; margin-left: 14rem">
    <input type="text" name="Estado" method="post" value="<?php echo $dat['estado'] ?>" placeholder="Edite estado" Required>
    <input type="submit" name="update" value="Editar">
</form>