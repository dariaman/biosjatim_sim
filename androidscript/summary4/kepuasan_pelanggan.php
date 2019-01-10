<?php

$userID 	 = $_POST['user-id'];
$deviceVersion = $_POST['deviceVersion'];
$typeUser	= $_POST['typeUser'];

$cancel =false;
if($userID=="" )
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


$queryUserID 	= "SELECT type FROM user_mobile WHERE md5(id)='$userID'";
$resultUserID 	= query_db($queryUserID);
$encodedUserID 	=  mysql_fetch_assoc($resultUserID);

$userType 		= $encodedUserID['type'];


if($typeUser == "Marketing" || $typeUser == "")
{

	$querySelect = "SELECT 	nama,name,
							buruk 		AS B,
							tidak_puas 	AS TP,
							cukup_puas 	AS CP,
							puas 		AS P,
							sangat_puas AS SP,
							jumlah_aplikasi,
							bobot_nilai,
							(((jumlah_aplikasi * 6) / 90 ) * 100 )AS persen
							FROM 
								(SELECT r.nama,cb.name,r.buruk,r.tidak_puas,r.cukup_puas,r.puas,r.sangat_puas,
									r.buruk+r.tidak_puas+r.cukup_puas+r.puas+r.sangat_puas AS jumlah_aplikasi,
										 (r.buruk*0)+(r.tidak_puas*1)+(r.cukup_puas*2)+(r.puas*4)+(r.sangat_puas*6) AS bobot_nilai
								 FROM rating r, fu_ajk_cabang cb
								 WHERE r.type = '$userType'
								 AND r.cabang = cb.id
								 ) AS summary_kepuasan";

	$result 		= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		//$data['data'][] = $getData;
		$bn = $getData['persen'];
		if(($bn >= '0') && ($bn <= '15')){
			$kepuasan = "Buruk";
		}else if(($bn >= '16') && ($bn <= '35')){
			$kepuasan = "Tidak Puas";
		}else if(($bn >= '36') && ($bn <= '60')){
			$kepuasan = "Cukup Puas";
		}else if(($bn >= '61') && ($bn <= '90')){
			$kepuasan = "Puas";
		}else if(($bn >= '91') && ($bn <= '100')){
			$kepuasan = "Sangat Puas";
		}
		$data['data'][] = array(
								'nama'			=> is_null($getData['nama']) ? 'Unknown' : $getData['nama'],
								'cabang'		=> is_null($getData['name']) ? 'Unknown' : $getData['name'],
								'B'				=> is_null($getData['B']) ? '0' : $getData['B'],
								'TP'			=> is_null($getData['TP']) ? '0' : $getData['TP'],
								'CP'			=> is_null($getData['CP']) ? '0' : $getData['CP'],
								'P'				=> is_null($getData['P']) ? '0' : $getData['P'],
								'SP'			=> is_null($getData['SP']) ? '0' : $getData['SP'],
								'jumlah_aplikasi'=> is_null($getData['jumlah_aplikasi']) ? '0' : $getData['jumlah_aplikasi'],
								'bobot_nilai'	=> is_null($getData['bobot_nilai']) ? '0' : $getData['bobot_nilai'],
								'kepuasan'		=> is_null($kepuasan) ? '-' : $kepuasan
							);
	}

	if ($result) {
		echo json_encode($data);
	}
	else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}


} else if ($typeUser == "Dokter" || $typeUser == "") {
	$querySelect = "SELECT 	nama,name,
							buruk 		AS B,
							tidak_puas 	AS TP,
							cukup_puas 	AS CP,
							puas 		AS P,
							sangat_puas AS SP,
							jumlah_aplikasi,
							bobot_nilai,
							(((jumlah_aplikasi * 6) / 90 ) * 100 )AS persen
							FROM 
								(SELECT r.nama,cb.name,r.buruk,r.tidak_puas,r.cukup_puas,r.puas,r.sangat_puas,
									r.buruk+r.tidak_puas+r.cukup_puas+r.puas+r.sangat_puas AS jumlah_aplikasi,
										 (r.buruk*0)+(r.tidak_puas*1)+(r.cukup_puas*2)+(r.puas*4)+(r.sangat_puas*6) AS bobot_nilai
								 FROM rating r, fu_ajk_cabang cb
								 WHERE r.type = '$userType'
								 AND r.cabang = cb.id
								 ) AS summary_kepuasan";


	$result 		= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		//$data['data'][] = $getData;
		$bn = $getData['persen'];
		if(($bn >= '0') && ($bn <= '15')){
			$kepuasan = "Buruk";
		}else if(($bn >= '16') && ($bn <= '35')){
			$kepuasan = "Tidak Puas";
		}else if(($bn >= '36') && ($bn <= '60')){
			$kepuasan = "Cukup Puas";
		}else if(($bn >= '61') && ($bn <= '90')){
			$kepuasan = "Puas";
		}else if(($bn >= '91') && ($bn <= '100')){
			$kepuasan = "Sangat Puas";
		}
		$data['data'][] = array(
								'nama'			=> is_null($getData['nama']) ? 'Unknown' : $getData['nama'],
								'cabang'		=> is_null($getData['name']) ? 'Unknown' : $getData['name'],
								'B'				=> is_null($getData['B']) ? '0' : $getData['B'],
								'TP'			=> is_null($getData['TP']) ? '0' : $getData['TP'],
								'CP'			=> is_null($getData['CP']) ? '0' : $getData['CP'],
								'P'				=> is_null($getData['P']) ? '0' : $getData['P'],
								'SP'			=> is_null($getData['SP']) ? '0' : $getData['SP'],
								'jumlah_aplikasi'=> is_null($getData['jumlah_aplikasi']) ? '0' : $getData['jumlah_aplikasi'],
								'bobot_nilai'	=> is_null($getData['bobot_nilai']) ? '0' : $getData['bobot_nilai'],
								'kepuasan'		=> is_null($kepuasan) ? '-' : $kepuasan
							);
	}

	if ($result) {
		echo json_encode($data);
	}
	else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

} else if ($typeUser == "Direksi_GM" || $typeUser == "Dokter") {
	$querySelect = "SELECT 	nama,name,
							buruk 		AS B,
							tidak_puas 	AS TP,
							cukup_puas 	AS CP,
							puas 		AS P,
							sangat_puas AS SP,
							jumlah_aplikasi,
							bobot_nilai,
							(((jumlah_aplikasi * 6) / 90 ) * 100 )AS persen
							FROM 
								(SELECT r.nama,cb.name,r.buruk,r.tidak_puas,r.cukup_puas,r.puas,r.sangat_puas,
									r.buruk+r.tidak_puas+r.cukup_puas+r.puas+r.sangat_puas AS jumlah_aplikasi,
										 (r.buruk*0)+(r.tidak_puas*1)+(r.cukup_puas*2)+(r.puas*4)+(r.sangat_puas*6) AS bobot_nilai
								 FROM rating r, fu_ajk_cabang cb
								 WHERE r.type = '$userType'
								 AND r.cabang = cb.id
								 ) AS summary_kepuasan";


	$result 		= query_db($querySelect);
	while ($getData = mysql_fetch_assoc($result))
	{
		$bn = $getData['persen'];
		if(($bn >= '0') && ($bn <= '15')){
			$kepuasan = "Buruk";
		}else if(($bn >= '16') && ($bn <= '35')){
			$kepuasan = "Tidak Puas";
		}else if(($bn >= '36') && ($bn <= '60')){
			$kepuasan = "Cukup Puas";
		}else if(($bn >= '61') && ($bn <= '90')){
			$kepuasan = "Puas";
		}else if(($bn >= '91') && ($bn <= '100')){
			$kepuasan = "Sangat Puas";
		}
		$data['data'][] = array(
								'nama'			=> is_null($getData['nama']) ? 'Unknown' : $getData['nama'],
								'cabang'		=> is_null($getData['name']) ? 'Unknown' : $getData['name'],
								'B'				=> is_null($getData['B']) ? '0' : $getData['B'],
								'TP'			=> is_null($getData['TP']) ? '0' : $getData['TP'],
								'CP'			=> is_null($getData['CP']) ? '0' : $getData['CP'],
								'P'				=> is_null($getData['P']) ? '0' : $getData['P'],
								'SP'			=> is_null($getData['SP']) ? '0' : $getData['SP'],
								'jumlah_aplikasi'=> is_null($getData['jumlah_aplikasi']) ? '0' : $getData['jumlah_aplikasi'],
								'bobot_nilai'	=> is_null($getData['bobot_nilai']) ? '0' : $getData['bobot_nilai'],
								'kepuasan'		=> is_null($kepuasan) ? '-' : $kepuasan
							);
	}

	if ($result) {
		echo json_encode($data);
	}
	else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

} else if ($typeUser == "Direksi_GM" || $typeUser == "Marketing") {
	$querySelect = "SELECT 	nama,name,
							buruk 		AS B,
							tidak_puas 	AS TP,
							cukup_puas 	AS CP,
							puas 		AS P,
							sangat_puas AS SP,
							jumlah_aplikasi,
							bobot_nilai,
							(((jumlah_aplikasi * 6) / 90 ) * 100 )AS persen
							FROM 
								(SELECT r.nama,cb.name,r.buruk,r.tidak_puas,r.cukup_puas,r.puas,r.sangat_puas,
									r.buruk+r.tidak_puas+r.cukup_puas+r.puas+r.sangat_puas AS jumlah_aplikasi,
										 (r.buruk*0)+(r.tidak_puas*1)+(r.cukup_puas*2)+(r.puas*4)+(r.sangat_puas*6) AS bobot_nilai
								 FROM rating r, fu_ajk_cabang cb
								 WHERE r.type = '$userType'
								 AND r.cabang = cb.id
								 ) AS summary_kepuasan";


	$result 		= query_db($querySelect);
	while ($row = mysql_fetch_assoc($result))
	{
		
		$bn = $getData['persen'];
		if(($bn >= '0') && ($bn <= '15')){
			$kepuasan = "Buruk";
		}else if(($bn >= '16') && ($bn <= '35')){
			$kepuasan = "Tidak Puas";
		}else if(($bn >= '36') && ($bn <= '60')){
			$kepuasan = "Cukup Puas";
		}else if(($bn >= '61') && ($bn <= '90')){
			$kepuasan = "Puas";
		}else if(($bn >= '91') && ($bn <= '100')){
			$kepuasan = "Sangat Puas";
		}
		//$data['data'][] = $getData;
		$data['data'][] = array(
								'nama'			=> is_null($getData['nama']) ? 'Unknown' : $getData['nama'],
								'cabang'		=> is_null($getData['name']) ? 'Unknown' : $getData['name'],
								'B'				=> is_null($getData['B']) ? '0' : $getData['B'],
								'TP'			=> is_null($getData['TP']) ? '0' : $getData['TP'],
								'CP'			=> is_null($getData['CP']) ? '0' : $getData['CP'],
								'P'				=> is_null($getData['P']) ? '0' : $getData['P'],
								'SP'			=> is_null($getData['SP']) ? '0' : $getData['SP'],
								'jumlah_aplikasi'=> is_null($getData['jumlah_aplikasi']) ? '0' : $getData['jumlah_aplikasi'],
								'bobot_nilai'	=> is_null($getData['bobot_nilai']) ? '0' : $getData['bobot_nilai'],
								'kepuasan'		=> is_null($kepuasan) ? '-' : $kepuasan
							);
	}

	if ($result) {
		echo json_encode($data);
	}
	else {
		$json['err_no'] = '1';
		$json['err_msg'] = 'Error occured. Please try again.';
		echo json_encode($json);
	}

} else {
		$json['err_no'] = '3';
		$json['err_msg'] = 'User type is not Marketing or Dokter';
		echo json_encode($json);
}

?>