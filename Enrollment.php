<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="general.css"></link>
  <?php
  include "sqlQueries.php";
  $modules = "select modules.moduleID, module, lecturer, credits from modules inner join plans on plans.moduleID = modules.moduleID inner join courses on courses.courseID = plans.courseID where courses.courseID = ".$_SESSION["courseID"];
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
  if(!empty($_POST["apply_filter"])){
    $modules .=" and module like '%".$_POST["keyword"]."%'";
    $err->log($modules);
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
  <h2>Module Enrolment</h2>
  <form method ="post" action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
    <h4>Filters</h4>
    <div class = "Container">
      <div class = "innerElement">
        <p>keyword</p>
        <input type= "text" name ="keyword" style ="color:black;"></input>
      </div>
      <div class = "innerElement">
        <p>lecturer</p>
        <select name ="lecturer">
          <option value = 'any'>any</option>
          <?php
          $result = $conn->query($lecturers);
          if ($result->num_rows>0){
            while ($row = $result->fetch_row()){
              echo "<option value ='".$row[0]."'>".$row[0]."</option>";
            }
          }else{
            $err->log("lecturers empty: ".mysqli_error($conn));
          }
          ?>
        </select>
      </div>
      <div class = "innerElement">
        <p>credits</p>
        <select name ="credits">
          <option value = "any">any</option>
          <?php
          $result = $conn->query($credits);
          if ($result->num_rows>0){
            while ($row = $result->fetch_row()){
              echo "<option value =".$row[0].">".$row[0]."</option>";
            }
          }else{
            $err->log("credits empty: ".mysqli_error($conn));
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
                if (!empty($_POST["apply_filter"])){
                  if($row["lecturer"] == $_POST["lecturer"] or $_POST["lecturer"] == 'any'){
                    if($row["credits"] == $_POST["credits"] or $_POST["credits"] == 'any'){
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
                  }
                }else{
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
              }
            }else{
              $err->log("result empty".mysqli_error($conn));
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
