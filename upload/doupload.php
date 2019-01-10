<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../param.php";

	$today = date('Y-m-d H:i:s');
	$idpolis = $_SESSION['polis'];
	$file_temp = $_SESSION['file_temp'];
	$file_name = $_SESSION['file_name'];

	$inputFileName = 'temp/'.$file_temp;
	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = '../myFiles/_uploaddata/'.$foldername;

	$querysup = mysql_query("SELECT * FROM  useraccess WHERE id = '".$idsupervisor."'");
	$rowsup = mysql_fetch_array($querysup);
	$namasup = $rowsup['firstname'];
	$emailsup = $rowsup['email'];

	if($gender=="L"){
		$gen= 'Bpk';
	}else{
		$gen= 'Ibu';
	}
	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}

$ls_body = '
<div class=msg>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                <tbody>
                    <tr>
                        <td align="center" valign="top">
                            <table width="600" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="27"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="10"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left"><b>Upload Data Member</b></span>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="2"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td align="right" valign="bottom">&nbsp;</td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="20"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#454545"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#444444"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#434343"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#414141"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#404040"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3f3f3f"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="1" bgcolor="#3e3e3e"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="4"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="70"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="40"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="1" bgcolor="#d7d7d7"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="19"></td>
                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" width="14"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">Dear '.$namasup.'</div>
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table width="100%" border="1" cellspacing="0" cellpadding="0">
                                                                <thead >
																	<tr bgcolor="#4CB7EF" style="color:#fff;text-decoration:none" rel="noreferrer">
																		<th>Nama Tertanggung </th>
																		<th>Cabang</th>
																		<th>Nomor KTP</th>
																		<th>Nomor PK</th>
																		<th class="text-center">Tanggal Lahir</th>
																		<th class="text-center">Usia</th>
																		<th class="text-center">Tanggal Akad</th>
																		<th class="text-center">Tanggal Akhir</th>
																		<th class="text-center">Tenor (bulan)</th>
																		<th class="text-center">Nilai Pertanggungan (Plafond)</th>
																		<th class="text-center">Rate</th>
																		<th class="text-center">Premi</th>

																	</tr>

																</thead><tbody>
                                                                ';



	try {
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
			. '": ' . $e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$newfilename = date('ymd_his').'_'.$file_name;

		copy($inputFileName, $path.'/'.$newfilename) or die( "Could not upload file!");
						//  Loop through each row of the worksheet in turn
		for ($row = 7; $row <= $highestRow; $row++) {
									//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('B' . $row . ':' . $highestColumn . $row,
			NULL, TRUE, FALSE);
				$i = 0;
				foreach($rowData[0] as $v){
					//echo $v.'<br>';
					$data[$i] = $v;
					$i++;
				}

				/*$nama = $data[0];
				$cabang = $data[1];
				$ktp = $data[2];
				$npk = $data[3];
				$tgllahirdd = $data[4];
				$tgllahirmm = $data[5];
				$tgllahiryy = $data[6];*/
			$cabang = $data[0];
			$nama = $data[1];
			$gender = $data[2];
			$ktp = str_replace($_separatorsNumb,$_separatorsNumb_,$data[3]);
			$npk = str_replace($_separatorsNumb,$_separatorsNumb_,$data[4]);
			$tgllahirdd = $data[5];
			$tgllahirmm = $data[6];
			$tgllahiryy = $data[7];

				$tgllahir = $tgllahiryy.'-'.$tgllahirmm.'-'.$tgllahirdd;
				$tgllahir = date('Y-m-d', strtotime($tgllahir));
				$usia = birthday($tgllahir,$today);
			$tglakaddd = $data[8];
			$tglakadmm = $data[9];
			$tglakadyy = $data[10];
				$tglakad = $tglakadyy.'-'.$tglakadmm.'-'.$tglakaddd;
				$tglakad = date('Y-m-d', strtotime($tglakad));
			$tenor = $data[11];
			$graceperiod = $data[12];
			$plafon = str_replace(".","" , $data[13]);
			$expremi = $data[14];
			$keterangan = $data[15];
			//$paket = $data[16];
			$nomorformulir = $data[16];
			$nomorspak = $data[17];

			/*
			$accup = $data[17];
			$kelas = $data[18];
			$lokasi = $data[19];
			$nilaijaminankpr = $data[20];
			$alamatkpr = $data[21];
			$kota = $data[22];
			$provinsi = $data[23];
			$kodepost = $data[24];
			*/
				$qpolis = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND id = '".$idpolis."'");
				$rpolis = mysql_fetch_array($qpolis);
				$levelval = $rpolis['levelvalidasi'];
				$lastdayinsurance = $rpolis['lastdayinsurance'];
				$ageend = $rpolis['ageend'];
				$agemin = $rpolis['agestart'];
				$byrate = $rpolis['byrate'];
				$calculaterate = $rpolis['calculatedrate'];
				$adminfee = $rpolis['adminfee'];
				$diskon = $rpolis['diskon'];
				$general = $rpolis['general'];
				//$typemedical = 'FCL';
				//DATA MEDICAL
/*
				if ($rpolis['freecover']=="Y") {
					$querymedical = mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafon.' BETWEEN upfrom AND upto AND del IS NULL');
					$rowmedical = mysql_fetch_array($querymedical);
					$typemedical = $rowmedical['type'];
				}
*/

				$querymedical = mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbro.'" AND idpartner="'.$idclient.'" AND idproduk="'.$idpolis.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafon.' BETWEEN upfrom AND upto AND del IS NULL');
				$rowmedical = mysql_fetch_array($querymedical);
				$typemedical = $rowmedical['type'];
				//DATA MEDICAL
				if($lastdayinsurance=1){
					$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -1 Day"));
				}else{
					$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month 0 Day"));;
				}
				if($levelval==3){
					$statusaktif = 'Upload';
				}elseif($levelval==2){
					$statusaktif = 'Pending';
				}
				$qcabang = mysql_query("SELECT * FROM ajkcabang WHERE name = UPPER('".$cabang."') AND idclient = '".$idclient."'");
				$rcabang = mysql_fetch_array($qcabang);
				$idcabang  = $rcabang['er'];
				$idarea  = $rcabang['idarea'];
				$idregional  = $rcabang['idreg'];

				if ($rpolis['general']=="T") {
					if($byrate=="Age"){
						$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
					}else{
						$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbro."' AND idclient='".$idclient."' AND idpolis='".$idpolis."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
					}
				}else{
					$cekajkumum = mysql_fetch_array(mysql_query('SELECT * FROM ajkumum WHERE nomorspajk="'.strtoupper($nomorspak).'" AND nama="'.strtoupper($nama).'" AND tgllahir="'.$tgllahir.'" AND nilaidiajukan="'.$plafon.'" AND tenor="'.$tenor.'"'));
					$qrate = mysql_query("SELECT ajkpolis.idgeneral,
																	 ajkpolis.byrategeneral,
																	 ajkpolis.classgeneral,
																	 ajkpolis.calculatedrate,
																	 ajkrategeneral.id,
																	 ajkrategeneral.tenorstart,
																	 ajkrategeneral.tenorend,
																	 ajkrategeneral.plafondstart,
																	 ajkrategeneral.plafondend,
																	 ajkrategeneral.lokasi,
																	 ajkrategeneral.quarantee,
																	 ajkrategeneral.kelas,
																	 ajkrategeneral.rate,
																	 ajkrategeneral.type
															FROM ajkpolis
															INNER JOIN ajkrategeneral ON ajkpolis.id = ajkrategeneral.idproduk
															WHERE ajkpolis.id = ".$idpolis." AND
																  IF(ajkpolis.byrategeneral='Tenor', ".$tenor." BETWEEN ajkrategeneral.tenorstart AND ajkrategeneral.tenorend, ajkrategeneral.tenorstart IS NULL) AND
																  IF(ajkpolis.byrategeneral='Plafond', ".$plafon." BETWEEN ajkrategeneral.plafondstart AND ajkrategeneral.plafondend, ajkrategeneral.plafondstart IS NULL) AND
																  IF(ajkpolis.classgeneral='Ya', ajkrategeneral.kelas=".$cekajkumum['kelaskontruksi']." ,ajkrategeneral.kelas IS NULL) AND
																  ajkrategeneral.lokasi=".$cekajkumum['wilayah']." AND
																  ajkrategeneral.quarantee=".$cekajkumum['okupasi']." AND
																  ajkrategeneral.status='Aktif'");
				}
				$rrate = mysql_fetch_array($qrate);
				$rate = $rrate['rate'];
				$rate_id = $rrate['id'];
				$premi = ($plafon * $rate) / $calculaterate;
				$extpremi = 0;
				$discountpremi = $premi * $diskon/100;
				$totalpremi = $premi - $discountpremi + $extpremi + $adminfee;

/*
			if($general =="Y"){
					$metRateGeneral = mysql_fetch_array(mysql_query('SELECT * FROM ajkrategeneral WHERE idbroker="'.$idbro.'" AND
																									   idclient="'.$idclient.'" AND
																									   idproduk="'.$idpolis.'" AND
																									   '.$tenor.' BETWEEN tenorstart AND tenorend AND
																									   lokasi = "'.$lokasi.'" AND
																									   quarantee = "'.$accup.'" AND
																									   kelas = "'.$kelas.'" AND
																									   status="Aktif"'));
					$metPremiGenFire = ($nilaijaminankpr * $metRateGeneral['ratefire']) / $rpolis['calculatedrate'];
					$metPremiGenPA = ($nilaijaminankpr * $metRateGeneral['ratepa']) / $rpolis['calculatedrate'];


				mysql_query("INSERT INTO ajkpeserta_temp
				(idbroker,idclient,idpolicy,filename,nomorktp,nomorpk,nomorspk,nama,gender,tgllahir,usia,plafond,tglakad,tenor,tglakhir,statusaktif,
				cabang,area,regional,premirateid,premirate,premi,diskonpremi, biayaadmin, extrapremi, totalpremi, paketasuransi, okupasi, kelas, lokasi, nilaijaminan, alamatobjek, kota, provinsi, kodepos,
				premifire,premipa,medical, input_by, input_time)
				VALUES ('".$idbro."','".$idclient."','".$idpolis."','".$newfilename."','".$ktp."','".$npk."','".$nomorformulir."','".strtoupper(trim($nama))."','".$gender."','".$tgllahir."','".$usia."','".$plafon."','".$tglakad."','".$tenor."','".$tglakhir."','".$statusaktif."',
				'".$idcabang."','".$idarea."','".$idregional."','".$rate_id."','".$rate."','".$premi."','".$discountpremi."','".$adminfee."','".$extpremi."','".$totalpremi."','".$paket."','".$accup."','".$kelas."','".$lokasi."','".$nilaijaminankpr."','".$alamatkpr."','".$kota."','".$provinsi."','".$kodepost."',
				'".$metPremiGenFire."','".$metPremiGenPA."','".$typemedical."','".$iduser."','".$today."')");
			}else{
*/
				/*
				mysql_query("INSERT INTO ajkpeserta_temp
				(idbroker,idclient,idpolicy,filename,nomorktp,nomorpk,nama,tgllahir,usia,plafond,tglakad,tenor,tglakhir,statusaktif,
				cabang,regional,premi,diskonpremi, biayaadmin, extrapremi, totalpremi, medical, input_by, input_time)
				VALUES ('".$idbro."','".$idclient."','".$idpolis."','".$newfilename."','".$ktp."','".$npk."','".strtoupper(trim($nama))."','".$tgllahir."','".$usia."','".$plafon."','".$tglakad."','".$tenor."','".$tglakhir."','".$statusaktif."',
				'".$idcabang."','".$idregional."','".$premi."','".$discountpremi."','".$adminfee."','".$extpremi."','".$totalpremi."','".$typemedical."','".$iduser."','".$today."')");
				*/
				if ($general=="T") {
					$generalfield = "";
				}else{
					$generalfield = "nomorspak='".$nomorspak."',";
				}
				mysql_query("INSERT INTO ajkpeserta_temp SET idbroker='".$idbro."',
													  idclient='".$idclient."',
													  idpolicy='".$idpolis."',
													  filename='".$newfilename."',
													  gender='".$gender."',
													  nomorktp='".$ktp."',
													  nomorpk='".$npk."',
													  nomorspk='".$nomorformulir."',
													  ".$generalfield."
													  nama='".strtoupper(trim($nama))."',
													  tgllahir='".$tgllahir."',
													  usia='".$usia."',
													  plafond='".$plafon."',
													  tglakad='".$tglakad."',
													  tenor='".$tenor."',
													  tglakhir='".$tglakhir."',
													  statusaktif='".$statusaktif."',
													  cabang='".$idcabang."',
													  area='".$idarea."',
													  regional='".$idregional."',
													  premirateid='".$rate_id."',
													  premirate='".$rate."',
													  premi='".$premi."',
													  diskonpremi='".$discountpremi."',
													  biayaadmin='".$adminfee."',
													  extrapremi='".$extpremi."',
													  totalpremi='".$totalpremi."',
													  medical='".$typemedical."',
													  input_by='".$iduser."',
													  input_time='".$today."'");

			//}
			$ls_body .= '<tr>
						<td>'.strtoupper(trim($nama)).'</td>
						<td>'.strtoupper($cabang).'</td>
						<td>'.$ktp.'</td>
						<td>'.$npk.'</td>
						<td>'.$tgllahir.'</td>
						<td>'.$usia.'</td>
						<td>'.$tglakad.'</td>
						<td>'.$tglakhir.'</td>
						<td>'.$tenor.'</td>
						<td>'.number_format($plafon,0,".",",").'</td>
						<td>'.$rate.'</td>
					 	<td>'.number_format($premi,0,".",",").'</td>
					</tr>';

		}

$ls_body .='</tbody>
			</table>
			</tr></tbody></table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
            <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">Salam </br> '.$namauser.'</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                   <tr>
                      <td style="font-size:0pt;line-height:0pt;text-align:left" height="22"></td>
                </tr>
                </tbody>
             </table>

                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#464646">
                                                <tbody>
                                                    <tr>

                                                        <td width="560">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-size:0pt;line-height:0pt;text-align:left" height="25">
                                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left">
                                                                                <b>'.$namebro.'</b> '.$alamat.'
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
</tr></tbody></table></div>';
		$ls_toemail = $emailsup;
		$ls_toname = $namasup;
		$ls_subject = "[App Credit Life Insurance] Upload Data Peserta";
		$ls_countemail = 1;
		$ls_fromname = $namauser;
		$ls_fromemail = $emailuser;
		$ls_ccname = '';
		$ls_ccmail = '';
		$li_countcc = 0;

		//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
		//unlink($inputFileName);
		header("location:../upload?pesan=berhasil");


?>