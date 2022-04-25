<?php

require_once('php/db.php');
require_once('php/utilities.php');
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