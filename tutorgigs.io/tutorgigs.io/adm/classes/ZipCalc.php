<?php
/*	Author: Kevin Pryce
*	June 2014
*	P2G Zip Calculator
*	Gets the distance between 2 zip codes 
*
*/

	//$distance = getDistance(77494, 77024);
	//echo round($distance, 1);

// This function returns Longitude & Latitude from zip code
function getLnt($zip) {
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
	
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	
	$result1[]=$result['results'][0];
	$result2[]=$result1[0]['geometry'];
	$result3[]=$result2[0]['location'];
	
	return $result3[0];
}

function getDistance($zip1, $zip2) {
	$first_lat = getLnt($zip1);
	$next_lat = getLnt($zip2);
	
	$lat1 = $first_lat['lat'];
	$lon1 = $first_lat['lng'];
	$lat2 = $next_lat['lat'];
	$lon2 = $next_lat['lng']; 
	
	$theta=$lon1-$lon2;
	
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
		cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
		cos(deg2rad($theta));
		
	$dist = acos($dist);
	$dist = rad2deg($dist);
	
	$miles = $dist * 60 * 1.1515;
	
	$unit = strtoupper($unit);
		
	return round( ($miles * 0.8684), 1 );
	
	/*if ($unit == "K") {
		return ($miles * 1.609344)." ".$unit;
	}
	else if ($unit == "M") {
		return ($miles * 0.8684)." ".$unit;
	}
	else {
		//return $miles." ".$unit;
		return $miles." ".$unit;
	}*/	
	
}

?>