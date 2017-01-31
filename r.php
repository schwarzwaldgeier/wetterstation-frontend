<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//create a txt file as described on http://wiki.sandaysoft.com/a/Realtime.txt

/*
 1 	19/08/09 	Date as 2 figure day [separator] 2 figure month [separator] 2 figure year - the separator is that set in the windows system short date format (see setup) 	<#date>
2 	16:03:45 	time(always hh:mm:ss as per computer system) 	<#timehhmmss>
3 	8.4 	outside temperature 	<#temp>
4 	84 	relative humidity 	<#hum>
5 	5.8 	dewpoint 	<#dew>
6 	24.2 	wind speed (average) 	<#wspeed>
7 	33.0 	latest wind speed reading 	<#wlatest>
8 	261 	wind bearing (degrees) 	<#bearing>
9 	0.0 	current rain rate (per hour) 	<#rrate>
10 	1.0 	rain today 	<#rfall>
11 	999.7 	barometer (The sea level pressure) 	<#press>
12 	W 	current wind direction (compass point) 	<#currentwdir>
13 	6 	wind speed (beaufort) 	<#beaufortnumber>
14 	km/h 	wind units - m/s, mph, km/h, kts 	<#windunit>
15 	C 	temperature units - degree C, degree F 	<#tempunitnodeg>
16 	hPa 	pressure units - mb, hPa, in 	<#pressunit>
17 	mm 	rain units - mm, in 	<#rainunit>
18 	146.6 	wind run (today) 	<#windrun>
19 	+0.1 	pressure trend value (The average rate of pressure change over the last three hours) 	<#presstrendval>
20 	85.2 	monthly rainfall 	<#rmonth>
21 	588.4 	yearly rainfall 	<#ryear>
22 	11.6 	yesterday's rainfall 	<#rfallY>
23 	20.3 	inside temperature 	<#intemp>
24 	57 	inside humidity 	<#inhum>
25 	3.6 	wind chill 	<#wchill>
26 	-0.7 	temperature trend value (The average rate of change in temperature over the last three hours) 	<#temptrend>
27 	10.9 	today's high temp 	<#tempTH>
28 	12:00 	time of today's high temp (hh:mm) 	<#TtempTH>
29 	7.8 	today's low temp 	<#tempTL>
30 	14:41 	time of today's low temp (hh:mm) 	<#TtempTL>
31 	37.4 	today's high wind speed (of average as per choice) 	<#windTM>
32 	14:38 	time of today's high wind speed (average) (hh:mm) 	<#TwindTM>
33 	44.0 	today's high wind gust 	<#wgustTM>
34 	14:28 	time of today's high wind gust (hh:mm) 	<#TwgustTM>
35 	999.8 	today's high pressure 	<#pressTH>
36 	16:01 	time of today's high pressure (hh:mm) 	<#TpressTH>
37 	998.4 	today's low pressure 	<#pressTL>
38 	12:06 	time of today's low pressure (hh:mm) 	<#TpressTL>
39 	1.8.7 	Cumulus Versions (the specific version in use) 	<#version>
40 	819 	Cumulus build number 	<#build>
41 	36.0 	10-minute high gust 	<#wgust>
42 	10.3 	Heat index 	<#heatindex>
43 	10.5 	Humidex 	<#humidex>
44 	13 	UV Index 	<#UV>
45 	0.2 	evapotranspiration today 	<#ET>
46 	14 	solar radiation W/m2 	<#SolarRad>
47 	260 	10-minute average wind bearing (degrees) 	<#avgbearing>
48 	2.3 	rainfall last hour 	<#rhour>
49 	3 	The number of the current (Zambretti) forecast as per Strings.ini. 	<#forecastnumber>
50 	1 	Flag to indicate that the location of the station is currently in daylight (1 = yes, 0 = No) 	<#isdaylight>
51 	1 	If the station has lost contact with its remote sensors "Fine Offset only", a Flag number is given (1 = Yes, 0 = No) 	<#SensorContactLost>
52 	NNW 	Average wind direction 	<#wdir>
53 	2040 	Cloud base 	<#cloudbasevalue>
54 	ft 	Cloud base units 	<#cloudbaseunit>
55 	12.3 	Apparent temperature 	<#apptemp>
56 	11.1 	Sunshine hours so far today 	<#SunshineHours>
57 	420.1 	Current theoretical max solar radiation 	<#CurrentSolarMax>
58 	1 	Is it sunny? 1 if the sun is shining, otherwise 0 (above or below threshold) 	<#IsSunny> 

ex.
18/10/08 16:03:45 8.4 84 5.8 24.2 33.0 261 0.0 1.0 999.7 W 6 mph C mb mm 146.6 +0.1 85.2 588.4 11.6 20.3 57 3.6 -0.7 10.9 12:00 7.8 14:41 37.4 14:38 44.0 14:28 999.8 16:01 998.4 12:06 1.8.2 448 36.0 10.3 10.5 13 0.2 14 260 2.3 3 1 1 NNW 2040 ft 12.3 11.1 420.1 1 

*/
@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/php_mysql.php");
//@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/funktionen_wetterseite.php");

$query       = "SELECT * from weather_merkur2 order by uid desc LIMIT 1";
  
$r      = mysqli_query($connection, $query);
//$anzkomplett = @mysqli_num_rows($result);
$d = mysqli_fetch_assoc($r);

