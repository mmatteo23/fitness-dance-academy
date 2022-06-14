<?php

require_once "config.php";

require_once(SITE_ROOT . "/php/Models/Corso.php");

// get all corsi from db
$modello = new Corso();

$corsi = $modello->index($_GET);

$htmlCorsi = "";
$modalCorsi = "";

if(count($corsi) > 0){
    foreach($corsi as $corso){
        $htmlCorsi .= "<div class='corso-card'>
            <img class='img-card' src='img/corsi/" . ($corso['copertina']?:'default.jpg') . "' alt='".$corso['alt_copertina']."'/>
            <p class='title-card'>" . $corso['titolo'] . "<span class='little-title'><span xml:lang='en' lang='en'> by</span> " . $corso['trainer_nome'] . " " . $corso['trainer_cognome'] . "</span></p>
            <p class='descrizione-card'>" . $corso['descrizione'] . "</p>
            <a href='areaprivata/prenotazione_corso.php' class='button button-purple'>Prenota</a>
        </div>";
    }
} else {
    $htmlCorsi = "<p>Non sono presenti corsi</p>";
}

$htmlPage = file_get_contents("html/corsi.html");
$footer = file_get_contents("html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<elencoCorsi/>", $htmlCorsi, $htmlPage);

echo $htmlPage;

?>