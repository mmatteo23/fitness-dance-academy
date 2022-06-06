<?php
require_once(SITE_ROOT . '/php/db.php');
require_once(SITE_ROOT . '/php/utilities.php');
use DB\DBAccess;

class Corso {

    protected $filtrable_fields = array("titolo", "descrizione", "data_inizio", "data_fine", "copertina");

    protected $id;
    protected $titolo;
    protected $descrizione;
    protected $data_inizio;
    protected $data_fine;
    protected $copertina;

    public function index(array $filters)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT corso.id, titolo, descrizione, data_inizio, data_fine, copertina, trainer as trainer_id, utente.nome as trainer_nome FROM corso
                INNER JOIN utente ON utente.id = trainer";
            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields, true);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public function create(array $data)
    {

    }

    public function read(int $id)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "SELECT * FROM corso WHERE id = " . $id;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults[0])?$queryResults[0]:NULL;
        }

        return NULL;
    }

    public function update(int $id, array $data)
    {

    }

    public function delete(int $id)
    {

    }

    /******************************************
     * 
     *              UTILITIES
     * 
     *****************************************/

    public function getCorsiByUserId(int $utenteId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT id, titolo, descrizione, data_inizio, data_fine, copertina, trainer_id, trainer_nome FROM
                (
                SELECT corso.id, titolo, descrizione, data_inizio, data_fine, copertina, trainer as trainer_id, utente.nome as trainer_nome FROM corso
                LEFT JOIN utente ON utente.id = trainer
                ) as corsi
                LEFT JOIN iscrizione_corso ON corso = id 
                WHERE cliente =" . $utenteId;

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public function getRegisteredCorsiByUserId(array $filters, int $utenteId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT id, titolo, descrizione, data_inizio, data_fine, copertina, trainer_id, trainer_nome FROM
                (
                SELECT corso.id, titolo, descrizione, data_inizio, data_fine, copertina, trainer as trainer_id, utente.nome as trainer_nome FROM corso
                LEFT JOIN utente ON utente.id = trainer
                ) as corsi
                LEFT JOIN iscrizione_corso ON corso = id 
                WHERE (cliente <> " . $utenteId . " OR cliente IS NULL)";
            
            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields, false);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public function registerUser(int $corsoId, int $utenteId)
    {
        if($this->read($corsoId) != NULL) {
            $connection_manager = new DBAccess();
            $conn_ok = $connection_manager->openDBConnection();

            if($conn_ok){
                $query = "INSERT INTO iscrizione_corso (cliente, corso)
                VALUE (
                    " . $utenteId . ",
                    " . $corsoId . "
                )";

                //echo $query;
                
                $queryResults = $connection_manager->executeQuery($query); 
                $connection_manager->closeDBConnection();
                
                return $queryResults;
            }

            return false;
        }

        return false;
    }

    public function unregisterUser(int $corsoId, int $utenteId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "DELETE FROM iscrizione_corso
            WHERE 
                cliente = '" . $utenteId . "' AND
                corso = '" . $corsoId . "'";

            //echo $query;
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public function isAlreadyRegistered(int $corsoId, int $utenteId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM iscrizione_corso
            WHERE 
                cliente = '" . $utenteId . "' AND
                corso = '" . $corsoId . "'";

            //echo $query;
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return isset($queryResults[0]) ? TRUE : FALSE;
        }

        return false;
    }

}

?>