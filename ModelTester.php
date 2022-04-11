<?php

require_once("php/Models/Utente.php");


if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    $modello = new Utente();
    $modello->create($_POST);
    
    $destructor = new Utente($_POST);
    $destructor->delete(1);


    $esercizio = new Esercizio($_POST);
    

    redirectTo('Esercizio.php');
}

echo '
<form method="POST" action="ModelTester.php">
    <label for="nome">First name:</label><br>
    <input type="text" id="nome" name="nome" value="John"><br>
    <label for="cognome">Last name:</label><br>
    <input type="text" id="cognome" name="cognome" value="Doe"><br><br>
    <input type="submit" value="Submit">
</form> 
';


?>