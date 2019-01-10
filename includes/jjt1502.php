<?php
/*
   ----------------------------------------------------------------------------------
   Copyright (C) JANUARI 2016 APLIKASI AJK PENSIUN
   Original Author Of File : Rahmad
   E-mail :kepodank@gmail.com
   YM(Yahoo Messenger) : penting_kaga
   ----------------------------------------------------------------------------------
*/
//define("BASE_URL", "/clibios/");
//define("hostname", "localhost");
//define("username", "biosdev");
//define("password", "biosdev19122016");
//define("dbname", "atsbios_dev");
//define("Utheme", "themeUser");
//define("Atheme", "themeAdmin");

define("BASE_URL", "/clibios/");
define("hostname", "localhost:3361");
define("username", "jatimsql");
define("password", 'ved+-18bios');
//define("dbname", "atsbios_dev");
define("dbname", "biosjatim_sim");
//define("dbname", "demo_ats");
define("Utheme", "themeUser");
define("Atheme", "themeAdmin");
//$pdo = new PDO("mysql:host=hostname;dbname=dbname", username, password);
$conn = @mysql_connect( hostname, username, password ) or die( mysql_error( ) );
mysql_select_db( dbname, $conn ) or die( mysql_error( $conn ) );
?>
