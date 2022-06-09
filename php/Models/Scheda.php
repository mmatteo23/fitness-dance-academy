<?php

require_once(SITE_ROOT . '/php/db.php');
require_once(SITE_ROOT . '/php/utilities.php');
use DB\DBAccess;

class Scheda {

    protected $filtrable_fields = array("data", "cliente", "trainer");

    protected $id;
    protected $data;
    protected $cliente;
    protected $trainer;

    public function index(array $filters)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM scheda";
            // append if there are some filters
            $query .= append_filters($filters, $this->filtrable_fields);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function prenotazionePendente($userID){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM prenotazione_scheda WHERE cliente = $userID";

            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            if(count($queryResults) > 0)
                return true;
        }

        return false;
    }

    public static function creaPrenotazione(array $data){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "INSERT INTO prenotazione_scheda (cliente, trainer, data)
            VALUE (
                " . $data['cliente'] . ",
                " . $data['trainerScheda'] . ",
                '" . date("yyyy-mm-dd hh:mm:ss") . "'
            )";
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public function create(array $data)
    {

    }

    public function read(int $id)
    {

    }

    public function update(int $id, array $data)
    {

    }

    public function delete(int $id)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $year = date("Y");
            $query = "DELETE FROM prenotazione_scheda WHERE id = ".$id;

            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function getEserciziById($id){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = 
                "SELECT esercizio.id AS id, nome, categoria, serie, ripetizioni, riposo 
                 FROM esercizio_scheda INNER JOIN esercizio ON esercizio = esercizio.id WHERE scheda = ".$id;

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function getSchedeByUtente(int $id){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = 
                "SELECT scheda.id as id, data, CONCAT(nome, ' ', cognome) AS trainer FROM scheda 
                INNER JOIN utente on trainer = utente.id 
                WHERE cliente = ".$id;

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function getSchedeRichieste($id){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = 
                "SELECT prenotazione_scheda.id as id, data, CONCAT(nome, ' ', cognome) AS utente FROM prenotazione_scheda 
                INNER JOIN utente on cliente = utente.id 
                WHERE trainer = ".$id;

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function getAllSchedeRichieste(){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = 
                "SELECT prenotazione_scheda.id as id, data, CONCAT(nome, ' ', cognome) AS utente FROM prenotazione_scheda 
                INNER JOIN utente on cliente = utente.id";

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

}

?>