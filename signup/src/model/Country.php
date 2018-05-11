<?php declare(strict_types = 1);

class Country 
{
	
	private $conn;
	private $tableName = 'country_codes';

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function getAllCountry()
	{
		$query = "SELECT * FROM ".$this->tableName."";

		$stmt = $this->conn->prepare($query);

		if($stmt->execute())
		{
			return $stmt->fetchAll();
		}
		else
		{
			echo 'Error';
		}
	}

}