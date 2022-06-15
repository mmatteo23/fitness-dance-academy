<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . '/php/Models/Corso.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');

$valid = '';

$modelloCorso = new Corso();
$modelloUtente = new Utente();

if(!isset($_SESSION['userId']) || $modelloUtente->isCliente($_SESSION['userId'])) {
    header("location: /login.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') { // Pulsante submit premuto
    preventMaliciousCode($_POST);


    $response = checkAndUploadImage(SITE_ROOT . "/img/corsi/", "copertina", $_POST['id'], "default.jpg");
    if($response[1] == "") {
        $valid = $modelloCorso->validator($_POST);
        if($valid == TRUE){
            $_POST['copertina'] = $response[0];
            if(!$modelloCorso->update($_POST['id'], $_POST)){
                $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
            }
            else{
                header("location: gestione_corso.php?view=".$_POST['id']);
            }
        } 
    } else {
        $valid .= $response[1];
    }
}

preventMaliciousCode($_GET);
// CHECK CORSO ESISTE
$lastIdPiuUno = $modelloCorso->getNewId();
if ($_GET['id'] >= $lastIdPiuUno) {
    header("location: gestione_corso.php");
}
$corso = $modelloCorso->read($_GET['id']);
// CHECK TRAINER AUTORIZZATO
if ($modelloUtente->isTrainer($_SESSION['userId'])) {
    if($corso['trainer'] != $_SESSION['userId']) {
        header("location: gestione_corso.php");
    }
}

$titolo = preg_replace('/<[^>]*>/', '', $corso['titolo']);
$descrizione = preg_replace('/<[^>]*>/', '', $corso['descrizione']);

$formContent = "
    <div class='input-wrapper success'>
        <label for='titolo'>Titolo*</label>
        <input type='text' value='" . $titolo . "' name='titolo' id='titolo' class='transparent-login' onblur='validaTitolo()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='descrizione'>Descrizione*</label>
        <input type='text' value='" . $descrizione . "' name='descrizione' id='descrizione' class='transparent-login' onblur='validaDescrizione()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='data_inizio'>Data di inizio*</label>
        <input type='date' value='" . $corso['data_inizio'] . "' name='data_inizio' id='data_inizio' class='transparent-login' onchange='validaDate()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='data_fine'>Data di fine*</label>
        <input type='date' value='" . $corso['data_fine'] . "' name='data_fine' id='data_fine' class='transparent-login' onchange='validaDate()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper input-wrapper-with-image success'>
        <img src='../img/corsi/" . ($corso['copertina']?$corso['copertina']:'default.jpg') . "' id='copertina-img' class='profilePicture' alt='user profile image'>
        <label for='copertina'>
            Copertina del corso
        </label>
        <p class='hint'>Grandezza massima della foto 2<abbr title='megabyte'>MB</abbr></p>
        <input type='file' name='copertina' id='copertina' class='transparent-login' accept='image/png, image/jpeg' onchange='validateImage(\"copertina\")'>       
        <p class='error'></p>
    </div>
    <div class='input-wrapper alt_img success'>
        <label for='alt_copertina'>Descrizione copertina*</label>
        <input type='text' value='" . $corso['alt_copertina'] . "' name='alt_copertina' id='alt_copertina' class='transparent-login' onblur='validaAltImmagine()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='trainer'>Trainer*</label>
        <select name='trainer' id='trainer' class='transparent-login'>
            <trainerOptions/>
        </select>
        <p class='error'></p>
    </div>
    <input type='text' value='" . $corso['id'] . "' name='id' id='id' hidden>
";

$trainers = $modelloUtente->getTrainers();

if(isset($trainers)){
    $trainerOptions = '';
    foreach ($trainers as $trainer) {
        if($trainer['id']==$corso['trainer'])
            $trainerOptions .= "<option value='" . $trainer['id'] . "' selected>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
        else
            $trainerOptions .= "<option value='" . $trainer['id'] . "'>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
    }
}

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/modifica_corso.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

$htmlPage = str_replace('<formContent/>', $formContent, $htmlPage);
$htmlPage = str_replace('<trainerOptions/>', $trainerOptions, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $valid, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>