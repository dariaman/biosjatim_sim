<?php
include_once "config.php";
//include_once "../sendmail/sendmail.php";

connect_db();

if(isset($_POST['action']))
{
	$action = $_POST['action'];
}else if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
$qmversion = mysql_query("SELECT * FROM mobile_app_version WHERE `status` = 'Available' AND type = 'Marketing' and api_version = '4'");
$rversion = mysql_fetch_array($qmversion);
$versionavailable = $rversion['version_name'];
$versioncodeavailable = $rversion['version_code'];
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
	UNION
	SELECT * FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND idclient is not null
	AND del is null";
	$result = query_db($query);




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
				$user_info['aktifuser'] = $row['aktif'];

				$qklient = mysql_query("SELECT * FROM ajkclient WHERE id= '".$row['idclient']."' and del is null");
				$rklient = mysql_fetch_array($qklient);

				$querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id ='".$row['idbroker']."' and del is null");
				$rowbroker = mysql_fetch_array($querybroker);

				$user_info['namaclient'] = is_null($rklient['name']) ? 'BIOS' : $rklient['name'];
				$user_info['logoclient'] = is_null($rklient['logo']) ? '' : $rklient['logo'];
				$user_info['namabroker'] = is_null($rowbroker['name']) ? 'BIOS' : $rowbroker['name'];
				$user_info['logobroker'] = is_null($rowbroker['logo']) ? '' : $rowbroker['logo'];

				$json['err_no'] = '0';
				$json['err_msg'] = 'Success';
				$json['user_info'] = $user_info;


				mysql_query("UPDATE useraccess SET sesslogout = '0' WHERE id ='".$row['id']."'");
			}
			else
			{
				$json['err_no'] = '1';
				$json['err_msg'] = 'Failed! Wrong email or password';
			}
	}
	echo json_encode($json);
}
else if ($action == 'load-client')
{
    $username = isset($_POST['username'])? $_POST['username']: $_GET['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : $_GET['password'];
    $password = md5($password);

    $query	="SELECT *, concat(firstname,' ',lastname) as nama FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND (idbroker is null AND idclient is null)
	UNION
	SELECT *, concat(firstname,' ',lastname) as nama FROM useraccess
	WHERE  username = '".$username."'
	AND passw = '".$password."'
	AND idclient is not null";
    $result = query_db($query);

    if($result){
        if(mysql_num_rows($result)>0)
        {
            $row = mysql_fetch_assoc($result);

            $user_info['nip']= $row['username'];
            $user_info['id'] = $row['id'];
            $user_info['user_id'] = $row['id'];
            $user_info['regional'] = $row['firstname'];
            $user_info['nama'] = $row['firstname'];
            $user_info['namalengkap'] = $row['nama'];
            $user_info['type'] = $row['tipe'];
            $user_info['idbank'] = $row['idclient'];
            $user_info['idmitra'] = $row['idclient'];
            $user_info['userphoto'] = $row['photo'];
            $user_info['aktifuser'] = $row['aktif'];

            $qklient = mysql_query("SELECT * FROM ajkclient WHERE id= '".$row['idclient']."'");
            $rklient = mysql_fetch_array($qklient);

            $querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id ='".$row['idbroker']."'");
            $rowbroker = mysql_fetch_array($querybroker);

            $user_info['namaclient'] = is_null($rklient['name']) ? 'BIOS' : $rklient['name'];
            $user_info['logoclient'] = is_null($rklient['logo']) ? '' : $rklient['logo'];
            $user_info['namabroker'] = is_null($rowbroker['name']) ? 'BIOS' : $rowbroker['name'];
            $user_info['logobroker'] = is_null($rowbroker['logo']) ? '' : $rowbroker['logo'];

            $json['err_no'] = '0';
            $json['err_msg'] = 'Success';
            $json['user_info'] = $user_info;

            //mysql_query("UPDATE useraccess SET sesslogout = '0' WHERE id ='".$row['id']."'");
        }
        else
        {
            $json['err_no'] = '1';
            $json['err_msg'] = 'Failed! Wrong email or password';
        }
    }
    echo json_encode($json);

}
else if($action=='list-debitur')
{

	$userType = $_POST['user-type'];
	// $userType = $_GET['user-type'];
	// $idSPK = $_GET['id-spk'];
	$userID = $_POST['user-id']; //

	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."' and del is null");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];
	$userType = $rowuser['tipe'];
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

	$page = $_POST['startpage'];

	// How many items to list per page
	$limit = 10;

	$calc = $limit * $page;
	$start = $calc - $limit;

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}


	if ($userType=="Bank")
	{

		$query = "SELECT id,nomorspk,nama,statusspk,input_date, ajkspk.photodebitur1 as photodebitur, IFNULL(alamat,'') as alamat FROM ajkspk WHERE idbroker = '".$idbro."'
		AND idpartner = '".$idclient."'
		AND input_by = '".$iduser."' and del is null
		ORDER BY input_date DESC
		LIMIT $start,$limit";

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

	}elseif ($userType=="Admin")
	{

		$query = "SELECT id,nomorspk,nama,statusspk,input_date, ajkspk.photodebitur1 as photodebitur, IFNULL(alamat,'') as alamat FROM ajkspk where del is null
		ORDER BY input_date DESC
		LIMIT $start,$limit";

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
else if($action=='list-debitur-filter')
{

	$userType = $_POST['user-type'];
	$userID = $_POST['user-id'];
	$search = $_POST['search'];

	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."' and del is null");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];
	$userType = $rowuser['tipe'];
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

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}


	if ($userType=="Bank")
	{

		$query = "SELECT id,nomorspk,nama,statusspk,input_date, ajkspk.photodebitur1 as photodebitur, IFNULL(alamat,'') as alamat FROM ajkspk WHERE idbroker = '".$idbro."'
		AND idpartner = '".$idclient."'
		AND input_by = '".$iduser."'
		AND (nama like '%$search%' OR nomorspk like '%$search%' OR input_date like '%$search%' OR statusspk like '%$search%' OR alamat like '%$search%')
		and del is null
		ORDER BY input_date DESC";

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
				$json['err_msg'] = 'Data yang anda cari tidak tersedia.';
			}
		}else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Proses gagal, coba lagi.';
		}
		echo json_encode($json);

	}elseif ($userType=="Admin")
	{

		$query = "SELECT id,nomorspk,nama,statusspk,input_date, ajkspk.photodebitur1 as photodebitur, IFNULL(alamat,'') as alamat FROM ajkspk
		WHERE (nama like '%$search%' OR nomorspk like '%$search%' OR input_date like '%$search%' OR statusspk like '%$search%' OR alamat like '%$search%') and del is null
		ORDER BY input_date DESC";

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
else if($action=='search-debitur')
{
	// $userType = $_POST['user-type'];
	// $userType = $_GET['user-type'];
	$userType = $_POST['user-type'];
	$idSPK = $_POST['id-spk'];
	$deviceVersion = $_POST['deviceVersion'];
	$status = $_POST['status'];
	// $idSPK = $_GET['id-spk'];
	///$userID = $_POST['user-id']; DISABLE FOR A MOMENT

		//TODO QUERY TO GET VALUE
    $query = "SELECT ajkspk.id,ajkspk.nomorspk as spak,ajkpolis.produk as nmproduk,ajkspk.idpartner as idcost,
		'' as dokter, ajkspk.id as idspk,ajkspk.nama,ajkspk.dob,ajkspk.nomorktp as noidentitas,
		ajkspk.jeniskelamin as jns_kelamin,ajkspk.alamat,ajkspk.pekerjaan,ajkspk.lamausahath,ajkspk.lamausahabln,ajkspk.jenisusaha,
		'' as pertanyaan1, '' as ket1, '' as pertanyaan2, '' as ket2, '' as pertanyaan3, '' as ket3, '' pertanyaan4, '' as ket4,
		'' as pertanyaan5, '' as ket5, '' as pertanyaan6, '' as ket6, ajkspk.dokterpemeriksa as dokter_pemeriksa,ajkspk.tinggibadan,
		ajkspk.beratbadan, tekanandarah as tekanandarah, ajkspk.nadi, ajkspk.pernafasan,ajkspk.guladarah, ajkspk.dokterkesimpulan as kesimpulan,
		ajkspk.doktercatatan as catatan, ajkspk.tglperiksa as tgl_periksa,plafond as plafond, tglakad,
		ajkspk.tenor as tenor, ajkspk.tglakhir, IFNULL(ajkspk.premi,0) as premi, IFNULL(nettpremi,0) as nettpremi,em as em, ketem, ajkspk.usia as x_usia,
		'' as cabang,
		ajkspk.photodebitur1 as filefotodebitursatu,
		ajkspk.photodebitur2 as filefotodebiturdua,
		ajkspk.photoktp as filefotoktp,
		ajkspk.ttddebitur as filettddebitur,
		ajkspk.ttdmarketing as filettdmarketing,
		ajkspk.photosk as filefotoskpensiun,
		ajkspk.ttddokter as filettddokter,
		ajkspk.input_by as input_by, ajkspk.update_by as update_by, ajkspk.update_date as update_date,
		ajkspk.del as del, mppbln as mpp, '' as catatan_penting, token as token  FROM ajkspk
		LEFT JOIN ajkpolis ON ajkpolis.id = ajkspk.idproduk WHERE ajkspk.nomorspk =  '$idSPK'";


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
				$json['err_msg'] = 'Failed! Wrong email or password'.$query;
			}
			echo json_encode($json);
		}else{
			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';
			echo json_encode($json);
		}
}
else if($action=='data-dashboard')
{

	$userType = $_POST['user-type'];
	$userID = $_POST['user-id']; //
	$deviceVersion = $_POST['deviceVersion'];

	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."'");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];
	$userType = $rowuser['tipe'];
	$sessionlogout = $rowuser['sesslogout'];

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}

	if($userType=="Admin"){
		$filterusr = "";
	}else{
		$filterusr = "AND input_by = '$userID'";
	}

		$query = "SELECT IFNULL(count(case when ajkspk.statusspk = 'Aktif'
							OR ajkspk.statusspk = 'Request'
							OR ajkspk.statusspk = 'Pending'
							OR ajkspk.statusspk = 'Proses'
							OR ajkspk.statusspk = 'Realisasi'
							OR ajkspk.statusspk = 'PreApproval'
							OR ajkspk.statusspk = 'Batal' then ajkspk.nama end),0) AS ttl_debitur, IFNULL(count(case when ajkspk.statusspk = 'Batal' then ajkspk.nama end),0) AS statusbatal,
		IFNULL(count(case when ajkspk.statusspk = 'Aktif' then ajkspk.nama end),0) AS statusaktif,
		IFNULL(count(case when ajkspk.statusspk = 'Request' then ajkspk.nama end),0) AS statusrequest,
		IFNULL(count(case when ajkspk.statusspk = 'Pending' then ajkspk.nama end),0) AS statuspending,
		IFNULL(count(case when ajkspk.statusspk = 'Proses' then ajkspk.nama end),0) AS statusproses,
		IFNULL(count(case when ajkspk.statusspk = 'Realisasi' then ajkspk.nama end),0) AS statusrealisasi,
		IFNULL(count(case when ajkspk.statusspk = 'PreApproval' then ajkspk.nama end),0) AS statuspreapproval,
		IFNULL(sum(case when ajkspk.statusspk <> 'Batal' then ajkspk.premi end),0) AS totalpremi,
		$sessionlogout AS sessionlogout
		FROM ajkspk WHERE del IS NULL $filterusr";

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
else if($action=='data-profile')
{

	$userType = $_POST['user-type'];
	$userID = $_POST['user-id']; //
	$deviceVersion = $_POST['deviceVersion'];

	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."'");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];
	$userType = $rowuser['tipe'];

	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}

	$query = "SELECT CONCAT('(',username,')',' ',IFNULL(firstname,''),' ',IFNULL(lastname,'')) AS NamaUser,
