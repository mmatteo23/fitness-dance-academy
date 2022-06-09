<?php

require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/php/db.php");
use DB\DBAccess;

class Sessione {
    
    private static function isBusy($date, $timeI, $timeF, $clientID){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM prenotazione_sessione 
                        WHERE cliente = $clientID AND data = '$date'
                        AND ((ora_inizio <= '$timeI' AND ora_fine >= '$timeF') 
                           OR(ora_inizio <= '$timeF' AND ora_fine >= '$timeF')
                           OR(ora_inizio >= '$timeI' AND ora_fine <= '$timeF'))";

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            if(count($queryResults) > 0){
                return true;
            }
        }
        return false;
    }

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
            $errors .= "<li>Non puoi prenotare sessioni per giorni passati.</li>";
        }
        if($mese == date("m") && $giorno == date("d") && ($oraI < date("h") || $oraI == date("h") && $minI < date("m"))){
            $errors .= "<li>Non puoi prenotare sessioni per ore passate.</li>";
        }
        if($oraF < $oraI || $oraF == $oraI && $minF <= $minI){
            $errors .= "<li>La sessione non può finire prima che inizi.</li>";
        }
        if(Sessione::isBusy(date("Y")."-".$mese."-".$giorno, $oraI.":".$minI.":00", $oraF.":".$minF.":00", $data['cliente'])){
            $errors .= "<li>Questo ho utente ha già prenotato una sessione per questo orario</li>";
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

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function delete($id)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "DELETE FROM prenotazione_sessione WHERE id = $id";

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function getSessionsOf($id){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM prenotazione_sessione WHERE cliente = ".$id;

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }
}

?>