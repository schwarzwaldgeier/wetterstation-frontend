<?php


//error_reporting(E_ALL);
//ini_set('display_errors', 1);


@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/parse_request.inc.php");

//Datenbankverbindung
@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/php_mysql.php");

//Ausgelagerte Funktionen
@require_once($_SERVER["DOCUMENT_ROOT"] . "/_extphp/wetterstation/inc/funktionen_wetterseite.php");

//Get-Parameter verarbeiten. Geht nur außerhalb Wordpress!
parse_request($_GET);

//echo $_SERVER["HTTP_REFERER"];



/*
//if(!(eregi('schwarzwaldgeier.de',$_SERVER["HTTP_REFERER"]))) {
if ($_COOKIE["gei"] != "ichbineingeier") {
    $wrefer = $_SERVER["HTTP_REFERER"];
    $void   = mysql_select_db($db);
    if (!(empty($_REQUEST["jalUserName"]))) {
        $wname = $_REQUEST["jalUserName"];
    } else {
        $wname = "";
    }
    $wbrowser = $_SERVER["HTTP_USER_AGENT"];
    $wquery   = "INSERT INTO redir_stats (uname,ubrause,urefer) values ('$wname','$wbrowser','$wrefer')";
    if ($void != 1) { 
        $error .= "could not select database $db !!!!";
    }
    $wresult = mysql_query($wquery);
    if ($wresult != 1) {
        $error .= "could not issue sql-statement ($wquery) !!!!";
        
    }
    
    
    
}
*/
?>



<!--
<link rel="stylesheet" href="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_cal.css" type="text/css" />
-->

<?php
$dat120   = rawdata_avg(120, $connection);
$vereist  = "";
$checkeis = false;


if (!(empty($_GET["rec_count"]))) {
    $rec_count = $_GET["rec_count"];
} else {
    $rec_count = 10;
}
if (!(empty($_GET["avg_hours"]))) {
    $avg_hours = $_GET["avg_hours"];
} else {
    $avg_hours = 2;
}
if (!(empty($_GET["sdate"])) && preg_match("/^\d{4}-(0\d|1[0-2])-(0\d|1\d|2\d|3[01])$/s", $_GET["sdate"])) {
    $sdate = $_GET["sdate"];
} else {
    $sdate = date("Y-m-d");
}

?>

<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script>
		/*
		 * jQuery UI Datepicker: Parse and Format Dates
		 * http://salman-w.blogspot.com/2013/01/jquery-ui-datepicker-examples.html
		 */
		$(function() {
			$("#datepicker").datepicker({
				dateFormat: "dd.mm.yy",
				firstDay: 1,
				
				maxDate: 0,
				monthNames: [ "Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember" ],
				dayNamesMin: [ "So", "Mo", "Di", "Mi", "Do", "Fr", "Sa" ],
				
				
				onSelect: function(dateText, inst) {
					var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
					
					var dateText3 = $.datepicker.formatDate("yy-mm-dd", date, inst.settings);
					
				
					$("#dateoutput").html(" --&gt; <a href=\""+  "/wetterstation-merkur&sdate=" +dateText3+ "\">Bitte klicken, falls nicht automatisch weitergeleitet wird.</a>");
					window.location.href = "/wetterstation-merkur?sdate="+dateText3;
					
					
					
				}
			});
		});
</script>

<?php
if (!isMobileDevice()) {
?>
	<!-- <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 5px;">
	<tr><td width="65%" valign="top">
	 <?php
    if ($sdate != date("Y-m-d")) {
?>
         <br>
         Historische Daten der Wetterstation am Merkur der Schwarzwaldgeier
         <br>
         <br>
         Zum Anzeigen einfach den entsprechenden Tag rechts im Kalender anklicken.<br>
         <br>
         <a target="_blank" href="/wetterstation-merkur/">Zur&uuml;ck zu den aktuellen Daten</a>
        <?php
    } else {
?>
        
       	<b>Telefon: 07221 - 277 577<p>
        
        Funkkanal: LPD 08-08  ***  433,250 (Tsq 88,5) <p>
        </b>
    <?php
    }
?>
    
    </td>
    <td width="35%" valign="middle" align="right">
    Das Wetterstationsteam:<p>
    Timm, Holly, Stefan, Ingo, Esther, Rolf.
    <br><a href="mailto:Wetterstation@Schwarzwaldgeier.de">Wetterstation@Schwarzwaldgeier.de<a>
    </td>
    </tr>
    </table> -->
<?php
}
?>

<br>

 <?php
if ($sdate != date("Y-m-d")) {
?>
 <h3>
 Historische Daten f&uuml;r den <?php
    echo substr($sdate, 8, 2) . "." . substr($sdate, 5, 2) . "." . substr($sdate, 0, 4);
?>

 </h3>
 <?php
}
?>


