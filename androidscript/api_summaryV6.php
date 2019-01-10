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

$qmversion = mysql_query("SELECT * FROM mobile_app_version WHERE `status` = 'Available' AND type = 'Management' AND api_version = '6'");
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
	AND tipe in ('Kadiv','Direksi','Broker','Admin')
	UNION
	SELECT * FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND idclient is not null
	AND tipe in ('Kadiv','Direksi','Broker','Admin')";
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
else if($action =='search-debitur-spak')
{
	$userType = $_POST['user-type'];
	$idSPK = $_POST['id-spk'];
	//$idbank = $_POST['idbank'];
	$deviceVersion = $_POST['deviceVersion'];
	if($userType=="Dokter")
	{
		// $query = "SELECT F.* ,S.spak, S.status AS status FROM fu_ajk_spak_form_temp AS F, fu_ajk_spak AS S
							// WHERE S.spak='$idSPK' AND F.idspk=S.id AND S.id_cost = '$idbank' AND F.dokter_pemeriksa IS NULL";
		$query = "SELECT F.* ,S.spak, S.status AS status FROM fu_ajk_spak_form_temp AS F, fu_ajk_spak AS S
							WHERE S.spak='$idSPK' AND F.idspk=S.id AND F.dokter_pemeriksa IS NULL";
		//TODO  QUERY HERE
		$result = query_db($query);

		if($deviceVersion <= 1.10)
		{
			$json['err_no']  = '2';
			$json['err_msg'] = 'You need to upgrade your device!!!';
			echo json_encode($json);
			die();
		}

		if($result)
		{
			//if found
			//RESULT DUMMY DATA
			$jsonResponse['err_no'] = '0';
			$jsonResponse['err_msg'] = 'Success';
			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['data']=$row;
			}
			else
			{
				//salah password atau email
				$json['err_no'] = '1';
				$json['err_msg'] = 'Data Debitur tidak tersedia.';
			}
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'User not found.';

		}
		echo json_encode($json);
	}
	else if($userType=="Marketing")
	{
		//TODO QUERY TO GET VALUE
		$query = "SELECT * FROM fu_ajk_spak_form_temp WHERE idspk='$idSPK'";
		$result = query_db($query);
		if($result)
		{
			//if found
			//RESULT DUMMY DATA
			$jsonResponse['err_no'] = '0';
			$jsonResponse['err_msg'] = 'Success';
			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['data']=$row;
			}
			else
			{
				//salah password atau email
				$json['err_no'] = '1';
				$json['err_msg'] = 'Failed! Wrong email or password';
			}
			echo json_encode($json);
		}
	}
}
else if($action=='search-debitur')
{
	// $userType = $_POST['user-type'];
	// $userType = $_GET['user-type'];
	$userType = $_POST['user-type'];
	$idSPK = $_POST['id-spk'];
	$deviceVersion = $_POST['deviceVersion'];
	// $idSPK = $_GET['id-spk'];
	///$userID = $_POST['user-id']; DISABLE FOR A MOMENT
	if($userType=="Dokter")
	{
		$query = "SELECT F.* ,S.spak,S.status as status FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S WHERE F.idspk='$idSPK'
		AND F.idspk=S.id";//AND ID DOKTER == ID LOGIN DOKTER
		//TODO  QUERY HERE
		$result = query_db($query);
		if($deviceVersion <= 1.10)
		{
			$json['err_no']  = '2';
			$json['err_msg'] = 'You need to upgrade your device!!!';
			echo json_encode($json);
			die();
		}

		if($result)
		{
			//if found
			//RESULT DUMMY DATA
			$jsonResponse['err_no'] = '0';
			$jsonResponse['err_msg'] = 'Success';
			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['data']=$row;
			}
			else
			{
				//salah password atau email
				$json['err_no'] = '1';
				$json['err_msg'] = 'Failed! Wrong email or password';
			}
			echo json_encode($json);
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'User not found.';

		}
	}
	else if($userType=="Marketing")
	{
		//TODO QUERY TO GET VALUE
		$status = $_POST['status'];
		if($status=="Aktif")
		{
			$query = "SELECT F.* ,S.ext_premi,S.ket_ext, S.spak,S.status as status FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S
			WHERE F.idspk='$idSPK'  AND F.idspk=S.id";//AND ID DOKTER == ID LOGIN DOKTER
		}
		else{
			$query = "SELECT F.* ,S.spak,S.status as status FROM fu_ajk_spak_form_temp AS F, fu_ajk_spak AS S
			WHERE F.idspk='$idSPK'  AND F.idspk=S.id";//AND ID DOKTER == ID LOGIN DOKTER
		}
		// $query = "SELECT * FROM fu_ajk_spak_form_temp WHERE idspk='$idSPK'";
		$result = query_db($query);
		if($result)
		{
			//if found
			//RESULT DUMMY DATA
			$jsonResponse['err_no'] = '0';
			$jsonResponse['err_msg'] = 'Success';
			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);
				if($status=="Aktif"){
					$extra_premi = $row['ext_premi'];
					$premi = $row['x_premi'];
					$totalPremi = $premi + ($premi*$extra_premi/100);
					$row['total_premi'] = round($totalPremi)."";
				}
				/*
				* Generate Notes when nearby round up month
				*/
				$today = date('m');
				$round_up_month=6;// you can change this for testing, return this to 6 after change it
				if($today==$round_up_month-1){
					$today_day = date('j');
					$lastday = 31;
					$diff_day = $lastday - $today_day;
					$met_Date = datediff($row['dob'], date('Y-m-d'));
					$met_Date_ = explode(",",$met_Date);
					if ($met_Date_[1] >= 6 ) {    $umur = $met_Date_[0] + 1;    }else{    $umur = $met_Date_[0];    }
					$row['catatan_penting'] = "* Mohon mengajukan deklarasi, kurang dari ".$diff_day." hari lagi sebelum usia akan bertambah menjadi ".$umur." tahun.";
				}else{
					$row['catatan_penting'] = null;
				}
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['data']=$row;
			}
			else
			{
				//salah password atau email
				$json['err_no'] = '1';
				$json['err_msg'] = 'Failed! Wrong email or password';
			}
			echo json_encode($json);
		}
	}
}
//modify satrya 160620
else if($action=='search-produk')
{
	// $userType = $_POST['user-type'];
	// $userType = $_GET['user-type'];
	$userType = $_POST['user-type'];
	$typeprod = $_POST['type-prod'];
	if($typeprod =="SPK"){
		$id = '12';
	}else{
		$id = '11';
	}
	$deviceVersion = $_POST['deviceVersion'];
	// $idSPK = $_GET['id-spk'];
	///$userID = $_POST['user-id']; DISABLE FOR A MOMENT

	if($userType=="Marketing")
	{
		$query = "SELECT * FROM fu_ajk_polis WHERE id = '".$id."'";
		$result = query_db($query);
		if($result)
		{
			if(mysql_num_rows($result)>0)
			{
				$jsonResponse['err_no'] = '0';
				$jsonResponse['err_msg'] = 'Success';
				if(mysql_num_rows($result)>0)
				{
					$row = mysql_fetch_assoc($result);
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';
					$json['data']=$row;
				}
				else
				{
					//salah password atau email
					$json['err_no'] = '1';
					$json['err_msg'] = 'Failed! Wrong email or password';
				}
				echo json_encode($json);

			}
			else
			{
				//salah password atau email
				$json['err_no'] = '0';
				$json['err_msg'] = 'Data debitur tidak tersedia.';
			}

		}
		echo json_encode($json);
	}
}
else if($action=='list-debitur')
{

	$userType = $_POST['user-type'];
	// $userType = $_GET['user-type'];
	// $idSPK = $_GET['id-spk'];
	$userID = $_POST['user-id']; //
	$deviceVersion = $_POST['deviceVersion'];
	/*
	* Generate first day of last month and last day of this month in PHP 5.2.10
	*/
	$date = new DateTime(date('Y-m-d'));
	$modLastDay= '-'.(date('j')).' day';
	$lastday = new DateTime(date('Y-m-d', strtotime('next month')));
	$lastday->modify($modLastDay);
	$currentDate = $lastday->format('Y-m-d');
	$modFirstDay ='-'.(date('j')-1).' day -1 month';
	$date->modify($modFirstDay);
	$past2Month = $date->format('Y-m-d');

	if($deviceVersion <= 1.10)
	{
		$json['err_no']  = '2';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}


	/*
	* Generate first day of last month and last day of this month in PHP 5.3 above
	*
	*
	* $lastday=new DateTime('last day of this month');
	* $date = new DateTime(date('Y-m').'-'.$lastday->format('d'));
	* $currentDate = $date->format('Y-m-d');
	* $date->modify('-2 month');
	* $past2Month = $date->format('Y-m-d');
	*/
	// $userID = $_GET['user-id']; //DISABLE FOR A MOMENT
	if($userType=="Dokter")
	{
		//TO DO Fix This Query to Right one
		// SELECT  F.*, S.status as status FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S WHERE F.dokter_pemeriksa=1 AND F.idspk=S.id
		//$query = "SELECT  F.*, S.spak AS spak, S.status as status FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S WHERE F.dokter_pemeriksa=(SELECT id FROM user_mobile WHERE md5(id)='$userID') AND F.idspk=S.id  AND (F.input_date BETWEEN '$past2Month' AND '$currentDate') ORDER BY spak DESC";
		$query = "SELECT  F.*,
		S.spak AS spak, S.status as status
		FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S
		WHERE F.dokter_pemeriksa=(
			SELECT id FROM user_mobile WHERE md5(id)='$userID'
			) AND F.idspk=S.id ORDER BY spak DESC";
		// $query = "SELECT * FROM fu_ajk_spak_form WHERE dokter_pemeriksa='$userID'";
		$result = query_db($query);
		if($result)
		{
			if(mysql_num_rows($result)>0)
			{
				$listdebitur=array();
				while($row = mysql_fetch_assoc($result)){
					array_push($listdebitur,$row);
				}
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['total_data'] = count($listdebitur);
				$json['data']=$listdebitur;

			}
			else
			{
				//salah password atau email
				$json['total_data'] = 0;
				$json['err_no'] = '0';
				$json['err_msg'] = 'Data Debitur tidak tersedia.';
			}
		}else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Failed to retrieve data. Please try again.';
		}
		echo json_encode($json);

	}
	else if ($userType=="Marketing")
	{
		//TO DO Fix This Query to Right one
		// $query = "SELECT * FROM fu_ajk_spak_form_temp WHERE input_by =(SELECT id FROM user_mobile WHERE md5(id)='$userID')";
		//$query = "SELECT  F.*, S.spak AS spak, S.status as status FROM fu_ajk_spak_form_temp AS F, fu_ajk_spak AS S WHERE F.input_by=(SELECT id FROM user_mobile WHERE md5(id)='$userID') AND F.idspk=S.id AND (F.input_date BETWEEN '$past2Month' AND '$currentDate') ORDER BY spak DESC";
		$query = "SELECT  F.*, S.spak AS spak, S.status as status FROM fu_ajk_spak_form_temp AS F, fu_ajk_spak AS S WHERE F.input_by=(SELECT id FROM user_mobile WHERE md5(id)='$userID') AND F.idspk=S.id ORDER BY spak DESC";
		// $query = "SELECT * FROM fu_ajk_spak_form WHERE dokter_pemeriksa='$userID'";

		$result = query_db($query);

		if($result)
		{
			if(mysql_num_rows($result)>0)
			{
				// $listdebitur=array();
				$listdebitur=array();
				while($row = mysql_fetch_assoc($result)){
					array_push($listdebitur,$row);
				}
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['total_data'] = count($listdebitur);
				$json['data']=$listdebitur;

			}
			else
			{
				//salah password atau email
				$json['total_data'] = 0;
				$json['err_no'] = '0';
				$json['err_msg'] = 'Data debitur tidak tersedia.';
			}
		}else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Proses gagal, coba lagi.';
		}
		echo json_encode($json);


	}
}
else if($action=='list-cabang')
{

    $deviceVersion = $_POST['deviceVersion'];
    /*
       * Generate first day of last month and last day of this month in PHP 5.2.10
    */
    $date = new DateTime(date('Y-m-d'));
    $modLastDay= '-'.(date('j')).' day';
    $lastday = new DateTime(date('Y-m-d', strtotime('next month')));
    $lastday->modify($modLastDay);
    $currentDate = $lastday->format('Y-m-d');
    $modFirstDay ='-'.(date('j')-1).' day -1 month';
    $date->modify($modFirstDay);
    $past2Month = $date->format('Y-m-d');

    $page = $_POST['page'];

    $limit = 50;
    $calc = $limit * $page;
    $start = $calc - $limit;


    $query = "select * from ajkcabang where idclient = '1' and del is null order by name asc LIMIT $start,$limit";

    $result = query_db($query);

    if($result)
    {
        if(mysql_num_rows($result)>0)
        {
            // $listdebitur=array();
            $listdebitur=array();
            while($row = mysql_fetch_assoc($result)){
                array_push($listdebitur,$row);
            }
            $json['err_no'] = '0';
            $json['err_msg'] = 'Success';
            $json['total_data'] = count($listdebitur);
            $json['data']=$listdebitur;

        }
        else
        {
            //salah password atau email
            $json['total_data'] = 0;
            $json['err_no'] = '0';
            $json['err_msg'] = 'Data debitur tidak tersedia.';
        }
    }else
    {
        $json['err_no'] = '1';
        $json['err_msg'] = 'Proses gagal, coba lagi.';
    }
    echo json_encode($json);
}
else if($action == "check-debitur")
{

	require_once("input/check-debitur.php");

}
else if($action == "add-debitur")
{

	require_once("input/add-debitur.php");

}
else if ($action == "rating")
{
	$userID 				= $_POST['user-id'];
	$id_spk					= $_POST['id_spk']; 		// id user get from  user_mobile
	$idbank					= $_POST['idbank']; 		// id user get from  user_mobile
	$nama 					= $_POST['nama']; 			// nama sales
	$rating					= $_POST['rating']; 		// rating type tinyint
	$deviceVersion 	= $_POST['deviceVersion'];


	$cancel =false;
	if($userID=="" || $id_spk =="" || $nama=="" || $rating=="")
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

	$queryUserID 		= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
	$resultUserID 	= query_db($queryUserID);
	$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

	$userCabang 		= $encodedUserID['cabang'];
	$userType 			= $encodedUserID['type'];
	$encodedUserID 	= $encodedUserID['id'];

	//Insert to spak table
	$query_last_spak 		= "SELECT MAX(id) as max FROM fu_ajk_spak";
	$result_last_spak 	= query_db($query_last_spak);
	if(mysql_num_rows($result_last_spak)>0)
	{
		$row 			=  mysql_fetch_assoc($result_last_spak);
		$next_spak_id 	=$row['max']+1;
	}else{
		$next_spak_id	=1;
	}


	//insert to form tabl

	$queryCheck = "SELECT DISTINCT(nama) FROM rating WHERE input_by = $encodedUserID AND nama = '$nama'";
	$result = query_db($queryCheck);
	$getID 	=  mysql_fetch_assoc($result);

	if($getID == true){
			if ($rating == "SP" || $rating == "sp")
			{
				$querySP = "UPDATE rating SET sangat_puas = sangat_puas+1 WHERE input_by = $encodedUserID AND nama = '$nama' ";
				$result = query_db($querySP);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID AND nama = '$nama' ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "P" || $rating == "p")
			{
				$querySP = "UPDATE rating SET puas = puas+1 WHERE input_by = $encodedUserID AND nama = '$nama' ";
				$result = query_db($querySP);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID AND nama = '$nama' ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "CP" || $rating == "cp")
			{
				$querySP = "UPDATE rating SET cukup_puas = cukup_puas+1 WHERE input_by = $encodedUserID AND nama = '$nama' ";
				$result = query_db($querySP);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID AND nama = '$nama' ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "TP" || $rating == "tp")
			{
				$querySP = "UPDATE rating SET tidak_puas = tidak_puas+1 WHERE input_by = $encodedUserID AND nama = '$nama' ";
				$result = query_db($querySP);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID AND nama = '$nama' ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "B" || $rating == "b"){

				$querySP = "UPDATE rating SET buruk = buruk+1 WHERE input_by = $encodedUserID AND nama = '$nama' ";
				$result = query_db($querySP);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID AND nama = '$nama' ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}
			}

	} else{

			if ($rating == "SP" || $rating == "sp") // Sangat Puas
			{
				$queryUserID 	= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
				$resultUserID 	= query_db($queryUserID);
				$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

				$userCabang 	= $encodedUserID['cabang'];
				$userType 		= $encodedUserID['type'];
				$encodedUserID 	= $encodedUserID['id'];


				$id ="SELECT MAX(id) AS ID FROM rating LIMIT 1";
				$result = query_db($id);
				if(mysql_num_rows($result)>0)
				{
					$row 	=  mysql_fetch_assoc($result);
					$nextID =$row['ID']+1;
				}else{
					$nextID =1;
				}

				$query = "INSERT INTO rating";
				$query .="(idcost,idspk,nama,buruk,tidak_puas,cukup_puas,puas,sangat_puas,cabang,input_by,type,input_date) ";
				$query .="VALUES ($idbank,$id_spk,'$nama','','','','',1,'$userCabang','$encodedUserID','$userType', NOW() )" ;

			    $result = query_db($query);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "P" || $rating == "p") // Puas
			{

				$queryUserID 	= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
				$resultUserID 	= query_db($queryUserID);
				$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

				$userCabang 	= $encodedUserID['cabang'];
				$userType 		= $encodedUserID['type'];
				$encodedUserID 	= $encodedUserID['id'];

				$id ="SELECT MAX(id) AS ID FROM rating LIMIT 1";
				$result = query_db($id);
				if(mysql_num_rows($result)>0)
				{
					$row 	=  mysql_fetch_assoc($result);
					$nextID =$row['ID']+1;
				}else{
					$nextID =1;
				}

				$query = "INSERT INTO rating";
				$query .="(idcost,idspk,nama,buruk,tidak_puas,cukup_puas,puas,sangat_puas,cabang,input_by,type,input_date) ";
				$query .="VALUES ($idbank,$id_spk,'$nama','','','',1,'','$userCabang','$encodedUserID','$userType', NOW() )" ;

			    $result = query_db($query);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "CP" || $rating == "cp") // Cukup Puas
			{

				$queryUserID 	= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
				$resultUserID 	= query_db($queryUserID);
				$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

				$userCabang 	= $encodedUserID['cabang'];
				$userType 		= $encodedUserID['type'];
				$encodedUserID 	= $encodedUserID['id'];

				$id ="SELECT MAX(id) AS ID FROM rating LIMIT 1";
				$result = query_db($id);
				if(mysql_num_rows($result)>0)
				{
					$row 	=  mysql_fetch_assoc($result);
					$nextID =$row['ID']+1;
				}else{
					$nextID =1;
				}

				$query = "INSERT INTO rating";
				$query .="(idcost,idspk,nama,buruk,tidak_puas,cukup_puas,puas,sangat_puas,cabang,input_by,type,input_date) ";
				$query .="VALUES ($idbank,$id_spk,'$nama','','',1,'','','$userCabang','$encodedUserID','$userType', NOW() )" ;

			    $result = query_db($query);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "TP" || $rating == "tp") // Tidak Puas
			{
				$queryUserID 	= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
				$resultUserID 	= query_db($queryUserID);
				$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

				$userCabang 	= $encodedUserID['cabang'];
				$userType 		= $encodedUserID['type'];
				$encodedUserID 	= $encodedUserID['id'];

				$id ="SELECT MAX(id) AS ID FROM rating LIMIT 1";
				$result = query_db($id);
				if(mysql_num_rows($result)>0)
				{
					$row 	=  mysql_fetch_assoc($result);
					$nextID =$row['ID']+1;
				}else{
					$nextID =1;
				}

				$query = "INSERT INTO rating";
				$query .="(idcost,idspk,nama,buruk,tidak_puas,cukup_puas,puas,sangat_puas,cabang,input_by,type,input_date) ";
				$query .="VALUES ($idbank,$id_spk,'$nama','',1,'','','','$userCabang','$encodedUserID','$userType', NOW() )" ;

			    $result = query_db($query);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			} else if ($rating == "B" || $rating == "b")
			{
				$queryUserID 	= "SELECT id,cabang,type FROM user_mobile WHERE md5(id)='$userID'";
				$resultUserID 	= query_db($queryUserID);
				$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

				$userCabang 	= $encodedUserID['cabang'];
				$userType 		= $encodedUserID['type'];
				$encodedUserID 	= $encodedUserID['id'];

				$id ="SELECT MAX(id) AS ID FROM rating LIMIT 1";
				$result = query_db($id);
				if(mysql_num_rows($result)>0)
				{
					$row 	=  mysql_fetch_assoc($result);
					$nextID =$row['ID']+1;
				}else{
					$nextID =1;
				}

				$query = "INSERT INTO rating";
				$query .="(idcost,idspk,nama,buruk,tidak_puas,cukup_puas,puas,sangat_puas,cabang,input_by,type,input_date) ";
				$query .="VALUES ($idbank,$id_spk,'$nama',1,'','','','','$userCabang','$encodedUserID','$userType', NOW() )" ;

			    $result = query_db($query);

				$querySelect = "SELECT nama,input_by FROM rating WHERE input_by = $encodedUserID ORDER BY id DESC LIMIT 1";
				$result = query_db($querySelect);
				$getID 	=  mysql_fetch_assoc($result);
				$name 	= $getID['nama'];

				if($result)
				{
					$json['sales_name'] = $name ;
					$json['err_no'] = '0';
					$json['err_msg'] = 'Success';

					echo json_encode($json);
				}else{
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';

					echo json_encode($json);
				}

			}


		}

}
else if ($action == "search-list-group")
{
	$search					= $_POST['search'];
	$user_id					= $_POST['user_id'];


	$querySelect = "SELECT ajkpolis.produk as nmproduk FROM ajkpolis
									WHERE ajkpolis.idcost = '$search' and general = 'T' and del is null";

	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = array(
							"nmproduk" => is_null($getData['nmproduk']) ? 'All Report' : $getData['nmproduk']
							);
	}


	if($result)
	{

		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

}
else if ($action == "search-list-client")
{
	$user_id					= $_POST['user_id'];
	$brokerid					= isset($_POST['brokerid'])? $_POST['brokerid']: $_GET['brokerid'];
	$clientid					= isset($_POST['clientid'])? $_POST['clientid']: $_GET['clientid'];

	$quser = mysql_fetch_array(query_db("SELECT * FROM useraccess WHERE useraccess.id= '$user_id'"));

	if($quser['idclient']!="0"){
		$clientid = $quser['idclient'];
	}
	if($quser['idbroker']!="0"){
		$brokerid = $quser['idbroker'];
	}
	if($clientid !="" OR $clientid != null){
		$filterid = "AND id = '$clientid'";
	}
	if($brokerid !="" OR $brokerid !=null){

		$querySelect = "SELECT id,name FROM ajkclient WHERE idc ='$brokerid' $filterid";
	}else{
		$querySelect = "SELECT useraccess.idclient as id,
		ajkclient.name as name
		FROM useraccess
		LEFT JOIN ajkclient ON useraccess.idclient = ajkclient.id
		WHERE useraccess.id= '$user_id' and ajkclient.del is null";
	}
	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = $getData;
	}


	if($result)
	{
		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

}
else if ($action == "search-list-broker")
{
	$user_id					= $_POST['user_id'];

	$quser = mysql_fetch_array(query_db("SELECT useraccess.idbroker as idbroker
		FROM useraccess
		WHERE useraccess.id= '$user_id'"));

	$idbroker = $quser['idbroker'];
	if($idbroker!="0"){
		$fileterbroker =  "AND id='$idbroker'";
	}

	$querySelect = "SELECT id,name FROM ajkcobroker WHERE del IS NULL $fileterbroker";
	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = $getData;
	}


	if($result)
	{
		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

}

else if ($action == "search-list-regional")
{
	$nmgrup			= $_POST['nmgrup'];
	$groupID		= $_POST['groupID'];
	$dateStart		= $_POST['dateStart'];
	$dateEnd		= $_POST['dateEnd'];

	$deviceVersion 	= $_POST['deviceVersion'];

	$cancel =false;
	if($nmgrup =="" || $groupID=="" || $dateStart == "" || $dateEnd == "")
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

	$querySelect = "SELECT
					fu_ajk_peserta.regional
					FROM fu_ajk_dn
					LEFT JOIN fu_ajk_polis ON fu_ajk_polis.id = fu_ajk_dn.id_nopol
					LEFT JOIN fu_ajk_costumer ON fu_ajk_costumer.id = fu_ajk_dn.id_cost
					LEFT JOIN fu_ajk_peserta ON fu_ajk_peserta.id_polis = fu_ajk_polis.id AND fu_ajk_peserta.id_dn = fu_ajk_dn.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_regional.name = fu_ajk_dn.id_regional
					WHERE fu_ajk_peserta.del IS NULL
					AND fu_ajk_peserta.id_cost = '$groupID'
					AND fu_ajk_polis.grupproduk = '$nmgrup'
					AND fu_ajk_dn.tgl_createdn BETWEEN '$dateStart' AND '$dateEnd'
					GROUP BY fu_ajk_peserta.regional";

	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$data['data'][] = $getData;
	}


	if($result)
	{

		echo json_encode($data);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

}
else if ($action == "list-nmproduk-sebaran-usia")
{

	require_once($pathsummary."/sebaran-usia/list.php");

}
else if ($action == "summary-produk") // Pie Chart
{

	require_once($pathsummary."/produk/chart.php");

}
else if ($action == "summary-produk-detail")
{

	require_once($pathsummary."/produk/detail.php");

}
else if($action == "summary-produk-detail-regional")
{

	require_once($pathsummary."/produk/detail-regional.php");

}
else if ($action == "summary-produk-tahunan") // Pie Chart
{

	require_once($pathsummary."/produk/tahunan/chart.php");

}
else if ($action == "summary-produk-detail-tahunan")
{

	require_once($pathsummary."/produk/tahunan/detail.php");

}
else if($action == "summary-produk-detail-regional-tahunan")
{

	require_once($pathsummary."/produk/tahunan/detail-regional.php");

}
else if ($action == "summary-klaim") // Pie Chart
{

	require_once($pathsummary."/klaim/chart.php");

}
else if ($action == "summary-klaim-detail")
{

	require_once($pathsummary."/klaim/detail.php");

}
else if ($action == "summary-klaim-detail-regional")
{

	require_once($pathsummary."/klaim/detail-regional.php");

}
else if ($action == "summary-lost-ratio") // Pie Chart
{

	require_once($pathsummary."/loss-ratio/chart.php");

}
else if ($action == "summary-lost-ratio-detail")
{

	require_once($pathsummary."/loss-ratio/detail.php");

}
else if($action == "summary-lost-ratio-detail-regional")
{

	require_once($pathsummary."/loss-ratio/detail-regional.php");

}
else if ($action == "summary-pemeriksaankes") // Pie Chart
{

	require_once($pathsummary."/pemeriksaankes/chart.php");

}
else if ($action == "summary-pemeriksaankes-detail")
{

	require_once($pathsummary."/pemeriksaankes/detail.php");

}
else if ($action == "summary-pemeriksaankes-detail-yearly")
{

	require_once($pathsummary."/pemeriksaankes/detail_yearly.php");

}
else if ($action == "summary-pemeriksaankes-detail-daily")
{

	require_once($pathsummary."/pemeriksaankes/detail_daily.php");

}
else if ($action == "summary-kepuasan-pelanggan")
{

	require_once($pathsummary."/kepuasan_pelanggan.php");

}
else if ($action == "summary-sebaran-usia") // Pie Chart
{

	require_once($pathsummary."/sebaran-usia/chart.php");

}
else if ($action == "summary-sebaran-usia-detail")
{

	require_once($pathsummary."/sebaran-usia/detail.php");

}
else if ($action == "summary-sebaran-usia-detail-regional")
{

	require_once($pathsummary."/sebaran-usia/detail-regional.php");

}
else if ($action == "list-nmproduk-akad-to-dol")
{

	require_once($pathsummary."/akad-to-dol/list.php");

}
else if ($action == "summary-akad-to-dol") // Pie Chart
{

	require_once($pathsummary."/akad-to-dol/chart.php");

}
else if ($action == "summary-akad-to-dol-detail")
{

	require_once($pathsummary."/akad-to-dol/detail.php");

}
else if ($action == "summary-akad-to-dol-detail-regional") // Pie Chart
{

	require_once($pathsummary."/akad-to-dol/detail-regional.php");

}
else if ($action == "list-nmproduk-sebab-kematian")
{

	require_once($pathsummary."/sebab-kematian/list.php");

}
else if ($action == "summary-sebab-kematian") // Pie Chart
{

	require_once($pathsummary."/sebab-kematian/chart.php");

}
else if ($action == "summary-sebab-kematian-detail")
{

	require_once($pathsummary."/sebab-kematian/detail.php");

}
else if ($action == "summary-sebab-kematian-detail-regional")
{

	require_once($pathsummary."/sebab-kematian/detail-regional.php");

}
else if ($action == "summary-selesai-klaim")
{

	require_once($pathsummary."/selesai-klaim/chart.php");

}
else if ($action == "summary-selesai-klaim-detail")
{

	require_once($pathsummary."/selesai-klaim/detail.php");

}
else if ($action == "summary-selesai-klaim-detail-regional")
{

	require_once($pathsummary."/selesai-klaim/detail-regional.php");

}
else if ($action == "summary-statistik-ranking-rkt-detail")
{

	require_once($pathsummary."/statistik-ranking/realisasi-kredit/detail.php");

}
else if ($action == "summary-statistik-ranking-rkt-detail-regional")
{

	require_once($pathsummary."/statistik-ranking/realisasi-kredit/detail-regional.php");

}
else if ($action == "summary-statistik-ranking-jdt-detail")
{

	require_once($pathsummary."/statistik-ranking/jumlah-debitur/detail.php");

}
else if ($action == "summary-statistik-ranking-jdt-detail-regional")
{

	require_once($pathsummary."/statistik-ranking/jumlah-debitur/detail-regional.php");

}
else if ($action == "summary-statistik-ranking-kt-detail")
{

	require_once($pathsummary."/statistik-ranking/klaim-terbanyak/detail.php");

}
else if ($action == "summary-statistik-ranking-kt-detail-regional")
{

	require_once($pathsummary."/statistik-ranking/klaim-terbanyak/detail-regional.php");

}
else if ($action == "summary-realisasi-penutupan-detail")
{

	require_once($pathsummary."/realisasi-penutupan/detail.php");

}
else if ($action == "summary-realisasi-penutupan-detail-regional")
{

	require_once($pathsummary."/realisasi-penutupan/detail-regional.php");

}
else if ($action == "summary-outstanding-detail")
{

	require_once($pathsummary."/outstanding/detail.php");

}
else if ($action == "summary-outstanding-detail-regional")
{

	require_once($pathsummary."/outstanding/detail-regional.php");

}
else if ($action == "summary-realisasi-penutupan-detail-v1")
{

	require_once($pathsummary."/realisasi-penutupan/detailv1.php");

}
else if($action == "pemeriksaan-awal")
{

	//pemeriksaan awal oleh dokter
	$user_id = $_POST['user_id'];
	$form_id = $_POST['form_id'];
	$id_spk = $_POST['id_spk'];
	$idbank = $_POST['idbank'];
	$idproduk = $_POST['idproduk'];
	$pertanyaan1 = $_POST['ansquestion1'];
	$pertanyaan2 = $_POST['ansquestion2'];
	$pertanyaan3 = $_POST['ansquestion3'];
	$pertanyaan4 = $_POST['ansquestion4'];
	$pertanyaan5 = $_POST['ansquestion5'];
	$pertanyaan6 = $_POST['ansquestion6'];
	$ket1 = strtoupper($_POST['ket1']);
	$ket2 = strtoupper($_POST['ket2']);
	$ket3 = strtoupper($_POST['ket3']);
	$ket4 = strtoupper($_POST['ket4']);
	$ket5 = strtoupper($_POST['ket5']);
	$ket6 = strtoupper($_POST['ket6']);

	$tinggiBadan=$_POST['tinggiBadan'];
	$beratBadan=$_POST['beratBadan'];
	$tekananDarah=$_POST['tekananDarah'];
	$nadi=$_POST['nadi'];
	$pernafasan=$_POST['pernafasan'];
	$gulaDarah=$_POST['gulaDarah'];

	$tekananDarah = addslashes($tekananDarah);

	$kesimpulan = strtoupper($_POST['kesimpulan']);
	$catatan = strtoupper($_POST['catatan']);
	// $extraPremi = $_POST['extraPremi'];
	// $tglPeriksa = $_POST['tglPemeriksaan'];
	//TODO process file



	$filefotodebiturdua = changePicture('fotoDebiturByDokter',$id_spk.$user_id);
	$filettddokter = changePicture('fotoTTDDokter', $id_spk.$user_id);



	$cancel = false;
	if($user_id==""||$form_id==""||$id_spk=="" || $pertanyaan1=="" || $pertanyaan2==""||$pertanyaan3==""|| $pertanyaan4=="" ||$pertanyaan5=="" || $pertanyaan6=="" )
	{
		$cancel=true;
	}

	//Continue
	if($tinggiBadan=="" ||$beratBadan=="" || $tekananDarah=="" || $nadi=="" || $pernafasan == "" || $gulaDarah=="" ||$tekananDarah=="" || $kesimpulan=="" ||$catatan=="")
	{
		$cancel=true;
	}
	//Continue
	if($filefotodebiturdua=="" || $filettddokter == "" )
	{
		$cancel=true;
	}

	if($cancel)
	{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';

		echo json_encode($json);
		die();
	}




	$queryForm = "SELECT * FROM fu_ajk_spak_form WHERE id=$form_id";
	$resultForm = query_db($queryForm);

	$form =  mysql_fetch_assoc($resultForm);

	$debiturDOB = $form['dob'];
	$debiturTenor = $form['tenor'];
	$met_Date = datediff($debiturDOB, date('Y-m-d'));
	$met_Date_ = explode(",",$met_Date);
	if ($met_Date_[1] >= 6 ) {    $umur = $met_Date_[0] + 1;    }else{    $umur = $met_Date_[0];    }

	$query = "SELECT * FROM fu_ajk_ratepremi WHERE id_cost= '$idbank' AND id_polis= '$idproduk' AND usia='$umur' AND tenor='$debiturTenor'";

	$result = query_db($query);

	$cekrate_tenor = mysql_fetch_array($result);        // RATE PREMI
	// print_r($cekrate_tenor);

	$spk_plafond = $form['plafond'];
	$premi = $spk_plafond* $cekrate_tenor['rate'] / 1000;
	$queryPolis = "SELECT * FROM fu_ajk_polis WHERE id =1";
	$resultPolis = query_db($queryPolis);
	$admpolis = mysql_fetch_array($resultPolis);

	if ($premi < $admpolis['min_premium']) {    $premi_x = $admpolis['min_premium'];    }else{    $premi_x = $premi;    }

	$query = "UPDATE fu_ajk_spak SET status='Approve' WHERE id=$id_spk";
	$result = query_db($query);
// echo "premi_x: ".$premi_x;
	$query = "UPDATE fu_ajk_spak_form SET dokter_pemeriksa = (SELECT id FROM user_mobile WHERE md5(id)='$user_id'), ";
	$query .= "pertanyaan1='$pertanyaan1', ";
	$query .= "pertanyaan2='$pertanyaan2', ";
	$query .= "pertanyaan3='$pertanyaan3', ";
	$query .= "pertanyaan4='$pertanyaan4', ";
	$query .= "pertanyaan5='$pertanyaan5', ";
	$query .= "pertanyaan6='$pertanyaan6', ";
	$query .= "ket1='$ket1', ";
	$query .= "ket2='$ket2', ";
	$query .= "ket3='$ket3', ";
	$query .= "ket4='$ket4', ";
	$query .= "ket5='$ket5', ";
	$query .= "ket6='$ket6', ";
	$query .= "x_premi='$premi_x', ";
	$query .= "x_usia='$umur', ";
	$query .= "tinggibadan='$tinggiBadan', ";
	$query .= "beratbadan='$beratBadan', ";
	$query .= "tekanandarah='$tekananDarah', ";
	$query .= "nadi='$nadi', ";
	$query .= "pernafasan='$pernafasan', ";
	$query .= "guladarah='$gulaDarah', ";



	$query .= "tgl_periksa=CURDATE(), ";
	$query .= "kesimpulan='$kesimpulan', ";
	$query .= "catatan='$catatan', ";
	$query .= "filefotodebiturdua='$filefotodebiturdua', ";
	$query .= "filettddokter='$filettddokter', ";
	$query .= "input_date=NOW() ";
	$query .= "WHERE id='$form_id'";
	$result = query_db($query);
	if($result)
	{
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
	}
	else
	{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Failed';
	}
	echo json_encode($json);
}
else if ($action == "activation")
{
	$activationCode = isset($_POST['input']) ? $_POST['input'] : $_GET['input'];
	$hour = date("H");
	$month= date("m");
	$token="ADONAI";
	$string = $hour.$month.$token;
	$code= md5($string);
	$code = strtoupper(substr($code,0,6));
	if($activationCode==$code){
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
	}else{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Invalid activation code';
	}
	echo json_encode($json);
}
else if ($action == "list-investigasi")
{
	$userID = $_POST['user-id'];
	$userType = $_POST['user-type'];

	$query = "SELECT * FROM ajk_order_klaim WHERE iddokter=(SELECT id FROM user_mobile WHERE md5(id)='$userID') AND status='Proses'";

	$result = query_db($query);

	if($result)
	{
			if(mysql_num_rows($result)>0)
			{
				$listdebitur=array();
				while($row = mysql_fetch_assoc($result))
				{
					array_push($listdebitur,$row);
				}

				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['total_data'] = count($listdebitur);
				$json['data']=$listdebitur;

			}
			else
			{
				//salah password atau email
				$json['total_data'] = 0;
				$json['err_no'] = '0';
				$json['err_msg'] = 'Data debitur tidak tersedia.';
			}
	}
	else
	{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Proses gagal, coba lagi.';
	}
	echo json_encode($json);
}
else if ($action == "list-investigasi-proses")
{
	$userID = $_POST['user-id'];
	$userType = $_POST['user-type'];

	$query = "SELECT * FROM ajk_order_klaim WHERE iddokter=(SELECT id FROM user_mobile WHERE md5(id)='$userID') AND status='Investigasi'";

	$result = query_db($query);

	if($result)
	{
			if(mysql_num_rows($result)>0)
			{
				$listdebitur=array();
				while($row = mysql_fetch_assoc($result))
				{
					array_push($listdebitur,$row);
				}

				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['total_data'] = count($listdebitur);
				$json['data']=$listdebitur;

			}
			else
			{
				//salah password atau email
				$json['total_data'] = 0;
				$json['err_no'] = '0';
				$json['err_msg'] = 'Data debitur tidak tersedia.';
			}
	}
	else
	{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Proses gagal, coba lagi.';
	}
	echo json_encode($json);

}
else if($action=='search-peserta')
{
	$idPeserta = $_POST['id-peserta'];
	$idDokter = $_POST['id-dokter'];

	// $query = "SELECT * from ajk_order_klaim WHERE idpeserta = '$idPeserta'";
	//$query = "SELECT nip_primary, nip_secondary, namalengkap FROM user_mobile WHERE id = md5(id)='$idDokter'";
	$query = "SELECT F.*, S.nip_primary AS nip_primary, S.nip_secondary AS nip_secondary, S.namalengkap AS namalengkap FROM ajk_order_klaim AS F, user_mobile AS S WHERE F.idpeserta= '$idPeserta' AND F.iddokter = S.id";

	$result = query_db($query);

		if($result)
		{

			//if found

			//RESULT DUMMY DATA
			$jsonResponse['err_no'] = '0';
			$jsonResponse['err_msg'] = 'Success';


			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);

				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['data']=$row;

			}
			else
			{
				//salah password atau email
				$json['err_no'] = '1';
				$json['err_msg'] = 'Failed! Wrong email or password';
			}

		}
		echo json_encode($json);

}
else if($action=="list-investigasi-klaim"){
	//DUMMY

	$data['nama'] = 'ARIEF KURNIAWAN';
	$data['dob'] = '';
	$data['plafond'] = '120000000';
	$data['alamat'] = '1981-01-30';
	$data['no_tlp_ahliwaris'] ='082111023546';
	$data['dokter'] = '3';



	$json['err_no'] = '0';
	$json['err_msg'] = 'Success';


}
else if($action=="change-permission"){
	changePermission();
}
else if($action == "validate-pemeriksaan-awal"){
	$input = $_POST['input'];
	$idspk = $_POST['idspk'];


	$result = query_db("SELECT * FROM fu_ajk_spak_form_temp WHERE token='$input' AND idspk='$idspk'");

	if($result){
		if(mysql_num_rows($result)>0){

			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';

		}else{

			$json['err_no'] = '1';
			$json['err_msg'] = 'Invalid token';

		}
		echo json_encode($json);
	}
}
else if($action == "load-verbalautopsi"){
	$query = "SELECT * FROM ajk_verbalautopsi ";
	$result = query_db($query);
	if($result)
	{

		if(mysql_num_rows($result)>0){
			$listVerbalAutopsi = array();
			while($row = mysql_fetch_assoc($result))
			{
				array_push($listVerbalAutopsi, $row);
			}
			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';
			$json['data'] = $listVerbalAutopsi;
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'No data available';
		}



	}else{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error Occured';
	}
	echo json_encode($json);
}
else if($action== "list-question-verbal-autopsi"){
	$penyakitID  = $_POST['penyakit-id'];

	$query = "SELECT * FROM ajk_qverbalautopsi WHERE idv=$penyakitID";

	$result = query_db($query);
	$listQuestion = array();
	if($result)
	{
		if(mysql_num_rows($result)>0)
		{
			while($row = mysql_fetch_assoc($result))
			{
				array_push($listQuestion,$row);
			}


			$json['err_no']="0";
			$json['err_msg']="Success";
			$json['data'] = $listQuestion;
		}
		else
		{
			$json['err_no']="1";
			$json['err_msg']="No data avalaiable";
		}


	}
	echo json_encode($json);
}
else if($action == "submit-klaim")
{


	$nmrorder = $_POST['nmrorder'];
	$user_id = $_POST['user-id'];
	$tipe_proses = $_POST['tipe-proses'];
	$jawaban_pertanyaan = json_decode(stripslashes($_POST['jawaban-pertanyaan']),true);
	$jawaban_penyakit = json_decode(stripslashes($_POST['jawaban-penyakit']),true);

	//upload Photo
	$filettddokter = changePicture('dokter-signature',$nmrorder.$user_id);


	// echo "NO ORDER: ".$nmrorder;
	// echo "</BR>";
	// echo "USER ID: ".$user_id;
	// echo "</BR>";
	// echo "Tipe Proses: ".$tipe_proses;
	// echo "</BR>";
	// var_dump($jawaban_pertanyaan);
	// echo "</BR>";
	// var_dump($jawaban_penyakit);

//Store all answer
	//GET TTDDOKTER



	$myfile = fopen("log.txt", "w+") or die("Unable to open file!");
	// print_r($jawaban_pertanyaan);
	// chmod("log.txt",0777);
	fwrite($myfile, "============".date('d-m-Y H:i:s')."=======\n");
	fwrite($myfile, stripcslashes($_POST['jawaban-pertanyaan']));
	fclose($myfile);



	for($i=0;$i<count($jawaban_pertanyaan); $i++){
		$temp = $jawaban_pertanyaan[$i];
		$question_id = $temp['question-id'];
		$penyakit_id = $temp['penyakit-id'];
		$jawaban = $temp['jawaban'];
		$keterangan = addslashes($temp['keterangan']);

		$query = "INSERT INTO ajk_averbalautopsi (no_order, id_question, id_verbalautopsi, jawaban, keterangan) ";
		$query.= "VALUES('$nmrorder', $question_id, $penyakit_id, '$jawaban', '$keterangan')";


		query_db($query);
	}

	// $filefotodebiturdua = changePicture('fotoDebiturByDokter');


	$query2 = "UPDATE ajk_order_klaim SET stroke='".$jawaban_penyakit['stroke']."', ";
	$query2.= "diabetes_melitus='".$jawaban_penyakit['diabetes']."', ";
	$query2.= "jantung='".$jawaban_penyakit['jantung']."', ";
	$query2.= "gagal_ginjal='".$jawaban_penyakit['ginjal']."', ";
	$query2.= "tekanan_darah_tinggi='".$jawaban_penyakit['tekanandarah']."', ";
	$query2.= "kanker='".$jawaban_penyakit['kanker']."', ";
	$query2.= "hati='".$jawaban_penyakit['hati']."', ";
	$query2.= "penyakit_lain='".$jawaban_penyakit['penyakit_lain']."', ";
	$query2.= "filettddokter='".$filettddokter."', ";
	$query2.= "prosesinvestigasi='".$tipe_proses."', ";
	$query2.= "status='Proses' ";
	$query2.= "WHERE nmrorder='$nmrorder'";


	$result = query_db($query2);
	// echo $query2;
	if($result){
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
	}else{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Failed';
	}
	echo json_encode($json);




//Store order


}

