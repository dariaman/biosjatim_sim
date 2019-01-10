<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
error_reporting(0);
require_once('../includes/metPHPXLS/Worksheet.php');
require_once('../includes/metPHPXLS/Workbook.php');
include_once('../includes/fu6106.php');
include_once('../includes/functions.php');
include_once('../koneksi.php');
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

$colField = mysql_fetch_array(mysql_query('SELECT ajkexcelupload.id, Count(ajkexcelupload.idxls) AS jumField, ajkclient.`name`, ajkpolis.policyauto FROM ajkexcelupload INNER JOIN ajkclient ON ajkexcelupload.idc = ajkclient.id INNER JOIN ajkpolis ON ajkexcelupload.idp = ajkpolis.id WHERE ajkexcelupload.idb="'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'" AND ajkexcelupload.idc="'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'" AND ajkexcelupload.idp="'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'" GROUP BY ajkexcelupload.idp'));
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
						 WHERE ajkexcelupload.idb = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'" AND
						 	   ajkexcelupload.idc = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'" AND
						 	   ajkexcelupload.idp = "'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'"
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
/*
echo AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY).'<br />';
*/
$filename = "MEMBER_BANK";
$filename1 = "MEMBER_INSURANCE";
function HeaderingExcel($filename) {
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename" );
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Pragma: public");
}
HeaderingExcel(_convertDate(_convertDateEng2(AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY))).'_'._convertDate(_convertDateEng2(AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY))).'_'.$filename.'.xls');
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet($filename);
$worksheet2 =& $workbook->add_worksheet($filename1);

$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
$ftotal =& $workbook->add_format();		$ftotal->set_bold();

$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.id = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'" AND ajkclient.id = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'" AND ajkpolis.id = "'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'" '));

if ($_REQUEST['idb']==""){	$_metbroker = '';	}else{	$_metbroker = $met_['brokername'];	}
if ($_REQUEST['idc']==""){	$_metclient = 'ALL CLIENT';	}else{	$_metclient = $met_['clientname'];	}
if ($_REQUEST['idp']==""){	$_metproduk = 'ALL PRODUCT';	}else{	$_metproduk = $met_['produk'];	}
$worksheet1->write_string(0, 0, "MEMBERSHIP DATA REPORT", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 15);
$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 15);
$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 15);
$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 15);
$worksheet2->write_string(0, 0, "MEMBERSHIP DATA REPORT", $fjudul);	$worksheet2->merge_cells(0, 0, 0, 15);
$worksheet2->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet2->merge_cells(1, 0, 1, 15);
$worksheet2->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet2->merge_cells(2, 0, 2, 15);
$worksheet2->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet2->merge_cells(3, 0, 3, 15);

$worksheet1->set_row(5, 15);
$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "NO", $format);
$worksheet1->set_column(5, 1, 30);	$worksheet1->write_string(5, 1, "Debitnote", $format);
$worksheet1->set_column(5, 2, 15);	$worksheet1->write_string(5, 2, "Date DN", $format);
$worksheet1->set_column(5, 3, 15);	$worksheet1->write_string(5, 3, "KTP", $format);
$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "ID Member", $format);
$worksheet1->set_column(5, 5, 30);	$worksheet1->write_string(5, 5, "Name", $format);
$worksheet1->set_column(5, 6, 10);	$worksheet1->write_string(5, 6, "BOD", $format);
$worksheet1->set_column(5, 7, 5);	$worksheet1->write_string(5, 7, "Age", $format);
$worksheet1->set_column(5, 8, 10);	$worksheet1->write_string(5, 8, "Plafond", $format);
$worksheet1->set_column(5, 9, 10);	$worksheet1->write_string(5, 9, "Start Insurance", $format);
$worksheet1->set_column(5, 10, 5);	$worksheet1->write_string(5, 10, "Tenor", $format);
$worksheet1->set_column(5, 11, 10);	$worksheet1->write_string(5, 11, "End Insurance", $format);
$worksheet1->set_column(5, 12, 10);	$worksheet1->write_string(5, 12, "Rate", $format);
$worksheet1->set_column(5, 13, 15);	$worksheet1->write_string(5, 13, "Nett Premium", $format);
$worksheet1->set_column(5, 14, 10);	$worksheet1->write_string(5, 14, "Status", $format);
$worksheet1->set_column(5, 15, 20);	$worksheet1->write_string(5, 15, "Branch", $format);


