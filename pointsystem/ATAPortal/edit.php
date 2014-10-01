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
			<h1>Edit Event</h1>
		</div>
		<hr/>
		<div class="ata-dashboard">

	  	<?php
		include ('./sqlitedb.php');

		$eventName = $_GET['eventName'];

	      	try{
	         $queryEvent = "select * from Events where EventName = \"$eventName\"";
	         $eventQuery = $db->prepare($queryEvent); 
	         $eventQuery->execute();  
		} catch (PDOException $e) {
	         echo "Event query open failed: " . $e->getMessage(); 
		}
		$eventResult = $eventQuery->fetch();

	       if($eventResult == NULL){
		   echo "<br/><h2>ERROR RETRIEVING EVENT</h2><br/>";
		} else {
		   $eventDate = htmlspecialchars($eventResult["Date"]);
		   $eventLocation = htmlspecialchars($eventResult["Location"]);
		   $eventContact = htmlspecialchars($eventResult["Contact"]);
		   $eventPoints = htmlspecialchars($eventResult["Points"]);
		}
		
		echo "<center>
			<h2>Edit Event</h2>
			<form method=\"POST\" action=\".?&eventName=".$eventName."\">
			<table class=\"input-table\">";
		echo "<tr>
			<td style=\"text-align:center;\"><p>Event Name: </p></td> 
			<td><p><input type=\"text\" name=\"editEventName\" value=\"$eventName\" required></p></td>
			</tr>";

		echo "<tr>
			<td style=\"text-align:center;\"><p>Date of Event: </p></td>
			<td><p><input type=\"text\" name=\"editEventDate\" value=\"$eventDate\" required></p></td>
			</tr>";
		
		echo "<tr>
			<td style=\"text-align:center;\"><p>Location: </p></td>
			<td><p><input type=\"text\" name=\"editEventLocation\" value=\"$eventLocation\" required></p></td>
			</tr>";

		echo "<tr>
			<td style=\"text-align:center;\"><p>Point of Contact: </p></td>
			<td><p><input type=\"text\" name=\"editEventContact\" value=\"$eventContact\" required></p></td>
			</tr>";
	
		echo "<tr>
			<td style=\"text-align:center;\"><p>Points: </p></td>
			<td><p><input type=\"integer\" name=\"editEventPoints\" value=\"$eventPoints\" required></p></td>
			</tr>
		
			</table>
			<p>
				<input class=\"btn-success btn-submit\" type=\"submit\" value=\"Submit\">
				<a href=\"kimball.stanford.edu/preassigns\"><button class=\"btn-warning btn-submit\">Cancel</button></a>	
			</p>
			</form><hr/>";
	
		echo "<form method=\"POST\" action=\".\">
			<input class=\"btn-danger btn-submit\" type=\"submit\" value=\"Delete Event\"><br/>
			<input type=\"text\" name=\"deleteEvent\" value=\"$eventName\" style=\"visibility:hidden;\" required>	
			</form>";
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