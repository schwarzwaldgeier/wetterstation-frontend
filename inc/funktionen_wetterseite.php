<?php
//Global: Die Farben für Windrichtung und -Stärke		
define("EASY", "#b0f4b0"); 			//Hellgrün, 
define("MODERATE", "#ffff99");		//Helgelb
define("HARD", "#FF9999");			//und hellrot. Dunklere Farbtöne wären auf Handys schwerer lesbar.

date_default_timezone_set('Europe/Berlin'); //Sorgt für korrekte Sommer- und Winterzeit

		

//Ist das aufrufende Endgerät ein Handy?  Muss erweitert werden, wenn neue Geräte auftauchen.
//Blackberry ist noch nicht dabei, da wohl etwas komplizierter. Braucht eh kein Mensch.
function isMobileDevice()
{
    
    if (strstr($_SERVER['HTTP_USER_AGENT'], 'Android') //Android
	|| strstr($_SERVER['HTTP_USER_AGENT'], 'webOS') //Palm, relativ exotisch
	|| strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') //iPhone. iPad zählt hier nicht als mobil, da der Bildschirm groß genug ist.
	|| strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') //iPod touch etc.
	&& $_GET["view"] != "desktop") //Mit view=desktop in der url lässt sich so die mobilansicht unterdrücken.
	{ 
        return true;
        
    } else
        return false;
    
}


//Windrichtung in Ampelfarben.
function WindDirectionColor ($wdir)

{
	$quality = WindDirectionQuality($wdir);
	//echo "Qualität ".$quality;
	
	switch ($quality)
	{
		case 1: $color = EASY; break;
		case 2: $color = MODERATE; break;
		default; $color = HARD; break;
	} 
	
		
		
		return $color;
		
	
	
}

//Numerische "Qualität" der Windrichtung. Gilt für beide Startplätze gleichzeitig.
//1 = Optimal
//2 = Laut FBO noch eingeschränkt erlaubt
//3 = Laut FBO untersagt.

function WindDirectionQuality ($wdir)

{


		//SP West:

		if (($wdir >= 181) && ($wdir <= 219)) { //181 - 219, grenzwertig
            $quality = 2; 
           
        }
        else if (($wdir >= 220) && ($wdir <= 300)) { //220 - 300, optimal
            $quality = 1;
            
        }
        else if (($wdir >= 301) && ($wdir <= 330)) { //301 - 330, grenzwertig
            $quality = 2;
            
        }
        
		
      	//SP Nordost
        else if (($wdir >= 10) && ($wdir <= 19)) { //10-19, grenzwertig
            $quality = 2;
            
        }
        else if (($wdir >= 20) && ($wdir <= 50)) { //20-50, optimal
            $quality = 1;
            
        }
        else if (($wdir >= 51) && ($wdir <= 70)) { //0-60, grenzwertig. Edit 26.6.14: "Grenzwertig" nun bis 70°, da die Wetterstation durch Lee etc.(?) gern mal zu viel Ost zeigt. - Sebastian
            $quality = 2;
            
        }
		
		else $quality = 3;
		
		return $quality;
		
	
	
}

//Gibt den bei aktuellen Windverhältnissen empfohlenen Startplatz aus.
function GetLaunchSite($wdir)
{
	//SP West:

		if (($wdir >= 181) && ($wdir < 220)) { //181 - 219  //Südeinschlag
            $site = "W"; 
           
        }
		else if (($wdir >= 220) && ($wdir < 300)) { //220 - 300 //Optimal
				$site = "W";
            
        }
        else if (($wdir >= 300) && ($wdir <= 330)) { //301 - 330 //Nordeinschlag. Bäume sind unsere Freunde!
            $site = "W";
            
        }
        
		
      	//SP Nordost
        else if (($wdir >= 10) && ($wdir < 20)) { //10-19 //Nordeinschlag
            $site = "NO";
            
        }
        else if (($wdir >= 20) && ($wdir < 50)) { //20-50 //Optimal
            $site = "NO";
            
        }
        else if (($wdir >= 50) && ($wdir <= 70)) { //0-60, Osteinschlag. Edit 26.6.14: "Grenzwertig" nun bis 70°, da die Wetterstation durch Lee etc.(?) gern mal zu viel Ost zeigt. - Sebastian
            $site = "NO";
            
        }
		
		else $site = "-";
		 
		//echo "site: ".$site;
		return $site;	
}