function out($field, $data)
{
	echo $data[$field]." ";

}
//out("record_datetime", $d);
$now = new DateTime();
echo $now->format('d/m/Y')." "; //
echo $now->format('H:m:s')." ";


out("temperature", $d);
out("humidity", $d);
out("wind_speed", $d);

$query_aws = "SELECT AVG(a.wind_speed) AS ws_avg FROM (SELECT wind_speed FROM weather_merkur2 ORDER BY tstamp DESC LIMIT 10) a;";
$r_aws = mysqli_query($connection, $query_aws);
$d_aws = mysqli_fetch_assoc($r_aws);
out("ws_avg", $d_aws);
out("wind_speed", $d);
out("wind_direction", $d);

out("wind_direction", $d); // wind bearing

echo "0.0 "; // rain today
out("pressure", $d); //barometer
echo(WindDirectionNormalName($d["wind_direction"] ) )." "; //compass point
echo "0 " ; //beaufort (fake)
echo "km/h "; //wind unit
echo "C "; //temp unit
echo "hPa "; //pressure unit
echo "mm "; //rain unit (fake)
echo "0.0 "; //TODO wind_run
echo "+0.0 "; //TODO pressure change
echo "0.0 "; //monthly rainfall
echo "0.0 "; //yearly rainfall
echo "0.0 "; //yesterday rainfall
echo "20.0 "; //inside temp
echo "50 "; //inside humidity
out("wind_chill", $d);
echo "+0.0 "; //TODO temperature trend value TODO
out("temperature", $d); //TODO high temp

echo $now->format('H:m')." "; //time hightemp


out("temperature", $d); //tody lowtemp
echo $now->format('H:m')." "; //lowtemp time
out("wind_maxspeed", $d); //today high wind speed avg
echo $now->format('H:m')." "; 
out("wind_maxspeed", $d); //today high gust
echo $now->format('H:m')." "; 
echo "1024.25 "; //today high pressure
echo $now->format('H:m')." "; 
echo "1024.25 "; //today low pressure
echo $now->format('H:m')." "; 
echo "1.8.7 "; //cumulus fake version
echo "819 "; //cunumulus fake build number

out("wind_maxspeed", $d); //10 min high gust
echo "0 "; //heat index
echo "0 "; //humidex
echo "0 "; //UV index
echo "0 "; //evapowhatever
echo "0 "; //solar rad
out("wind_direction", $d); //10min avg wind bearing
echo "0 "; //rainfall last hour
echo "0 "; //number of current whatever forecast
echo "1 "; //daylight yes no
echo "0 "; //connection lost?

echo(WindDirectionNormalName($d["wind_direction"] ) )." "; //compass point
echo "1700 ";//cloud base
echo "m "; //cloud base units
out ("temperature", $d); //apparent temperature
echo "1 "; //sunshine hours today so far
echo "420.1 "; // max radiation or so


echo "1"; //is the sun shining?

function WindDirectionNormalName ($direction)
{
	
	$d = $direction;
	$sectorSize = 22.5;
	
	//N
	if (
			($d>=360-1*($sectorSize/2) && $d<=360) 
			|| //360er-Übertrag
			($d>=0 && $d< 1*($sectorSize/2)) 
		)
		$w = "N";
	//NNO
	else if ($d>=1*($sectorSize/2) && $d< 3*($sectorSize/2)) 
		$w = "NNO";
	
	//NO
	else if ($d>=3*($sectorSize/2) && $d< 5*($sectorSize/2)) 
		$w = "NO";
	
	//ONO
	else if ($d>=5*($sectorSize/2) && $d< 7*($sectorSize/2)) 
		$w = "ONO";
	//O
	else if ($d>=7*($sectorSize/2) && $d< 9*($sectorSize/2)) 
		$w = "O";
	//OSO
	else if ($d>=9*($sectorSize/2) && $d< 11*($sectorSize/2)) 
		$w = "OSO";
	//SO
	else if ($d>=11*($sectorSize/2) && $d< 13*($sectorSize/2)) 
		$w = "SO";
	//SSO
	else if ($d>=13*($sectorSize/2) && $d< 15*($sectorSize/2)) 
		$w = "SSO";
	//S
	else if ($d>=15*($sectorSize/2) && $d< 17*($sectorSize/2)) 
		$w = "S";
	//SSW
	else if ($d>=17*($sectorSize/2) && $d< 19*($sectorSize/2)) 
		$w = "SSW";
	//SW
	else if ($d>=19*($sectorSize/2) && $d< 21*($sectorSize/2)) 
		$w = "SW";
	//WSW
	else if ($d>=21*($sectorSize/2) && $d< 23*($sectorSize/2)) 
		$w = "WSW";
	//W
	else if ($d>=23*($sectorSize/2) && $d< 25*($sectorSize/2)) 
		$w = "W";
	//WNW
	else if ($d>=25*($sectorSize/2) && $d< 27*($sectorSize/2)) 
		$w = "WNW";
	//NW
	else if ($d>=27*($sectorSize/2) && $d< 29*($sectorSize/2)) 
		$w = "NW";
	//NNW
	else if ($d>=29*($sectorSize/2) && $d< 31*($sectorSize/2)) 
		$w = "NNW";
	else
		$w = "-";
		
	return $w;
	
	
	
	
}


?>