IFNULL(email,'') AS EmailUser,
CASE WHEN gender = 'L' THEN 'LAKI-LAKI' ELSE 'PEREMPUAN' END AS JenisKelamin,
IFNULL(DATE_FORMAT(dob,'%d-%m-%Y'),'00-00-0000') AS tglLahir,
IFNULL(ajkregional.name,'') AS NamaRegional,
IFNULL(ajkcabang.name,'') AS NamaCabang,
IFNULL(leveluser.nama,'') AS NamaJabatan,
IFNULL(DATE_FORMAT(useraccess.input_time,'%d-%m-%Y'),'00-00-0000') AS tglJoin
FROM  useraccess
LEFT JOIN ajkregional ON ajkregional.er = useraccess.regional
LEFT JOIN ajkcabang ON ajkcabang.er = useraccess.branch
LEFT JOIN leveluser ON leveluser.er = useraccess.level
WHERE  id = '".$userID."'";

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
else if($action=='input-foto')
{

	$userID 		= $_POST['user-id']; //
	$noidentitas 	= $_POST['nomorktp'];
	$long 	= $_POST['longitude'];
	$lat 	= $_POST['latitude'];
	$alamat 	= $_POST['alamat'];
	$today = date('Y-m-d H:i:s');
	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = '../myFiles/_photogeneral/'.$foldername;

	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}
	//$test = "temp_photo_FullBodyPhoto.jpg";
	//$filefotofullbody = $_POST['filefotofullbody'];
	$filefoto1 = changePicture('filefoto1',$user_id.$noidentitas);
	$filefoto2 		= changePicture('filefoto2',$user_id.$noidentitas);

	$filefoto1_name = $_FILES['filefoto1']['name'];
	$filefoto1_temp = $_FILES['filefoto1']['tmp_name'];

	$filefoto2_name = $_FILES['filefoto2']['name'];
	$filefoto2_temp = $_FILES['filefoto2']['tmp_name'];



	if($filefoto1_name!=""){
		$destination_folder1		= $path.'/'.$filefoto1_name;
		move_uploaded_file($filefoto1_temp,$destination_folder1);
		mysql_query("insert into ajkphotoklaim (idpeserta,photo,type,latitude,longitude,lokasi,input_by,input_date)
				values ('".$noidentitas."','".$filefoto1_name."','awal','".$lat."','".$long."','".$alamat."','".$userID."','".$today."')");
	}
	if($filefoto2_name!=""){
		$destination_folder2		= $path.'/'.$filefoto2_name;
		move_uploaded_file($filefoto2_temp,$destination_folder2);
		mysql_query("insert into ajkphotoklaim (idpeserta,photo,type,latitude,longitude,lokasi,input_by,input_date)
				values ('".$noidentitas."','".$filefoto2_name."','awal','".$lat."','".$long."','".$alamat."','".$userID."','".$today."')");
	}
	$json['err_no'] = '0';
	$json['err_msg'] = 'Success';
	echo json_encode($json);
}
else if($action=='list-nmproduk')
{

	$userID 		= $_POST['user-id']; //
	$type 	= $_POST['type'];
	$idbro 	= $_POST['idbro'];
	$idclient 	= $_POST['idclient'];
	$deviceVersion 	= $_POST['deviceVersion'];
	$version_code 	= $_POST['version_code'];


	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."'");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	//$idclient = $rowuser['idclient'];
	$userType = $rowuser['tipe'];

	$today = date('Y-m-d H:i:s');
	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}

	if($userType=="Admin"){
		$querySelect = "SELECT produk as nmproduk, id as idpolis,plafondstart, plafondend,agestart, ageend,agecalculateday,IFNULL(mppstart,0) as mppstart, IFNULL(mppend,0) as mppend, IFNULL(tenormin,0) as tenormin, IFNULL(tenormax,0) as tenormax, mpptype
					FROM ajkpolis
					WHERE status = 'Aktif'
					AND general = 'T'
					AND del is null";
	}else{
		$querySelect = "SELECT produk as nmproduk, id as idpolis,plafondstart, plafondend,agestart, ageend,agecalculateday,IFNULL(mppstart,0) as mppstart, IFNULL(mppend,0) as mppend, IFNULL(tenormin,0) as tenormin, IFNULL(tenormax,0) as tenormax, mpptype
					FROM ajkpolis
					WHERE idcost = '".$idclient."'
					AND status = 'Aktif'
					AND general = 'T'
					AND del is null";
	}


	$result					= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$json['data'][] = $getData;
	}


	if($result)
	{
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
		echo json_encode($json);
	}else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}
}
else if($action=='list-asuransi')
{

    $userID 		= $_POST['user-id']; //
    $type 	= $_POST['type'];
    $idbro 	= $_POST['idbro'];
    $idclient 	= $_POST['idclient'];
    $idproduk 	= $_POST['idproduk'];
    $deviceVersion 	= $_POST['deviceVersion'];
    $version_code 	= $_POST['version_code'];

    $bulan = date('m');
    $tahun = date('Y');

    $queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."'");
    $rowuser = mysql_fetch_array($queryuser);
    $iduser = $rowuser['id'];
    $idbro = $rowuser['idbroker'];
    //$idclient = $rowuser['idclient'];
    $userType = $rowuser['tipe'];

    $today = date('Y-m-d H:i:s');
    if($deviceVersion <> $versionavailable)
    {
        $json['err_no']  = '20';
        $json['err_msg'] = 'You need to upgrade your device!!!';

        echo json_encode($json);
        die();
    }

    if($userType=="Admin"){
        $querySelect = "SELECT produk as nmproduk, id as idpolis,plafondstart, plafondend,agestart, ageend,agecalculateday,IFNULL(mppstart,0) as mppstart, IFNULL(mppend,0) as mppend, IFNULL(tenormin,0) as tenormin, IFNULL(tenormax,0) as tenormax, mpptype
					FROM ajkpolis
					WHERE status = 'Aktif'
					AND general = 'T'
					AND del is null";
    }else{
        /*
        $querySelect = "select ajkinsurance.id, ajkinsurance.`name` from ajkpolisasuransi INNER JOIN ajkinsurance on ajkinsurance.id = ajkpolisasuransi.idas
                        where ajkpolisasuransi.idproduk = '".$idproduk."' and ajkpolisasuransi.del is null";
        */
        //$querySelect = "select id, name from ajkinsurance where idc = '1' and del is null";

        $querySelect = "select ajkinsurance.id,
                        			CONCAT(ajkinsurance.`name`,' - ',ifnull(ajksharehis.persentase_target,0),' %')as name,
                        			ifnull(ajksharehis.nilai_target,0) as target,
			                        ifnull(ajksharehis.nilai_pencapaian,0) as sekarang
                        from ajkinsurance
                        inner join ajksharehis
                        on ajksharehis.idinsurance = ajkinsurance.id
                        where ajkinsurance.del is null and
                        			ajkinsurance.idc = 1 AND
                        			ajksharehis.bulan = '$bulan' and
                        			ajksharehis.tahun = '$tahun'
                        order by name";
    }


    $result					= query_db($querySelect);
    while ($getData = mysql_fetch_assoc($result))
    {
        $json['data'][] = $getData;
    }


    if($result)
    {
        $json['err_no'] = '0';
        $json['err_msg'] = 'Success';
        echo json_encode($json);
    }else {
        $json['err_no'] = '1';
        $json['err_msg'] = 'Error occured. Please try again.';
        echo json_encode($json);
    }
}
else if($action=='hitung-premi')
{
    $tanggallahir = isset($_POST['tanggallahir'])? $_POST['tanggallahir']: $_GET['tanggallahir'];
    $tenor = isset($_POST['tenor'])? $_POST['tenor']: $_GET['tenor'];
    $plafond = isset($_POST['plafond'])? $_POST['plafond']: $_GET['plafond'];
    $produk = isset($_POST['produk'])? $_POST['produk']: $_GET['produk'];
    $idproduk = isset($_POST['idproduk'])? $_POST['idproduk']: $_GET['idproduk'];

    $rubah = date_format(date_create($tanggallahir), 'Y');
    $thn_skrg = date('Y');
    $umur = $thn_skrg - $rubah;

    $query = "select * from ajkratepremi
                where idpolis = '$idproduk'
                and '$tenor' BETWEEN tenorfrom and tenorto
                and '$umur' BETWEEN agefrom and ageto
                and `status` = 'aktif'
                and del is null ";
    $result = query_db($query);

    if(mysql_num_rows($result)>0)
    {
        $row = mysql_fetch_assoc($result);
        //$rate_info['rate']=$row['rate'];
        $rate = $row['rate'];

        $premi = $plafond * $rate/1000;
        $extrapremi = $premi * 40 /100;
        $totalpremi = $premi + $extrapremi;

        if ($totalpremi < 250000)
        {
            $totalpremi = 250000;
        }

        // $json['rate_info'] = $rate_info;
        /*
           $row['premi'] = round($premi)."";
           $row['extrapremi'] = round($extrapremi)."";
        */

        $premi1 = round($premi)."";
        $extrapremi1 = round($extrapremi)."";
        $totalpremi1 = round($totalpremi)."";

        $coba['usia'] = $umur;
        $coba['rate'] = $rate;
        $coba['premi'] = $premi1;
        $coba['extrapremi'] = $extrapremi1;
        $coba['total_premi'] = $totalpremi1;

        $json['err_no'] = '0';
        $json['err_msg'] = 'Success';
        $json['data']=$coba;


    }else{

        $json['err_no'] = '1';
        $json['err_msg'] = 'Failed! Parameter is not valid';

    }
    echo json_encode($json);
}
else if($action=='get-medical')
{

	$userID 		= $_POST['user-id']; //
	$type 	= $_POST['type'];
	$idclient 	= $_POST['idclient'];
	$idproduk = $_POST['idproduk'];
	$usiatenor = $_POST['usiatenor'];
	$plafond = $_POST['plafond'];

	$deviceVersion 	= $_POST['deviceVersion'];

	$today = date('Y-m-d H:i:s');
	if($deviceVersion <> $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
	}

	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  id = '".$userID."'");
	$rowuser = mysql_fetch_array($queryuser);
	$iduser = $rowuser['id'];
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];

	$querySelect = "SELECT * FROM ajkmedical
