<?php
include('config.php');

    $fetch = "SELECT * FROM `threshold`";
    
    $fetch_query = mysqli_query($conn, $fetch);
    $fetch_numrows = mysqli_num_rows($fetch_query);
    if ($fetch_numrows > 0){
        $data = [];
        // echo "inside this loop<br>";
         while($row = mysqli_fetch_assoc($fetch_query)) {
            // if($_POST["data"] == "temp"){
                
           $data[] = $row;
        }
         echo json_encode($data);
    }
    else{
        echo "ERROR!No Data.";
    }


?>