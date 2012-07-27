<?php 
require_once("config.php");
class DB{
	public $dbh;

	public $onlineUserTableName = "user_online";

	public function __construct()
	{
		$hostname = Config::read('db.host');
		$dbname = Config::read('db.basename');
		$user = Config::read('db.user');
		$password = Config::read('db.password');
		$dsn = 'mysql:host='.$hostname.';dbname='.$dbname;
		$this->dbh = new PDO($dsn, $user, $password);
	}


	public function getNumberOfOnline(){
		$query = "select * from user_online";
		$result = $this->dbh->query($query);
		$number = $result->rowCount();
		return $number;
	}

	// actions
	public function updateAndLoginOnlineList($device_id, $time){
		$query = "select * from user_online where session='$device_id'";
		$result = $this->dbh->query($query);

		//echo $stmt->rowCount()."<br>";

		if($result->rowCount() == 0){
			$query = "INSERT INTO user_online VALUES('$device_id', '$time')";
			$result = $this->dbh->query($query);
		}        
		else{   
			$query = "UPDATE user_online SET time='$time' WHERE session = '$device_id'";
			$result = $this->dbh->query($query);
		} 
	}
	public function refreshOnlineList($time_check){
		$query = "delete from user_online WHERE time<$time_check";
		$result = $this->dbh->query($query);
		//echo $result;
	}

}

?>
