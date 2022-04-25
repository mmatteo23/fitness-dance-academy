<?php

require_once("php/Models/Scheda.php");

// get all corsi from db
$modello = new Scheda();

$schede = $modello->index($_GET);

$htmlPage = file_get_contents("html/schede.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>