$worksheet2->set_row(5, 13);
$worksheet2->set_column(5, 0, 1);	$worksheet2->write_string(5, 0, "NO", $format);
$worksheet2->set_column(5, 1, 30);	$worksheet2->write_string(5, 1, "Debitnote", $format);
$worksheet2->set_column(5, 2, 15);	$worksheet2->write_string(5, 2, "Date DN", $format);
$worksheet2->set_column(5, 3, 15);	$worksheet2->write_string(5, 3, "KTP", $format);
$worksheet2->set_column(5, 4, 15);	$worksheet2->write_string(5, 4, "ID Member", $format);
$worksheet2->set_column(5, 5, 30);	$worksheet2->write_string(5, 5, "Name", $format);
$worksheet2->set_column(5, 6, 10);	$worksheet2->write_string(5, 6, "BOD", $format);
$worksheet2->set_column(5, 7, 5);	$worksheet2->write_string(5, 7, "Age", $format);
$worksheet2->set_column(5, 8, 10);	$worksheet2->write_string(5, 8, "Plafond", $format);
$worksheet2->set_column(5, 9, 10);	$worksheet2->write_string(5, 9, "Start Insurance", $format);
$worksheet2->set_column(5, 10, 5);	$worksheet2->write_string(5, 10, "Tenor", $format);
$worksheet2->set_column(5, 11, 10);	$worksheet2->write_string(5, 11, "End Insurance", $format);
$worksheet2->set_column(5, 12, 10);	$worksheet2->write_string(5, 12, "Rate", $format);
$worksheet2->set_column(5, 13, 15);	$worksheet2->write_string(5, 13, "Nett Premium", $format);
$worksheet2->set_column(5, 14, 10);	$worksheet2->write_string(5, 14, "Status", $format);
$worksheet2->set_column(5, 15, 20);	$worksheet2->write_string(5, 15, "Branch", $format);

