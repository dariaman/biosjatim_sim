<?php
include "../koneksi.php";
$id = isset($_POST['id']) ? $_POST['id'] : '';
$date = date('Y-m-d H:i:s');
$session=  session_id();

$q1="DELETE FROM ajkuserlogin WHERE user_id = ".$id;
$result1=mysql_query($q1);

$q2="UPDATE ajkuserlog SET logout_at = '".$date."' WHERE user_id = ".$id;
$result2=mysql_query($q2);
