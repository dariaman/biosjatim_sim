<?php
 /********************************************************************
 DESC  : Create by hansen;
 EMAIL : hansendputra@gmail.com;
 Create Date : 2018-01-02

 ********************************************************************/
	include "../param.php";

	$path_upload ="../image/upload/";
	$today = date('YmdHis');
	
	if (!file_exists($path_upload)) {
	  mkdir($path_upload, 0777, true);
	  chmod($path, 0777);
	}

	$namaproduk = $_POST['namaproduk'];
	$nama = $_POST['namatertanggung'];
	$jnsklmn = $_POST['jnsklmn'];
	$tgllahir = _convertDate2($_POST['tgllahir']);
	$nomorktp = $_POST['nomorktp'];
	$pekerjaan = $_POST['pekerjaan'];
	$alamat = $_POST['alamat'];
	$plafon = $_POST['plafon'];
	$tenor = $_POST['tenor'];

	$nama = str_replace("'", "''", $nama);

	$query_last_spak 	= "SELECT COUNT(*) as max 
												FROM ajkspk
												WHERE idbroker = '".$idbro."'
												AND idpartner = '".$idclient."'
												AND idproduk = '".$namaproduk."'
												AND YEAR(input_date) = YEAR(NOW())";
	$result_last_spak 	= mysql_query($query_last_spak);
	if(mysql_num_rows($result_last_spak)>0){
		$row 			=  mysql_fetch_assoc($result_last_spak);
		$next_spak_id 	=$row['max']+1;
	}else{
		$next_spak_id	=1;
	}

	$next_spak_no = formatSPAKNo($next_spak_id,$namaproduk);

	$token = generateRandomNumber(6);

	$query = "INSERT INTO ajkspk (idbroker,
																idpartner,
																idproduk,
																nomorspk,
																nama,
																jeniskelamin,
																dob,
																nomorktp,
																alamat,
																pekerjaan,
																asuransi,
																plafond,
																tenor,
																token,
																cabang,
																input_by,
																input_date) 
						VALUES ('$idbro', 
										'$idclient', 
										'$namaproduk', 
										'$next_spak_no', 
										'$nama', 
										'$jnsklmn', 
										'$tgllahir', 
										'$nomorktp', 
										'$alamat', 
										'$pekerjaan',
										'$asuransi', 
										'$plafon', 
										'$tenor', 
										'$token',
										'$cabang',
										'$iduser', 
										 NOW())" ;
	
	$result = mysql_query($query);

	if($result){
		echo "success";
	}	

	// function uploaded($data,$file){
	// 	global $path_upload;
	// 	global $today;
	// 	global $nama;
	// 	$nama = str_replace(" ","_", $nama);
	// 	$data = substr($data,strpos($data,",")+1);
	// 	$data = base64_decode($data);
	// 	$image = $path_upload.$nama.uniqid().'_D'.$file;
	// 	file_put_contents($image, $data);	
	// 	return $image;
	// }

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
		
	function generateRandomNumber($length){
		$token = "";
		for($i = 0 ; $i<$length;$i++){
			$token.=rand(0,9);
		}
		return $token;
	}		
	
?>