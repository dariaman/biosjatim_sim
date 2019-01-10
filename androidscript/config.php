<?php
define('DB_NAME','biosjatim');
define('DB_USER', 'jatimsql');
define('DB_PASSWORD', 'ved+-18bios');
define('DB_HOST', 'localhost:3362');

//define('BASE_URL', 'https:/f/mbeta.adonai.co.id/biosdemo/androidscript'); // For genymotion

$conn;
function connect_db(){
	$conn =mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if (!$conn) {
	    die('Could not connect: ' . mysql_error());
	}
	else{

		$db_found = mysql_select_db(DB_NAME, $conn);
	}
}
function query_db($query){

	$result = mysql_query($query);
	if(! $result )
	{
	  die('Could not get data: ' . mysql_error());
	}

	return $result;
}
function close_db($conn){
	mysql_close($conn);
}

/*
//Cara Pake
connect_db();
$return = query_db("SELECT * FROM user_mobile");


//For List of data
while($row = mysql_fetch_assoc($return)){
	echo $row['type']." ".$row['level']." ".$row['status']." ".$row['nama']." ".$row['alamat']." ";
	echo "<br><br>";
}

//For Single data
if(mysql_num_rows($result)>0)
{
	$row = mysql_fetch_assoc($result);
	$user_info['email']= $row['email'];
	$user_info['nama'] = $row['nama'];
	$user_info['type'] = $row['type'];

}

*/
function sendnotif($registatoin_ids, $data) {
	//Google cloud messaging GCM-API url
	$url = 'https://fcm.googleapis.com/fcm/send';
	$fields = array(
		//'to' => "/topics/global",
		'to' => $registatoin_ids,
		'data' => $data
	);

	// Google Cloud Messaging GCM API Key
	define("GOOGLE_API_KEY", "AIzaSyCaRuBxKGCnya7dRTiuPph7q0sCv2Nc9sY");
	$headers = array(
	'Authorization: key=' . GOOGLE_API_KEY,
	'Content-Type: application/json'
);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
	}
	curl_close($ch);
	return $result;
}
?>