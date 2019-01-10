<?php

$userID 		= $_POST['user-id'];
$dateStart 		= $_POST['dateStart'];
$dateEnd 		= $_POST['dateEnd'];
$groupID		= $_POST['groupID'];
$usersType		= $_POST['type'];
$range  		= $_POST['range']; // daily || monthly || yearly
$privilege  	= $_POST['privilege']; // daily || monthly || yearly
$cabang 		= $_POST['regional']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion	= $_POST['deviceVersion'];

$year = date("Y", strtotime($dateEnd));
$monthly = date("Y-m", strtotime($dateEnd));
$startMonth = $monthly."-01";
$startDate = $year."-01-01";


$cancel =false;
if($userID=="" || $dateStart =="" || $dateEnd == "" || $range =="" )
{
	$cancel=true;
}

if($cancel)
{
	$json['err_no']  = '1';
	$json['err_msg'] = 'Error occured. Please try again.';

	echo json_encode($json);
	die();
}

if($deviceVersion <= 1.10)
{
	$json['err_no']  = '2';
	$json['err_msg'] = 'You need to upgrade your device!!!';

	echo json_encode($json);
	die();
}

$queryUserID 	= "SELECT id,cabang FROM user_mobile WHERE md5(id)='$userID'";
$resultUserID 	= query_db($queryUserID);
$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
$userCabang 	= $encodedUserID['cabang'];
$encodedUserID 	= $encodedUserID['id'];

//Insert to spak table
$query_last_spak 	= "SELECT MAX(id) as max FROM fu_ajk_spak";
$result_last_spak 	= query_db($query_last_spak);
if(mysql_num_rows($result_last_spak)>0)
{
	$row 			=  mysql_fetch_assoc($result_last_spak);
	$next_spak_id 	=$row['max']+1;
}else{
	$next_spak_id	=1;
}

if($range == 'yearly'){
	
	require_once ('yearly/yearly.php');
		
}else {
	$json['err_no'] = '1';
	$json['err_msg'] = 'Error occured. Please try again.';
	echo json_encode($json);
}
?>