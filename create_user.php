<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="create_user.css">
  <?php
  include("sqlQueries.php");
  include("create_user_script.php");
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
