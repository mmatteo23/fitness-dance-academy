<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");

// Get post params
preventMaliciousCode($_POST);
$id_corso = $_POST['id_corso'];

$modello = new Corso;
$corso = $modello->read($id_corso);

if($corso !== NULL){
    if($modello->unregisterUser($id_corso, $_SESSION['userId']))
        header('Location: prenotazione_corso.php');
}

// Lo facciamo ritornare e basta? O errore/successo?
header('Location: prenotazione_corso.php');

?>