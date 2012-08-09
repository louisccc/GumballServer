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

    //getter
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
        // print_r($rows);
        return $rows; 
    }
    public function getNewestDataOf($device_id){
        $query = "select * from $this->basicSensorLog_tableName where device_id = $device_id order by created_time DESC limit 1";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
        $rows = $result->fetchAll();
        //print_r($rows);
        return $rows;
        }
        return null;
    }

    public function getWindowStateBy($log_id){
        $query = "select * from $this->windowStateLog_tableName where log_id = $log_id";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            //print_r($rows);
            return $rows;
        }
        return null;
    }

    public function getPeopleStateBy($log_id){
        $query = "select * from $this->peopleSensorLog_tableName where log_id = $log_id";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows; 
    }

    public function getFeedbackStatusBy($device_id){
        $query = "select * from $this->feedbackStatus_tableName where device_id=$device_id and if_get=0";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            //print_r($rows);
            return json_encode($rows);
        }
        return null;
    }

    public function getDeviceIdByIpAddr($ip_addr){
        //echo $ip_addr;
        $query = "select * from $this->onlineUser_tableName where ipaddress='$ip_addr'";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();

        return $rows[0]['session'];
    }
    public function getExistReport(){
        $query = "select id, title, coordinate_x, coordinate_y, created_by, created_at from problems where status = 1";
        $result = $this->dbh->query($query);

        $rows = $result->fetchAll();
        return $rows;

    }
    public function insertFixReport($title, $coor_x, $coor_y, $user_id){
        $query = "insert into problems values( NULL, '".$title. "','" .$title. "'," .$coor_x. "," .$coor_y. "," .$user_id. ", 1, NOW(), NOW()," .$user_id. ");";
        $result = $this->dbh->query($query);

        //echo $result;
    }
    public function insertSensorBasic($device_id, $light, $temp, $sound){

        $query = "insert into $this->basicSensorLog_tableName values( NULL, $device_id, $light, $temp, $sound, NOW())";
        $result = $this->dbh->query($query);

        $insert_id = $this->dbh->lastInsertId();
        return $insert_id;

    }
    public function insertWindowState($log_id, $window_state){
        $query = "insert into $this->windowStateLog_tableName values(".$log_id.",".$window_state.")";
        $result = $this->dbh->query($query);
    }
    public function insertPeopleState($log_id, $people_state){
        $query = "insert into $this->peopleSensorLog_tableName values($log_id, $people_state)";
        $result = $this->dbh->query($query);
    }

    public function insertFeedbackStatusBy($ip_addr, $application_id, $type){ 
        $device_id = $this->getDeviceIdByIpAddr($ip_addr);
        $query = "insert into $this->feedbackStatus_tableName (device_id, application_id, feedback_type) values ($device_id, $application_id, \"$type\")";
        $result = $this->dbh->query($query);
    }

    // mostly for sensor scenario need conjection control
    public function insertFeedbackStatusByDeviceId($device_id, $application_id, $type){ 
        $time = $this->getMaxTimeStamp();
        date_default_timezone_set('America/Los_Angeles');
        $date = date('Y-m-d H:i:s', time());
        $diff = strtotime($date) - strtotime($time);
        if($diff > 2){
            $query = "insert into $this->feedbackStatus_tableName (device_id, application_id, feedback_type) values ($device_id, $application_id, \"$type\")";
            $result = $this->dbh->query($query);
        }
    }

    public function getMaxTimeStamp(){
        $query = "select max(created_time) from $this->feedbackStatus_tableName";
        $result = $this->dbh->query($query);
        $num = $result->fetchAll();
        //print_r($num);
        return $num[0][0];

    }

    public function updateFixReport($user_id, $report_id){

        $query = "update problems set status=0, updated_by=$user_id, updated_at='NOW()' where id=$report_id";
        $report = $this->dbh->query($query);
    }

    public function updateFeedbackStatusBy($feedback_id){
        $query = "update $this->feedbackStatus_tableName set if_get=1 where feedback_id=$feedback_id";
        $result = $this->dbh->query($query);
    }

    // actions
    public function updateAndLoginOnlineList($device_id, $time, $ip_addr){
        $query = "select * from $this->onlineUser_tableName where session='$device_id'";
        $result = $this->dbh->query($query);

        if($result->rowCount() == 0){
            $query = "INSERT INTO $this->onlineUser_tableName VALUES('$device_id', '$time', '$ip_addr')";
            $result = $this->dbh->query($query);
        }        
        else{   
            $query = "UPDATE $this->onlineUser_tableName SET time='$time', ipaddress='$ip_addr' WHERE session = '$device_id'";
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
