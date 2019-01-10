<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
error_reporting(0);
include_once('../fpdf.php');
define('../FPDF_FONTPATH', 'font/');
include_once('../includes/fu6106.php');
include_once('../includes/functions.php');
include_once('../includes/code39.php');
include_once('../koneksi.php');
switch ($_REQUEST['pdf']) {
	case "d":

		;
		break;

	case "lprmember":
		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)) {	$satu ='AND ajkcobroker.id = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"'; }
		if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)) {	$dua ='AND ajkclient.id = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';	}
		if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY)) {	$tiga ='AND ajkpolis.id = "'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		if ($_REQUEST['idb']) {
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);	}
		}else{	}
		if ($_REQUEST['idc']) {
			$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,270,7,20,15);	}
		}else{	}


		$pdf->ln(-12);
		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(270,4,'MEMBERSHIP DATA REPORT',0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$met_['brokername'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$met_['clientname'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$met_['produk'],0,0,'C',0);$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(10);
		$pdf->cell(8,5,'No',1,0,'C',1);
		$pdf->cell(28,5,'Debitnote',1,0,'C',1);
		$pdf->cell(20,5,'Date DN',1,0,'C',1);
		$pdf->cell(20,5,'KTP',1,0,'C',1);
		$pdf->cell(16,5,'IDMember',1,0,'C',1);
		$pdf->cell(35,5,'Name',1,0,'C',1);
		$pdf->cell(17,5,'BOD',1,0,'C',1);
		$pdf->cell(8,5,'Age',1,0,'C',1);
		$pdf->cell(20,5,'Plafond',1,0,'C',1);
		$pdf->cell(18,5,'Date Start',1,0,'C',1);
		$pdf->cell(10,5,'Tenor',1,0,'C',1);
		$pdf->cell(18,5,'Date End',1,0,'C',1);
		$pdf->cell(12,5,'Rate',1,0,'C',1);
		$pdf->cell(21,5,'Nett Premium',1,0,'C',1);
		$pdf->cell(30,5,'Branch',1,0,'C',1);

		if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY))	{	$satu = 'AND ajkdebitnote.idbroker="'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
		if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY))	{	$dua = 'AND ajkdebitnote.idclient="'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';		}
		if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY))	{	$tiga = 'AND ajkdebitnote.idproduk="'.AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'"';	}
		if (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY))	{	$empat = 'AND ajkpeserta.statusaktif="'.AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY).'"';	}

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

			$cell[$i][0] = substr($metCOB_['nomordebitnote'], 3);
			$cell[$i][1] = $metCOB_['tgldebitnote'];
			$cell[$i][2] = $metCOB_['nomorktp'];
			$cell[$i][3] = $metCOB_['idpeserta'];
			$cell[$i][4] = $metCOB_['nama'];
			$cell[$i][5] = _convertDate($metCOB_['tgllahir']);
			$cell[$i][6] = $metCOB_['usia'];
			$cell[$i][7] = duit($metCOB_['plafond']);
			$cell[$i][8] = _convertDate($metCOB_['tglakad']);
			$cell[$i][9] = $metCOB_['tenor'];
			$cell[$i][10] = _convertDate($metCOB_['tglakhir']);
			$cell[$i][11] = $metRateAs['rate'];
			$cell[$i][12] = duit($metCOB_['totalpremi']);
			$cell[$i][13] = $metCOB_['cabang'];
			$tPremi += $metCOB_['totalpremi'];
			$i++;
		}
		$pdf->Ln();
		for($j<1;$j<$i;$j++)
		{	$pdf->cell(8,5,$j+1,1,0,'C');
			$pdf->cell(28,5,$cell[$j][0],1,0,'C');
			$pdf->cell(20,5,_convertDate($cell[$j][1]),1,0,'C');
			$pdf->cell(20,5,$cell[$j][2],1,0,'C');
			$pdf->cell(16,5,$cell[$j][3],1,0,'C');
			$pdf->cell(35,5,$cell[$j][4],1,0,'L');
			$pdf->cell(17,5,$cell[$j][5],1,0,'C');
			$pdf->cell(8,5,$cell[$j][6],1,0,'C');
			$pdf->cell(20,5,$cell[$j][7],1,0,'C');
			$pdf->cell(18,5,$cell[$j][8],1,0,'C');
			$pdf->cell(10,5,$cell[$j][9],1,0,'C');
			$pdf->cell(18,5,$cell[$j][10],1,0,'C');
			$pdf->cell(12,5,$cell[$j][11],1,0,'C');
			$pdf->cell(21,5,$cell[$j][12],1,0,'R');
			$pdf->cell(30,5,$cell[$j][13],1,0,'C');
			$pdf->Ln();
		}

		$pdf->cell(230,6,'Total Premi',1,0,'L',1);
		$pdf->cell(21,6,duit($tPremi),1,0,'R',1);
		$pdf->cell(30,6,'',1,0,'R',1);

		$pdf->Output("Report_Member.pdf","I");
		;
		break;

	case "member":
		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$metmember = mysql_fetch_array(mysql_query('SELECT
			ajkdebitnote.id,
			ajkdebitnote.idbroker,
			ajkdebitnote.idclient,
			ajkdebitnote.idproduk,
			ajkdebitnote.idas,
			ajkdebitnote.idaspolis,
			ajkcobroker.`name` AS brokername,
			ajkcobroker.logo AS brokerlogo,
			ajkclient.`name` AS clientname,
			ajkpolis.produk,
			ajkpolis.byrate,
			ajkpolis.calculatedrate,
			ajkcabang.`name` AS cabang,
			ajkdebitnote.nomordebitnote,
			ajkdebitnote.tgldebitnote,
			ajkdebitnote.paidstatus
			FROM ajkdebitnote
			INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
			INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
			INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
			INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
			WHERE ajkdebitnote.id = "'.AES::decrypt128CBC($_REQUEST['idd'], ENCRYPTION_KEY).'"'));


		$pathFile = '../'.$PathPhoto.''.$metmember['brokerlogo'];
		if (file_exists($pathFile))
		{	$metLogoBroker = $pathFile;
			$pdf->Image($metLogoBroker,10,7,20,15);
		}

		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,$metmember['brokername']);
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Broker Insurance');
		$pdf->SetFont('helvetica','B',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(130, 30,'List Member Insurance');

		$pdf->ln(15);
		$pdf->SetFont('helvetica','',10);
		$pdf->cell(30,5,'Partner',0,0,'L',0);	$pdf->cell(170,5,': '.$metmember['clientname'],0,0,'L',0);	$pdf->cell(30,5,'Date Debitnote',0,0,'L',0);		$pdf->cell(30,5,': '._convertDate($metmember['tgldebitnote']),0,0,'L',0);	$pdf->ln();
		$pdf->cell(30,5,'Product',0,0,'L',0);	$pdf->cell(170,5,': '.$metmember['produk'],0,0,'L',0);		$pdf->cell(30,5,'Debitnote',0,0,'L',0);				$pdf->cell(30,5,': '.$metmember['nomordebitnote'],0,0,'L',0);	$pdf->ln();
		$pdf->cell(30,5,'Branch',0,0,'L',0);	$pdf->cell(170,5,': '.$metmember['cabang'],0,0,'L',0);		$pdf->cell(30,5,'Status',0,0,'L',0);				$pdf->cell(30,5,': '.$metmember['paidstatus'],0,0,'L',0);	$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 55;
		$pdf->setY($y_axis1);
		$pdf->setX(10);

		$metListmember_ = mysql_query('SELECT * FROM ajkpeserta WHERE iddn="'.$metmember['id'].'" AND del IS NULL');

		$pdf->cell(8,6,'No',1,0,'C',1);
		$pdf->cell(20,6,'ID Member',1,0,'C',1);
		$pdf->cell(65,6,'Name',1,0,'C',1);
		$pdf->cell(18,6,'DOB',1,0,'C',1);
		$pdf->cell(10,6,'Age',1,0,'C',1);
		$pdf->cell(30,6,'Plafond',1,0,'C',1);
		$pdf->cell(30,6,'Date Start',1,0,'C',1);
		$pdf->cell(10,6,'Tenor',1,0,'C',1);
		$pdf->cell(30,6,'Date End',1,0,'C',1);
		$pdf->cell(20,6,'Rate',1,0,'C',1);
		$pdf->cell(30,6,'Nett Premium',1,0,'C',1);
		while ($_metListmember = mysql_fetch_array($metListmember_))
		{
			if ($metmember['byrate']=="Age") {
				$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metmember['idbroker'].'" AND idclient="'.$metmember['idclient'].'" AND idpolis="'.$metmember['idproduk'].'" AND age="'.$_metListmember['usia'].'" AND '.$_metListmember['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
			}else{
				$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metmember['idbroker'].'" AND idclient="'.$metmember['idclient'].'" AND idpolis="'.$metmember['idproduk'].'" AND '.$_metListmember['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
			}

			$cell[$i][0] = $_metListmember['idpeserta'];
			$cell[$i][1] = $_metListmember['nama'];
			$cell[$i][2] = $_metListmember['tgllahir'];
			$cell[$i][3] = $_metListmember['usia'];
			$cell[$i][4] = duit($_metListmember['plafond']);
			$cell[$i][5] = _convertDate($_metListmember['tglakad']);
			$cell[$i][6] = $_metListmember['tenor'];
			$cell[$i][7] = _convertDate($_metListmember['tglakhir']);
			$cell[$i][8] = $metRate['rate'];
			$cell[$i][9] = duit($_metListmember['totalpremi']);
			$i++;
			$tTotalPeserta += $_metListmember['nama'];
			$tTotalPremi += $_metListmember['totalpremi'];
		}
		$pdf->Ln();

		for($j<1;$j<$i;$j++)
		{	$pdf->cell(8,6,$j+1,1,0,'C');
			$pdf->cell(20,6,$cell[$j][0],1,0,'C');
			$pdf->cell(65,6,$cell[$j][1],1,0,'L');
			$pdf->cell(18,6,$cell[$j][2],1,0,'C');
			$pdf->cell(10,6,$cell[$j][3],1,0,'C');
			$pdf->cell(30,6,$cell[$j][4],1,0,'R');
			$pdf->cell(30,6,$cell[$j][5],1,0,'C');
			$pdf->cell(10,6,$cell[$j][6],1,0,'C');
			$pdf->cell(30,6,$cell[$j][7],1,0,'C');
			$pdf->cell(20,6,$cell[$j][8],1,0,'C');
			$pdf->cell(30,6,$cell[$j][9],1,0,'R');
			$pdf->Ln();
		}

		$pdf->cell(241,6,'Total Premi',1,0,'L',1);
		$pdf->cell(30,6,duit($tTotalPremi),1,0,'R',1);


		$pdf->Ln();
		$pdf->setFont('Arial','',9);
		$pdf->MultiCell(200,6,'Catatan :',0,'L');//	$pdf->cell(75,6,'Jakarta, '.$futgl.'',0,0,'L');$pdf->Ln();
		$pdf->MultiCell(200,6,'Bukti Konfirmasi ini merupakan dokumen elektronik, sehingga cukup menggunakan cap dan tanda tangan elektronik.',0,'L');//	$pdf->cell(75,6,$met_asuransi['name'],0,0,'L');		$pdf->Ln();

		$tglIndo__ = explode(" ", $tglIndo);
		$metTglIndo = str_replace($_blnIndo,$_blnIndo_ , $tglIndo__[1]);
		$pdf->cell(232,4,'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'',0,0,'R',0);

		$pdf->ln();
		$pdf->cell(200,4,'',0,0,'L',0);$pdf->SetFont('helvetica','B',9);
		$pdf->cell(20,6,$metmember['brokername'],0,0,'L',0);

		$pdf->Ln(30);
		$pdf->cell(200,6,' ', 0, 0, 'R');
		$pdf->cell(30,6,'{namadn}', 0, 0, 'L');

		$pdf->Output('MEMBER_'.$metmember['nomordebitnote'].".pdf","I");
		;
		break;
	case "rptdebitnote":
/*
echo AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'<br />';
echo AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY).'<br />';
*/
$pdf=new FPDF('P','mm','A4');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

$met_idproduk = explode("_", AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY));
if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)) {	$satu ='AND ajkcobroker.id = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)) {	$dua ='AND ajkclient.id = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';	}
if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY)) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}

