<?php

require_once("php/Models/Scheda.php");

if(isset($_GET['id']))
{
    $schedaId = $_GET['id'];

    $esercizi = Scheda::getEserciziById($schedaId);

    $content = '<ol id="ex-container">';
    $i=0;
    foreach($esercizi as $es){
        $i++;
        $content .= 
            '<li class="ex">
                <img class="ex-img" src="img/iconeEsercizi/'.$es['id'].'.png"/>
                <ul class="ex-descr">
                    <li>'.$i.". ".$es['nome'].'</li>
                    <li>Serie: '.$es['serie'].'</li>
                    <li>Ripetizioni: '.$es['ripetizioni'].'</li>
                    <li>Pausa: '.$es['riposo'].'s</li>
                </ul>
            </li>';
    }
    $content .= "</ol>";

    $htmlPage = file_get_contents("html/visualizzaScheda.html");

    $footer = file_get_contents("html/components/footer.html");

    $htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
    $htmlPage = str_replace("<esercizi/>", $content, $htmlPage);

    echo $htmlPage;
}

?>