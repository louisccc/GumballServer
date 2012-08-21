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

    ###
    ### stuff with online list of user and device
    ###
    ### get the number of online user 
    public function getNumberOfOnlineUser(){
        if(($result = $this->getOnlineUserList())!= null){
            return count($result);
        }
        return 0;
    }
    ### get the number of online device
    public function getNumberOfOnlineDevice(){
        if(($result = $this->getOnlineDeviceList()) != null){
            return count($result);
        }
        return 0;
    }
    ### get the detail stuff of online user 
    public function getOnlineUserList(){
        $query = "select * from $this->onlineUser_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0 ){
            return $result->fetchAll();
        }
        return null;
    }
    ### get the detail stuff of online device
    public function getOnlineDeviceList(){
        $query = "select * from $this->onlineDevice_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0 ){
            return $result->fetchAll();
        }
        return null;
    }
    ### get the list of all differenct device_id from log table
    public function getAllDevice(){
        $query = "select distinct device_id from $this->basicSensorLog_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return $result->fetchAll();
        }
        return null;
    }
    public function checkIsDeviceOnline($device_id){
        $query = "select * from $this->onlineDevice_tableName where session = \"$device_id\"";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return true;
        }
        return false;
    }
    
    ### get the newest data row of 3-sensor data table using device_id
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
    ### get the arbitrary data row by log_id
    public function getWindowStateBy($log_id){
        $query = "select * from $this->windowStateLog_tableName where log_id = $log_id";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return $result->fetchAll();
        }
        return null;
    }
    public function getPeopleStateBy($log_id){
        $query = "select * from $this->peopleSensorLog_tableName where log_id = $log_id";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return $result->fetchAll();
        }
        return null;
    }

    ### get the feedback by device_id (means from device's feedback ex. window sensor)
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
    ### get the feedback by user_id (means from user's feedback ex.transportation mode)
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

    ### utilities get device_id from ipaddr by checking online device table
    public function getDeviceIdByIpAddr($ip_addr){
        $query = "select * from $this->onlineDevice_tableName where ipaddress='$ip_addr'";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows[0]['session'];
        }
        return null; 
    }
    ### utilities get user_id from ipaddr by checking online user table
    public function getUserIdByIpAddr($ip_addr){
        $query = "select * from $this->onlineUser_tableName where ipaddr='$ip_addr'";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows[0]['user_id'];
        }
        return null; 
    }

    ### get all report that not solved
    public function getExistReport(){
        $query = "select id, title, coordinate_x, coordinate_y, created_by, created_at from problems where status = 1";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $rows = $result->fetchAll();
            return $rows;
        }
        return null;
    }
    ### fix report updating database
    public function updateFixReport($user_id, $report_id){
        $query = "update problems set status=0, updated_by=$user_id, updated_at='NOW()' where id=$report_id";
        $report = $this->dbh->query($query);
    }
    ### make new report to database
    public function insertFixReport($title, $coor_x, $coor_y, $user_id){
        $query = "insert into problems values( NULL, '".$title. "','" .$title. "'," .$coor_x. "," .$coor_y. "," .$user_id. ", 1, NOW(), NOW()," .$user_id. ");";
        $result = $this->dbh->query($query);
    }
    ### insert 3-sensor
    public function insertSensorBasic($device_id, $light, $temp, $sound){

        $query = "insert into $this->basicSensorLog_tableName values( NULL, $device_id, $light, $temp, $sound, NOW())";
        $result = $this->dbh->query($query);
        $insert_id = $this->dbh->lastInsertId();
        return $insert_id;

    }
    ### insert window sensor
    public function insertWindowState($log_id, $window_state){
        $query = "insert into $this->windowStateLog_tableName values(".$log_id.",".$window_state.")";
        $result = $this->dbh->query($query);
    }
    ### insert people sensor
    public function insertPeopleState($log_id, $people_state){
        $query = "insert into $this->peopleSensorLog_tableName values($log_id, $people_state)";
        $result = $this->dbh->query($query);
    }

    ### insert feedback by ipaddr
    public function insertFeedbackStatusBy($ip_addr, $application_id, $type){ 
        $device_id = $this->getDeviceIdByIpAddr($ip_addr);
        $user_id = $this->getUserIdByIpAddr($ip_addr);
        if($device_id != null && $user_id != null){
            $query = "insert into $this->feedbackStatus_tableName (device_id, application_id, user_id, feedback_type) values ($device_id, $application_id, $user_id, \"$type\")";
            $result = $this->dbh->query($query);
        }
        else if($device_id != null && $user_id == null){
            $this->insertFeedbackStatusByDeviceId($device_id, $application_id, $type);
        }
        else if($user_id != null && $device_id == null){
            $this->insertFeedbackStatusByUserId($user_id, $application_id, $type);
        }
    }

    // mostly for sensor scenario need conjection control
    // use device_id to insert feedback
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
    ### use user_id to insert feedback add avoid too fast insertion
    public function insertFeedbackStatusByUserId($user_id, $application_id, $type){ 
        $time = $this->getMaxTimeStamp();
        date_default_timezone_set('America/Los_Angeles');
        $date = date('Y-m-d H:i:s', time());
        $diff = strtotime($date) - strtotime($time);
        if($diff > 2){
            $query = "insert into $this->feedbackStatus_tableName (user_id, application_id, feedback_type) values ($user_id, $application_id, \"$type\")";
            $result = $this->dbh->query($query);
        }
    }
    ### update feedback by device(gumball machine)
    public function updateFeedbackStatusBy($feedback_id){
        $query = "update $this->feedbackStatus_tableName set if_get=1 where feedback_id=$feedback_id";
        $result = $this->dbh->query($query);
    }
    ### let device can take the credit of user's feedback(mean can be retrieve by device)
    public function assignFeedbackOfDeviceId($feedback_id, $device_id, $user_id){
        $query = "update $this->feedbackStatus_tableName set device_id=$device_id=1 where feedback_id=$feedback_id and user_id=$user_id";
        $result = $this->dbh->query($query);
    }
    ### function with user stuff  
    public function checkUserExist($account){
        $query = "select * from members where account=\"$account\"";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return true;
        }
        return false;
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
    public function getUserByToken($token){
        $query = "select * from $this->members_tableName where token=\"$token\"";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            return $result->fetchAll();
        }
        return null;
    }
    public function getUserIdByToken($token){
        if(($result = $this->getUserByToken($token)) != null){
            return $result[0]['user_id'];
        }
        return null;
    }
    ### utilities
    public function getMaxTimeStamp(){
        $query = "select max(created_time) from $this->feedbackStatus_tableName";
        $result = $this->dbh->query($query);
        if($result->rowCount() > 0){
            $num = $result->fetchAll();
            return $num[0][0];
        }
        return null;
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
    
    public function updateAndLoginOnlineUserList($user_id, $time, $ip_addr){
        $query = "select * from $this->onlineUser_tableName where user_id='$user_id'";
        $result = $this->dbh->query($query);

        if($result->rowCount() == 0){
            $query = "INSERT INTO $this->onlineUser_tableName (user_id, time, ipaddr) VALUES ('$user_id', '$time', '$ip_addr')";
            $result = $this->dbh->query($query);
        }        
        else{   
            $query = "UPDATE $this->onlineUser_tableName SET time='$time', ipaddr='$ip_addr' WHERE user_id = '$user_id'";
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
        $trip_id = $this->getMaxTripId($user_id) + 1;
        for($i = 4; $i < count($transportation); $i++){
            #print_r($transportation[$i]);
            $time_splited = explode(".",$transportation[$i][8]);
            $time_splited = explode("T",$time_splited[0]);
            $time = $time_splited[0]." ".$time_splited[1];
            $this->insertDataRowToDatabase($label, $user_id, $trip_id, 
                $transportation[$i][0], $transportation[$i][1], 
                $transportation[$i][2], $transportation[$i][3], 
                $transportation[$i][4], $transportation[$i][5], 
                $transportation[$i][6], $transportation[$i][7], 
                $time);
        }
    }
    public function insertDataRowToDatabase($label, $user_id, $trip_id, $segment, $point, $latitude, $longitude, $altitude, $bearing, $accuracy, $speed, $time){ 
        $query = "insert into $this->transportation_tableName values (NULL, $user_id, $trip_id, '$label', $segment, $point, $latitude, $longitude, $altitude, $bearing, $accuracy, $speed, \"$time\")";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }
    public function getMaxTripId($user_id){
        $query = "select MAX(trip_id) from $this->transportation_tableName where user_id=$user_id";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows[0][0];
    }
    public function getTripsOfUser($user_id){
        $query = "select distinct(trip_id) from $this->transportation_tableName where user_id=$user_id";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function getTransportationData($user_id){
        $query = "select * from $this->transportation_tableName where user_id=$user_id";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }
    public function getTransportationDataByTrip($user_id, $trip_id){
        $query = "select * from $this->transportation_tableName where user_id=\"$user_id\" and trip_id=\"$trip_id\"";
        $result = $this->dbh->query($query);
        $rows = $result->fetchAll();
        return $rows;
    }
    public function getTransportationDataNewestTrip($user_id){
        $trip_id = $this->getMaxTripId($user_id);
        return $this->getTransportationData($user_id, $trip_id);
    }
}

?>
