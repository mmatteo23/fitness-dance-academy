<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Scheda.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modelloScheda = new Scheda();
$modelloUtente = new Utente();

preventMaliciousCode($_GET);
$schede = $modelloScheda->index($_GET);

/*************************************************************************
 * 
 *                          CODICE HTML UTILE
 * 
 ************************************************************************/
$table_footer = "
    </tbody>
</table>
";
$trainers = "";

$response = "";
$btnPrenota = '<input type="submit" value="Prenota" name="prenota" class="button button-purple"/>';
$form = '<div id="trainerSchedaDiv">
            <label for="trainerScheda"><span xml:lang="en" lang="en">Trainer</span> al quale richiedere la scheda: </label>
            <select id="trainerScheda" name="trainerScheda">
                <listaTrainer/>
            </select>
            <input type="submit" value="Prenota" name="prenota" class="button button-purple"/>
        </div>';
$msg_pendente = "";

if(isset($_SESSION['userId']) && Utente::isCliente($_SESSION['userId'])) {
    $prenotazione_pendente = Scheda::prenotazionePendente($_SESSION['userId']);
    if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
        preventMaliciousCode($_POST);
        $_POST['cliente'] = $_SESSION['userId'];
        
        if(!$prenotazione_pendente){
            $returned = Scheda::creaPrenotazione($_POST);
            if($returned !== false){
                $response = "<p class='response success' id='feedbackResponse' autofocus='autofocus' role='alert'>Prenotazione effettuata con successo</p>";
                $prenotazione_pendente = true;
            } else 
                $response = "<p class='response danger' id='feedbackResponse' autofocus='autofocus' role='alert'>Errore durante la richiesta di prenotazione. Si prega di riprovare o contattare l'assistenza.</p>";  
        } else {
            $response = "<p class='response danger' id='feedbackResponse' autofocus='autofocus' role='alert'>Ti sei già prenotato per ricevere una scheda. Puoi prenotarti per una sola scheda alla volta</p>";
        }
    }
    
    $schedeUtente = Scheda::getSchedeByUtente($_SESSION['userId']);
    $trainersArr = $modelloUtente->getTrainers();
    foreach($trainersArr as $trainerSingolo){
        $trainers .= "<option value='".$trainerSingolo['id']."' >".$trainerSingolo['nome']." ".$trainerSingolo["cognome"]."</option>";
    }
    
    if(count($schedeUtente) > 0){   // l'utente ha qualche scheda => gliela mostro
        $content = "
            <table id='tabPrenotate' class='full-button'>
                <thead>
                    <tr>
                        <th scope='col'><span xml:lang='en' lang='en'>Personal Trainer</span></th>
                        <th scope='col'>Data</th>
                        <th scope='col'>Azioni</th>
                    </tr>
                </thead>
                <tbody>
            ";
        foreach($schedeUtente as $scheda){
            $data = explode('-', $scheda['data']);
            $data = $data[2]."/".$data[1];
            $content .= "
                <tr>
                    <th data-title='Trainer' scope='row'>".$scheda['trainer']."</th>
                    <td data-title='Data'>".$scheda['data']."</td>
                    <td><a class='button button-purple' aria-label='Visualizza la scheda' href='visualizzaScheda.php?id=".$scheda['id']."'>Visualizza</a></td>
                </tr>";
        }
        $content .= $table_footer;
    } else {    // L'utente non ha nemmeno una scheda compilata

        if($prenotazione_pendente){
            $content = '<p>La tua richiesta di ricevere una nuova scheda è stata presa in carico, la visualizzerai qui quando il <span xml:lang="en" lang="en">trainer</span> l\'avr&agrave; compilata</p>';
        } else {         
            $content = "<p>Non sono presenti schede per questo utente</p>";
        }
    }

    if($prenotazione_pendente){
        $btnPrenota = '<input type="submit" value="Prenota" name="prenota" class="button button-purple" disabled="disabled"/>';
        $msg_pendente = '<p class="standard-text">Abbiamo notificato il tuo <span xml:lang="en" lang="en">Personal Trainer</span> della richiesta di una nuova scheda.</p>';
    }
        
} else {
    header("location: ../login.php");
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_scheda.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer2.html");

$htmlPage = str_replace('<response/>', $response, $htmlPage);
$htmlPage = str_replace('<msgPendente/>', $msg_pendente, $htmlPage);
$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<formPrenotazione/>", $form, $htmlPage);
$htmlPage = str_replace("<listaTrainer/>", $trainers, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace('<input type="submit" value="Prenota" name="prenota" class="button button-purple"/>', $btnPrenota, $htmlPage);

echo $htmlPage;

?>