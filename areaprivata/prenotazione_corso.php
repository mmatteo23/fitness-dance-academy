<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");

$content_corsi = "";
$content_corsi_prenotati = "";
$html_table = "<table class='table-prenotazione'>
    <thead>
        <tr>
            <td>Titolo</td>
            <td>Data Inizio</td>
            <td>Data Fine</td>
            <td><span xml:lang='en'>Trainer</span></td>
            <td>Prenotati</td>
        </tr>
    </thead>
    <tbody>";
$html_table_footer = "</tbody></table>";

$modello = new Corso;

$corsi = $modello->index($_GET);

$corsi_prenotati = $modello->getCorsiByUserId($_SESSION['userId']);

if(count($corsi)){
    $content_corsi = $html_table;

    foreach($corsi as $corso){
        $content_corsi .= "<tr>
            <td>". $corso['titolo'] ."</td>
            <td>". $corso['data_inizio'] ."</td>
            <td>". $corso['data_fine'] ."</td>
            <td>". $corso['trainer_nome'] ."</td>
            <td>". $corso['id'] ."</td>
        </tr>";
    }

    $content_corsi .= $html_table_footer;
} else {
    $content_corsi = "<p>Non ci sono corsi registrati</p>";
}

if(count($corsi_prenotati)){
    $content_corsi_prenotati = $html_table;

    foreach($corsi_prenotati as $corso){
        $content_corsi_prenotati .= "<tr>
            <td>". $corso['titolo'] ."</td>
            <td>". $corso['data_inizio'] ."</td>
            <td>". $corso['data_fine'] ."</td>
            <td>". $corso['trainer_nome'] ."</td>
            <td>". $corso['id'] ."</td>
        </tr>";
    }

    $content_corsi_prenotati .= $html_table_footer;
} else {
    $content_corsi_prenotati = "<p>Non ti sei prenotato a nessun corso</p>";
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_corso.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<tabellaElencoCorsi/>", $content_corsi, $htmlPage);
$htmlPage = str_replace("<tabellaCorsiPrenotati/>", $content_corsi_prenotati, $htmlPage);

echo $htmlPage;

?>