//Übersetzt Gradangaben in Windrichtungskürzel wie "NO", "W" usw.
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


//Prüft, ob ein Datum hinreichend aktuell ist. $tolerance ist der maximale zeitliche Abstand zu "jetzt".
//Sinn: Datum soll rot eingefärbt werden, wenn die Station futsch ist, also nur alte Daten liefert.
//$tolerance = erlaubter delay.

function IsUpToDate ($date, $tolerance)
{
	
	$now = date('d.m. H:i');
	
	$differenceInSeconds = $now - $date;
	if ($differenceInSeconds > $tolerance)	{
echo '<!--';
echo $now.'/'.$date.'/'.$differenceInSeconds ;
echo ' Daten alt!';

echo '\n-->';
		return false;
	}
	else	{
		return true; 
	}
}



//Startbarkeit der Windstärke in Zahlen 1-3 ausgedrückt
//1=Relativ problemlos
//2=schwierig
//3=sehr schwierig bzw. unmöglich
//alles natürlich vom individuellen Können abhängig, aber gibt etwas Orientierung.
//isSquall (bool) gibt an, ob es sich um eine Böe handelt, für diese gelten höhere Grenzwerte

function WindSpeedQuality($speed, $wdir, $isSquall)
{
	
	$launchSite = GetLaunchSite($wdir);
	//echo "<!-- Startplatz: ".$launchSite. " -->";

	
	//Weststartplatz:
	if ($launchSite == "W")	{
		if ($isSquall == false)	{ 	//normal
			$limitEasy = 18; 		
			$limitModerate = 26; 	
		} else {					//böe
			$limitEasy = 22; 		
			$limitModerate = 30; 		
		}
	}
	
	
	//NO Startplatz
	else if ($launchSite == "NO")	{
		if ($isSquall == false)	{ 	//normal
			$limitEasy = 18; 		
			$limitModerate = 26; 	
		} else {					//böe
			$limitEasy = 22; 		
			$limitModerate = 30; 		
		}
	}
	
	//Kein Startplatz
	else	{					
		$limitEasy = 0; 		
		$limitModerate = 0; 
	}
	
	
	
	
	
	
							
						
	
	
	if ($speed <= $limitEasy)
		$quality=1;
	else if ($speed <= $limitModerate)
		$quality = 2;
	else 
		$quality = 3;
		
	return $quality;	
}

//Weist Windgeschwindigkeiten eine Farbe zu. 40 km/h ist schließlich nicht startbar, nur weil er schön aus West kommt. 
//Die Richtung wird dabei mit berücksichtigt, schwacher Wind von falscher Seite macht's auch nicht leichter.
//Issquall: True wenn Böe
function WindSpeedColor($speed, $wdir, $isSqall) 
{
	
	$quality = WindSpeedQuality ($speed, $wdir, $isSqall);
	
	if (WindDirectionQuality($wdir) == 3) //Wenn der Wind aus der falschen Richtung kommt, ist er eh unstartbar
	{
		$color = HARD;
	}
	else if (WindDirectionQuality($wdir) == 2) //Wenn er aus einer grenzwertigen Richtung (FBO) kommt, kriegt er noch gelb, wenn er schwach ist, ansonsten rot.
	{
		if ($quality == 1)
			$color = MODERATE;
		else $color = HARD;
	}
		
		
	else {
		switch ($quality)
		{
			case 1: $color =  EASY; break;
			case 2: $color =  MODERATE; break;
			default: $color = HARD; break;
		}
	}
	
		
		
	return $color;
	
	
	
	
	
	
}