else if($action=="resolve-cabang-temp"){
	resolveUnnamedCabang('fu_ajk_spak_form_temp');
}
else if($action=="resolve-cabang"){
	resolveUnnamedCabang('fu_ajk_spak_form');
}
else if($action=="random-number"){
	echo generateRandomNumber(6);
}else if($action=="gpstrack"){
	$nip = isset($_POST['nip'])? $_POST['nip']: $_GET['nip'];
	$latitude = isset($_POST['latitude'])? $_POST['latitude']: $_GET['latitude'];
	$longitude = isset($_POST['longitude'])? $_POST['longitude']: $_GET['longitude'];
	$imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
	$phone = isset($_POST['phone'])? $_POST['phone']: $_GET['phone'];
	$version_code = isset($_POST['version_code'])? $_POST['version_code']: $_GET['version_code'];
	$deviceVersion = isset($_POST['deviceVersion'])? $_POST['deviceVersion']: $_GET['deviceVersion'];
	$today = date('Y-m-d H:i:s');
	$hariini = date('Y-m-d');


	$querygps = mysql_query("SELECT * FROM ajkgps WHERE username = '".$nip."' AND date_format(datettime,'%Y-%m-%d') = '".$hariini."'");
	if($resultgps = mysql_num_rows($querygps)>0){
		$query	= "UPDATE ajkgps SET longitude = '".$longitude."',latitude = '".$latitude."', datettime = '".$today."'
				WHERE username = '".$nip."' AND date_format(datettime,'%Y-%m-%d') = '".$hariini."'";
	}else{
		$query	= "INSERT INTO ajkgps (username, longitude,latitude,phone,imei,datettime)
				VALUES('".$nip."','".$longitude."','".$latitude."','".$phone."','".$imei."','".$today."')";
	}


	$result = query_db($query);

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';
		echo json_encode($json);
		die();
	}

	if($result){
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
		echo json_encode($json);
	}else{
		$json['err_no'] = '1';
		$json['err_msg'] = 'Koneksi Error';
		echo json_encode($json);
	}
}else if($action=="sendtoken"){
	$nip = isset($_POST['nip'])? $_POST['nip']: $_GET['nip'];
	$usertoken = isset($_POST['usertoken'])? $_POST['usertoken']: $_GET['usertoken'];
	$imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
	$version_code = isset($_POST['version_code'])? $_POST['version_code']: $_GET['version_code'];
	$deviceVersion = isset($_POST['deviceVersion'])? $_POST['deviceVersion']: $_GET['deviceVersion'];
	$today = date('Y-m-d H:i:s');
	$hariini = date('Y-m-d');
	$packagename = 'com.bios.report';

	$quser = mysql_fetch_array(mysql_query("SELECT * FROM useraccess WHERE id =  '$nip'"));

	if($usertoken != ""){
		$cektoken = mysql_query("SELECT * FROM user_mobile_token WHERE UserImei = '$imei' AND packagename = '$packagename'");
		$tokenrow = mysql_num_rows($cektoken);
		if($tokenrow == 0){
			mysql_query("INSERT INTO user_mobile_token (UserID, UserToken,UserImei, packagename, inputdate) VALUES('".$nip."','$usertoken','$imei','$packagename','$today')");
		}else{
			mysql_query("UPDATE user_mobile_token SET UserToken = '$usertoken', UserID = '$nip', updatedate = '$today' WHERE UserImei = '$imei' AND packagename = '$packagename'");
		}
	}
	$json['err_no'] = '0';
	$json['err_msg'] = 'Success';
	echo json_encode($json);
	//$result = query_db($query);
}else if($action=="notification"){
	$nip = isset($_POST['nip'])? $_POST['nip']: $_GET['nip'];
	$usertoken = isset($_POST['usertoken'])? $_POST['usertoken']: $_GET['usertoken'];
	$imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
	$version_code = isset($_POST['version_code'])? $_POST['version_code']: $_GET['version_code'];
	$deviceVersion = isset($_POST['deviceVersion'])? $_POST['deviceVersion']: $_GET['deviceVersion'];

	if($deviceVersion <> $versionavailable)
	{
		$pesan = "Silahkan diperbaharui aplikasi anda";
		$subject = "Update Available";
		$typeupdate = "update";

		sendPushNotificationToGCM($usertoken,$subject, $pesan, $typeupdate);
	}



	$result = query_db($query);
}
else if($action=='data-dashboard')
{

	$userType = $_POST['user-type'];
	$userID = $_POST['user-id']; //
	$deviceVersion = $_POST['deviceVersion'];

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}

    $quser = mysql_fetch_array(mysql_query("SELECT * FROM useraccess WHERE id =  '$userID'"));


    if($userType == "Direksi"){
        $filterquery = "AND idbroker = '".$quser['idbroker']."' AND idclient = '".$quser['idclient']."'";

    }elseif($userType == "Kadiv" AND $quser['level'] =="13"){
        $filterquery = "AND idbroker = '".$quser['idbroker']."' AND idclient = '".$quser['idclient']."' AND regional='".$quser['regional']."'";

    }elseif($userType == "Kadiv" AND $quser['level'] =="12"){
        $filterquery = "AND idbroker = '".$quser['idbroker']."' AND idclient = '".$quser['idclient']."' AND cabang = '".$quser['branch']."'";

    }
	$query = "SELECT
	            count(*) AS ttl_debitur,
        		IFNULL(SUM(ajkpeserta.totalpremi),0) as ttl_premi,
        		IFNULL(SUM(ajkpeserta.plafond),0) as ttl_plafond
        	  FROM ajkpeserta
        	  WHERE ajkpeserta.statusaktif in ('Aktif','Inforce','Lapse','Maturity')
              $filterquery";

		$result = query_db($query);

		if($result)
		{
			if(mysql_num_rows($result)>0)
			{
				$row = mysql_fetch_assoc($result);
				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['total_data'] = mysql_num_rows($result);
				$json['data']=$row;

			}
			else
			{
				//salah password atau email
				$json['total_data'] = 0;
				$json['err_no'] = '0';
				$json['err_msg'] = 'Gagal Loading data user.';
			}
		}else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Proses gagal, coba lagi.';
		}
		echo json_encode($json);
}
else
{
	$json['err_no'] = '1';
	$json['err_msg'] = 'No action found.';

	echo json_encode($json);
}

