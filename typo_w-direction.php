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
		global $wbeg;
		global $wend;
		global $anzkomplett;
		global $sdate;

		$maxspeed = 0;
    	$query = "SELECT * from wp_weather_merkur2 where record_datetime like '".$sdate."%' order by uid desc";
    //	$void = mysqli_select_db($db);
    	$result = mysqli_query($connection, $query);
    	$anzkomplett = @mysqli_num_rows($result);
    	$x = $anzkomplett - 1;
    	for ($i=0; $i < $anzkomplett; $i++) {
    		$void = mysqli_data_seek($result, $i);
    		$array = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$datay[$x] = round($array["wind_direction"],0);
//			if ($array["wind_speed"] > $maxspeed) { $maxspeed = $array["wind_speed"]; }
    		$datax[$x] = $array["tstamp"];
    		if($i == 0) { $wend = $array["tstamp"]; }
    		$wbeg = $array["tstamp"];
    		$x--;
    	}
    	$maxspeed = $maxspeed + 5;
 }
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph.php");
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph_scatter.php");
include ($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/jpgraph/src/jpgraph_plotline.php");
last_records(200, $connection);


// The callback that converts timestamp to minutes and seconds
function TimeCallback($aVal) {
    return Date('H:i:s',$aVal);
}

// Setup the basic graph
$graph = new Graph(540,447,"auto",3);
$graph->SetMargin(30,10,50,70);



$graph->SetBackgroundImage("typo_direction.png",BGIMG_FILLPLOT);
//$graph->AdjBackgroundImage(0,0);
$graph->img->SetAntiAliasing("white");



$graph->title->Set('Merkur Windrichtungs-Sensor');
$graph->subtitle->Set(date('d.m.Y H:i:s',$wbeg).' - '.date('d.m.Y H:i:s',$wend));
$graph->SetAlphaBlending();

// Setup a manual x-scale (We leave the sentinels for the
// Y-axis at 0 which will then autoscale the Y-axis.)
// We could also use autoscaling for the x-axis but then it
// probably will start a little bit earlier than the first value
// to make the first value an even number as it sees the timestamp
// as an normal integer value.
$graph->SetScale("intlin",0,360,$datax[0],$datax[$anzkomplett - 1]);

$graph->yaxis->scale->ticks->Set(10); // Set major and minor tick to 10



// Setup the x-axis with a format callback to convert the timestamp
// to a user readable time
$graph->xaxis->SetLabelFormatCallback('TimeCallback');
$graph->xaxis->SetLabelAngle(90);


// Create the line
$p1 = new ScatterPlot($datay,$datax);

// Add horizontal lines to display the allowed window
$west_line = new PlotLine(HORIZONTAL, 22.5*11.5, 'lightgreen', 2*22.5);
$northost_line = new PlotLine(HORIZONTAL, 22.5*2, 'lightgreen', 22.5);

$graph->Add($west_line);
$graph->Add($northost_line);

//$p1 = new LinePlot($datay,$datax);
//$p1->SetColor("#1FE55C");

// Set the fill color partly transparent
//$p1->SetFillColor("#1FE55C@0.6");

// Add lineplot to the graph
$graph->Add($p1);

// Output line
$graph->Stroke();
?>