$baris = 6;
if ($_REQUEST['coBroker'])	{	$satu = 'AND ajkdebitnote.idbroker="'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
if ($_REQUEST['coClient'])	{	$dua = 'AND ajkdebitnote.idclient="'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';		}
if ($_REQUEST['coProduct'])	{	$tiga = 'AND ajkdebitnote.idproduk="'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'"';	}
if ($_REQUEST['datastatus']){	$empat = 'AND ajkpeserta.statusaktif="'.AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY).'"';	}

$metCOB = mysql_query('SELECT
ajkdebitnote.nomordebitnote,
ajkdebitnote.tgldebitnote,
ajkdebitnote.idbroker,
ajkdebitnote.idclient,
ajkdebitnote.idproduk,
ajkdebitnote.idas,
ajkdebitnote.idaspolis,
ajkpeserta.idpeserta,
ajkpeserta.nomorktp,
ajkpeserta.nama,
ajkpeserta.tgllahir,
ajkpeserta.usia,
ajkpeserta.plafond,
ajkpeserta.tglakad,
ajkpeserta.tenor,
ajkpeserta.tglakhir,
ajkpeserta.totalpremi,
ajkpeserta.astotalpremi,
ajkpeserta.statusaktif,
ajkcabang.`name` AS cabang
FROM ajkdebitnote
INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'.AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).'" AND "'.AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'"');
while ($metCOB_ = mysql_fetch_array($metCOB)) {
if ($met_['byrate']=="Age") {
	$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idpolis="'.$metCOB_['idproduk'].'" AND age="'.$metCOB_['usia'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
}else{
	$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idpolis="'.$metCOB_['idproduk'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
}

$metAsuransi = mysql_fetch_array(mysql_query('SELECT byrate FROM ajkpolisasuransi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idcost="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND id="'.$metCOB_['idaspolis'].'"'));
if ($metAsuransi['byrate']=="Age") {
	$metRateAs = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremiins WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND idpolis="'.$metCOB_['idaspolis'].'" AND age="'.$metCOB_['usia'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
}else{
	$metRateAs = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremiins WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND idpolis="'.$metCOB_['idaspolis'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
}
$worksheet1->write_string($baris, 0, ++$no, 'C');
$worksheet1->write_string($baris, 1, $metCOB_['nomordebitnote']);
$worksheet1->write_string($baris, 2, $metCOB_['tgldebitnote']);
$worksheet1->write_string($baris, 3, $metCOB_['nomorktp']);
$worksheet1->write_string($baris, 4, $metCOB_['idpeserta']);
$worksheet1->write_string($baris, 5, $metCOB_['nama']);
$worksheet1->write_string($baris, 6, $metCOB_['tgllahir']);
$worksheet1->write_number($baris, 7, $metCOB_['usia']);
$worksheet1->write_number($baris, 8, duit($metCOB_['plafond']));
$worksheet1->write_string($baris, 9, _convertDate($metCOB_['tglakad']));
$worksheet1->write_number($baris, 10, $metCOB_['tenor']);
$worksheet1->write_string($baris, 11, _convertDate($metCOB_['tglakhir']));
$worksheet1->write_string($baris, 12, $metRate['rate']);
$worksheet1->write_number($baris, 13, $metCOB_['totalpremi']);
$worksheet1->write_string($baris, 14, $metCOB_['statusaktif']);
$worksheet1->write_string($baris, 15, $metCOB_['cabang']);

	$worksheet2->write_string($baris, 0, ++$no1, 'C');
	$worksheet2->write_string($baris, 1, $metCOB_['nomordebitnote']);
	$worksheet2->write_string($baris, 2, $metCOB_['tgldebitnote']);
	$worksheet2->write_string($baris, 3, $metCOB_['nomorktp']);
	$worksheet2->write_string($baris, 4, $metCOB_['idpeserta']);
	$worksheet2->write_string($baris, 5, $metCOB_['nama']);
	$worksheet2->write_string($baris, 6, $metCOB_['tgllahir']);
	$worksheet2->write_number($baris, 7, $metCOB_['usia']);
	$worksheet2->write_number($baris, 8, duit($metCOB_['plafond']));
	$worksheet2->write_string($baris, 9, _convertDate($metCOB_['tglakad']));
	$worksheet2->write_number($baris, 10, $metCOB_['tenor']);
	$worksheet2->write_string($baris, 11, _convertDate($metCOB_['tglakhir']));
	$worksheet2->write_string($baris, 12, $metRateAs['rate']);
	$worksheet2->write_number($baris, 13, $metCOB_['astotalpremi']);
	$worksheet2->write_string($baris, 14, $metCOB_['statusaktif']);
	$worksheet2->write_string($baris, 15, $metCOB_['cabang']);
$baris++;
$tPremi += $metCOB_['totalpremi'];
$tPremias += $metCOB_['astotalpremi'];
}
$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 11);
$worksheet1->write_number($baris, 13, $tPremi, $ftotal);
$worksheet2->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet2->merge_cells($baris, 0, $baris, 11);
$worksheet2->write_number($baris, 13, $tPremias, $ftotal);


$workbook->close();
		;
		break;
case "rptdebitnote":
$filename = "DETBINOTE";
function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
HeaderingExcel(_convertDate(AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY)).'_'.AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'_'.$filename.'.xls');
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet($filename);

$format =& $workbook->add_format();		$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('orange');
$fjudul =& $workbook->add_format();		$fjudul->set_align('center');	$fjudul->set_bold();
$ftotal =& $workbook->add_format();		$ftotal->set_bold();

if ($_REQUEST['idb']) {	$satu ='AND ajkcobroker.id = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
if ($_REQUEST['idc']) {	$dua ='AND ajkclient.id = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';	}
$met_idproduk = explode("_", AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY));
if ($_REQUEST['idp']) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}

