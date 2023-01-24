<?php
include('config.php');

session_start();

unset($_SESSION[$sessionName]);

header("Location: ../../index.php"); /* Redirect browser */
  exit();
 

?>