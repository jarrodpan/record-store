<?php

//include_once("../config.php");

class Database
{
	/**
	@author Jarrod Pan
	*/
	
	public $conn;
	
	public function connect()
	{
		$this->conn = null;
		try
		{
			$this->conn = new PDO("mysql:host=".DB_HN.";port=3306;dbname=".DB_DB, DB_UN, DB_PW);
		}
		catch (PDOException $e)
		{
			echo "Error: ".$e->getMessage();
		}

		return $this->conn;
	}
	
	public function query($q)
	{
		//$st = $this->conn->prepare($q);
		$st = $this->conn->query($q);
		//return $st->fetchObject('Item');
		return $st->fetchAll();
	}
	
	
}

 ?>