$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)==""){	$_metbroker = '';	}else{	$_metbroker = $met_['brokername'];	}
if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)==""){	$_metclient = 'ALL CLIENT';	}else{	$_metclient = $met_['clientname'];	}
if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY)==""){	$_metproduk = 'ALL PRODUCT';	}else{	$_metproduk = $met_['produk'];	}

$worksheet1->write_string(0, 0, "REPORT PAYMENTS", $fjudul);	$worksheet1->merge_cells(0, 0, 0, 7);
$worksheet1->write_string(1, 0, strtoupper($_metbroker), $fjudul);	$worksheet1->merge_cells(1, 0, 1, 7);
$worksheet1->write_string(2, 0, strtoupper($_metclient), $fjudul);	$worksheet1->merge_cells(2, 0, 2, 7);
$worksheet1->write_string(3, 0, strtoupper($_metproduk), $fjudul);	$worksheet1->merge_cells(3, 0, 3, 7);

$worksheet1->set_row(5, 15);
$worksheet1->set_column(5, 0, 1);	$worksheet1->write_string(5, 0, "NO", $format);
$worksheet1->set_column(5, 1, 40);	$worksheet1->write_string(5, 1, "Debitnote", $format);
$worksheet1->set_column(5, 2, 10);	$worksheet1->write_string(5, 2, "Date DN", $format);
$worksheet1->set_column(5, 3, 15);	$worksheet1->write_string(5, 3, "Member", $format);
$worksheet1->set_column(5, 4, 15);	$worksheet1->write_string(5, 4, "Premium", $format);
$worksheet1->set_column(5, 5, 15);	$worksheet1->write_string(5, 5, "Status", $format);
$worksheet1->set_column(5, 6, 10);	$worksheet1->write_string(5, 6, "Date Payment", $format);
$worksheet1->set_column(5, 7, 10);	$worksheet1->write_string(5, 7, "Branch", $format);
$baris = 6;
if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY))	{	$satu = 'AND ajkdebitnote.idbroker="'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY))	{	$dua = 'AND ajkdebitnote.idclient="'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';		}
if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY))	{	$tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';	}
		if (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="1") 		{	$_datapaid="Paid";
		}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="2")	{	$_datapaid="Paid*";
		}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="")	{	$_datapaid="%";
		}else{	$_datapaid="Unpaid";	}
		if ($_REQUEST['st'])	{	$empat = 'AND ajkdebitnote.paidstatus like "'.$_datapaid.'"';	}

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
ajkdebitnote.paidtanggal,
ajkdebitnote.premiasuransi,
Count(ajkpeserta.nama) AS jmember
FROM ajkdebitnote
INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'.AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).'" AND "'.AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'"
GROUP BY ajkdebitnote.id');
while ($metCOB_ = mysql_fetch_array($metCOB)) {
if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
	$tgllunas = '';
}else{
	$tgllunas = _convertDate($metCOB_['paidtanggal']);
}

$worksheet1->write_string($baris, 0, ++$no, 'C');
$worksheet1->write_string($baris, 1, $metCOB_['nomordebitnote']);
$worksheet1->write_string($baris, 2, $metCOB_['tgldebitnote']);
$worksheet1->write_number($baris, 3, $metCOB_['jmember']);
$worksheet1->write_number($baris, 4, $metCOB_['premiclient']);
$worksheet1->write_string($baris, 5, $metCOB_['paidstatus']);
$worksheet1->write_string($baris, 6, $tgllunas);
$worksheet1->write_string($baris, 7, $metCOB_['cabang']);
$baris++;
$tPremi += $metCOB_['premiclient'];
}
$worksheet1->write_string($baris, 0, "TOTAL", $fjudul);	$worksheet1->merge_cells($baris, 0, $baris, 3);
$worksheet1->write_number($baris, 4, $tPremi, $ftotal);
$workbook->close();
	;
	break;
	case "s":
		;
		break;
	default:
		;
} // switch

?>