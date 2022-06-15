<?php

require_once(SITE_ROOT . "/php/db.php");

use DB\DBAccess;

class Sessione {
    
    private static function isBusy($date, $timeI, $timeF, $clientID){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM prenotazione_sessione 
                        WHERE cliente = $clientID AND data = '$date'
                        AND ((ora_inizio <= '$timeI' AND ora_fine >= '$timeI') 
                           OR(ora_inizio <= '$timeF' AND ora_fine >= '$timeF')
                           OR(ora_inizio >= '$timeF' AND ora_fine <= '$timeF'))";

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            if(count($queryResults) > 0){
                return true;
            }
        }
        return false;
    }

    public static function gymIsClosed($date, $time, $time2){
        $dow = date('w', $date);
        if($dow == 0){
            return "<li>La palestra è chiusa di domenica</li>";
        }
        if($dow === 6 && 
          ($time < strtotime("11:00") || $time > strtotime("16:30") ||
          $time2 < strtotime("11:00") || $time2 > strtotime("16:30"))){
            return "<li>La palestra apre dalle 11:00 alle 16:30 di sabato</li>";
        }
        if($time < strtotime("10:00") || $time > strtotime("22:30") ||
          $time2 < strtotime("11:00") || $time2 > strtotime("22:30")){
            return "<li>La palestra apre dalle 10:00 alle 22:30 dal lunedì al venerdì</li>";
        }
        return false;
    }

    public static function validator(array $data)
    {
        $errors = "";
        $date = strtotime($data['data']);
        $giorno = date('d', $date);
        $mese = date('m', $date);
        $time = strtotime($data['ora_inizio']);
        $oraI = date('H', $time);
        $minI = date('i', $time);
        $time2 = strtotime($data['ora_fine']);
        $oraF = date('H', $time2);
        $minF = date('i', $time2);
        if(date('y', $date) < date("y") || $mese < date("m") || $mese == date("m") && $giorno < date("d")){
            $errors .= "<li>Non puoi andare indietro nel tempo</li>";
        }
        if($mese == date("m") && $giorno == date("d") && ($oraI < date("h") || $oraI == date("h") && $minI < date("m"))){
            $errors .= "<li>Il momento è passato</li>";
        }
        if($oraF < $oraI || $oraF == $oraI && $minF <= $minI){
            $errors .= "<li>La sessione non può finire prima che inizi</li>";
        }
        if(Sessione::isBusy(date("Y", $date)."-".$mese."-".$giorno, $oraI.":".$minI.":00", $oraF.":".$minF.":00", $data['cliente'])){
            $errors .= "<li>Questo ho utente ha già prenotato una sessione per questo orario</li>";
        }
        $errors .= Sessione::gymIsClosed($date, $time, $time2);
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
                '" . ($data['data']) . "',
                '" . $data['ora_inizio'] . ":00',
                '" . $data['ora_fine'].":00',
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
            $query = "SELECT * FROM prenotazione_sessione WHERE cliente = ".$id." AND data>DATE_SUB(CURDATE(), INTERVAL 1 DAY) ORDER BY data";

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function countPrenotati(array $data){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $year = date("Y");
            $date = $year."-".$data['meseSessione']."-".$data['giornoSessione'];
            $timeI = $data['oraInizio'].":".$data['minutoInizio'].":00";
            $timeF = $data['oraFine'].":".$data['minutoFine'].":00";
            $query = "SELECT COUNT(*) AS n FROM prenotazione_sessione 
                WHERE data='".$date."' AND 
                ((ora_inizio <= '$timeI' AND ora_fine >= '$timeF') 
                OR(ora_inizio <= '$timeF' AND ora_fine >= '$timeF')
                OR(ora_inizio >= '$timeI' AND ora_fine <= '$timeF'))" ;

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults[0]['n'];
        }

        return false;
    }

    public static function getSessioniByGiorno($giorno) {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "SELECT 
                        -- per togliere i secondi
                        DATE_FORMAT(sessione.ora_inizio,'%k:%i') as `ora_inizio`,
                        DATE_FORMAT(sessione.ora_fine,'%k:%i') as `ora_fine`,
                        utente.nome,
                        utente.cognome,
                        utente.email,
                        utente.telefono
                    FROM 
                        prenotazione_sessione AS sessione
                    JOIN (
                        SELECT 
                            id, nome, cognome, email, telefono
                        FROM 
                            utente
                    ) AS utente
                    ON 
                        sessione.cliente=utente.id
                    WHERE
                        sessione.data='$giorno'
                    ORDER BY
                        sessione.ora_inizio";
            // echo($query);

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }
}

?>