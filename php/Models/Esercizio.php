<?php
require_once(SITE_ROOT . '/php/db.php');
require_once(SITE_ROOT . '/php/utilities.php');
use DB\DBAccess;

class Esercizio {

    protected $filtrable_fields = array("nome", "categoria");

    public function index(array $filters = [])
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM esercizio";
            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function addEsercizio($idScheda, $idEs, $nSerie, $nRip, $pausa){
        
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "INSERT INTO esercizio_scheda (scheda, esercizio, serie, ripetizioni, riposo)
            VALUE ($idScheda, $idEs, $nSerie, $nRip, $pausa)";
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

}

?>