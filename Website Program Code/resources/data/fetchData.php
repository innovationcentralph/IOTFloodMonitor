<?php
include('config.php');
if(isset($_GET["DeviceID"])){
    $selectedDevice = $_GET["DeviceID"];
    date_default_timezone_set('Asia/Singapore');
    $sql = "SELECT * from sensorlog WHERE DeviceID = '$selectedDevice' ORDER BY `TimeStamp` ASC";
    $data = [];
    $value1 = [];
    $value2 = [];
    $value3 = [];
    $value4= [];
    // $value4 = [];
    // $value5 = [];
    $scan_query = mysqli_query($conn, $sql);
    $scan_numrows = mysqli_num_rows($scan_query);
    // `ambientTemp`, `coilTemp`, `WaterLevel`, `current`, `dateTime`, `tStamp`
    if ($scan_numrows > 0){
        // echo "inside this loop<br>";
        while($row = mysqli_fetch_assoc($scan_query)) {
            // if($row["AlertLevel"] == "1"){
            //     $row["AlertLevel"] = "LOW";
            // }
            // else if($row["AlertLevel"] == "2"){
            //     $row["AlertLevel"] = "MEDIUM";
            // }
            // else if($row["AlertLevel"] == "3"){
            //     $row["AlertLevel"] = "HIGH";
            // }
            $row["DateTime"] = date("M j, Y h:iA",$row["TimeStamp"] );
            
            $row["date"] = $row["TimeStamp"] * 1000;
            $row["WaterLevel"] = (double)$row["WaterLevel"];
            $row["AlertLevel"] = (double)$row["AlertLevel"];
            $row["WaterLevelTooltip"] = (double)$row["WaterLevel"] . "%";
            $row["AlertLevelTooltip"] = "LOW";
            $row["WaterLevelColor"]["stroke"] = $row["AlertLevelColor"]["stroke"] = "#4bc0c0";
             $row["AlertLevelColor"]["fill"] = "#4bc0c0";
            if ($row["AlertLevel"] == 2 ){
                $row["AlertLevelColor"]["stroke"] = "#ffcd56";
                $row["AlertLevelColor"]["fill"] = "#ffcd56";
                $row["AlertLevelTooltip"] = "MEDIUM";
            }
            else if ($row["AlertLevel"] == 3 ){
                $row["AlertLevelColor"]["stroke"] = "#ff6384";
                $row["AlertLevelColor"]["fill"] = "#ff6384";
                $row["AlertLevelTooltip"] = "HIGH";
            }
            
            else if ($row["AlertLevel"] > 3 ){
                $row["AlertLevel"] = 0;
                $row["AlertLevelColor"]["stroke"] = "#ff6384";
                $row["AlertLevelColor"]["fill"] = "#ff6384";
                $row["AlertLevelTooltip"] = "ERROR";
            }
                // array_push($data, [
                //     // 'date' => date("M j h:i:s A",$row["TimeStamp"] ) ,
                //     'WaterLevel' => $row["WaterLevel"] ,
                //     'AlertLevel' => $row["AlertLevel"]
                // ]);
                
            $value1[] = $row["WaterLevel"] ;
            $value2[] = $row["AlertLevel"] ;
            // $value3[] = $row["Latitude"] ;
            // $value4[] = $row["Longitude"] ;
            $data[] = $row;
        }
            array_push($data,   
            ['WaterLevelMax' => max($value1),
            'AlertLevelMax' => max($value2),
            // 'LatitudeMax' => max($value3),
            // 'LongitudeMax' => max($value4),
            'WaterLevelMin' => min($value1),
            'AlertLevelMin' => min($value2)
            // 'LatitudeMin' => min($value3),
            // 'LongitudeMin' => min($value4)
        ]);
        }
        echo json_encode($data);
}
?>