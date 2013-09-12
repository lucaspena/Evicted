<!DOCTYPE html>
<title>Evicted</title>
<?php 
   session_start();
   if (!isset($_SESSION['username'])) {
      header("location:index.php");
   }
   $name = $_SESSION['username'];
?>

<html>
  <head>
  <?php $c = mysqli_connect("fling.seas.upenn.edu","tmandel","abc123","tmandel"); ?>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css" />
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyITdCyExBH6Tpl2UunuqeGmxJ8ZiUgTQ&sensor=true">
  </script>
  <script type="text/javascript">
     // initialize Google Maps 
     function initialize(arr) {
        var point = new google.maps.LatLng(arr[0], arr[1]);
        var mapOptions = {
          center: point,
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map;
        map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

        var marker = new google.maps.Marker({
          //icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
          position: new google.maps.LatLng(arr[0], arr[1]),
          map: map,
          title: 'Current Location'
        });
      
        for (var i = 2; i < arr.length; i += 4) {
          var myLatLng = new google.maps.LatLng(arr[i], arr[i + 1]);
          var marker = new google.maps.Marker({
            icon: 'http://www.google.com/intl/en_ALL/mapfiles/marker_green'+String.fromCharCode((i - 2)/4 + 65)+'.png',
            position: myLatLng,
            map: map,
            title: arr[i + 2]
          });
        }
      }
 
      google.maps.event.addDomListener(window, 'load', initialize);

      // fill table
      function repopulate(uname, tableID, arr, rain, feels, wind) {
        try {
          var table = document.getElementById(tableID);
          var rowCount = table.rows.length;
          
          for (var i = 1; i < rowCount; i++) {
            console.log(rowCount);
            table.deleteRow(1);
          }
        } catch (e) { alert(e); }

        for (var i = 2; i < arr.length; i += 4) {
          var row = table.insertRow((i - 2) / 4 + 1);
          
         var cell = row.insertCell(0);
         cell.innerHTML = "<button class='suggestions' onclick='updateLocation(\"" + uname + "\", " 
             + arr[i] + ", " + arr[i+1] + ", \"" + arr[i+3] + "\", " + rain + ", " + feels + ", " + wind + "); return false;'>"+ String.fromCharCode((i - 2)/4 + 65)
             + " : " + arr[i+2] + "</button>";
        }
      }

      // reload page after timeout
      function delayedRedirect() {
        location.reload();
      }

      //Updates location, reinitializes, and reloads page
      function updateLocation(uname, newx, newy, bid, rain, feels, wind){ 
        $.post('update.php', { username: uname, b: bid, r: rain, t: feels, w: wind }).done(function(data) {
          if (data === "dead") {
            window.location = "death.php";
          } else {
            initialize([newx, newy]);
            setTimeout(delayedRedirect, 1000);
          }
        }, "json");
      }

                            
      //Updates user stats
      function updateStats(uname, bid, cat){
        $.post('changestats.php', { username: uname, b: bid, c: cat }).done(function(data) {
            if (data === "dead") {
              window.location = "death.php";
            } else {
              setTimeout(delayedRedirect, 1000);
            }
          }, "json");        
      }

  </script>
</head>

  <?php
// death
     mysqli_query($c, "SELECT busid FROM Users WHERE username='$name'") or die("<h2>GAME OVER</h2><p>You have died. Create a new account if you would like to try again.</p>"); 

// user stats
     $get_coords = "SELECT xcoord, ycoord, city, state FROM Users, Business WHERE username = '$name' AND busid = businessid";
     ($coord_res = mysqli_query($c, $get_coords)) or die("Can't get coordinates");
  
     $row = mysqli_fetch_row($coord_res);
     $xco = $row[0];
     $yco = $row[1];
// initialize points for Google Maps
     $p_pts = array($xco, $yco);
     $j_pts = json_encode($p_pts);
     $phun_pts = array($xco, $yco);
     $jhun_pts = json_encode($phun_pts);
     $pmed_pts = array($xco, $yco);
     $jmed_pts = json_encode($pmed_pts);
     $pcom_pts = array($xco, $yco);
     $jcom_pts = json_encode($pcom_pts);
     $psoc_pts = array($xco, $yco);
     $jsoc_pts = json_encode($psoc_pts);
     $part_pts = array($xco, $yco);
     $jart_pts = json_encode($part_pts);
     $pstr_pts = array($xco, $yco);
     $jstr_pts = json_encode($pstr_pts);
     $pmov_pts = array($xco, $yco);
     $jmov_pts = json_encode($pmov_pts);
     $pslp_pts = array($xco, $yco);
     $jslp_pts = json_encode($pslp_pts);

     echo "<body onload='initialize(" . $j_pts . ")'>";

// Weather
     $city = str_replace(" ", "_", $row[2]);
     $command = "python weather.py $city $row[3]";
     $location = exec($command);  
// TATES API KEY: b2d3e1bf55db753a
     $json_string = file_get_contents("http://api.wunderground.com/api/8535d5a9b64d9c3d/geolookup/conditions/q/" . $location . ".json");
     $parsed_json = json_decode($json_string);
     $temp_f = $parsed_json->{'current_observation'}->{'temp_f'};
     $rain = $parsed_json->{'current_observation'}->{'precip_today_in'};
     $wind = $parsed_json->{'current_observation'}->{'wind_mph'};
     $feelslike = $parsed_json->{'current_observation'}->{'feelslike_f'};
     $stats = "SELECT * FROM Users WHERE username = '$name'";
     ($rstats = mysqli_query($c, $stats)) or die("OOPS, Something went wrong!");
     $row = mysqli_fetch_row($rstats);

// get options      
      $hun = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
             "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'hun' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
      $med = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
             "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'med' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
      $soc = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
             "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'soc' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
      $art = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
             "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'art' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
      $str = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
             "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'str' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
      $mov = "SELECT distinct (city), xcoord, ycoord, state, businessid FROM Business WHERE category <> 'oth' AND city <> '$row[2]' GROUP BY city ORDER BY rand() LIMIT 0,5";
      $slp = "SELECT xcoord, ycoord, businessname, businessid FROM Business WHERE xcoord <= $xco + 0.05 AND xcoord >= $xco - 0.05 AND " .
        "ycoord <= $yco + 0.05 AND xcoord >= $yco - 0.05 AND category = 'slp' AND businessid <> '$row[10]' ORDER BY rand() LIMIT 0,5";
                              
      ($rhun = mysqli_query($c, $hun)) or die("Can't get the 5 businesses-hun");
      while($row1 = mysqli_fetch_row($rhun)){
        array_push($phun_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jhun_pts = json_encode($phun_pts);
      }
      ($rmed = mysqli_query($c, $med)) or die("Can't get the 5 businesses-med");
      while($row1 = mysqli_fetch_row($rmed)){
        array_push($pmed_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jmed_pts = json_encode($pmed_pts);
      }
      ($rsoc = mysqli_query($c, $soc)) or die("Can't get the 5 businesses-soc");
      while($row1 = mysqli_fetch_row($rsoc)){
        array_push($psoc_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jsoc_pts = json_encode($psoc_pts);
      }
      ($rart = mysqli_query($c, $art)) or die("Can't get the 5 businesses-art");
      while($row1 = mysqli_fetch_row($rart)){
        array_push($part_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jart_pts = json_encode($part_pts);
      }
      ($rstr = mysqli_query($c, $str)) or die("Can't get the 5 businesses-str");
      while($row1 = mysqli_fetch_row($rstr)){
        array_push($pstr_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jstr_pts = json_encode($pstr_pts);
      }
      ($rmov = mysqli_query($c, $mov)) or die("Can't get the 5 businesses-mov");
      while($row1 = mysqli_fetch_row($rmov)){
        array_push($pmov_pts, $row1[1], $row1[2], $row1[0] . ", " . $row1[3], $row1[4]);
        $jmov_pts = json_encode($pmov_pts);
      }
      ($rslp = mysqli_query($c, $slp)) or die("Can't get the 5 businesses-mov");
      while($row1 = mysqli_fetch_row($rslp)){
        array_push($pslp_pts, $row1[0], $row1[1], htmlspecialchars($row1[2], ENT_QUOTES), $row1[3]);
        $jslp_pts = json_encode($pslp_pts);
      }
// businessname for stat table
     $busid = "SELECT businessname FROM Business where businessid = '$row[10]'";
     ($bn = mysqli_query($c, $busid)) or die("WE CAN'T FIND YOU");
     $r = mysqli_fetch_row($bn);

     $four  = 100 - $row[3];
     $five  = 100 - $row[4];
     $six   = 100 - $row[5];
     $sev   = 100 - $row[6];
     $eight = 100 - $row[7];
     $ten   = 100 - $row[9];

// stat table
     echo "<div id='leftside'>";
     echo "<img src='evictedlogo.png' height='50' style='float:left;'/><form action='logout.php' class='logout' ><input type='submit' class='submit' value='Logout'></form><br/>";
     echo "<table id='left'><tr>" . 
        "<td><div id='status'>" .
          "<table>" .
            "<tr><td><strong>Stats</strong></td><td/></tr>" .
            "<tr><td>Username</td><td>$row[0]</td></tr>" .
            "<tr><td>Name</td><td>$row[1] $row[2]</td></tr>" .
            "<tr><td>Money</td><td>$$row[8]</td></tr>" .
            "<tr><td>Hunger</td><td><img src='bar_red2.png' height=10px width=$row[3]px><img src='bar2.png' height=10px width=$four px> ($row[3])</td></tr>" .
            "<tr><td>Fatigue</td><td><img src='bar_red2.png' height=10px width=$row[7]px><img src='bar2.png' height=10px width=$eight px> ($row[7])</td></tr>" .
            "<tr><td>Health</td><td><img src='bar_grn.png' height=10px width=$row[4]px><img src='bar2.png' height=10px width=$five px> ($row[4])</td></tr>" .
            "<tr><td>Comfort</td><td><img src='bar_grn.png' height=10px width=$row[5]px><img src='bar2.png' height=10px width=$six px> ($row[5])</td></tr>" .
            "<tr><td>Satisfaction</td><td><img src='bar_grn.png' height=10px width=$row[6]px><img src='bar2.png' height=10px width=$sev px> ($row[6])</td></tr>" .
            "<tr><td>Social</td><td><img src='bar_grn.png' height=10px width=$row[9]px><img src='bar2.png' height=10px width=$ten px> ($row[9])</td></tr>" .
            "<tr><td>Temperature</td><td>$temp_f</td></tr>" .
          "</table><BR>";
      echo "</div></td>";
      echo "<td><div id='buttons'>";

// add rows to table
      echo ("<button class='options' onclick='initialize(" . $jhun_pts . "); repopulate(\"$name\", \"placesTable\", $jhun_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Get Food</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jmed_pts . "); repopulate(\"$name\", \"placesTable\", $jmed_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Be Healthy</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jsoc_pts . "); repopulate(\"$name\", \"placesTable\", $jsoc_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Go Out</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jart_pts . "); repopulate(\"$name\", \"placesTable\", $jart_pts, \"$rain\", \"$feelslike\", \"$wind\")'>See the Sights</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jstr_pts . "); repopulate(\"$name\", \"placesTable\", $jstr_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Work Out</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jmov_pts . "); repopulate(\"$name\", \"placesTable\", $jmov_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Move Away</button><br/>");
      echo ("<button class='options' onclick='initialize(" . $jslp_pts . "); repopulate(\"$name\", \"placesTable\", $jslp_pts, \"$rain\", \"$feelslike\", \"$wind\")'>Go Rest</button><br/>");

      echo "</div></td></tr></table>";

// table of choices
      echo "<div id='info'>" .
             "<table id='placesTable' width='350px'>" .
               "<tr><td><strong>Your Choices</strong></td></tr>" .
             "</table>" .    
           "</div>" .
         "</div>";

// business info
      $currbus = "SELECT fullAddress, photourl, reviewCount, url, rating, category FROM Business WHERE businessid = '$row[10]'";
      ($loca = mysqli_query($c, $currbus)) or die("YOUR LOCATION IS OUT OF BOUNDS");
      $busrow = mysqli_fetch_row($loca);                        

      mysqli_close($c);


      $comma = "python category.py $busrow[5]";
      $but = exec($comma);

// business information
      echo "<div id='rightside'>";

      echo "<div id='map-canvas'></div><br/>";

      echo "<div id='currlocation'>" .
           "<table id='locinfo'>" .
             "<tr>" .
               "<td><img src='$busrow[1]'/></td>" .
               "<td>" .
                 "<table id='infotable'>" .
                   "<tr>&nbsp;&nbsp;&nbsp;&nbsp;Business Name: $r[0]</tr><br/>" .
                   "<tr>&nbsp;&nbsp;&nbsp;&nbsp;Address: $busrow[0]</tr><br/>" .
                   "<tr>&nbsp;&nbsp;&nbsp;&nbsp;Rating: $busrow[4] ($busrow[2] reviews)</tr><br/>" .
                   "<tr>&nbsp;&nbsp;&nbsp;&nbsp;Business URL: <a href='$busrow[3]' target='_blank'>$busrow[3]</a></tr>" .
                 "</table>" .
               "</td>" .
             "</tr>" .
             "<tr>" .
               "<td/><td>" .
                 "<button class='options' onclick='updateStats(\"$name\", \"$row[10]\", \"$busrow[5]\")'>$but</button>&nbsp;&nbsp;&nbsp;" .
                 "<button class='options' onclick='updateStats(\"$name\", \"$row[10]\", \"wor\")'>Work</button>" .
               "</td>" .
             "</tr>" .
           "</table>" .
           "</div></div>";

  ?>
</body>
</html>
