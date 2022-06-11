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
            <img class='img-card' src='img/corsi/" . ($corso['copertina']?:'default.jpg') . "'/>
            <p class='title-card'>" . $corso['titolo'] . "<span class='little-title'><span xml:lang='en'> by</span> " . $corso['trainer_nome'] . "</span></p>
            <p>" . $corso['descrizione'] . "</p>
            <a href='areariservata/prenotazionecorso.php?corso=" . $corso['id'] . "' class='button button-purple'>Prenota</a>
    </div>";
        
        
        /*

        "<div class='flip-card'>
            <div class='flip-card-inner'>
                <div class='flip-card-front'>
                    <img class='img-card' src='" . ($corso['copertina']?:'img/spinningQuadrato.jpg') . "'/>
                    <p class='title-card'>" . $corso['titolo'] . "<span class='little-title'><span xml:lang='en'> by</span> " . $corso['trainer_nome'] . "</span></p> 
                    <button class='flip-btn'>Scopri di più</button>
                </div>
                <div class='flip-card-back'>
                    <div class='flip-card-detail-box'>
                        <p class='title-card'>" . $corso['titolo'] . "</p>
                        <p>" . $corso['descrizione'] . "</p>
                        <p><span class='text-bold'>Allenatore:</span> " . $corso['trainer_nome'] . "</p>
                    </div>
                </div>
            </div>
        </div>";

        
        $modalCorsi .= "<div id='modalCorso". $corso['id'] . "' class='modal'>
            <!-- Modal content -->
            <div class='modal-content'>
                <span class='close' id='close" . $corso['id'] . "'>&times;</span>
                <h2>". $corso['titolo'] . "</h2>
                <p>". $corso['descrizione'] . "</p>
            </div>
        </div>";
        */
    }
} else {
    $htmlCorsi = "<p>Non sono presenti corsi</p>";
}

$htmlPage = file_get_contents("html/corsi.html");
$footer = file_get_contents("html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<elencoCorsi/>", $htmlCorsi, $htmlPage);
//$htmlPage = str_replace("<modalCorsi/>", $modalCorsi, $htmlPage);

echo $htmlPage;

?>