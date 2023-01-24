<?php

include('config.php');
$queryArray = array();

if (isset($_POST)){
    foreach($_POST as $key => $value) {
        // $query .= $key ."= " . $value;
        array_push($queryArray,$key ."= " . $value);
    }
    $query = implode(",",$queryArray);
    $query ="UPDATE `threshold` SET $query";
    if($conn->query($query)==TRUE){

        echo "OK!Threshold update successfull!";
    }
    else{ 
        echo "Threshold update failed. " . mysqli_error($conn).".";
    }
}

?>