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

	public function getCountryByCountryCode()
	{
		$query = "SELECT * FROM ".$this->tableName." WHERE code =?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->countryCode);

		if ($stmt->execute())
		{
			return $stmt->fetchAll();	
		}
		else 
		{
			return false;
		}

	}

}