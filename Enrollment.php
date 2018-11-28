<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="general.css"></link>
  <?php
  include "sqlQueries.php";
  $modules = "select modules.moduleID, module, lecturer, credits from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  $lecturers = "select distinct lecturer from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  $credits = "select distinct credits from modules, plans, courses where plans.moduleID=modules.moduleID and plans.courseID = ".$_SESSION["courseID"];
  if (!empty($_POST["enrol"])){
    $err->log("enrol subimt -------");
    foreach($_POST["row"] as $moduleID){
      $enrol = "insert into enrolment (moduleID, studentID) values (".$moduleID.",".$_SESSION["studentID"].")";
      if($insert = $conn->query($enrol)){
        $err->log($_SESSION["username"]." has enrolled onto ".$moduleID);
        $enrolled = true;
      }else{
        $err->log("insert error: ".mysqli_error($conn));
      }
    }
  }
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
    <a href = "enrolment.php"><div>Module enrolment</div></a>
  </nav>
  <form>
    <h4>Filters</h4>
    <div class = "Container">
      <div class = "innerElement">
        <p>keyword</p>
        <input type= "text" name ="keyword"></input>
      </div>
      <div class = "innerElement">
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
      <div class = "innerElement">
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
    <div class = "innerElement" style="width:100%;">
      <h3>Available modules</h3>
      <form  method = "POST" action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
        <table class = "fixed_header">
          <thead>
            <tr>
              <th style="width:30px;"><input type="checkbox" name="all" onclick=toggle(this)></input></th>
              <th>Title</th>
              <th>Lecturer</th>
              <th>Credits</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = $conn->query($modules);
            if ($result->num_rows>0){
              $i = 0;
              while ($row = $result->fetch_assoc()){
                echo "<tr><td><input type = 'checkbox' name = 'row[".$i."]' value = '".$row["moduleID"]."' class = 'sub_checkbox'></td>";
                $first = true;
                foreach($row as $data){
                  if(!$first){
                    echo "<td>".$data."</td>";
                  }else{
                    $first = false;
                  }
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
        <input type="submit" class="button" value="Enrol" name="enrol" style="margin: 10px;"></input>
      </form>
      <p><?php if ($enrolled) {echo "Enrolment succesful!";}?></p>
    </div>
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
