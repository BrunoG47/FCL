<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = $role = $nome = $telefone = $nif = $morada = $codigo = "";
$email_err = $password_err = $confirm_password_err = $nome_err = $telefone_err = $nif_err =  $morada_err = $codigo_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Check if nome is empty
    if (empty(trim($_POST["nome"]))) {
        $nome_err = "Insira nome.";
    } else {
        $nome = trim($_POST["nome"]);
    }
    //Check if telefone is empty
    if (empty(trim($_POST["telefone"]))) {
        $telefone_err = "Insira Telefone.";
    } else {
        $telefone = trim($_POST["telefone"]);
    }
    //Check if nif is empty
    if (empty(trim($_POST["nif"]))) {
        $nif_err = "Insira NIF.";
    } else {
        $nif = trim($_POST["nif"]);
    }
    //Check if nif is empty
    if (empty(trim($_POST["morada"]))) {
        $morada_err = "Insira Morada.";
    } else {
        $morada = trim($_POST["morada"]);
    }
    //Check if nif is empty
    if (empty(trim($_POST["codigo"]))) {
        $codigo_err = "Insira Código Postal.";
    } else {
        $codigo = trim($_POST["codigo"]);
    }
    // Check if Email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Insira email";
    } elseif (empty(trim($_POST["password"]))) {
        $password_err = "Insira palavra-passe.";
    }
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Confirme a palavra-passe.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Prepare a select statement
    $sql = "SELECT id FROM users WHERE email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        // Set parameters
        $param_email = trim($_POST["email"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            /* store result */
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                $email_err = "Email já usado.";
            } else {
                $email = trim($_POST["email"]);
            }
        } else {
            echo "Oops! Algo correu mal. Tente mais tarde.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Insira palavra-passe.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Palavra-passe tem de ter no minimo 6 carateres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Confirme a palavra-passe.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Palavra-passe não combina.";
        }
    }


    // Check input errors before inserting in database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($nome_err) && empty($telefone_err) && empty($morada_err) && empty($codigo_err) && empty($nif_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, role, nome, telefone, morada, codigo, nif) VALUES (?, ? , 'U', ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_email, $param_password, $param_nome, $param_telefone, $param_morada, $param_codigo, $param_nif);
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_nome = $nome;
            $param_telefone = $telefone;
            $param_morada = $morada;
            $param_codigo = $codigo;
            $param_nif = $nif;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to profile page
                header("location: login.php");
            } else {
                echo "Oops! Algo correu mal. Tente mais tarde.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registo</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #435165;
        }
    </style>
</head>

<body>
    <div class="login">
        <h2>Registo</h2>
        <p>Insira os seus dados para criar a sua conta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
            <!--email-->
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-user"></i>
                </label>
                <input type="email" name="email" class="form-control email" placeholder="Email" autocomplete="off" <?php echo (!empty($email_err)) ? ':invalid' : ''; ?> value="<?php echo $email; ?>">
                <?php
                if (!empty($email_err)) {
                    echo '<div class="alert alert-danger">' . $email_err . '</div>';
                }
                ?>
            </div>
            <!--password-->
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" class="form-control" placeholder="Palavra-Passe" autocomplete="off" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($password_err)) {
                    echo '<div class="alert alert-danger">' . $password_err . '</div>';
                }
                ?>
            </div>
            <!--confirm password-->
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirme Passe" autocomplete="off" <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($confirm_password_err)) {
                    echo '<div class="alert alert-danger">' . $confirm_password_err . '</div>';
                }
                ?>
            </div>
            <!--nome-->
            <div class="form-group">
                <label for="nome">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="nome" style="width: 40%; margin-left: 27%; color: #495457;" class="form-control" placeholder="Nome" autocomplete="off" <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($nome_err)) {
                    echo '<div class="alert alert-danger">' . $nome_err . '</div>';
                }
                ?>
            </div>
            <!--telefone-->
            <div class="form-group">
                <label for="telefone">
                    <i class="fas fa-user"></i>
                </label>
                <input type="number" name="telefone" style="width: 40%; margin-left: 27%; color: #495457;" class="form-control" placeholder="Telefone" autocomplete="off" <?php echo (!empty($telefone_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($telefone_err)) {
                    echo '<div class="alert alert-danger">' . $telefone_err . '</div>';
                }
                ?>
            </div>
            <!--nif-->
            <div class="form-group">
                <label for="nif">
                    <i class="fas fa-user"></i>
                </label>
                <input type="number" name="nif" style="width: 40%; margin-left: 27%; color: #495457;" class="form-control" placeholder="NIF" autocomplete="off" <?php echo (!empty($nif_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($nif_err)) {
                    echo '<div class="alert alert-danger">' . $nif_err . '</div>';
                }
                ?>
            </div>
            <!--morada-->
            <div class="form-group">
                <label for="morada">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="morada" style="width: 40%; margin-left: 27%; color: #495457;" class="form-control" placeholder="Morada" autocomplete="off" <?php echo (!empty($morada_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($morada_err)) {
                    echo '<div class="alert alert-danger">' . $morada_err . '</div>';
                }
                ?>
            </div>
            <!--codigo-->
            <div class="form-group">
                <label for="codigo">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="codigo" style="width: 40%; margin-left: 27%; color: #495457;" class="form-control" placeholder="Código Postal" autocomplete="off" <?php echo (!empty($codigo_err)) ? 'is-invalid' : ''; ?>">
                <?php
                if (!empty($codigo_err)) {
                    echo '<div class="alert alert-danger">' . $codigo_err . '</div>';
                }
                ?>
            </div>
            <!--submeter-->
            <div class="form-group">
                <input type="submit" class="submit btn btn-primary reset" value="Submeter">
            </div>
            <p>Já tem conta? <a href="login.php">Entre aqui</a>.</p>
        </form>
    </div>
</body>

</html>