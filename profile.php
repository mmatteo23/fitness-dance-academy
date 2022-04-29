<?php

require_once("php/Models/Utente.php");
require_once("php/validSession.php");

echo "Belllaaaaaaaa ".$_SESSION['email']."\n";

$modello = new Utente();

$utente = $modello->read($_SESSION['userId']);

print_r($utente);

$htmlPage = file_get_contents("html/profile.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>