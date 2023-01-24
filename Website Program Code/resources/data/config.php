<?php
// local
// $servername="localhost";
// $username="root";
// $password="";
// $dbase="fms";

//hostinger
$servername="localhost";
$username="u891337127_floodmonadmin";
$password="*5cUe=Mk#7";
$dbase="u891337127_fms";

$sql_details = array(
    'user' => $username,
    'pass' => $password,
    'db'   => $dbase,
    'host' => $servername
);

$sessionName = "powermeter";
$user1 = 'admin';


$conn = new mysqli($servername,$username,$password,$dbase);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
function sendOutput($data, $httpHeaders=array(),$noHeader = true) {
    
    if (!$noHeader){
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
    }

    echo $data;
    // exit;
}

function execQuery($conn, $query, $fail, $header, $noHeader = true){
    // echo $query ."<br>";
    if(!$conn->query($query)==TRUE){ 
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        if ($noHeader){
            echo 'Line error ' .  __LINE__ .":". $fail  . mysqli_error($conn);
        }
        else{
            sendOutput(json_encode(array('Line error ' .  __LINE__ => $fail  . mysqli_error($conn))), 
            array('Content-Type: application/json', $header)
            );
        }
       
        
        return $fail;
    }
    return;
    
}
?>