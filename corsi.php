<?php

require_once("php/Models/Corso.php");

// get all corsi from db
$modello = new Corso();

$corsi = $modello->index($_GET);

$htmlPage = file_get_contents("html/corsi.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>