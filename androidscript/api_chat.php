<?php
include_once "config.php";

connect_db();

if(isset($_POST['action']))
{
	$action = $_POST['action'];
}else if(isset($_GET['action']))
{
	$action = $_GET['action'];
}

$qmversion = mysql_query("SELECT * FROM mobile_app_version WHERE `status` = 'Available' AND type = 'Chat' AND api_version = '1'");
$rversion = mysql_fetch_array($qmversion);
$versionavailable = $rversion['version_name'];
$versioncodeavailable = $rversion['version_code'];
$api_version = $rversion['api_version'];

$pathsummary = 'summary'.$api_version;
if($action == 'login')
{
	$username = isset($_POST['username'])? $_POST['username']: $_GET['username'];
	$password = isset($_POST['password']) ? $_POST['password'] : $_GET['password'];
	$password = md5($password);
	$idmobile = $_POST['regid'];
	$deviceVersion = isset($_POST['deviceVersion']) ? $_POST['deviceVersion'] : $_GET['deviceVersion'];
	$query	="SELECT * FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND (idbroker is null AND idclient is null)
	AND tipe in ('Kadiv','Direksi','Bank','Admin')
	UNION
	SELECT * FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND idclient is not null
	AND tipe in ('Kadiv','Direksi','Bank','Admin')";
	$result = query_db($query);


	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';
		echo json_encode($json);
		die();
	}

	if($result){
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$user_info['nip']= $row['username'];
			$user_info['id'] = $row['id'];
			$user_info['user_id'] = $row['id'];
			$user_info['regional'] = $row['firstname'];
			$user_info['nama'] = $row['firstname'];
			$user_info['namalengkap'] = $row['firstname'];
			$user_info['type'] = $row['tipe'];
			$user_info['idbank'] = $row['idclient'];
			$user_info['idmitra'] = $row['idclient'];
			$user_info['userphoto'] = $row['photo'];
			$user_info['useremail'] = $row['email'];
		    $user_info['aktifuser'] = $row['aktif'];

			$qklient = mysql_query("SELECT * FROM ajkclient WHERE id= '".$row['idclient']."'");
			$rklient = mysql_fetch_array($qklient);

			$querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id ='".$row['idbroker']."'");
			$rowbroker = mysql_fetch_array($querybroker);

			$user_info['namaclient'] = is_null($rklient['name']) ? 'BIOS' : $rklient['name'];
			$user_info['logoclient'] = is_null($rklient['logo']) ? '' : $rklient['logo'];
			$user_info['namabroker'] = is_null($rowbroker['name']) ? 'BIOS' : $rowbroker['name'];
			$user_info['logobroker'] = is_null($rowbroker['logo']) ? '' : $rowbroker['logo'];

			//$query_survey = mysql_fetch_array(mysql_query());
			$user_info['tipesurvey'] = $rowbroker['logo'];

			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';
			$json['user_info'] = $user_info;
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Username atau password salah';
		}
	}
	echo json_encode($json);
}
else if ($action == 'list-chat-admin')
{
	$username = $_POST['username'];

    $query = "select id, `from`, `to`, message, sent from (select * from chatuser where `to` = '$username' ORDER BY sent desc)
                as temps GROUP BY `from` ORDER BY sent DESC";
    $result = mysql_query($query);

    if ($result)
    {
        if (mysql_num_rows($result) > 0)
        {
            $listobject=array();
            while($row = mysql_fetch_assoc($result)){
                array_push($listobject,$row);
            }

            $json['err_no'] = '0';
            $json['err_msg'] = 'Success';
            $json['total_data'] = count($listobject);
            $json['data']=$listobject;
        }
        else
        {
            $json['err_no'] = '1';
            $json['err_msg'] = 'Failed! Wrong email or password';
        }
    }
    echo json_encode($json);
}
else if ($action == 'list-contact')
{
    $username = $_POST['username'];

    $query = "select id, username, IFNULL(lastname,'-') as lastname from useraccess where username not in ('$username')";
    $result = mysql_query($query);

    if ($result)
    {
        if (mysql_num_rows($result) > 0)
        {
            $listobject=array();
            while($row = mysql_fetch_assoc($result)){
                array_push($listobject,$row);
            }

            $json['err_no'] = '0';
            $json['err_msg'] = 'Success';
            $json['total_data'] = count($listobject);
            $json['data']=$listobject;
        }
        else
        {
            $json['err_no'] = '1';
            $json['err_msg'] = 'Failed! Wrong email or password';
        }
    }
    echo json_encode($json);
}
else if ($action == 'fetch-chat')
{
    $username = $_POST['username'];
    $namauser = $_POST['namauser'];

    $query = "select * from chatuser where `to` in ('$username','$namauser') AND `from` in ('$username','$namauser') ORDER BY sent asc";

    $result = query_db($query);

    if ($result)
    {
        if (mysql_num_rows($result) > 0)
        {
            $listobject=array();
            while($row = mysql_fetch_assoc($result)){
                array_push($listobject,$row);
            }

            $json['err_no'] = '0';
            $json['err_msg'] = 'Success';
            $json['total_data'] = count($listobject);
            $json['data']=$listobject;
        }
        else
        {
            $json['err_no'] = '1';
            $json['err_msg'] = 'Failed! Wrong email or password';
        }
    }
    echo json_encode($json);
}
else if ($action == 'send-message')
{
    $fromUser   = isset($_POST['fromUser'])? $_POST['fromUser']: $_GET['fromUser'];
    $toUser    = isset($_POST['toUser'])? $_POST['toUser']: $_GET['toUser'];
    $pesan      = isset($_POST['pesan'])? $_POST['pesan']: $_GET['pesan'];
    $waktu      = isset($_POST['waktu'])? $_POST['waktu']: $_GET['waktu'];

    $query = "INSERT INTO chatuser (`from`, `to`, message, sent) VALUES
	            ('$fromUser','$toUser','$pesan',NOW())";

    $result = query_db($query);

    if($result)
    {
        $json['err_no'] = '0';
        $json['err_msg'] = 'Success';
    }

    echo json_encode($json);
}
else if ($action == 'send-notif')
{
    $fromuser = isset($_POST['fromuser'])? $_POST['fromuser']: $_GET['fromuser'];
    $touser = isset($_POST['touser'])? $_POST['touser']: $_GET['touser'];
    $pesan = isset($_POST['pesan'])? $_POST['pesan']: $_GET['pesan'];
    $time = isset($_POST['waktu'])? $_POST['waktu']: $_GET['waktu'];

    $query = "select * from user_mobile_token umt
            join useraccess uac on uac.id=umt.UserID
            where uac.username='".$touser."'";
    $result = query_db($query);

    if ($result)
    {
        $rtoken = mysql_fetch_assoc($result);
        $notoken = $rtoken['UserToken'];

        $data = [
                  "post_title" => $fromuser,
                  "post_msg" => $pesan,
                  "time" => date('Y-m-d H:i:s')
                ];

        _sendnotif($notoken,$data);
    }
}
else if($action=="gpstrack"){
    $user_id = isset($_POST['user_id'])? $_POST['user_id']: $_GET['user_id'];
    $imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
    $usertoken = isset($_POST['usertoken'])? $_POST['usertoken']: $_GET['usertoken'];
    $devicename = isset($_POST['devicename'])? $_POST['devicename']: $_GET['devicename'];
    $today = date('Y-m-d H:i:s');
    $hariini = date('Y-m-d');

    if($usertoken != ""){
        $query = "SELECT * FROM user_mobile_token WHERE UserID = '$user_id'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) > 0)
        {
            $query = "UPDATE user_mobile_token SET UserImei = '$imei', UserToken = '$usertoken', deviceName = '$devicename', updatedate = '$today' WHERE UserID = '$user_id'";
            $result = mysql_query($query);
        }
        else
        {
            $query = "INSERT INTO user_mobile_token(UserID, UserToken, UserImei, deviceName, inputdate) VALUES('$user_id','$usertoken','$imei','$devicename',NOW())";
            $result = mysql_query($query);
        }
    }

}
else
{
	$json['err_no'] = '1';
	$json['err_msg'] = 'No action found.';

	echo json_encode($json);
}

function _sendnotif($registatoin_ids, $data)
{
    // google cloud messaging GCM_API url
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
                    'to' => $registatoin_ids,
                    'data' => $data
                    );
    // Google Cloud Messaging GCM API key
    define("GOOGLE_API_KEY","AAAAYkMMcpU:APA91bH9RAM0_yac0Oc152TkCi1_cdQrGBN6JzWp5Ki0Ro4u6NOP2nXO1CN3yvjtu1_3-5D2rD-SpEG1R1QcY2E7QBVMCbCowE3jD0ppA5XCmUXutnX0yPDp0ptqsshq-ciETi-TUYVg");

    $headers = array(
                        'Authorization: key=' . GOOGLE_API_KEY,
                        'Content-Type: application/json'
                        );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if ($result === FALSE)
    {
        die('Curl failed : ' . curl_error($ch));
    }

    curl_close($ch);

    return $result;

}
?>
