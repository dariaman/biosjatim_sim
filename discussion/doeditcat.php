<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
    include "../param.php";
    // include "../sendmail/sendmail.php";

    session_start();
    $idcat = $_REQUEST["idcat"];
    $namakategori = $_REQUEST["namakategori"];
    $description = $_REQUEST["description"];
    $typecat = $_REQUEST["typecat"];
    $userakses = $_REQUEST["userakses"];
    $user = $_SESSION["User"];
    $today = date("Y-m-d H:i:s");



        mysql_query("UPDATE categories SET  cat_name = '".$namakategori."',
		cat_description ='".$description."',
		cat_type = '".$typecat."',
		cat_group = '".$userakses."',
		cat_userupdate = '".$user."',
		cat_updatedate = '".$today."'
		WHERE cat_id = '".$idcat."'");

    //kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
    header("location:../discussion");
