<?php session_start();?>
<html>
<head>
<title>My Student Management System</title>
<link rel="stylesheet" type="text/css" href="login_styles.css">
</head>
<body>
  <?php include "login_script.php";?>
	<div class="center_screen">
		<div id="banner">
	    <h1>Student Login</h1>
    </div>
    <div id = "form">
    <form method="post" action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
      <table>
        <tr>
          <td><p class = "left">Username</p></td>
          <td><input type="text" name="username" size="18"></td>
        </tr>
        <tr>
          <td><p class = "left">Password</p></td>
          <td><input type ="password" name="password" size="18"></td>
        <tr>
          <td></td>
          <td><input class = "button" type="submit" value="Login"></td>
        </tr>
      </table>
    </form>
    </div>
    <div>
      <button class = button onclick="location.href = 'create_user.php';">Create Login</button>
    </div>
  </div>
</body>
</html>
