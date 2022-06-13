<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');
require_once(SITE_ROOT . '/php/Models/Sessione.php');

$valid = '';

$modelloUtente = new Utente();


if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    if (isset($_POST['cancella'])) {
        $modelloUtente->delete($_SESSION['userId']);
        header('Location: /php/logout.php');
    } else
    $response = checkAndUploadImage(SITE_ROOT . "/img/fotoProfilo/", "profile-img", $_SESSION['userId'], "default.png");
    if($response[1] == "") {
        $valid = $modelloUtente->validator($_POST);
        if($valid == TRUE){
            $_POST['foto_profilo'] = $response[0];
            $_POST['ruolo'] = $modelloUtente->getRole($_SESSION['userId']);
            if(!$modelloUtente->update($_SESSION['userId'], $_POST)){
                $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
            }
            else{
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['userId'] = $modelloUtente->getIdFromEmail($_POST['email']);
                header("location: profile.php");
            }
        } 
    } else {
        $valid .= $response[1];
    }
}

// TAKE OLD USER INFO
$userData = $modelloUtente->read($_SESSION['userId']);

$formContent = "
    <div class='input-wrapper'>
        <label for='nome'>Nome*</label>
        <input type='text' value='" . $userData['nome'] . "' name='nome' id='nome' class='transparent-login' onblur='validaNome()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='cognome'>Cognome*</label>
        <input type='text' value='" . $userData['cognome'] . "' name='cognome' id='cognome' class='transparent-login' onblur='validaCognome()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='email'><span xml:lang='en'>E-mail*</span></label>
        <input type='email' value='" . $userData['email'] . "' name='email' id='email' class='transparent-login' onblur='validaEmail()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper input-wrapper-with-image'>
        <label for='profile_img'>
            <img src='/img/fotoProfilo/" . ($userData['foto_profilo']?$userData['foto_profilo']:'default.png') . "' id='user-profile-img' class='profilePicture' alt='user profile image'>
            <div class='input-label-img'>
                <span>Foto profilo<span>
                <br>
                <span class='hint'>Grandezza massima della foto 2<abbr title='megabyte'>MB</abbr></span>
            </div>
        </label>
        <input type='file' value='" . $userData['foto_profilo'] . "' name='profile-img' id='profile-img' class='transparent-login' accept='image/png, image/jpeg' onchange='validateImage(\"profile-img\")'>       
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='data_nascita'>Data di nascita*</label>
        <input type='date' value='" . $userData['data_nascita'] . "' name='data_nascita' id='data_nascita' class='transparent-login' value='2000-01-01'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='telefono'>Telefono</label>
        <input type='tel' value='" . $userData['telefono'] . "' name='telefono' id='telefono' class='transparent-login' pattern='[0-9]{10}'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='sesso'>Sesso</label>
            <div class='super-radio-wrapper'>
            <div class='radio-wrapper'>
                <input type='radio' name='sesso' id='maschio' class='transparent-login' value='M' " . ($userData['sesso'] == "M" ? "checked" : "") . "/>
                <label for='html'>Maschio</label>
            </div>
            <div class='radio-wrapper'>
                <input type='radio' name='sesso' id='femmina' class='transparent-login' value='F' " . ($userData['sesso'] == "F" ? "checked" : "") . "/>
                <label for='html'>Femmina</label>
            </div>
        </div>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='altezza'>Altezza (in centimetri)</label>
        <input type='number' value='" . $userData['altezza'] . "' name='altezza' id='altezza' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='peso'>Peso (in chilogrammi)</label>
        <input type='number' value='" . $userData['peso'] . "' name='peso' id='peso' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='password'><span xml:lang='en'>Password*</span></label>
        <input type='password' value='" . $userData['password'] . "' name='password' id='password' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper'>
        <label for='Rpassword'>Ripeti <span xml:lang='en'>Password*</span></label>
        <input type='password' value='" . $userData['password'] . "' name='Rpassword' id='Rpassword' class='transparent-login'>
        <p class='error'></p>
    </div>
";

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/edit_profile.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

$htmlPage = str_replace('<formContent/>', $formContent, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $valid, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>