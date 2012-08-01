<?php 
require_once("config.php");
class DB{
    public $dbh;

    public $onlineUser_tableName = "user_online";
    public $basicSensorLog_tableName = "basic_sensor_log";
    public $location_tableName = "location";
    public $peopleSensorLog_tableName = "people_presence_log_ext";
    public $windowStateLog_tableName = "window_state_log_ext";
    public $feedbackStatus_tableName = "feedback_repository";


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
        $query = "select * from $this->onlineUser_tableName";
        $result = $this->dbh->query($query);
        $number = $result->rowCount();
        return $number;
    }
    public function getAllDevice(){
        $query = "select distinct device_id from $this->basicSensorLog_tableName";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        //print_r($rows);
        return $rows; 
    }
    public function getNewestDataOf($device_id){
        $query = "select * from $this->basicSensorLog_tableName where device_id = $device_id order by created_time DESC limit 1";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        //print_r($rows);
        return $rows;
    }

    public function getWindowStateBy($log_id){
        $query = "select * from $this->window_state_log_ext where log_id = $log_id limit 1";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }
    
    public function getPeopleStateBy($log_id){
        
    }

    public function getFeedbackStatusBy($device_id){
        $query = "select * from $this->feedbackStatus_tableName where device_id=$device_id and if_get=0";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        //   print_r($rows);
        return json_encode($rows);
    }

    public function insertFeedbackStatusBy($device_id, $application_id, $type){
        $query = "insert into $this->feedbackStatus_tableName (device_id, application_id, feedback_type) values ($device_id, $application_id, \"$type\")";
        $result = $this->dbh->query($query);
    }

    public function updateFeedbackStatusBy($feedback_id){
        $query = "update $this->feedbackStatus_tableName set if_get=1 where feedback_id=$feedback_id";
        $result = $this->dbh->query($query);
    }



    // actions
    public function updateAndLoginOnlineList($device_id, $time){
        $query = "select * from $this->onlineUser_tableName where session='$device_id'";
        $result = $this->dbh->query($query);

        if($result->rowCount() == 0){
            $query = "INSERT INTO $this->onlineUser_tableName VALUES('$device_id', '$time')";
            $result = $this->dbh->query($query);
        }        
        else{   
            $query = "UPDATE $this->onlineUser_tableName SET time='$time' WHERE session = '$device_id'";
            $result = $this->dbh->query($query);
        } 
    }
    public function refreshOnlineList($time_check){
        $query = "delete from $this->onlineUser_tableName WHERE time<$time_check";
        $result = $this->dbh->query($query);
        //echo $result;
    } 

}

?>
