<?php declare(strict_types = 1);


class Affiliate 
{

	private $conn;
	private $tableName = 'affiliate';

	public $sponsorScreenID; //sponsor_username
	public $screenID; // screenID
	public $signupDate; //signup_date
	public $companyName; // company_name
	public $companyType; // company_type
	public $title;	// title
	public $firstName; // first_name
	public $middleName; // middle_name
	public $lastName; // last_name
	public $gender; // gender
	public $emailAdd; // email
	public $username; // username
	public $password; // password - hashed 
	public $password1; //password1 - unhashed
	public $affiliateType; //customer type column 
	public $ipAdd; // ipadd
	public $token;
	public $country;
	public $lastUpdate;

	public function __construct($db)
	{
		$this->conn = $db;
	}
	
	public function getNumRowByScreenID()
	{
		$query = "SELECT * FROM " .$this->tableName. " WHERE screenid = ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->screenID);

		if ($stmt->execute())
		{
			return $stmt->rowCount();	
		}
		else 
		{
			return false;
		}
	}

	public function getDataByScreenID()
	{
		$query = "SELECT * FROM " .$this->tableName. " WHERE screenid like ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->screenID);

		if ($stmt->execute())
		{
			return $stmt->fetchAll();
		}
		else
		{
			return false; 
		}

	}

	public function getDataByUsername()
	{
		$query = "SELECT * FROM " .$this->tableName. " WHERE username like ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->username);

		if($stmt->execute())
		{	
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}	
		else
		{
			return false;
		}
	}

	public function checkEmailAdd()
	{
		$query = "SELECT * FROM " .$this->tableName. " WHERE email = ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->emailAdd);

		if ($stmt->execute())
		{
			return $stmt->rowCount();	
		}
		else 
		{
			return false;
		}
	}

	 public function createAffiliate()
	 {
	 	$query = "INSERT INTO " .$this->tableName. " (screenid, company_name, company_type, title, first_name, middle_name, last_name, gender, username, password, password1, email, type, sponsor_username,token, signup_date, ip_address, country, last_update) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	 	$stmt = $this->conn->prepare($query);

	 	$stmt->bindParam(1, $this->screenID);
	 	$stmt->bindParam(2, $this->companyName);
	 	$stmt->bindParam(3, $this->companyType);
	 	$stmt->bindParam(4, $this->title);	 	
	 	$stmt->bindParam(5, $this->firstName);
	 	$stmt->bindParam(6, $this->middleName);
	 	$stmt->bindParam(7, $this->lastName);
	 	$stmt->bindParam(8, $this->gender);
	 	$stmt->bindParam(9, $this->username);
	 	$stmt->bindParam(10, $this->password);
	 	$stmt->bindParam(11, $this->password1);
	 	$stmt->bindParam(12, $this->emailAdd);
	 	$stmt->bindParam(13, $this->affiliateType);
	 	$stmt->bindParam(14, $this->sponsorScreenID);
	 	$stmt->bindParam(15, $this->token);
	 	$stmt->bindParam(16, $this->signupDate);
	 	$stmt->bindParam(17, $this->ipAdd);
	 	$stmt->bindParam(18, $this->country);
	 	$stmt->bindParam(19, $this->lastUpdate);

	 	if ($stmt->execute())
	 	{
	 		return true;
	 	}
	 	else
	 	{
	 		return false;
	 	}
	 }

	 public function copyData()
	 {
	 	$query = "INSERT INTO `affiliate_history`(`screenid`, `last_update`, `company_name`, `company_type`, `title`, `first_name`, `middle_name`, `last_name`, `gender`, `confirm`, `email_attempts`, `id`, `username`, `password`, `password1`, `email`, `type`, `video_watched`, `void`, `tpp_comm_percent`, `tmp_comm_percent`, `ipif_comm_percent`, `bonus_reward_percent`, `bonus_qualified`, `bonus_7day_yes_rate`, `bonus_7day_no_rate`, `bonus_qualified_level`, `bonus_7day_sponsored_id1`, `bonus_amount1`, `bonus_7day_sponsored_id2`, `bonus_amount2`, `bonus_7day_sponsored_id3`, `bonus_amount3`, `sponsored_id`, `total_sponsored`, `level1_bonus`, `sponsored_level1`, `level2_bonus`, `sponsored_level2`, `level3_bonus`, `sponsored_level3`, `level4_bonus`, `sponsored_level4`, `level5_bonus`, `sponsored_level5`, `level6_bonus`, `sponsored_level6`, `level7_bonus`, `sponsored_level7`, `level8_bonus`, `sponsored_level8`, `level9_bonus`, `sponsored_level9`, `level10_bonus`, `sponsored_level10`, `level11_bonus`, `sponsored_level11`, `level12_bonus`, `sponsored_level12`, `total_bonus`, `bonus_paid`, `bonus_balance`, `paypal`, `stpay_id`, `master_sponsor_username`, `master_sponsor_id`, `master_sponsor_order`, `sponsor_username`, `sponsor_id1`, `sponsor_id2`, `sponsor_id3`, `sponsor_id4`, `sponsor_id5`, `sponsor_id6`, `sponsor_id7`, `sponsor_id8`, `sponsor_id9`, `sponsor_id10`, `sponsor_id11`, `sponsor_id12`, `presenter_id1`, `presenter_id2`, `presenter_id3`, `presenter_id4`, `presenter_id5`, `amount_owed`, `hold_funds`, `address`, `unit`, `city`, `state`, `country`, `zip`, `ship_method`, `ship_to`, `ship_address`, `ship_unit`, `ship_city`, `ship_state`, `ship_country`, `ship_zip`, `phone_home_cnt`, `phone_home`, `phone_mobile_cnt`, `phone_mobile`, `phone_office_cnt`, `phone_office`, `phone_office_ext`, `question`, `answer`, `birth_date`, `alt_1_username`, `alt_2_username`, `alt_3_username`, `alt_4_username`, `alt_5_username`, `alt_6_username`, `alt_7_username`, `alt_8_username`, `alt_9_username`, `alt_10_username`, `alt_11_username`, `alt_12_username`, `alt_13_username`, `alt_14_username`, `alt_15_username`, `token`, `signup_date`, `jv_date`, `jv_security_code`, `jv_path`, `jv_filename`, `renewal_date`, `agreed_ip`, `kyc_approved`, `kyc_approved_by`, `kyc_approved_date`, `terminated_datetime`, `termination_reason`, `t_first_name`, `t_last_name`, `terminated_by`, `ip_address`, `flyer`, `brochure`, `cards`, `email_status`, `change_date`, `email_exclude`, `bulk_messaging`) SELECT * FROM " .$this->tableName. " WHERE username like ?" ;

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->username);
		if($stmt->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	 }
}