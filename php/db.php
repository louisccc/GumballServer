<?php 
require_once("config.php");
class DB{
    public $dbh;

    public $onlineUser_tableName = "user_online";
    public $onlineDevice_tableName = "device_online";
    public $basicSensorLog_tableName = "basic_sensor_log";
    public $location_tableName = "location";
    public $peopleSensorLog_tableName = "people_presence_log_ext";
    public $windowStateLog_tableName = "window_state_log_ext";
    public $feedbackStatus_tableName = "feedback_repository";
    public $members_tableName = "members";
    public $transportation_tableName = "transportation_log";

    public function __construct()
    {
        $hostname = Config::read('db.host');
        $dbname = Config::read('db.basename');
        $user = Config::read('db.user');
        $password = Config::read('db.password');
        $dsn = 'mysql:host='.$hostname.';dbname='.$dbname;
        try{
            $this->dbh = new PDO($dsn, $user, $password);
        }
        catch(PDOException $e){
        } 
    }

    //getter
    public function getNumberOfOnline(){
        $query = "select * from $this->onlineUser_tableName";
        $result = $this->dbh->query($query);
        $number = $result->rowCount();
        return $number;
    }
    public function getOnlineList(){
        $query = "select * from $this->onlineUser_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0 ){
            $rows = $result->fetchAll();
            return $rows;
        }
        return null;
    }
    public function getAllDevice(){
        $query = "select distinct device_id from $this->basicSensorLog_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            // print_r($rows);
            return $rows; 
        }
        return null;
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
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows; 
        }
        return null;
    }

    public function getFeedbackStatusBy($device_id){
        $query = "select * from $this->feedbackStatus_tableName where device_id=$device_id and if_get=0";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            //print_r($rows);
            return $rows;
        }
        return null;
    }

    public function getFeedbackStatusByUserId($user_id){
        $query = "select * from $this->feedbackStatus_tableName where user_id=$user_id and if_get=0";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            //print_r($rows);
            return $rows;
        }
        return null;
    }


    public function getDeviceIdByIpAddr($ip_addr){
        //echo $ip_addr;
        $query = "select * from $this->onlineUser_tableName where ipaddress='$ip_addr'";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows[0]['session'];
        }
        return null; 
    }
    public function getExistReport(){
        $query = "select id, title, coordinate_x, coordinate_y, created_by, created_at from problems where status = 1";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows;
        }
        return null;

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
    
    public function insertFeedbackStatusByUserId($user_id, $application_id, $type){ 
        $time = $this->getMaxTimeStamp();
        date_default_timezone_set('America/Los_Angeles');
        $date = date('Y-m-d H:i:s', time());
        $diff = strtotime($date) - strtotime($time);
        if($diff > 2){
            $query = "insert into $this->feedbackStatus_tableName (device_id, user_id, application_id, feedback_type) values (-1, $user_id, $application_id, \"$type\")";
            $result = $this->dbh->query($query);
        }
    }

    public function insertNewUser($account, $password){
        if(!$this->checkUserExist($account)){
            $token = md5(uniqid($account, true));
            $pass = md5($password);
            $query = "insert into $this->members_tableName (account, password, token) values (\"$account\", \"$pass\", \"$token\")";
            $result = $this->dbh->query($query);
            return $token;
        }
        else{
            echo "existed";
        }
    }

    public function verifyUser($account, $password){
        if($this->checkUserExist($account)){
            $encrypt_password = md5($password); 
            $query = "select * from $this->members_tableName where account=\"$account\" and password=\"$encrypt_password\"";
            $result = $this->dbh->query($query);
            if($result->rowCount() > 0){
                $row = $result->fetchAll();
                return $row[0];
            }
        }
        return null;
    }

    public function checkUserExist($account){
        $query = "select * from members where account=\"$account\"";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return true;
        }
        else
            return false;
    }

    public function getUserByToken($token){
        $query = "select * from $this->members_tableName where token=\"$token\"";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return $result->fetchAll();
        }
        return null;
    }
    public function getMaxTimeStamp(){
        $query = "select max(created_time) from $this->feedbackStatus_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $num = $result->fetchAll();
            //print_r($num);
            return $num[0][0];
        }
        return null;
    }

    public function updateFixReport($user_id, $report_id){

        $query = "update problems set status=0, updated_by=$user_id, updated_at='NOW()' where id=$report_id";
        $report = $this->dbh->query($query);
    }

    public function updateFeedbackStatusBy($feedback_id){
        $query = "update $this->feedbackStatus_tableName set if_get=1 where feedback_id=$feedback_id";
        $result = $this->dbh->query($query);
    }

    public function assignFeedbackOfDeviceId($feedback_id, $device_id, $user_id){
        $query = "update $this->feedbackStatus_tableName set device_id=$device_id=1 where feedback_id=$feedback_id and user_id=$user_id";
        $result = $this->dbh->query($query);
    }
    
    // actions
    public function updateAndLoginOnlineDeviceList($device_id, $time, $ip_addr){
        $query = "select * from $this->onlineDevice_tableName where session='$device_id'";
        $result = $this->dbh->query($query);

        if($result->rowCount() == 0){
            $query = "INSERT INTO $this->onlineDevice_tableName VALUES('$device_id', '$time', '$ip_addr')";
            $result = $this->dbh->query($query);
        }        
        else{   
            $query = "UPDATE $this->onlineDevice_tableName SET time='$time', ipaddress='$ip_addr' WHERE session = '$device_id'";
            $result = $this->dbh->query($query);
        } 
    }
    
    public function updateAndLoginOnlineUserList($token, $time, $ip_addr){
        $query = "select * from $this->onlineUser_tableName where token='$token'";
        $result = $this->dbh->query($query);

        if($result->rowCount() == 0){
            $query = "INSERT INTO $this->onlineUser_tableName (token, time, ipaddr) VALUES ('$token', '$time', '$ip_addr')";
            $result = $this->dbh->query($query);
        }        
        else{   
            $query = "UPDATE $this->onlineUser_tableName SET time='$time', ipaddr='$ip_addr' WHERE token = '$token'";
            $result = $this->dbh->query($query);
        } 
    }
    public function refreshOnlineUserList($time_check){
        $query = "delete from $this->onlineUser_tableName WHERE time<$time_check";
        $result = $this->dbh->query($query);
        //echo $result;
    } 

    public function refreshOnlineDeviceList($time_check){
        $query = "delete from $this->onlineDevice_tableName WHERE time<$time_check";
        $result = $this->dbh->query($query);
        //echo $result;
    }


    public function insertDataToDatabase($transportation, $user_id){
        if($transportation == null){
            return null;
        }
        $label = $transportation[1][1];
        $trip_id = $this->getSupposeTripId();
        for($i = 4; $i < count($transportation); $i++){
            print_r($transportation[$i]);
            $time_splited = explode(".",$transportation[$i][8]);
            $this->insertDataRowToDatabase($label, $user_id, $trip_id, 
                $transportation[$i][0], $transportation[$i][1], 
                $transportation[$i][2], $transportation[$i][3], 
                $transportation[$i][4], $transportation[$i][5], 
                $transportation[$i][6], $transportation[$i][7], 
                $time_splited[0]);
        }
    }
    public function insertDataRowToDatabase($label, $user_id, $trip_id, $segment, $point, $latitude, $longitude, $altitude, $bearing, $accuracy, $speed, $time){
        $query = "insert into $this->transportation_tableName values (NULL, $user_id, $trip_id, '$label', $segment, $point, $latitude, $longitude, $altitude, $bearing, $accuracy, $speed, \"$time\")";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }
    public function getSupposeTripId(){
        $query = "select count(distinct('trip_id')) from $this->transportation_tableName where 1";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows[0][0];
    }
}

?>
