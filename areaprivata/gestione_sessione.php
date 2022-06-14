<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Sessione.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modelloSessione = new Sessione;
$modelloUtente = new Utente;

if(!isset($_SESSION['userId']) || $modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/gestione_sessione.html");

$errors = "";

$prenotazioni_table = "
<table class='table-prenotazione table-prenotazione-e-utenti'>
    <thead>
        <tr>
            <th scope='col'>Ora inizio</td>
            <th scope='col'>Ora fine</td>
            <th scope='col'>Nome</td>
            <th scope='col'>Cognome</td>
            <th scope='col'>Email</td>
            <th scope='col'>Telefono</td>
        </tr>
    </thead>
    <tbody>
";
$html_table_footer = "</tbody></table>";
$content_prenotazioni="";

if($_SERVER['REQUEST_METHOD'] === "GET"){
    preventMaliciousCode($_GET);
    // Se si sta richiedendo un corso specifico con 'view' allora:
    if(isset($_GET['data'])){
        $giorno = $_GET['data'];
        $listaPrenotazioni = $modelloSessione->getSessioniByGiorno($giorno);

        if($listaPrenotazioni){
            $content_prenotazioni = $prenotazioni_table;
        
            foreach($listaPrenotazioni as $prenotazione){
                $content_prenotazioni .= "<tr>
                    <td data-title='Ora inizio'>". $prenotazione['ora_inizio'] ."</td>
                    <td data-title='Ora fine'>". $prenotazione['ora_fine'] ."</td>
                    <td data-title='Nome'>". $prenotazione['nome'] ."</td>
                    <td data-title='Cognome'>". $prenotazione['cognome'] ."</td>
                    <td data-title='Email'>". $prenotazione['email'] ."</td>
                    <td data-title='Telefono'>". $prenotazione['telefono'] ."</td>
                </tr>";
            }
        
            $content_prenotazioni .= $html_table_footer;
        } else {
            $content_prenotazioni = "<p>Non ci sono prenotazioni per la data selezionata.</p>";
        }
    }
}

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

$htmlPage = str_replace('<div id="errori"></div>', $errors, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<prenotazioni/>", $content_prenotazioni, $htmlPage);

echo $htmlPage;

?>