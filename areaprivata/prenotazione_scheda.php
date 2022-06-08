<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . "/php/Models/Scheda.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

// get all corsi from db
$modello = new Scheda();

$schede = $modello->index($_GET);

$content = "
<table id='tabPrenotate'>
    <thead>
        <tr>
            <th scope='col'>Data</th>
            <th scope='col'>Personal Trainer</th>
            <th scope='col'>Visualizza</th>
        </tr>
    </thead>
    <tbody>
";
$table_footer = "
    </tbody>
</table>
";
$trainers = "";

$messaggi = "";
$btnPrenota = '<input type="submit" value="Prenota" name="prenota" class="button button-purple"/>';
$form = '
    <form action="../areaprivata/prenotazione_scheda.php" method="post" id="formSessione">
        <h2>Prenota una scheda con uno dei nostri Personal Trainer</h2> 
        <div id="messaggi"></div>
        <div id="trainerSchedaDiv">
            <label for="trainerScheda">Trainer al quale richiedere la scheda: </label>
            <select id="trainerScheda" name="trainerScheda">
                <listaTrainer/>
            </select>
            <input type="submit" value="Prenota" name="prenota" class="button button-purple"/>
        </div>
    </form>';

if(isset($_SESSION['userId'])){
    if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto

        $_POST['cliente'] = $_SESSION['userId'];
    
        $returned = Scheda::creaPrenotazione($_POST);
        if($returned !== false)
            $messaggi = "<p>Prenotazione effettuata con successo <i class='fa fa-check'></i></p>";
    }

    if(Utente::getRole($_SESSION['userId'])==1){
        if(Scheda::prenotazionePendente($_SESSION['userId'])){
            $btnPrenota = '<p>Questo utente ha già una richiesta pendente</p><input type="submit" value="Prenota" name="prenota" class="button button-purple" disabled="disabled"/>';
        }

        $schedeUtente = Scheda::getSchedeByUtente($_SESSION['userId']);
        $trainersArr = Utente::getTrainers();
        foreach($trainersArr as $trainerSingolo){
            $trainers .= "<option value='".$trainerSingolo['id']."'>".$trainerSingolo['nome']." ".$trainerSingolo["cognome"]."</option>";
        }
        
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
            $content .= $table_footer;
        } else {
            $content = "<p>Non sono presenti schede per questo utente</p>";
        }
    }
    else{
        $form = "";
        $content = "
        <table id='tabPrenotate'>
            <thead>
                <tr>
                    <th scope='col'>id</th>
                    <th scope='col'>Utente</th>
                    <th scope='col'>Elimina</th>
                </tr>
            </thead>
            <tbody>
        ";
        $schedeRichieste = Scheda::getSchedeRichieste($_SESSION['userId']);
        if(count($schedeRichieste) > 0){
            foreach($schedeRichieste as $scheda){
                $content .= "
                    <tr>
                        <td>".$scheda['id']."</td>
                        <td>".$scheda['utente']."</td>
                        <td><button id='delete-scheda' class='button button-purple'><i class=' fa fa-window-close'></i></i></button></td>
                    </tr>";
            }
            $content .= $table_footer;
        }
        else{
            $content = "<p>Non sono presenti schede richieste</p>";
        }
    }
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_scheda.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

$htmlPage = str_replace('<input type="submit" value="Prenota" name="prenota" class="button button-purple"/>', $btnPrenota, $htmlPage);
$htmlPage = str_replace('<div id="messaggi"></div>', $messaggi, $htmlPage);
$htmlPage = str_replace("<listaTrainer/>", $trainers, $htmlPage);
$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<formPrenotazione/>", $form, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>