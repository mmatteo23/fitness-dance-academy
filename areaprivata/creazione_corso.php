<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/Models/Corso.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');

$errors = '';

$modelloCorso = new Corso();
$modelloUtente = new Utente();

if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    $errors = $modelloCorso->validator($_POST);
    if (isset($_POST['cancella'])) {
        $modelloUtente->delete($_SESSION['userId']);
        $_SESSION['email'] = "";
        header('Location: /index.php');
    } else
    if($errors === TRUE){
        $_POST['foto_profilo'] = 'default.png';
        $_POST['ruolo'] = 1;
        if(!$modelloUtente->update($_SESSION['userId'], $_POST)){
            echo "non ce l'ho fatta";
        }
        else{
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['userId'] = $modelloUtente->getIdFromEmail($_POST['email']);
            header('location: profile.php');
        }
    }
}

// TAKE OLD USER INFO
$userData = $modelloUtente->read($_SESSION['userId']);

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
        <input type='date' name='data_inizio' id='data_inizio' class='transparent-login' value='2000-01-01'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='data_fine'>Data di fine*</label>
        <input type='date' name='data_fine' id='data_fine' class='transparent-login' value='2000-01-01'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='copertina'>Copertina del corso</label>
        <input type='file' name='copertina' id='copertina' class='transparent-login' accept='image/png, image/jpeg' onblur='validaImg()'>       
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='descrizione'>Descrizione copertina* (se scelta)</label>
        <input type='text' name='descrizione' id='descrizione' class='transparent-login' onblur='validaDescrizione()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
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
$htmlPage = str_replace('<formErrors/>', $errors, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>