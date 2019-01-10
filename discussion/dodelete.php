<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../koneksi.php";

	session_start();
	$catid = $_REQUEST["idcat"];
	$shareuser = $_REQUEST["shareuser"];
	$user = $_SESSION["Username"];
	$today = date("Y-m-d H:i:s");
		mysql_query("DELETE FROM sharedcat WHERE shared = '".$shareuser."' AND catid = '".$catid."'");

	$topicdesc = $rowtop['topic_subject'];
	$notifdesc = $topicdesc.' by '.$user;
	$icon = '<i class="fa fa-times media-object bg-red"></i>';

	$querycat = mysql_query("SELECT * FROM categories WHERE cat_id = '".$catid."'");
	$rowcat = mysql_fetch_array($querycat);
	$catname = $rowcat['cat_name'];
	$catname = $catname. ' by '.$user;

	mysql_query("INSERT INTO notification (namanotif,description,icon,noticdate,notifuser,notiffrom)
	VALUES ('Categories Remove','".$catname."','".$icon."','".$today."','".$shareuser."','".$user."')");

	//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
	header("location:../discussion");
?>