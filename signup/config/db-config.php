<?php declare(strict_types = 1);

class Database 
{
	private $host = '173.208.200.238';
	private $dbName = 'tpp_main7';
	private $username = 'tppmain7';
	private $password = '83x0Gew';
	private $charset = 'utf8';

	public $conn;

	public function getConnection()
	{
		$this->conn = null;

		$dsn = "mysql:host=$this->host;dbname=$this->dbName;charset=$this->charset";

		$opt = [
			PDO::ATTR_ERRMODE 				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false
		];

		try 
		{
			$this->conn = new PDO($dsn, $this->username, $this->password, $opt);

			return $this->conn;
		}
		catch(PDOexception $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}
}
