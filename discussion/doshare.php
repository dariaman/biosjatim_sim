<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../koneksi.php";

	session_start();
	$userid = $_REQUEST["userid"];
	$catid = $_REQUEST["idcat"];
	$user = $_SESSION["Username"];
	$today = date("Y-m-d H:i:s");

		mysql_query("INSERT INTO sharedcat (shared,catid, usersharedby, inputdate)
		VALUES ('".$userid."','".$catid."','".$user."','".$today."')");

	//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
	header("location:../discussion");
?>