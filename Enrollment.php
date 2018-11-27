<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="general.css"></link>
  <?php
  include "sqlQueries.php";
  $stmt = "select module, lecturer, credits from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  $lecturers = "select distinct lecturer from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  $credits = "select distinct credits from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  $err->log($_SESSION["courseID"]);
  ?>
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
  <form>
    <div class = "Container">
      <div class = "inner_element">
        <h4>Filters</h4>
      </div>
      <div class = "inner_element">
        <p>keyword</p>
        <input type= "text" name ="keyword"></input>
      </div>
      <div class = "inner_element">
        <p>lecturer</p>
        <select name ="lecturer">
          <option value = "none"></option>
          <?php
          $result = $conn->query($lecturers);
          if ($result->num_rows>0){
            while ($row = $result->fetch_row()){
              echo "<option value ='".$row[0]."'>'".$row[0]."'</option>";
            }
          }else{
            $err->log("lecturers empty");
          }
          ?>
        </select>
      </div>
      <div class = "inner_element">
        <p>credits</p>
        <select name ="credits">
          <option value = "none"></option>
          <?php
          $result = $conn->query($credits);
          if ($result->num_rows>0){
            while ($row = $result->fetch_row()){
              echo "<option value =".$row[0].">".$row[0]."</option>";
            }
          }else{
            $err->log("credits empty");
          }
          ?>
        </select>
      </div>
      <input type ="submit" name ="apply_filter" value="apply filter" class = "button" style = "align-self:flex-end;"></input>
    </div>
  </form>
  <div class = "Container">
    <div class = "inner_element" style="width:100%;">
      <form>
        <table class = "fixed_header">
          <thead>
            <tr>
              <th><input type="checkbox" name="all" onclick=toggle(this)></input></th>
              <th style="width:200px; text-align: center;">Title</th>
              <th>Lecturer</th>
              <th>Credits</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = $conn->query($stmt);
            if ($result->num_rows>0){
              $i = 0;
              while ($row = $result->fetch_assoc()){
                echo "<tr><td><input type = 'checkbox' name = row".$i." class = 'sub_checkbox'></td>";
                foreach($row as $data){
                  echo "<td>".$data."</td>";
                }
                echo "</tr>";
                $i = $i + 1;
              }
            }else{
              $err->log("result empty");
            }
            ?>
          </tbody>
        </table>
        <input type="submit" class="button" value="Enroll" name="enroll" style="margin: 10px;"></input>
      </form>
    </div>
    <!-- <div class = "inner_element" style="width:50%;">
      <h2>Description</h2>
      <p>aslfdkjasdlk fjaslfkjasldfk jsadlfkjasd flskafjlasfja slfkjasldfka sjdfl ;kasjfdl sadlfkjasldkfj  l;kasdjfl;a asdl;fkjaksl ;lkasdjf l laskdjf laksjfd lkasdjf lkasdf alk jslkadf aslkf jlaskdjf lkasj fdl</p>
    </div> -->
  </div>
</body>
<script>
function toggle(source) {
  checkboxes = document.getElementsByClassName('sub_checkbox');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</html>
