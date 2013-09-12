<?php
  $c = mysqli_connect("fling.seas.upenn.edu","tmandel","abc123","tmandel");
  $name = $_POST["username"];
  $b = $_POST["b"];
  $t = $_POST["t"];  
  $w = $_POST["w"];
  $r = $_POST["r"];
  
  $check = true;

// business and user information
  $old = "SELECT xcoord, ycoord, hunger, health, city, money, comfort, satisfaction, social, energy FROM Business, Users WHERE username='$name' AND busid=businessid";
  ($oldloc = mysqli_query($c, $old)) or die("CAN'T UPDATE");
  $oldcoords = mysqli_fetch_row($oldloc);

// business information
  $new = "SELECT xcoord, ycoord, city FROM Business WHERE businessid='$b'";
  ($newloc = mysqli_query($c, $new)) or die("CAN'T UPDATE");
  $newcoords = mysqli_fetch_row($newloc);
  
  $dist = sqrt(pow($newcoords[0] - $oldcoords[0], 2) + pow($newcoords[1] - $oldcoords[1], 2)) * 300;

  $hunger = $oldcoords[2];

// for moving
  if ($dist > 80) {
    $hunger = max(min(max($hunger + (($dist * 1.5) / 20) - rand(100, 110), $hunger), 100), 0);
  }

  $hunger = min($hunger + rand(8, 11), 100);
  
  $satis = $oldcoords[7];
  $soc = $oldcoords[8];
  $fatig = $oldcoords[9];
  $comfort = $oldcoords[6];

// weather
  if ($w > 15) {
    $comfort = $comfort - ($w - 15);
  }
  if ($t < 45.0) {
    $comfort = $comfort - (45 - $t);
  } else if ($t > 90.0) {
    $comfort = $comfort - ($t - 90);
  } else {
    $comfort = $comfort + rand(2, 4);
  }
  if ($r > 0.0) {
    $comfort = $comfort - ceil($r * 3);
  }
  $comfort = min(100, max(0, $comfort));

  $health = $oldcoords[3];
// for moving
  if ($dist > 80) {
    $fatig = max(min(max($fatig + (($dist * 1.5) / 20) - rand(100, 110), $fatig), 100), 0);
  }

  $fatig = min($fatig + rand(4, 7), 100);

  $money = $oldcoords[5];
  if ($oldcoords[4] != $newcoords[2]) {
    $money = $money - ($dist / 50 + rand(4, 8));
    $check = $money >= 0;
  }

// if query is to execute
  if ($check) {
    $health = max ($health - 1, 0);
    if ($hunger > 80) {
      $health = max ($health - (($hunger * 1.5) / 20), 0);
    }
    if ($comfort == 0) {
      $health = max ($health - 1, 0);
    }
    if ($satis < 5) {
      $health = max ($health - 1, 0);
    }
    if ($satis > 95) {
      $health = min ($health + 1, 100);
    }
    if ($soc < 5) {
      $health = max ($health - 1, 0);
    }
    if ($soc > 95) {
      $health = min ($health + 1, 100);
    }
    $update = "UPDATE Users SET busid='$b', money=$money,  hunger=$hunger, health=$health, comfort=$comfort, energy=$fatig WHERE username='$name'";
    ($rupdate = mysqli_query($c, $update)) or die("Location Not Updated!");
  }

// death
  if ($check && $health == 0) {
    mysqli_query($c, "DELETE FROM Users WHERE username='$name'") or die("dead");
    mysqli_query($c, "DELETE FROM Authentication WHERE username='$name'") or die ("dead");
    mysqli_close($c);
    die("dead");
  }
  mysqli_close($c);
?>
