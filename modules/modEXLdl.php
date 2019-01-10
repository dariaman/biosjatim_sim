<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
error_reporting(0);
session_start();
require_once('../includes/metPHPXLS/Worksheet.php');
require_once('../includes/metPHPXLS/Workbook.php');
include_once('../includes/fu6106.php');
include_once('../includes/Encrypter.class.php');
$thisEncrypter = new textEncrypter();
include_once('../includes/functions.php');
switch ($_REQUEST['Rxls']) {
	case "ExlDL":
		$filename = "FILE_UPLOAD";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}

		$colField = mysql_fetch_array(mysql_query('SELECT ajkexcelupload.id, Count(ajkexcelupload.idxls) AS jumField, ajkclient.`name`, ajkpolis.policyauto FROM ajkexcelupload INNER JOIN ajkclient ON ajkexcelupload.idc = ajkclient.id INNER JOIN ajkpolis ON ajkexcelupload.idp = ajkpolis.id WHERE ajkexcelupload.idb="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkexcelupload.idc="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkexcelupload.idp="'.$thisEncrypter->decode($_REQUEST['idp']).'" GROUP BY ajkexcelupload.idp'));
		$jumlahFieldDataUplaod = $colField['jumField'];
		HeaderingExcel(str_replace(" ","_", strtoupper($colField['name'])).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();
		$fdate =& $workbook->add_format();		$fdate->set_color('white');

		$worksheet1->write_string(0, $jumlahFieldDataUplaod + 1, date("Y-m-d"), $fdate);	//cek data asli file excel

		$worksheet1->merge_cells(0, 0, 0, $jumlahFieldDataUplaod);	$worksheet1->write_string(0, 0, "DATA UPLOAD PESERTA", $fjudul, 0, $jumlahFieldDataUplaod);
		$worksheet1->merge_cells(1, 0, 1, $jumlahFieldDataUplaod);	$worksheet1->write_string(1, 0, strtoupper($colField['name']), $fjudul);
		$worksheet1->merge_cells(2, 0, 2, $jumlahFieldDataUplaod);	$worksheet1->write_string(2, 0, strtoupper($colField['policyauto']), $fjudul);

		$Databaris = 4;
		$Datakolom = 1;
		$metDLExl = mysql_query('SELECT ajkexcel.fieldname, ajkexcelupload.valempty, ajkexcelupload.valdate, ajkexcelupload.valsamedata
						 FROM ajkexcelupload
						 INNER JOIN ajkexcel ON ajkexcelupload.idxls = ajkexcel.id
						 WHERE ajkexcelupload.idb = "'.$thisEncrypter->decode($_REQUEST['idb']).'" AND
						 	   ajkexcelupload.idc = "'.$thisEncrypter->decode($_REQUEST['idc']).'" AND
						 	   ajkexcelupload.idp = "'.$thisEncrypter->decode($_REQUEST['idp']).'"
						 ORDER BY ajkexcelupload.id ASC');
		$worksheet1->write_string($Databaris, 0, "No",$format);
		while ($metDLExl_ = mysql_fetch_array($metDLExl)) {
			if ($metDLExl_['valempty']=="Y" OR $metDLExl_['valdate']=="Y" OR $metDLExl_['valsamedata']=="Y") {
				$metKolomVal = $metDLExl_['fieldname'].'*';
			}else{
				$metKolomVal = $metDLExl_['fieldname'];
			}
			$worksheet1->write_string($Databaris, $Datakolom, $metKolomVal, $format);
			$Datakolom++;
		}

		$workbook->close();
		;
		break;

	case "lprmember":
		$filename = "MEMBER_BANK";

		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel(_convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).'_'._convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		if ($thisEncrypter->decode($_REQUEST['idb'])) {	$satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idp'])) {
			$metProduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
			$tiga ='AND ajkpolis.id = "'.$metProduk[0].'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

		if ($_REQUEST['idb']==""){	$_metbroker = '';	}else{	$_metbroker = $met_['brokername'];	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$_metclient = $met_['clientname'];	}else{	$_metclient = 'ALL CLIENT';	}
		if ($thisEncrypter->decode($_REQUEST['idp'])) {	$_metproduk = $met_['produk'];		}else{	$_metproduk = 'ALL PRODUCT';	}

		$worksheet1->write_string(0, 0, "LAPORAN DATA DEBITUR PERUSAHAAN", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 20);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 20);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 20);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 20);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "No", $format);
		$worksheet1->set_column(5, 1, 30);	$worksheet1->write_string(5, 1, "Perusahaan", $format);
		$worksheet1->set_column(5, 2, 30);	$worksheet1->write_string(5, 2, "Produk", $format);
		$worksheet1->set_column(5, 3, 30);	$worksheet1->write_string(5, 3, "Debitnote", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "Tanggal DN", $format);
		$worksheet1->set_column(5, 5, 15);	$worksheet1->write_string(5, 5, "KTP", $format);
		$worksheet1->set_column(5, 6, 15);	$worksheet1->write_string(5, 6, "No Pinjaman", $format);
		$worksheet1->set_column(5, 7, 15);	$worksheet1->write_string(5, 7, "ID Debitur", $format);
		$worksheet1->set_column(5, 8, 15);	$worksheet1->write_string(5, 8, "ID Asuransi", $format);
		$worksheet1->set_column(5, 9, 15);	$worksheet1->write_string(5, 9, "ID Re-Broker", $format);
		$worksheet1->set_column(5, 10, 15);	$worksheet1->write_string(5, 10, "ID Re-Asuransi", $format);
		$worksheet1->set_column(5, 11, 30);	$worksheet1->write_string(5, 11, "Nama Debitur", $format);
		$worksheet1->set_column(5, 12, 10);	$worksheet1->write_string(5, 12, "Tanggal Lahir", $format);
		$worksheet1->set_column(5, 13, 30);	$worksheet1->write_string(5, 13, "Jenis Kelamin", $format);
		$worksheet1->set_column(5, 14, 30);	$worksheet1->write_string(5, 14, "Pekerjaan", $format);
		$worksheet1->set_column(5, 15, 10);	$worksheet1->write_string(5, 15, "Mulai Asuransi", $format);
		$worksheet1->set_column(5, 16, 10);	$worksheet1->write_string(5, 16, "Akhir Asuransi", $format);
		$worksheet1->set_column(5, 17, 10);	$worksheet1->write_string(5, 17, "Nm Asuransi", $format);
		$worksheet1->set_column(5, 18, 5);	$worksheet1->write_string(5, 18, "JWP (Bulan)", $format);
		$worksheet1->set_column(5, 19, 10);	$worksheet1->write_string(5, 19, "Harga Pertanggungan", $format);
		$worksheet1->set_column(5, 20, 5);	$worksheet1->write_string(5, 20, "Usia", $format);
		$worksheet1->set_column(5, 21, 10);	$worksheet1->write_string(5, 21, "Usia + JWP", $format);
		$worksheet1->set_column(5, 22, 10);	$worksheet1->write_string(5, 22, "Rate", $format);
		$worksheet1->set_column(5, 23, 15);	$worksheet1->write_string(5, 23, "Premi", $format);
		$worksheet1->set_column(5, 24, 10);	$worksheet1->write_string(5, 24, "Status", $format);
		$worksheet1->set_column(5, 25, 20);	$worksheet1->write_string(5, 25, "Cabang", $format);
		$worksheet1->set_column(5, 26, 20);	$worksheet1->write_string(5, 26, "Keterangan", $format);
		$baris = 6;
		
		$metCOB = mysql_query($thisEncrypter->decode($_SESSION['lprmember']));

		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];
			
			$worksheet1->write_string($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metCOB_['perusahaan']);
			$worksheet1->write_string($baris, 2, $metCOB_['produk']);
			$worksheet1->write_string($baris, 3, $metCOB_['nomordebitnote']);
			$worksheet1->write_string($baris, 4, $metCOB_['tgldebitnote']);
			$worksheet1->write_string($baris, 5, $metCOB_['nomorktp']);			
			$worksheet1->write_string($baris, 6, $metCOB_['nopinjaman']);
			$worksheet1->write_string($baris, 7, $metCOB_['idpeserta']);
			$worksheet1->write_string($baris, 8, $metCOB_['nm_asuransi']);
			$worksheet1->write_string($baris, 9, $metCOB_['noasuransi']);
			$worksheet1->write_string($baris, 9, $metCOB_['norebroker']);
			$worksheet1->write_string($baris, 10, $metCOB_['noreasuransi']);			
			$worksheet1->write_string($baris, 11, $metCOB_['nama']);
			$worksheet1->write_string($baris, 12, $metCOB_['tgllahir']);
			$worksheet1->write_string($baris, 13, $metCOB_['jnskelamin']);
			$worksheet1->write_string($baris, 14, $metCOB_['nm_kategori_profesi']);
			$worksheet1->write_string($baris, 15, _convertDate($metCOB_['tglakad']));
			$worksheet1->write_string($baris, 16, _convertDate($metCOB_['tglakhir']));
			$worksheet1->write_string($baris, 17, $metCOB_['nmasuransi']);
			$worksheet1->write_number($baris, 18, $metCOB_['tenor']);
			$worksheet1->write_number($baris, 19, $metCOB_['plafond']);
			$worksheet1->write_number($baris, 20, $metCOB_['usia']);
			$worksheet1->write_string($baris, 21, $usiatenor);
			$worksheet1->write_string($baris, 22, $metCOB_['premirate']);
			$worksheet1->write_number($baris, 23, $metCOB_['totalpremi']);
			$worksheet1->write_string($baris, 24, $metCOB_['statusaktif']);
			$worksheet1->write_string($baris, 25, $metCOB_['cabang']);
			$worksheet1->write_string($baris, 26, $metCOB_['keterangan']);

			$baris++;
			$tPremi += $metCOB_['totalpremi'];
			$tPremias += $metCOB_['astotalpremi'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 19);
		$worksheet1->write_number($baris, 20, $tPremi, $ftotal);

		$workbook->close();
		;
	break;

	case "lprmemberarm":
		$filename = "MEMBER_BANK_ARM";

		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel($filename.date('YmdHis').'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		$worksheet1->write_string(0, 0, "LIST OUTSTANDING PEMBAYARAN", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 13);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 13);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 13);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 13);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "No", $format);
		$worksheet1->set_column(5, 1, 30);	$worksheet1->write_string(5, 1, "ID Peserta", $format);
		$worksheet1->set_column(5, 2, 30);	$worksheet1->write_string(5, 2, "No Pinjaman", $format);
		$worksheet1->set_column(5, 3, 30);	$worksheet1->write_string(5, 3, "Nama", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "Tgl Akad", $format);
		$worksheet1->set_column(5, 5, 15);	$worksheet1->write_string(5, 5, "Plafond", $format);
		$worksheet1->set_column(5, 6, 15);	$worksheet1->write_string(5, 6, "Tenor", $format);
		$worksheet1->set_column(5, 7, 15);	$worksheet1->write_string(5, 7, "Pekerjaan", $format);
		$worksheet1->set_column(5, 8, 15);	$worksheet1->write_string(5, 8, "Rate", $format);
		$worksheet1->set_column(5, 9, 15);	$worksheet1->write_string(5, 9, " Nilai Premi", $format);
		$worksheet1->set_column(5, 10, 15);	$worksheet1->write_string(5, 10, "Nilai Bayar", $format);
		$worksheet1->set_column(5, 11, 15);	$worksheet1->write_string(5, 11, "Selisih Bayar", $format);
		$worksheet1->set_column(5, 12, 15);	$worksheet1->write_string(5, 12, "Cabang", $format);
		$baris = 6;
				
		$metCOB = mysql_query($thisEncrypter->decode($_SESSION['lprmemberarm']));
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];
			
			$worksheet1->write_string($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metCOB_['idpeserta']);
			$worksheet1->write_string($baris, 2, $metCOB_['nopinjaman']);
			$worksheet1->write_string($baris, 3, $metCOB_['nama']);
			$worksheet1->write_string($baris, 4, $metCOB_['tglakad']);
			$worksheet1->write_string($baris, 5, $metCOB_['plafond']);			
			$worksheet1->write_string($baris, 6, $metCOB_['tenor']);
			$worksheet1->write_string($baris, 7, $metCOB_['nm_kategori_profesi']);
			$worksheet1->write_string($baris, 8, $metCOB_['premirate']);
			$worksheet1->write_string($baris, 9, $metCOB_['premi']);
			$worksheet1->write_string($baris, 10, $metCOB_['nilaibayar']);			
			$worksheet1->write_string($baris, 11, $metCOB_['premi'] - $metCOB_['nilaibayar']);
			$worksheet1->write_string($baris, 12, $metCOB_['nmcabang']);

			$baris++;
			$tPremi += $metCOB_['totalpremi'];
			$tPremias += $metCOB_['astotalpremi'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 16);
		$worksheet1->write_number($baris, 15, $tPremi, $ftotal);

		$workbook->close();
		;
	break;

	case "lprviewappins":
		$filename = "MEMBER_VIEW_INS";

		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel($filename.date('YmdHis').'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		$worksheet1->write_string(0, 0, "LAPORAN DATA DEBITUR ASURANSI", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 20);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 20);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 20);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 20);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "No", $format);
		$worksheet1->set_column(5, 1, 15);	$worksheet1->write_string(5, 1, "Asuransi", $format);
		$worksheet1->set_column(5, 2, 30);	$worksheet1->write_string(5, 2, "ID Peserta", $format);
		$worksheet1->set_column(5, 3, 30);	$worksheet1->write_string(5, 3, "No Pinjaman", $format);
		$worksheet1->set_column(5, 4, 50);	$worksheet1->write_string(5, 4, "Nama", $format);
		$worksheet1->set_column(5, 5, 10);	$worksheet1->write_string(5, 5, "Tgl Lahir", $format);
		$worksheet1->set_column(5, 6, 15);	$worksheet1->write_string(5, 6, "Tgl Akad", $format);
		$worksheet1->set_column(5, 7, 15);	$worksheet1->write_string(5, 7, "Tgl Akhir", $format);
		$worksheet1->set_column(5, 8, 15);	$worksheet1->write_string(5, 8, "Plafond", $format);
		$worksheet1->set_column(5, 9, 5);	$worksheet1->write_string(5, 9, "Usia", $format);
		$worksheet1->set_column(5, 10, 5);	$worksheet1->write_string(5, 10, "Tenor", $format);
		$worksheet1->set_column(5, 11, 15);	$worksheet1->write_string(5, 11, "Pekerjaan", $format);
		$worksheet1->set_column(5, 12, 15);	$worksheet1->write_string(5, 12, "Cabang", $format);
		$worksheet1->set_column(5, 13, 15);	$worksheet1->write_string(5, 13, "Rate Bank", $format);
		$worksheet1->set_column(5, 14, 15);	$worksheet1->write_string(5, 14, "Premi Bank", $format);
		$worksheet1->set_column(5, 15, 15);	$worksheet1->write_string(5, 15, "Rate AS", $format);
		$worksheet1->set_column(5, 16, 15);	$worksheet1->write_string(5, 16, "Premi AS", $format);
		$worksheet1->set_column(5, 17, 15);	$worksheet1->write_string(5, 17, "B/F", $format);
		$worksheet1->set_column(5, 18, 15);	$worksheet1->write_string(5, 18, "Cad. Klaim", $format);
		$worksheet1->set_column(5, 19, 15);	$worksheet1->write_string(5, 19, "Cad. Premi", $format);
		$worksheet1->set_column(5, 20, 15);	$worksheet1->write_string(5, 20, "Nett Premi", $format);
		

		$baris = 6;
				
		$metCOB = mysql_query($thisEncrypter->decode($_SESSION['lprmemberasviewapp']));
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {

			$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];			
			$worksheet1->write_string($baris, 0, ++$no, 'C');			
			$worksheet1->write_string($baris, 1, $metCOB_['nmasuransi']);
			$worksheet1->write_string($baris, 2, $metCOB_['idpeserta']);
			$worksheet1->write_string($baris, 3, $metCOB_['nopinjaman']);
			$worksheet1->write_string($baris, 4, $metCOB_['nama']);			
			$worksheet1->write_string($baris, 5, $metCOB_['tgllahir']);
			$worksheet1->write_string($baris, 6, $metCOB_['tglakad']);
			$worksheet1->write_string($baris, 7, $metCOB_['tglakhir']);
			$worksheet1->write_string($baris, 8, $metCOB_['plafond']);
			$worksheet1->write_string($baris, 9, $metCOB_['usia']);			
			$worksheet1->write_string($baris, 10, $metCOB_['tenor']);
			$worksheet1->write_string($baris, 11, $metCOB_['nm_kategori_profesi']);
			$worksheet1->write_string($baris, 12, $metCOB_['nmcabang']);
			$worksheet1->write_string($baris, 13, $metCOB_['premirate']);
			$worksheet1->write_string($baris, 14, $metCOB_['premi']);
			$worksheet1->write_string($baris, 15, $metCOB_['aspremirate']);
			$worksheet1->write_string($baris, 16, $metCOB_['aspremi']);
			$worksheet1->write_string($baris, 17, $metCOB_['bf']);			
			$worksheet1->write_string($baris, 18, $metCOB_['cad_klaim']);
			$worksheet1->write_string($baris, 19, $metCOB_['cad_premi']);
			$worksheet1->write_string($baris, 20, $metCOB_['premi'] - $metCOB_['bf'] - $metCOB_['cad_klaim'] - $metCOB_['cad_premi']);

			$baris++;
			$tPremi += $metCOB_['totalpremi'];
			$tPremias += $metCOB_['astotalpremi'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 16);
		$worksheet1->write_number($baris, 15, $tPremi, $ftotal);

		$workbook->close();
		;
	break;

	case "lprmemberIns":
		$filename = "INSURANCE";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel(_convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).'_'._convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		if ($thisEncrypter->decode($_REQUEST['idb'])) {	$satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idp'])) {	$tiga ='AND ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['ida'])) {	$empat ='AND ajkinsurance.id = "'.$thisEncrypter->decode($_REQUEST['ida']).'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id,
																								  ajkcobroker.logo,
																								  ajkcobroker.`name` AS brokername,
																								  ajkclient.`name` AS clientname,
																								  ajkclient.logo AS logoclient,
																								  ajkpolis.produk,
																								  ajkpolis.policymanual,
																								  ajkpolis.byrate,
																								  ajkinsurance.`name` AS insurancename
																						  FROM ajkcobroker
																						  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
																						  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
																						  INNER JOIN ajkinsurance ON ajkcobroker.id = ajkinsurance.idc
																						  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.''));

		if ($thisEncrypter->decode($_REQUEST['idb']))	{	$_metbroker 	= $met_['brokername'];	}else{	$_metbroker = 'ALL BROKER';	}
		if ($thisEncrypter->decode($_REQUEST['idc']))	{	$_metclient 	= $met_['clientname'];		}else{	$_metclient = 'ALL CLIENT';	}
		if ($thisEncrypter->decode($_REQUEST['idp']))	{	$_metproduk 	= $met_['produk'];			}else{	$_metproduk = 'ALL PRODUCT';	}
		if ($thisEncrypter->decode($_REQUEST['ida']))	{	$_metinsurance 	= $met_['insurancename'];	}else{	$_metproduk = 'ALL INSURANCE';	}

		$worksheet1->write_string(0, 0, "LAPORAN DATA DEBITUR ASURANSI ".strtoupper($_metinsurance), $fjudul);	$worksheet1->merge_cells(0, 0, 0, 23);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);		$worksheet1->merge_cells(1, 0, 1, 23);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);		$worksheet1->merge_cells(2, 0, 2, 23);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);		$worksheet1->merge_cells(3, 0, 3, 23);
		$worksheet1->write_string(4, 0, 'PERIODE '._convertDateEng(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).' s/d '._convertDateEng(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))), $fjudul);	$worksheet1->merge_cells(4, 0, 4, 23);

		$worksheet1->set_row(7, 15);
		$worksheet1->set_column(7, 0, 1);	$worksheet1->write_string(6, 0, "No", $format);
		$worksheet1->set_column(7, 1, 30);	$worksheet1->write_string(6, 1, "Asuransi", $format);
		$worksheet1->set_column(7, 2, 30);	$worksheet1->write_string(6, 2, "Produk", $format);
		$worksheet1->set_column(7, 3, 20);	$worksheet1->write_string(6, 3, "ID Debitur", $format);
		$worksheet1->set_column(7, 4, 15);	$worksheet1->write_string(6, 4, "Nama Debitur", $format);
		$worksheet1->set_column(7, 5, 15);	$worksheet1->write_string(6, 5, "Tanggal Lahir", $format);
		$worksheet1->set_column(7, 6, 30);	$worksheet1->write_string(6, 6, "Jenis Kelamin", $format);
		$worksheet1->set_column(7, 7, 10);	$worksheet1->write_string(6, 7, "Mulai Asuransi", $format);
		$worksheet1->set_column(7, 8, 10);	$worksheet1->write_string(6, 8, "JWP (Bulan)", $format);
		$worksheet1->set_column(7, 9, 10);	$worksheet1->write_string(6, 9, "Harga Pertanggungan", $format);
		$worksheet1->set_column(7, 10, 5);	$worksheet1->write_string(6, 10, "Usia Masuk", $format);
		$worksheet1->set_column(7, 11, 10);	$worksheet1->write_string(6, 11, "Akhir Asuransi", $format);
		$worksheet1->set_column(7, 12, 5);	$worksheet1->write_string(6, 12, "Usia + JWP", $format);
		$worksheet1->set_column(7, 13, 10);	$worksheet1->write_string(6, 13, "Rate", $format);
		$worksheet1->set_column(7, 14, 10);	$worksheet1->write_string(6, 14, "Premi", $format);
		$worksheet1->set_column(7, 15, 10);	$worksheet1->write_string(6, 15, "Brokerage", $format);
		$worksheet1->set_column(7, 16, 10);	$worksheet1->write_string(6, 16, "Feebase", $format);
		$worksheet1->set_column(7, 17, 10);	$worksheet1->write_string(6, 17, "Cad. Klaim", $format);
		$worksheet1->set_column(7, 18, 10);	$worksheet1->write_string(6, 18, "Cad. Premi", $format);
		$worksheet1->set_column(7, 19, 10);	$worksheet1->write_string(6, 19, "PPN", $format);
		$worksheet1->set_column(7, 20, 10);	$worksheet1->write_string(6, 20, "PPh", $format);
		$worksheet1->set_column(7, 21, 10);	$worksheet1->write_string(6, 21, "Total Premi", $format);
		$worksheet1->set_column(7, 22, 10);	$worksheet1->write_string(6, 22, "Status", $format);
		$worksheet1->set_column(7, 23, 20);	$worksheet1->write_string(6, 23, "Cabang", $format);


		$baris = 7;
				
		$metCOB = mysql_query($thisEncrypter->decode($_SESSION['lprmemberIns']));

		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];

			$worksheet1->write_string($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metCOB_['asuransi']);
			$worksheet1->write_string($baris, 2, $metCOB_['produk']);
			$worksheet1->write_string($baris, 3, $metCOB_['idpeserta']);
			$worksheet1->write_string($baris, 4, $metCOB_['nama']);
			$worksheet1->write_string($baris, 5, $metCOB_['tgllahir']);
			$worksheet1->write_string($baris, 6, $metCOB_['jnskelamin']);
			$worksheet1->write_string($baris, 7, _convertDate($metCOB_['tglakad']));
			$worksheet1->write_number($baris, 8, $metCOB_['tenor']);
			$worksheet1->write_number($baris, 9, $metCOB_['plafond']);
			$worksheet1->write_number($baris, 10, $metCOB_['usia']);
			$worksheet1->write_string($baris, 11, _convertDate($metCOB_['tglakhir']));
			$worksheet1->write_number($baris, 12, $usiatenor);
			$worksheet1->write_string($baris, 13, $metCOB_['aspremirate']);
			$worksheet1->write_number($baris, 14, $metCOB_['astotalpremi']);
			$worksheet1->write_number($baris, 15, $metCOB_['brokerage']);
			$worksheet1->write_number($baris, 16, $metCOB_['feebase']);
			$worksheet1->write_number($baris, 17, $metCOB_['cad_klaim']);
			$worksheet1->write_number($baris, 18, $metCOB_['cad_premi']);
			$worksheet1->write_number($baris, 19, $metCOB_['ppn']);
			$worksheet1->write_number($baris, 20, $metCOB_['pph']);
			$worksheet1->write_number($baris, 21, $metCOB_['astotalpremi']-$metCOB_['brokerage']-$metCOB_['ppn']+$metCOB_['pph']-$metCOB_['feebase']-$metCOB_['cad_klaim']-$metCOB_['cad_premi']);
			$worksheet1->write_string($baris, 22, $metCOB_['statusaktif']);
			$worksheet1->write_string($baris, 23, $metCOB_['cabang']);


			$baris++;
			$tPremias += $metCOB_['astotalpremi'];
			$tPlafondas += $metCOB_['plafond'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 8);
		$worksheet1->write_number($baris, 9, $tPlafondas, $ftotal);
		$worksheet1->write_number($baris, 23, $tPremias, $ftotal);

		$workbook->close();
	break;

	case "armpayment":
		$filename = "PAYMENT";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel(_convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).'_'._convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		if ($thisEncrypter->decode($_REQUEST['idb'])) {	$satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		$met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
		if ($thisEncrypter->decode($_REQUEST['idp'])) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

		if ($_REQUEST['idb']==""){	$_metbroker = '';	}else{	$_metbroker = $met_['brokername'];	}
		if ($_REQUEST['idc']==""){	$_metclient = 'ALL CLIENT';	}else{	$_metclient = $met_['clientname'];	}
		if ($_REQUEST['idp']==""){	$_metproduk = 'ALL PRODUCT';	}else{	$_metproduk = $met_['produk'];	}

		$worksheet1->write_string(0, 0, "REPORT PAYMENTS", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 6);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 6);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 6);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 6);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "NO", $format);
		$worksheet1->set_column(5, 1, 40);	$worksheet1->write_string(5, 1, "Debitnote", $format);
		$worksheet1->set_column(5, 2, 10);	$worksheet1->write_string(5, 2, "Date DN", $format);
		$worksheet1->set_column(5, 3, 15);	$worksheet1->write_string(5, 3, "Premium", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "Status", $format);
		$worksheet1->set_column(5, 5, 10);	$worksheet1->write_string(5, 5, "Date Payment", $format);
		$worksheet1->set_column(5, 6, 10);	$worksheet1->write_string(5, 6, "Branch", $format);

		$baris = 6;
		if ($thisEncrypter->decode($_REQUEST['idb']))	{	$satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc']))	{	$dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';		}
		/*
		   if ($_REQUEST['idp'])	{	$tiga = 'AND ajkdebitnote.idproduk="'.$thisEncrypter->decode($_REQUEST['idp']).'"';	}
		   if ($_REQUEST['st'])	{	$empat = 'AND ajkdebitnote.paidstatus="'.$thisEncrypter->decode($_REQUEST['st']).'"';	}
		*/

		if ($thisEncrypter->decode($_REQUEST['st'])=="1") 		{	$_datapaid="Paid";
		}elseif ($thisEncrypter->decode($_REQUEST['st'])=="2")	{	$_datapaid="Paid*";
		}else{	$_datapaid="Unpaid";	}
		if ($thisEncrypter->decode($_REQUEST['idp']))	{	$tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';	}
		if ($thisEncrypter->decode($_REQUEST['st']))	{	$empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';	}

		$metCOB = mysql_query('SELECT
		ajkdebitnote.id,
		ajkdebitnote.idbroker,
		ajkdebitnote.idclient,
		ajkdebitnote.idproduk,
		ajkdebitnote.idas,
		ajkdebitnote.idaspolis,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.tgldebitnote,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.premiclient,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal
		FROM ajkdebitnote
		INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
		WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"');
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
				$tgllunas = '';
			}else{
				$tgllunas = _convertDate($metCOB_['paidtanggal']);
			}

			$worksheet1->write_string($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metCOB_['nomordebitnote']);
			$worksheet1->write_string($baris, 2, $metCOB_['tgldebitnote']);
			$worksheet1->write_number($baris, 3, $metCOB_['premiclient']);
			$worksheet1->write_string($baris, 4, $metCOB_['paidstatus']);
			$worksheet1->write_string($baris, 5, $tgllunas);
			$worksheet1->write_string($baris, 6, $metCOB_['cabang']);

			$baris++;
			$tPremi += $metCOB_['premiclient'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 2);
		$worksheet1->write_number($baris, 3, $tPremi, $ftotal);
		$workbook->close();
		;
	break;

	case "rptdebitnote":
		$filename = "DEBITNOTE BANK";
		$filename1 = "CREDITNOTE INS";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel(_convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).'_'._convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);
		$worksheet2 =& $workbook->add_worksheet($filename1);

		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();

		if ($thisEncrypter->decode($_REQUEST['idb'])) {	$satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		$met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
		if ($thisEncrypter->decode($_REQUEST['idp'])) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

		if ($_REQUEST['idb']==""){	$_metbroker = '';	}else{	$_metbroker = $met_['brokername'];	}
		if ($thisEncrypter->decode($_REQUEST['idc'])==""){	$_metclient = 'ALL CLIENT';	}else{	$_metclient = $met_['clientname'];	}
		if ($thisEncrypter->decode($_REQUEST['idp'])==""){	$_metproduk = 'ALL PRODUCT';	}else{	$_metproduk = $met_['produk'];	}

		$worksheet1->write_string(0, 0, "REPORT PAYMENTS BANK", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 9);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 9);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 9);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 9);

		$worksheet2->write_string(0, 0, "REPORT PAYMENTS INSURANCE", $fjudul);	$worksheet2->merge_cells(0, 0, 0, 11);
		$worksheet2->write_string(1, 0, strtoupper($_metbroker), $fjudul);		$worksheet2->merge_cells(1, 0, 1, 11);
		$worksheet2->write_string(2, 0, strtoupper($_metclient), $fjudul);		$worksheet2->merge_cells(2, 0, 2, 11);
		$worksheet2->write_string(3, 0, strtoupper($_metproduk), $fjudul);		$worksheet2->merge_cells(3, 0, 3, 11);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "NO", $format);
		$worksheet1->set_column(5, 1, 40);	$worksheet1->write_string(5, 1, "Perusahaan", $format);
		$worksheet1->set_column(5, 2, 40);	$worksheet1->write_string(5, 2, "produk", $format);
		$worksheet1->set_column(5, 3, 40);	$worksheet1->write_string(5, 3, "Debitnote", $format);
		$worksheet1->set_column(5, 4, 10);	$worksheet1->write_string(5, 4, "Date DN", $format);
		$worksheet1->set_column(5, 5, 15);	$worksheet1->write_string(5, 5, "Member", $format);
		$worksheet1->set_column(5, 6, 15);	$worksheet1->write_string(5, 6, "Premium", $format);
		$worksheet1->set_column(5, 7, 15);	$worksheet1->write_string(5, 7, "Status", $format);
		$worksheet1->set_column(5, 8, 10);	$worksheet1->write_string(5, 8, "Date Payment", $format);
		$worksheet1->set_column(5, 9, 10);	$worksheet1->write_string(5, 9, "Branch", $format);

		$worksheet2->set_row(5, 15);
		$worksheet2->set_column(5, 0, 1);	$worksheet2->write_string(5, 0, "NO", $format);
		$worksheet2->set_column(5, 1, 40);	$worksheet2->write_string(5, 1, "Perusahaan", $format);
		$worksheet2->set_column(5, 2, 40);	$worksheet2->write_string(5, 2, "produk", $format);
		$worksheet2->set_column(5, 3, 40);	$worksheet2->write_string(5, 3, "Debitnote", $format);
		$worksheet2->set_column(5, 4, 10);	$worksheet2->write_string(5, 4, "Date DN", $format);
		$worksheet2->set_column(5, 5, 15);	$worksheet2->write_string(5, 5, "Member", $format);
		$worksheet2->set_column(5, 6, 15);	$worksheet2->write_string(5, 6, "Premium", $format);
		$worksheet2->set_column(5, 7, 15);	$worksheet2->write_string(5, 7, "Status", $format);
		$worksheet2->set_column(5, 8, 10);	$worksheet2->write_string(5, 8, "Date Payment", $format);
		$worksheet2->set_column(5, 9, 10);	$worksheet2->write_string(5, 9, "Branch", $format);
		$worksheet2->set_column(5, 10, 10);	$worksheet2->write_string(5, 10, "Insurance", $format);
		$worksheet2->set_column(5, 11, 10);	$worksheet2->write_string(5, 11, "Policy Insurance", $format);

		$baris = 6;
		if ($thisEncrypter->decode($_REQUEST['idb']))	{	$satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc']))	{	$dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';		}
		if ($thisEncrypter->decode($_REQUEST['idp']))	{	$tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';	}
		if ($thisEncrypter->decode($_REQUEST['st'])=="1") 		{	$_datapaid="Paid";
		}elseif ($thisEncrypter->decode($_REQUEST['st'])=="2")	{	$_datapaid="Paid*";
		}else{	$_datapaid="Unpaid";	}
		if ($thisEncrypter->decode($_REQUEST['st']))	{	$empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';	}

		$metCOB = mysql_query('SELECT
		ajkdebitnote.id,
		ajkdebitnote.idbroker,
		ajkdebitnote.idclient,
		ajkdebitnote.idproduk,
		ajkdebitnote.idas,
		ajkdebitnote.idaspolis,
		ajkcabang.name AS cabang,
		ajkdebitnote.tgldebitnote,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.premiclient,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.premiasuransi,
		ajkdebitnote.as_paidstatus,
		ajkdebitnote.as_paidtgl,
		Count(ajkpeserta.nama) AS jmember,
		ajkinsurance.name AS asuransi,
		ajkclient.name AS perusahaan,
		ajkpolis.produk AS produk,
		ajkpolisasuransi.policymanual AS asuransipolis
		FROM ajkdebitnote
		INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
		INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
		INNER JOIN ajkinsurance ON ajkdebitnote.idas = ajkinsurance.id
		LEFT JOIN ajkpolisasuransi ON ajkdebitnote.idaspolis = ajkpolisasuransi.id
		WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"
		GROUP BY ajkdebitnote.id');
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {	$tgllunas = '';	}else{	$tgllunas = _convertDate($metCOB_['paidtanggal']);	}
			if ($metCOB_['as_paidtgl']=="" OR $metCOB_['as_paidtgl']=="0000-00-00") {	$tgllunasAS = '';	}else{	$tgllunasAS = _convertDate($metCOB_['as_paidtgl']);	}

			$worksheet1->write_string($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metCOB_['perusahaan']);
			$worksheet1->write_string($baris, 2, $metCOB_['produk']);
			$worksheet1->write_string($baris, 3, $metCOB_['nomordebitnote']);
			$worksheet1->write_string($baris, 4, $metCOB_['tgldebitnote']);
			$worksheet1->write_number($baris, 5, $metCOB_['jmember']);
			$worksheet1->write_number($baris, 6, $metCOB_['premiclient']);
			$worksheet1->write_string($baris, 7, $metCOB_['paidstatus']);
			$worksheet1->write_string($baris, 8, $tgllunas);
			$worksheet1->write_string($baris, 9, $metCOB_['cabang']);

			$worksheet2->write_string($baris, 0, ++$no1, 'C');
			$worksheet2->write_string($baris, 1, $metCOB_['perusahaan']);
			$worksheet2->write_string($baris, 2, $metCOB_['produk']);
			$worksheet2->write_string($baris, 3, $metCOB_['nomordebitnote']);
			$worksheet2->write_string($baris, 4, $metCOB_['tgldebitnote']);
			$worksheet2->write_number($baris, 5, $metCOB_['jmember']);
			$worksheet2->write_number($baris, 6, $metCOB_['premiasuransi']);
			$worksheet2->write_string($baris, 7, $metCOB_['as_paidstatus']);
			$worksheet2->write_string($baris, 8, $tgllunasAS);
			$worksheet2->write_string($baris, 9, $metCOB_['cabang']);
			$worksheet2->write_string($baris, 10, $metCOB_['asuransi']);
			$worksheet2->write_string($baris, 11, $metCOB_['asuransipolis']);

			$baris++;
			$tPremi += $metCOB_['premiclient'];
			$tPremiAs += $metCOB_['premiasuransi'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 5);	$worksheet1->write_number($baris, 6, $tPremi, $ftotal);
		$worksheet2->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet2->merge_cells($baris, 0, $baris, 5);	$worksheet2->write_number($baris, 6, $tPremiAs, $ftotal);

		$workbook->close();
		;
	break;

	case "rategeneral":
		$filename1 = "RATE_COMPREHENSIVE";
		$filename2 = "RATE_TOTALLOSONLY";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		$fRateGen = mysql_fetch_array(mysql_query('SELECT ajkclient.name, ajkpolis.produk
										   FROM ajkclient
										   INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
										   WHERE ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'" '));
		HeaderingExcel('FORMAT_RATE_'.str_replace(" ","_" ,$fRateGen['name']).'_'.str_replace(" ","_" ,$fRateGen['produk']).'.xls');
		$workbook = new Workbook("");

		$worksheet1 =& $workbook->add_worksheet($filename1);
		$worksheet1->set_column(0, 0, 10);	$worksheet1->write_string(0, 0, "No", $format);
		$worksheet1->set_column(0, 1, 10);	$worksheet1->write_string(0, 1, "TenorStart", $format);
		$worksheet1->set_column(0, 2, 10);	$worksheet1->write_string(0, 2, "TenorEnd", $format);
		$worksheet1->set_column(0, 3, 10);	$worksheet1->write_string(0, 3, "PlafondStart", $format);
		$worksheet1->set_column(0, 4, 10);	$worksheet1->write_string(0, 4, "PlafondEnd", $format);
		$worksheet1->set_column(0, 5, 10);	$worksheet1->write_string(0, 5, "KodeLokasi", $format);
		$worksheet1->set_column(0, 6, 10);	$worksheet1->write_string(0, 6, "KodePertanggungan", $format);
		$worksheet1->set_column(0, 7, 10);	$worksheet1->write_string(0, 7, "KodeKelas", $format);
		$worksheet1->set_column(0, 8, 10);	$worksheet1->write_string(0, 8, "Rate", $format);


		$worksheet2 =& $workbook->add_worksheet($filename2);
		$worksheet2->set_column(0, 0, 10);	$worksheet2->write_string(0, 0, "No", $format);
		$worksheet2->set_column(0, 1, 10);	$worksheet2->write_string(0, 1, "TenorStart", $format);
		$worksheet2->set_column(0, 2, 10);	$worksheet2->write_string(0, 2, "TenorEnd", $format);
		$worksheet2->set_column(0, 3, 10);	$worksheet2->write_string(0, 3, "PlafondStart", $format);
		$worksheet2->set_column(0, 4, 10);	$worksheet2->write_string(0, 4, "PlafondEnd", $format);
		$worksheet2->set_column(0, 5, 10);	$worksheet2->write_string(0, 5, "KodeLokasi", $format);
		$worksheet2->set_column(0, 6, 10);	$worksheet2->write_string(0, 6, "KodePertanggungan", $format);
		$worksheet2->set_column(0, 7, 10);	$worksheet2->write_string(0, 7, "KodeKelas", $format);
		$worksheet2->set_column(0, 8, 10);	$worksheet2->write_string(0, 8, "Rate", $format);

		$workbook->close();
		;
	break;

	case "lprdataspk":
		$filename = "DATA_SPK";
		function HeaderingExcel($filename) {
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$filename" );
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
		}
		HeaderingExcel(_convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom']))).'_'._convertDate(_convertDateEng2($thisEncrypter->decode($_REQUEST['dtto']))).'_'.$filename.'.xls');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);
		$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
		$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
		$ftotal =& $workbook->add_format();		$ftotal->set_bold();
		if ($thisEncrypter->decode($_REQUEST['idb'])!="") {	$satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc'])!="") {	$dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idp'])!="") {
			//$tiga ='AND ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"';
			$metProduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
			$tiga ='AND ajkpolis.id = "'.$metProduk[0].'"';
		}

		$metDataSPK = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
											  FROM ajkcobroker
											  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
											  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
											  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

		if ($_REQUEST['idb']==""){	$_metbroker = '';	}else{	$_metbroker = $metDataSPK['brokername'];	}
		if ($thisEncrypter->decode($_REQUEST['idc'])) {	$_metclient = $metDataSPK['clientname'];	}else{	$_metclient = 'ALL CLIENT';	}
		if ($thisEncrypter->decode($_REQUEST['idp'])) {	$_metproduk = $metDataSPK['produk'];		}else{	$_metproduk = 'ALL PRODUCT';	}

		$worksheet1->write_string(0, 0, "LAPORAN DATA DEBITUR SPK", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 16);
		$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 16);
		$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 16);
		$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 16);

		$worksheet1->set_row(5, 15);
		$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "No", $format);
		$worksheet1->set_column(5, 1, 40);	$worksheet1->write_string(5, 1, "Perusahaan", $format);
		$worksheet1->set_column(5, 2, 40);	$worksheet1->write_string(5, 2, "Produk", $format);
		$worksheet1->set_column(5, 3, 20);	$worksheet1->write_string(5, 3, "SPK", $format);
		$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "Status", $format);
		$worksheet1->set_column(5, 5, 25);	$worksheet1->write_string(5, 5, "Nama", $format);
		$worksheet1->set_column(5, 6, 15);	$worksheet1->write_string(5, 6, "Tgl Lahir", $format);
		$worksheet1->set_column(5, 7, 5);	$worksheet1->write_string(5, 7, "Usia", $format);
		$worksheet1->set_column(5, 8, 10);	$worksheet1->write_string(5, 8, "Mulai Asuransi", $format);
		$worksheet1->set_column(5, 9, 5);	$worksheet1->write_string(5, 9, "Tenor", $format);
		$worksheet1->set_column(5, 10, 10);	$worksheet1->write_string(5, 10, "Akhir Asuransi", $format);
		$worksheet1->set_column(5, 11, 15);	$worksheet1->write_string(5, 11, "Plafond", $format);
		$worksheet1->set_column(5, 12, 10);	$worksheet1->write_string(5, 12, "Premi", $format);
		$worksheet1->set_column(5, 13, 5);	$worksheet1->write_string(5, 13, "EM", $format);
		$worksheet1->set_column(5, 14, 10);	$worksheet1->write_string(5, 14, "Nett Premi", $format);
		$worksheet1->set_column(5, 15, 20);	$worksheet1->write_string(5, 15, "Cabang", $format);
		$worksheet1->set_column(5, 16, 10);	$worksheet1->write_string(5, 16, "Tgl Input", $format);

		$baris = 6;
		if ($thisEncrypter->decode($_REQUEST['idb']))	{	$spksatu = 'AND ajkspk.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idc']))	{	$spkdua = 'AND ajkspk.idpartner="'.$thisEncrypter->decode($_REQUEST['idc']).'"';	}
		if ($thisEncrypter->decode($_REQUEST['idp']))	{	$metEx = explode("_",$thisEncrypter->decode($_REQUEST['idp']));
															$spktiga = 'AND ajkspk.idproduk="'.$metEx[0].'"';
															}
		if ($thisEncrypter->decode($_REQUEST['st']))	{	$spkempat = 'AND ajkspk.statusspk="'.$thisEncrypter->decode($_REQUEST['st']).'"';	}

		$metSPK = mysql_query('SELECT
		ajkspk.id,
		ajkspk.idbroker,
		ajkspk.idpartner,
		ajkspk.idproduk,
		ajkcobroker.`name` AS namabroker,
		ajkclient.`name` AS namaperusahaan,
		ajkpolis.produk AS namaproduk,
		ajkratepremi.rate,
		ajkspk.nomorspk,
		ajkspk.statusspk,
		ajkspk.nama,
		ajkspk.dob,
		ajkspk.usia,
		ajkspk.tglakad,
		ajkspk.tenor,
		ajkspk.tglakhir,
		ajkspk.mppbln,
		ajkspk.plafond,
		ajkspk.premi,
		ajkspk.em,
		ajkspk.premiem,
		ajkspk.nettpremi,
		ajkspk.cabang,
		ajkcabang.`name` AS namacabang,
		DATE_FORMAT(ajkspk.input_date,"%Y-%m-%d") AS tglinput
		FROM ajkspk
		INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
		INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
		LEFT JOIN ajkratepremi ON ajkspk.idrate = ajkratepremi.id
		INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
		WHERE
		ajkspk.del IS NULL '.$spksatu.' '.$spkdua.' '.$spktiga.' '.$spkempat.' AND DATE_FORMAT(ajkspk.input_date,"%Y-%m-%d") BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"
		ORDER BY ajkspk.input_date DESC');
		while ($metSPK_ = mysql_fetch_array($metSPK)) {
			$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];

			$worksheet1->write_number($baris, 0, ++$no, 'C');
			$worksheet1->write_string($baris, 1, $metSPK_['namaperusahaan']);
			$worksheet1->write_string($baris, 2, $metSPK_['namaproduk']);
			$worksheet1->write_string($baris, 3, $metSPK_['nomorspk']);
			$worksheet1->write_string($baris, 4, $metSPK_['statusspk']);
			$worksheet1->write_string($baris, 5, $metSPK_['nama']);
			$worksheet1->write_string($baris, 6, _convertDate($metSPK_['dob']));
			$worksheet1->write_number($baris, 7, $metSPK_['usia']);
			$worksheet1->write_string($baris, 8, _convertDate($metSPK_['tglakad']));
			$worksheet1->write_number($baris, 9, $metSPK_['tenor']);
			$worksheet1->write_string($baris, 10, _convertDate($metSPK_['tglakhir']));
			$worksheet1->write_number($baris, 11, $metSPK_['plafond']);
			$worksheet1->write_number($baris, 12, $metSPK_['premi']);
			$worksheet1->write_number($baris, 13, $metSPK_['em']);
			$worksheet1->write_number($baris, 14, $metSPK_['nettpremi']);
			$worksheet1->write_string($baris, 15, $metSPK_['namacabang']);
			$worksheet1->write_string($baris, 16, _convertDate($metSPK_['tglinput']));

			$baris++;
			$tPremi += $metSPK_['premi'];
			$tEM += $metSPK_['em'];
			$tNettPremi += $metSPK_['nettpremi'];
		}
		$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 11);
		$worksheet1->write_number($baris, 12, $tPremi, $ftotal);
		$worksheet1->write_number($baris, 13, $tEM, $ftotal);
		$worksheet1->write_number($baris, 14, $tNettPremi, $ftotal);

		$workbook->close();		
	break;

	case "putjatim03":
		$filename = "adonai";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel($filename.date("Ymd").'-03.csv');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$query = "SELECT nopinjaman,idpeserta 
							FROM ajkpeserta 
							WHERE DATE_FORMAT(input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')";
		$put03 = mysql_query($query);

		while ($put03_ = mysql_fetch_array($put03)) {

			$worksheet1->write_string($baris, 0, $put03_['nopinjaman'].'|'.$put03_['idpeserta']);
			//$worksheet1->write_string($baris, 1, );
			$baris++;
		}
	
		$workbook->close();		
	break;

	case "putjatim04":
		$filename = "adonai";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel($filename.date("Ymd").'-04.csv');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$query = "SELECT nopinjaman,
											nilaiclaimclient 
							FROM ajkcreditnote 
									 INNER JOIN ajkpeserta
									 ON ajkpeserta.id = ajkcreditnote.idpeserta
							WHERE tipeklaim = 'Restitusi' /*and 
										DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')*/";
		$put04 = mysql_query($query);

		while ($put04_ = mysql_fetch_array($put04)) {

			$worksheet1->write_string($baris, 0, $put04_['nopinjaman'].'|'.$put04_['nilaiclaimclient']);
			//$worksheet1->write_string($baris, 1, );
			$baris++;
		}

		$workbook->close();
	break;

	case "putjatim05":
		$filename = "adonai";
		function HeaderingExcel($filename) {
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
		}
		HeaderingExcel($filename.date("Ymd").'-05.csv');
		$workbook = new Workbook("");
		$worksheet1 =& $workbook->add_worksheet($filename);

		$query = "SELECT nopinjaman,
											status 
							FROM ajkcreditnote 
									 INNER JOIN ajkpeserta
									 ON ajkpeserta.id = ajkcreditnote.idpeserta
							WHERE tipeklaim = 'Claim' /*and 
										DATE_FORMAT(ajkcreditnote.input_time,'%Y-%m-%d') = DATE_FORMAT(now(),'%Y-%m-%d')*/";
		$put04 = mysql_query($query);

		while ($put04_ = mysql_fetch_array($put04)) {

			$worksheet1->write_string($baris, 0, $put04_['nopinjaman'].'|'.$put04_['status']);
			//$worksheet1->write_string($baris, 1, );
			$baris++;
		}

		$workbook->close();
	break;

	default:
	;
} // switch

?>