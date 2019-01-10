<?php
	$userID 		= $_POST['user-id']; //
	//Only Marketing use this
	$nmproduk 		= $_POST['nmproduk'];
	$nama 			= $_POST['nama'];
	$jns_kelamin 	= $_POST['jns_kelamin'];
	$dob 			= $_POST['dob']; //date-of-birtday
	$noidentitas 	= $_POST['noidentitas'];
	$alamat 		= $_POST['alamat'];
	$pekerjaan 		= $_POST['pekerjaan'];
	$plafond 		= $_POST['plafond']; // jumlah pinjaman
	$tenor 			= $_POST['tenor']; // jangka waktu pinjaman
	$deviceVersion 	= $_POST['deviceVersion'];

	$umur = birthday($dob,date("Y-m-d"));
	// $from 	= new DateTime($dob);
	// $to   	= new DateTime('today');
	// $age 	= $from->diff($to)->y;


	// $date_now = date_create('Y-m-d');
	// $interval = date_diff($date_now, $dob);
	// $age = $interval->format("%y");


	$filefotodebitursatuLoc = $_FILES['filefotodebitursatu']['name'];
	$filefotoktpLoc 		= $_FILES['filefotoktp']['name'];
	$filettddebiturLoc 		= $_FILES['filettddebitur']['name'];
	$filettdmarketingLoc 	= $_FILES['filettdmarketing']['name'];
	$filefotoskpensiunLoc 	= $_FILES['filefotoskpensiun']['name'];

	//TODO get all data and insert to database

	$cancel =false;
	if($userID=="" || $nama =="" || $jns_kelamin=="" ||$dob=="" || $noidentitas=="" || $alamat=="" || $pekerjaan==""|| $plafond==""||$tenor=="")
	{
		$cancel=true;
	}
	if($filefotodebitursatuLoc=="" || $filefotoktpLoc == "" || $filettddebiturLoc=="" || $filettdmarketingLoc=="" ||$filefotoskpensiunLoc =="")
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

	/*
	* Check Android Version
	*/
	if($deviceVersion <= 1.0)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}
	/*-EOF-*/



	$queryUserID 	= "SELECT id,cabang FROM user_mobile WHERE md5(id)='$userID'";
	$resultUserID 	= query_db($queryUserID);
	$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
	$userCabang 	= $encodedUserID['cabang'];
	$encodedUserID 	= $encodedUserID['id'];

	$queryCabang 	= "SELECT id,name FROM fu_ajk_cabang WHERE id = '$userCabang'";
	$resultCabang 	= query_db($queryCabang);
	$encodedCabang 	=  mysql_fetch_assoc($resultCabang);
	$namaCabang 	= $encodedCabang['cabang'];
	$CabangID 		= $encodedCabang['id'];

	//Insert to spak table
	$query_last_spak 	= "SELECT MAX(id) as max FROM fu_ajk_spak";
	$result_last_spak 	= query_db($query_last_spak);
	if(mysql_num_rows($result_last_spak)>0){
		$row 			=  mysql_fetch_assoc($result_last_spak);
		$next_spak_id 	=$row['max']+1;
	}else{
		$next_spak_id	=1;
	}

	$cek_query_pst 	= "SELECT * FROM fu_ajk_peserta WHERE nama = '$nama' AND tgl_lahir = '$dob' AND kredit_jumlah = '$plafond' AND cabang = '$namaCabang'";
	$cek_query 		= "SELECT * FROM fu_ajk_spak_form WHERE nama = '$nama' AND dob = '$dob' AND plafond = '$plafond' AND cabang = '$userCabang'";
	$cek_query_temp = "SELECT * FROM fu_ajk_spak_form_temp WHERE nama = '$nama' AND dob = '$dob' AND plafond = '$plafond' AND cabang = '$userCabang'";

	$result = query_db($cek_query);
	$result_pst = query_db($cek_query_pst);
	$result_temp = query_db($cek_query_temp);
	$checkrows = mysql_num_rows($result);
	$checkrows_pst = mysql_num_rows($result_pst);
	$checkrows_temp = mysql_num_rows($result_temp);
	if ($checkrows > 0 || $checkrows_pst > 0 || $checkrows_temp > 0){
	// if ($checkrows > 0){

		$json['err_no'] = '3';
		$json['err_msg'] = 'Data Exist';

		echo json_encode($json);

	}else{

		$query_percepatan = "
					SELECT * FROM fu_ajk_polis WHERE nmproduk = '$nmproduk'
					";

		$result_percepatan = query_db($query_percepatan);
		$row_pct = mysql_fetch_assoc($result_percepatan);

		$id_polis = $row_pct['id'];
		$type_produk = $row_pct['typeproduk'];

		// $query = "
					// SELECT * FROM fu_ajk_medical
					// WHERE $plafond BETWEEN si_from AND si_to
					// AND $age BETWEEN age_from AND age_to
					// AND id_polis = '$id_polis'
				// ";
/*
		$query = "
					SELECT * FROM fu_ajk_medical
					WHERE $plafond BETWEEN si_from AND si_to
					AND DATEDIFF(CURDATE(),'$dob')/365 BETWEEN age_from AND age_to
					AND id_polis = '$id_polis'
				";
*/
		//modif by satrya 160906
		$query = "
					SELECT * FROM fu_ajk_medical
					WHERE $plafond BETWEEN si_from AND si_to
					AND $umur BETWEEN age_from AND age_to
					AND id_polis = '$id_polis'
				";

		$result = query_db($query);
		$row = mysql_fetch_assoc($result);

		if($result)
		{

			$json['id_polis']		= $id_polis;
			$json['type_medical'] 	= is_null($row['type_medical']) ? '0' : $row['type_medical'];
			$json['err_no'] 		= '0';
			$json['err_msg'] 		= 'Success';

			echo json_encode($json);
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Error occured. Please try again.';

			echo json_encode($json);
		}
	}

?>