<div style="width: 100%;" id="letztemessungen">
<tr><td width="100%" valign="top" align="<?php
if (isMobileDevice())
    echo 'left';
else
    echo 'center';





?>">






<?php
if ($sdate == date("Y-m-d")) {

    if (!$vereist) {

        last_records($connection, $rec_count);

    }
	echo "<br />";
    if (isMobileDevice())
        echo '<div class="responsive">';
?>
	
    
     <table style="width: 60%; border: 1px #0000ff solid; line-height: 10px; ">
    		<h3>
    			Wetterstation Merkur: Durchschnittswerte
    		</h3>

    		<tr>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="16%" valign="top">Windrichtung</td>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">Geschw.</td>
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">B&ouml;e</td>
<!--    		<td style="color: #ffffff; background-color: #0000ff;" align="right" width="50" valign="top">Temp.</td>
-->
    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="14%" valign="top">Luftdruck</td>

    		<td style="color: #ffffff; background-color: #0000ff; text-align:right;" align="right" width="26%" valign="top">Mess-Zeit</td>
    		
            </tr>


<?php
    if ($vereist) {
?>


            <tr style="background-color: rgb(204, 204, 204);">
            <td style="color: rgb(102, 0, 0); background-color: rgb(255, 153, 153); text-align: right;" valign="top">Huuiiii, </td>
            <td style="text-align: right;" valign="top">das ist</td>
            <td style="text-align: right;" valign="top">ja saukalt wieder heute</td>

            <td style="text-align: right;" valign="top">letzte 20 Minuten</td>
            <td style="color: rgb(204, 255, 204); background-color: rgb(0, 102, 0); text-align: right;" align="right" valign="top">Mist!</td>
            </tr>


            <tr style="background-color: rgb(204, 204, 204);">
            <td style="color: rgb(102, 0, 0); background-color: rgb(255, 153, 153); text-align: right;" valign="top">Mir</td>
            <td style="text-align: right;" valign="top">scheint, ich habe mir</td>
            <td style="text-align: right;" valign="top">doch wirklich alles abgefroren</td>
<!--
            <td align="right" valign="top">0&#x00b0;&nbsp;C</td>
            <td align="right" valign="top">0 hpa</td>
-->
            <td style="text-align: right;" valign="top">letzte 60 Minuten</td>
            <td style="color: rgb(204, 255, 204); background-color: rgb(0, 102, 0); text-align: right;" align="right" valign="top">kaalt!!</td>
            </tr>


            <tr style="background-color: rgb(204, 204, 204);">
            <td style="color: rgb(102, 0, 0); background-color: rgb(255, 153, 153); text-align: right;" valign="top">bbrrr...</td>
            <td style="text-align: right;" valign="top">eisig</td>
            <td style="text-align: right;" valign="top">brrr</td>

            <td style="text-align: right;" valign="top">letzte 120 Minuten</td>
            <td style="color: rgb(204, 255, 204); background-color: rgb(0, 102, 0); text-align: right;" align="right" valign="top">hilfe</td>
            </tr>


<?php
    } else {

        data_avg(20, $connection);
        data_avg(60, $connection);  
        data_avg(120, $connection);
    }



	echo "</table>";
    
	if (isMobileDevice())
        echo '</div>';


echo "<br />";


}
?>


<img width="540" alt="Windgeschwindigkeits-Messung Wetterstation Merkur" src="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_w-speed.php?sdate=<?php
echo $sdate;
?><?php
echo $vereist;
?>">
<br>
<br>
<!--
<img width="540" alt="Windrichtungs-Messung Wetterstation Merkur" style="background-image: url('http://i.imgur.com/prajpXN.png'); opacity:0.5; float: left;" src="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_w-direction.php?sdate=<?php
echo $sdate;
?><?php
echo $vereist;
?>" />
-->
<div style="position: relative; top: 0; left: 0; z-index: -1;">
  <img src="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_w-direction.php?sdate=<?php
echo $sdate;
?><?php
echo $vereist;
?>" style="position: relative; top: 0; left: 0;"/>
 <!-- <img src="http://i.imgur.com/prajpXN.png.jpg" style="position: absolute; top: 49px; left: 30px; opacity: 0.3;" width="500" height="329" /> -->
</div>



<!--
<br>
<br>
<img width="540" height="344" alt="Temperatur-Messung Wetterstation Merkur" src="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_w-temp.php?sdate=<?php
echo $sdate;
?>">
-->
<br>
<br>
<img width="540" height="344" alt="Luftdruck-Messung Wetterstation Merkur" src="https://www.schwarzwaldgeier.de/_extphp/wetterstation/typo_w-press.php?sdate=<?php
echo $sdate;
?>">
<br>
<br>

</div>










<br>




<div id="historisch">
<h2>Historische Daten</h2>
<p>Historische Daten: <input id="datepicker" type="text" value="Klicken und Datum ausw&auml;hlen"> </p>
    
<p id="dateoutput"></p>
</div>
