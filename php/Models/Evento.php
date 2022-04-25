<?php

require_once('php/db.php');
require_once('php/utilities.php');
use DB\DBAccess;

class Evento {

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
            $query = "SELECT * FROM evento";
            // append if there are some filters
            $query .= append_filters($filters, $this->filtrable_fields);

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

}

?>