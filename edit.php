<?php
include "functions.php";    // Using database connection file here
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_GET['n_ficha'])) {
    if (!empty($_POST)) {
        $n_ficha = isset($_POST['n_ficha']) ? $_POST['n_ficha'] : NULL;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $nota = isset($_POST['nota']) ? $_POST['nota'] : '';
        $stmt = $pdo->prepare 
    }
    $n_ficha = $_GET['n_ficha']; // get id through query string
}
$qry = mysqli_query($link, "SELECT estado FROM fichas WHERE n_ficha = '$n_ficha'"); // select query
$dat = mysqli_fetch_array($qry); // fetch data

if (isset($_POST['update'])) // when click on Update button
{
    $nota = $_POST['Nota'];
    $estado = $_POST['Estado'];
    $edit = mysqli_query($link, "UPDATE fichas set estado='$estado', nota='$nota' where n_ficha = '$n_ficha'");
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
    <title>SosToners-Editar Ficha</title>
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
        <select name="Estado" method="post" placeholder="Insira estado" id="selectBoxId" Required>
            <option value="Para diagnóstico">Para Diagnóstico</option>
            <option value="Em Diagnóstico">Em Diagnóstico</option>
            <option value="Em testes">Em testes</option>
            <option value="Aguarda aprovação">Aguarda aprovação</option>
            <option value="Aguarda peças">Aguarda peças</option>
            <option value="Em laboratório">Em laboratório</option>
            <option value="Em reparação">Em reparação</option>
            <option value="Em controlo">Em controlo</option>
            <option value="Pronto para entrega">Pronto para entrega</option>
            <option value="Entregue">Entregue</option>
            <option value="Sem reparação">Sem reparação</option>
            <option value="Para devolução">Para devolução</option>
        </select>
        <input type="text" name="Nota" method="post">
        <input type="submit" name="update" value="Editar">
    </form>