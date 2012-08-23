<?php
require_once("db.php");
$allowedExts = array("jpg", "jpeg", "gif", "png","rtf", "csv");
$allowedTypes = array("image/jpeg", "image/pjpeg", "image/png", "image/gif", "text/csv", "text/comma-separated-values");
$extension = end(explode(".", $_FILES["file"]["name"]));
$store_path = "../uploads/";
$file_type = $_FILES["file"]["type"];
#print_r($_FILES);
if ( $_FILES["file"]["size"] < 2000000 && in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        #echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        #echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        #echo "Type: " . $_FILES["file"]["type"] . "<br />";
        #echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        #echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
           # echo "File ". $_FILES['file']['name'] ." uploaded
           #     successfully.<br />";
        }
        if (file_exists($store_path.$_FILES["file"]["name"]))
        {
           # echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"], $store_path.$_FILES["file"]["name"]);
            #echo "Stored in: " . $store_path . $_FILES["file"]["name"];
            $file_path = $store_path.$_FILES["file"]["name"];
            $data_array = array();
            $handle = fopen($file_path, "r");
            while( ($data = fgetcsv($handle, 1000, ","))!= FALSE ){
                $num = count($data);
            #    echo "<p> $num fields in line $row: <br /></p>\n";
            #    $row++;
                array_push($data_array, $data);
                for ($c=0; $c < $num; $c++) {
            #        echo $data[$c] . "<br />\n";
                }
            }
            #print_r($data_array);
            if( isset($_POST["token"]) ){
                $db = new DB();
                $user_id = $db->getUserIdByToken($_POST["token"]);
                $result['trip_id'] = $db->insertDataToDatabase($data_array, $user_id);
            }
            fclose($handle);
            echo json_encode($result);
        }
    }
}
else
{
    # echo "Invalid file";
}
?>
