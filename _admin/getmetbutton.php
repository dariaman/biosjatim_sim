<?php
include_once('../includes/jjt1502.php');
include_once('../includes/functions.php');
$qr = intval($_GET['qr']);
//echo('SELECT * FROM ajkpolis WHERE id ="'.$qr.'"');
$metButton = mysql_fetch_array(mysql_query('SELECT * FROM ajkpolis WHERE id ="'.$qr.'"'));
//echo $metButton['general'];
if ($metButton['general']=="T") {
echo '<input type="hidden" name="exs" value="UploadXls2"><button type="hidden" name="submit" value="Deklarasi AJK">Deklarasi AJK</button>';
}else{
echo '<input type="hidden" name="exs" value="UploadXls2General"><button type="hidden" name="submit" value="Deklarasi AJK + General">Deklarasi AJK + General</button>';
}

?>