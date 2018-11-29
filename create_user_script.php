<?php
$fetch_courses = "select course, courseID from courses";
$courses = $conn->query($fetch_courses);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  //check usernmame
  $stmt = "select username from login";
  $result = $conn->query($stmt);
  while($row = $result->fetch_assoc() and ($uflag != "username already exists")){
    if ($row["username"] == $_POST["username"]){
      $uflag = "username already exists";
    }elseif($_POST["username"]==""){
    }else{
      $uflag = "username is available";
    }
  }
  //check passwords match
  if (!empty($_POST["Create"])){
    $err->log($_POST["Create"]);
    if ($_POST["password"] != $_POST["cpassword"]){
      $pflag = "passwords don't match";
      $err->log($pflag);
    }else{
      $err->log("passwords match");
    }
  //flag empty entries
    $i=0;
    $empty_flag = 1;
    foreach($_POST as $val){
      if (empty($val)){
        $flags[$i]=0;
        $err->log($flags[$i]);
      }else{
        $flags[$i]=1;
        $err->log($flags[$i]);
      }
      $empty_flag = $empty_flag*$flags[$i]; //if any flag is 0 empty flag is 0;
      $i = $i+1;
    }
    if ($empty_flag or !$pflag or $uflag == "username is available"){
      //insert student details
      $insert_student = "insert into students (firstname, lastname, courseID) values ('".$_POST["firstname"]."','".$_POST["lastname"]."' ,".$_POST["course"].")";
      if($student= $conn->query($insert_student)){$err->log("student inserted");
      }else{
        $err->log(mysqli_error($conn));
        $err->log($insert_student);
      }
      //get studentID
      $fetch_ID = "select studentID from students where firstname = '".$_POST["firstname"]."'";
      if($ID= $conn->query($fetch_ID)){
        $err->log("fetching studentID");
        while($row = $ID->fetch_row()){
          $studentID = $row[0];
          $err->log("studentID ".$studentID.$row[0]);
        }
      }else{$err->log(mysqli_error($conn));}
      //insert login details
      $password = hash(sha256, $_POST["password"], FALSE);
      $insert_login = "insert into login (username, password, studentID) values ('".$_POST["username"]."','".$password."',".$studentID.")";
      if($login= $conn->query($insert_login)){
        $err->log("login inserted");
        echo "<script>window.location.href= 'Student_login.php';</script>";
      }else{$err->log(mysqli_error($conn));}
    }
  }
}
?>
