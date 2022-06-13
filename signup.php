<?php

session_start();

require_once "config.php";
require_once("php/Models/Utente.php");
require_once("php/utilities.php");

$modelloUtente = new Utente;
$valid = "";

if(isset($_SESSION['email'])){    // the user is already registered
    header("location: areaprivata/profile.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
    $allUtenti = $modelloUtente->index(array());
    $newId = count($allUtenti) + 1;
    $response = checkAndUploadImage("img/fotoProfilo/", "profile-img", $newId, "default.png");
    if($response[1] == "") {
        $valid = $modelloUtente->validator($_POST);
        if($valid == TRUE){
            $_POST['foto_profilo'] = $response[0];
            $_POST['ruolo'] = 3;
            if(!$modelloUtente->create($_POST)){
                $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
            }
            else{
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['userId'] = $newId;
                header("location: areaprivata/profile.php");
            }
        } 
    } else {
        $valid .= $response[1];
    }
}


$htmlPage = file_get_contents("html/signup.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<formErrors/>", $valid, $htmlPage);

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>