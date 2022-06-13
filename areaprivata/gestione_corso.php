<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modello = new Corso;
$modelloUtente = new Utente;

if(!isset($_SESSION['userId']) || $modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

// html pieces
$content_corsi = "";
$html_table = "<table class='table-prenotazione'>
    <thead>
        <tr>
            <th>Titolo</td>
            <th>Data Inizio</td>
            <th>Data Fine</td>
            <th>Prenotazioni</td>
            <th>Visualizza</td>
            <th>Modifica</td>
        </tr>
    </thead>
    <tbody>";
$iscritti_table = "
<table class='table-prenotazione'>
    <thead>
        <tr>
            <th>Nome</td>
            <th>Cognome</td>
            <th>Data di nascita</td>
            <th>Email</td>
            <th>Telefono</td>
        </tr>
    </thead>
    <tbody>
";
$html_table_footer = "</tbody></table>";
$pageTitle = "
    <div class='private-area-title-and-button'>
        <h1 id='head-private-area-top'>Lista dei tuoi corsi</h1>
        <a class='link-sopra-table button button-purple' href='creazione_corso.php'>Aggiungi un nuovo corso</a>
    </div>
";
$filters = "
    <form method='get' class='filtri'>
        <label for='titolo'>Nome</label>
        <input type='text' name='titolo'/>
        <label for='descrizione'>Descrizione</label>
        <input type='text' name='descrizione'/>
        <button type='submit' class='button button-transparent button-filter'>Cerca</button>
        <button onClick='resetFilters()' type='reset' class='button button-transparent button-filter'>Reset</button>
    </form>
";

$isTrainer = $modelloUtente->isTrainer($_SESSION['userId']);
if ($isTrainer)
    $corsi = $modello->getCorsiByTrainerId($_GET, $_SESSION['userId']);
else
    $corsi = $modello->getAllCorsi($_GET);

if(count($corsi)){
    $content_corsi = $html_table;

    foreach($corsi as $corso){
        $nIscritti = $modello->getNumeroIscritti($corso['id']);
        $content_corsi .= "<tr>
            <th>". $corso['titolo'] ."</th>
            <td data-title='Data Inizio'>". $corso['data_inizio'] ."</td>
            <td data-title='Data Fine'>". $corso['data_fine'] ."</td>
            <td data-title='Prenotazioni'>". $nIscritti ."</td>
            <td>
                <button type='submit' name='view' value=" . $corso['id'] . " class='button button-purple button-filter'>Visualizza</button>
            </td>
            <td>
                <a class='button button-purple button-filter' href=\"/areaprivata/modifica_corso.php?id=".$corso['id']."\">Modifica</a>
            </td>
        </tr>";
    }

    $content_corsi .= $html_table_footer;
} else {
    $content_corsi = "<p>Non ci sono corsi che combaciano con i tuoi parametri di ricerca</p>";
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/gestione_corso.html");

if($_SERVER['REQUEST_METHOD'] === "GET"){

    // Se si sta richiedendo un corso specifico con 'view' allora:
    if(isset($_GET['view'])){
        $htmlPage = str_replace(
            "<li id='selectedBreadcrumb'>Corsi</li>", 
            "<li><a href='gestione_corso.php'>Corsi</a></li>", 
            $htmlPage
        );
        $htmlPage = str_replace(
            "<modificaBreadcrumb/>", 
            "<li id='selectedBreadcrumb'>Visualizza</li>", 
            $htmlPage
        );

        $corsoId = $_GET['view'];
        $corso = $modello->read($corsoId);
        $pageTitle = "
            <h1 id='head-private-area-top'>".$corso['titolo']."</h1>
            <p>".$corso['descrizione']."</p>
        ";
        $filters = "";
        $nIscritti = $modello->getNumeroIscritti($corsoId);
        if($nIscritti){
            $content_corsi = $iscritti_table;
            $iscritti = $modello->getIscritti($corsoId);
        
            foreach($iscritti as $iscritto){
                $content_corsi .= "<tr>
                    <td data-title='Nome'>". $iscritto['nome'] ."</td>
                    <td data-title='Cognome'>". $iscritto['cognome'] ."</td>
                    <td data-title='Data Nascita'>". $iscritto['data_nascita'] ."</td>
                    <td data-title='email'>". $iscritto['email'] ."</td>
                    <td data-title='telefono'>". $iscritto['telefono'] ."</td>
                </tr>";
            }
        
            $content_corsi .= $html_table_footer;
        } else {
            $content_corsi = "<p>Non ci sono utenti iscritti a questo corso.</p>";
        }
    }

}

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageTitle/>", $pageTitle, $htmlPage);
$htmlPage = str_replace("<filters/>", $filters, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<tabellaElencoCorsi/>", $content_corsi, $htmlPage);

echo $htmlPage;

?>