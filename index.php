<!DOCTYPE HTML>
<?php include 'header.php' ?>
<link rel="stylesheet" type="text/css" href="styles.css">

<title>Evicted</title>

<body>
<div class="wrapper">
</div>

<div class="main">

<table class="begin">

<tr>
<td style="text-align:center;"><h2>Login</h2></td>
</tr>
<tr>
<td><form action="checklogin.php" method="POST">
    <table>
      <tr>
	<td>Username:</td>
	<td><input type="text" name="username"></td>
      </tr>
      <tr>
	<td>Password:</td> 
	<td><input type="password" name="password"></td>
      </tr>
    </table>
    <input type="submit" class="submit" value="Login">
</form>
<p><a href="signup.php">Click here</a> to register</p>
<p></p>

</td>


</tr>
</table>

</div></body>
</html>
