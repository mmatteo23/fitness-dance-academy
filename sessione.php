<?php

session_start();
require_once('php/models/Sessione.php');

$htmlPage = file_get_contents("html/sessione.html");

$errors = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto

    $_POST['cliente'] = $_SESSION['userId'];
    print_r($_POST);

    $errors = Sessione::validator($_POST);

    if ($errors != ""){
        $htmlPage = str_replace("<div id='errori'></div>", $errors, $htmlPage);
    } else {
        $returned = Sessione::create($_POST);
    }   
}

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>