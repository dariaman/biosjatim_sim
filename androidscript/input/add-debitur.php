<?php

	$userID 		= $_POST['user-id']; //
	//Only Marketing use this
	$idbank			= $_POST['idbank'];
	$idbank			= '1';
	$idmitra		= $_POST['idmitra'];
	$id_polis		= $_POST['id_polis'];
	$nmproduk 		= $_POST['nmproduk'];
	$nama 			= $_POST['nama'];
	$jns_kelamin 	= $_POST['jns_kelamin'];
	$dob 			= $_POST['dob']; //date-of-birtday
	$noidentitas 	= $_POST['noidentitas'];
	$alamat 		= $_POST['alamat'];
	$pekerjaan 		= $_POST['pekerjaan'];
	$plafond 		= $_POST['plafond']; // jumlah pinjaman
	$tenor 			= $_POST['tenor']; // jangka waktu pinjaman
	if($id_polis=="11" OR $id_polis =="12"){
		$mpp 			= $_POST['mpp'];
	}else{
		$mpp = null;
	}

	$question1		= $_POST['question1'];
	$question2		= $_POST['question2'];
	$question3		= $_POST['question3'];
	$question4		= $_POST['question4'];
	$nm_almt_rs		= $_POST['nm_almt_rs'];
	$nm_dokter		= $_POST['nm_dokter'];
	$almt_dokter	= $_POST['almt_dokter'];
	$deviceVersion 	= $_POST['deviceVersion'];

	//$test = "temp_photo_FullBodyPhoto.jpg";
	//$filefotofullbody = $_POST['filefotofullbody'];
	$filefotodebitursatuLoc = changePicture('filefotodebitursatu',$userID.$noidentitas);
	$filefotoktpLoc 		= changePicture('filefotoktp',$userID.$noidentitas);
	$filettddebiturLoc 		= changePicture('filettddebitur',$userID.$noidentitas);
	$filettdmarketingLoc 	= changePicture('filettdmarketing',$userID.$noidentitas);
	$filefotoskpensiunLoc 	= changePicture('filefotoskpensiun',$userID.$noidentitas);
	//$filefotoktp = $_POST['filefotoktp'];
	//$filettddebitur = $_POST['filettddebitur'];
	//$filettdmarketing = $_POST['filettdmarketing'];
	//TODO get all data and insert to database

	$cancel =false;
	if($userID=="" || $nama =="" || $jns_kelamin=="" ||$dob=="" || $noidentitas=="" || $alamat=="" || $pekerjaan==""|| $plafond==""||$tenor=="")
	{
		$cancel=true;
	}
	if($question1 =="" || $question2 == "" || $question3 =="" || $question4 =="")
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


	if($deviceVersion <= $versionavailable)
	{
		$json['err_no']  = '20';
		$json['err_msg'] = 'You need to upgrade your device!!!';

		echo json_encode($json);
		die();
	}
	/*-EOF-*/

	$queryUserID 	= "SELECT id,cabang FROM user_mobile WHERE id='$userID'";
	$resultUserID 	= query_db($queryUserID);
	$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
	$userCabang 	= $encodedUserID['cabang'];
	$encodedUserid 	= $encodedUserID['id'];

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

	$cek_query_pst = "SELECT * FROM fu_ajk_peserta WHERE nama = '$nama' AND tgl_lahir = '$dob' AND kredit_jumlah = '$plafond' AND cabang = '$namaCabang'";
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

		if($nmproduk == 'PERCEPATAN'){
		$next_spak_no = formatSPAKNoSKKT($next_spak_id);
		}else{
			$next_spak_no = formatSPAKNo($next_spak_id);
		}

		$query_spak  = "INSERT INTO fu_ajk_spak (id, id_cost, id_polis, id_mitra, spak, input_date, input_by)
		VALUES('$next_spak_id', '$idbank', '$id_polis', '$idmitra','$next_spak_no', NOW(), '$encodedUserid')";
		$result_spak = query_db($query_spak);

		$query_skkt  = "
						INSERT INTO fu_ajk_skkt (id_cost, id_mitra, id_polis, id_spak, kode_spak, question_1, question_2, nm_almt_rs, question_3, nm_dokter, almt_dokter, question_4, created_at, input_by)
						VALUES('$idbank', '$idmitra', '$id_polis', '$next_spak_id', '$next_spak_no', '$question1', '$question2', '$nm_almt_rs', '$question3', '$nm_dokter', '$almt_dokter', '$question4', NOW(), '$encodedUserid')";
		$result_skkt = query_db($query_skkt);

		//insert to form table
		$id ="SELECT MAX(id) AS ID FROM fu_ajk_spak_form_temp LIMIT 1";

		//INSERT INTO fu_ajk_spak_Form_Temp (id,idspk) VALUES(2,2)
		$result = query_db($id);

		if(mysql_num_rows($result)>0){
			$row 	=  mysql_fetch_assoc($result);
			$nextID =$row['ID']+1;
		}else{
			$nextID =1;
		}

		$token = generateRandomNumber(6);

		$query = "INSERT INTO fu_ajk_spak_form_temp ";
		$query .="(id, idcost, idspk, nama, jns_kelamin, dob, noidentitas, alamat, pekerjaan, plafond, tenor, filefotodebitursatu, ";

		$query .="filefotoktp, filettddebitur, filettdmarketing, filefotoskpensiun, token ,mpp, cabang, input_by, input_date) ";
		$query .="VALUES ('$nextID', '$idbank', '$next_spak_id', '$nama', '$jns_kelamin', '$dob', '$noidentitas', '$alamat', '$pekerjaan', '$plafond', '$tenor', ";
		$query .="'$filefotodebitursatuLoc', '$filefotoktpLoc', '$filettddebiturLoc', '$filettdmarketingLoc', '$filefotoskpensiunLoc', '$token','$mpp', '$userCabang','$encodedUserid', NOW())" ;//CONTINUE...

		$result = query_db($query);
	 // result return true if success
		if($result)
		{
			$json['token']=$token;
			$json['no_spk'] = $next_spak_no;
			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';

			echo json_encode($json);
		}
		else
		{
			$json['err_no'] = '1';
			$json['err_msg'] = 'Error occured. Please try again.';

			echo json_encode($json);
		}

		// if($nmproduk == 'PERCEPATAN'){

			// $query = "INSERT INTO fu_ajk_spak_form_temp ";
			// $query .="(id, idcost, idspk, nama, jns_kelamin, dob, noidentitas, alamat, pekerjaan, plafond, tenor, filefotodebitursatu, ";

			// $query .="filefotoktp, filettddebitur, filettdmarketing, filefotoskpensiun, cabang, input_by, input_date) ";
			// $query .="VALUES ('$nextID', '$idbank', '$next_spak_id', '$nama', '$jns_kelamin', '$dob', '$noidentitas', '$alamat', '$pekerjaan', '$plafond', '$tenor', ";
			// $query .="'$filefotodebitursatuLoc', '$filefotoktpLoc', '$filettddebiturLoc', '$filettdmarketingLoc', '$filefotoskpensiunLoc', '$userCabang','$encodedUserid', NOW())" ;//CONTINUE...
		// }else{
			// $token = generateRandomNumber(6);

			// $query = "INSERT INTO fu_ajk_spak_form_temp ";
			// $query .="(id, idcost, idspk, nama, jns_kelamin, dob, noidentitas, alamat, pekerjaan, plafond, tenor, filefotodebitursatu, ";

			// $query .="filefotoktp, filettddebitur, filettdmarketing, filefotoskpensiun, token , cabang, input_by, input_date) ";
			// $query .="VALUES ('$nextID', '$idbank', '$next_spak_id', '$nama', '$jns_kelamin', '$dob', '$noidentitas', '$alamat', '$pekerjaan', '$plafond', '$tenor', ";
			// $query .="'$filefotodebitursatuLoc', '$filefotoktpLoc', '$filettddebiturLoc', '$filettdmarketingLoc', '$filefotoskpensiunLoc', '$token', '$userCabang','$encodedUserid', NOW())" ;//CONTINUE...
		// }



		// $result = query_db($query);
	 // // result return true if success
		// if($result)
		// {
			// if($nmproduk == 'PERCEPATAN'){
				// $json['no_spk'] = $next_spak_no;
				// $json['err_no'] = '0';
				// $json['err_msg'] = 'Success';

				// echo json_encode($json);
			// }else{
				// $json['token']=$token;
				// $json['no_spk'] = $next_spak_no;
				// $json['err_no'] = '0';
				// $json['err_msg'] = 'Success';

				// echo json_encode($json);
			// }
		// }
		// else
		// {
			// $json['err_no'] = '1';
			// $json['err_msg'] = 'Error occured. Please try again.';

			// echo json_encode($json);
		// }

	}

?>