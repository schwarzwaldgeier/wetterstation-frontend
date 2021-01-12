<?php
	$anzkomplett = 0;
date_default_timezone_set("Europe/Berlin");

    @require_once($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/parse_request.inc.php");
	@require_once($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/php_mysql.php");
    parse_request($_GET);
	if(!(empty($_GET["rec_count"]))) { $rec_count = $_GET["rec_count"]; } else { $rec_count = 10; }
	if(!(empty($_GET["avg_hours"]))) { $avg_hours = $_GET["avg_hours"]; } else { $avg_hours = 1; }

	if(!(empty($_GET["sdate"])) && preg_match("/^\d{4}-(0\d|1[0-2])-(0\d|1\d|2\d|3[01])$/s", $_GET["sdate"])) { $sdate = $_GET["sdate"]; } else { $sdate = date("Y-m-d"); }




function last_records($rcount, $connection) {
		global $datax;
		global $datay;
		global $maxspeed;
		global $minspeed;
		global $wbeg;
		global $wend;
		global $anzkomplett;
		global $sdate;
		$maxspeed = 0;
		$minspeed = 2000;
    	$query = "SELECT * from wp_weather_merkur2 where record_datetime like '".$sdate."%' order by uid desc";
    
    	$result = mysqli_query($connection, $query);
    	$anzkomplett = @mysqli_num_rows($result);
    	$x = $anzkomplett - 1;
    	for ($i=0; $i < $anzkomplett; $i++) {
    		$void = mysqli_data_seek($result, $i);
    		$array = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$datay[$x] = $array["pressure"];
			if ($array["pressure"] > $maxspeed) { $maxspeed = $array["pressure"]; }
			if ($array["pressure"] < $minspeed) { $minspeed = $array["pressure"]; }
    		$datax[$x] = $array["tstamp"];
    		if($i == 0) { $wend = $array["tstamp"]; }
    		$wbeg = $array["tstamp"];
    		$x--;
    	}
    	$maxspeed = $maxspeed + 1;
    	$minspeed = $minspeed - 1;
 }
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph.php");
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph_line.php");
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph_plotline.php");



// The callback that converts timestamp to minutes and seconds
function TimeCallback($aVal) {
    return Date('H:i:s',$aVal);
}

// Fake some suitable random data
/*
$now = time();
$datax = array($now);
for( $i=0; $i < 360; $i += 10 ) {
    $datax[] = $now + $i;
}




$n = count($datax);
$datay=array();
for( $i=0; $i < $n; ++$i ) {
    $datay[] = rand(30,150);
}

*/

// Setup the basic graph
$graph = new Graph(540,344,"auto",3);
$graph->SetMargin(50,10,50,70);


last_records(200, $connection);

//$graph->SetBackgroundImage("pix/baro.jpg",BGIMG_FILLPLOT);
//$graph->AdjBackgroundImage(0,0);
//$graph->img->SetAntiAliasing("white");



$graph->title->Set('Merkur Luftdruck-Sensor (hpa)');
$graph->subtitle->Set(date('d.m.Y H:i:s',$wbeg).' - '.date('d.m.Y H:i:s',$wend));
$graph->SetAlphaBlending();

// Setup a manual x-scale (We leave the sentinels for the
// Y-axis at 0 which will then autoscale the Y-axis.)
// We could also use autoscaling for the x-axis but then it
// probably will start a little bit earlier than the first value
// to make the first value an even number as it sees the timestamp
// as an normal integer value.
$graph->SetScale("intlin",$minspeed,$maxspeed,$datax[0],$datax[$anzkomplett - 1]);

// Setup the x-axis with a format callback to convert the timestamp
// to a user readable time
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
$graph->xaxis->SetLabelAngle(90);

// Create the line
$p1 = new LinePlot($datay,$datax);
$p1->SetColor("#000066");

// Set the fill color partly transparent
$p1->SetFillColor("#000066@0.6");

// Add lineplot to the graph
$graph->Add($p1);

// Output line
$graph->Stroke();
?>




