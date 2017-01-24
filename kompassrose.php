<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta id="refresh" http-equiv="refresh" content="60; URL=http://schwarzwaldgeier.de/_extphp/wetterstation/kompassrose.php">
<script>
document.getElementById("refresh").content="60; URL=http://schwarzwaldgeier.de/_extphp/wetterstation/kompassrose.php?" + String(Date.now());
</script>

<title>Wetterstation Merkur</title>
</head>

<body>
<?php


@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/php_mysql.php");


$query       = "SELECT * from weather_merkur2 order by uid desc LIMIT 1";
  
    $result      = mysqli_query($connection, $query);
    $anzkomplett = @mysqli_num_rows($result);
	$data = mysqli_fetch_assoc($result);
	
	
	
function WindDirectionNormalName ($direction)
{
	
	$d = $direction;
	$sectorSize = 22.5;
	//TODO get rid of the else if stuff by using mod and math.floor
	//N
	if (($d >= 360-1*($sectorSize/2) && $d<=360) 
			|| //360er-Uebertrag
			($d>=0 && $d< 1*($sectorSize/2)) 
		)
		$w = "Nord";
	//NNO
	else if ($d>=1*($sectorSize/2) && $d< 3*($sectorSize/2)) 
		$w = "Nordnordost";
	
	//NO
	else if ($d>=3*($sectorSize/2) && $d< 5*($sectorSize/2)) 
		$w = "Nordost";
	
	//ONO
	else if ($d>=5*($sectorSize/2) && $d< 7*($sectorSize/2)) 
		$w = "Ostnordost";
	//O
	else if ($d>=7*($sectorSize/2) && $d< 9*($sectorSize/2)) 
		$w = "Ost";
	//OSO
	else if ($d>=9*($sectorSize/2) && $d< 11*($sectorSize/2)) 
		$w = "Ostsüdost";
	//SO
	else if ($d>=11*($sectorSize/2) && $d< 13*($sectorSize/2)) 
		$w = "Südost";
	//SSO
	else if ($d>=13*($sectorSize/2) && $d< 15*($sectorSize/2)) 
		$w = "Südsüdost";
	//S
	else if ($d>=15*($sectorSize/2) && $d< 17*($sectorSize/2)) 
		$w = "Süd";
	//SSW
	else if ($d>=17*($sectorSize/2) && $d< 19*($sectorSize/2)) 
		$w = "Südsüdwest";
	//SW
	else if ($d>=19*($sectorSize/2) && $d< 21*($sectorSize/2)) 
		$w = "Südwest";
	//WSW
	else if ($d>=21*($sectorSize/2) && $d< 23*($sectorSize/2)) 
		$w = "Westsüdwest";
	//W
	else if ($d>=23*($sectorSize/2) && $d< 25*($sectorSize/2)) 
		$w = "West";
	//WNW
	else if ($d>=25*($sectorSize/2) && $d< 27*($sectorSize/2)) 
		$w = "Westnordwest";
	//NW
	else if ($d>=27*($sectorSize/2) && $d< 29*($sectorSize/2)) 
		$w = "Nordwest";
	//NNW
	else if ($d>=29*($sectorSize/2) && $d< 31*($sectorSize/2)) 
		$w = "Nordnordwest";
	else
		$w = ""; //Nullwind, einfach garnichts anzeigen
		
	
	if ($w != "")
		$w = "aus ".$w;
	
	return $w;
	

	
	
	
}
?>
<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   version="1.2"
   width="640"
   height="480"
   id="svg2993">
     <!--[if IE]>
  <div style="font-weight: bold; width: 100%; margin: 3em">Mit dem Internet Explorer kann es zu Darstellungsproblemen kommen.</div>
  <![endif]-->
  <defs
     id="defs2995">
    <inkscape:path-effect
       effect="skeletal"
       id="path-effect3111" />
    <inkscape:path-effect
       effect="skeletal"
       id="path-effect3095" />
    <inkscape:path-effect
       effect="skeletal"
       id="path-effect3091" />
    <inkscape:path-effect
       effect="skeletal"
       id="path-effect3029" />
  </defs>
  <metadata
     id="metadata2998">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <g
     id="layer1">
    <path
       d="m 513.7305,252.17731 a 116.81561,116.81561 0 1 1 -233.63123,0 116.81561,116.81561 0 1 1 233.63123,0 z"
       transform="translate(-73.574469,-11.485396)"
       id="path3001"
       style="fill:none;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />

	<image x="0" y="0" width="640px" height="480px"
    xlink:href="https://www.schwarzwaldgeier.de/_extphp/startplaetze.jpg">
    <title>Startplaetze</title>
  </image>
    <text
       x="304.62408"
       y="108.38695"
       id="text3003"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="304.62408"
         y="108.38695"
         id="tspan3005">N</tspan></text>
    <text
       x="454.35461"
       y="249.08199"
       id="text3007"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="454.35461"
         y="249.08199"
         id="tspan3009">O</tspan></text>
    <text
       x="309.78723"
       y="400.10327"
       id="text3011"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="309.78723"
         y="400.10327"
         id="tspan3013">S</tspan></text>
    <text
       x="158.76596"
       y="247.79118"
       id="text3015"
       xml:space="preserve"
       style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="158.76596"
         y="247.79118"
         id="tspan3017">W</tspan></text>
    <text
       x="33"
       y="47"
       id="wind_speed_text"
       xml:space="preserve"
       style="font-size:25px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="33"
         y="47"
         id="tspan3061"><?php echo $data['wind_speed']; ?> km/h <?php echo WindDirectionNormalName($data['wind_direction']); ?></tspan></text>
		 
		 
		 <text
       	x="33"
      	 y="77"
       id="wind_maxspeed_text"
       xml:space="preserve"
       style="font-size:25px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="33"
         y="77"
         id="tspan3069"><?php echo $data['wind_maxspeed']; ?> km/h Böe</tspan></text>
 
 
 
    <text
       x="463.01202"
       y="46.45829"
       id="temperature_text"
       xml:space="preserve"
       style="font-size:25px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="463.01202"
         y="46.45829"
         id="tspan3065"><?php echo $data['temperature']; ?>° C</tspan>
		 
		 </text>
    <text
       x="32.857899"
       y="420.67325"
       id="pressure_text"
       xml:space="preserve"
       style="font-size:25px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="32.857899"
         y="420.67325"
         id="tspan3073"><?php echo $data['pressure']; ?> hpa</tspan></text>
    <text
       x="390.64389"
       y="420.67325"
       id="humidity_text"
       xml:space="preserve"
       style="font-size:25px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:black;fill-opacity:1;stroke:#202020;font-family:Sans"><tspan
         x="390.64389"
         y="420.67325"
         id="tspan3079"><?php echo $data['humidity']; ?>% Luftfeuchtigkeit</tspan></text>
    
    <?php 
	if ($data['wind_direction'] >=0 || $data['wind_direction'] <= 360) // Bei Nullwind wird nichts angezeigt
	{ ?>
	
    <g transform="rotate (<?php echo $data['wind_direction']; ?>,322,240)">
	<path
       d="m 281.87412,131.32801 40.82092,226.37057 41.62766,-226.37057 -41.62766,61.15071 -40.82092,-61.15071"
       id="wind_direction_arrow"
       style="fill:#000000;fill-rule:evenodd;stroke:none" />
	 </g>
	 
	 <?php 
	}
	 ?>
  </g>

  
</svg>
</body></html>
