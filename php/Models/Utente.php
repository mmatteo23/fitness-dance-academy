<?php

require_once('php/db.php');
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
    /*
    // SET METHODS
    public function setId(int $_id)
    {
        $this->id = $_id;
    }

    public function setNome(string $_nome)
    {
        $this->nome = $_nome;
    }

    public function setCognome(string $_cognome)
    {
        $this->cognome = $_cognome;
    }

    public function setEmail(string $_email)
    {
        $this->email = $_email;
    }

    public function setDataNascita(string $_data_nascita)
    {
        $this->data_nascita = $_data_nascita;
    }

    public function setPassword(string $_password)
    {
        $this->password = $_password;
    }

    public function setTelefono(string $_telefono)
    {
        $this->telefono = $_telefono;
    }

    public function setSesso(char $_sesso)
    {
        $this->sesso = $_sesso;
    }

    public function setFotoProfilo(string $_foto_profilo)
    {
        $this->foto_profilo = $_foto_profilo;
    }

    public function setRuolo(string $_ruolo)
    {
        $this->ruolo = $_ruolo;
    }

    public function setAltezza(string $_altezza)
    {
        $this->altezza = $_altezza;
    }

    public function setPeso(string $_peso)
    {
        $this->peso = $_peso;
    }
    */

    public static function validator(array $data = NULL)
    {
        $errors = "";
        if($data['nome']==""){
            $errors .= "<li>Il campo 'nome' va inserito</li>";
        }
        if($data['cognome']==""){
            $errors .= "<li>Il campo 'cognome' va inserito</li>";
        }
        if($data['nome']==""){
            $errors .= "<li>Il campo 'nome' va inserito</li>";
        }
        return true;
    }

    // CRUD OPERATIONS
    public static function create(array $data)
    {
        $connection_manager = new DBAccess();
        $conn_ok = $connection_manager->openDBConnection();

        if($conn_ok){
            $query = "INSERT INTO utente (nome, cognome, email, data_nascita, password, telefono, sesso, foto_profilo, ruolo, altezza, peso)
            VALUE (
                '" . $data['nome'] . "',
                '" . $data['cognome'] . "',
                '" . $data['email'] . "',
                '" . $data['dataNascita'] . "',
                '" . $data['password'] . "',
                '" . $data['telefono'] . "',
                '" . $data['sesso'] . "',
                '" . $data['foto_profilo'] . "',
                " . $data['ruolo'] . ",
                " . $data['altezza'] . ",
                " . $data['peso'] . "
            )";
            
            $queryResults = $connection_manager->executeQuery($query); 
            $connection_manager->closeDBConnection();
            
            return $queryResults;
        }

        return false;
    }

    public function read(int $id)
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
                telefono = '" . $data['telefono'] . "',
                sesso = '" . $data['sesso'] . "',
                foto_profilo = '" . $data['foto_profilo'] . "',
                ruolo = " . $data['ruolo'] . ",
                altezza = " . $data['altezza'] . ",
                peso = " . $data['peso'] . "
                
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
}