//Stellt die Windrichtung als Pfeil dar (SVG-Format)
function DisplayWindDirectionArrow($direction)
{
    if ($direction >= 0 && $direction <= 360) {
?>
		<svg width="32px" height="32px" xmlns="http://www.w3.org/2000/svg">
			<g>
				<title>Windrichtung</title>
				<g transform="rotate(<?php echo round($direction, 0);?>, 16, 16)" >
					<path fill="#000000" d="m21,4l-10,0l5,24"/>
				</g>
			</g>
		</svg>
		
		
		<?php
        
        echo "&nbsp;";
        echo round($direction, 0) . "&#x00b0;&nbsp;(".WindDirectionNormalName($direction).")";
		//echo round($direction, 0) . "&#x00b0;";
    } else {
        echo "-"; //Wert nicht feststellbar / Nullwind
        //echo '<!--  Fehlerhafte Windrichtung: ' . $direction . ' -->';
    }
}


//Tabelle "Letzte 10 Messungen"
function last_records($connection,  $rcount)
{
    //check, ob wir die Tabelle kleiner brauchen
    $isMobileDevice = isMobileDevice();
    
?>

	<h3>Wetterstation Merkur: Die letzten <?php echo $rcount; ?> Messungen</h3>
    <?php
    if (isMobileDevice())
        echo '<div class="responsive">';
?>
    <table style="width: 60%; border: 1px #0000ff solid; line-height: 10px; ">

    		<tr>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="8%" valign="top">Windr.</td>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">Geschw.</td>
			<!--<sup><a style="color: #ffffff" href="#erklaerung-originalwert">*</a><sup></td>
<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">Geschw. (Turm, gemessen)<sup><a style="color: #ffffff" href="#erklaerung-originalwert">*</a><sup></td>-->
           <td class: desktop-only style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">B&ouml;e</td> 
			
<!-- <td class: desktop-only style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">B&ouml;e<sup><a style="color: #ffffff" href="#erklaerung-originalwert">*</a><sup></td> -->
    		<?php
    if (!$isMobileDevice) {
?>
    			
    			<td class: desktop-only style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">Luftdruck</td>
    		<?php
    }
?>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="26%" valign="top">Mess-Zeit</td>
    		
            </tr>


    <?php
    $query       = "SELECT * from wp_weather_merkur2 order by uid desc LIMIT $rcount";
   // $void        = mysqli_select_db($db);
    $result      = mysqli_query($connection, $query);
    $anzkomplett = @mysqli_num_rows($result);
    $fa          = 0;
    for ($i = 0; $i < $anzkomplett; $i++) {
        if ($fa == 0) {
            $bgs = "#cccccc";
            $fa  = 1;
        } else {
            $bgs = "#dddddd";
            $fa  = 0;
        }
        $void  = mysqli_data_seek($result, $i);
        $array = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
		
        
		
		if (!($wdir = round($array["wind_direction"], 0)))
			$wdir = 361; //Falls nichts gezogen werden kann. Dieser Wert lässt dann "Störung" anzeigen.
		
		
	
		
		
        
        
?>
    		<tr style="background-color: <?php
        echo $bgs;
?>; color: black; font-weight: bold">
    		<td style="color: black; font-weight: bold; background-color: <?php
        echo WindDirectionColor ($wdir);
?>; text-align:right;" align="right" valign="top">
				<?php
        DisplayWindDirectionArrow($wdir);
?>
            </td>
			<?php
	    if (isset($_GET["windspeed"]))
		{
			if ($_GET["windspeed"] == "original")
			{
				$array["wind_speed"] = $array["original_wind_speed"];
				$array["wind_maxspeed"] = $array["original_wind_maxspeed"];
			}
		}
	    
	    ?>

    		<td style="text-align:right; background-color: <?php echo WindSpeedColor(round($array["wind_speed"], 0), $wdir, false) ?>;" valign="top"><?php
        echo round($array["wind_speed"], 0);
?>&nbsp;km/h</td>
<!-- <td style="text-align:right; background-color: <?php echo WindSpeedColor(round($array["original_wind_speed"], 0), $wdir, false) ?>;" valign="top"><?php
        echo round($array["original_wind_speed"], 0);
?>&nbsp;km/h</td> -->
            <td  style="text-align:right;background-color: <?php echo WindSpeedColor(round($array["wind_maxspeed"], 0), $wdir, true) ?>; " valign="top"><?php
            echo round($array["wind_maxspeed"], 0);
?>&nbsp;km/h</td>
 <!-- <td  style="text-align:right;background-color: <?php echo WindSpeedColor(round($array["original_wind_maxspeed"], 0), $wdir, true) ?>; " valign="top"><?php
            echo round($array["original_wind_maxspeed"], 0);
?>&nbsp;km/h</td> -->
           
    		
    		<?php
        if (!$isMobileDevice) {
?>
    			
    			<td  style="text-align:right;" align="right" valign="top"><?php
            echo $array["pressure"];
?>&nbsp;hpa</td>
			<?php
        }



        
	
		
        if ($isMobileDevice)
            $datumsformat = "H:i";
        else
            $datumsformat = "d.m. H:i";

$korrekturwert_zeitzone = 0;
?>

    		<td style="text-align:right; <?php if (!IsUpToDate(date($datumsformat, $array["tstamp"] + $korrekturwert_zeitzone), 800) ) {echo "background-color: red;"; $veraltet=true;} else {$veraltet=false;} ?>" valign="top" ><?php
        echo date($datumsformat, $array["tstamp"] + $korrekturwert_zeitzone);
		if ($veraltet)
		{
			echo '<span style="font-size: smaller;"><br /><br />Veraltete Daten! Station defekt?</span>';
			
		}
		//echo " ";
		//echo time_elapsed_string($array["tstamp"] + $korrekturwert_zeitzone);
?></td>
 
    		
            </tr>
   
	
 <?php } ?>


	</table>

<!-- <div id="erklaerung-originalwert">* Die Wetterstation ist an der Spitze des Merkurturms angebracht, am Startplatz hat es durch den Kegeleffekt weniger Wind. Bisher wurde immer nur die Messung geteilt durch 1,35 angezeigt (der "geschätzte" Wert). Im Sinne maximaler Transparenz zeigen wir nun beides an. Der Faktor funktioniert außerdem nicht in allen Situationen gleich gut. Die Funk- und Telefondurchsage sagt weiterhin den gewohnten Schätzwert an. </div> -->

<?php
    if (isMobileDevice())
        echo '</div>';
?>
<?php
}
?>


