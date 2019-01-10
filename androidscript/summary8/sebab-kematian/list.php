<?php

	$usersType	 	= $_POST['type'];
	$privilege 		= $_POST['privilege'];
	$cabang 	 	= $_POST['cabang'];
	$groupID 	 	= $_POST['groupID'];
	$deviceVersion 	= $_POST['deviceVersion'];

	$cancel =false;
	if($usersType=="" || $privilege=="")
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

if($usersType == "Direksi_GM" || $privilege == "2" || $privilege == "3")
{
	$querySelect = "SELECT
					po.nmproduk
					FROM fu_ajk_peserta pr, fu_ajk_klaim k, fu_ajk_polis po, fu_ajk_namapenyakit np, fu_ajk_grupproduk gp
					WHERE pr.id_cost = '$groupID' 
					AND pr.id_peserta = k.id_peserta
					AND k.sebab_meninggal = np.id
					AND pr.id_polis = po.id 
					AND pr.nama_mitra = gp.id 
					GROUP BY po.nmproduk";

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
	
}else{
	
	$querySelect = "SELECT
					po.nmproduk
					FROM fu_ajk_peserta pr, fu_ajk_klaim k, fu_ajk_polis po, fu_ajk_namapenyakit np, fu_ajk_grupproduk gp
					WHERE pr.id_cost = '$groupID' 
					AND pr.regional = '$cabang' 
					AND pr.id_peserta = k.id_peserta
					AND k.sebab_meninggal = np.id
					AND pr.id_polis = po.id 
					AND pr.nama_mitra = gp.id 
					GROUP BY po.nmproduk";

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

?>