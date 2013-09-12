<!DOCTYPE HTML>
<?php include 'header.php' ?>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<title>Evicted</title>

<body>
  <div class="wrapper">
    <h2>Logged In!</h2>
    
    <?php
       $user = $_POST["username"];
       $pass = $_POST["password"];
       $con = mysqli_connect("fling.seas.upenn.edu","tmandel","abc123","tmandel");
       
       if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
       }
// check if exists
       $query = "SELECT * FROM Authentication WHERE username = '" . $user . "' AND password = '" . MD5($pass) . "'";
       
       ($result = mysqli_query($con, $query)) or die("Couldn't execute query.");
       
       $count = mysqli_num_rows($result);
       if ($count == 1) {
          session_start();
          $_SESSION['username'] = $user;
          mysqli_close($con);
          header("location:game.php"); 
       } else {
          echo "WRONG";
          mysqli_close($con);
          header("location:index.php");
       }
    ?>
    <BR>
  </div>
</body>
</html>
