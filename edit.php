<?php
include('config.php');
if (isset($_REQUEST["n_cliente"])) {
    $n_cliente = $_REQUEST["n_cliente"];
    $query = mysqli_query($sucess, "SELECT estado from fichas where n_cliente = ?");
    $stmt->bind_param('i', $_SESSION['n_cliente']);
    $stmt->execute();
    $stmt->bind_result($password, $email, $nome, $morada, $codigo, $nif, $telefone, $n_cliente);
    $stmt->fetch();
    $stmt->close();
    $_SESSION['i'] = $_SESSION['n_cliente'];
    $row = mysqli_fetch_array($query);
}

if (isset($_REQUEST["submit"])) {

    $estado = $_REQUEST["estado"];
    $sql = mysqli_query($sucess1, "UPDATE estado = '$estado' where id='$eid'");
    header('location:select.php');
}
?>
<form method="post">
    <table border="1" align="center">

        <tr>
            <td>Estado:</td>
            <td>
                <select name="estado">
                    <option value="">Select Any One</option>
                    <option value="Em diagnóstico" <?php
                                            if ($row["estado"] == 'Em diagnóstico') {
                                                echo "selected";
                                            }
                                            ?>>Em diagnóstico</option>
                    <option value="Concluido" <?php
                                                if ($row["estado"] == 'concluido') {
                                                    echo "selected";
                                                }
                                                ?>>Concluido</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="submit" name="submit"></td>
        </tr>
    </table>
</form>