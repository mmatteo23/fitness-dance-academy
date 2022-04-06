<?php

require_once('db.php');
use DB\DBAccess;

/**
 * @brief getUserData()		returns the user data from username
 * @return 	array()			if user exists it returns an array with user data
 * @return  NULL			if user doesn't exist it returns null
 */
function getUser(int $id) {
    $connection_manager = new DBAccess();
    $conn_ok = $connection_manager->openDBConnection();

    if($conn_ok){
        $query = "SELECT * FROM utente
                WHERE id = $id LIMIT 1";

        $queryResults = $connection_manager->executeQuery($query); 
        $connection_manager->closeDBConnection();
        if(isset($queryResults[0]['id']))
            return $queryResults[0];
        return false;
    }
}


?>
