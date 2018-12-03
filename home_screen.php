<?php session_start();?>
<html>
<head>
<?php
include('sqlQueries.php');
$studentID = $_SESSION["studentID"];
$getAddr = "select address1, address2, postcode from students where studentID = '".$studentID."'";
$getCourse = "select course from courses inner join students on students.courseID = courses.courseID where studentID = ".$studentID.""?>
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
          <td>
            <?php
            if ($address = $conn->query($getAddr)){
              while($row = $address->fetch_row()){
                echo $row[0]."<br>".$row[1]."<br>".$row[2];
              }
            }?>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><button class = "button">Edit Address</button></td>
        </tr>
      </table>
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
          <td><?php
          if ($course = $conn->query($getCourse)){
            while($row = $course->fetch_assoc()){
              echo $row["course"];
            }
          }
          ?></td>
        </tr>
        <tr>
          <td>Username: </td>
          <td><?php echo $_SESSION["username"];?></td>
        </tr>
        <tr>
          <td></td>
          <td><button class = "button">Change Username</button></td>
        </tr>
        <tr>
          <td></td>
          <td><button class = "button">Change Password</button></td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
