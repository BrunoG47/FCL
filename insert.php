<?php
require 'config.php';
session_start();    
$_SESSION['id'] = $_SESSION['i'];
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "test");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Escape user inputs for security
$nome = mysqli_real_escape_string($link, $_REQUEST['nome']);
$morada = mysqli_real_escape_string($link, $_REQUEST['morada']);
$codigo = mysqli_real_escape_string($link, $_REQUEST['codigo']);
$i = $_SESSION['i'];

// Attempt insert query execution
$sql = "UPDATE users SET nome='$nome', morada='$morada', codigo='$codigo' WHERE id = '$i' ";
if(mysqli_query($link, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
    <title>Document</title>
</head>
<body>
<div>
<form>
<input type="text" size="3" id="timer" name="redirect2" value="1">
</form>
</div>
<script>
var targetURL="home.php"
var currentsecond=1;
function countredirect(){
if (currentsecond!=1){
currentsecond-=1
document.getElementById('timer').value=currentsecond;
}
else{
window.location=targetURL;
return
}
setTimeout("countredirect()",1000)
}
countredirect();
//-->
</script>
</body>
</html>