function generateRandomNumber ($length){
	$token = "";
	for($i = 0 ; $i<$length;$i++){
		$token.=rand(0,9);
	}
	return $token;
}

function changePicture($postKeyword, $token)
{
        $photo = $_FILES[$postKeyword]['name'];
        //$prevphoto = $_POST['prevphoto'];

        if ($photo == "") {
            //$urlphoto = $prevphoto;
        }
        else
        {
            //=============
           	$arr = explode("." , $photo);


            $ext = $arr[count($arr)-1];

            $encrypted_name = date('Y-m-d_H:i:s_').md5($postKeyword.date('Y-m-d H:i:s').$token);
            $filepath = "uploads" . "/" . $encrypted_name.".".$ext;

            if (move_uploaded_file($_FILES[$postKeyword]['tmp_name'], $filepath)) {
                $urlphoto = "/" . $encrypted_name . "." . $ext;
            }
            // chmod($urlphoto, 755);
        }


        return $filepath;

}
function changePermission(){
	$dir    = 'uploads/';
	$files1 = scandir($dir);
	for($i=0; $i<count($files1);$i++){
		$file = $files1[$i];
		if($file!="." && $file!=".." && $file!=""){
		echo $dir.$file."</br></br>";

		chmod($dir.$file,755);
		}
	}
}
function formatSPAKNo($input){

	if(strlen($input)<6){
		$cur_length = strlen($input);
		$dif = 5-$cur_length;
		$zero = "";
		for($i=0;$i<$dif;$i++){
			$zero .= "0" ;
		}
		return "M".$zero.$input;
	}else{
		return "M".$input;
	}
}
function formatSPAKNoSKKT($input){

	if(strlen($input)<5){
		$cur_length = strlen($input);
		$dif = 4-$cur_length;
		$zero = "";
		for($i=0;$i<$dif;$i++){
			$zero .= "0" ;
		}
		return "MP".$zero.$input;
	}else{
		return "MP".$input;
	}
}

