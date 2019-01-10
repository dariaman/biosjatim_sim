<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
error_reporting(0);
include_once('../fpdf.php');
define('../FPDF_FONTPATH', 'font/');
include_once('../includes/functions.php');
include_once('../includes/code39.php');
include_once('../koneksi.php');
session_start();
//include_once('../param.php');
$type = 'query'.$_REQUEST['pdf'];
switch ($_REQUEST['pdf']) {
	case "tblangsuran":
		$bunga = $_REQUEST['bunga'];
		$tenor = $_REQUEST['tn']*12;
		$plafond = $_REQUEST['plf'];
		$tahuncadangan = 1;
		$plafond_cadangan = $plafond;
		$rateas = 3.75;

		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(270,4,'DATA ANGSURAN',0,0,'C',0);$pdf->ln();
		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(15);
		$pdf->cell(20,5,'Bulan ke',1,0,'C',1);
		$pdf->cell(40,5,'Pokok Pinjaman',1,0,'C',1);
		$pdf->cell(40,5,'Cicilan Pokok Pinjaman',1,0,'C',1);
		$pdf->cell(40,5,'Bunga',1,0,'C',1);
		$pdf->cell(40,5,'Angsuran per Bulan',1,0,'C',1);
		$pdf->cell(40,5,'Saldo Pokok Pinjaman',1,0,'C',1);
		$pdf->cell(40,5,'Nilai Asuransi',1,0,'C',1);
		$pdf->Ln();
		$i = 1;
		$n = 1;
		$plafond_cadangan = $plafond;
	 	while ($i <= $tenor) {
	 		$angsuran = $plafond*(($bunga/100)/12)/(1-(1/pow((1+($bunga/100)/12),$tenor)));
	 		$bungaasungsuran = $plafond_cadangan * (($bunga/100)/12);
	 		$cicilanpokok = $angsuran - $bungaasungsuran;
	 		
	 		if($i == $tenor){
	 			$saldo = 0;	
	 		}else{	 			
	 			$saldo = $plafond_cadangan - $cicilanpokok;	
	 		}

	 		if($n == 12 or $i == 1){
	 			$n = 1;
	 			$asuransi = ($plafond_cadangan/1000) * $rateas;	
	 		}else{
	 			$asuransi = 0;	
	 			$n++;
	 		}

	 		$pdf->setX(15);
	 		$pdf->cell(20,5,$i,1,0,'C');
	 		$pdf->cell(40,5,duit(round($plafond_cadangan,0)),1,0,'C');
	 		$pdf->cell(40,5,duit(round($cicilanpokok,0)),1,0,'C');
	 		$pdf->cell(40,5,duit(round($bungaasungsuran,0)),1,0,'C');
	 		$pdf->cell(40,5,duit(round($angsuran,0)),1,0,'C');
	 		$pdf->cell(40,5,duit(round($saldo,0)),1,0,'C');
	 		$pdf->cell(40,5,duit(round($asuransi,0)),1,0,'C');

	 		$pdf->Ln();
	 		$plafond_cadangan = $saldo;
	 		$i++;
	 	}
	 			
	 			

	 			// $i = 1;
	 			// while($i <= $tenor){
	 			// 	if($i == 1){
	 			// 		$plafond_cadangan = $plafond;
					// 	$nilai_cicilan = round(($plafond_cadangan * 3.75/1000),0);
	 			// 	}else{
		 		// 		$bungabulan = $plafond_cadangan * (($bunga/100)/12);
		 		// 		$cicilanpokok = $angsuran - $bungabulan;
		 		// 		$plafond_cadangan = round($plafond_cadangan - $cicilanpokok,0);				 					
		 		// 		$nilai_cicilan = round(($plafond_cadangan * 3.75/1000),0);
	 			// 	}
	 			// 	//echo $plafond_cadangan.' '.$i.'<br>';				
	 				
					// $pdf->setX(5);
			// 		$pdf->cell(8,5,$i,1,0,'C');
			// 		$i++;
	 	// 		}
	 	// }

		$pdf->Output("Report_Member.pdf","I");
	break;

	case "lprmember":
		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		/*if ($_REQUEST['idb']) {
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);	}
		}else{*/
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);
				}
		/*if ($_REQUEST['idc']) {*/
			$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,240,7,45,15);	}
		/*}else{	}*/


		$pdf->ln(-12);
		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(270,4,'MEMBERSHIP DATA REPORT',0,0,'C',0);$pdf->ln();
		// $pdf->cell(270,4,$met_['brokername'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprproduk'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprstatus'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprcabang'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprperiode'],0,0,'C',0);$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(5);
		$pdf->cell(8,5,'No',1,0,'C',1);
		$pdf->cell(28,5,'No Pinjaman',1,0,'C',1);
		
		$pdf->cell(25,5,'ID Peserta',1,0,'C',1);
		$pdf->cell(45,5,'Nama',1,0,'C',1);
		$pdf->cell(20,5,'Tgl Lahir',1,0,'C',1);
		$pdf->cell(8,5,'Usia',1,0,'C',1);
		$pdf->cell(20,5,'Plafon',1,0,'C',1);
		$pdf->cell(18,5,'Tgl Akad',1,0,'C',1);
		$pdf->cell(10,5,'Tenor',1,0,'C',1);
		$pdf->cell(18,5,'Tgl Akhir',1,0,'C',1);		
		$pdf->cell(45,5,'Cabang',1,0,'C',1);
		$pdf->cell(20,5,'Asuransi',1,0,'C',1);
		$pdf->cell(21,5,'Nett Premi',1,0,'C',1);		

		$query = AES::decrypt128CBC($_SESSION[$type], ENCRYPTION_KEY);
		$metCOB = mysql_query($query);
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$cell[$i][0] = $metCOB_['nopinjaman'];
			$cell[$i][1] = $metCOB_['idpeserta'];
			$cell[$i][2] = $metCOB_['nama'];
			$cell[$i][3] = _convertDate($metCOB_['tgllahir']);
			$cell[$i][4] = $metCOB_['usia'];
			$cell[$i][5] = duit($metCOB_['plafond']);
			$cell[$i][6] = _convertDate($metCOB_['tglakad']);
			$cell[$i][7] = $metCOB_['tenor'];
			$cell[$i][8] = _convertDate($metCOB_['tglakhir']);			
			$cell[$i][9] = $metCOB_['cabang'];
			$cell[$i][10] = $metCOB_['asuransi'];
			$cell[$i][11] = duit($metCOB_['totalpremi']);
			$tPremi += $metCOB_['totalpremi'];
			$i++;
		}
		$pdf->Ln();

		for($j<1;$j<$i;$j++){	
			$pdf->setX(5);
			$pdf->cell(8,5,$j+1,1,0,'C');
			$pdf->cell(28,5,$cell[$j][0],1,0,'C');
			$pdf->cell(25,5,$cell[$j][1],1,0,'C');
			$pdf->cell(45,5,$cell[$j][2],1,0,'L');
			$pdf->cell(20,5,$cell[$j][3],1,0,'C');
			$pdf->cell(8,5,$cell[$j][4],1,0,'C');
			$pdf->cell(20,5,$cell[$j][5],1,0,'C');
			$pdf->cell(18,5,$cell[$j][6],1,0,'C');
			$pdf->cell(10,5,$cell[$j][7],1,0,'C');
			$pdf->cell(18,5,$cell[$j][8],1,0,'C');			
			$pdf->cell(45,5,$cell[$j][9],1,0,'C');
			$pdf->cell(20,5,$cell[$j][10],1,0,'C');
			$pdf->cell(21,5,$cell[$j][11],1,0,'R');
			$pdf->Ln();
		}
		$pdf->setX(5);
		$pdf->cell(265,6,'Total Premi',1,0,'L',1);
		$pdf->cell(21,6,duit($tPremi),1,0,'R',1);

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
		$pdf->cell(20,6,'ID Peserta',1,0,'C',1);
		$pdf->cell(65,6,'Nama',1,0,'C',1);
		$pdf->cell(18,6,'Tgl Lahir',1,0,'C',1);
		$pdf->cell(10,6,'Usia',1,0,'C',1);
		$pdf->cell(30,6,'Plafon',1,0,'C',1);
		$pdf->cell(30,6,'Tgl Akad',1,0,'C',1);
		$pdf->cell(10,6,'Tenor',1,0,'C',1);
		$pdf->cell(30,6,'Tgl Akhir',1,0,'C',1);
		$pdf->cell(20,6,'Rate',1,0,'C',1);
		$pdf->cell(30,6,'Nett Premi',1,0,'C',1);
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
		$pdf->cell(190,4,'Laporan Nota Debit',0,0,'C',0);$pdf->ln();

		$pdf->cell(190,4,$_SESSION['lprproduk'],0,0,'C',0);$pdf->ln();
		$pdf->cell(190,4,$_SESSION['lprstatus'],0,0,'C',0);$pdf->ln();
		$pdf->cell(190,4,$_SESSION['lprcabang'],0,0,'C',0);$pdf->ln();
		$pdf->cell(190,4,$_SESSION['lprperiode'],0,0,'C',0);$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(10);
		$pdf->cell(8,5,'No',1,0,'C',1);
		$pdf->cell(20,5,'Tgl DN',1,0,'C',1);
		$pdf->cell(30,5,'Nota Debet',1,0,'C',1);
		$pdf->cell(14,5,'Member',1,0,'C',1);
		$pdf->cell(15,5,'Status',1,0,'C',1);
		$pdf->cell(25,5,'Tgl Bayar',1,0,'C',1);
		$pdf->cell(30,5,'Premi',1,0,'C',1);
		$pdf->cell(50,5,'Cabang',1,0,'C',1);

		if (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="1") 		{	$_datapaid="Paid";
		}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="2")	{	$_datapaid="Paid*";
		}elseif (AES::decrypt128CBC($_REQUEST['st'], ENCRYPTION_KEY)=="")	{	$_datapaid="%";
		}else{	$_datapaid="Unpaid";	}
		if ($_REQUEST['st'])	{	$empat = 'AND ajkdebitnote.paidstatus like"'.$_datapaid.'"';	}


		$metCOB = mysql_query(AES::decrypt128CBC($_SESSION[$type], ENCRYPTION_KEY));
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
		if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
			$_tglbayar = '';
		}else{
			$_tglbayar = _convertDate($metCOB_['paidtanggal']);
		}
			$cell[$i][0] = $metCOB_['tgldebitnote'];
			$cell[$i][1] = substr($metCOB_['nomordebitnote'], 3);
			$cell[$i][2] = $metCOB_['jmember'];
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

	case "rptcnbatal":
		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		/*if ($_REQUEST['idb']) {
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);	}
		}else{*/
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);
				}
		/*if ($_REQUEST['idc']) {*/
			$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,240,7,45,15);	}
		/*}else{	}*/

		$pdf->ln(-12);
		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(270,4,'MEMBERSHIP DATA BATAL',0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprproduk'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprstatus'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprcabang'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprperiode'],0,0,'C',0);$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(5);

		$pdf->cell(8,5,'No',1,0,'C',1);
		$pdf->cell(28,5,'No Pinjaman',1,0,'C',1);
		$pdf->cell(25,5,'ID Peserta',1,0,'C',1);
		$pdf->cell(45,5,'Nama',1,0,'C',1);
		$pdf->cell(20,5,'Tgl Lahir',1,0,'C',1);
		$pdf->cell(8,5,'Usia',1,0,'C',1);
		$pdf->cell(20,5,'Plafon',1,0,'C',1);
		$pdf->cell(18,5,'Tgl Akad',1,0,'C',1);
		$pdf->cell(10,5,'Tenor',1,0,'C',1);
		$pdf->cell(18,5,'Tgl Akhir',1,0,'C',1);		
		$pdf->cell(45,5,'Cabang',1,0,'C',1);
		$pdf->cell(20,5,'Asuransi',1,0,'C',1);
		$pdf->cell(21,5,'Nett Premi',1,0,'C',1);


		$query = AES::decrypt128CBC($_SESSION[$type], ENCRYPTION_KEY);
		$metCOB = mysql_query($query);
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$cell[$i][0] = $metCOB_['nopinjaman'];
			$cell[$i][1] = $metCOB_['idpeserta'];
			$cell[$i][2] = $metCOB_['nama'];
			$cell[$i][3] = _convertDate($metCOB_['tgllahir']);
			$cell[$i][4] = $metCOB_['usia'];
			$cell[$i][5] = duit($metCOB_['plafond']);
			$cell[$i][6] = _convertDate($metCOB_['tglakad']);
			$cell[$i][7] = $metCOB_['tenor'];
			$cell[$i][8] = _convertDate($metCOB_['tglakhir']);			
			$cell[$i][9] = $metCOB_['cabang'];
			$cell[$i][10] = $metCOB_['asuransi'];
			$cell[$i][11] = duit($metCOB_['totalpremi']);
			$tPremi += $metCOB_['totalpremi'];
			$i++;
		}
		$pdf->Ln();

		for($j<1;$j<$i;$j++){	
			$pdf->setX(5);
			$pdf->cell(8,5,$j+1,1,0,'C');
			$pdf->cell(28,5,$cell[$j][0],1,0,'C');
			$pdf->cell(25,5,$cell[$j][1],1,0,'C');
			$pdf->cell(45,5,$cell[$j][2],1,0,'L');
			$pdf->cell(20,5,$cell[$j][3],1,0,'C');
			$pdf->cell(8,5,$cell[$j][4],1,0,'C');
			$pdf->cell(20,5,$cell[$j][5],1,0,'C');
			$pdf->cell(18,5,$cell[$j][6],1,0,'C');
			$pdf->cell(10,5,$cell[$j][7],1,0,'C');
			$pdf->cell(18,5,$cell[$j][8],1,0,'C');			
			$pdf->cell(45,5,$cell[$j][9],1,0,'C');
			$pdf->cell(20,5,$cell[$j][10],1,0,'C');
			$pdf->cell(21,5,$cell[$j][11],1,0,'R');
			$pdf->Ln();
		}
		$pdf->setX(5);
		$pdf->cell(265,6,'Total Premi',1,0,'L',1);
		$pdf->cell(21,6,duit($tPremi),1,0,'R',1);

		$pdf->Output("Report_Member_Batal.pdf","I");	
	break;

	case "rptcnrefund":
		$pdf=new FPDF('L','mm','A4');
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
									  FROM ajkcobroker
									  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
									  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
									  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		/*if ($_REQUEST['idb']) {
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);	}
		}else{*/
			$pathFile = '../'.$PathPhoto.''.$met_['logo'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,10,7,20,15);
				}
		/*if ($_REQUEST['idc']) {*/
			$pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
			if (file_exists($pathFile))	{	$metLogoBroker = $pathFile;	$pdf->Image($metLogoBroker,240,7,45,15);	}
		/*}else{	}*/

		$pdf->ln(-12);
		$pdf->SetFont('helvetica','B',10);
		$pdf->cell(270,4,'MEMBERSHIP DATA REFUND',0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprproduk'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprstatus'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprcabang'],0,0,'C',0);$pdf->ln();
		$pdf->cell(270,4,$_SESSION['lprperiode'],0,0,'C',0);$pdf->ln();

		$pdf->setFont('Arial','',9);
		$pdf->setFillColor(233,233,233);
		$y_axis1 = 30;
		$pdf->setY($y_axis1);
		$pdf->setX(5);

		$pdf->cell(8,5,'No',1,0,'C',1);
		$pdf->cell(28,5,'No Pinjaman',1,0,'C',1);
		$pdf->cell(25,5,'ID Peserta',1,0,'C',1);
		$pdf->cell(45,5,'Nama',1,0,'C',1);
		$pdf->cell(20,5,'Tgl Lahir',1,0,'C',1);
		$pdf->cell(8,5,'Usia',1,0,'C',1);
		$pdf->cell(20,5,'Tgl Akad',1,0,'C',1);
		$pdf->cell(10,5,'Tenor',1,0,'C',1);
		$pdf->cell(18,5,'Tgl Refund',1,0,'C',1);		
		$pdf->cell(20,5,'Plafon',1,0,'C',1);
		$pdf->cell(45,5,'Cabang',1,0,'C',1);
		$pdf->cell(20,5,'Asuransi',1,0,'C',1);
		$pdf->cell(21,5,'Nilai Refund',1,0,'C',1);
		/*FA		$pdf->cell(21,5,'Tgl Refund',1,0,'C',1);
				$pdf->cell(21,5,'Nilai Refund',1,0,'C',1);
		*/

		$query = AES::decrypt128CBC($_SESSION[$type], ENCRYPTION_KEY);
		$metCOB = mysql_query($query);
		
		while ($metCOB_ = mysql_fetch_array($metCOB)) {
			$cell[$i][0] = $metCOB_['nopinjaman'];
			$cell[$i][1] = $metCOB_['idpeserta'];
			$cell[$i][2] = $metCOB_['nama'];
			$cell[$i][3] = _convertDate($metCOB_['tgllahir']);
			$cell[$i][4] = $metCOB_['usia'];
			$cell[$i][5] = ($metCOB_['tglakad']);
			$cell[$i][6] = $metCOB_['tenor'];
			$cell[$i][7] = _convertDate($metCOB_['tglklaim']);
			$cell[$i][8] = duit($metCOB_['plafond']);		
			$cell[$i][9] = $metCOB_['cabang'];
			$cell[$i][10] = $metCOB_['asuransi'];
			$cell[$i][11] = duit($metCOB_['nilaiclaimclient']);
		/*FA			$cell[$i][12] = _convertDate($metCOB_['tglklaim']);
					$cell[$i][13] = duit($metCOB_['nilaiclaimclient']);
		*/			
			$tPremi += $metCOB_['nilaiclaimclient'];
			$tRefund += $metCOB_['nilaiclaimclient'];
			$i++;
		}
		$pdf->Ln();

		for($j<1;$j<$i;$j++){	
			$pdf->setX(5);
			$pdf->cell(8,5,$j+1,1,0,'C');
			$pdf->cell(28,5,$cell[$j][0],1,0,'C');
			$pdf->cell(25,5,$cell[$j][1],1,0,'C');
			$pdf->cell(45,5,$cell[$j][2],1,0,'L');
			$pdf->cell(20,5,$cell[$j][3],1,0,'C');
			$pdf->cell(8,5,$cell[$j][4],1,0,'C');
			$pdf->cell(20,5,$cell[$j][5],1,0,'C');
			$pdf->cell(10,5,$cell[$j][6],1,0,'C');
			$pdf->cell(18,5,$cell[$j][7],1,0,'C');
			$pdf->cell(20,5,$cell[$j][8],1,0,'C');			
			$pdf->cell(45,5,$cell[$j][9],1,0,'C');
			$pdf->cell(20,5,$cell[$j][10],1,0,'C');
			$pdf->cell(21,5,$cell[$j][11],1,0,'R');
		/*FA			$pdf->cell(20,5,$cell[$j][12],1,0,'C');
					$pdf->cell(21,5,$cell[$j][13],1,0,'R');
		*/
			$pdf->Ln();
		}
		$pdf->setX(5);
		$pdf->cell(267,6,'Total Refund',1,0,'L',1);
		$pdf->cell(21,6,duit($tPremi),1,0,'R',1);

		$pdf->Output("Report_Member_Refund.pdf","I");	
	break;

	default:
		$pdf=new FPDF('P','mm','A4');
		$pdf=new PDF_Code39();
		$pdf->Open();
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$metDN = mysql_fetch_array(mysql_query('SELECT ajkdebitnote.id,
											   ajkdebitnote.idbroker,
											   ajkdebitnote.idclient,
											   ajkdebitnote.idproduk,
											   ajkdebitnote.idcabang,
											   ajkcobroker.`name` AS brokername,
											   ajkcobroker.logo AS brokerlogo,
											   ajkclient.`name` AS clientname,
											   ajkpolis.produk,
											   ajkpolis.wpc,
											   ajkpolis.bankdebitnote,
											   ajkpolis.bankdebitnotecabang,
											   ajkpolis.bankdebitnoteaccount,
											   ajkcabang.`name` AS cabangname,
											   ajkdebitnote.nomordebitnote,
											   ajkdebitnote.tgldebitnote,
											   ajkdebitnote.paidstatus,
											   ajkdebitnote.premiclient,
											   COUNT(ajkpeserta.nama) AS jData,
											   SUM(ajkpeserta.premi) AS debpremi,
											   SUM(ajkpeserta.diskonpremi) AS debdiskon,
											   SUM(ajkpeserta.extrapremi) AS debem,
											   SUM(ajkpeserta.totalpremi) AS debtpremi
											   FROM ajkdebitnote
											   INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
											   INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
											   INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
											   INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
											   INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
											   WHERE ajkdebitnote.id = "'.AES::decrypt128CBC($_REQUEST['idd'], ENCRYPTION_KEY).'"
											   GROUP BY ajkdebitnote.id'));

		$pathFile = '../'.$PathPhoto.''.$metDN['brokerlogo'];
		if (file_exists($pathFile))
		{	$metLogoBroker = $pathFile;
			$pdf->Image($metLogoBroker,10,7,20,15);
		}

		$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,$metDN['brokername']);
		$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Broker Insurance');

		$pdf->SetFont('helvetica','B',12);
		$pdf->Text(85, 25,'INVOICE PREMIUM');

		//$pdf->Text(85, 35,$metDN['nomordebitnote']);
		$pdf->Code39(82, 26, $metDN['nomordebitnote']);

		$pdf->SetFont('helvetica','',9);
		$pdf->Text(15, 50,'Terima Dari');		$pdf->Text(50, 50,': '.$metDN['clientname'].' (Cabang : '.$metDN['cabangname'].')');
		$pdf->Text(15, 55,'Uang sejumlah');

		$pdf->MultiCell(0,32,'',0);
		$pdf->Cell(39,4,'',0,0,'L');
		$pdf->MultiCell(150,4,':'.mametbilang(duitterbilang($metDN['premiclient'])).'',0);

		$pdf->ln(); $pdf->cell(4,5,'',0,0,'L',0);
		$pdf->cell(35,5,'Nama Produk',0,0,'L',0);			$pdf->cell(80,5,': '.$metDN['produk'].'',0,0,'L',0);
		$pdf->cell(32,5,'Premi Pokok',0,0,'L',0);			$pdf->cell(10,5,': Rp',0,0,'L',0);	$pdf->cell(25,5,duit($metDN['debpremi']),0,0,'R',0);

		$pdf->ln(); $pdf->cell(4,5,'',0,0,'L',0);
		$pdf->cell(35,5,'Jumlah Debitur',0,0,'L',0);		$pdf->cell(80,5,': '.$metDN['jData'].' Debitur',0,0,'L',0);
		$pdf->cell(32,5,'Diskon',0,0,'L',0);				$pdf->cell(10,5,': Rp',0,0,'L',0);	$pdf->cell(25,5,duit($metDN['debdiskon']),0,0,'R',0);

		$pdf->ln(); $pdf->cell(4,5,'',0,0,'L',0);
		$pdf->cell(35,5,'Tanggal Debitnote',0,0,'L',0);		$pdf->cell(80,5,': '._convertDate($metDN['tgldebitnote']).'',0,0,'L',0);
		$pdf->cell(32,5,'Extrapremi',0,0,'L',0);			$pdf->cell(10,5,': Rp',0,0,'L',0);	$pdf->cell(25,5,duit($metDN['debem']),0,0,'R',0);

		$pdf->ln(); $pdf->cell(4,5,'',0,0,'L',0);
		$tanggalwpc=date('Y-m-d',strtotime($metDN['tgldebitnote']."+ ".$metDN['wpc']." day"));
		$pdf->cell(35,5,'Tanggal Jatuh Tempo',0,0,'L',0);	$pdf->cell(80,5,': '._convertDate($tanggalwpc).'',0,0,'L',0);
		$pdf->SetFont('helvetica','B',9);	$pdf->Line(197, 75,130, 75);
		$pdf->cell(32,5,'Total Premi',0,0,'L',0);			$pdf->cell(10,5,': Rp',0,0,'L',0);	$pdf->cell(25,5,duit($metDN['premiclient']),0,0,'R',0);

		$pdf->ln(10);
		$pdf->cell(10,4,'',0,0,'L',0);$pdf->SetFont('helvetica','',9);
		$tglIndo__ = explode(" ", $tglIndo);
		$metTglIndo = str_replace($_blnIndo,$_blnIndo_ , $tglIndo__[1]);
		$pdf->cell(109,4,'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'',0,0,'L',0);
		$pdf->cell(80,4,'Pembayaran dapat dilakukan pada account berikut :',0,0,'L',0);
		$pdf->ln();
		$pdf->cell(10,4,'',0,0,'L',0);$pdf->SetFont('helvetica','B',9);
		$pdf->cell(109,4,$metDN['brokername'],0,0,'L',0);
		$pdf->SetFont('helvetica','',9);
		$pdf->cell(30,4,'Nama Bank',0,0,'L',0);	$pdf->cell(80,4,$metDN['bankdebitnote'],0,0,'L',0);
		$pdf->ln();
		$pdf->cell(119,4,'',0,0,'L',0);
		$pdf->cell(30,4,'Nomor Rekening',0,0,'L',0);	$pdf->cell(80,4,$metDN['bankdebitnoteaccount'],0,0,'L',0);
		$pdf->ln();
		$pdf->cell(119,4,'',0,0,'L',0);
		$pdf->cell(30,4,'Cabang',0,0,'L',0);	$pdf->cell(80,4,$metDN['bankdebitnotecabang'],0,0,'L',0);

		$pdf->ln(15);
		$pdf->SetFont('helvetica','B',9);
		$pdf->cell(1,4,'',0,0,'L',0);
		$pdf->cell(80,4,'{debinotename}',0,0,'C',0);


		$pdf->Output($metDN['nomordebitnote'].".pdf","I");
	;
} // switch

?>