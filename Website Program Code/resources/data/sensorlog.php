<?php
include("config.php");

// temp ambient, temp coil, voltage and amphere



date_default_timezone_set('Asia/Singapore');
$date = new DateTime();
$tStamp = $date->format('U');
$date = $date->format('Y-m-d H:i:s');

include('pdoqueries.php');

if (isset($_GET)  ){
    $d1=$_GET["u"];
    $d2=$_GET["f"];
    $d3=$_GET["lat"];
    $d4=$_GET["long"];
    $d5=$_GET["id"];
    
    unset($_GET);
    // unset($_GET["u"], $_GET["f"], $_GET["lat"],$_GET["long"], $_GET["id"]);
    // $_GET["WaterLevel['value']"] = $d1;
    // $_GET["AlertLevel['value']"] = $d2;
    // $_GET["Latitude['value']"] = $d3;
    // $_GET["Longitude['value']"] = $d4;
    // $_GET["DeviceID['value']"] = $d5;
    // $_GET["TimeStamp['value']"] = $tStamp;
    // $_GET["DateTime['value']"] = $date;

    
    // $_GET["WaterLevel['type']"] = "d";
    // $_GET["AlertLevel['type']"] = "i";
    // $_GET["Latitude['type']"] = "d";
    // $_GET["Longitude['type']"] = "d";
    // $_GET["DeviceID['type']"] = "s";
    // $_GET["TimeStamp['type']"] = "d";
    // $_GET["DateTime['type']"] = "s";

    $_GET["WaterLevel"] = ["'value'" =>$d1, "'type'" => "d"];
    $_GET["AlertLevel"] = ["'value'" =>$d2, "'type'" => "i"];
    $_GET["Latitude"] = ["'value'" =>$d3, "'type'" => "d"];
    $_GET["Longitude"] = ["'value'" =>$d4, "'type'" => "d"];
    $_GET["DeviceID"] = ["'value'" =>$d5, "'type'" => "s"];
    $_GET["TimeStamp"] = ["'value'" =>$tStamp, "'type'" => "d"];
    $_GET["DateTime"] = ["'value'" =>$date, "'type'" => "s"];

    

    // echo json_encode($_GET);


    $parseDBlogPost = new GeneratePDOQuery();
    $parseDBlogPost->parseInsertForm($_GET);
    $parseDBlogPost->getInsertFormInputStr();
    try {   
        $sql = "INSERT INTO `sensorlog` ($parseDBlogPost->insert_ColumnNamesStr) VALUES ($parseDBlogPost->insert_ColumnInstanceStr)";
        // echo $sql;
        $insert = $conn->prepare($sql);
        $insert->bind_param($parseDBlogPost->insert_ColumnDataTypesStr,...$parseDBlogPost->insert_ColumnValues); 
        if(!$insert->execute()){
            sendOutput(
                "Line" . __LINE__. ": " ."Sensor log failed. ". $insert->error,
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
            exit;        
        }
        else{
            $fetchSQL = "SELECT * from users LIMIT 1";
            $fetchSQL_query = mysqli_query($conn, $fetchSQL);
            $fetchSQL_numrows = mysqli_num_rows($fetchSQL_query);
            if ($fetchSQL_numrows > 0){
                while($row = mysqli_fetch_assoc($fetchSQL_query)) {
                    $ContactNumber = $row["ContactNumber"];
                }
            }
            
            sendOutput(
            "Line" . __LINE__. ": " ."Sensor log successfull!%$ContactNumber%",
            array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
        }
        $insert->close();

        
    }
    catch(Exception $e) {
        throw New Exception( $e->getMessage() );
    } 
   
}
else{
    sendOutput(json_encode(array('error' => "No params set")), 
        array('Content-Type: application/json', "HTTP/1.1 401 Unauthorized")
    );
    exit;
}

function checkAlerts($sensorArray){
    $alertMessage = array();
    $alertMessageStr = "";
    $alertUpdates = array();
    foreach($sensorArray as $sensorData){
        if ($sensorData["value"] > $sensorData["max"] ||  $sensorData["value"] < $sensorData["min"] ){
            if ($sensorData["alert"] == 0){
                array_push($alertMessage,$sensorData["label"] . " sensor out of range!");
            }
            
            array_push($alertUpdates,1);
        }
        else{
            $sensorData["alert"] == 0;
                array_push($alertUpdates,0);
        }
    }
    $alertMessageStr = implode("\n",$alertMessage);
    $response = array($alertMessageStr, $alertUpdates);
    return $response;

}

function smsgateway($recipients = [], $message, $apicode, $passwd)
{
    $url = 'http://mactechph.com/broker/api.php';
    $message = substr($message,0,200);
    $gatewayparams = array('1' => $recipients, '2' => $message, '3' => $apicode, 'passwd' => $passwd);
    $config = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($gatewayparams),
            ),
    );

    $context  = stream_context_create($config);
    return file_get_contents($url, false, $context);
}
	

?>