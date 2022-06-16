<?php
require_once(SITE_ROOT . '/php/db.php');
use DB\DBAccess;

class Utente {

    protected $id;
    protected $nome;
    protected $cognome;
    protected $email;
    protected $data_nascita;
    protected $password;
    protected $telefono;
    protected $sesso;
    protected $foto_profilo;
    protected $ruolo;
    protected $altezza;
    protected $peso;

    // GET METHODS
    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getCognome()
    {
        return $this->cognome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDataNascita()
    {
        return $this->data_nascita;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getSesso()
    {
        return $this->sesso;
    }

    public function getFotoProfilo()
    {
        return $this->foto_profilo;
    }

    public function getRuolo()
    {
        return $this->ruolo;
    }

    public function getAltezza()
    {
        return $this->altezza;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public static function getTrainers(){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM utente WHERE ruolo = 2";
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

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
        $errors .=  Utente::isMandatory($data, "nome", "nome").
                    Utente::isMandatory($data, "cognome", "cognome").
                    Utente::isMandatory($data, "data_nascita", "data di nascita").
                    Utente::isMandatory($data, "email", "e-mail").
                    Utente::isMandatory($data, "password", "password").
                    Utente::isMandatory($data, "Rpassword", "ripeti password").
                    Utente::checkRegExp($data, "nome", "/^[a-zA-Z\s-]+$/", "nome").
                    Utente::checkRegExp($data, "cognome", "/^[a-zA-Z\s-]+$/", "cognome").
                    Utente::checkRegExp($data, "email", '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', "e-mail").
                    ($data['telefono']!=""?Utente::checkRegExp($data, "telefono", '/^[0-9]{10}$/', "telefono"):"").
                    (time() < strtotime('+18 years', strtotime($data['data_nascita']))?"<li>Data di nascita non corrispondente a persona maggiorenne</li>":"").
                    ($data['password']==$data['Rpassword']?"":"<li>Le due <span xml:lang='en'>password</span> non combaciano</li>").
                    ($data['sesso']=="M"||$data['sesso']=="F"?"":"<li>Il sesso deve essere F per femmina o M per maschio</li>").
                    (strlen($data['password'])>=4?"":"<li>La <span xml:lang='en'>password</span> deve avere almeno 4 caratteri</li>");
        if($errors != "")
            return "<ul>".$errors."</ul>";
        return true;
    }
    
    public static function index(array $filters) {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT * FROM utente";
            // append if there are some filters
            if(count($filters)) $query .= append_filters($filters, $this->filtrable_fields);

            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?$queryResults:NULL;
        }

        return NULL;
    }

    public static function getNewId() {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "SELECT id FROM utente ORDER BY id DESC";
            //echo $query;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults)?($queryResults[0]['id']+1):1;
        }

        return NULL;
    }

    // CRUD OPERATIONS
    public static function create(array $data)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "INSERT INTO utente (id, nome, cognome, email, data_nascita, password, telefono, sesso, foto_profilo, ruolo, altezza, peso)
            VALUE (
                '" . $data['id'] . "',
                '" . $data['nome'] . "',
                '" . $data['cognome'] . "',
                '" . $data['email'] . "',
                '" . $data['data_nascita'] . "',
                '" . $data['password'] . "',
                '" . ($data['telefono']?:NULL) . "',
                '" . $data['sesso'] . "',
                '" . $data['foto_profilo'] . "',
                " . $data['ruolo'] . ",
                '" . ($data['altezza']?:NULL) . "',
                '" . ($data['peso']?:NULL) . "'
            )";
            echo($query);
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function read(int $id)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "SELECT * FROM utente WHERE id = " . $id;
            $queryResults = $connection_manager->executeQuery($query);
            $connection_manager->closeDBConnection();

            return isset($queryResults[0])?$queryResults[0]:NULL;
        }

        return NULL;
    }

    public function update(int $id, array $data)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "UPDATE utente SET 
                nome = '" . $data['nome'] . "', 
                cognome = '" . $data['cognome'] . "',
                data_nascita = '" . $data['data_nascita'] . "',
                password = '" . $data['password'] . "',
                telefono = '" . ($data['telefono']?:NULL) . "',
                sesso = '" . $data['sesso'] . "',
                foto_profilo = '" . $data['foto_profilo'] . "',
                ruolo = " . $data['ruolo'] . ",
                altezza = '" . ($data['altezza']?:NULL) . "',
                peso = '" . ($data['peso']?:NULL) . "'
                
                WHERE id = " . $id;
                
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public function delete(int $id)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "DELETE FROM utente WHERE id = " . $id;
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public static function getIdFromEmail($email){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "SELECT id FROM utente WHERE email = '".$email."'";
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults[0]['id'];
        }

        return false;
    }

    public static function getRole($id){
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){

            $query = "SELECT ruolo FROM utente WHERE id = ".$id;
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults[0]['ruolo'];
        }

        return false;
    }

    public static function isAdmin($userId) {
        $userData = Utente::read($userId);
        if ($userData) {
            if ($userData['ruolo'] == 1)
                return true;
            else
                return false;
        }

        return false;
    }

    public static function isTrainer($userId) {
        $userData = Utente::read($userId);
        if($userData) {
            if ($userData['ruolo'] == 2)
                return true;
            else
                return false;
        }

        return false;
    }

    public static function isCliente($userId) {
        $userData = Utente::read($userId);
        if ($userData) {
            if ($userData['ruolo'] == 3)
                return true;
            else
                return false;
        }

        return false;
    }
}