<?php
//generic php function to send GCM push notification
function sendPushNotificationToGCM($registatoin_ids, $message) {
	//Google cloud messaging GCM-API url
	$url = 'https://fcm.googleapis.com/fcm/send';
	$fields = array(
	//'to' => "/topics/global",
	'to' => $registatoin_ids,
	//'data' => array("message" =>$message,"datamsg" =>"apk"),
	'data' =>
	array
	(
	'post_title' => 'SPK TELAH DIAPPROVE OLEH SUPERVISOR',
	'post_msg' => $message,
	"datamsg" =>"SPK",
	"datastatus" => "Proses",
	"dataformid" => "5",
	"dataidspk" => "M17210001"
	)
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
//echo sendPushNotificationToGCM("dripOeEtyM0:APA91bGMhK2XEwTvSgeo6xmx1ftPYuAP90qwC4witmUrRXRijMnPH_YqUGB15KZqOnX-SVk0egpJU-TBRo9gqqxwQHwFXm7zUsx9UHwNDo0Ft7yeUzGaPVOd5QEpehRg7BtWhK-i5i_x", "This Agreement covers both Products you choose to distribute for free and Products for which you charge a fee. In order to charge a fee for your Products, you must have a valid Payment Account under a separate agreement with a Payment Processor. If you have an existing Payment Account with a Payment Processor before signing up for the Store, then the terms of that agreement will apply except in the event of a conflict with this Agreement (in which case the terms of this Agreement shall apply")
echo sendPushNotificationToGCM("d8X3v-XSkHI:APA91bHm5Kei7bSTrvZrb6C7qW691SZa1weya4uG2F7I-B59GxH-AAGbMtPOWfMg-YCZBkV8CB2KoNRbeYDUe9ZtRnIZqkG8FNuJlW2-bCM3bH7tnJSxM78jVlgUv3Ho1MXbihJWCWU0", "This Agreement covers both Products you choose to distribute for free and Products for which you charge a fee. In order to charge a fee for your Products, you must have a valid Payment Account under a separate agreement with a Payment Processor. If you have an existing Payment Account with a Payment Processor before signing up for the Store, then the terms of that agreement will apply except in the event of a conflict with this Agreement (in which case the terms of this Agreement shall apply")
?>

