<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="create_user.css">
  <?php
  include("sqlQueries.php"); //connect
  $fetch_courses = "select course from courses";
  $courses = $conn->query($fetch_courses);
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //check usernmame
    $fetch_user = "select username from login";
    $result = $conn->query($fetch_user);
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
        $flags[$i]=1;
        $err->log($flags[$i]);
      }else{
        $flags[$i]=0;
        $err->log($flags[$i]);
      }
      $i = $i+1;
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
          <td style = "color:red"><?php if ($flags[0] === 1){echo "required";}?></td>
        </tr>
        <tr>
          <td>Last Name: </td>
          <td><input type="text" name="lastname" class = "input"></input></td>
          <td style = "color:red"><?php if ($flags[1] === 1){echo "required";}?></td>
        </tr>
        <tr>
          <td>Course:</td>
          <td><select name="course">

          <td style = "color:red"><?php if ($flags[2] === 1){echo "required";}?></td>
        <tr>
          <td>Username: </td>
          <td><input type="text" name="username" class = "input"></input></td>
          <td style = "color:red"><?php if ($flags[3] === 1){echo "required";}?></td>
        </tr>
        <tr>
          <td></td>
          <td><input class = "button" type="submit" value="Check Username" name="check"></input></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <p class = "error" id = "username">
              <?php if ($uflag){
                echo "<script> document.getElementById('username').style.display = 'visible';</script>";
                echo $uflag;
              }
              ?>
            </p>
          </td>
        </tr>
        <tr>
          <td>Password: </td>
          <td><input type = "password" name="password" class = "input"></input></td>
          <td style = "color:red"><?php if ($flags[4] === 1){echo "required";}?></td>
        </tr>
        <tr>
          <td>Confirm Password: </td>
          <td><input type="password" name = "cpassword" class = "input"></input></td>
          <td style = "color:red"><?php if ($flags[5] === 1){echo "required";}?></td>
        <tr>
          <td></td>
          <td><input type="submit" class="button" value="Create" name="create"></input></td>
        </tr>
      </table>
    </form>
</body>
</html>
