<?php

session_start();
require_once("php/Models/Scheda.php");

// get all corsi from db
$modello = new Scheda();

$schede = $modello->index($_GET);

$content = "";
$schedeUtente = Scheda::getSchedeById($_SESSION['userId']);

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

$htmlPage = file_get_contents("html/schede.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>