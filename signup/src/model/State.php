<?php declare(strict_types = 1);


class State
{
	private $conn;
	private $tableName = 'statedb';

	public $stateCode;
	public $countryCode;
	public $countryID;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function getAllStateByCountryCode()
	{
		$query = "SELECT * FROM ".$this->tableName." WHERE country_code like ? AND state = ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->countryCode);
		$stmt->bindParam(2, $this->stateCode);

		if ($stmt->execute())
		{
			return $stmt->fetch(PDO::FETCH_ASSOC);	
		}
		else 
		{
			return false;
		}		
	}
}