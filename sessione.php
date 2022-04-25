<?php

session_start();
require_once('php/Models/Sessione.php');

$htmlPage = file_get_contents("html/sessione.html");

$errors = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {     // Pulsante submit premuto

    $_POST['cliente'] = $_SESSION['userId'];

    $errors = Sessione::validator($_POST);

    if ($errors != ""){
        $htmlPage = str_replace("<div id='errori'></div>", $errors, $htmlPage);
    } else {
        $returned = Sessione::create($_POST);
    }   
}

$giornoHTML = "";
$settimana = ["domenica", "lunedì", "martedì", "mercoledì", "giovedì", "venerdì", "sabato"];
$giornoHTML .= "<p id='giornoSettimana'>".$settimana[date("w")]."</p>";
$mesi = ["gennaio", "febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre"];
$mese = $mesi[intval(date("m"))-1];

$n = 31;
if($mese == "novembre" || $mese == "aprile" || $mese == "giugno" || $mese == "settembre")
    $n = 30;
else if($mese == "febbraio"){
    $n = 28;
    if(date("y")%4==0)
        $n = 29;
}

$giornoHTML .= "<select id='giornoSessione' name='giornoSessione' onchange='giornoCambiato()'>";
for($i=1; $i<=$n; $i++){
    $giorno = $i;
    if($i<10)
        $giorno = "0".$i;
    if($i == date("d"))
        $giornoHTML .= "<option value=".$i." selected>".$giorno."</option>";
    else
        $giornoHTML .= "<option value=".$i.">".$giorno."</option>";
}
$giornoHTML .= "<select/>";

$giornoHTML .= "<select id='meseSessione' name='meseSessione' onchange='meseCambiato()'>";
for($i=1; $i<=12; $i++){
    if($i == date("m"))
        $giornoHTML .= "<option value=".$i." selected>".$mesi[$i-1]."</option>";
    else
        $giornoHTML .= "<option value=".$i.">".$mesi[$i-1]."</option>";
}
$giornoHTML .= "<select/>";

$tabellaSess = "";
if(isset($_SESSION['userId']) && $_SESSION['userId']!=''){
    $sessioniPrenot = Sessione::getSessionsOf($_SESSION['userId']);
    foreach($sessioniPrenot as $sess){
        $data = explode('-', $sess['data']);
        $data = $data[2]."/".$data[1];
        $oraI = substr($sess['ora_inizio'], 0, 5);
        $oraF = substr($sess['ora_fine'], 0, 5);;
        $tabellaSess .= "
            <tr id='sess".$sess['id']."'>
                <td>".$data."</td>
                <td>".$oraI."</td>
                <td>".$oraF."</td>
                <td><button onclick = 'deleteSession(".$sess['id'].")' id='btn-cancella'>Cancella</button></td>
            </tr>";
    }

}

$footer = file_get_contents("html/components/footer.html");

$htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
$htmlPage = str_replace("<giornoSessione/>", $giornoHTML, $htmlPage);
$htmlPage = str_replace("<sessionTableBody/>", $tabellaSess, $htmlPage);

echo $htmlPage;

?>