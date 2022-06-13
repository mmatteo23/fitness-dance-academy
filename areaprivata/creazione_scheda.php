<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');
require_once(SITE_ROOT . '/php/Models/Esercizio.php');
require_once(SITE_ROOT . '/php/Models/Scheda.php');

$errors = '';

$modelloUtente = new Utente();
$modelloEsercizio = new Esercizio();

$html_table = "
    <table id='tabEx' class='table-prenotazione'>
        <thead>
            <tr>
                <th scope='col'>Esercizio</th>
                <th scope='col'>Numero Serie</th>
                <th scope='col'>Ripetizioni</th>
                <th scope='col'>Pausa</th>
            </tr>
        </thead>
        <tbody>";
$html_table_footer = "</tbody></table>";

$hiddenInput = "";
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $hiddenInput = "<input type='hidden' id='idPren' name='idPren' value='".$_GET['id']."'>";
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    $errors = Scheda::validate($_POST['listaEs']);
    
    if($errors === TRUE){  //input valido
        $errors = "";

        $idPren = $_POST['idPren'];
        $prenotazione = Scheda::getPrenotazione($idPren);
        $trainer = $prenotazione['trainer'];
        $client = $prenotazione['cliente'];

        Scheda::deletePrenotazione($idPren);
        $idScheda = Scheda::create($client, $trainer);

        $listaEs = trim($_POST['listaEs'], ';');

        foreach(explode(';', $listaEs) as $esercizio){
            $dati = explode(':', $esercizio);
            Esercizio::addEsercizio($idScheda, $dati[0], $dati[1], $dati[2], $dati[3]);
        }
        header("location: /areaprivata/visualizzaScheda.php?id=$idScheda");
    }
}

$esercizi = $modelloEsercizio->index();

// crea options per selectbox degli esercizi
$optionsEsercizi = "";
foreach($esercizi as $esercizio){
    $optionsEsercizi .= "<option value=". $esercizio["id"] . ">" . $esercizio["nome"] . "</option>";
}



$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/creazione_scheda.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

$htmlPage = str_replace('<optionsEsercizi/>', $optionsEsercizi, $htmlPage);
$htmlPage = str_replace('<tableEserciziScheda/>', $html_table.$html_table_footer, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $errors, $htmlPage);
$htmlPage = str_replace('<hiddenInput/>', $hiddenInput, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>