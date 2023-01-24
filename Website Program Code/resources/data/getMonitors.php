<?php

include("config.php");
date_default_timezone_set('Asia/Singapore');
$date=date('Y-m-d H:i');
session_start();
$currentEpoch = date('U');
// echo json_encode($_SESSION);
if (isset($_GET["DeviceID"])){
    $DeviceID = $_GET["DeviceID"];
        // $search = "SELECT * FROM `monitoring` inner join users on monitoring.devID = users.DeviceID where monitoring.devID = '$userID'";
        
        $search = "SELECT * FROM `sensorlog`  where DeviceID = '$DeviceID' ORDER BY TimeStamp DESC LIMIT 1";
// echo $search;
 
            $search_query = mysqli_query($conn, $search);
            $numrows = mysqli_num_rows($search_query);
            $rows = array();
            while($row = mysqli_fetch_assoc($search_query)) {
               
                $rows[] = $row;
            }
        
       
            // array_push($rows, ["currentEpoch" => $currentEpoch]);
        echo json_encode($rows);
}
else{
    
    echo "no user set";
}
?>