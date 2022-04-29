<?php

session_start();
require_once("php/Models/Scheda.php");

// get all corsi from db
$modello = new Scheda();

$schede = $modello->index($_GET);

$content = "";

if(isset($_SESSION['userId'])){
    $schedeUtente = Scheda::getSchedeByUtente($_SESSION['userId']);
    
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

$htmlPage = file_get_contents("html/schede.html");

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>