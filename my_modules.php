<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
<?php
include('sqlQueries.php');
$studentID = $_SESSION["studentID"];
$getModules = "select module, lecturer, credits from modules, enrolment, students where enrolment.studentID = ".$studentID." and modules.moduleID = enrolment.moduleID";
?>
<link rel="stylesheet" type="text/css" href="general.css"></link>
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
    <a><div>My Assessments</div></a>
    <a href = "Enrollment.php"><div>Module Enrollment</div></a>
  </nav>
  <h2>My Modules</h2>
    <?php
    if ($modules = $conn->query($getModules)){
      if($modules->num_rows()>0){
        while($row = $modules->fetch_assoc()){
            echo "<div class = box><div class = collapsible><div class = 'left'>".$row["module"]."</div><div class = 'right'>+</div></div>";
            echo "<div class = content><p>The lecturer for this module is: ".$row["lecturer"]."</p>";
            echo "<p>This module is worth: ".$row["credits"]." credits</p></div></div>";
          }
        }else{
          $err->log("no modules");
          echo "<h3>You have not enrolled onto any modules yet!</h3>";
        }
    }else{
      $err->log("query fail");
    }
    ?>
  <button class= "button" value = "enroll onto modules"onclick="window.location.href='Enrollment.php'"></button>
</body>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  }
  );
}
</script>
</html>
