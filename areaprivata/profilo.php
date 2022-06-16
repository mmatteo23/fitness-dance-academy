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
        <li id='private-area-selected'><p class='button button-transparent'>DATI<i class='fa-solid fa-file-lines' aria-hidden='true'></i></p></li>
        <li><a href='../areaprivata/prenotazione_corso.php' class='button button-transparent'>
            CORSI
            <i class='fa-solid fa-person-running' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/prenotazione_scheda.php' class='button button-transparent'>
            SCHEDE
            <i class='fa-solid fa-folder-closed' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/prenotazione_sessione.php' class='button button-transparent'>
            SESSIONE
            <i class='fa-regular fa-clock' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/modifica_profilo.php' class='button button-transparent'>
            MODIFICA
            <i class='fa-solid fa-pen-to-square' aria-hidden='true'></i>
        </a></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en' lang='en'>LOGOUT</span>
            <i class='fa-solid fa-right-from-bracket' aria-hidden='true'></i>
        </a></li>
    </ul>
";
$menuPrivateAreaGestione = "
    <ul id='private-area-menu'>
        <li id='private-area-selected'><p class='button button-transparent'>DATI<i class='fa-solid fa-file-lines'></i></p></li>
        <li><a href='../areaprivata/gestione_corso.php' class='button button-transparent'>
            CORSI
            <i class='fa-solid fa-person-running' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/gestione_scheda.php' class='button button-transparent'>
            SCHEDE
            <i class='fa-solid fa-folder-closed' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/gestione_sessione.php' class='button button-transparent'>
            SESSIONE
            <i class='fa-regular fa-clock' aria-hidden='true'></i>
        </a></li>
        <li><a href='../areaprivata/modifica_profilo.php' class='button button-transparent'>
            MODIFICA
            <i class='fa-solid fa-pen-to-square' aria-hidden='true'></i>
        </a></li>
        <li><a href='../php/logout.php' class='button button-transparent'>
            <span xml:lang='en'>LOGOUT</span>
            <i class='fa-solid fa-right-from-bracket' aria-hidden='true'></i>
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
        ($userData['altezza']?'<li><span>Altezza:</span><span>'.$userData['altezza'].' cm</span></li>':'').
        ($userData['peso']?'<li><span>Peso:</span><span>'.$userData['peso'].' kg</span></li>':'').'
    </ul>
';

$htmlPage = file_get_contents(SITE_ROOT . '/html/areaprivata/profilo.html');
$footer = file_get_contents(SITE_ROOT . '/html/components/footer.html');

if($userData['ruolo'] == 3)
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaUtente, $htmlPage);
else
    $htmlPage = str_replace('<menuPrivateArea/>', $menuPrivateAreaGestione, $htmlPage);

$htmlPage = str_replace('<profilePicture/>', $profilePicture, $htmlPage);
$htmlPage = str_replace('<profileData/>', $profileData, $htmlPage);
$htmlPage = str_replace('<pageFooter/>', $footer, $htmlPage);

echo $htmlPage;

?>