<?php

namespace DB;
use \Datetime;

class DBAccess {
	private const USER = 'root';
	private const PASSWD = 'mariadb';
	private const HOST = '127.0.0.1';
	private const DATABASE = 'mariadb';
	private const PORT = '3306';

	private $connection;

	public function openDBConnection() {
		$this->connection = mysqli_connect(DBAccess::HOST, 
										   DBAccess::USER, 
										   DBAccess::PASSWD,
										   DBAccess::DATABASE);
		if(mysqli_errno($this->connection)){
			return false;
		} else {
			return true;
		}
	}

	public function closeDBConnection() {
		mysqli_close($this->connection);
	}

	public function escape_string(string $str) {
		return mysqli_real_escape_string($this->connection, $str);
	}

	/*****************************************************************
	 * 
	 * 	ATTENZIONE!!! MODIFICARE PRIMA DI METTERE IN PRODUZIONE
	 * 
	 ****************************************************************/
	/**
	 * @brief executeQuery(string)	it executes the passed query and return the result
	 * @return key/id				INSERT: returns the id/key that identifies the created record.
	 * @return array()				SELECT:	returns an array with the db records extracted
	 * @return TRUE					UPDATE/DELETE: the query updates the record successfully
	 * @return FALSE				default: the query was wrong								 
	 * 
	 ****************************************************************/
	public function executeQuery(string $query){
		
		$queryResult = mysqli_query($this->connection, $query);

		if($queryResult) {
			if(mysqli_insert_id($this->connection))			// INSERT QUERY
				return mysqli_insert_id($this->connection);

			if($queryResult === TRUE){ // UPDATE o DELETE
				return TRUE;
			}

			// SELECT
			$result = array();
			while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
				array_push($result, $row);
			}
			$queryResult->free();
			return $result;
		}
		return $queryResult;	// FALSE => Query failed
	}

	public function getNewId(string $tableName){
		$query = "SELECT id FROM " . $tableName . " ORDER BY id DESC LIMIT 1 ";

		$newId = $this->executeQuery($query);
		return $newId ? $newId[0]['id']+1 : 1;
	}
}

?>