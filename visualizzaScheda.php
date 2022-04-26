<?php

require_once("php/Models/Scheda.php");

if(isset($_GET['id']))
{
    $schedaId = $_GET['id'];

    $esercizi = Scheda::getEserciziById($schedaId);

    $content = '<div id="ex-container">';
    foreach($esercizi as $es){
        $content .= 
            '<div class="ex">
                <img class="ex-img" src="img/iconeEsercizi/'.$es['id'].'.png"/>
                <div class="ex-descr">
                    <h3>'.$es['nome'].'</h3>
                    <h3>'.$es['serie'].'<span>X</span>'.$es['ripetizioni'].'</h3>
                </div>
            </div>';
        if(intval($es['riposo'])>0)
            $content .= 
                '<div class="pausa">
                    <img class="ex-img" src="img/iconeEsercizi/pausa.png"/>
                    <div class="ex-descr">
                        <h3>Pausa</h3>
                        <h3>'.$es['riposo'].'s</h3>
                    </div>
                </div>';
    }
    $content .= "</div>";

    $htmlPage = file_get_contents("html/visualizzaScheda.html");

    $footer = file_get_contents("html/components/footer.html");

    $htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
    $htmlPage = str_replace("<esercizi/>", $content, $htmlPage);

    echo $htmlPage;
}

?>