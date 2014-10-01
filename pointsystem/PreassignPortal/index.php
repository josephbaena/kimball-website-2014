<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Kimball Hall | Preassigns</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../css/stylish-portfolio.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery Version 1.11.0 -->
    <script src="../../js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../js/bootstrap.min.js"></script> 
</head>

<body>

	<!-- Navigation -->
    <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="fa fa-bars"></i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand">
                <a href="../../">Kimball Hall</a>
            </li>
            <li>
                <a href="../../">Home</a>
            </li>
            <li>
                <a href="../../index.html#eventCalendar">Event Calendar</a>
            </li>
            <li>
                <a href="../../staff">Staff</a>
            </li>
            <li>
                <a href="../../policies/">Policies</a>
            </li>
            <li>
                <a href="../../preassigns">Preassigns</a>
            </li>
            <li>
                <a href="../../reservations">Reservations</a>
            </li>
            <li>
                <a href="../../artsjam">Arts Jam</a>
            </li>
        </ul>
    </nav>

    <!-- Callout -->
    <aside class="callout-preassigns">
        <div class="text-vertical-center">
            <h1 id="title">Preassigns</h1>
        </div>
    </aside>
    <!-- Header -->


<center>
	<div class="container">
		<div class="header-title">
			<h1>Preassign Dashboard</h1>
			<a href="../about"><h4>How does the Preassign Point System work?</h4></a>
		</div>
		<hr/>
		
		<div class="preassign-dashboard">

	  	<?php 
		include ('sqlitedb.php');
		$curSUID = $_SERVER[REMOTE_USER];
		$searchedSUID = $_GET['SUID'];
		if($searchedSUID != NULL && strlen($searchedSUID) != 0)
			$curSUID = $searchedSUID;

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

		if($preassignFullName == NULL){
			die();
		}

		$preassignFirstName = substr($preassignFullName , 0, strpos($preassignFullName, ' '));


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

		echo "<div class=\"p-banner\">";
		echo "<h1>Hi ".$preassignFirstName."!</h1>";

		$totalPoints = $points + $pastPoints;

		if ($points == NULL && $pastPoints == NULL){
			echo "<br/><h3>You have 0 Points</h3><hr/>";
		} else {
			echo "<br/><h3>You have ".$totalPoints."/5 Points</h3></div><hr/>";
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
   	   	   echo "<table class=\"dbTable\"><tr><h2>Scheduled Events</h2><br/></tr>";
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
		   echo "<p id=\"eventTableColumnHead\">Attendance</p>";
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
				echo "<tr><center><td colspan=\"6\"><hr class=\"divider-hr\"/></td></center></tr>";
				$separatorCounter = 0; 
			}

			if($ifResults != NULL){
				if(htmlspecialchars($ifResults ["Approved"]) == 1){
					echo "<tr id=\"bodyRow\" style=\"background-color:#7CBA7C;\">";
					$attended = 1; 
				} else {
					echo "<tr id=\"bodyRow\" style=\"background-color:#E18F8F;\">";
					$attended = 2; 
				}
			} else {
				if($alternatingRowTrack == 0){
					echo "<tr id=\"bodyRow\" style=\"background-color:#AACAEA;\">";	
					$alternatingRowTrack = 1;
				}else{
					echo "<tr id=\"bodyRow\" >";
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
	             		echo "<input id=\"checkbox\" type=\"checkbox\" name=\"$eventNameStripped\" value=\"$eventNameStripped\">";
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
		   echo "<br/><input class=\"btn-success s-attendance\" type=\"submit\" value=\"Submit Attendance\"></form><br/><br/>";
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
			//echo "<br/><h4>You have 0 Points Pending Approval</h4>";
		} else {
			//echo "<br/><h4>You have ".$points." Points Pending Approval</h4>";
		}

	       ?>
	</div>

        </div>
	</center>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <h4><strong>Kimball Hall</strong>
                    </h4>
                    <p>661 Escondido Road<br>Stanford, CA 94305</p>
                    <br>
                    <ul class="list-inline">
                        <li>
                            <a href="https://www.facebook.com/groups/538725966237303/"><i class="fa fa-facebook-square fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a href="http://kimball-hall.tumblr.com"><i class="fa fa-tumblr-square fa-fw fa-3x"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-instagram fa-fw fa-3x"></i></a>
                        </li>
                    </ul>
                    <hr class="small">
                    <p class="text-muted">&copy; Kimball Hall 2014</p>
                </div>
            </div>
        </div>
    </footer>    

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>
  </body>
</html>