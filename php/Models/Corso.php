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
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields);

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

    public function getCorsiByUser(int $utenteId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT corso.id, titolo, descrizione, data_inizio, data_fine, copertina, trainer as trainer_id, utente.nome as trainer_nome FROM corso
                INNER JOIN utente ON utente.id = trainer
                RIGHT JOIN iscrizione_corso ON cliente = utente.id 
                WHERE cliente =" . $utenteId;
                
            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

}

?>