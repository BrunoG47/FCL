<?php

include "config.php"; // Using database connection file here

$n_ficha = $_GET['n_ficha']; // get id through query string

$qry = mysqli_query($link, "SELECT estado FROM fichas WHERE n_ficha='$n_ficha'"); // select query

$data = mysqli_fetch_array($qry); // fetch data

if (isset($_POST['update'])) // when click on Update button
{
    $n_ficha = $_POST['n_ficha'];
    $estado = $_POST['estado'];

    $edit = mysqli_query($link, "UPDATE fichas set estado='$estado' where n_ficha='$n_ficha'");

    if ($edit) {
        mysqli_close($link); // Close connection
        header("location:admin.php"); // redirects to all records page
        exit;
    } else {
        echo mysqli_error($error);
    }
}
?>
<h3>Editar Dados</h3>

<form method="POST">
    <input type="text" name="Estado" value="<?php echo $data['estado'] ?>" placeholder="Edite estado" Required>
    <h3><?php echo $data['n_ficha'] ?></h3>
    <input type="submit" name="update" value="Editar">
</form>