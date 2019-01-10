<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../param.php";

	$today = date('Y-m-d H:i:s');
	$tglval= $_REQUEST['idp'];
	$tglval = AES::decrypt128CBC($tglval, ENCRYPTION_KEY);
	$typeval = $_REQUEST['tval'];
	$typeval = AES::decrypt128CBC($typeval, ENCRYPTION_KEY);
	if($typeval=="Pending"){
		$querypertemp = mysql_query("SELECT * FROM ajkpeserta_temp WHERE input_time = '".$tglval."'");
		$rowtemppeserta = mysql_fetch_array($querypertemp);
		$iduserinput = $rowtemppeserta['input_by'];
		$queryinput = mysql_query("SELECT * FROM  useraccess WHERE id = '".$iduserinput."'");
		$rowinput = mysql_fetch_array($queryinput);
		$namainput = $rowinput['firstname'];
		$emailinput = $rowinput['email'];
		$supinput = $rowinput['supervisor'];
		$queryinput = mysql_query("SELECT * FROM  useraccess WHERE id = '".$supinput."'");
		$rowinput = mysql_fetch_array($queryinput);
		$namasup = $rowinput['firstname'];
		$emailsup = $rowinput['email'];

		$queryuw = mysql_query("SELECT * FROM  useraccess WHERE level = '1'");
		$rowuw = mysql_fetch_array($queryuw);
		$namauw = $rowuw['firstname'];
		$emailuw = $rowuw['email'];
		$ls_body = '<div class=msg>
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
                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left"><b>Approve Member</b></span>
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
                                                            <div style="color:#676767;font-family:Arial;font-size:14px;line-height:18px;text-align:left">Dear '.$namauw.'</div>
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
																<th>Nomor KTP</th>
																<th>Nomor PK</th>
																<th class="text-center">Tanggal Lahir</th>
																<th class="text-center">Usia</th>
																<th class="text-center">Tanggal Akad</th>
																<th class="text-center">Tanggal Akhir</th>
																<th class="text-center">Tenor (bulan)</th>
																<th class="text-center">Nilai Pertanggungan (Plafond)</th>
																<th class="text-center">Premi</th>
															</tr>

														</thead><tbody>
                                                                ';
		if(!empty($_POST['approve'])) {
			foreach($_POST['approve'] as $check) {
				mysql_query("INSERT INTO ajkpeserta (idbroker,
				idclient,
				idpolicy,
				filename,
				nomorktp,
				nomorpk,
				nomorspk,
				nama,
				gender,
				tgllahir,
				usia,
				plafond,
				tglakad,
				tenor, tglakhir, premirateid, premirate, premi,
				diskonpremi,biayaadmin, extrapremi,totalpremi,regional,area,cabang,
				paketasuransi, okupasi, kelas, lokasi, nilaijaminan, alamatobjek, kota, provinsi, kodepos, premigeneralrate, premigeneral, premiparate, premipa,medical, input_by, input_time, checker_by, checker_time, approve_by, approve_time,statusaktif)
				SELECT idbroker,
				idclient,
				idpolicy,
				filename,
				nomorktp,
				nomorpk,
				nomorspk,
				nama,
				gender,
				tgllahir,
				usia,
				plafond,
				tglakad,
				tenor, tglakhir, premirateid, premirate, premi,
				diskonpremi,biayaadmin, extrapremi,totalpremi,regional,area,cabang,
				paketasuransi, okupasi, kelas, lokasi, nilaijaminan, alamatobjek, kota, provinsi, kodepos, premigeneralrate, premigeneral, premiparate, premipa,medical, input_by, input_time, checker_by, checker_time, '$iduser', '$today', 'Approve'
				FROM ajkpeserta_temp WHERE input_time = '".$tglval."' AND nomorktp = '".$check."'");

				$querytemp = mysql_query("SELECT idbroker,idclient,idpolicy,filename,nomorktp,nomorpk,nama,gender,tgllahir,usia,plafond,tglakad,tenor, tglakhir, premirateid, premirate, premi,diskonpremi,biayaadmin, extrapremi,totalpremi,regional,cabang, input_by, input_time
										  FROM ajkpeserta_temp WHERE input_time = '".$tglval."' AND nomorktp = '".$check."'");
				$rowtemp = mysql_fetch_array($querytemp);

				$nama = $rowtemp['nama'];
				$ktp = $rowtemp['nomorktp'];
				$npk = $rowtemp['nomorpk'];
				$tgllahir = $rowtemp['tgllahir'];
				$usia = $rowtemp['usia'];
				$tglakad = $rowtemp['tglakad'];
				$tglakhir = $rowtemp['tglakhir'];
				$tenor = $rowtemp['tenor'];
				$plafon = $rowtemp['plafond'];
				$premi = $rowtemp['premi'];
				$inputby = $rowtemp['input_by'];



				$ls_body .= '<tr>
						<td>'.strtoupper(trim($nama)).'</td>
						<td>'.$ktp.'</td>
						<td>'.$npk.'</td>
						<td>'.$tgllahir.'</td>
						<td>'.$usia.'</td>
						<td>'.$tglakad.'</td>
						<td>'.$tglakhir.'</td>
						<td>'.$tenor.'</td>
						<td>'.number_format($plafon,0,".",",").'</td>
					 	<td>'.number_format($premi,0,".",",").'</td>
					</tr>';

				mysql_query("DELETE FROM ajkpeserta_temp WHERE input_time = '".$tglval."' AND nomorktp = '".$check."'");
			}
			$check = "";
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


		$ls_toemail = $emailuw;
		$ls_toname = $namauw;
		$ls_subject = "[App Credit Life Insurance] Approve";
		$ls_countemail = 1;
		$ls_fromname = $namauser;
		$ls_fromemail = $emailuser;
		$ls_ccname = $namainput.'|'.$namasup;
		$ls_ccmail = $emailinput.'|'.$emailsup;
		$li_countcc = 2;
		//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);

	}else{
		$querysup = mysql_query("SELECT * FROM  useraccess WHERE id = '".$idsupervisor."'");
		$rowsup = mysql_fetch_array($querysup);
		$namasup = $rowsup['firstname'];
		$emailsup = $rowsup['email'];
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
                                                            <span style="color:#ffffff;font-family:Arial;font-size:12px;line-height:16px;text-align:left"><b>Verification Member</b></span>
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
																		<th>Nomor KTP</th>
																		<th>Nomor PK</th>
																		<th class="text-center">Tanggal Lahir</th>
																		<th class="text-center">Usia</th>
																		<th class="text-center">Tanggal Akad</th>
																		<th class="text-center">Tanggal Akhir</th>
																		<th class="text-center">Tenor (bulan)</th>
																		<th class="text-center">Nilai Pertanggungan (Plafond)</th>
																		<th class="text-center">Premi</th>
																	</tr>

																</thead><tbody>
                                                                ';

		if(!empty($_POST['approve'])) {
			foreach($_POST['approve'] as $check) {

				mysql_query("UPDATE ajkpeserta_temp SET statusaktif = 'Pending', checker_by='".$iduser."', checker_time='".$today."' WHERE input_time = '".$tglval."' AND nomorktp = '".$check."'");

				$querytemp = mysql_query("SELECT idbroker,idclient,idpolicy,filename,nomorktp,nomorpk,nama,tgllahir,usia,plafond,tglakad,tenor, tglakhir, premirate, premi,
				diskonpremi,biayaadmin, extrapremi,totalpremi,regional,cabang, input_by, input_time
				FROM ajkpeserta_temp WHERE input_time = '".$tglval."' AND nomorktp = '".$check."'");
				$rowtemp = mysql_fetch_array($querytemp);

				$nama = $rowtemp['nama'];
				$ktp = $rowtemp['nomorktp'];
				$npk = $rowtemp['nomorpk'];
				$tgllahir = $rowtemp['tgllahir'];
				$usia = $rowtemp['usia'];
				$tglakad = $rowtemp['tglakad'];
				$tglakhir = $rowtemp['tglakhir'];
				$tenor = $rowtemp['tenor'];
				$plafon = $rowtemp['plafond'];
				$premi = $rowtemp['premi'];
				$inputby = $rowtemp['input_by'];

				$queryinput = mysql_query("SELECT * FROM  useraccess WHERE id = '".$inputby."'");
				$rowinput = mysql_fetch_array($queryinput);
				$namainput = $rowinput['firstname'];
				$emailinput = $rowinput['email'];

				$ls_body .= '<tr>
						<td>'.strtoupper(trim($nama)).'</td>
						<td>'.$ktp.'</td>
						<td>'.$npk.'</td>
						<td>'.$tgllahir.'</td>
						<td>'.$usia.'</td>
						<td>'.$tglakad.'</td>
						<td>'.$tglakhir.'</td>
						<td>'.$tenor.'</td>
						<td>'.number_format($plafon,0,".",",").'</td>
					 	<td>'.number_format($premi,0,".",",").'</td>
					</tr>';
			}
			$check = "";
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
		$ls_subject = "[App Credit Life Insurance] Verification";
		$ls_countemail = 1;
		$ls_fromname = $namauser;
		$ls_fromemail = $emailuser;
		$ls_ccname = $namainput;
		$ls_ccmail = $emailinput;
		$li_countcc = 1;
		//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
	}

	header("location:../validasi/?type=");
?>