<?php
include("config.php");


date_default_timezone_set('Asia/Singapore');
$date = new DateTime();
$tStamp = $date->format('U');
$date = $date->format('Y-m-d H:i:s');


if (isset($_POST)  ){
    $DO = $_POST["DO"];
    $PH = $_POST["PH"];
    $Temp = $_POST["Temp"];
    $H2O = $_POST["H2O"];

    try {   
        $fetch = "SELECT * FROM `threshold`";
        $fetch_query = mysqli_query($conn, $fetch);
        $fetch_numrows = mysqli_num_rows($fetch_query);
        $threshold = [];
            while($row = mysqli_fetch_assoc($fetch_query)) {
            $threshold[] = $row;
        }
        
        $sensorReadingSMS = "Sensor readings as of $date:\nTemp: $Temp degC\nPH: $PH\nWater Level: $H2O % \nDO: $DO";
        $sensorArray = array([
                "min"=> $threshold[0]["tempMin"],
                "max"=> $threshold[0]["tempMax"],
                "value"=> $Temp,
                "label"=> "Temperature",
                "alert" => $threshold[0]["tempStat"]
            ],
            [
                "min"=> $threshold[0]["phMin"],
                "max"=> $threshold[0]["phMax"],
                "value"=> $PH,
                "label"=> "PH",
                "alert" => $threshold[0]["phStat"]
            ],
            [
                "min"=> $threshold[0]["DOMin"],
                "max"=> $threshold[0]["DOMax"],
                "value"=> $DO,
                "label"=> "DO",
                "alert" => $threshold[0]["DOStat"]
            ]);
            
            
        /***************************** DETECT THRESHOLD VIOLATIONS *****************************/
        $smsMessage = "";
        $smsMessage = checkAlerts($sensorArray);
        $insert = $conn->prepare("INSERT INTO `sensorlog`( `DO`, `PH`, `Temp`, `H2O`, `tStamp`, `dateTime`) VALUES (?,?,?,?,?,?)");
        $insert->bind_param("ddddds",$DO,$PH, $Temp, $H2O, $tStamp, $date); 
        if(!$insert->execute()){
            sendOutput(
                "Line" . __LINE__. ": " ."Alert sms sending failed. ". $insert->error(),
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
            exit;
                            
        }
        $insert->close();
        /***************************** DETECT THRESHOLD VIOLATIONS *****************************/
        
        
        /***************************** UDPATE THRESHOLD *****************************/
        $updateTH= $conn->prepare("UPDATE `threshold` SET `tempStat`=?, phStat=?,DOStat=? ");
        $updateTH->bind_param("iii",$smsMessage[1][0], $smsMessage[1][1],$smsMessage[1][2]);
        
	    if (!$updateTH->execute()){
            sendOutput(
                "Line" . __LINE__. ": " ."Threshold update failed. ". $updateTH->error,
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
            exit;
        }
        $updateTH->close();
        /***************************** UDPATE THRESHOLD *****************************/
                    
           
        /***************************** SEND SMS FOR TH VIOLATIONs *****************************/
        if ($smsMessage[0] <> ""){
            $smsResult = smsgateway($recipients, $smsMessage[0], $api, $apiPass);
            if ($smsResult == "success"){
                sendOutput("Line" . __LINE__. ": Alert sent",
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                 sendOutput(
                "Line" . __LINE__. ": " ."Alert sms sending failed. ". $smsResult,
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
                exit;
            }
        }
        /***************************** SEND SMS FOR TH VIOLATIONs *****************************/
        
        
        /***************************** SEND SMS FOR PERIODICAL LOGS *****************************/
        
         $periodicalUpdate = smsgateway($recipients, $sensorReadingSMS, $api, $apiPass);
            if ($periodicalUpdate == "success"){
                sendOutput(
                    "Line" . __LINE__. ": Periodical log sent.",
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK'));
            }
            else{
                sendOutput(
                "Sensor log successfull! Periodical sms sending failed. ". $smsResult,
                array('Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error'));
            }
            
        /***************************** SEND SMS FOR PERIODICAL LOGS *****************************/
    }
    catch(Exception $e) {
        throw New Exception( $e->getMessage() );
    } 
   
        }
        
   
   
    


else{
    sendOutput(json_encode(array('error' => "No params set")), 
                array('Content-Type: application/json', "HTTP/1.1 401 Unauthorized")
            );
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
    // echo "result" .file_get_contents($url, false, $context);
    return file_get_contents($url, false, $context);
}
	

?>