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

$content_corsi_prenotati = "";
$tabellaSessioniPrenotate = "
    <div id='private-area-table'>
        <h2>Le sessioni che hai già prenotato</h2>
        <headTabellaSessioni/>
    </div>
";
$headTabellaSessioni = "
    <table id='tabPrenotate'>
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

$contatorePersone = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto
    preventMaliciousCode($_POST);
    $n = $modelloSessione->countPrenotati($_POST);
    $contatorePersone = "<p id='risultatoContatoreSessioni'>Abbiamo trovato $n persone prenotate per questo orario.</p>";

}

$giornoHTML = "";
$settimana = ["domenica", "lunedì", "martedì", "mercoledì", "giovedì", "venerdì", "sabato"];
$giornoHTML .= "<p id='giornoSettimana'>".$settimana[date("w")]."</p>";
$mesi = ["gennaio", "febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre"];
$mese = $mesi[intval(date("m"))-1];

$n = 31;
if($mese == "novembre" || $mese == "aprile" || $mese == "giugno" || $mese == "settembre")
    $n = 30;
else if($mese == "febbraio"){
    $n = 28;
    if(date("y")%4==0)
        $n = 29;
}

$giornoHTML .= "<select id='giornoSessione' name='giornoSessione' onchange='giornoCambiato()'>";
for($i=1; $i<=$n; $i++){
    $giorno = $i;
    if($i<10)
        $giorno = "0".$i;
    if($i == date("d"))
        $giornoHTML .= "<option value=".$i." selected>".$giorno."</option>";
    else
        $giornoHTML .= "<option value=".$i.">".$giorno."</option>";
}
$giornoHTML .= "</select>";

$giornoHTML .= "<select id='meseSessione' name='meseSessione' onchange='meseCambiato()'>";
for($i=1; $i<=12; $i++){
    if($i == date("m"))
        $giornoHTML .= "<option value=".$i." selected>".$mesi[$i-1]."</option>";
    else
        $giornoHTML .= "<option value=".$i.">".$mesi[$i-1]."</option>";
}
$giornoHTML .= "</select>";

$footer = file_get_contents(SITE_ROOT . "/html/components/footer.html");

$htmlPage = str_replace('<div id="errori"></div>', $errors, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<giornoSessione/>", $giornoHTML, $htmlPage);
$htmlPage = str_replace("<contatore/>", $contatorePersone, $htmlPage);

echo $htmlPage;

?>