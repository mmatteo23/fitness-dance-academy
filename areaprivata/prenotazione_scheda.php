<?php

session_start();

require_once "../config.php";
require_once(SITE_ROOT . "/php/Models/Scheda.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

// get all corsi from db
$modello = new Scheda();

$schede = $modello->index($_GET);

$content = "";
$trainers = "";

if(isset($_SESSION['userId'])){
    $schedeUtente = Scheda::getSchedeByUtente($_SESSION['userId']);
    $trainers = Utente::getTrainers();
    
    if(count($schedeUtente) > 0){
        foreach($schedeUtente as $scheda){
            $data = explode('-', $scheda['data']);
            $data = $data[2]."/".$data[1];
            $content .= "
                <tr>
                    <td>".$data."</td>
                    <td>".$scheda['trainer']."</td>
                    <td><a class='button button-purple' href=visualizzaScheda.php?id=".$scheda['id']."><i class=' fa fa-location-arrow'></i></i></a></td>
                </tr>";
        }
    } else {
        $content = "<p>Non sono presenti schede per questo utente</p>";
    }
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_scheda.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

$htmlPage = str_replace("<listaTrainer/>", $trainers, $htmlPage);
$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>