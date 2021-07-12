<?php
$nome = $_POST["nome"];
$email = $_POST["email"];
$telefone = $_POST["telefone"];
$message = $_POST["message"];
//get data from form  
//$to = "geral@sostoners.pt";
$to = "brunoragoncalves@gmail.com";
$subject = "FCL";
$txt ="Nome Cliente= ". $nome . "\r\nTelefone Cliente= " . $telefone . "\r\nEmail Cliente= " . $email . "\r\nMensagem= " . $message;
$headers = "From: geral@tinteiros-toners.pt" . "\r\n" .
"CC: $email";
if($email!=NULL){
    mail($to,$subject,$txt,$headers);
}
//redirect
header("Location:profile.php");
?>