<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');
require_once(SITE_ROOT . '/php/Models/Sessione.php');

$errors = '';

$modello = new Utente();


if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    $errors = $modello->validator($_POST);
    if (isset($_POST['cancella'])) {
        $modello->delete($_SESSION['userId']);
        header('Location: /php/logout.php');
    } else
    if($errors === TRUE){
        $_POST['foto_profilo'] = 'default.png';
        $_POST['alt_foto_profilo'] = 'Foto profilo di default';
        $_POST['ruolo'] = 1;
        if(!$modello->update($_SESSION['userId'], $_POST)){
            echo "non ce l'ho fatta";
        }
        else{
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['userId'] = $modello->getIdFromEmail($_POST['email']);
            header('location: profile.php');
        }
    }
}

// TAKE OLD USER INFO
$userData = $modello->read($_SESSION['userId']);

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
$htmlPage = str_replace('<formErrors/>', $errors, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>