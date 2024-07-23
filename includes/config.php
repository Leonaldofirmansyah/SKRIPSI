<?php
class Config{
	/* Letak Folder Project Berada */
	public $folder = ''; // if this project is on the webroot, leave it blank
	/* Config Connection Database */
	private $host = "localhost";
	private $db_name = "skripsi_leo";
	private $username = "root";
	private $password = "";
	public $conn;
	/* Config Site */
	public $title = "skripsi_leo"; // this title your website

	public function getConnection(){
	
		$this->conn = null;
		
		try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
		}catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}
		
		return $this->conn;
	}

	public function link($option = '')
	{
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/".$option;
	}
}
?>