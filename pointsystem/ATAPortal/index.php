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
			<h1>ATA Control Center</h1>
			<a href="#p-activity"><h4>Jump to Preassign Activity</h4></a>
		</div>
		<hr/>
		
		<div class="ata-dashboard">

	        <br/><h2>Scheduled Events</h2><br/>

	  	<?php 
		include ('./sqlitedb.php');

		$deletedEventName = htmlspecialchars($_POST["deleteEvent"]);

		if($deletedEventName != NULL){
			try{
		         $deleteQuery = "delete from Events where EventName = \"$deletedEventName\"";
		         $deleteEvent = $db->prepare($deleteQuery); 
		         $deleteEvent->execute();  
			} catch (PDOException $e) {
		         echo "Event delete failed: " . $e->getMessage(); 
			}
		}


		$editedEventName_Old = $_GET['eventName'];

		$editedEventName = htmlspecialchars($_POST["editEventName"]);
		$editedEventDate = htmlspecialchars($_POST["editEventDate"]);
		$editedEventLocation = htmlspecialchars($_POST["editEventLocation"]);
		$editedEventContact = htmlspecialchars($_POST["editEventContact"]);
		$editedEventPoints = htmlspecialchars($_POST["editEventPoints"]);
	
		
		if($editedEventName != NULL){
			try{
		         $updateQuery = "update Events set EventName = \"$editedEventName\", Date = \"$editedEventDate\", Location = \"$editedEventLocation\", Contact = \"$editedEventContact\", Points = \"$editedEventPoints\" where EventName = \"$editedEventName_Old\"";
		         $updateEvent = $db->prepare($updateQuery); 
		         $updateEvent->execute();  
			} catch (PDOException $e) {
		         echo "Event update failed: " . $e->getMessage(); 
			}
		}

		$newPASUID = htmlspecialchars($_POST["submitSUID"]);
		$newPAName = htmlspecialchars($_POST["submitNewPAName"]);
		$newPAPoints = htmlspecialchars($_POST["submitNewPAPoints"]);
		
		if($newPASUID != NULL){
			try{
 				$queryInsertPA = $db->query("INSERT INTO Preassigns VALUES(\"$newPASUID\", \"$newPAName\", $newPAPoints)");
			} catch (PDOException $e) {
         			echo "Insert PA failed: " . $e->getMessage(); 
		      }
		}

		$newEventName = htmlspecialchars($_POST["submitEventName"]);
		$newEventDate = htmlspecialchars($_POST["submitEventDate"]);
		$newEventLocation = htmlspecialchars($_POST["submitEventLocation"]);
		$newEventContact = htmlspecialchars($_POST["submitEventContact"]);
		$newEventPoints = htmlspecialchars($_POST["submitEventPoints"]);

		if($newEventName != NULL){
			try{
 				$queryInsertEvent = $db->query("INSERT INTO Events VALUES(\"$newEventName\", \"$newEventDate\", \"$newEventLocation\", \"$newEventContact\", $newEventPoints, \"fall14\")");
			} catch (PDOException $e) {
         			echo "Insert event failed: " . $e->getMessage(); 
		      }
		}

	      	try{
	         $queryAvailableEvents = "select * from Events order by Date desc";
	         $eventsQuery = $db->prepare($queryAvailableEvents); 
	         $eventsQuery->execute();  
		} catch (PDOException $e) {
	         echo "Item query open failed: " . $e->getMessage(); 
		}
		$eventResults = $eventsQuery->fetchall();

	       if($eventResults == NULL){
		   echo "<br/><h2>NO EVENTS</h2><br/>";
		} else {
		   echo "<center><table class=\"dbTable\">";
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
		   echo "</tr>";
		   $rowTrack = 0;
		   $separatorCounter = 0; 
		   foreach ($eventResults as $eventRow){
			$eventName = htmlspecialchars($eventRow["EventName"]);
			if($separatorCounter == 6){
				echo "<tr><center><td colspan=\"5\"><hr class=\"divider-hr\"/></td></center></tr>";
				$separatorCounter = 0; 
			}
			if($rowTrack == 0){
				echo "<tr id=\"bodyRow\" style=\"background-color:#AACAEA;\">";
				$rowTrack = 1;
			}else{
				echo "<tr id=\"bodyRow\">";
				$rowTrack = 0;
			}
			

			echo "<td>";
			echo "<a href=\"./edit.php?&eventName=".$eventName."\">".$eventName."</a>";
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
			echo "</tr>";
			$separatorCounter++;
		   }
		   echo "</table></center>";
		}
	       ?>

	<br/><br/><center>
	<h2>Add Event</h2>
	<form method="POST" action="index.php">
	<table class="input-table">
	
	<tr>
		<td><p>Event Name: </p></td> 
		<td><p><input type="text" name="submitEventName" required></p></td>
	</tr>
	
	<tr>
		<td><p>Date of Event: </p></td>
		<td><p><input type="text" name="submitEventDate" required></p></td>
	</tr>
	
	<tr>
		<td><p>Location: </p></td>
		<td><p><input type="text" name="submitEventLocation" required></p></td>
	</tr>

	<tr>
		<td><p>Point of Contact: </p></td>
		<td><p><input type="text" name="submitEventContact" required></p></td>
	</tr>

	<tr>
		<td><p>Points: </p></td>
		<td><p><input type="integer" name="submitEventPoints" required></p></td>
	</tr>

	</table>
	<p><input class="btn-success btn-submit" type="submit" value="Submit"></p>
	</form>

       <br/></center>
	</div>
       </div>

	<br/>

	<hr/>
	<div>	

	<center>
	<br/><h2>Kimball Preassigns</h2>

	  	<?php 
	      	try{
	         $queryPreassigns = "select * from Preassigns";
	         $preassignQuery = $db->prepare($queryPreassigns ); 
	         $preassignQuery ->execute();  
		} catch (PDOException $e) {
	         echo "Item query open failed: " . $e->getMessage(); 
		}
		$preassignResults = $preassignQuery->fetchall();

	       if($preassignResults == NULL){
		   echo "<br/><h2>NO STUDENTS ENTERED</h2><br/>";
		} else {
		   echo "<center><table class=\"dbTable-sized\">";
		   echo "<tr>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">SUID</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Name</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Points</p>";
		   echo "</td>";

		   $rowTrack = 0;
		   foreach ($preassignResults as $preassignRow){
		       $curSUID = htmlspecialchars($preassignRow["SUID"]);
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
			$totalPoints = $points + htmlspecialchars($preassignRow["Points"]);
			if($rowTrack == 0){
				echo "<tr style=\"background-color:#ffd3d3;\">";
				$rowTrack = 1;
			}else{
				echo "<tr>";
				$rowTrack = 0;
			}
			echo "<td>";
			echo "<a href=\"../PreassignPortal?SUID=".$curSUID."\" >".$curSUID."</a>" ;
			echo "</td>";
			echo "<td>";
             		echo htmlspecialchars($preassignRow["Name"]);
			echo "</td>";
			echo "<td>";
             		if($totalPoints >= 5){
				echo "<img src=\"check.png\" />";
			} else {
	             		echo htmlspecialchars($totalPoints);
			}
			echo "</td>";
			echo "</tr>";
		   }
		   echo "</table></center>";
		}
	       ?>

	<br/>
	<!--<h2>Add Student</h2>
	<form method="POST" action="index.php">
	<table>
	
	<tr>
		<td style="text-align:center;"><p>SUID: </p></td> 
		<td><p><input type="text" name="submitSUID" required></p></td>
	</tr>
	
	<tr>
		<td style="text-align:center;"><p>Name: </p></td>
		<td><p><input type="text" name="submitNewPAName" required></p></td>
	</tr>

	<tr>
		<td style="text-align:center;"><p>Points: </p></td>
		<td><p><select name="submitNewPAPoints" style="width:205px;" required>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>

			</select></p></td>
	</tr>
	</table>
	<p><input type="submit" value="Submit"></p>
	</form> -->
	<br/>
	</center>
	</div>
	
	
	<div class="p-activity" id="p-activity">	

	<center>

	<br/><h2>Pre-assign Activity</h2>

	  	<?php 
	      	try{
	         $queryAvailableEvents = "select Participated.SUID, Name, Participated.EventName as EventName, Events.Points as Points, Approved from Participated, Preassigns, Events where Participated.SUID = Preassigns.SUID and Participated.EventName = Events.EventName and Approved = 0 order by Name";
	         $eventsQuery = $db->prepare($queryAvailableEvents); 
	         $eventsQuery->execute();  
		} catch (PDOException $e) {
	         echo "Item query open failed: " . $e->getMessage(); 
		}
		$eventResults = $eventsQuery->fetchall();


	       if($eventResults == NULL){
		   echo "<br/><h2>NO PREASSIGN ACTIVITY </h2><br/>";
		} else {
		   echo "<center><table class=\"dbTable-sized\">";
		   echo "<tr>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Name</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Event Name</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Point Value</p>";
		   echo "</td>";
		   echo "<td>";
		   echo "<p id=\"eventTableColumnHead\">Approve?</p>";
		   echo "</td>";
   		   echo "<form method=\"POST\" action=\".\">";


		   $rowTrack = 0;
		   $handled = 0; 
		   foreach ($eventResults as $eventRow){
			$eventName = htmlspecialchars($eventRow["EventName"]);
			$eventNameStripped = str_replace(" ", "", $eventName); 
			$participantSUID = htmlspecialchars($eventRow["SUID"]);

			$decisionPost = htmlspecialchars($_POST["approvalRadio".$eventNameStripped.$participantSUID]);
			if($decisionPost != NULL){
				
				$decision = substr($decisionPost, 0, (-strlen($decisionPost))+2);
				if($decision == 'YE'){
					$approvalPost = $decisionPost; 
				}
				if($decision == 'NO'){
					$disapprovalPost = $decisionPost; 
				}
			}	

			if($approvalPost != NULL){
				$curSUID = substr(stristr($approvalPost, $eventNameStripped), strlen($eventNameStripped));

				try{
			         $updateQuery = "update Participated set Approved = 1 where SUID = \"$curSUID\" and EventName = \"$eventName\"";
	       		  $updateApproved = $db->prepare($updateQuery); 
			         $updateApproved ->execute();  
				} catch (PDOException $e) {
			         echo "Update Approved failed: " . $e->getMessage(); 
				}
			$handled = 1; 
			}
				
			if($disapprovalPost != NULL){
				$curSUID = substr(stristr($disapprovalPost, $eventNameStripped), strlen($eventNameStripped));
				try{
			         $deleteQuery = "delete from Participated where SUID = \"$curSUID\" and EventName = \"$eventName\"";
	       		  $deleteParticipated = $db->prepare($deleteQuery); 
			         $deleteParticipated ->execute();  
				} catch (PDOException $e) {
			         echo "Delete Participated tuple failed: " . $e->getMessage(); 
				}
		       $handled = 1; 
			}

		 	if(!$handled){
				if($rowTrack == 0){
					echo "<tr style=\"background-color:#AACAEA;\">";
					$rowTrack = 1;
				}else{
					echo "<tr>";
					$rowTrack = 0;
				}
				echo "<td>";
				echo "<a href=\"../PreassignPortal?SUID=$participantSUID\">".htmlspecialchars($eventRow["Name"])."</a>";
				echo "</td>";
				echo "<td>";
	             		echo htmlspecialchars($eventRow["EventName"]);
				echo "</td>";
				echo "<td>";
	             		echo htmlspecialchars($eventRow["Points"]);
				echo "</td>";
				echo "<td>";
				echo "YES <input class=\"approve-radio\" type=\"radio\" name=\"approvalRadio$eventNameStripped$participantSUID\" value=\"YES$eventNameStripped$participantSUID\"/><br/>";
				echo "NO <input class=\"approve-radio\" type=\"radio\" name=\"approvalRadio$eventNameStripped$participantSUID\" value=\"NO$eventNameStripped$participantSUID\"/>";
				echo "</td>";
				echo "</tr>";
			}
   		   $handled = 0; 
		   }
		   echo "</table></center>";
		   echo "<br/><input class=\"btn-success btn-submit\" type=\"submit\" value=\"Submit Approval\"></form><br/><br/>";
		}
	       ?>

	<br/><br/>
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
