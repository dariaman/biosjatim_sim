<?php

	$userID 		= $_POST['user-id']; //
	$idbro			= $_POST['idbro'];
	$idbank			= $_POST['idbank'];
	$idproduk		= $_POST['idproduk'];
	$idmitra		= $_POST['idmitra'];
	$nama 			= $_POST['nama'];
	$jns_kelamin 	= $_POST['jns_kelamin'];
	$dob 			= $_POST['dob']; //date-of-birtday
	$noidentitas 	= $_POST['noidentitas'];
	$alamat 		= $_POST['alamat'];
	$pekerjaan 		= $_POST['pekerjaan'];
	$plafond 		= $_POST['plafond']; // jumlah pinjaman
	$tenor 			= $_POST['tenor']; // jangka waktu pinjaman
	$deviceVersion 	= $_POST['deviceVersion'];
	$asuransi 	    = $_POST['asuransi'];

	$today = date('Y-m-d H:i:s');

	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = '../androidscript/myFiles/_ajk/'.$foldername;

	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}
/*
	$filefotodebitursatuLoc = changePicture('filefotodebitursatu',$userID.$noidentitas);
	$filefotoktpLoc 		= changePicture('filefotoktp',$userID.$noidentitas);
	$filettddebiturLoc 		= changePicture('filettddebitur',$userID.$noidentitas);
	$filettdmarketingLoc 	= changePicture('filettdmarketing',$userID.$noidentitas);
	$filefotoskpensiunLoc 	= changePicture('filefotoskpensiun',$userID.$noidentitas);
*/


	$filefotodebitursatuLoc = $_FILES['filefotodebitursatu']['name'];
	$arr = explode("." , $filefotodebitursatuLoc);
	$ext = $arr[count($arr)-1];
	$filefotodebitursatuLoc = str_replace(" ","_" , $nama).'_'.uniqid().".".$ext;
	$filefotodebitursatuLoc_temp = $_FILES['filefotodebitursatu']['tmp_name'];
	imagerotation($filefotodebitursatuLoc_temp);
	$filefotodebitursatuLoc_des		= $path.'/'.$filefotodebitursatuLoc;
	move_uploaded_file($filefotodebitursatuLoc_temp,$filefotodebitursatuLoc_des);

	$filefotoktpLoc = $_FILES['filefotoktp']['name'];
	$arr = explode("." , $filefotoktpLoc);
	$ext = $arr[count($arr)-1];
	$filefotoktpLoc = str_replace(" ","_" , $nama).'_'.uniqid().".".$ext;
	$filefotoktpLoc_temp = $_FILES['filefotoktp']['tmp_name'];
	imagerotation($filefotoktpLoc_temp);
	$filefotoktpLoc_des		= $path.'/'.$filefotoktpLoc;
	move_uploaded_file($filefotoktpLoc_temp,$filefotoktpLoc_des);

	$filettddebiturLoc = $_FILES['filettddebitur']['name'];
	$arr = explode("." , $filettddebiturLoc);
	$ext = $arr[count($arr)-1];
	$filettddebiturLoc = str_replace(" ","_" , $nama).'_'.uniqid().".".$ext;
	$filettddebiturLoc_temp = $_FILES['filettddebitur']['tmp_name'];
	$filettddebiturLoc_des		= $path.'/'.$filettddebiturLoc;
	move_uploaded_file($filettddebiturLoc_temp,$filettddebiturLoc_des);

	$filettdmarketingLoc = $_FILES['filettdmarketing']['name'];
	$arr = explode("." , $filettdmarketingLoc);
	$ext = $arr[count($arr)-1];
	$filettdmarketingLoc = str_replace(" ","_" , $nama).'_'.uniqid().".".$ext;
	$filettdmarketingLoc_temp = $_FILES['filettdmarketing']['tmp_name'];
	$filettdmarketingLoc_des		= $path.'/'.$filettdmarketingLoc;
	move_uploaded_file($filettdmarketingLoc_temp,$filettdmarketingLoc_des);

	$filefotoskpensiunLoc = $_FILES['filefotoskpensiun']['name'];
	$arr = explode("." , $filefotoskpensiunLoc);
	$ext = $arr[count($arr)-1];
	$filefotoskpensiunLoc = str_replace(" ","_" , $nama).'_'.uniqid().".".$ext;
	$filefotoskpensiunLoc_temp = $_FILES['filefotoskpensiun']['tmp_name'];
	imagerotation($filefotoskpensiunLoc_temp);
	$filefotoskpensiunLoc_des		= $path.'/'.$filefotoskpensiunLoc;
	move_uploaded_file($filefotoskpensiunLoc_temp,$filefotoskpensiunLoc_des);

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

