<?php
function generateID($letterCount, $numberCount, $uppercase = true) {
    $letterChar= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numberChar = '0123456789';
    $randomString = '';
  
    for ($i = 0; $i < $letterCount; $i++) {
        $index = rand(0, strlen($letterChar) - 1);
        $randomString .= $letterChar[$index];
    }
    
    for ($i = 0; $i < $numberCount; $i++) {
        $index = rand(0, strlen($numberChar) - 1);
        $randomString .= $numberChar[$index];
    }
  
    return $randomString;
}


function generatePW($charCount) {
    $characters= 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@!';
    $randomString = '';
  
    for ($i = 0; $i < $charCount; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    
   
    return $randomString;
}

?>
    