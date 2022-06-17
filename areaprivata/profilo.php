<?php

require_once '../config.php';

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/Models/Utente.php');

$modello = new Utente();

$userData = $modello->read($_SESSION['userId']);

$fotoProfiloDIR = 'img/fotoProfilo/';
$fotoDefault = 'default.png';
$menuPrivateAreaUtente = "
    <ul id='private-area-menu'>
        <li id='private-area-selected'><p class='button button-transparent'>
            DATI
            <img class='iconAP'   aria-hidden='true' src='../img/icons/dati.png' alt=''/>
        </p></li>
        <li><a href='../areaprivata/prenotazione_corso.php' class='button button-transparent'>
            CORSI
            <img class='iconAP'   aria-hidden='true' src='../img/icons/corsi.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/prenotazione_scheda.php' class='button button-transparent'>
            SCHEDE
            <img class='iconAP'   aria-hidden='true' src='../img/icons/schede.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/prenotazione_sessione.php' class='button button-transparent'>
            SESSIONE
            <img class='iconAP'   aria-hidden='true' src='../img/icons/sessione.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/modifica_profilo.php' class='button button-transparent'>
            MODIFICA
            <img class='iconAP'   aria-hidden='true' src='../img/icons/modifica.png' alt=''/>
        </a></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en' lang='en'>LOGOUT</span>
            <img class='iconAP'   aria-hidden='true' src='../img/icons/logout.png' alt=''/>
        </a></li>
    </ul>
";
$menuPrivateAreaGestione = "
    <ul id='private-area-menu'>
        <li id='private-area-selected'><p class='button button-transparent'>
            DATI
            <img class='iconAP'   aria-hidden='true' src='../img/icons/dati.png' alt=''/>
        </p></li>
        <li><a href='../areaprivata/gestione_corso.php' class='button button-transparent'>
            CORSI
            <img class='iconAP'   aria-hidden='true' src='../img/icons/corsi.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/gestione_scheda.php' class='button button-transparent'>
            SCHEDE
            <img class='iconAP'   aria-hidden='true' src='../img/icons/schede.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/gestione_sessione.php' class='button button-transparent'>
            SESSIONE
            <img class='iconAP'   aria-hidden='true' src='../img/icons/sessione.png' alt=''/>
        </a></li>
        <li><a href='../areaprivata/modifica_profilo.php' class='button button-transparent'>
            MODIFICA
            <img class='iconAP'   aria-hidden='true' src='../img/icons/modifica.png' alt=''/>
        </a></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en'>LOGOUT</span>
            <img class='iconAP'   aria-hidden='true' src='../img/icons/logout.png' alt=''/>
        </a></li>
    </ul>
";

// fetch foto profilo, se c'è nel db la mette altrimenti mette quella di default
if ($userData['foto_profilo']) {
    $profilePicture = "<img class='profilePicture' src='../".$fotoProfiloDIR.$userData['foto_profilo']."' alt=''/>";
} else {
    $profilePicture = "<img class='profilePicture' src='../".$fotoProfiloDIR.$fotoDefault."' alt=''/>";
}

$profileData = '
    <ul>
        <li>'.$userData['nome'].' '.$userData['cognome'].'</li>
        <li><span>E-mail:</span><span>'.$userData['email'].'</span></li>
        <li><span>Data di nascita:</span><span>'.$userData['data_nascita'].'</span></li>'.
        ($userData['telefono']?'<li><span>Telefono:</span><span>'.$userData['telefono'].'</span></li>':'').
        ($userData['altezza']?'<li><span>Altezza:</span><span>'.$userData['altezza'].' <abbr title="centimetri">cm</abbr></span></li>':'').
        ($userData['peso']?'<li><span>Peso:</span><span>'.$userData['peso'].' <abbr title="chilogrammi">kg</abbr></span></li>':'').'
    </ul>
';

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/profilo.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer2.html');

if($userData['ruolo'] == 3)
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaUtente, $htmlPage);
else
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaGestione, $htmlPage);

$htmlPage = str_replace('<profilePicture/>', $profilePicture, $htmlPage);
$htmlPage = str_replace('<profileData/>', $profileData, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>