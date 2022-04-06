<?php

require_once('db.php');
use DB\DBAccess;


/**
 * @brief authentication(string, string)		check if an user with these credentials exists
 * @return 	TRUE			the user exist 
 * 			FALSE			the user isn't saved in our server
 */
function authentication(string $email, string $password) {
    $connection_manager = new DBAccess();
    $conn_ok = $connection_manager->openDBConnection();

    if($conn_ok){
        $query = "SELECT * FROM utente
                WHERE email = '$email' AND
                password = '$password'";

        $queryResults = $connection_manager->executeQuery($query); 
        $connection_manager->closeDBConnection();
        if(!$queryResults)
            return false;
        
        if(count($queryResults) == 0) { // usare gli if in modo efficiente, la cpu elabora velocemente i branch positivi, perché in caso di ramo else deve fare il rollback di quello che ha fatto e prendere l'altro ramo => mettere in esito positivo sempre i rami che sono piú probabili!
            return false;
        }

        return true;
    }

    return false;
    
}


?>