<?php

$userID 	 = $_POST['user-id'];
$usersType	 = $_POST['type']; // Privilege user
$privilege 	 = $_POST['privilege'];
$nmproduk	 = $_POST['nmproduk'];
$groupID	 = $_POST['groupID'];
$cabang 	 = $_POST['cabang']; // not mandatory (for kadiv wilayah/regional)
$deviceVersion = $_POST['deviceVersion'];

$cancel =false;
if($userID=="" || $usersType=="" || $privilege=="")
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


if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{

$querySelect1 = "
				SELECT SUM(JmlKematian) AS total_kematian, JmlKematian, regional, nmproduk, namapenyakit
				FROM(
				SELECT np.namapenyakit, COUNT(k.id) AS JmlKematian, 
				pr.regional, po.nmproduk
				FROM fu_ajk_peserta pr, fu_ajk_klaim k, fu_ajk_polis po, fu_ajk_namapenyakit np
				WHERE po.nmproduk = '$nmproduk'
				AND pr.id_peserta = k.id_peserta
				AND k.sebab_meninggal = np.id
				AND pr.id_polis = po.id 
				AND pr.id_cost = '$groupID'
				GROUP BY np.namapenyakit, pr.regional, po.nmproduk
				) AS deathly
				GROUP BY regional, namapenyakit, nmproduk
				ORDER BY total_kematian DESC
				";

$querySelect2 = "
				SELECT
				namapenyakit,
				nmproduk,
				SUM(JmlKematian) AS total_all_cabang
				FROM (
				$querySelect1
				) AS all_deathly GROUP BY regional
				"; 
				$result1 		= query_db($querySelect1);
				$result2 		= query_db($querySelect2);
				$checkrows = mysql_num_rows($result1);
				if ($checkrows > 0){
					while ($getData = mysql_fetch_assoc($result1))
					{
						//$data1['data'][] = $getData;
						$data1['data'][] = array(
											'regional'		=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
											'cabang'		=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
											'nm_produk'		=> is_null($getData['nmproduk']) ? 'LAINNYA' : $getData['nmproduk'],
											'sebab_meninggal'=> is_null($getData['namapenyakit']) ? 'LAINNYA' : $getData['namapenyakit'],
											'jml_kematian'=> is_null($getData['JmlKematian']) ? '0' : $getData['JmlKematian']
											
										  );
					}
					
					while ($getData1 = mysql_fetch_assoc($result2))
					{
						//$data1['data'][] = $getData;
						$data1['total'][] = array(
											'nm_produk'		=> is_null($getData1['nmproduk']) ? 'LAINNYA' : $getData1['nmproduk'],
											'sebab_meninggal'=> is_null($getData1['namapenyakit']) ? 'LAINNYA' : $getData1['namapenyakit'],
											'jml_kematian'=> is_null($getData1['total_all_cabang']) ? 'LAINNYA' : $getData1['total_all_cabang']
											
										  );
					}
					
				}else{
					$data1 = array();
				}

				if($result1)
				{
					// $test['data'][] = array($data;
					echo json_encode($data1);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}
} else {
		$querySelect = "SELECT SUM(JmlKematian) AS total_kematian, JmlKematian, cabang, regional, nmproduk, namapenyakit
						FROM(
						SELECT np.namapenyakit, COUNT(k.id) AS JmlKematian, 
						pr.cabang, pr.regional, po.nmproduk
						FROM fu_ajk_peserta pr, fu_ajk_klaim k, fu_ajk_polis po, fu_ajk_namapenyakit np
						WHERE po.nmproduk = '$nmproduk'
						AND pr.id_peserta = k.id_peserta
						AND k.sebab_meninggal = np.id
						AND pr.id_polis = po.id 
						AND pr.regional = '$cabang'
						AND pr.id_cost = '$groupID'
						GROUP BY np.namapenyakit, pr.cabang, po.nmproduk
						) AS deathly
						GROUP BY namapenyakit,cabang,nmproduk
						ORDER BY total_kematian DESC
						";

$querySelect2 = "
				SELECT
				namapenyakit,
				nmproduk,
				SUM(JmlKematian) AS total_all_cabang
				FROM (
				$querySelect
				) AS all_deathly GROUP BY cabang
				"; 
				$result 		= query_db($querySelect);
				$result2 		= query_db($querySelect2);
				
				while ($getData = mysql_fetch_assoc($result))
				{
					//$data['data'][] = $getData;
					$data['data'][] = array(
										'regional'		=> is_null($getData['regional']) ? 'LAINNYA' : $getData['regional'],
										'cabang'		=> is_null($getData['cabang']) ? 'LAINNYA' : $getData['cabang'],
										'nm_produk'		=> is_null($getData['nmproduk']) ? 'LAINNYA' : $getData['nmproduk'],
										'sebab_meninggal'=> is_null($getData['namapenyakit']) ? 'LAINNYA' : $getData['namapenyakit'],
										'jml_kematian'=> is_null($getData['JmlKematian']) ? 'LAINNYA' : $getData['JmlKematian']
									  );
				}

				while ($getData1 = mysql_fetch_assoc($result2))
					{
						//$data1['data'][] = $getData;
						$data['total'][] = array(
											'nm_produk'		=> is_null($getData1['nmproduk']) ? 'LAINNYA' : $getData1['nmproduk'],
											'sebab_meninggal'=> is_null($getData1['namapenyakit']) ? 'LAINNYA' : $getData1['namapenyakit'],
											'jml_kematian'=> is_null($getData1['total_all_cabang']) ? 'LAINNYA' : $getData1['total_all_cabang']
											
										  );
					}

				if($result)
				{
					// $test['data'][] = array($data;
					echo json_encode($data);
				}else {
					$json['err_no'] = '1';
					$json['err_msg'] = 'Error occured. Please try again.';
					echo json_encode($json);
				}
}

?>