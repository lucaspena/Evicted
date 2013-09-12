<!DOCTYPE HTML>
<div class="header">
  <a href="index.php"><img src="evictedlogo.png"></a>
</div>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<title>Evicted</title>

<body>
  <div class="wrapper">
    <h2>GAME OVER</h2>You have died, so we deleted you. Try to stay alive next time. You can sign up again on the homepage.<BR/>
  </div>
</body>
</html>

<?php
   session_start();
   session_destroy();
?>


