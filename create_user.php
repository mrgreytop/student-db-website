<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="create_user.css">
  <?php
  include("sqlQueries.php");
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
</head>
<body>
  <div>
    <form action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method = POST>
      <table class = "spaced_form">
        <tr>
          <td>First Name: </td>
          <td><input type="text" name="firstname" class= "input"></input></td>
          <td class = "error"><?php if ($flags[0] === 0){echo "required";}?></td>
        </tr>
        <tr>
          <td>Last Name: </td>
          <td><input type="text" name="lastname" class = "input"></input></td>
          <td class = "error"><?php if ($flags[1] === 0){echo "required";}?></td>
        </tr>
        <tr>
          <td>Course:</td>
          <td><select name="course" class = "input" style="width:100%;">
            <option value = 'none' class = 'input'></option>
            <?php
            while($row = $courses->fetch_row()){
                echo "<option value = '".$row[1]."' class = 'input'>".$row[0]."</option>";
              }?>
          </select></td>
          <td class = "error"><?php if ($flags[2] === 0){echo "required";}?></td>
        <tr>
          <td>Username: </td>
          <td><input type="text" name="username" class = "input"></input></td>
          <td class = "error"><?php if ($flags[3] === 0){echo "required";}?></td>
        </tr>
        <tr>
          <td></td>
          <td><input class = "button" type="submit" value="Check Username" name="check"></input></td>
        </tr>
        <tr>
          <td></td>
          <td class = "error" id = username>
            <?php if ($uflag){
              echo "<script> document.getElementById('username').style.display = 'visible';</script>";
              echo $uflag;
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>Password: </td>
          <td><input type = "password" name="password" class = "input" ></input></td>
          <td class = "error"><?php if ($flags[4] === 0){echo "required";}?></td>
        </tr>
        <tr>
          <td>Confirm Password: </td>
          <td><input type="password" name = "cpassword" class = "input"></input></td>
          <td class = "error">
            <?php
            if (($flags[5] === 0 and !empty($pflag)) or !empty($pflag)){
              echo $pflag;
              $err->log("password flag".$pflag);
            }elseif($flags[5] === 0){
              echo "required";
              $err->log("cpassword required");
            }else{
              $err->log("no password error");
            }
            ?>
          </td>
        <tr>
          <td></td>
          <td><input type="submit" class="button" value="Create" name="Create"></input></td>
        </tr>
      </table>
    </form>
</body>
</html>
