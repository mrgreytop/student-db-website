<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  include("sqlQueries.php"); //connect
  //check usernmame
  $stmt = "select username from login";
  $result = $conn->query($stmt);
  while($row = $result->fetch_assoc() and ($uflag != "username already exists")){
    if ($row["username"] == $_POST["username"]){
      $uflag = "username already exists";
      $err->log("user name exists");
      $err->log($uflag);
    }else{
      $uflag = "username is available";
    }
  }
  //check passwords match
  if (isset($_POST["create"])){
    if ($_POST["password"] != $_POST["cpassword"]){
      $pflag = "passwords don't match";
      $err->log($pflag);
    }else{
      $err->log("passwords match");
    }
  }
  //flag empty entries
  $i=0;
  foreach($_POST as $val){
    if (empty($val)){
      $flags[$i]=0;
      $err->log($flags[$i]);
    }else{
      $flags[$i]=1;
      $err->log($flags[$i]);
    }
    $i = $i+1;
  }
}
?>
