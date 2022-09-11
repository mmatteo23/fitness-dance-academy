<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Sessione.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modelloSessione = new Sessione();
$modelloUtente = new Utente();

if(!isset($_SESSION['userId']) || !$modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: ../login.php");
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_sessione.html");

$errors = "";
$response = "";

$content_corsi_prenotati = "";
$tabellaSessioniPrenotate = "
    <div id='private-area-table'>
        <h1>Le sessioni che hai già prenotato</h1>
        <headTabellaSessioni/>
    </div>
";
$headTabellaSessioni = "
    <table id='tabPrenotate' class='full-button'>
        <thead>
            <tr>
                <th scope='col'>Data</th>
                <th scope='col'>Dalle</th>
                <th scope='col'>Alle</th>
                <th scope='col'>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <sessionTableBody/>
        </tbody>
    </table>
";

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto

    preventMaliciousCode($_POST);
    $_POST['cliente'] = $_SESSION['userId'];

    $errors = Sessione::validator($_POST);

    if ($errors == ""){
        $returned = Sessione::create($_POST);
        if($returned !== false)
            $response = "<p class='response success' id='feedbackResponse' autofocus='autofocus' role='alert'>Prenotazione effettuata con successo per la sessione scelta</p>";
        else
            $response = "<p class='response danger' id='feedbackResponse' autofocus='autofocus' role='alert'>Errore durante la richiesta di prenotazione. Si prega di riprovare o contattare l'assistenza.</p>";
    } else {
        $errors = "<div id='errori'>".$errors."</div>";
    }  
}

$tabellaSess_content = "";
if(isset($_SESSION['userId']) && $_SESSION['userId']!=''){
    $sessioniPrenot = $modelloSessione->getSessionsOf($_SESSION['userId']);
    if(count($sessioniPrenot)){
        foreach($sessioniPrenot as $sess){
            $data = explode('-', $sess['data']);
            $data = $data[2]."/".$data[1];
            $oraI = substr($sess['ora_inizio'], 0, 5);
            $oraF = substr($sess['ora_fine'], 0, 5);;

            //<button onclick = 'deleteSession(".$sess['id'].")' id='btn-cancella'>Cancella</button>
            $tabellaSess_content .= "
                <tr id='sess".$sess['id']."'>
                    <th data-title='Data'>".$data."</th>
                    <td data-title='Dalle'>".$oraI."</td>
                    <td data-title='Alle'>".$oraF."</td>
                    <td>
                        <button onclick='deleteSession(".$sess['id'].")' class='button button-purple' id='btn-conferma'>Conferma</button>
                        <button onclick='hideModal(".$sess['id'].")' class='button button-violet' id='btn-annulla'>Annulla</button>
                        <button onclick = 'showModal(".$sess['id'].")' class='button button-purple' id='btn-cancella'>Cancella</button>
                    </td>
                </tr>";
        }
    } else {
        $headTabellaSessioni = "<p>Non ti sei prenotato a nessuna sessione</p>";
    }
}
$footer = file_get_contents(SITE_ROOT . "/html/components/footer2.html");

$htmlPage = str_replace('<errori/>', $errors, $htmlPage);
$htmlPage = str_replace('<response/>', $response, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
//$htmlPage = str_replace("<giornoSessione/>", $giornoHTML, $htmlPage);
$htmlPage = str_replace("<tabellaSessioniPrenotate/>", $tabellaSessioniPrenotate, $htmlPage);
$htmlPage = str_replace("<headTabellaSessioni/>", $headTabellaSessioni, $htmlPage);
$htmlPage = str_replace("<sessionTableBody/>", $tabellaSess_content, $htmlPage);

echo $htmlPage;

?>