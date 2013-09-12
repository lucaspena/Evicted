<!DOCTYPE HTML>
<?php include 'header.php' ?>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<title>Evicted</title>

<body>
  <div class="wrapper">

  <?php
   $user = $_POST["username"];
   $pass1 = $_POST["pass1"];
   $pass2 = $_POST["pass2"];
   if ($pass1 != $pass2) {
      echo "<h2>Password Mismatch!</h2>";
      echo "Did you want to have to keep track of two passwords?";
   } else if (strlen ($pass1) < 6) {
      echo "<h2>Your password must be at least 6 characters long!</h2>";
      echo "You should probably try again...";
   } else if (strlen ($user) < 4 ) {
      echo "<h2>Your username must be at least 4 characters long!</h2>";
      echo "You probably want to fix that...";
   } else {
   $fname = $_POST["fname"];
   $lname = $_POST["lname"];
   $location = $_POST["location"];

   $xcoord = 0;
   $ycoord = 0;
   $b = "A";

   if ($location == "ann") {
     $b = "OoCzKO4aeGr6Kq39m5Hcjg";
   } else if ($location == "cambridge") {
     $b = "FLF1GSMzylSaHM6iDHnkbw";
   } else if ($location == "ny") {
     $b = "fco4qToL2DhnVamv0yaW4w";
   } else if ($location == "seattle") {
     $b = "XYcht_y51ZsZdUJgam0e3A";
   } else if ($location == "la") {
     $b = "KXjkZVuH001Fo2FYXmjGfw";
   } else {
     $b = "bjkA3ustWdg3EsTLRiUNKA";
   }

   $hunger = 10;
   $health = 90;
   $comfort = 30;
   $satisfaction = 30;
   $energy = 50;
   $social = 50;
   $money = 100;

   $con = mysqli_connect("fling.seas.upenn.edu","tmandel","abc123","tmandel");
   
   if (mysqli_connect_errno()) {
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   
   $q = "INSERT INTO Authentication VALUES ('" . $user . "','" . MD5($pass1) . "')";
   
   ($r = mysqli_query($con, $q)) or die("<h2>Username Taken!</h2>Try again.");

   $qu = "INSERT INTO Users VALUES ('" . $user . "','" . $fname . "','" . $lname . "'," . $hunger . "," . $health . "," . $comfort . "," . $satisfaction . "," . $energy . "," .  $money . "," . $social . ",'" . $b . "')";

   ($ru = mysqli_query($con, $qu)) or die("User doesn't exist.");


   session_start();
   $_SESSION['username'] = $user;
   mysqli_close($con);
   header("location:game.php");
   }
  ?>
<BR>
</div>
</body>
</html>
