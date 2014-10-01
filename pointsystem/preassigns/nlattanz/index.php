<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Kimball Hall</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="../../../bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="../../../css/flat-ui.css" rel="stylesheet">

    <link rel="shortcut icon" href="../../../images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../../js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="demo-headline">
        <h1 class="demo-logo">
          Kimball Hall
          <small>Stanford's Arts Theme House</small>
        </h1>
      </div> <!-- /demo-headline -->


<div class="row demo-row">
        <div class="span14">

<!-- menu -->
          <div class="navbar navbar-inverse">
            <div class="navbar-inner">
              <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse-01"></button>
                <div class="nav-collapse collapse" id="nav-collapse-01">
                <ul class="nav">
                  <li>
                    <a href="../../../">
                      Home
                    </a>
                  </li>
                  <li>
                    <a href="../../../staff">
                      Staff
                    </a>
                    <ul>
                      <li><a href="../../../ra">Residential Assistants</a></li>
                      <li><a href="../../../rcc">Residential Computer Consultants</a></li>
                      <li><a href="../../../phe">Peer Health Educator</a></li>
                      <li><a href="../../../ata">Academic Theme Associates</a></li>
                      <li><a href="../../../rf">Resident Fellows</a></li>
                    </ul>
                  </li>

                  <li>
                    <a href="../../../policies">
                      Policies
                    </a>
                  </li>

                  <li class="active">
                    <a href="../../../pointsystem">
                      Point System
                    </a>
                  </li>

                  <li>
                    <a href="../../../cs2c">
                      CS 2C
                    </a>
                  </li>

                  <li>
                    <a href="../../../calendar">
                      Calendar
                    </a>
                  </li>

                  <li>
                    <a href="../../../reservations">
                      Reservations
                    </a>
                  </li>
                  
                  <li>
                    <a href="../../../about">
                      About
                    </a>
                  </li>
                </ul>
              </div><!--/.nav -->
            </div>
          </div>
        </div>
      <!-- /menu -->

	<div class="container">
		<div class="demo-type-example">
		<h1>Pre-assign Control Center</h1>
   		<center>

		<div style="background-color:grey;text-align:center;color:white;border-radius:10px;">

	  	<?php 
		include ('../sqlitedb.php');
		$curSUID = substr(stristr(getcwd(), 'preassigns'), 11);

		try{
	         $queryPoints = "select sum(Points) as PAPoints 
	         				from Participated, Events
	         				where Participated.SUID = \"$curSUID\"
	         				and Participated.EventName = Events.EventName
	         				and Participated.Approved = 1";
	         $pointsQuery = $db->prepare($queryPoints ); 
	         $pointsQuery ->execute();  
		} catch (PDOException $e) {
	         echo "Points query open failed: " . $e->getMessage(); 
		}
		$pointsResult = $pointsQuery->fetch();
		$points = htmlspecialchars($pointsResult["PAPoints"]);

		try{
	         $queryPastPoints = "select Points as pastPoints, Name
						from Preassigns
	         				where SUID = \"$curSUID\"";
	         $pastPointsQuery = $db->prepare($queryPastPoints); 
	         $pastPointsQuery ->execute();  
		} catch (PDOException $e) {
	         echo "Past points query open failed: " . $e->getMessage(); 
		}
		$pastPointsResult = $pastPointsQuery->fetch();
		$pastPoints = htmlspecialchars($pastPointsResult["pastPoints"]);
		$preassignFullName = htmlspecialchars($pastPointsResult["Name"]);
		$preassignFirstName = substr($preassignFullName , 0, strpos($preassignFullName, ' '));
		echo "<br/><h2>Hi ".$preassignFirstName." !</h2>";

		$totalPoints = $points + $pastPoints;

		if ($points == NULL && $pastPoints == NULL){
			echo "<br/><h3>You have 0 Points</h3>";
		} else {
			echo "<br/><h3>You have ".$totalPoints."/20 Points</h3>";
		}

		# display all available events
		# Suggestion: display only those events that student has not yet attended?
		# and those that student claims to have attended in "your activity"
	    try{
	        $queryAvailableEvents = "select * from Events order by Date DESC";
	        $eventsQuery = $db->prepare($queryAvailableEvents); 
	        $eventsQuery->execute();  
		} catch (PDOException $e) {
	         echo "Item query open failed: " . $e->getMessage(); 
		}
		$eventResults = $eventsQuery->fetchall();

	       if($eventResults == NULL){
		   echo "<br/><h2>NO EVENTS</h2><br/>";
		} else {
                 echo "<center>";
   	   	   echo "<table id=\"dbTable\"><tr><h2>Scheduled Events</h2></tr>";
		   echo "<tr>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Event Name</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Date</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Location</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Contact</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Points</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Attending</p>";
		   echo "</td>";
		   echo "<form method=\"POST\" action=\".\">";

		   $alternatingRowTrack = 0;
  		   $separatorCounter = 0; 
		   foreach ($eventResults as $eventRow){
			$eventName = htmlspecialchars($eventRow["EventName"]);
			$eventNameStripped = str_replace(" ", "", $eventName); 
			$eventAttended = htmlspecialchars($_POST[$eventNameStripped]);
			if($eventAttended != NULL){
				try{
					$insertAttended = "insert into Participated values(\"$curSUID\", \"$eventName\", 0)";
					$attendQuery = $db->prepare($insertAttended ); 
 		          		$attendQuery ->execute();  
				} catch (PDOException $e) {
		          		echo "Insert attendance failed: " . $e->getMessage(); 
				}
			}

			
			try{
		          $queryIfParticipated = "select * from Participated where SUID = \"$curSUID\" and EventName = \"$eventName\"";
		          $ifQuery = $db->prepare($queryIfParticipated ); 
 		          $ifQuery ->execute();  
			} catch (PDOException $e) {
		          echo "IF query failed: " . $e->getMessage(); 
			}
			$ifResults = $ifQuery ->fetch();
			
			$attended = 0;

			if($separatorCounter == 6){
				echo "<tr style=\"background-color:grey;color:grey;\"><td>.</td><td>.</td><td>.</td><td>.</td><td>.</td><td>.</td></tr>";
				$separatorCounter = 0; 
			}

			if($ifResults != NULL){
				if(htmlspecialchars($ifResults ["Approved"]) == 1){
					echo "<tr id=\"bodyRow\" style=\"background-color:#880000;color:white;\">";
					$attended = 1; 
				} else {
					echo "<tr id=\"bodyRow\" style=\"background-color:#cd4444;\">";
					$attended = 2; 
				}
			} else {
				if($alternatingRowTrack == 0){
					echo "<tr id=\"bodyRow\" style=\"background-color:#7aacde;\">";	
					$alternatingRowTrack = 1;
				}else{
					echo "<tr id=\"bodyRow\" style=\"background-color:#e0e0e0;\">";
					$alternatingRowTrack = 0;
				}
			}
			
			echo "<td>";
			echo $eventName;
			echo "</td>";
			echo "<td>";
             		echo htmlspecialchars($eventRow["Date"]);
			echo "</td>";
			echo "<td>";
             		echo htmlspecialchars($eventRow["Location"]);
			echo "</td>";
			echo "<td>";
             		echo htmlspecialchars($eventRow["Contact"]);
			echo "</td>";
			echo "<td>";
             		echo htmlspecialchars($eventRow["Points"]);
			echo "</td>";
			echo "<td>";
			if($attended == 0){
	             		echo "<input type=\"checkbox\" name=\"$eventNameStripped\" value=\"$eventNameStripped\">";
			} else {
				if($attended == 1)
					echo "<p style=\"margin:0;\"> Approved </p>";
				if($attended == 2)
					echo "<p style=\"margin:0;\"> Pending Approval </p>";
			}	
			echo "</td>";	
			echo "</tr>";
			console.log($separatorCounter);
  		   	$separatorCounter++; 
		   }
		   echo "</table></center>";
		   echo "<br/><input type=\"submit\" value=\"Submit Attendance\"></form><br/><br/>";
		}	

		try{
	         $queryPoints = "select sum(Points) as PAPoints 
	         				from Participated, Events 
	         				where Participated.SUID = \"$curSUID\" 
	         				and Participated.EventName = Events.EventName
	         				and Participated.Approved = 0";
	         $pointsQuery = $db->prepare($queryPoints ); 
	         $pointsQuery ->execute();  
		} catch (PDOException $e) {
	         echo "Points query open failed: " . $e->getMessage(); 
		}
		$pointsResult = $pointsQuery->fetch();
		$points = htmlspecialchars($pointsResult["PAPoints"]);

		if ($points == NULL){
			echo "<br/><h4>You have 0 Points Pending Approval</h4>";
		} else {
			echo "<br/><h4>You have ".$points." Points Pending Approval</h4>";
		}

	       ?>
	<br/><br/>
	</div>



       </div>
	



	</center>
      </div>

            <!-- Load JS here for greater good =============================-->
        <script src="../../../js/jquery-1.8.3.min.js"></script>
        <script src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="../../../js/jquery.ui.touch-punch.min.js"></script>
        <script src="../../../js/bootstrap.min.js"></script>
        <script src="../../../js/bootstrap-select.js"></script>
        <script src="../../../js/bootstrap-switch.js"></script>
        <script src="../../../js/flatui-checkbox.js"></script>
        <script src="../../../js/flatui-radio.js"></script>
        <script src="../../../js/jquery.tagsinput.js"></script>
        <script src="../../../js/jquery.placeholder.js"></script>
        <script src="../../../js/jquery.stacktable.js"></script>
        <script src="http://vjs.zencdn.net/c/video.js"></script>
        <script src="../../../js/application.js"></script>
  </body>
</html>