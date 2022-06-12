<?php

session_start();

require_once "config.php";
require_once("php/Models/Utente.php");

$errors = "";

if(isset($_SESSION['email'])){    // the user is already registered
    header("location: areaprivata/profile.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
    $errors = Utente::validator($_POST);
    if($errors === TRUE){
        $_POST['foto_profilo'] = "default.png";
        $_POST['alt_foto_profilo'] = "Foto profilo di default";
        $_POST['ruolo'] = 3;
        if(!Utente::create($_POST)){
            $errors = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
        }
        else{
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['userId'] = Utente::getIdFromEmail($_POST['email']);
            header("location: areaprivata/profile.php");
        }
    }
}


$htmlPage = file_get_contents("html/signup.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<formErrors/>", $errors, $htmlPage);

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>