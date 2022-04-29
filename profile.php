<?php

require_once("php/Models/Utente.php");
require_once("php/validSession.php");

$modello = new Utente();
$userData = $modello->read($_SESSION['userId']);

$fotoProfiloDIR = "img/fotoProfilo/";
$fotoDefault = "default.png";

// fetch foto profilo, se c'è nel db la mette altrimenti mette quella di default
if ($userData['foto_profilo']) {
    $profilePicture = "<img class='profilePicture' src='".$fotoProfiloDIR.$userData['foto_profilo']."'/>";
} else {
    $profilePicture = "<img class='profilePicture' src='".$fotoProfiloDIR.$fotoDefault."'/>";
}

$profileData = "
    <ul>
        <li>".$userData['nome']." ".$userData['cognome']."</li>
        <li>E-mail: ".$userData['email']."</li>
        <li>Data di nascita: ".$userData['data_nascita']."</li>
        <li>Telefono: ".$userData['telefono']."</li>
    </ul>
";

$htmlPage = file_get_contents("html/profile.html");
$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<profilePicture/>", $profilePicture, $htmlPage);
$htmlPage = str_replace("<profileData/>", $profileData, $htmlPage);
$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);

echo $htmlPage;

?>