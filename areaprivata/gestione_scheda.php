<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . "/php/Models/Scheda.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");

// get all corsi from db
$modelloScheda = new Scheda();
$modelloUtente = new Utente();

if(!isset($_SESSION['userId']) || $modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

$schede = $modelloScheda->index($_GET);

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

if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    if(!$modelloUtente->isCliente($userId)){
        $content = "
        <table id='tabPrenotate'>
            <thead>
                <tr>
                    <th scope='col'>id</th>
                    <th scope='col'>Cliente</th>
                    <th scope='col'>Data prenotazione</th>
                    <th scope='col'>Elimina</th>
                </tr>
            </thead>
            <tbody>
        ";
        if($modelloUtente->isAdmin($userId)){
            $schedeRichieste = $modelloScheda->getAllSchedeRichieste();
        } else {
            $schedeRichieste = $modelloScheda->getSchedeRichieste($userId);
        }
        if(count($schedeRichieste) > 0){
            foreach($schedeRichieste as $scheda){
                $content .= "
                    <tr id='scheda".$scheda['id']."'>
                        <td>".$scheda['id']."</td>
                        <td>".$scheda['utente']."</td>
                        <td>".$scheda['data']."</td>
                        <td><button id='delete-scheda' onClick='deletePrenotazioneScheda(".$scheda['id'].")' class='button button-purple'><i class=' fa fa-window-close'></i></i></button></td>
                    </tr>";
            }
            $content .= $table_footer;
        }
        else{
            $content = "<p>Non sono presenti schede richieste</p>";
        }
    }
    else{
        // redirect to profile.html
    }
}

$htmlPage = file_get_contents(SITE_ROOT . "/html/areaprivata/gestione_scheda.html");

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

$htmlPage = str_replace("<sessionTableBody/>", $content, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>