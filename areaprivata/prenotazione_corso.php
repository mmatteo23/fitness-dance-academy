<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modello = new Corso;
$modelloUtente = new Utente;

if(!isset($_SESSION['userId']) || !$modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

// html pieces
$content_corsi = "";
$content_corsi_prenotati = "";
$html_table = "
    <table class='table-prenotazione'>
        <thead>
            <tr>
                <th scope='col'>Titolo</th>
                <th scope='col'>Data Inizio</th>
                <th scope='col'>Data Fine</th>
                <th scope='col'><span xml:lang='en'>Trainer</span></th>
                <th scope='col'>Prenotati</th>
            </tr>
        </thead>
        <tbody>";
$html_table_footer = "</tbody></table>";
$response = "";

if($_SERVER['REQUEST_METHOD'] === "POST"){

    // Check if there is an insert or a delete
    if(isset($_POST['insert'])){
        if(!$modello->isAlreadyRegistered($_POST['insert'], $_SESSION['userId'])){
            $result = $modello->registerUser($_POST['insert'], $_SESSION['userId']);
            if($result)
                $response = "<p class='response success' autofocus>Registrazione avvenuta con successo.</p>";
            else
                $response = "<p class='response danger' autofocus>Errore durante la registrazione. Si prega di riprovare o contattare l'assistenza.</p>";
        } else {
            $response = "<p class='response danger' autofocus>Cosa pensi di fare? Ti sei già registrato.</p>";
        }
    }

    if(isset($_POST['delete'])){
        if($modello->isAlreadyRegistered($_POST['delete'], $_SESSION['userId'])){
            $result = $modello->unregisterUser($_POST['delete'], $_SESSION['userId']);
            if($result)
                $response = "<p class='response success' autofocus>Ti sei disiscritto dal corso.</p>";
            else
                $response = "<p class='response danger' autofocus>Errore durante la registrazione. Si prega di riprovare o contattare l'assistenza.</p>";
        } else {
            $response = "<p class='response danger' autofocus>Cosa pensi di fare? Non puoi disiscriverti da un corso a cui non sei iscritto.</p>";
        }
    }

}

$corsi = $modello->getUnregisteredCorsiByUserId($_GET, $_SESSION['userId']);

$corsi_prenotati = $modello->getCorsiByUserId($_SESSION['userId']);

if(count($corsi)){
    $content_corsi = $html_table;

    foreach($corsi as $corso){
        $content_corsi .= "<tr>
            <th scope='row'>". $corso['titolo'] ."</th>
            <td data-title='Data Inizio'>". explode(' ', $corso['data_inizio'])[0] ."</td>
            <td data-title='Data Fine'>". explode(' ',$corso['data_fine'])[0] ."</td>
            <td data-title='Trainer'>". $corso['trainer_nome'] ."</td>
            <td>
                <button type='submit' name='insert' value=" . $corso['id'] . " class='button button-purple button-filter'>Prenota</button>
            </td>
            
        </tr>";
    }

    $content_corsi .= $html_table_footer;
} else {
    $content_corsi = "<p>Non ci sono corsi che combaciano con i tuoi parametri di ricerca</p>";
}

if(count($corsi_prenotati)){
    $content_corsi_prenotati = $html_table;

    foreach($corsi_prenotati as $corso){
        $content_corsi_prenotati .= "<tr>
            <th scope='row'>". $corso['titolo'] ."</th>
            <td data-title='Data Inizio'>". explode(' ', $corso['data_inizio'])[0] ."</td>
            <td data-title='Data Fine'>". explode(' ', $corso['data_fine'])[0] ."</td>
            <td data-title='Trainer'>". $corso['trainer_nome'] ."</td>
            <td>
                <button type='submit' name='delete' value=" . $corso['id'] . " class='button button-purple button-filter'>Disiscriviti</button>
            </td>
        </tr>";
    }

    $content_corsi_prenotati .= $html_table_footer;
} else {
    $content_corsi_prenotati = "<p>Non ti sei prenotato a nessun corso</p>";
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/prenotazione_corso.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<response/>", $response, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<tabellaElencoCorsi/>", $content_corsi, $htmlPage);
$htmlPage = str_replace("<tabellaCorsiPrenotati/>", $content_corsi_prenotati, $htmlPage);

echo $htmlPage;

?>