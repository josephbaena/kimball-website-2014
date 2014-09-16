<?php 
    date_default_timezone_set('America/San_Francisco');

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
//  http://code.google.com/apis/calendar/data/2.0/reference.html#Parameters
//  for alternatives.

//  This version gets all the events happening starting from right now until
//  eight days from now.

//  Don't forget to replace "yourcalendaraddress" by your Google
//  calendar address.  For your default calendar, it's just your gmail
//  address before the "@gmail.com"
    $feed = "https://www.google.com/calendar/feeds/kimball.hall.mail%40gmail.com/" . 
        "public/basic?orderby=starttime&singleevents=true&" .
        "sortorder=ascending&" .
        "start-min=" . $right_now . "&" .
        "start-max=" . $next_year;

    $xml = simplexml_load_file($feed);
    $entries = $xml->entry;
    $data = [];
    

    foreach ($entries as $entry) {
            $title = (string)$entry->title; 

	    $author_email = (string)$entry->author->email;
	    $time_str = (string)$entry->content;
 	    $time = substr($time_str, 6, strpos($time_str, 'Â')-6);
	    
	    $year_pos = strpos($time, ',') + 6; 
	    
	    $date = substr($time, 0, $year_pos);
	    $rest = substr($time, $year_pos);

	    if(strpos($rest, ',')){

	    } else {
		//Single Day Event
		$start_time = substr($rest, 0, strpos($rest, 'to')-1);
		$end_time = substr($rest, strpos($rest, 'to')+2);
		$start_time = editTime($start_time); 
		$end_time = editTime($end_time);
  	    }
	  
	    

	    //echo($time);
	    //$json_data = array ('title'=>$title,'author_email'=>$author_email,'start_time'=>$start_time, 'end_time'=>$end_time);
	    //array_push($data, $json_data); 
		
    }

    function editTime($time_str){
	$pm_pos = strpos($time_str, 'pm');
	$hour; 
	$hour_len = 1; 
	if($pm_pos){
		if($pm_pos == 2 || $pm_pos == 5){
			$hour = (intval(substr($time_str, 1, 1)%12)) + 12;	
		} else {
			$hour = (intval(substr($time_str, 1, 2))); 
		}	 
	       	$time_str = mb_ereg_replace('pm',':00', $time_str);	    
	        $hour_len = 2; 
	} else {
		$time_str = mb_ereg_replace('am',':00', $time_str);		
	}
	if($hour)
		echo ($hour . substr($time_str, $hour_len, strlen($time_str)-$hour_len));
	else
		echo $time_str;
    }	
	

  //header('Content-Type: application/json');
   //echo json_encode($data); 
?>
