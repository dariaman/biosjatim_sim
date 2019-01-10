<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
session_start();
include "koneksi.php";
include "includes/functions.php"; //added by chrismanuel at 20180312
setUserLog($_SESSION["uid"], 'logout'); //added by chrismanuel at 20180312
$user = $_SESSION["User"];
$path="https://".$_SERVER['SERVER_NAME']."/";
session_destroy();
echo "<script>window.location='".$path."/login?pesan=Sesi anda telah berakhir.'</script>";
// header('location:login');
