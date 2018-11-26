<?php
include("error_log.php");
if(session_start()){
  $err = new errlog("error_log.txt");
  $err->log("Start Session");
}else{
  file_put_contents("error_log.txt", "session start failed",FILE_APPEND);
}

$err->log($_SERVER["REQUEST_METHOD"]."");

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  include("info.php");
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: ". mysqli_connect_error());
    $err->log("conn fail");
  }
  $uname = $_POST["username"];
  $password = hash(sha256, $_POST["password"], FALSE);
  if (!$uname and !$password){
    $err->log("post fail");
  }else{
    $finduser = "select * from login where username = '".$uname."' and password = '".$password."'";
    $result = $conn->query($finduser);
    if (!$result){
      $err->log("query fail");
      $message = "incorrect username or password";
    }else{
      $row = $result->fetch_assoc();
      if (!$row){
        $err->log("fetch fail");
      }else{
        $err->log( "FETCHED ".$row["studentID"]." ".$row["username"]."");
        $_SESSION["studentID"] = $row["studentID"];
        $_SESSION["username"] = $row["username"];
        echo "<script>window.location.href= 'home_screen.php';</script>";
      }
    }
  }
}
?>
