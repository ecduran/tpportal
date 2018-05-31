<?php declare(strict_types = 1);

class Config
{
	private $conn;
	private $tableName = 'config';


	public function __construct($db)
	{
		$this->conn = $db;
	}

	public function getData()
	{
		$query = 'SELECT from_email,from_name,cycler_url FROM '.$this->tableName;

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