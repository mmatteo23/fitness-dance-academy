<?php

session_start();

require_once("php/Models/Utente.php");

$errors = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
    $errors = Utente::validator($_POST);
    if($errors === TRUE){
        $_POST['foto_profilo'] = "default.png";
        $_POST['ruolo'] = 1;
        if(!Utente::create($_POST)){
            echo "non ce l'ho fatta";
        }
        else{
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['userId'] = Utente::getIdFromEmail($_POST['email']);
            header("location: profile.php");
        }
    }
}


$htmlPage = file_get_contents("html/creaProfilo.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<formErrors/>", $errors, $htmlPage);

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>