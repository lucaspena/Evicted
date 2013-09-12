<!DOCTYPE HTML>
<?php include 'header.php' ?>
<link rel="stylesheet" type="text/css" href="styles.css">

<title>Evicted</title>

<body>
<div class="wrapper">
</div>

<div class="main">
<table>

<tr>
<td style="text-align:center;"><h2>Sign Up</h2></td>
</tr>

<tr>
<td><form action="createaccount.php" method="POST">
    <table>
      <tr>
	<td>First Name</td>
	<td><input type="text" name="fname"></td>
      </tr>
      <tr>
	<td>Last Name:</td>
	<td><input type="text" name="lname"></td>
      </tr>
      <tr>
	<td>Username:</td>
	<td><input type="text" name="username"></td>
      </tr>
      <tr>
	<td>Password:
	<td><input type="password" name="pass1"></td>
      </tr>
      <tr>
	<td>Re-enter Password:
	<td><input type="password" name="pass2"></td>
      </tr>
      <tr>
	<td>Starting Location:
	<td><select name="location">
	    <option value="phila">Philadelphia</option>
	    <option value="cambridge">Cambridge</option>
	    <option value="ny">New York</option>
	    <option value="seattle">Seattle</option>
	    <option value="la">Los Angeles</option>
	    <option value="ann">Ann Arbor</option>
	</select></td>
      </tr>
      <tr>
	<td><input type="submit" value="Sign Up" class="submit"></td>
      </tr>
  </form>
</td>
</tr>
</table>

</div></body>
</html>
