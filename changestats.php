<?php
  $con = mysqli_connect("fling.seas.upenn.edu","tmandel","abc123","tmandel");
  $name = $_POST["username"];
  $b = $_POST["b"];
  $c = $_POST["c"];

// get stats
  $gets = "SELECT hunger, health, comfort, satisfaction, energy, money, social FROM Users WHERE username='$name'";
  ($stats = mysqli_query($con, $gets)) or die("BAD USERNAME AHHHHHH");

  $values = mysqli_fetch_row($stats);

// get reviews
  $getr = "SELECT text, rating FROM Review WHERE businessid='$b' ORDER BY rand() LIMIT 0, 5";
  ($rtext = mysqli_query($con, $getr)) or die("NO REVIEWS");

  $r1 = mysqli_fetch_row($rtext);
  $r2 = mysqli_fetch_row($rtext);
  $r3 = mysqli_fetch_row($rtext);
  $r4 = mysqli_fetch_row($rtext);
  $r5 = mysqli_fetch_row($rtext);

// get current new business information
  $geta = "SELECT rating, businessname, reviewCount FROM Business WHERE businessid='$b'";
  ($atext = mysqli_query($con, $geta)) or die("THERE IS NO RATING");

  $rating = mysqli_fetch_row($atext); 

// combine reviews for efficiency
  $allrevs = $r1[0] . " " . $r2[0] . " " . $r3[0] . " " . $r4[0] . " " . $r5[0];
  $arr = json_decode(exec("python fullparse.py \"$allrevs\""));
 
  $hunger  = $values[0];
  $health  = $values[1];
  $comfort = $values[2];
  $satis   = $values[3];
  $fatig   = $values[4];
  $money   = $values[5];
  $social  = $values[6];

// default update
  $update = "SELECT hunger FROM Users WHERE username='$name'";

// default check
  $check = true;

//hunger
  if ($c == "hun") {
    
    $satis = $satis + $arr->{'food'} + (($rating[0] - 50) / 2);
    $satis = min($satis, 100);
    
    $price = ($arr->{'price'}) * rand(6, 10);

    $hunger = $hunger - rand(50, 80);
    $hunger = max($hunger, 0);
    if ($hunger > 20 && $hunger < 35) {
        $fatig = max($fatig - rand(1, 2), 0);
    }
    $money = $money - $price; 
    $check = $money >= 0;     

    $health = $arr->{'health'} + $health;
    $health = max(0, min($health, 100));

// medical
  } else if ($c == "med") {
    $health2 = min (($r1[1] + $r2[1] + $r3[1] + $r4[1] + $r5[1]) / 10 + $health, 100);
    
    $price = ($arr->{'price'} + 2) * rand(6, 10);
    $money = $money - $price; 

    $check = $money >= 0 && $health < 100;
    $health = $health2;

// social
  } else if ($c == "soc") {
    $social = min (($r1[1] + $r2[1] + $r3[1] + $r4[1] + $r5[1]) / 10 + $social, 100);
    
    $price = ($arr->{'price'} + 1) * rand(5, 10);
    $money = $money - $price; 

    $health = $health - rand(3, 6);
    $health = max(0, $health);

    $fatig = $fatig + rand(4, 6);
    $fatig = min(100, $fatig);

    $check = $money >= 0;

// satisfaction
  } else if ($c == "art") {
    
    $satis = $arr->{'satis'} + $satis + ($rating[0] / 2);
    $satis = min($satis, 100);      
    
    $price = ($arr->{'price'} + 1) * rand(3, 5);
    $money = $money - $price; 
    
    $fatig = $fatig + rand(1, 3);
    $fatig = min($fatig, 100);
    $check = $money >= 0;

// fatigue  
  } else if ($c == "str") {
    $price = 0;
    if (stristr($rating[1], "gym") || stristr($rating[1], "fit") || 
        stristr($rating[1], "train") || stristr($rating[1], "club") ||
        stristr($rating[1], "sport") || stristr($rating[1], "studio")) { 

      $price = ($arr->{'price'} + 1) * rand(1, 2);
    } 
    $money = $money - $price; 
    $check = $money >= 0;

    $satis = $arr->{'satis'};

    $health = min (($r1[1] + $r2[1] + $r3[1] + $r4[1] + $r5[1]) / 60 + $health, 100);
    $fatig = min ($fatig + rand(25, 40), 100);

// rest    
  } else if ($c == "slp") {
    
    $satisf = $arr->{'satis'};
    $fatig = max($fatig - ($satisf + rand(50, 80)), 0);
    
    $price = ($arr->{'price'} + 1) * rand(15, 24);
    $money = $money - $price;
    $hunger = $hunger + rand(25, 40);

    $check = $money >= 0;

// work
  } else if ($c == "wor") {
    $price = $arr->{'price'};    
    $satisf = $arr->{'satis'};
    $parse = $arr->{'food'};
    $money = $price + $satisf + $parse + $money + rand(15, 25) + $rating[2] / 2;
    
    $satis = max($satis + $satisf - rand(35, 50), 0);
    $social = max($social - max(40 - $rating[2], 10), 0);
    $fatig = min($fatig + $rating[2] / 5 + rand(25, 30), 100);
    
  }

$health = $health - 1;

// if changing
  if ($check) { 
    if ($c != "hun") {
      $hunger = $hunger + rand(5,10);
      if ($c == "wor") {
        $hunger = $hunger + rand(10, 20);
      }
      if ($hunger > 80) {
        $health = max ($health - (($hunger * 1.5) / 20), 0);
      }
      if ($fatig > 80) {
        $health = max($health - (5 - ((100 - $fatig) / 5)), 0);
      }
    }
    $hunger = min ($hunger, 100);
    $update = "UPDATE Users SET hunger=$hunger, health=$health, comfort=$comfort, satisfaction=$satis, energy=$fatig, money=$money, social=$social WHERE username='$name'";
    mysqli_query($con, $update) or die("DEAD");
  }

// death
  if ($check && $health <= 0) {
    mysqli_query($con, "DELETE FROM Users WHERE username='$name'") or die("YOU DIED!");
    mysqli_query($con, "DELETE FROM Authentication WHERE username='$name'") or die ("YOU ARE DEAD");
    mysqli_close($con);
    die("dead");
  }
  mysqli_close($con);
?>
