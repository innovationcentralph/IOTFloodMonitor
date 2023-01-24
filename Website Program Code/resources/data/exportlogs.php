

<?php


include('config.php');
session_start();

date_default_timezone_set('Asia/Singapore');
$date = new DateTime();
$tStamp = $date->format('U');
$date = $date->format('Y-m-d');

if(isset($_SESSION[$sessionName]['userID'])){ 
        // if (isset($_GET["mode"])){
            
        //     $mode = $_GET["mode"];
        //     // echo $mode;
        //     if ($mode == "today"){
        //         $query = "SELECT * FROM `attendancelist` WHERE Date(timeIn) = Date('$date') ORDER BY timeIn ASC";
        //         $fileName = "attendacelog_today";
        //     }
        //     else if ($mode == "all"){
        //         $query = "SELECT * FROM `attendancelist` ORDER BY timeIn ASC";  
        //         $fileName = "attendacelog_all";
        //     }
        // }

        if (isset($_GET["beginDate"])){
            
            $beginDate = $_GET["beginDate"];
            $endDate = $_GET["endDate"];
            // echo $mode;
            // echo "$beginDate , $endDate";
            
if(isset($_GET["device"])){
    $selectedDevice = $_GET["device"];
    if($beginDate == $endDate){
        $query = "SELECT * from sensorlog WHERE DeviceID = '$selectedDevice' and Date(DateTime) = '$endDate' ORDER BY TimeStamp ASC";
    }
    else{
        $query = "SELECT * from sensorlog WHERE DeviceID = '$selectedDevice' and DateTime between '$beginDate' and '$endDate' ORDER BY TimeStamp ASC";
    }
               
            //   echo $query;
     }     
    else{
        echo json_encode(array("response"=>"error","data"=>"Opps! No parameter set!" ));
        exit;
    }
    
      $fileName = "logs";
           
        }

        
        header('Content-Type: text/csv; charset=utf-8');  
        header("Content-Disposition: attachment; filename=".$fileName."_$tStamp.csv");  
        // header( "refresh:5;url=../../students.php" );

        $output = fopen("php://output", "w");  
        fputcsv($output, array('Device ID','Water Level', 'Alert Level', 'Latitude','Longitude','Date & Time'));  
        //  $query = "SELECT * FROM `attendancelist` WHERE Date(timeIn) = Date('$date') ORDER BY timeIn ASC";  
    
        $result = mysqli_query($conn, $query);  
        $scan_numrows = mysqli_num_rows($result);
        if($scan_numrows > 0){
            while($row = mysqli_fetch_assoc($result))  {  
                $currentRow = array();
                
                if($row["AlertLevel"] == "1"){
                    $row["AlertLevel"] = "LOW";
                }
                else if($row["AlertLevel"] == "2"){
                    $row["AlertLevel"] = "MEDIUM";
                }
                else if($row["AlertLevel"] == "3"){
                    $row["AlertLevel"] = "HIGH";
                }
                array_push($currentRow, $row["DeviceID"],  $row["WaterLevel"],$row["AlertLevel"], $row["Latitude"],$row["Longitude"],$row["DateTime"] );
                fputcsv($output,  $currentRow);  
            }  
        }
        else{
        // array_push($output, "No Data to show");
        fputcsv($output,  array("No Data to show"));  
        }
        fclose($output);  
    
}
      

    //   if (trim($string) == 'HTTP/1.1 200 OK')
        // header("location: ../../index.php"); 
//  } 


      

     
 
?>


