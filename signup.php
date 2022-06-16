<?php

session_start();

require_once "config.php";
require_once("php/Models/Utente.php");
require_once("php/utilities.php");

$modelloUtente = new Utente;
$valid = "";

if(isset($_SESSION['email'])){    // the user is already registered
    header("location: areaprivata/profilo.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
    if(Utente::getIdFromEmail($_POST['email']) === false){
        $newId = $modelloUtente->getNewId();
        $response = checkAndUploadImage("img/fotoProfilo/", "profile-img", $newId, "default.png");
        if($response[1] == "") {
            $valid = $modelloUtente->validator($_POST);
            if($valid === TRUE){
                $_POST['id'] = $newId;
                $_POST['foto_profilo'] = $response[0];
                $_POST['ruolo'] = 3;
                if(!$modelloUtente->create($_POST)){
                    header("location: error.php");
                }
                else{
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['userId'] = $newId;
                    header("location: areaprivata/profilo.php");
                }
            } 
        } else {
            $valid .= $response[1];
        }
    } else {
        $valid = "<p class='response danger' id='feedbackResponse' autofocus='autofocus' role='alert'>Un utente con questa <span xml:lang='en'>email</span> è già registrato.</p>";
    }
}


$htmlPage = file_get_contents("html/signup.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<formErrors/>", $valid, $htmlPage);

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>