$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)) {
	$pathFile = '../'.$PathPhoto.''.$met_['logo'];
	if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,40,10);	}
}else{	}
if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)) {
	$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
	if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,270,7,20,15);	}
}else{	}

$pdf->ln(-12);
$pdf->SetFont('helvetica','B',10);
$pdf->cell(190,4,'REPORT DEBITNOTE',0,0,'C',0);$pdf->ln();
$pdf->cell(190,4,$met_['brokername'],0,0,'C',0);$pdf->ln();
$pdf->cell(190,4,$met_['clientname'],0,0,'C',0);$pdf->ln();
$pdf->cell(190,4,$met_['produk'],0,0,'C',0);$pdf->ln();
if ($_REQUEST['dtfrom'] OR $_REQUEST['dtto']) {
	$pdf->cell(190,4,AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).' - '.AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY),0,0,'C',0);$pdf->ln();
}else{	}

$pdf->setFont('Arial','',9);
$pdf->setFillColor(233,233,233);
$y_axis1 = 30;
$pdf->setY($y_axis1);
$pdf->setX(10);
$pdf->cell(8,5,'No',1,0,'C',1);
$pdf->cell(20,5,'Date DN',1,0,'C',1);
$pdf->cell(30,5,'Debitnote',1,0,'C',1);
$pdf->cell(14,5,'Member',1,0,'C',1);
$pdf->cell(15,5,'Status',1,0,'C',1);
$pdf->cell(25,5,'Date Payment',1,0,'C',1);
$pdf->cell(30,5,'Premium',1,0,'C',1);
$pdf->cell(50,5,'Branch',1,0,'C',1);

if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY))	{	$satu = 'AND ajkdebitnote.idbroker="'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY))	{	$dua = 'AND ajkdebitnote.idclient="'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';		}
if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY))	{	$tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';	}

if (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="1") 		{	$_datapaid="Paid";
}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="2")	{	$_datapaid="Paid*";
}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="")	{	$_datapaid="%";
}else{	$_datapaid="Unpaid";	}
if ($_REQUEST['st'])	{	$empat = 'AND ajkdebitnote.paidstatus like"'.$_datapaid.'"';	}

$metCOB = mysql_query('SELECT
ajkdebitnote.nomordebitnote,
ajkdebitnote.tgldebitnote,
ajkdebitnote.idbroker,
ajkdebitnote.idclient,
ajkdebitnote.idproduk,
ajkdebitnote.idas,
ajkdebitnote.idaspolis,
ajkdebitnote.paidstatus,
ajkdebitnote.paidtanggal,
ajkdebitnote.premiclient,
COUNT(ajkpeserta.idpeserta) AS jData,
ajkcabang.`name` AS cabang
FROM ajkdebitnote
INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'.AES::decrypt128CBC($_REQUEST['dtfrom'], ENCRYPTION_KEY).'" AND "'.AES::decrypt128CBC($_REQUEST['dtto'], ENCRYPTION_KEY).'"
GROUP BY ajkdebitnote.id');
while ($metCOB_ = mysql_fetch_array($metCOB)) {
if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
	$_tglbayar = '';
}else{
	$_tglbayar = _convertDate($metCOB_['paidtanggal']);
}
	$cell[$i][0] = $metCOB_['tgldebitnote'];
	$cell[$i][1] = substr($metCOB_['nomordebitnote'], 3);
	$cell[$i][2] = $metCOB_['jData'];
	$cell[$i][3] = $metCOB_['paidstatus'];
	$cell[$i][4] = $_tglbayar;
	$cell[$i][5] = duit($metCOB_['premiclient']);
	$cell[$i][6] = $metCOB_['cabang'];
	$tPremi += $metCOB_['premiclient'];
	$i++;
}
$pdf->Ln();
for($j<1;$j<$i;$j++)
{	$pdf->cell(8,5,$j+1,1,0,'C');
	$pdf->cell(20,5,_convertDate($cell[$j][0]),1,0,'C');
	$pdf->cell(30,5,$cell[$j][1],1,0,'C');
	$pdf->cell(14,5,$cell[$j][2],1,0,'C');
	$pdf->cell(15,5,$cell[$j][3],1,0,'C');
	$pdf->cell(25,5,$cell[$j][4],1,0,'C');
	$pdf->cell(30,5,$cell[$j][5],1,0,'R');
	$pdf->cell(50,5,$cell[$j][6],1,0,'L');
	$pdf->Ln();
}

$pdf->SetFont('helvetica','B',10);
$pdf->cell(112,6,'Total Premi',1,0,'L',1);
$pdf->cell(30,6,duit($tPremi),1,0,'R',1);
$pdf->cell(50,6,'',1,0,'R',1);

