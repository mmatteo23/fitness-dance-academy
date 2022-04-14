<?php

require_once('php/db.php');
use DB\DBAccess;

class Sessione {
    
    public static function validator(array $data)
    {
        $errors = "";
        $giorno = $data['giornoSessione'];
        $mese = $data['meseSessione'];
        $oraI = $data['oraInizio'];
        $minI = $data['minutoInizio'];
        $oraF = $data['oraFine'];
        $minF = $data['minutoFine'];
        if($mese < date("m") || $mese == date("m") && $giorno < date("d")){
            $errors += "<li>Non puoi prenotare sessioni per giorni passati.</li>";
        }
        if($mese == date("m") && $giorno == date("d") && ($oraI < date("h") || $oraI == date("h") && $minI < date("m"))){
            $errors += "<li>Non puoi prenotare sessioni per ore passate.</li>";
        }
        if($oraF < $oraI || $oraF == $oraI && $minF <= $minI){
            $errors += "<li>La sessione non può finire prima che inizi.</li>";
        }
        if($errors != "")
            return "<ul>".$errors."</ul>";
        return $errors;
    }

    
    // CRUD OPERATIONS
    public static function create(array $data)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $year = date("Y");
            $query = "INSERT INTO prenotazione_sessione (data, ora_inizio, ora_fine, cliente)
            VALUES (
                '" . ($year."-".$data['meseSessione']."-".$data['giornoSessione']) . "',
                '" . $data['oraInizio'].":".$data['minutoInizio'].":00',
                '" . $data['oraFine'].":".$data['minutoFine'].":00',
                " . $data['cliente'] . "
            )";
            echo $query;

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }
}

?>