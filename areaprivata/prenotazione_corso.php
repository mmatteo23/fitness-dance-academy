<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");

$content = "";

$modello = new Corso;

$corsi = $modello->index($_GET);

if(count($corsi)){
    foreach($corsi as $corso){
        $content .= "<tr>
            <td>". $corso['titolo'] ."</td>
            <td>". $corso['data_inizio'] ."</td>
            <td>". $corso['data_fine'] ."</td>
            <td>". $corso['trainer_nome'] ."</td>
            <td>". $corso['id'] ."</td>
        </tr>";
    }
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_corso.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<elencoCorsi/>", $content, $htmlPage);

echo $htmlPage;

?>