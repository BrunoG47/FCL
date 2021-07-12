<?php
require 'config.php';
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true ){
        if ($_SESSION["role"] == 'U') {
	header('Location: home.php');
	exit;
}
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] == 'A') {
	header('Location: admin.php');
	exit;
}
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] == 'G') {
	header('Location: empresa.php');
	exit;
}
}
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] == 'F') {
	header('Location: empresa.php');
	exit;
}
}
// Define variables and initialize with empty values
$email = $password = $role = $redirect = "";
$email_err = $password_err = $login_err = $role_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if Email is empty
    // Define email error message
    if (empty(trim($_POST["email"]))) {
        $email_err = "Introduza email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    // Define password error message
    if (empty(trim($_POST["password"]))) {
        $password_err = "Introduza palavra-passe.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT n_cliente, email, password, role FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $n_cliente, $email, $hashed_password, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["email"] = $email;
                            $_SESSION["n_cliente"] = $n_cliente;
                            $_SESSION["role"] = $role;

                            //redirect to especific website
                            // U=simple site
                            // A=admin site
                            // R=revendedor site
                            if ($role == 'U') {
                                $redirect = 'home.php';
                            } else if ($role == 'A') {
                                $redirect = 'admin.php';
                            } else if ($role == 'G') {
                                $redirect = 'empresa.php';
                            } else if ($role == 'F') {
                                $redirect = 'empresa.php';
                            }
                            header('Location: ' . $redirect);
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Password inválida.";
                        }
                    }
                } else {
                    // Email doesn't exist, display a generic error message
                    $login_err = "Email não existe.";
                }
            } else {
                echo "Oops! Algo correu mal, tente novamente mais tarde.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="permissions.js"></script>
    <style>
        body {
            background-color: #435165;
        }
    </style>
</head>

<body>
    <div class="login">
        <h2>Entrar</h2>
        <p>Introduza as suas credenciais para entrar.</p>
        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-user"></i>
                </label>
                <input type="email" name="email" class="form-control email" placeholder="Email" <?php echo (!empty($email_err)) ? ':invalid' : ''; ?> value="<?php echo $email; ?>">
                <?php
                if (!empty($email_err)) {
                    echo '<div class="alert alert-danger">' . $email_err . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" class="form-control" placeholder="Palavra-passe" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>>
                <?php
                if (!empty($password_err)) {
                    echo '<div class="alert alert-danger">' . $password_err . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Entrar">
            </div>
            <p></p>
        </form>
    </div>
</body>

</html>