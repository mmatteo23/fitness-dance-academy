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
            $query = "SELECT corso.id, titolo, descrizione, data_inizio, data_fine, copertina, trainer as trainer_id, utente.nome as trainer_nome, utente.cognome as trainer_cognome FROM corso
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

    public static function isMandatory($data, $key, $name){
        if($data[$key] == "")
            return "<li>Il campo '".$name."' va inserito</li>";
        return "";
    }

    public static function checkRegExp($data, $key, $regEx, $name){
        if($data[$key]!="" && !preg_match($regEx, $data[$key]))
            return "<li>Il campo '".$name."' contiene input non valido</li>";
        return "";
    }

    public static function validator(array $data = NULL)
    {
        $errors = "";
        $errors .=  Corso::isMandatory($data, "titolo", "titolo").
                    Corso::isMandatory($data, "descrizione", "descrizione").
                    Corso::isMandatory($data, "data_inizio", "data di inizio").
                    Corso::isMandatory($data, "data_fine", "data di fine").
                    Corso::isMandatory($data, "trainer", "trainer").
                    Corso::checkRegExp($data, "nome", "/^[a-zA-Z\s-]+$/", "nome").
                    Corso::checkRegExp($data, "cognome", "/^[a-zA-Z\s-]+$/", "cognome").
                    Corso::checkRegExp($data, "email", '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', "e-mail").
                    ($data['telefono']!=""?Corso::checkRegExp($data, "telefono", '/^[0-9]{10}$/', "telefono"):"").
                    (time() < strtotime('+18 years', strtotime($data['data_nascita']))?"<li>Data di nascita troppo recente</li>":"").
                    ($data['password']==$data['Rpassword']?"":"<li>Le due <span xml:lang='en'>password</span> non combaciano</li>").
                    (strlen($data['password'])>=4?"":"<li>La <span xml:lang='en'>password</span> deve avere almeno 4 caratteri</li>");
        if($errors != "")
            return "<ul>".$errors."</ul>";
        return true;
    }

    public function getAllCorsi(array $filters)
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
                WHERE 1=1";

            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields, false);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

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

    public function getCorsiByTrainerId(array $filters, int $trainerId)
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
                WHERE trainer_id =" . $trainerId;
            
            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields, false);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public function getUnregisteredCorsiByUserId(array $filters, int $utenteId)
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

    public function getNumeroIscritti(int $corsoId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT COUNT(cliente) as count FROM iscrizione_corso WHERE corso =".$corsoId;

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $queryResults = $queryResults[0]['count'];
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public function getIscritti(int $corsoId)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM utente WHERE id IN (SELECT cliente FROM iscrizione_corso WHERE corso ='.$corsoId.')";

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