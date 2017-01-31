<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//create a txt file as described on http://wiki.sandaysoft.com/a/Realtime.txt


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
echo $now->format('d/m/Y')." "; //1. day
echo $now->format('H:m:s')." "; //2. time


out("temperature", $d); //3. outside temperature
out("humidity", $d);    //4. relative humidity
echo ("0 "); //5. dew point


$query_aws = "SELECT AVG(a.wind_speed) AS ws_avg FROM (SELECT wind_speed FROM weather_merkur2 ORDER BY tstamp DESC LIMIT 10) a;";
$r_aws = mysqli_query($connection, $query_aws);
$d_aws = mysqli_fetch_assoc($r_aws);
out("ws_avg", $d_aws); //6. avg wind speed
out("wind_speed", $d); //7. latest wind speed reading
out("wind_direction", $d); //8. wind bearing

echo "0.0 "; // 9. current rain rate
echo "0.0 "; // 10. rain today
out("pressure", $d); // 11. barometer
echo(WindDirectionNormalName($d["wind_direction"] ) )." "; //12 compass point
echo "0 " ; //13. beaufort (fake)
echo "km/h "; // 14 wind unit
echo "C "; // 15 temp unit
echo "hPa "; //16 pressure unit
echo "mm "; // 17 rain unit (fake)
echo "0.0 "; //18 TODO wind_run
echo "+0.0 "; //19 TODO pressure change
echo "0.0 "; //20 monthly rainfall
echo "0.0 "; // 21 yearly rainfall
echo "0.0 "; //22 yesterday rainfall
echo "20.0 "; //23 inside temp
echo "50 "; // 25 inside humidity
out("wind_chill", $d);
echo "+0.0 "; //26 TODO temperature trend value TODO
out("temperature", $d); //27 TODO high temp

echo $now->format('H:m')." "; //28 time hightemp


out("temperature", $d); //29 tody lowtemp
echo $now->format('H:m')." "; //30 lowtemp time
out("wind_maxspeed", $d); //31 today high wind speed avg
echo $now->format('H:m')." ";  //32
out("wind_maxspeed", $d); //33 today high gust 
echo $now->format('H:m')." "; //34
echo "1024.25 "; //35 today high pressure
echo $now->format('H:m')." "; //36
echo "1024.25 "; //37 today low pressure
echo $now->format('H:m')." ";  //38
echo "1.8.7 "; //39 cumulus fake version
echo "819 "; //40 cunumulus fake build number

out("wind_maxspeed", $d); //41 10 min high gust
echo "0 "; //42 heat index
echo "0 "; //43 humidex
echo "0 "; //44 UV index
echo "0 "; //45 evapowhatever
echo "0 "; //46 solar rad
out("wind_direction", $d); //47 10min avg wind bearing
echo "0 "; //48 rainfall last hour
echo "0 "; //49 number of current whatever forecast
echo "1 "; //50 daylight yes no
echo "0 "; //51 connection lost?

echo(WindDirectionNormalName($d["wind_direction"] ) )." "; //52 avg wind dir TODO
echo "1700 ";//53 cloud base
echo "m "; //54 cloud base units
out ("temperature", $d); //55 apparent temperature
echo "1 "; //56 sunshine hours today so far
echo "420.1 "; //57 max radiation or so


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
		$w = "NNE";
	
	//NO
	else if ($d>=3*($sectorSize/2) && $d< 5*($sectorSize/2)) 
		$w = "NE";
	
	//ONO
	else if ($d>=5*($sectorSize/2) && $d< 7*($sectorSize/2)) 
		$w = "ENE";
	//O
	else if ($d>=7*($sectorSize/2) && $d< 9*($sectorSize/2)) 
		$w = "E";
	//OSO
	else if ($d>=9*($sectorSize/2) && $d< 11*($sectorSize/2)) 
		$w = "ESE";
	//SO
	else if ($d>=11*($sectorSize/2) && $d< 13*($sectorSize/2)) 
		$w = "SE";
	//SSO
	else if ($d>=13*($sectorSize/2) && $d< 15*($sectorSize/2)) 
		$w = "SSE";
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