<?php

//Tabelle Durchschnittswerte der letzten Minuten
function data_avg($mins, $connection)
{
    $ws          = 0;
    $te          = 0;
    $pr          = 0;
    $ms          = 0;
    $wd_u        = 0;
    $wd_v        = 0;
    $wd_w        = 0;
    $mytime      = time() - $mins * 60;
    #$query = "SELECT avg(wind_speed) as wind_speed,avg(wind_direction) as wind_direction from wp_weather_merkur2 where tstamp > $mytime limit 1";
    $query       = "SELECT wind_speed, wind_direction, temperature, pressure, wind_maxspeed from wp_weather_merkur2 where tstamp > $mytime";
//    $void        = mysqli_select_db($db);
    $result      = mysqli_query($connection, $query);
    $anzkomplett = @mysqli_num_rows($result);
    $x           = $anzkomplett - 1;
    $recs        = $anzkomplett;
    if ($recs > 0) {
        for ($i = 0; $i < $anzkomplett; $i++) {
            $void  = mysqli_data_seek($result, $i);
            $array = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $wd_u += sin(round($array["wind_direction"], 0) * M_PI / 180);
            $wd_v += cos(round($array["wind_direction"], 0) * M_PI / 180);
            $ws += round($array["wind_speed"], 0);
            $te += round($array["temperature"], 0);
            $pr += round($array["pressure"], 0);
           // $recs = $array["recs"];
            if ($array["wind_maxspeed"] > $ms) {
                $ms = round($array["wind_maxspeed"], 0);
            }
            
        }
        
        $wd_u = $wd_u / $anzkomplett;
        $wd_v = $wd_v / $anzkomplett;
        $wd_w = atan2(abs($wd_u), abs($wd_v)) * 180 / M_PI;
        
        if (($wd_u >= 0) && ($wd_v >= 0)) {
            $wd_w = $wd_w;
        }
        if (($wd_u >= 0) && ($wd_v < 0)) {
            $wd_w = 180 - $wd_w;
        }
        if (($wd_u < 0) && ($wd_v >= 0)) {
            $wd_w = 360 - $wd_w;
        }
        if (($wd_u < 0) && ($wd_v < 0)) {
            $wd_w = 180 + $wd_w;
        }
        $wd_w = round($wd_w, 0);
        $ws   = round($ws / $anzkomplett);
        $te   = round($te / $anzkomplett);
        $pr   = round($pr / $anzkomplett);
    }
    
    $arr["wd"] = $wd_w;
    $arr["ws"] = $ws;
    $arr["te"] = $te;
    $arr["pr"] = $pr;
    $arr["ms"] = $ms;
    
    //return $arr;
    
    //$fcol = WRED;
   
    
?>






    		<tr style="background-color: #cccccc; color:black; font-weight: bold">
                <td style="color: black; font-weight: bold; text-align:right; background-color: <?php echo WindDirectionColor($arr["wd"]); ?>" align="right" valign="top">
                <?php
        DisplayWindDirectionArrow($arr["wd"]);
    ?></td>
                <td style="text-align:right; background-color: <?php echo WindSpeedColor($arr["ws"], $arr["wd"], false) ?>;" valign="top"><?php
        echo $arr["ws"];
    ?>&nbsp;km/h</td>
                <td style="text-align:right;  background-color: <?php echo WindSpeedColor($arr["ms"], $arr["wd"], true) ?>; " valign="top"><?php echo $arr["ms"];
    ?>&nbsp;km/h </td>

                <td style="text-align:right;" valign="top"><?php echo $arr["pr"]; ?> hpa</td>
                <td style="text-align:right;" valign="top">letzte <?php echo $mins; ?> Minuten</td>
    		
            </tr>



<?php
    
}



