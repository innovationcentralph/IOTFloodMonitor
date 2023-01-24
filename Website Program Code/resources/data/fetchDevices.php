<?php
session_start();
include('config.php');
if(isset($_SESSION[$sessionName]['userID'])){
    $userID = $_SESSION[$sessionName]['userID'];
    // if(isset($_GET["device"])){
        // $selectedDevice = $_GET["device"];
        // date_default_timezone_set('Asia/Singapore');
        $sql = "select distinct(DeviceID) from sensorlog";
        // $sql = "select distinct(devID) from sensorlog Where userID = '$userID'";
       
        $scan_query = mysqli_query($conn, $sql);
        $scan_numrows = mysqli_num_rows($scan_query);
        $rows = array();
        if ($scan_numrows > 0){
            while($row = mysqli_fetch_assoc($scan_query)) {
                
                $rows[] = $row;
            }
        }
        echo json_encode(array("response"=>"success","data"=>$rows,"userID"=>$userID));
    // }
    // else{
    //     echo json_encode(array("response"=>"error","data"=>"Opps! User does not exist!" ));
    // }
}

else{
    echo json_encode(array("response"=>"error","data"=>"Sorry! You are not allowed to access this page!" ));
}
?>