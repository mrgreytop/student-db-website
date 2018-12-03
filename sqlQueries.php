<?php
include("error_log.php");
$err = new errlog("error_log.txt");
include 'info.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: ". mysqli_connect_error());
  $err->log("CONN FAIL");
}else{
  $err->log("CONN SUC");
}
?>
