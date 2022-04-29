<?php

require_once("php/Models/Corso.php");

// get all corsi from db
$modello = new Corso();

$corsi = $modello->index($_GET);

$htmlCorsi = "";
if(count($corsi) > 0){
    foreach($corsi as $corso){
        $htmlCorsi .= "<div class='corso-card'>
            <img class='img-card' src='" . ($corso['copertina']?:'img/spinningQuadrato.jpg') . "'/>
            <p class='title-card'>" . $corso['titolo'] . "<span class='little-title'><span xml:lang='en'> by</span> " . $corso['trainer_nome'] . "</span></p>
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