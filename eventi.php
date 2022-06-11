<?php

require_once("php/Models/Evento.php");

// get all corsi from db
$modello = new Evento();

$eventi = $modello->index($_GET);

$htmlPage = file_get_contents("html/eventi.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>