<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . '/php/Models/Corso.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');

$valid = '';

$modelloCorso = new Corso();
$modelloUtente = new Utente();

if($_SERVER['REQUEST_METHOD'] == 'POST') { // Pulsante submit premuto
    preventMaliciousCode($_POST);
    $newId = $modelloCorso->getNewId();
    $response = checkAndUploadImage(SITE_ROOT . "/img/corsi/", "copertina", $newId, "default.jpg");
    if($response[1] == "") {
        $valid = $modelloCorso->validator($_POST);
        if($valid === TRUE){
            $_POST['id'] = $newId;
            $_POST['copertina'] = $response[0];
            if(!$modelloCorso->create($_POST)){
                $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
            }
            else{
                header("location: gestione_corso.php");
            }
        } 
    } else {
        $valid .= $response[1];
    }
}

$formContent = "
    <div class='input-wrapper'>
        <label for='titolo'>Titolo*</label>
        <input type='text' name='titolo' id='titolo' class='transparent-login' onblur='validaTitolo()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='descrizione'>Descrizione*</label>
        <input type='text' name='descrizione' id='descrizione' class='transparent-login' onblur='validaDescrizione()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='data_inizio'>Data di inizio*</label>
        <input type='date' name='data_inizio' id='data_inizio' class='transparent-login' value='2000-01-01' onblur='validaDate()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='data_fine'>Data di fine*</label>
        <input type='date' name='data_fine' id='data_fine' class='transparent-login' value='2000-01-01' onblur='validaDate()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='copertina'>Copertina del corso</label>
        <input type='file' name='copertina' id='copertina' class='transparent-login' accept='image/png, image/jpeg' onchange='validateImage(\"copertina\")'>       
        <p class='error'></p>
    </div>
    <div class='input-wrapper alt_img success'>
        <label for='alt_copertina'>Descrizione copertina*</label>
        <p class='hint'>Se non viene scelta una copertina, lasciare la descrizione di default.</p>
        <input type='text' value='Immagine del logo del sito: teschio con ossa e scritta FDA' name='alt_copertina' id='alt_copertina' class='transparent-login' onblur='validaAltImmagine()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='trainer'>Trainer*</label>
        <select name='trainer' id='trainer' class='transparent-login'>
            <trainerOptions/>
        </select>
        <p class='error'></p>
    </div>
";

$trainers = $modelloUtente->getTrainers();

if(isset($trainers)){
    $trainerOptions = '';
    foreach ($trainers as $trainer) {
        if($trainer['id']==0)
            $trainerOptions .= "<option value='" . $trainer['id'] . "' selected>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
        else
            $trainerOptions .= "<option value='" . $trainer['id'] . "'>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
    }
}

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/creazione_corso.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

$htmlPage = str_replace('<formContent/>', $formContent, $htmlPage);
$htmlPage = str_replace('<trainerOptions/>', $trainerOptions, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $valid, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>