function email_to($to, $from, $subject ,$message){
		// email stuff (change data below)
	// $to = $_REQUEST['email'];
	$from = "donotreply@adonai.co.id";
	$subject = "adonai.co.id - ".$subject;
	$message = "";

	// a random hash will be necessary to send mixed content
	$separator = md5(time());

	// carriage return type (we use a PHP end of line constant)
	$eol = PHP_EOL;



	// main header
	$headers  = "From: ".$from.$eol;
	$headers .= "MIME-Version: 1.0".$eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

	// no more headers after this, we start the body! //

	$body = "--".$separator.$eol;
	$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
	//$body .= "This is a MIME encoded message.".$eol;

	// message
	$body .= "--".$separator.$eol;
	$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
	$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
	$body .= $message.$eol;


	// send message
	mail($to, $subject, $body, $headers);
}
function sendPushNotificationToGCM($registatoin_ids, $data) {
	//Google cloud messaging GCM-API url
	$url = 'https://fcm.googleapis.com/fcm/send';
	$fields = array(
	//'to' => "/topics/global",
	'to' => $registatoin_ids,
	'data' => $data
/*
	'data' =>
	array
	(
		'post_title' => 'SPK TELAH DIAPPROVE OLEH SUPERVISOR',
		'post_msg' => $message,
		"datamsg" =>$type,
		"datastatus" => "Aktif",
		"dataformid" => $title,
		"dataidspk" => "27114"
	)
   */
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