$pdf->Output("Report_Debitnote.pdf","I");
	;
	break;
	case "_spk":
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$met_idproduk = explode("_", AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY));
		if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)) {	$satu ='AND ajkcobroker.id = "'.AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY).'"';	}
		if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)) {	$dua ='AND ajkclient.id = "'.AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY).'"';	}
		if (AES::decrypt128CBC($_REQUEST['idp'], ENCRYPTION_KEY)) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate,
									  ajkpolis.mpptype,ajkpolis.typemedical
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		if (AES::decrypt128CBC($_REQUEST['idb'], ENCRYPTION_KEY)) {
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,40,10);	}
		}else{	}
		if (AES::decrypt128CBC($_REQUEST['idc'], ENCRYPTION_KEY)) {
			$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,270,7,20,15);	}
		}else{	}

		//$pdf->Image('image/adonai_64.gif',10,5);
		$pdf->SetFont('helvetica','I',5);	$pdf->Text(180, 5,'Tanggal System '.$futgl);
		$qspk = mysql_fetch_array(mysql_query('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinput FROM ajkspk WHERE nomorspk="'.AES::decrypt128CBC($_REQUEST['ids'],ENCRYPTION_KEY).'"'));
		if ($qspk['jeniskelamin']=="M") {	$gender = "Laki-Laki";	}else{	$gender = "Perempuan";	}
		$foldernamedebitur = date("y",strtotime($qspk['input_date'])).date("m",strtotime($qspk['input_date']));
		$foldernamemedical = date("y",strtotime($qspk['medical_date'])).date("m",strtotime($qspk['medical_date']));
		$quser = mysql_fetch_array(mysql_query("SELECT * FROM useraccess WHERE id = '".$qspk['input_by']."'"));
		$quserdokter = mysql_fetch_array(mysql_query("SELECT * FROM useraccess WHERE id = '".$qspk['dokterpemeriksa']."'"));
		$qusercabang = mysql_fetch_array(mysql_query("SELECT name FROM ajkcabang WHERE er = '".$quser['branch']."'"));
		$statususer = $level;
		if ($met_['typemedical']=="SKKT") {
			$pdf->SetFont('helvetica','B',12);	$pdf->Text(80, 30,'FORMULIR PERCEPATAN');
			$pdf->SetFont('helvetica','B',12);	$pdf->Text(90, 35,'Nomor '.$qspk['nomorspk'].' ' );
			$tenornya = $qspk['tenor'] * 12;
			//$tenornya = $metForm['tenor'];
		}else{
			$pdf->SetFont('helvetica','B',12);	$pdf->Text(60, 30,'SURAT PEMERIKSAAN KESEHATAN "SPK"');
			$pdf->SetFont('helvetica','B',12);	$pdf->Text(90, 35,'SPK. '.$qspk['nomorspk']);
			$tenornya = $qspk['tenor'];
		}

		$pdf->SetFont('helvetica','B',9);
		$pdf->Text(10, 45,'Nama Perusahaan');	$pdf->Text(52, 45,': '.$met_['clientname']);
		if($met_['mpptype']=='Y'){
			$sety = '55';
			$pdf->Text(10, 50,'Nama Produk');		$pdf->Text(52, 50,': '.$met_['produk'].' ');
			$pdf->Text(10, $sety,'MPP Bulan (Grace Period)');		$pdf->Text(52, $sety,': '.$qspk['mppbln'].' Bulan');
			$sety ='60';
		}else{
			$sety = '55';
			$pdf->Text(10, 50,'Nama Produk');		$pdf->Text(52, 50,': '.$met_['produk']);
		}
		$pdf->Text(10, $sety,'Upload User');		$pdf->Text(52, $sety,': '.strtoupper($quser['firstname']));


		$pdf->SetFont('helvetica','U',9);
		$pdf->Text(10, $sety+10,'DATA NASABAH');

		$pdf->SetFont('helvetica','',9);

		if ($qspk['photodebitur2']!="") {
			$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['photodebitur1'];
			if (file_exists($pathFile))
			{	$mamet_photonya = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['photodebitur1'];	}
			else {	//$mamet_photonya = 'image/non-user.png';
				if ($qspk['photodebitur2']== NULL) {
					$mamet_photonya = 'image/non-user.png';
				}else{
					$mamet_photonya = '../androidscript/uploads/'.$foldernamedebitur.'/'.''.$qspk['photodebitur2'];
				}
			}
			//$mamet_ttddebitur = '../ajkmobilescript/'.$metdata_form['filettddebitur'];		$pdf->Image($mamet_ttddebitur,15,250,40,35);
			if ($qruser['id_cost']!="") {
				if ($qspk['photodebitur2']!="") {
					$pathFile = '../ajkmobilescript/'.$metdata_form['filettddebitur'];
					if (file_exists($pathFile))
					{	$mamet_ttddebitur = '../ajkmobilescript/'.$metdata_form['filettddebitur'];
						$pdf->Image($mamet_ttddebitur,15,250,40,35);
					}
					else {	$pdf->Text(15, 270,'Tidak Ada TTD Debitur');	}
				}else{	$pdf->Text(15, 270,'Tidak Ada TTD Debitur');	}

				//$mamet_ttdmarketing = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];	$pdf->Image($mamet_ttdmarketing,65,250,40,35);
				if ($metdata_form['filettdmarketing']!="") {
					$pathFile = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];
					if (file_exists($pathFile))
					{	$mamet_ttdmarketing = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];
						$pdf->Image($mamet_ttdmarketing,65,250,40,35);
					}
					else {	$pdf->Text(60, 270,'Tidak Ada TTD Marketing');	}
				}else{	$pdf->Text(60, 270,'Tidak Ada TTD Marketing');	}

				if ($metdata_form['filettddokter']!="") {
					$pathFile = '../ajkmobilescript/'.$metdata_form['filettdmarketing'];
					if (file_exists($pathFile))
					{	$mamet_ttddokter = '../ajkmobilescript/'.$metdata_form['filettddokter'];
						$pdf->Image($mamet_ttddokter,115,250,40,35);
					}
					else {	$pdf->Text(115, 270,'Tidak Ada TTD Dokter');	}
				}else{	$pdf->Text(115, 270,'Tidak Ada TTD Dokter');	}
			}else{	}
		}else{
			$_cekSPK = substr($met['spak'],0,2);		//CEK PHOTO PERCEPATAN DARI TABLET
			if ($_cekSPK=="MP") {
				$mamet_photonya = '../ajkmobilescript/'.$metdata_form['filefotodebitursatu'];
			}else{
				if ($met['photo_spk']== NULL) {	$mamet_photonya = 'image/non-user.png';	}else{	$mamet_photonya = $metpath.''.$met['photo_spk'];}
			}
		}


		$pdf->Text(10, $sety+15,'Nama Nasabah');		$pdf->Text(52, $sety+15,': '.strtoupper($qspk['nama']));		$pdf->Image($mamet_photonya,150,40,40,40);
		$pdf->Text(10, $sety+20,'Jenis Kelamin');		$pdf->Text(52, $sety+20,': '.$gender);
		$pdf->Text(10, $sety+25,'Tanggal Lahir');		$pdf->Text(52, $sety+25,': '._convertDate($qspk['dob']));
		$pdf->Text(10, $sety+30,'Usia');				$pdf->Text(52, $sety+30,': '.$qspk['usia'].' Tahun');
		$pdf->Text(10, $sety+35,'Alamat');				$pdf->Text(52, $sety+35,': '.nl2br($qspk['alamat']));
		$pdf->Text(10, $sety+40,'Pekerjaan');			$pdf->Text(52, $sety+40,': '.$qspk['pekerjaan']);
		if ($qspk['statusspk']=="Tolak") {		$statusSPK = 'Ditolak';
		}elseif ($qspk['statusspk']=="Batal") {	$statusSPK = 'Dibatalkan';
		}else{	$statusSPK = $qspk['statusspk'];	}
		$pdf->Text(10, $sety+45,'Status');			$pdf->Text(52, $sety+45,': '.$statusSPK);

		if ($idclient!="") {
			$pdf->SetFont('helvetica','U',9);
			$pdf->Text(10, $sety+50,'DATA PERNYATAAN');
			$pdf->SetFont('helvetica','',9);

			$pdf->SetY($sety+52);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda dalam jangka 5 tahun terakhir ini pernah atau sedang menderita penyakit, Asma, Cacat, Tumor/Kanker, TBC, Kencing manis , Hati, Ginjal, Jantung, Stroke, Tekanan Darah Tinggi, Epilepsi, Gangguan Jiwa, Penyakit Autoimun, Keterbelakngan Mental atau Idiot. Jika "Ya", Jelaskan.' ,0,'L');
			$pdf->SetX(9);
			$pertanyaanketerangan = explode("|", $qspk['pertanyaanketerangan']);
			$dokterpertanyaan = explode("|", $qspk['dokterpertanyaan']);
			$ket1 = $pertanyaanketerangan[0];
			$ket2 = $pertanyaanketerangan[1];
			$ket3 = $pertanyaanketerangan[2];
			$ket4 = $pertanyaanketerangan[3];
			$ket5 = $pertanyaanketerangan[4];
			$ket6 = $pertanyaanketerangan[5];
			$pertanyaan1 = $dokterpertanyaan[0];
			$pertanyaan2 = $dokterpertanyaan[1];
			$pertanyaan3 = $dokterpertanyaan[2];
			$pertanyaan4 = $dokterpertanyaan[3];
			$pertanyaan5 = $dokterpertanyaan[4];
			$pertanyaan6 = $dokterpertanyaan[5];
			if($pertanyaan1=="Y"){ $pertanyaan1 = "YA";}else{$pertanyaan1 = "TIDAK";}
			if($pertanyaan2=="Y"){ $pertanyaan2 = "YA";}else{$pertanyaan2 = "TIDAK";}
			if($pertanyaan3=="Y"){ $pertanyaan3 = "YA";}else{$pertanyaan3 = "TIDAK";}
			if($pertanyaan4=="Y"){ $pertanyaan4 = "YA";}else{$pertanyaan4 = "TIDAK";}
			if($pertanyaan5=="Y"){ $pertanyaan5 = "YA";}else{$pertanyaan5 = "TIDAK";}
			if($pertanyaan6=="Y"){ $pertanyaan6 = "YA";}else{$pertanyaan6 = "TIDAK";}

			if ($ket1!="#") { $keterangan_1 = ', '.$ket1;	}else{	$keterangan_1 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan1.''.$keterangan_1.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah Berat badan Anda berubah dalam 12 bulan terakhir ini, Jika "Ya", Jelaskan.' ,0,'L');
			$pdf->SetX(9);
			if ($ket2!="#") { $keterangan_2 = ', '.$ket2;	}else{	$keterangan_2 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan2.''.$keterangan_2.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda menderita HIV/AIDS?' ,0,'L');
			$pdf->SetX(9);
			if ($ket3!="#") { $keterangan_3 = ', '.$ket3;	}else{	$keterangan_3 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan3.''.$keterangan_3.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda mengkonsumsi rutin (ketergantungan) pada Narkoba? Jika "Ya", Sebutkan jenisnya?' ,0,'L');
			$pdf->SetX(9);
			if ($ket4!="#") { $keterangan_4 = ', '.$ket4;	}else{	$keterangan_4 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan4.''.$keterangan_4.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Khusus untuk Wanita, apakah anda sedang hamil ? Jika "Ya" adalah komplikasi / penyakit kehamilan ? jelaskan? Usia Kandungan?' ,0,'L');
			$pdf->SetX(9);
			if ($ket5!="#") { $keterangan_5 = ', '.$ket5;	}else{	$keterangan_5 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan5.''.$keterangan_5.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);
			$pdf->MultiCell(195,4,'Apakah anda seorang perokok? Jika "Ya" berapa batang perhari?' ,0,'L');
			$pdf->SetX(9);
			if ($ket6!="#") { $keterangan_6 = ', '.$ket6;	}else{	$keterangan_6 = '';	}
			$pdf->SetFont('helvetica','B',8);
			$pdf->MultiCell(195,4,'Jawaban : '.$pertanyaan6.''.$keterangan_6.'' ,0,'L');

			$pdf->Ln();
			$pdf->SetFont('helvetica','U',9);
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA CEK MEDICAL' ,0,'L');

			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(9);	$pdf->Cell(36,4,'Dokter Pemeriksa' ,0,'L');			$pdf->Cell(36,4,': '.strtoupper($quserdokter['firstname']) ,0,'l');
			$pdf->Ln();
			$pdf->SetX(9);	$pdf->Cell(36,4,'Tanggal Periksa' ,0,'L');			$pdf->Cell(36,4,': ' ._convertDate($qspk['tglperiksa']) ,0,'l');
			$pdf->Ln();

			$pdf->SetX(10);	$pdf->Cell(37,5, 'Tinggi dan Berat Badan' ,1,0,'C');
			$pdf->Cell(37,5,'Tekanan Darah' ,1,0,'C');
			$pdf->Cell(37,5,'Nadi' ,1,0,'C');
			$pdf->Cell(38,5,'Pernafasan' ,1,0,'C');
			$pdf->Cell(38,5,'Gula Darah' ,1,0,'C');
			$pdf->Ln();
			$pdf->SetX(10);	$pdf->Cell(37,5,$qspk['tinggibadan'].'/'.$qspk['beratbadan'].'' ,1,0,'C');
			$pdf->Cell(37,5,$qspk['tekanandarah'] ,1,0,'C');
			$pdf->Cell(37,5,$qspk['nadi'] ,1,0,'C');
			$pdf->Cell(38,5,$qspk['pernafasan'] ,1,0,'C');
			$pdf->Cell(38,5,$qspk['guladarah'] ,1,0,'C');
			$pdf->Ln();
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'Kesimpulan : '.$qspk['dokterkesimpulan'].'' ,0,'L');
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'Catatan : '.$qspk['doktercatatan'].'' ,0,'L');


			$pdf->Ln();
			$pdf->SetFont('helvetica','U',9);
			$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA PEMINJAMAN ASURANSI' ,0,'L');
			$pdf->SetFont('helvetica','',9);
			$pdf->SetX(10);
			$pdf->Cell(35,5,'Plafond' ,1,0,'C');
			$pdf->Cell(25,5,'Tanggal Akad' ,1,0,'C');
			$pdf->Cell(20,5,'Tenor' ,1,0,'C');
			$pdf->Cell(25,5,'Tanggal Akhir ' ,1,0,'C');
			$pdf->Cell(30,5,'Premi' ,1,0,'C');
			$pdf->Cell(22,5,'Ext. Premi (%)' ,1,0,'C');
			if ($idas!="") {
				$pdf->Cell(30,5,'Total Premi(*)' ,1,0,'C');
			}else{
				$pdf->Cell(30,5,'Total Premi' ,1,0,'C');
			}

			$pdf->Ln();
			//if ($met['status']=="Aktif" OR $met['status']=="Realisasi") { ditambahkan status yang preaproval untuk melihat data keterangan plafondnya 29092016
			if ($qspk['statusspk']!="Request" OR $qspk['statusspk']!="Tolak" OR $qspk['statusspk']!="Batal") {
				if ($qspk['em']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM '.$qspk['em'].'%  ('.$qspk['ketem'].')';	}


				//if ($metFormSPK['tenor'] > 12) {	$tenorSPK_ = $metFormSPK['tenor'] / 12;	}	else{	$tenorSPK_ = $metFormSPK['tenor'];	}	REVISI 061015

				//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $tenorSPK_ . '"')); // RATE PREMI	REVISI 061015
				if ($idas!="") {
					$cekData_ = mysql_fetch_array(mysql_query('SELECT fu_ajk_peserta.id_dn,
																	  fu_ajk_peserta.id_cost,
																	  fu_ajk_peserta.id_polis,
																	  fu_ajk_peserta.id_peserta,
																	  fu_ajk_peserta.kredit_jumlah,
																	  fu_ajk_peserta.usia,
																	  fu_ajk_peserta.kredit_tenor,
																	  DATE_FORMAT(fu_ajk_peserta.input_time, "%Y-%m-%d") AS tglinput,
																	  fu_ajk_dn.id_polis_as,
																	  fu_ajk_dn.id_as,
																	  fu_ajk_polis.singlerate
																	  FROM fu_ajk_peserta
																	  INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
																	  INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_cost = fu_ajk_polis.id_cost AND fu_ajk_peserta.id_polis = fu_ajk_polis.id
																	  WHERE fu_ajk_peserta.id_cost = "'.$met['id_cost'].'" AND
																	  		fu_ajk_peserta.id_polis = "'.$met['id_polis'].'" AND
																	  		fu_ajk_peserta.spaj ="'.$met['spak'].'"'));
					if ($cekData_['singlerate']=="Y") {
						if ($met_polis['mpptype']=="Y") {
							/*
							   if ($metFormSPK['mpp'] < $met_polis['mppbln_min']) {
							   $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="lama"')); // RATE PREMI
							   }else{
							   $tenorSPKMPP = $metFormSPK['tenor'] * 12;
							   $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru"')); // RATE PREMI
							   }
							*/
							$tenorSPKMPP = $metFormSPK['tenor'];
							if ($met_['tglinput'] <= "2016-07-31") {
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="lama" AND del ="1"')); // RATE PREMI
								if (!$cekrate['rate']) {
									$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru" AND del IS NULL')); // RATE PREMI
								}
							}else{
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND tenor="' . $tenorSPKMPP . '" AND status="baru" AND del IS NULL')); // RATE PREMI
							}
						}else{
							if ($met_['tglinput'] <= "2016-07-31") {
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_as="'.$cekData_['id_as'].'" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="lama" AND del ="1"')); // RATE PREMI
								if (!$cekrate['rate']) {
									$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_as="'.$cekData_['id_as'].'" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
								}
							}else{
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_as="'.$cekData_['id_as'].'" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
							}
						}
					}else{
						if ($met_['tglinput'] <= "2016-07-31") {
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND tenor="' . $metFormSPK['tenor'] . '" AND status="lama" AND del ="1"')); // RATE PREMI
							if (!$cekrate['rate']) {
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_as="'.$cekData_['id_as'].'" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
							}
						}else{
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND id_polis_as="'.$cekData_['id_polis_as'].'" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
						}
					}
				}else{
					if ($met_polis['singlerate']=="Y") {
						if ($met_polis['mpptype']=="Y") {
							/*
							   if ($metFormSPK['mpp'] < $met_polis['mppbln_min']) {
							   $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="lama" AND del IS NULL')); // RATE PREMI
							   }else{
							   $tenorSPKMPP = $metFormSPK['tenor'] * 12;
							   $cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL')); // RATE PREMI
							   }
							*/
							$tenorSPKMPP = $metFormSPK['tenor'];
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL')); // RATE PREMI
							//$pdf->Text(10, 60,$cekrate['rate']);
						}else{
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
						}
					}else{
						$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND status="baru" AND del IS NULL')); // RATE PREMI
					}
				}

				//REVISI 27092016 PERHITUNGAN PREMI SPK UNUTK DATA KE ASURANSI HARUS MENGHITUNG RATE YG BERJALAN
				if ($idas=="") {
					$premi = $qspk['premi'];
					$metStatusPremi = $premi;
					$metExtPremi = $premi * $qspk['em'] / 100;	//HITUNG EXTRA PREMI
					$mettotalpremibayangan = ROUND($premi + $metExtPremi);
				}else{
					$premi = $qspk['plafond'] * $cekrate['rate'] / 1000;
					$metStatusPremi = $premi;
					$metExtPremi = $premi * $met['em'] / 100;	//HITUNG EXTRA PREMI
					$mettotalpremibayangan = ROUND($premi + $metExtPremi);
				}
				//REVISI 27092016 PERHITUNGAN PREMI SPK UNUTK DATA KE ASURANSI HARUS MENGHITUNG RATE YG BERJALAN

				if ($mettotalpremibayangan < $met_polis['min_premium']) {
					$mettotalpremibayangan_ = $met_polis['min_premium'];
				}else{
					$mettotalpremibayangan_ = $mettotalpremibayangan;
				}

				//$mettotalpremibayangan_ = $mettotalpremibayangan;
				$pdf->Cell(35,5, duit($qspk['plafond']) ,1,0,'C');
				$pdf->Cell(25,5,_convertDate($qspk['tglakad']) ,1,0,'C');
				$pdf->Cell(20,5,$qspk['tenor'] ,1,0,'C');
				$pdf->Cell(25,5,_convertDate($qspk['tglakhir']) ,1,0,'C');
				$pdf->Cell(30,5,duit($metStatusPremi) ,1,0,'C');
				$pdf->Cell(22,5,duit($qspk['em']).'%' ,1,0,'C');
				$pdf->Cell(30,5,duit($mettotalpremibayangan_) ,1,0,'C');
			}else{
				$met_ket_EM = $qspk['ketem'];
				$pdf->Cell(35,5,'',1,0,'C');
				$pdf->Cell(25,5,'',1,0,'C');
				$pdf->Cell(20,5,'',1,0,'C');
				$pdf->Cell(25,5,'',1,0,'C');
				$pdf->Cell(30,5,'',1,0,'C');
				$pdf->Cell(22,5,'',1,0,'C');
				$pdf->Cell(30,5,'',1,0,'C');

			}

			$pdf->Ln();
			//if ($met['ket_ext']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM'. $met['ket_ext'];	}
			//$pdf->SetX(9);	$pdf->Cell(37,4, 'Keterangan' ,0,'L');			$pdf->Cell(36,4,': ' .$met_ket_EM ,0,'1');

			if ($idas =="") {
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(9);
				$pdf->MultiCell(195,4,'Keterangan' ,0,'L');
				$pdf->SetX(9);
				if ($ket6!="#") { $keterangan_6 = ' '.$ket6;	}else{	$keterangan_6 = '';	}
				$pdf->SetFont('helvetica','',9);
				$pdf->MultiCell(195,4,$met_ket_EM);
			}else{	}

			$pdf->Ln();
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Cabang' ,0,'L');				$pdf->Cell(36,4,': ' .$qusercabang['name'] ,0,'1');
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Input User' ,0,'L');			$pdf->Cell(36,4,': ' .strtoupper($quser['firstname']) ,0,'1');
			$pdf->SetX(9);	$pdf->Cell(37,4, 'Tanggal Input' ,0,'L');		$pdf->Cell(36,4,': ' .$qspk['input_date'] ,0,'1');

			//end 150908 modify by satrya
			//$met_tglinputnya = explode(" ",$metFormSPK['input_date']);

			//KONDISI MENAMPILKAN CATATAN USIA APABILA AKAN NAIK USIA SISA 1 BULAN LAGI
			//if ($met_tglinputnya[0] <= $datelog) {
			//}else{
			$pdf->Ln();
			$mets = datediff($qspk['tglakad'], $qspk['dob']);
			$cekbulan = explode(",", $mets);
			if ($cekbulan[1] >= 6 ) {	$umur = $cekbulan[0] + 1;	}else{	$umur = $cekbulan[0];	}
			if ($cekbulan[1] == 5) {
				$sisahari = 30 - $cekbulan[2];
				$sisathn = $cekbulan[0] + 1;
				$blnnnya ='Mohon mengajukan Deklarasi kurang dari '.$sisahari.' hari, sebelum usia akan bertambah menjadi '.$sisathn.' tahun';
				if ($met['status']=="Tolak") {

				}else{
					$pdf->SetX(9);	$pdf->Cell(50,4, 'Catatan : ' ,0,'L');	$pdf->Cell(36,4,'' ,0,'1');
					$pdf->Cell(36,4,$blnnnya ,0,'1');
				}
			}else{
				$blnnnya ='';
			}

			//PERTANYAAN HISTORY PENYAKIT APABILA DATA MP
			$cekSKKT = mysql_fetch_array(mysql_query('SELECT * FROM ajkskkt WHERE nomorspk="'.$qspk['nomorspk'].'"'));
			if ($cekSKKT['question_2']) {
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',14);
				$pdf->Cell(190,5,'KETERANGAN KESEHATAN TERTANGGUNG',0,0,'C');	$pdf->Ln();	$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$metSehat = explode("|", $cekSKKT['question_1']);
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(6,5,'1. ',0,0,'L');	$pdf->Cell(140,5,'Apakah Anda dalam keadaan sehat / tidak sehat :',0,0,'L');	$pdf->Cell(20,5,'Sehat',0,0,'C');	$pdf->Cell(25,5,'Tidak Sehat',0,0,'C');	$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				if ($metSehat[0]=="S") {	$setkolom1 = 'Sehat';	}else{	$setkolom11 = 'Tidak Sehat';	}
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Pada saat ini dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom1,0,0,'C');	$pdf->Cell(25,5,$setkolom11,0,0,'C');	$pdf->Ln();

				if ($metSehat[1]=="S") {	$setkolom2 = 'Sehat';	}else{	$setkolom22 = 'Tidak Sehat';	}
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(135,5,'Biasanya dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom2,0,0,'C');	$pdf->Cell(25,5,$setkolom22,0,0,'C');	$pdf->Ln();	$pdf->Ln();

				$pdf->SetFont('Arial','B',10);
				$metSehat = explode("|", $cekSKKT['question_2']);
				$pdf->Cell(6,5,'2. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 2 tahun terakhir ini, apakah anda pernah / tidak pernah :',0,0,'L');	$pdf->Cell(20,5,'Pernah',0,0,'C');		$pdf->Cell(25,5,'Tidak Pernah',0,0,'C');	$pdf->Ln();

				$pdf->SetFont('Arial','',10);
				if ($metSehat[0]=="P") {	$setkolom3 = 'Pernah';	}else{	$setkolom33 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'1. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit malaria ',0,0,'L');													$pdf->Cell(20,5,$setkolom3,0,0,'C');	$pdf->Cell(25,5,$setkolom33,0,0,'C');		$pdf->Ln();

				if ($metSehat[1]=="P") {	$setkolom4 = 'Pernah';	}else{	$setkolom44 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'2. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kanker ',0,0,'L');														$pdf->Cell(20,5,$setkolom4,0,0,'C');	$pdf->Cell(25,5,$setkolom44,0,0,'C');		$pdf->Ln();

				if ($metSehat[2]=="P") {	$setkolom5 = 'Pernah';	}else{	$setkolom55 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'3. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit TBC ',0,0,'L');														$pdf->Cell(20,5,$setkolom5,0,0,'C');	$pdf->Cell(25,5,$setkolom55,0,0,'C');		$pdf->Ln();

				if ($metSehat[3]=="P") {	$setkolom6 = 'Pernah';	}else{	$setkolom66 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'4. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kencing manis ',0,0,'L');												$pdf->Cell(20,5,$setkolom6,0,0,'C');	$pdf->Cell(25,5,$setkolom66,0,0,'C');		$pdf->Ln();

				if ($metSehat[4]=="P") {	$setkolom7 = 'Pernah';	}else{	$setkolom77 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'5. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit hati ',0,0,'L');														$pdf->Cell(20,5,$setkolom7,0,0,'C');	$pdf->Cell(25,5,$setkolom77,0,0,'C');		$pdf->Ln();

				if ($metSehat[5]=="P") {	$setkolom8 = 'Pernah';	}else{	$setkolom88 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'6. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ginjal ',0,0,'L');														$pdf->Cell(20,5,$setkolom8,0,0,'C');	$pdf->Cell(25,5,$setkolom88,0,0,'C');		$pdf->Ln();

				if ($metSehat[6]=="P") {	$setkolom9 = 'Pernah';	}else{	$setkolom99 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'7. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit jantung ',0,0,'L');													$pdf->Cell(20,5,$setkolom9,0,0,'C');	$pdf->Cell(25,5,$setkolom99,0,0,'C');		$pdf->Ln();

				if ($metSehat[7]=="P") {	$setkolom10 = 'Pernah';	}else{	$setkolom1010 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'8. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ayan ',0,0,'L');														$pdf->Cell(20,5,$setkolom10,0,0,'C');	$pdf->Cell(25,5,$setkolom1010,0,0,'C');		$pdf->Ln();

				if ($metSehat[8]=="P") {	$setkolom11 = 'Pernah';	}else{	$setkolom1111 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'9. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit lumpuh ',0,0,'L');														$pdf->Cell(20,5,$setkolom11,0,0,'C');	$pdf->Cell(25,5,$setkolom1111,0,0,'C');		$pdf->Ln();

				if ($metSehat[9]=="P") {	$setkolom12 = 'Pernah';	}else{	$setkolom1212 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'10. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit syaraf ',0,0,'L');														$pdf->Cell(20,5,$setkolom12,0,0,'C');	$pdf->Cell(25,5,$setkolom1212,0,0,'C');		$pdf->Ln();

				if ($metSehat[10]=="P") {	$setkolom13 = 'Pernah';	}else{	$setkolom1313 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'11. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah tinggi ',0,0,'L');										$pdf->Cell(20,5,$setkolom13,0,0,'C');	$pdf->Cell(25,5,$setkolom1313,0,0,'C');		$pdf->Ln();

				if ($metSehat[11]=="P") {	$setkolom14 = 'Pernah';	}else{	$setkolom1414 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'12. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah rendah ',0,0,'L');										$pdf->Cell(20,5,$setkolom14,0,0,'C');	$pdf->Cell(25,5,$setkolom1414,0,0,'C');		$pdf->Ln();

				if ($metSehat[12]=="P") {	$setkolom15 = 'Pernah';	}else{	$setkolom1515 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'13. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kelamin ',0,0,'L');													$pdf->Cell(20,5,$setkolom15,0,0,'C');	$pdf->Cell(25,5,$setkolom1515,0,0,'C');		$pdf->Ln();

				if ($metSehat[13]=="P") {	$setkolom16 = 'Pernah';	}else{	$setkolom1616 = 'Tidak Pernah';	}
				$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'14. ',0,0,'L');	$pdf->Cell(129,5,'Dirawat di Rumah Sakit ',0,0,'L');														$pdf->Cell(20,5,$setkolom16,0,0,'C');	$pdf->Cell(25,5,$setkolom1616,0,0,'C');		$pdf->Ln();

				if ($metSehat[14]=="P") {	$setkolom17 = 'Pernah';	}else{	$setkolom1717 = 'Tidak Pernah';	}
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(180,5,'Jika pernah dirawat di Rumah Sakit, sebutkan nama dan alamat Rumah Sakit',0,0,'L');	$pdf->Ln();
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,'yang merawat Anda',0,0,'L');	$pdf->Ln();

				$pdf->SetFont('Arial','',10);
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,$cekSKKT['nm_alamat_rs'],0,0,'L');

				if ($metSehat[15]=="P") {	$setkolom18 = 'Pernah';	}else{	$setkolom1818 = 'Tidak Pernah';	}
				$pdf->Cell(6,10,'',0,0,'L');	$pdf->Cell(185,10,' ',0,0,'L');	$pdf->Ln();

				if ($cekSKKT['question_3']=="P") {	$setkolom19 = 'Pernah';	}else{	$setkolom1919 = 'Tidak Pernah';	}
				$pdf->Cell(6,5,'3. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 12 bulan terakhir ini pernah / tidak pernah dirawat dokter:',0,0,'L');	$pdf->Cell(20,5,$setkolom19,0,0,'C');	$pdf->Cell(25,5,$setkolom1919,0,0,'C');		$pdf->Ln();

				if ($cekSKKT['nm_dokter']=="")		{	$_ohhdokter = "---";	}else{	$_ohhdokter = $cekSKKT['nm_dokter'];	}
				if ($cekSKKT['almt_dokter']=="")	{	$_ohhdokter_ = "---";	}else{	$_ohhdokter_ = $cekSKKT['almt_dokter'];	}
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(30,5,'Nama dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter,0,0,'L');	$pdf->Ln();
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(30,5,'Alamat dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter_,0,0,'L');	$pdf->Ln();	$pdf->Ln();

				$pdf->SetFont('Arial','B',10);
				if ($cekSKKT['question_4']=="H") {	$setkolom20 = 'Hamil';	}else{	$setkolom2020 = 'Tidak Hamil';	}

				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(6,5,'4. ',0,0,'L');	$pdf->Cell(140,5,'Saat ini dalam keadaan',0,0,'L');	$pdf->Cell(20,5,'Hamil',0,0,'C');	$pdf->Cell(25,5,'Tidak Hamil',0,0,'C');	$pdf->Ln();

				$pdf->SetFont('Arial','',10);
				$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(140,5,'(hanya untuk wanita)',0,0,'L');	$pdf->Cell(20,5,$setkolom20,0,0,'C');		$pdf->Cell(25,5,$setkolom2020,0,0,'C');	$pdf->Ln();	$pdf->Ln();
				$pdf->MultiCell(195,4,'Pernyataan-pernyataan tersebut di atas saya jawab dengan jujur sesuai dengan keadaan yang sebenarnya dan jika ada suatu hal yang saya ketahui dan tidak saya beritahukan atau saya dengan sengaja menjawab dengan tidak jujur / tidak benar, maka Pihak Asuransi berhak membatalkan atau menolak pembayaran manfaat asuransi ini.' ,0,'L');
				$pdf->MultiCell(195,4,'Selanjutnya saya dengan ini memberi kuasa penuh kepada Pemegang Polis dan Dokter-Dokter yang akan atau telah memeriksa atau mengobati saya untuk memberi keterangan-keterangan yang diminta oleh Pihak Asuransi mengenai segala sesuatu yang diperlukan dalam hubungannya dengan penutupan asuransi ini.' ,0,'L');	$pdf->Ln();
				$jangkrikgan = explode(" ", $cekSKKT['created_at']);
				$pdf->Cell(190,5,ucfirst(strtolower(($inputcabang))).', '. _convertDate($jangkrikgan[0]),0,0,'R');	$pdf->Ln();
				$pdf->Cell(190,5,'Mengetahui,',0,0,'L');	$pdf->Ln();
				$pdf->Cell(95,5,'Pejabat Bank / Koperasi / Lembaga',0,0,'L');
				$pdf->Cell(95,5,'Yang membuat pernyataan',0,0,'C');	$pdf->Ln(32);

				//TTD MARKETING
				if ($qspk['ttdmarketing']!="") {
					$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
					if (file_exists($pathFile))
					{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
						$pdf->Image($mamet_ttddebitur,10,225,40,30);
					}
					else {	$pdf->Text(15, 240,'Tidak Ada TTD Marketing');	}
				}
				$pdf->Cell(95,5,strtoupper($quser['firstname']),0,0,'L');

				//TTD DEBITUR
				if ($qspk['ttddebitur']!="") {
					$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
					if (file_exists($pathFile))
					{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
						$pdf->Image($mamet_ttddebitur,132,225,40,30);
					}
					else {	$pdf->Text(135, 240,'Tidak Ada TTD Debitur');	}
				}
				$pdf->Cell(95,5,strtoupper($qspk['nama']),0,0,'C');

			}else{

			}
			//echo $mets.'<br />';
		}
		else{
			$pdf->SetY(107);
			$pdf->SetX(9);
			if ($statususer=="" OR $statususer=="6" OR $statususer=="7") {
				$pdf->Ln();
				$pdf->SetFont('helvetica','U',9);
				$pdf->SetX(9);	$pdf->MultiCell(195,4,'DATA PEMINJAMAN ASURANSI',0,'L');
				$pdf->SetFont('helvetica','',9);
				$pdf->SetX(10);
				$pdf->Cell(35,5,'Plafond' ,1,0,'C');
				$pdf->Cell(25,5,'Tanggal Akad' ,1,0,'C');
				$pdf->Cell(20,5,'Tenor' ,1,0,'C');
				$pdf->Cell(25,5,'Tanggal Akhir ' ,1,0,'C');
				$pdf->Cell(30,5,'Premi' ,1,0,'C');
				$pdf->Cell(22,5,'Ext. Premi (%)' ,1,0,'C');
				$pdf->Cell(30,5,'Total Premi' ,1,0,'C');

				$pdf->Ln();
				//if ($met['status']=="Aktif" OR $met['status']=="Realisasi") { ditambahkan status yang preaproval untuk melihat data keterangan plafondnya 29092016
				if ($qspk['statusspk']!="Request" OR $qspk['statusspk']!="Tolak" OR $qspk['statusspk']!="Batal") {
					if ($met['ext_premi']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM '.$met['ext_premi'].'%  ('.$met['ket_ext'].')';	}

					if ($met_polis['typeproduk']!="SPK") {
						if ($met_polis['mpptype']=="Y") {
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND tenor="'.$metForm['tenor'] .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
						}else{
							//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND del IS NULL')); // RATE PREMI
							if ($met['tglinput'] <= "2016-08-31" AND ($met['id_polis']=="1" OR $met['id_polis']=="2")) {		// Produk SPK REGULER dan PERCEPATAN yg tgl input dibawah tgl 31 agustus 2016 menggunakan rate lama
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND tenor="'.$tenornya.'" AND status="lama" AND del IS NULL'));		// RATE PREMI
							}else{
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND tenor="'.$tenornya.'" AND status="baru" AND del IS NULL'));		// RATE PREMI
							}
						}
					}else{
						if ($met_polis['mpptype']=="Y") {
							$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$met['id_cost'].'" AND id_polis="'.$met['id_polis'].'" AND tenor="'.$tenornya .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
						}else{
							//$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $metFormSPK['tenor'] . '" AND del IS NULL')); // RATE PREMI
							if ($met['tglinput'] <= "2016-08-31" AND ($met['id_polis']=="1" OR $met['id_polis']=="2")) {		// Produk SPK REGULER dan PERCEPATAN yg tgl input dibawah tgl 31 agustus 2016 menggunakan rate lama
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $tenornya . '" AND status="lama" AND del IS NULL')); // RATE PREMI
							}else{
								$cekrate = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="' . $met['id_cost'] . '" AND id_polis="' . $met['id_polis'] . '" AND usia="' . $metFormSPK['x_usia'] . '" AND tenor="' . $tenornya . '" AND status="baru" AND del IS NULL')); // RATE PREMI
							}
						}
					}

					//$premi = $metFormSPK['plafond'] * $cekrate['rate'] / 1000;
					$premi = $qspk['premi'];

					$metStatusPremi = $premi;
					$metExtPremi = ROUND($premi * $qspk['em'] / 100);	//HITUNG EXTRA PREMI
					$mettotalpremibayangan = $premi + $metExtPremi;
					if ($mettotalpremibayangan < $met_polis['min_premium']) {
						$mettotalpremibayangan_ = $met_polis['min_premium'];
					}else{
						$mettotalpremibayangan_ = $mettotalpremibayangan;
					}

					//premi yang dikirim ke asuransi premi debitur tidak ditampilkan 29092016
					if ($qspk['statusspk']=="PreApproval") {
						$pdf->Cell(35,5, duit($qspk['plafond']) ,1,0,'C');
						$pdf->Cell(25,5,_convertDate($qspk['tglakad']) ,1,0,'C');
						$pdf->Cell(20,5,$qspk['tenor'] ,1,0,'C');
						$pdf->Cell(25,5,_convertDate($qspk['tglakhir']) ,1,0,'C');
						$pdf->Cell(30,5,'',1,0,'C');
						$pdf->Cell(22,5,'',1,0,'C');
						$pdf->Cell(30,5,'',1,0,'C');
					}else{

						$pdf->Cell(35,5, duit($qspk['plafond']) ,1,0,'C');
						$pdf->Cell(25,5,_convertDate($qspk['tglakad']) ,1,0,'C');
						$pdf->Cell(20,5,$qspk['tenor'] ,1,0,'C');
						$pdf->Cell(25,5,_convertDate($qspk['tglakhir']) ,1,0,'C');
						$pdf->Cell(30,5,duit($metStatusPremi) ,1,0,'C');
						$pdf->Cell(22,5,duit($qspk['ext_premi']).'%' ,1,0,'C');
						$pdf->Cell(30,5,duit($mettotalpremibayangan_) ,1,0,'C');
					}
					//premi yang dikirim ke asuransi premi debitur tidak ditampilkan 29092016

					$pdf->Ln();
					//if ($met['ket_ext']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM'. $met['ket_ext'];	}
					//$pdf->SetX(9);	$pdf->Cell(37,4, 'Keterangan' ,0,'L');			$pdf->Cell(36,4,': ' .$met_ket_EM ,0,'1');

					$pdf->SetFont('helvetica','',9);
					$pdf->SetX(9);
					$pdf->MultiCell(195,4,'Keterangan' ,0,'L');
					$pdf->SetX(9);
					if ($metFormSPK['ket6']!="") { $keterangan_6 = ', '.$metFormSPK['ket6'];	}else{	$keterangan_6 = '';	}
					$pdf->SetFont('helvetica','',9);
					$pdf->MultiCell(195,4,$met_ket_EM);

					$pdf->Ln();
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Cabang' ,0,'L');				$pdf->Cell(36,4,': ' .$qusercabang['name'] ,0,'1');
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Input User' ,0,'L');			$pdf->Cell(36,4,': ' .strtoupper($quser['firstname']) ,0,'1');
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Tanggal Input' ,0,'L');		$pdf->Cell(36,4,': ' .$qspk['input_date'] ,0,'1');

					//end 150908 modify by satrya
					//$met_tglinputnya = explode(" ",$metFormSPK['input_date']);

					//KONDISI MENAMPILKAN CATATAN USIA APABILA AKAN NAIK USIA SISA 1 BULAN LAGI
					//if ($met_tglinputnya[0] <= $datelog) {
					//}else{
					$pdf->Ln();
					$mets = datediff($metFormSPK['tgl_asuransi'], $metFormSPK['dob']);
					$cekbulan = explode(",", $mets);
					if ($cekbulan[1] >= 6 ) {	$umur = $cekbulan[0] + 1;	}else{	$umur = $cekbulan[0];	}
					if ($cekbulan[1] == 5) {
						$sisahari = 30 - $cekbulan[2];
						$sisathn = $cekbulan[0] + 1;
						$blnnnya ='Mohon mengajukan Deklarasi kurang dari '.$sisahari.' hari, sebelum usia akan bertambah menjadi '.$sisathn.' tahun';
						if ($met['status']=="Tolak") {

						}else{
							$pdf->SetX(9);	$pdf->Cell(50,4, 'Catatan : ' ,0,'L');	$pdf->Cell(36,4,'' ,0,'1');
							$pdf->Cell(36,4,$blnnnya ,0,'1');
						}
					}else{	$blnnnya ='';	}

					//PERTANYAAN HISTORY PENYAKIT APABILA DATA MP
					$cekSKKT = mysql_fetch_array(mysql_query('SELECT * FROM ajkskkt WHERE nomorspk="'.$qspk['nomorspk'].'"'));
					if ($cekSKKT['question_2']) {
						$pdf->AddPage();
						$pdf->SetFont('Arial','B',14);
						$pdf->Cell(190,5,'KETERANGAN KESEHATAN TERTANGGUNG',0,0,'C');	$pdf->Ln();	$pdf->Ln();
						$pdf->SetFont('Arial','',10);
						$metSehat = explode("|", $cekSKKT['question_1']);
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'1. ',0,0,'L');	$pdf->Cell(140,5,'Apakah Anda dalam keadaan sehat / tidak sehat :',0,0,'L');	$pdf->Cell(20,5,'Sehat',0,0,'C');	$pdf->Cell(25,5,'Tidak Sehat',0,0,'C');	$pdf->Ln();
						$pdf->SetFont('Arial','',10);
						if ($metSehat[0]=="S") {	$setkolom1 = 'Sehat';	}else{	$setkolom11 = 'Tidak Sehat';	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Pada saat ini dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom1,0,0,'C');	$pdf->Cell(25,5,$setkolom11,0,0,'C');	$pdf->Ln();

						if ($metSehat[1]=="S") {	$setkolom2 = 'Sehat';	}else{	$setkolom22 = 'Tidak Sehat';	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(135,5,'Biasanya dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom2,0,0,'C');	$pdf->Cell(25,5,$setkolom22,0,0,'C');	$pdf->Ln();	$pdf->Ln();

						$pdf->SetFont('Arial','B',10);
						$metSehat = explode("|", $cekSKKT['question_2']);
						$pdf->Cell(6,5,'2. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 2 tahun terakhir ini, apakah anda pernah / tidak pernah :',0,0,'L');	$pdf->Cell(20,5,'Pernah',0,0,'C');		$pdf->Cell(25,5,'Tidak Pernah',0,0,'C');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						if ($metSehat[0]=="P") {	$setkolom3 = 'Pernah';	}else{	$setkolom33 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'1. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit malaria ',0,0,'L');													$pdf->Cell(20,5,$setkolom3,0,0,'C');	$pdf->Cell(25,5,$setkolom33,0,0,'C');		$pdf->Ln();

						if ($metSehat[1]=="P") {	$setkolom4 = 'Pernah';	}else{	$setkolom44 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'2. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kanker ',0,0,'L');														$pdf->Cell(20,5,$setkolom4,0,0,'C');	$pdf->Cell(25,5,$setkolom44,0,0,'C');		$pdf->Ln();

						if ($metSehat[2]=="P") {	$setkolom5 = 'Pernah';	}else{	$setkolom55 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'3. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit TBC ',0,0,'L');														$pdf->Cell(20,5,$setkolom5,0,0,'C');	$pdf->Cell(25,5,$setkolom55,0,0,'C');		$pdf->Ln();

						if ($metSehat[3]=="P") {	$setkolom6 = 'Pernah';	}else{	$setkolom66 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'4. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kencing manis ',0,0,'L');												$pdf->Cell(20,5,$setkolom6,0,0,'C');	$pdf->Cell(25,5,$setkolom66,0,0,'C');		$pdf->Ln();

						if ($metSehat[4]=="P") {	$setkolom7 = 'Pernah';	}else{	$setkolom77 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'5. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit hati ',0,0,'L');														$pdf->Cell(20,5,$setkolom7,0,0,'C');	$pdf->Cell(25,5,$setkolom77,0,0,'C');		$pdf->Ln();

						if ($metSehat[5]=="P") {	$setkolom8 = 'Pernah';	}else{	$setkolom88 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'6. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ginjal ',0,0,'L');														$pdf->Cell(20,5,$setkolom8,0,0,'C');	$pdf->Cell(25,5,$setkolom88,0,0,'C');		$pdf->Ln();

						if ($metSehat[6]=="P") {	$setkolom9 = 'Pernah';	}else{	$setkolom99 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'7. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit jantung ',0,0,'L');													$pdf->Cell(20,5,$setkolom9,0,0,'C');	$pdf->Cell(25,5,$setkolom99,0,0,'C');		$pdf->Ln();

						if ($metSehat[7]=="P") {	$setkolom10 = 'Pernah';	}else{	$setkolom1010 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'8. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ayan ',0,0,'L');														$pdf->Cell(20,5,$setkolom10,0,0,'C');	$pdf->Cell(25,5,$setkolom1010,0,0,'C');		$pdf->Ln();

						if ($metSehat[8]=="P") {	$setkolom11 = 'Pernah';	}else{	$setkolom1111 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'9. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit lumpuh ',0,0,'L');														$pdf->Cell(20,5,$setkolom11,0,0,'C');	$pdf->Cell(25,5,$setkolom1111,0,0,'C');		$pdf->Ln();

						if ($metSehat[9]=="P") {	$setkolom12 = 'Pernah';	}else{	$setkolom1212 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'10. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit syaraf ',0,0,'L');														$pdf->Cell(20,5,$setkolom12,0,0,'C');	$pdf->Cell(25,5,$setkolom1212,0,0,'C');		$pdf->Ln();

						if ($metSehat[10]=="P") {	$setkolom13 = 'Pernah';	}else{	$setkolom1313 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'11. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah tinggi ',0,0,'L');										$pdf->Cell(20,5,$setkolom13,0,0,'C');	$pdf->Cell(25,5,$setkolom1313,0,0,'C');		$pdf->Ln();

						if ($metSehat[11]=="P") {	$setkolom14 = 'Pernah';	}else{	$setkolom1414 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'12. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah rendah ',0,0,'L');										$pdf->Cell(20,5,$setkolom14,0,0,'C');	$pdf->Cell(25,5,$setkolom1414,0,0,'C');		$pdf->Ln();

						if ($metSehat[12]=="P") {	$setkolom15 = 'Pernah';	}else{	$setkolom1515 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'13. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kelamin ',0,0,'L');													$pdf->Cell(20,5,$setkolom15,0,0,'C');	$pdf->Cell(25,5,$setkolom1515,0,0,'C');		$pdf->Ln();

						if ($metSehat[13]=="P") {	$setkolom16 = 'Pernah';	}else{	$setkolom1616 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'14. ',0,0,'L');	$pdf->Cell(129,5,'Dirawat di Rumah Sakit ',0,0,'L');														$pdf->Cell(20,5,$setkolom16,0,0,'C');	$pdf->Cell(25,5,$setkolom1616,0,0,'C');		$pdf->Ln();

						if ($metSehat[14]=="P") {	$setkolom17 = 'Pernah';	}else{	$setkolom1717 = 'Tidak Pernah';	}
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(180,5,'Jika pernah dirawat di Rumah Sakit, sebutkan nama dan alamat Rumah Sakit',0,0,'L');	$pdf->Ln();
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,'yang merawat Anda',0,0,'L');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,$cekSKKT['nm_alamat_rs'],0,0,'L');

						if ($metSehat[15]=="P") {	$setkolom18 = 'Pernah';	}else{	$setkolom1818 = 'Tidak Pernah';	}
						$pdf->Cell(6,10,'',0,0,'L');	$pdf->Cell(185,10,' ',0,0,'L');	$pdf->Ln();

						if ($cekSKKT['question_3']=="P") {	$setkolom19 = 'Pernah';	}else{	$setkolom1919 = 'Tidak Pernah';	}
						$pdf->Cell(6,5,'3. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 12 bulan terakhir ini pernah / tidak pernah dirawat dokter:',0,0,'L');	$pdf->Cell(20,5,$setkolom19,0,0,'C');	$pdf->Cell(25,5,$setkolom1919,0,0,'C');		$pdf->Ln();

						if ($cekSKKT['nm_dokter']=="")		{	$_ohhdokter = "---";	}else{	$_ohhdokter = $cekSKKT['nm_dokter'];	}
						if ($cekSKKT['almt_dokter']=="")	{	$_ohhdokter_ = "---";	}else{	$_ohhdokter_ = $cekSKKT['almt_dokter'];	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(30,5,'Nama dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter,0,0,'L');	$pdf->Ln();
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(30,5,'Alamat dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter_,0,0,'L');	$pdf->Ln();	$pdf->Ln();

						$pdf->SetFont('Arial','B',10);
						if ($cekSKKT['question_4']=="H") {	$setkolom20 = 'Hamil';	}else{	$setkolom2020 = 'Tidak Hamil';	}

						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'4. ',0,0,'L');	$pdf->Cell(140,5,'Saat ini dalam keadaan',0,0,'L');	$pdf->Cell(20,5,'Hamil',0,0,'C');	$pdf->Cell(25,5,'Tidak Hamil',0,0,'C');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(140,5,'(hanya untuk wanita)',0,0,'L');	$pdf->Cell(20,5,$setkolom20,0,0,'C');		$pdf->Cell(25,5,$setkolom2020,0,0,'C');	$pdf->Ln();	$pdf->Ln();
						$pdf->MultiCell(195,4,'Pernyataan-pernyataan tersebut di atas saya jawab dengan jujur sesuai dengan keadaan yang sebenarnya dan jika ada suatu hal yang saya ketahui dan tidak saya beritahukan atau saya dengan sengaja menjawab dengan tidak jujur / tidak benar, maka Pihak Asuransi berhak membatalkan atau menolak pembayaran manfaat asuransi ini.' ,0,'L');
						$pdf->MultiCell(195,4,'Selanjutnya saya dengan ini memberi kuasa penuh kepada Pemegang Polis dan Dokter-Dokter yang akan atau telah memeriksa atau mengobati saya untuk memberi keterangan-keterangan yang diminta oleh Pihak Asuransi mengenai segala sesuatu yang diperlukan dalam hubungannya dengan penutupan asuransi ini.' ,0,'L');	$pdf->Ln();
						$jangkrikgan = explode(" ", $cekSKKT['created_at']);
						$pdf->Cell(190,5,ucfirst(strtolower(($inputcabang))).', '. _convertDate($jangkrikgan[0]),0,0,'R');	$pdf->Ln();
						$pdf->Cell(190,5,'Mengetahui,',0,0,'L');	$pdf->Ln();
						$pdf->Cell(95,5,'Pejabat Bank / Koperasi / Lembaga',0,0,'L');
						$pdf->Cell(95,5,'Yang membuat pernyataan',0,0,'C');	$pdf->Ln(32);

						//TTD MARKETING
						if ($qspk['ttdmarketing']!="") {
							$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
							if (file_exists($pathFile))
							{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
								$pdf->Image($mamet_ttddebitur,10,225,40,30);
							}
							else {	$pdf->Text(15, 240,'Tidak Ada TTD Marketing');	}
						}
						$pdf->Cell(95,5,strtoupper($quser['firstname']),0,0,'L');

						//TTD DEBITUR
						if ($qspk['ttddebitur']!="") {
							$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
							if (file_exists($pathFile))
							{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
								$pdf->Image($mamet_ttddebitur,132,225,40,30);
							}
							else {	$pdf->Text(135, 240,'Tidak Ada TTD Debitur');	}
						}
						$pdf->Cell(95,5,strtoupper($qspk['nama']),0,0,'C');

					}else{

					}

					//PERTANYAAN HISTORY PENYAKIT APABILA DATA MP
				}else{
					$met_ket_EM = $met['keterangan'];
					$pdf->Cell(35,5,'',1,0,'C');
					$pdf->Cell(25,5,'',1,0,'C');
					$pdf->Cell(20,5,'',1,0,'C');
					$pdf->Cell(25,5,'',1,0,'C');
					$pdf->Cell(30,5,'',1,0,'C');
					$pdf->Cell(22,5,'',1,0,'C');
					$pdf->Cell(30,5,'',1,0,'C');
					$pdf->Ln();
					//if ($met['ket_ext']=="") {	$met_ket_EM ='PREMI STANDAR';	}else{	$met_ket_EM = 'EM'. $met['ket_ext'];	}
					//$pdf->SetX(9);	$pdf->Cell(37,4, 'Keterangan' ,0,'L');			$pdf->Cell(36,4,': ' .$met_ket_EM ,0,'1');

					$pdf->SetFont('helvetica','',9);
					$pdf->SetX(9);
					$pdf->MultiCell(195,4,'Keterangan' ,0,'L');
					$pdf->SetX(9);
					if ($metFormSPK['ket6']!="") { $keterangan_6 = ', '.$metFormSPK['ket6'];	}else{	$keterangan_6 = '';	}
					$pdf->SetFont('helvetica','',9);
					$pdf->MultiCell(195,4,$met_ket_EM);
					$pdf->Ln();
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Cabang' ,0,'L');				$pdf->Cell(36,4,': ' .$qusercabang['name'] ,0,'1');
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Input User' ,0,'L');			$pdf->Cell(36,4,': ' .strtoupper($quser['firstname']) ,0,'1');
					$pdf->SetX(9);	$pdf->Cell(37,4, 'Tanggal Input' ,0,'L');		$pdf->Cell(36,4,': ' .$qspk['input_date'] ,0,'1');

					//end 150908 modify by satrya
					//$met_tglinputnya = explode(" ",$metFormSPK['input_date']);

					//KONDISI MENAMPILKAN CATATAN USIA APABILA AKAN NAIK USIA SISA 1 BULAN LAGI
					//if ($met_tglinputnya[0] <= $datelog) {
					//}else{
					$pdf->Ln();
					$mets = datediff($metFormSPK['tgl_asuransi'], $metFormSPK['dob']);
					$cekbulan = explode(",", $mets);
					if ($cekbulan[1] >= 6 ) {	$umur = $cekbulan[0] + 1;	}else{	$umur = $cekbulan[0];	}
					if ($cekbulan[1] == 5) {
						$sisahari = 30 - $cekbulan[2];
						$sisathn = $cekbulan[0] + 1;
						$blnnnya ='Mohon mengajukan Deklarasi kurang dari '.$sisahari.' hari, sebelum usia akan bertambah menjadi '.$sisathn.' tahun';
						if ($met['status']=="Tolak") {

						}else{
							$pdf->SetX(9);	$pdf->Cell(50,4, 'Catatan : ' ,0,'L');	$pdf->Cell(36,4,'' ,0,'1');
							$pdf->Cell(36,4,$blnnnya ,0,'1');
						}
					}else{	$blnnnya ='';	}

					//PERTANYAAN HISTORY PENYAKIT APABILA DATA MP
					$cekSKKT = mysql_fetch_array(mysql_query('SELECT * FROM ajkskkt WHERE nomorspk="'.$qspk['nomorspk'].'"'));
					if ($cekSKKT['question_2']) {
						$pdf->AddPage();
						$pdf->SetFont('Arial','B',14);
						$pdf->Cell(190,5,'KETERANGAN KESEHATAN TERTANGGUNG',0,0,'C');	$pdf->Ln();	$pdf->Ln();
						$pdf->SetFont('Arial','',10);
						$metSehat = explode("|", $cekSKKT['question_1']);
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'1. ',0,0,'L');	$pdf->Cell(140,5,'Apakah Anda dalam keadaan sehat / tidak sehat :',0,0,'L');	$pdf->Cell(20,5,'Sehat',0,0,'C');	$pdf->Cell(25,5,'Tidak Sehat',0,0,'C');	$pdf->Ln();
						$pdf->SetFont('Arial','',10);
						if ($metSehat[0]=="S") {	$setkolom1 = 'Sehat';	}else{	$setkolom11 = 'Tidak Sehat';	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Pada saat ini dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom1,0,0,'C');	$pdf->Cell(25,5,$setkolom11,0,0,'C');	$pdf->Ln();

						if ($metSehat[1]=="S") {	$setkolom2 = 'Sehat';	}else{	$setkolom22 = 'Tidak Sehat';	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(135,5,'Biasanya dalam keadaan :',0,0,'L');	$pdf->Cell(20,5,$setkolom2,0,0,'C');	$pdf->Cell(25,5,$setkolom22,0,0,'C');	$pdf->Ln();	$pdf->Ln();

						$pdf->SetFont('Arial','B',10);
						$metSehat = explode("|", $cekSKKT['question_2']);
						$pdf->Cell(6,5,'2. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 2 tahun terakhir ini, apakah anda pernah / tidak pernah :',0,0,'L');	$pdf->Cell(20,5,'Pernah',0,0,'C');		$pdf->Cell(25,5,'Tidak Pernah',0,0,'C');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						if ($metSehat[0]=="P") {	$setkolom3 = 'Pernah';	}else{	$setkolom33 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'1. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit malaria ',0,0,'L');													$pdf->Cell(20,5,$setkolom3,0,0,'C');	$pdf->Cell(25,5,$setkolom33,0,0,'C');		$pdf->Ln();

						if ($metSehat[1]=="P") {	$setkolom4 = 'Pernah';	}else{	$setkolom44 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'2. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kanker ',0,0,'L');														$pdf->Cell(20,5,$setkolom4,0,0,'C');	$pdf->Cell(25,5,$setkolom44,0,0,'C');		$pdf->Ln();

						if ($metSehat[2]=="P") {	$setkolom5 = 'Pernah';	}else{	$setkolom55 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'3. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit TBC ',0,0,'L');														$pdf->Cell(20,5,$setkolom5,0,0,'C');	$pdf->Cell(25,5,$setkolom55,0,0,'C');		$pdf->Ln();

						if ($metSehat[3]=="P") {	$setkolom6 = 'Pernah';	}else{	$setkolom66 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'4. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kencing manis ',0,0,'L');												$pdf->Cell(20,5,$setkolom6,0,0,'C');	$pdf->Cell(25,5,$setkolom66,0,0,'C');		$pdf->Ln();

						if ($metSehat[4]=="P") {	$setkolom7 = 'Pernah';	}else{	$setkolom77 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'5. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit hati ',0,0,'L');														$pdf->Cell(20,5,$setkolom7,0,0,'C');	$pdf->Cell(25,5,$setkolom77,0,0,'C');		$pdf->Ln();

						if ($metSehat[5]=="P") {	$setkolom8 = 'Pernah';	}else{	$setkolom88 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'6. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ginjal ',0,0,'L');														$pdf->Cell(20,5,$setkolom8,0,0,'C');	$pdf->Cell(25,5,$setkolom88,0,0,'C');		$pdf->Ln();

						if ($metSehat[6]=="P") {	$setkolom9 = 'Pernah';	}else{	$setkolom99 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'7. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit jantung ',0,0,'L');													$pdf->Cell(20,5,$setkolom9,0,0,'C');	$pdf->Cell(25,5,$setkolom99,0,0,'C');		$pdf->Ln();

						if ($metSehat[7]=="P") {	$setkolom10 = 'Pernah';	}else{	$setkolom1010 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'8. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit ayan ',0,0,'L');														$pdf->Cell(20,5,$setkolom10,0,0,'C');	$pdf->Cell(25,5,$setkolom1010,0,0,'C');		$pdf->Ln();

						if ($metSehat[8]=="P") {	$setkolom11 = 'Pernah';	}else{	$setkolom1111 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'9. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit lumpuh ',0,0,'L');														$pdf->Cell(20,5,$setkolom11,0,0,'C');	$pdf->Cell(25,5,$setkolom1111,0,0,'C');		$pdf->Ln();

						if ($metSehat[9]=="P") {	$setkolom12 = 'Pernah';	}else{	$setkolom1212 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'10. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit syaraf ',0,0,'L');														$pdf->Cell(20,5,$setkolom12,0,0,'C');	$pdf->Cell(25,5,$setkolom1212,0,0,'C');		$pdf->Ln();

						if ($metSehat[10]=="P") {	$setkolom13 = 'Pernah';	}else{	$setkolom1313 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'11. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah tinggi ',0,0,'L');										$pdf->Cell(20,5,$setkolom13,0,0,'C');	$pdf->Cell(25,5,$setkolom1313,0,0,'C');		$pdf->Ln();

						if ($metSehat[11]=="P") {	$setkolom14 = 'Pernah';	}else{	$setkolom1414 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'12. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit tekanan darah rendah ',0,0,'L');										$pdf->Cell(20,5,$setkolom14,0,0,'C');	$pdf->Cell(25,5,$setkolom1414,0,0,'C');		$pdf->Ln();

						if ($metSehat[12]=="P") {	$setkolom15 = 'Pernah';	}else{	$setkolom1515 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'13. ',0,0,'L');	$pdf->Cell(129,5,'Menderita penyakit kelamin ',0,0,'L');													$pdf->Cell(20,5,$setkolom15,0,0,'C');	$pdf->Cell(25,5,$setkolom1515,0,0,'C');		$pdf->Ln();

						if ($metSehat[13]=="P") {	$setkolom16 = 'Pernah';	}else{	$setkolom1616 = 'Tidak Pernah';	}
						$pdf->Cell(10,5,'',0,0,'L');	$pdf->Cell(7,5,'14. ',0,0,'L');	$pdf->Cell(129,5,'Dirawat di Rumah Sakit ',0,0,'L');														$pdf->Cell(20,5,$setkolom16,0,0,'C');	$pdf->Cell(25,5,$setkolom1616,0,0,'C');		$pdf->Ln();

						if ($metSehat[14]=="P") {	$setkolom17 = 'Pernah';	}else{	$setkolom1717 = 'Tidak Pernah';	}
						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(180,5,'Jika pernah dirawat di Rumah Sakit, sebutkan nama dan alamat Rumah Sakit',0,0,'L');	$pdf->Ln();
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,'yang merawat Anda',0,0,'L');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(180,5,$cekSKKT['nm_alamat_rs'],0,0,'L');

						if ($metSehat[15]=="P") {	$setkolom18 = 'Pernah';	}else{	$setkolom1818 = 'Tidak Pernah';	}
						$pdf->Cell(6,10,'',0,0,'L');	$pdf->Cell(185,10,' ',0,0,'L');	$pdf->Ln();

						if ($cekSKKT['question_3']=="P") {	$setkolom19 = 'Pernah';	}else{	$setkolom1919 = 'Tidak Pernah';	}
						$pdf->Cell(6,5,'3. ',0,0,'L');	$pdf->Cell(5,5,'a. ',0,0,'L');	$pdf->Cell(135,5,'Dalam jangka waktu 12 bulan terakhir ini pernah / tidak pernah dirawat dokter:',0,0,'L');	$pdf->Cell(20,5,$setkolom19,0,0,'C');	$pdf->Cell(25,5,$setkolom1919,0,0,'C');		$pdf->Ln();

						if ($cekSKKT['nm_dokter']=="")		{	$_ohhdokter = "---";	}else{	$_ohhdokter = $cekSKKT['nm_dokter'];	}
						if ($cekSKKT['almt_dokter']=="")	{	$_ohhdokter_ = "---";	}else{	$_ohhdokter_ = $cekSKKT['almt_dokter'];	}
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'b. ',0,0,'L');	$pdf->Cell(30,5,'Nama dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter,0,0,'L');	$pdf->Ln();
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(5,5,'',0,0,'L');		$pdf->Cell(30,5,'Alamat dokter ',0,0,'L');	$pdf->Cell(150,5,': '.$_ohhdokter_,0,0,'L');	$pdf->Ln();	$pdf->Ln();

						$pdf->SetFont('Arial','B',10);
						if ($cekSKKT['question_4']=="H") {	$setkolom20 = 'Hamil';	}else{	$setkolom2020 = 'Tidak Hamil';	}

						$pdf->SetFont('Arial','B',10);
						$pdf->Cell(6,5,'4. ',0,0,'L');	$pdf->Cell(140,5,'Saat ini dalam keadaan',0,0,'L');	$pdf->Cell(20,5,'Hamil',0,0,'C');	$pdf->Cell(25,5,'Tidak Hamil',0,0,'C');	$pdf->Ln();

						$pdf->SetFont('Arial','',10);
						$pdf->Cell(6,5,'',0,0,'L');		$pdf->Cell(140,5,'(hanya untuk wanita)',0,0,'L');	$pdf->Cell(20,5,$setkolom20,0,0,'C');		$pdf->Cell(25,5,$setkolom2020,0,0,'C');	$pdf->Ln();	$pdf->Ln();
						$pdf->MultiCell(195,4,'Pernyataan-pernyataan tersebut di atas saya jawab dengan jujur sesuai dengan keadaan yang sebenarnya dan jika ada suatu hal yang saya ketahui dan tidak saya beritahukan atau saya dengan sengaja menjawab dengan tidak jujur / tidak benar, maka Pihak Asuransi berhak membatalkan atau menolak pembayaran manfaat asuransi ini.' ,0,'L');
						$pdf->MultiCell(195,4,'Selanjutnya saya dengan ini memberi kuasa penuh kepada Pemegang Polis dan Dokter-Dokter yang akan atau telah memeriksa atau mengobati saya untuk memberi keterangan-keterangan yang diminta oleh Pihak Asuransi mengenai segala sesuatu yang diperlukan dalam hubungannya dengan penutupan asuransi ini.' ,0,'L');	$pdf->Ln();
						$jangkrikgan = explode(" ", $cekSKKT['created_at']);
						$pdf->Cell(190,5,ucfirst(strtolower(($inputcabang))).', '. _convertDate($jangkrikgan[0]),0,0,'R');	$pdf->Ln();
						$pdf->Cell(190,5,'Mengetahui,',0,0,'L');	$pdf->Ln();
						$pdf->Cell(95,5,'Pejabat Bank / Koperasi / Lembaga',0,0,'L');
						$pdf->Cell(95,5,'Yang membuat pernyataan',0,0,'C');	$pdf->Ln(32);

						//TTD MARKETING
						if ($qspk['ttdmarketing']!="") {
							$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
							if (file_exists($pathFile))
							{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttdmarketing'];
								$pdf->Image($mamet_ttddebitur,10,225,40,30);
							}
							else {	$pdf->Text(15, 240,'Tidak Ada TTD Marketing');	}
						}
						$pdf->Cell(95,5,strtoupper($quser['firstname']),0,0,'L');

						//TTD DEBITUR
						if ($qspk['ttddebitur']!="") {
							$pathFile = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
							if (file_exists($pathFile))
							{	$mamet_ttddebitur = '../androidscript/uploads/'.$foldernamedebitur.'/'.$qspk['ttddebitur'];
								$pdf->Image($mamet_ttddebitur,132,225,40,30);
							}
							else {	$pdf->Text(135, 240,'Tidak Ada TTD Debitur');	}
						}
						$pdf->Cell(95,5,strtoupper($qspk['nama']),0,0,'C');

					}else{

					}
				}


			}
		}
		$namafilenya = str_replace(" ","_" , $qspk['nama']);
		$pdf->Output("SPK_".$qspk['nomorspk']."_".$namafilenya.".pdf","I");

	;
} // switch

?>