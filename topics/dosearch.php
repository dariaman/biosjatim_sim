<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	$search = $_REQUEST['search'];
	$category = $_REQUEST['idcat'];
	header("location:../topics/?idcat=".$_REQUEST['idcat']."&search=".$search);
?>