<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');
require_once(SITE_ROOT . '/php/Models/Sessione.php');

$valid = '';

$modelloUtente = new Utente();

$ruoloUtente = $modelloUtente->getRole($_SESSION['userId']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {     // Pulsante submit premuto
    preventMaliciousCode($_POST);
    if (isset($_POST['cancella']) && !Utente::isTrainer($_SESSION['userId'])) {
        $modelloUtente->delete($_SESSION['userId']);
        header('Location: /php/logout.php');
    } else {
        $response = checkAndUploadImage(SITE_ROOT . "/img/fotoProfilo/", "profile-img", $_SESSION['userId'], "default.png");
        if($response[1] == "") {
            $valid = $modelloUtente->validator($_POST);
            if($valid == TRUE){
                $_POST['foto_profilo'] = $response[0];
                $_POST['ruolo'] = $ruoloUtente;
                if(!$modelloUtente->update($_SESSION['userId'], $_POST)){
                    $valid = "<p>Qualcosa è andato storto, ci scusiamo per il disagio</p>";
                }
                else{
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['userId'] = $modelloUtente->getIdFromEmail($_POST['email']);
                    header("location: profilo.php");
                }
            } 
        } else {
            $valid .= $response[1];
        }
    }
}

// TAKE OLD USER INFO
$userData = $modelloUtente->read($_SESSION['userId']);

$formContent = "
    <div class='input-wrapper success'>
        <label for='nome'>Nome*</label>
        <input type='text' value='" . $userData['nome'] . "' name='nome' id='nome' class='transparent-login' onblur='validaNome()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='cognome'>Cognome*</label>
        <input type='text' value='" . $userData['cognome'] . "' name='cognome' id='cognome' class='transparent-login' onblur='validaCognome()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='email'><span xml:lang='en' lang='en'>E-mail*</span></label>
        <input type='email' value='" . $userData['email'] . "' name='email' id='email' class='transparent-login' onblur='validaEmail()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper input-wrapper-with-image success'>
        <img src='../img/fotoProfilo/" . ($userData['foto_profilo']?$userData['foto_profilo']:'default.png') . "' id='user-profile-img' class='profilePicture' alt='user profile image'>
        <label for='profile-img'>
            Foto profilo
        </label>
        <p class='hint'>Grandezza massima della foto 2<abbr title='megabyte'>MB</abbr></p>
        <input type='file' name='profile-img' id='profile-img' class='transparent-login' accept='image/png, image/jpeg' onchange='validateImage(\"profile-img\")'>       
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='data_nascita'>Data di nascita*</label>
        <input type='date' value='" . $userData['data_nascita'] . "' name='data_nascita' id='data_nascita' class='transparent-login' onblur='validaDataNascita()'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='telefono'>Telefono</label>
        <input type='tel' value='" . $userData['telefono'] . "' name='telefono' id='telefono' class='transparent-login' onblur='validaTelefono()' pattern='[0-9]{10}'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='maschio'>Sesso</label>
            <div class='super-radio-wrapper'>
            <div class='radio-wrapper'>
                <input type='radio' name='sesso' id='maschio' class='transparent-login' value='M' " . ($userData['sesso'] == "M" ? "checked" : "") . "/>
                <label for='maschio'>Maschio</label>
            </div>
            <div class='radio-wrapper'>
                <input type='radio' name='sesso' id='femmina' class='transparent-login' value='F' " . ($userData['sesso'] == "F" ? "checked" : "") . "/>
                <label for='femmina'>Femmina</label>
            </div>
        </div>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='altezza'>Altezza (in centimetri)</label>
        <input type='number' value='" . $userData['altezza'] . "' name='altezza' id='altezza' onblur='validaAltezza()' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='peso'>Peso (in chilogrammi)</label>
        <input type='number' value='" . $userData['peso'] . "' name='peso' id='peso' onblur='validaPeso()' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='password'><span xml:lang='en' lang='en'>Password*</span></label>
        <input type='password' value='" . $userData['password'] . "' name='password' id='password' onblur='validaPassword()' class='transparent-login'>
        <p class='error'></p>
    </div>
    <div class='input-wrapper success'>
        <label for='Rpassword'>Ripeti <span xml:lang='en' lang='en'>Password*</span></label>
        <input type='password' value='" . $userData['password'] . "' name='Rpassword' id='Rpassword' onblur='validaPassword()' class='transparent-login'>
        <p class='error'></p>
    </div>
";

$menuPrivateAreaUtente = "
    <ul id='private-area-menu'>
        <li><a href='../areaprivata/profilo.php' class='button button-transparent'>
            DATI
            <span class='material-symbols-outlined'>description</span>
        </a></li>
        <li><a href='../areaprivata/prenotazione_corso.php' class='button button-transparent'>
            CORSI
            <span class='material-symbols-outlined'>sports_gymnastics</span>
        </a></li>
        <li><a href='../areaprivata/prenotazione_scheda.php' class='button button-transparent'>
            SCHEDE
            <span class='material-symbols-outlined'>table_rows</span>
        </a></li>
        <li><a href='../areaprivata/prenotazione_sessione.php' class='button button-transparent'>
            SESSIONE
            <span class='material-symbols-outlined'>schedule</span>
        </a></li>
        <li id='private-area-selected'><p class='button button-transparent'>
            MODIFICA
            <span class='material-symbols-outlined'>edit_note</span>
        </p></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en' lang='en'>LOGOUT</span>
            <span class='material-symbols-outlined'>logout</span>
        </a></li>
    </ul>
";
$menuPrivateAreaGestione = "
    <ul id='private-area-menu'>
        <li><a href='../areaprivata/profilo.php' class='button button-transparent'>
            DATI
            <span class='material-symbols-outlined'>description</span>
        </a></li>
        <li><a href='../areaprivata/gestione_corso.php' class='button button-transparent'>
            CORSI
            <span class='material-symbols-outlined' aria-hidden='true'>sports_gymnastics</span>
        </a></li>
        <li><a href='../areaprivata/gestione_scheda.php' class='button button-transparent'>
            SCHEDE
            <span class='material-symbols-outlined' aria-hidden='true'>table_rows</span>
        </a></li>
        <li><a href='../areaprivata/gestione_sessione.php' class='button button-transparent'>
            SESSIONE
            <span class='material-symbols-outlined' aria-hidden='true'>schedule</span>
        </a></li>
        <li id='private-area-selected'><p class='button button-transparent'>
            MODIFICA
            <span class='material-symbols-outlined'>edit_note</span>
        </p></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en'>LOGOUT</span>
            <span class='material-symbols-outlined' aria-hidden='true'>logout</span>
        </a></li>
    </ul>
";

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/modifica_profilo.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

if(!Utente::isTrainer($_SESSION['userId'])){
    $modal = file_get_contents(SITE_ROOT . '/html/components/modal_confirm_delete.html');
    $form_conferma = '
        <form action="modifica_profilo.php" method="post">
            <input id = "confirmDelete" class="button" type="submit" value="Cancella" name="cancella" />
        </form>
    ';
    $modal = str_replace('<formCancella/>', $form_conferma, $modal);
    $buttonElimina = file_get_contents(SITE_ROOT . '/html/components/button_elimina.html');
} else {
    $modal = "";
    $buttonElimina = "";
}

$htmlPage = str_replace('<formContent/>', $formContent, $htmlPage);
$htmlPage = str_replace('<formErrors/>', $valid, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);
$htmlPage = str_replace('<modal/>', $modal, $htmlPage);
$htmlPage = str_replace('<buttonElimina/>', $buttonElimina, $htmlPage);



if($userData['ruolo'] == 3)
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaUtente, $htmlPage);
else
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaGestione, $htmlPage);


if ($ruoloUtente < 3) {
    $htmlPage = str_replace(
        "<a href='/areaprivata/prenotazione_corso.php' class='button button-transparent'>", 
        "<a href='/areaprivata/gestione_corso.php' class='button button-transparent'>", 
        $htmlPage
    );
    $htmlPage = str_replace(
        "<a href='/areaprivata/prenotazione_scheda.php' class='button button-transparent'>", 
        "<a href='/areaprivata/gestione_scheda.php' class='button button-transparent'>", 
        $htmlPage
    );
    $htmlPage = str_replace(
        "<a href='/areaprivata/prenotazione_sessione.php' class='button button-transparent'>", 
        "<a href='/areaprivata/gestione_sessione.php' class='button button-transparent'>", 
        $htmlPage
    );
}

echo $htmlPage;

?>