if($deviceVersion <> $versionavailable)
{
	$json['err_no']  = '20';
	$json['err_msg'] = 'Error occured. Please try again.';

	echo json_encode($json);
	die();
}

	/*-EOF-*/

	$queryUserID 	= "SELECT * FROM useraccess WHERE id='$userID'";
	$resultUserID 	= query_db($queryUserID);
	$encodedUserID 	=  mysql_fetch_assoc($resultUserID);
	$userCabang 	= $encodedUserID['branch'];
	$idbro			= $encodedUserID['idbroker'];
	$idbank			= $encodedUserID['idclient'];
	$encodedUserID 	= $encodedUserID['id'];
	$userEmail 	= $encodedUserID['email'];
	$firstname 	= $encodedUserID['firstname'];
	$supervisor 	= $encodedUserID['supervisor'];
	if($userEmail==""){
		$userEmail = 'sysdev@kode.web.id';
	}
	$querySupervisorID 	= "SELECT * FROM useraccess WHERE id='$supervisor'";
	$encodedSupervisorID 	= mysql_fetch_assoc(query_db($querySupervisorID));
	$namasupervisor = $encodedSupervisorID['firstname'];
	$emailsupervisor = $encodedSupervisorID['email'];

	$queryCabang 	= "SELECT er,name FROM ajkcabang WHERE er = '$userCabang'";
	$resultCabang 	= query_db($queryCabang);
	$encodedCabang 	=  mysql_fetch_assoc($resultCabang);
	$namaCabang 	= $encodedCabang['name'];
	$CabangID 		= $encodedCabang['er'];

	//Insert to spak table
	$query_last_spak 	= "SELECT COUNT(*) as max FROM ajkspk
						WHERE idbroker = '".$idbro."'
						AND idpartner = '".$idbank."'
						AND idproduk = '".$idproduk."'
						AND YEAR(input_date) = YEAR(NOW())";
	$result_last_spak 	= query_db($query_last_spak);
	if(mysql_num_rows($result_last_spak)>0){
		$row 			=  mysql_fetch_assoc($result_last_spak);
		$next_spak_id 	=$row['max']+1;
	}else{
		$next_spak_id	=1;
	}

	$cek_query_pst = "SELECT * FROM ajkpeserta WHERE nama = '$nama' AND tgllahir = '$dob' AND plafond = '$plafond' AND cabang = '$namaCabang'";
	$cek_query 		= "SELECT * FROM ajkspk WHERE nama = '$nama' AND dob = '$dob' AND plafond = '$plafond'";

	$result = query_db($cek_query);
	$result_pst = query_db($cek_query_pst);
	$checkrows = mysql_num_rows($result);
	$checkrows_pst = mysql_num_rows($result_pst);
	if ($checkrows > 0 || $checkrows_pst > 0){
	// if ($checkrows > 0){

		$json['err_no'] = '3';
		$json['err_msg'] = 'Data Exist';

		echo json_encode($json);
	}else{
		$filefotodebitursatuLoc = $foldername.'/'.$filefotodebitursatuLoc;
		$filefotoktpLoc = $foldername.'/'.$filefotoktpLoc;
		$filettddebiturLoc = $foldername.'/'.$filettddebiturLoc;
		$filettdmarketingLoc = $foldername.'/'.$filettdmarketingLoc;
		$filefotoskpensiunLoc = $foldername.'/'.$filefotoskpensiunLoc;
		$next_spak_no = formatSPAKNo($next_spak_id,$idproduk);

		$token = generateRandomNumber(6);

		$query = "INSERT INTO ajkspk ";
		$query .="(idbroker,idpartner,idproduk,nomorspk,nama,jeniskelamin,dob,nomorktp,alamat,pekerjaan,asuransi,plafond,tenor,photodebitur1, ";

		$query .="photoktp,ttddebitur,ttdmarketing,photosk,token,cabang,input_by,input_date) ";
		$query .="VALUES ('$idbro', '$idbank', '$idproduk', '$next_spak_no', '$nama', '$jns_kelamin', '$dob', '$noidentitas', '$alamat', '$pekerjaan','$asuransi', '$plafond', '$tenor', '$filefotodebitursatuLoc', ";
		$query .="'$filefotoktpLoc', '$filettddebiturLoc', '$filettdmarketingLoc', '$filefotoskpensiunLoc', '$token','$CabangID','$encodedUserID', NOW())" ;//CONTINUE...

		$result = query_db($query);
	 // result return true if success
		if($result)
		{
			$json['token']=$token;
			$json['no_spk'] = $next_spak_no;
			$json['err_no'] = '0';
			$json['err_msg'] = 'Success';

			//$subject = '[BIOS] Input data debitur';
			//$body = 'Dear '.$namasupervisor. ' <br><br> Data calon debitur telah diinput dengan nomor <b>'.$next_spak_no.'</b> Atas nama <b>'.$nama.'</b> dengan plafon Rp. <b>'.$plafond.'</b> dan tenor sampai dengan <b>'.$tenor.' Bulan </b><br><br> Demikian yang dapat disampaikan.<br>Terima Kasih<br>'.$firstname;
			//kirimemail($firstname, $userEmail,$namasupervisor,$emailsupervisor,1,'','',0, $subject,$body);
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