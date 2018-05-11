<?php declare(strict_types = 1);


class Openerp()
{
	private $conn;
	private $tableName = 'openerp_coa';


	public function __construct($db)
	{
		$this->conn;
	}

	public function getCountryCode()
	{
		$query = 'SELECT country_code FROM ' .$this->tableName;

		$stmt = $this->conn->prepare($query);

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