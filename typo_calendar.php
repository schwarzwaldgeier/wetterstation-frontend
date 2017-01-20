<table style="margin-bottom: 5px; border-bottom: 5px solid #FF7900;" width="171" cellpadding="3" cellspacing="0" border="0">
	    <tr>
        	<td class="bg_orange_light">
<?php

	if(!(empty($_GET['action']))) { $act = $_GET['action']; } else { $act = ""; }
	if(!(empty($_GET['was']))) { $wa = $_GET['was']; } else { $wa = ""; }
    @require_once($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/parse_request.inc.php");
	@require_once($_SERVER["DOCUMENT_ROOT"]."/_extphp/wetterstation/inc/php_mysql.php");
    parse_request($_GET);
    $caldates = " ";

 	$query = "SELECT substring(record_datetime,1,10) as record_datetime from weather_merkur group by substring(record_datetime,1,10)";
 	$void = mysql_select_db($db);
 	$result = mysql_query($query);
 	$anzkomplett = @mysql_num_rows($result);
 	$x = $anzkomplett - 1;
 	for ($i=0; $i < $anzkomplett; $i++) {
 		$void = mysql_data_seek($result, $i);
 		$array = mysql_fetch_array($result, MYSQL_ASSOC);
 		$caldates .= substr($array["record_datetime"],8,2) . "." . substr($array["record_datetime"],5,2) . "." . substr($array["record_datetime"],2,2) . " ";
	}



$month_names = array("", "Januar","Februar","M&auml;rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
$day_monday = "Mo";
$day_tuesday = "Di";
$day_wednesday = "Mi";
$day_thursday = "Do";
$day_friday = "Fr";
$day_saturday = "Sa";
$day_sunday = "So";
$mydaycount=01; $myday=01;
if (!empty($_GET['sdate'])) {
	$xmymonth = substr($_GET['sdate'],5,2);
	$xmyyear = substr($_GET['sdate'],0,4);
	$xmyday = substr($_GET['sdate'],8,2);
	$mymonth = substr($_GET['sdate'],5,2);
	$myyear = substr($_GET['sdate'],0,4);
} else {
	$noselect = "true";
	$xmymonth=date("n");
	$xmyyear = date("Y");
	$xmyday = date("d");
	$mymonth=date("n");
	$myyear = date("Y");
}

$myyear_after  = $myyear; $myyear_before = $myyear;
$mymonth_next = $mymonth + 1;
if ($mymonth_next > 12)
{
		$mymonth_next = 01;
	$myyear_after = $myyear + 1;
}
$mymonth_before = $mymonth - 1;
if ($mymonth_before < 1)
{
	$mymonth_before = 12;
	$myyear_before = $myyear - 1;
}
$rowmark=0;
while (checkdate($mymonth,$mydaycount,$myyear)):
	$mydaycount++;
endwhile;
$month_name = $month_names[$mymonth];
/*---------------------------------------------*/
echo "\n";
echo "<table style=\"margin: 4px; border: 1px solid #DDDDDD;\" width=\"155\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
echo "<tr>\n";
echo "<td colspan=\"7\" class=\"zz_kurzmarke_bereich\">\n";
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "<tr>\n";
echo "<td class=\"zz_kurzmarke_bereich\" align=\"left\"><a href=\"/index.php?option=com_content&task=view&id=22&Itemid=30&sdate=$myyear_before-".str_pad($mymonth_before,2,"0",STR_PAD_LEFT)."-".str_pad($myday,2,"0",STR_PAD_LEFT)."\"><img alt=\"Voriger Monat\" border=\"0\" src=\"/_extphp/wetterstation/pix/pfeil_links.gif\" width=\"13\" height=\"10\"></td>\n";
echo "<td class=\"zz_kurzmarke_bereich\" align=\"center\" nowrap>$month_name $myyear</td>\n";
echo "<td class=\"zz_kurzmarke_bereich\" align=\"right\"><a href=\"/index.php?option=com_content&task=view&id=22&Itemid=30&sdate=$myyear_after-".str_pad($mymonth_next,2,"0",STR_PAD_LEFT)."-".str_pad($myday,2,"0",STR_PAD_LEFT)."\"><img alt=\"N&auml;chster Monat\" border=\"0\" src=\"/_extphp/wetterstation/pix/pfeil_rechts.gif\" width=\"13\" height=\"10\"></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class=\"tag\">$day_monday</td>\n";
echo "<td class=\"tag\">$day_tuesday</td>\n";
echo "<td class=\"tag\">$day_wednesday</td>\n";
echo "<td class=\"tag\">$day_thursday</td>\n";
echo "<td class=\"tag\">$day_friday</td>\n";
echo "<td class=\"tag\">$day_saturday</td>\n";
echo "<td class=\"tag\">$day_sunday</td>\n";
echo "</tr>\n";
echo "\n<tr>\n";
while ($myday<$mydaycount):
	if ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Monday')
	{
		makelink($myday, $mymonth, $myyear, $myday, "wochentag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);


		$rowmark= '01';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Tuesday')
	{
		makelink(AddDay(-1), $mymonth_before, $myyear_before, AddDay(-1), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		makelink($myday, $mymonth, $myyear, $myday, "wochentag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark= '02';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Wednesday')
	{
		for ($i=1; $i<=2; $i++)
		{
			makelink(AddDay(-3 + $i), $mymonth_before, $myyear_before, AddDay(-3 + $i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		}
		makelink($myday, $mymonth, $myyear, $myday, "wochentag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark= '03';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Thursday')
	{
		for ($i=1; $i<=3; $i++)
		{
			makelink(AddDay(-4 + $i), $mymonth_before, $myyear_before, AddDay(-4 + $i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		}
		makelink($myday, $mymonth, $myyear, $myday, "wochentag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark= '04';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Friday')
	{
		for ($i=1; $i<=4; $i++)
		{
			makelink(AddDay(-5 + $i), $mymonth_before, $myyear_before, AddDay(-5 + $i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		}
		makelink($myday, $mymonth, $myyear, $myday, "wochentag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark= '05';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Saturday')
	{
		for ($i=1; $i<=5; $i++)
		{
			makelink(AddDay(-6 + $i), $mymonth_before, $myyear_before, AddDay(-6 + $i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		}
		makelink($myday, $mymonth, $myyear, $myday, "samstag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark= '06';
	}
	elseif ($myday == '01' and date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Sunday')
	{
		for ($i=1; $i<=6; $i++)
		{
			makelink(AddDay(-7 + $i), $mymonth_before, $myyear_before, AddDay(-7 + $i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		}
		makelink($myday, $mymonth, $myyear, $myday, "sonntag",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark = '07';
	}
	else
	{	$cssclass = "wochentag";
		if (date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Sunday')
		{ $cssclass = "sonntag";}

		if (date('l', mktime(0,0,0,$mymonth,$myday,$myyear)) == 'Saturday')
		{ $cssclass = "samstag";}

		makelink($myday, $mymonth, $myyear, $myday, $cssclass,$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
	}
	$myday++;
	$rowmark++;

	if ($rowmark>7)
	{	echo "\n</tr>\n<tr>";
		$rowmark='01';
	}
endwhile;
$myday-- ;
$i = 1 ;
if ($rowmark != 1)
{
	while ($rowmark < 8)
	{	makelink(AddDay($i), $mymonth_next, $myyear_after, AddDay($i), "meinmonat",$caldates,$xmyday, $xmymonth, $xmyyear, $noselect);
		$rowmark++ ;
		$i++ ;
	}
}
echo "\n</tr>\n";
echo "</table>\n";
echo "</form>\n";
function makelink($Taglink, $Monatlink, $Jahrlink, $Linktext, $cssStil, $caldates2,$xmyday2, $xmymonth2, $xmyyear2, $noselect2)
{
	//echo "$Taglink $Monatlink $Jahrlink <br>";
//	global $xmyday, $xmymonth, $xmyyear, $noselect;

    //echo str_pad($Taglink,2,"0",STR_PAD_LEFT).".".str_pad($Monatlink,2,"0",STR_PAD_LEFT).".".substr($Jahrlink,2,2);
  //  echo $caldates;


	$TodayMarker = "";
	$LinkMarker = "";
	if (date("d.m.Y") == date("d.m.Y", mktime(0,0,0,$Monatlink,$Taglink,$Jahrlink))) $TodayMarker = " id=\"heute\"";
	if ((date("d.m.Y", mktime(0,0,0,$xmymonth2,$xmyday2,$xmyyear2))) == date("d.m.Y", mktime(0,0,0,$Monatlink,$Taglink,$Jahrlink))) $LinkMarker = " id=\"marked\"";
	if(!(empty($noselect2))) {
		$LinkMarker = "";
	}
	echo "<td class=\"$cssStil\" $TodayMarker>";

	if 	(eregi(str_pad($Taglink,2,"0",STR_PAD_LEFT).".".str_pad($Monatlink,2,"0",STR_PAD_LEFT).".".substr($Jahrlink,2,2), $caldates2)) {
		echo "<a title=\"Wetterwerte für den ".str_pad($Taglink,2,"0",STR_PAD_LEFT).".".str_pad($Monatlink,2,"0",STR_PAD_LEFT).".".substr($Jahrlink,2,2)." anzeigen\" href=\"/index.php?option=com_content&task=view&id=22&Itemid=30&sdate=$Jahrlink-".str_pad($Monatlink,2,"0",STR_PAD_LEFT)."-".str_pad($Taglink,2,"0",STR_PAD_LEFT)."\" class=\"$cssStil\"$LinkMarker>";
		echo $Linktext."</a></td>";
	} else {
		echo $Linktext."</td>";
	}


}
function AddDay($AnzDays)
{	global $myday, $mymonth, $myyear;
	return date("j", mktime(0,0,0,$mymonth,$myday + $AnzDays,$myyear));
}
/*---------------------------------------------*/
?>
      	</td>
    </tr>
 </table>

