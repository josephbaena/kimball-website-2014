<?php 
    date_default_timezone_set('America/San_Francisco');
    $test = "what";

//  This tells the code where to look in Google's data protocal
//   to find the tags used in the calendar feed.  Note that
//   we're only looking at "confirmed" links.  For more details, see
//   http://code.google.com/apis/gdata/docs/1.0/elements.html

    $confirmed = 'http://schemas.google.com/g/2005#event.confirmed';

// This puts the date in a form Google will read:

    $right_now = date("Y-m-d\Th:i:sP", time());

//  For our purposes, a week will be 8 days.  This allows next
//   Sunday's schedule to appear on the preceeding Sunday
//  Adjust for your own purposes

    $year_in_seconds = 60 * 60 * 24 * 365;
    $next_year = date("Y-m-d\Th:i:sP", time() + $year_in_seconds);

//  This is my version of the call to Google's API.  See
//   http://code.google.com/apis/calendar/data/2.0/reference.html#Parameters
//   for alternatives.

//   This version gets all the events happening starting from right now until
//   eight days from now.

//  Don't forget to replace "yourcalendaraddress" by your Google
//   calendar address.  For your default calendar, it's just your gmail
//   address before the "@gmail.com"

    $feed = "https://www.google.com/calendar/feeds/kimball.hall.mail%40gmail.com/" . 
        "public/full?orderby=starttime&singleevents=true&" .
        "sortorder=ascending&" .
        "start-min=" . $right_now . "&" .
        "start-max=" . $next_year;

   echo($feed);

//  Create a new document from the feed

    $doc = new DOMDocument(); 
    $doc.load( $feed );

//  We're looking for all the entries in the feed, denoted, logically
//   enough, by the tag "entry"

    $entries = $doc.getElementsByTagName( "entry" ); 
    echo($entries);

//  This is pretty much self-explanatory

    foreach ( $entries as $entry ) { 
    
        $status = $entry.getElementsByTagName( "eventStatus" ); 
        $eventStatus = $status.item(0).getAttributeNode("value").value;

        if ($eventStatus == $confirmed) {

            $titles = $entry.getElementsByTagName( "title" ); 
            $title = $titles.item(0).nodeValue;

            $title = ereg_replace(" & ", " &amp; ", $title);

            $times = $entry.getElementsByTagName( "when" ); 

            $startTime = $times.item(0).getAttributeNode("startTime").value;

	    $when = date( "l\, F j\, Y \a\\t h:i A T", strtotime( $startTime ) );

            $places = $entry.getElementsByTagName( "where" ); 
            $where = $places.item(0).getAttributeNode("valueString").value;

            $web = $entry.getElementsByTagName( "link" ); 
            $link = $web.item(0).getAttributeNode("href").value;

            echo $title;
            echo $when;
            echo $where;
	}
}
?>