WHERE idbroker = '".$idbro."'
AND idpartner = '".$idclient."'
AND idproduk = '".$idproduk."'
AND '".$usiatenor."' BETWEEN agefrom AND ageto
AND '".$plafond."' BETWEEN upfrom AND upto
AND del IS NULL";

	$result = query_db($querySelect);
	if($result)
	{
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$today = date('m');
			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';
			$json['data']=$row;
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Failed to retrieve data. Please try again.'.$querySelect;
		}
		echo json_encode($json);
	}else{
		$json['err_no'] = '0';
		$json['err_msg'] = 'Success';
		echo json_encode($json);
	}
}
else if($action == "check-debitur")
{

	require_once("input/check-debitur.php");

}
else if($action=="random-number"){
	echo generateRandomNumber(6);
}
else if($action=="gpstrack"){
    $nip = isset($_POST['nip'])? $_POST['nip']: $_GET['nip'];
    $user_id = isset($_POST['user_id'])? $_POST['user_id']: $_GET['user_id'];
    $latitude = isset($_POST['latitude'])? $_POST['latitude']: $_GET['latitude'];
    $longitude = isset($_POST['longitude'])? $_POST['longitude']: $_GET['longitude'];
    $imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
    $device_model = isset($_POST['device_model'])? $_POST['device_model']: $_GET['device_model'];
    $device_name = isset($_POST['device_name'])? $_POST['device_name']: $_GET['device_name'];
    $version_code = isset($_POST['version_code'])? $_POST['version_code']: $_GET['version_code'];
    $deviceVersion = isset($_POST['deviceVersion'])? $_POST['deviceVersion']: $_GET['deviceVersion'];
    $today = date('Y-m-d H:i:s');
    $hariini = date('Y-m-d');


    $querygps = mysql_query("SELECT * FROM ajkgps WHERE username = '".$user_id."' AND date_format(datettime,'%Y-%m-%d') = '".$hariini."'");
    if($resultgps = mysql_num_rows($querygps)>0){
        $query	= "UPDATE ajkgps SET longitude = '".$longitude."',latitude = '".$latitude."', datettime = '".$today."'
				WHERE username = '".$user_id."' AND date_format(datettime,'%Y-%m-%d') = '".$hariini."'";
    }else{
        $query	= "INSERT INTO ajkgps (username, longitude,latitude,phone_model,phone_name,imei,datettime)
				VALUES('".$user_id."','".$longitude."','".$latitude."','".$device_model."','".$device_name."','".$imei."','".$today."')";
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
}
else if($action == "add-debitur-jatim")
{

	require_once("input/add-debitur-jatim.php");

}
else if($action == "add-debitur-percepatan")
{

	require_once("input/add-debitur_1306.php");

}
else if($action=="sendtoken"){
    $nip = isset($_POST['nip'])? $_POST['nip']: $_GET['nip'];
    $usertoken = isset($_POST['usertoken'])? $_POST['usertoken']: $_GET['usertoken'];
    $imei = isset($_POST['imei'])? $_POST['imei']: $_GET['imei'];
    $model = isset($_POST['model'])? $_POST['model']: $_GET['model'];
    $device_name = isset($_POST['device_name'])? $_POST['device_name']: $_GET['device_name'];
    $version_code = isset($_POST['version_code'])? $_POST['version_code']: $_GET['version_code'];
    $deviceVersion = isset($_POST['deviceVersion'])? $_POST['deviceVersion']: $_GET['deviceVersion'];
    $today = date('Y-m-d H:i:s');
    $hariini = date('Y-m-d');
    $packagename = 'com.biosajk.marketing';

    //$quser = mysql_fetch_array(mysql_query("SELECT * FROM useraccess WHERE id =  '$nip'"));

    $cektoken = mysql_query("SELECT * FROM user_mobile_token WHERE UserImei = '$imei' AND packagename = '$packagename'");
    $tokenrow = mysql_num_rows($cektoken);
    if($tokenrow == 0){
        mysql_query("INSERT INTO user_mobile_token (UserID, UserToken, UserImei, deviceModel, deviceName,packagename, inputdate) VALUES('".$nip."','$usertoken','".$imei."','".$model."','".$device_name."','$packagename','$today')");
    }else{
        mysql_query("UPDATE user_mobile_token SET UserToken = '$usertoken', UserID = '$nip', updatedate = '$today' WHERE UserImei = '$imei' AND packagename = '$packagename'");
    }


    $result = query_db($query);
}
else
{
	$json['err_no'] = '1';
	$json['err_msg'] = 'No action found.'.$action;

	echo json_encode($json);
}

function formatSPAKNo($input, $idprod){

	if(strlen($idprod<10)){
		$noprod = '0'.$idprod;
	}else{
		$noprod = $idprod;
	}
	$year = date("y");
	if(strlen($input)<5){
		$cur_length = strlen($input);
		$dif = 4-$cur_length;
		$zero = "";
		for($i=0;$i<$dif;$i++){
			$zero .= "0" ;
		}
		return "M".$year.$noprod.$zero.$input;
	}else{
		return "M".$year.$noprod.$input;
	}
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
	$foldername = date("y",strtotime(date('Y-m-d'))).date("m",strtotime(date('Y-m-d')));
	$path = 'uploads/'.$foldername;

	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}
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
		$filepath = $path . "/" . $encrypted_name.".".$ext;

		if (move_uploaded_file($_FILES[$postKeyword]['tmp_name'], $filepath)) {
			$urlphoto = "/" . $encrypted_name . "." . $ext;
		}
		// chmod($urlphoto, 755);
	}


	return $filepath;

}
function _sendnotif($registatoin_ids, $data) {
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

function imagerotation($file){
	$image = imagecreatefromstring(file_get_contents($file));
	$exif = exif_read_data($file);
	if(!empty($exif['Orientation'])) {
		switch($exif['Orientation']) {
			case 8:
				$image = imagerotate($image,270,0);
				break;
			case 3:
				$image = imagerotate($image,180,0);
				break;
			case 6:
				$image = imagerotate($image,90,0);
				break;
		}
	}
}
?>
