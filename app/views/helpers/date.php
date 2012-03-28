<?php 

class DateHelper extends AppHelper {
	
	function relativeTime($time){   
	    $delta = time() - $time;
	
	    if ($delta < 1 * MINUTE)
	    {
	        return $delta == 1 ? "one second ago" : $delta . " seconds ago";
	    }
	    if ($delta < 2 * MINUTE)
	    {
	      return "a minute ago";
	    }
	    if ($delta < 45 * MINUTE)
	    {
	        return floor($delta / MINUTE) . " minutes ago";
	    }
	    if ($delta < 90 * MINUTE)
	    {
	      return "an hour ago";
	    }
	    if ($delta < 24 * HOUR)
	    {
	      return floor($delta / HOUR) . " hours ago";
	    }
	    if ($delta < 48 * HOUR)
	    {
	      return "yesterday";
	    }
	    if ($delta < 30 * DAY)
	    {
	        return floor($delta / DAY) . " days ago";
	    }
	    if ($delta < 12 * MONTH)
	    {
	      $months = floor($delta / DAY / 30);
	      return $months <= 1 ? "one month ago" : $months . " months ago";
	    }
	    else
	    {
	        $years = floor($delta / DAY / 365);
	        return $years <= 1 ? "one year ago" : $years . " years ago";
	    }
	}
}