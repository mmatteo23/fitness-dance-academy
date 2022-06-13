<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');
require_once(SITE_ROOT . '/php/Models/Esercizio.php');

$errors = '';

$modelloUtente = new Utente();
$modelloEsercizio = new Esercizio();

$html_table = "
    <table class='table-prenotazione'>
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    
}

$trainers = $modelloUtente->getTrainers();
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
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>