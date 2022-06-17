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

preventMaliciousCode($_GET);
preventMaliciousCode($_POST);

if(isset($_GET['id'])){
    $corsoId = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $corsoId = $_POST['id'];
} else {
    $corsoId = false;
}

if($corsoId !== false){

    // CHECK CORSO ESISTE
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

    if($_SERVER['REQUEST_METHOD'] == 'POST') { // Pulsante submit premuto
    
        if(isset($_POST['cancella']) && $corso){    // se passo questo if sono sicuro che il corso lo sta modificando il proprietario e che esiste
            
            $result = $modelloCorso->delete($corsoId);
            if($result){
                header("location: gestione_corso.php");
            } else {
                $valid = "<p>Errore! Qualcosa è andanto storto nella cancellazione del corso, riprovare o contattare l'assistenza.</p>";
            }
        } else {
            $response = checkAndUploadImage(SITE_ROOT . "/img/corsi/", "copertina", $corsoId, "default.jpg");
            if($response[1] == "") {
                $valid = $modelloCorso->validator($_POST);
                if($valid === TRUE){
                    $_POST['copertina'] = $response[0];
                    if(!$modelloCorso->update($corsoId, $_POST)){
                        $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
                    }
                    else{
                        header("location: gestione_corso.php?view=".$corsoId);
                    }
                } 
            } else {
                $valid .= $response[1];
            }
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
        <input type='text' value='" . $corso['id'] . "' name='id' id='id' hidden='hidden'>
    ";

    $trainers = $modelloUtente->getTrainers();

    if(isset($trainers)){
        $trainerOptions = '';
        foreach ($trainers as $trainer) {
            if($trainer['id']==$corso['trainer'])
                $trainerOptions .= "<option value='" . $trainer['id'] . "' selected='selected' role='option'>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
            else
                $trainerOptions .= "<option value='" . $trainer['id'] . "' role='option'>" . $trainer['nome'] . " " . $trainer['cognome'] . "</option>";
        }
    }
} else {
    header("location: gestione_corso.php");
}

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/modifica_corso.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer2.html');
$modal = file_get_contents(SITE_ROOT . '/html/components/modal_confirm_delete.html');
$import_script = '<script defer src="../js/modalManager.js"></script>';
$form_conferma = '
    <form action="modifica_corso.php" method="post">
        <input type="text" value="' . $corsoId . '" name="id" id="id" hidden="hidden">
        <input id = "confirmDelete" class="button" type="submit" value="Cancella" name="cancella" />
    </form>
';
$modal = str_replace('<formCancella/>', $form_conferma, $modal);
$buttonElimina = file_get_contents(SITE_ROOT . '/html/components/button_elimina.html');

$htmlPage = str_replace('<formContent/>', $formContent, $htmlPage);
$htmlPage = str_replace('<trainerOptions/>', $trainerOptions, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $valid, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);
$htmlPage = str_replace('<modal/>', $modal, $htmlPage);
$htmlPage = str_replace('<modalManagerJs/>', $import_script, $htmlPage);
$htmlPage = str_replace('<buttonElimina/>', $buttonElimina, $htmlPage);

echo $htmlPage;

?>