<?php session_start();?>
<html>
<head>
<?php
  include('sqlQueries.php');
  $studentID = $_SESSION["studentID"];
  $getAddr = "select address1, address2, postcode from students where studentID = '".$studentID."'";
  $getCourse = "select course from courses inner join students on students.courseID = courses.courseID where studentID = ".$studentID."";
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["edit_address"])){
      $update_address_stmt = "update students set address1 = '".$_POST["address1"]."', address2 = '".$_POST["address2"]."', postcode = '".$_POST["postcode"]."' where studentID = ".$studentID;
      if($update_address = $conn->query($update_address_stmt)){
        $err->log("address updated");
      }else{
        $err->log($update_address_stmt);
        $err->log("address update failed: ".mysqli_error($conn));
      }
    }
    if(!empty($_POST["change_username"])){
      $password = hash(sha256, $_POST["password"], FALSE);
      $finduser = "select * from login where studentID = '".$_SESSION["studentID"]."' and password = '".$password."'";
      if ($result = $conn->query($finduser)){
        $update_username_stmt = "update login set username = '".$_POST["username"]."' where studentID = '".$_SESSION["studentID"]."'";
        if($update_username = $conn->query($update_username_stmt)){
          $err->log("username updated");
          $_SESSION["username"] = $_POST["username"];
        }else{
          $err->log($update_username_stmt);
          $err->log("username update fail".mysqli_error($conn));
        }
      }else{
        $err->log("wrong password");
        $message = "incorrect password";
      }
    }
    if(!empty($_POST["change_password"])){
      $old_pass = hash(sha256, $_POST["old_password"], FALSE);
      $new_pass = hash(sha256, $_POST["new_password"], FALSE);
      $confirm_pass = hash(sha256, $_POST["confirm_password"], FALSE);
      $finduser = "select * from login where studentID = '".$_SESSION["studentID"]."' and password = '".$old_pass."'";
      if($result = $conn->query($finduser)){
        if($new_pass == $confirm_pass){
          $update_password_stmt = "update login set password = '".$new_pass."' where studentID = ".$_SESSION["studentID"];
          if($update_password = $conn->query($update_password_stmt)){
            $err->log("password updated");
          }else{
            $err->log("passwords don't match");
          }
        }
      }else{
        $err->log("wrong password");
      }

    }
    $close = $_POST["close_form"];
    for($i=0;$i<count($close);$i++){
      if(!empty($close[$i])){
        $_POST = array();
        echo "<script>closeForm(".$i.")</script>";
      }
    }
  }else{
    $err->log("post thing broke");
  }
?>
<link rel="stylesheet" type="text/css" href="general.css">
<title>My Student Management System</title>
</head>
<body>
  <div class = "Container">
    <h1 style = "width: 75%;">Clue Bastle</h1>
    <div style = "width: 25%;">
      <table>
        <tr style = "height: 36px;">
          <td>Hello</td>
          <td><?php echo $_SESSION["username"]."!";?></td>
        </tr>
        <tr style = "height: 12px;">
          <td></td>
          <td><a href = "Student_login.php">Sign Out</a></td>
        </tr>
      </table>
    </div>
  </div>
  <nav>
    <a href= "home_screen.php"><div>My Details</div></a>
    <a href = "my_modules.php"><div>My Modules</div></a>
    <a href = "Assessments.php"><div>My Assessments</div></a>
    <a href = "Enrollment.php"><div>Module Enrollment</div></a>
  </nav>
  <div class = 'Container'>
    <div id="Personal_Details">
      <h2>Personal Details</h2>
      <table>
        <tr>
          <td>Address:</td>
          <?php
            if ($address = $conn->query($getAddr)){
              while($row = $address->fetch_row()){
                if(!empty($row[0])){
                  echo "<td>".$row[0]."<br>".$row[1]."<br>".$row[2]."<td></tr>";
                  echo "<tr><td></td><td><button class = 'button' onclick='openForm(0)'>Edit Address</button></td></td>";
                }else{
                  echo '<td><button class = "button"  onclick = "openForm(0)">Add Address</button></td>';
                }
              }
            }
          ?>
        </tr>
      </table>
      <form class = 'hidden_form' method = 'POST' action = <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
        <table>
          <tr>
            <td>Line 1:</td>
            <td><input type = 'text' name = 'address1'></input></td>
          </tr>
          <tr>
            <td>Line 2:</td>
            <td><input type = 'text' name = 'address2'></input></td>
          </tr>
          <tr>
            <td>Post Code</td>
            <td><input type = 'text' name = 'postcode'></input></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type = 'submit' class = 'button' name ='edit_address' value = 'Save'></input>
              <button class = 'button' onclick="closeForm(0)">Cancle</button>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div id= "Student_Details">
      <h2>Student Details</h2>
      <table>
        <tr>
          <td>Student Number:</td>
          <td><?php echo $studentID;?></td>
        </tr>
        <tr>
          <td>Course:</td>
          <td>
            <?php
            if ($course = $conn->query($getCourse)){
              while($row = $course->fetch_assoc()){
                echo $row["course"];
              }
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>Username: </td>
          <td><?php echo $_SESSION["username"];?></td>
        </tr>
        <tr>
          <td></td>
          <td><button class = "button" onclick="openForm(1)">Change Username</button></td>
        </tr>
        <tr>
          <td></td>
          <td><button class = "button" onclick="openForm(2)">Change Password</button></td>
        </tr>
      </table>
      <form class = "hidden_form" method = "POST" action = <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
        <table>
          <tr>
            <td>New Username:</td>
            <td><input type = "text" name = "username"></input></td>
          </tr>
          <tr>
            <td>Password:</td>
            <td><input type = "password" name = "password"></input></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input class = button type = submit name = "change_username" value= "Change Username"></input>
              <input class = button type = submit name = "close_form[1]" value = "Cancle"></input>
            </td>
          </tr>
        </table>
      </form>
      <form class = "hidden_form" method = "POST" action = <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
        <table>
          <tr>
            <td>Old Password:</td>
            <td><input type = "password" name = "old_password"></input></td>
          </tr>
          <tr>
            <td>New Password:</td>
            <td><input type = "password" name = "new_password"></input></td>
          </tr>
          <tr>
            <td>Confirm Password:</td>
            <td><input type = "password" name = "confirm_password"></input></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input class = button type = submit name = "change_password" value= "Change Password"></input>
              <input class = button type = submit name = "close_form[2]" value = "Cancle"></input>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <script>
    hiddenforms = document.getElementsByClassName("hidden_form");
    function openForm(form) {
      var i;
      for(i = 0; i<hiddenforms.length;i++){
        hiddenforms[i].style.display = "none";
      }
      hiddenforms[form].style.display = "block";
    }
    function closeForm(form) {
      hiddenforms[form].style.display = "none";
    }
  </script>
</body>
</html>
