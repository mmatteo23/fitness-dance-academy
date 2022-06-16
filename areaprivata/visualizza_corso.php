<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

$modelloCorso = new Corso;
$modelloUtente = new Utente;

if(!isset($_SESSION['userId']) || $modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

// html pieces
$content_corsi = "";
$iscritti_table = "
<table class='table-prenotazione'>
    <thead>
        <tr>
            <th scope='col'>Nome</th>
            <th scope='col'>Data di nascita</th>
            <th scope='col'>Email</th>
            <th scope='col'>Telefono</th>
        </tr>
    </thead>
    <tbody>
";
$html_table_footer = "</tbody></table>";

preventMaliciousCode($_GET);

if($_SERVER['REQUEST_METHOD'] === "GET"){

    // Se si sta richiedendo un corso specifico con 'view' allora:
    if(isset($_GET['id'])){
        $corsoId = $_GET['id'];
        // // CHECK CORSO ESISTE
        $corso = $modelloCorso->read($corsoId);
        if (!$corso) {
            header("location: gestione_corso.php");
        }
        // CHECK TRAINER AUTORIZZATO
        if ($modelloUtente->isTrainer($_SESSION['userId'])) {
            if($corso['trainer'] != $_SESSION['userId']) {
                header("location: gestione_corso.php");
            }
        }
        $pageTitle = "
            <h1 id='head-private-area-top'>".$corso['titolo']."</h1>
            <p id='corsoDescription'>".$corso['descrizione']."</p>
        ";
        $filters = "";
        $nIscritti = $modelloCorso->getNumeroIscritti($corsoId);
        if($nIscritti){
            $content_corsi = $iscritti_table;
            $iscritti = $modelloCorso->getIscritti($corsoId);
        
            foreach($iscritti as $iscritto){
                $content_corsi .= "<tr>
                    <th data-title='Nome' scope='row'>". $iscritto['nome']." ".$iscritto['cognome'] ."</th>
                    <td data-title='Data Nascita'>". $iscritto['data_nascita'] ."</td>
                    <td data-title='Email'>". $iscritto['email'] ."</td>
                    <td data-title='Telefono'>". ($iscritto['telefono']?:"<span aria-label='telefono assente'>/</span>") ."</td>
                </tr>";
            }
        
            $content_corsi .= $html_table_footer;
        } else {
            $content_corsi = "<p>Non ci sono utenti iscritti a questo corso.</p>";
        }
    } else {
        header("location: gestione_corso.php");
    }

}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/visualizza_corso.html");
$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

// tag substitutions
$htmlPage = str_replace("<pageTitle/>", $pageTitle, $htmlPage);
$htmlPage = str_replace("<filters/>", $filters, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<elencoIscritti/>", $content_corsi, $htmlPage);

echo $htmlPage;

?>