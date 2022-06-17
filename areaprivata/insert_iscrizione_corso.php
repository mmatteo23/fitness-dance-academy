<?php

require_once "../config.php";

require_once(SITE_ROOT . '/php/validSession.php');
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Corso.php");

// Get post params
preventMaliciousCode($_POST);
$id_corso = $_POST['id_corso'];

$modello = new Corso;
$corso = $modello->read($id_corso);

if($corso !== NULL){
    if($modello->registerUser($id_corso, $_SESSION['userId']))
        header('Location: prenotazione_corso.php');

} else {
    echo "ERRORE";
}


?>