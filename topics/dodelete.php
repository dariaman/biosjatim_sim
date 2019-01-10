<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../koneksi.php";

	session_start();
	$catid = $_REQUEST["idcat"];
	$topicid = $_REQUEST["idtop"];
	$shareuser = $_REQUEST["shareuser"];
	$user = $_SESSION["Username"];
	$today = date("Y-m-d H:i:s");
		mysql_query("DELETE FROM sharedcattop WHERE shared = '".$shareuser."' AND catid = '".$catid."' AND topid = '".$topicid."'");

	$querytop = mysql_query("SELECT * FROM topics WHERE topic_id = '".$topicid."' ");
	$rowtop = mysql_fetch_array($querytop);
	$topicdesc = $rowtop['topic_subject'];
	$notifdesc = $topicdesc.' by '.$user;
	$icon = '<i class="fa fa-times media-object bg-red"></i>';
mysql_query("INSERT INTO notification (namanotif,description,icon,noticdate,notifuser,notiffrom)
	VALUES ('Topics Remove','".$notifdesc."','".$icon."','".$today."','".$shareuser."','".$user."')");

	//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
	header("location:../topics?idcat=".$catid);
?>