//Rohdaten aus Datenbank abholen.
function rawdata_avg($mins, $connection)
{
    $ws           = 0;
    $te           = 0;
    $pr           = 0;
    $ms           = 0;
    $wd_u         = 0;
    $wd_v         = 0;
    $wd_w         = 0;
    $mytime       = time() - $mins * 60;
    $query        = "SELECT min(wind_speed) mis, max(wind_speed)mas, min(wind_direction) mid, max(wind_direction) mad from wp_weather_merkur2 where tstamp > $mytime";
  
   // $mysqli = mysqli
  //  $void         = mysqli_select_db('wetter');
    $result       = mysqli_query($connection, $query);
//echo "RES:" . $result;    
//echo mysqli_error($connection);
//print_r($result);

$void         = mysqli_data_seek($result, 0);
    $array        = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $arr["wdmin"] = $array["mid"];
    $arr["wdmax"] = $array["mad"];
    $arr["wsmin"] = $array["mis"];
    $arr["wsmax"] = $array["mas"];
    return $arr;
	
}
	
	

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'Jahre',
                30 * 24 * 60 * 60       =>  'Monate',
                24 * 60 * 60            =>  'Tage',
                60 * 60                 =>  'Stunden',
                60                      =>  'Minuten',
                1                       =>  'Sekunden'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            //return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
			return 'Vor '.$r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}





?>
