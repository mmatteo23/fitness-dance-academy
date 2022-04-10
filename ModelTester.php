<?php

require_once("php/Models/Utente.php");


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $utente = new Utente();

    $utente->create($_POST);
}

$utente = new Utente();

$utente->read(4);

echo '
<form method="POST" action="ModelTester.php">
    <label for="fname">First name:</label><br>
    <input type="text" id="fname" name="fname" value="John"><br>
    <label for="lname">Last name:</label><br>
    <input type="text" id="lname" name="lname" value="Doe"><br><br>
    <input type="submit" value="Submit">
</form> 
';


?>