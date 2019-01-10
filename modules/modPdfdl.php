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
include_once('../includes/Encrypter.class.php');
$thisEncrypter = new textEncrypter();
include_once('../includes/functions.php');
include_once('../includes/code39.php');

switch ($_REQUEST['pdf']) {
  case "a":
      ;
  break;

  case "armpayment":
    $pdf=new FPDF('P', 'mm', 'A4');
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';
    }
    $met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';
    }

    $met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo AS brokerlogo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
							  FROM ajkcobroker
							  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
							  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
							  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));



    $pathFile = '../'.$PathPhoto.''.$met_['brokerlogo'];
    if (file_exists($pathFile)) {
        $metLogoBroker = $pathFile;
        $pdf->Image($metLogoBroker, 10, 7, 40, 10);
    }

    //$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,$met_['brokername']);
    //$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Broker Insurance');
    $pdf->SetFont('helvetica', 'B', 14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(90, 30, 'List Debitnote');

    $pdf->ln(15);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->cell(30, 5, 'Perusahaan', 0, 0, 'L', 0);	$pdf->cell(170, 5, ': '.$met_['clientname'], 0, 0, 'L', 0);	$pdf->cell(30, 5, 'Tanggal Debitnote', 0, 0, 'L', 0);		$pdf->cell(30, 5, ': '._convertDate($met_['tgldebitnote']), 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(30, 5, 'Produk', 0, 0, 'L', 0);	$pdf->cell(170, 5, ': '.$met_['produk'], 0, 0, 'L', 0);		$pdf->cell(30, 5, 'Debitnote', 0, 0, 'L', 0);				$pdf->cell(30, 5, ': '.$met_['nomordebitnote'], 0, 0, 'L', 0);	$pdf->ln();

    $pdf->setFont('Arial', '', 9);
    $pdf->setFillColor(233, 233, 233);
    $y_axis1 = 45;
    $pdf->setY($y_axis1);
    $pdf->setX(10);
    if ($thisEncrypter->decode($_REQUEST['st'])=="1") {
        $_datapaid="Paid";
    } elseif ($thisEncrypter->decode($_REQUEST['st'])=="2") {
        $_datapaid="Paid*";
    } else {
        $_datapaid="Unpaid";
    }

    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';
    }
    if ($thisEncrypter->decode($_REQUEST['st'])) {
        $empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';
    }
    $metListDN_ = mysql_query('SELECT
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

    $pdf->cell(8, 6, 'No', 1, 0, 'C', 1);
    $pdf->cell(40, 6, 'Debitnote', 1, 0, 'C', 1);
    $pdf->cell(18, 6, 'Date DN', 1, 0, 'C', 1);
    $pdf->cell(30, 6, 'Premium', 1, 0, 'C', 1);
    $pdf->cell(15, 6, 'Status', 1, 0, 'C', 1);
    $pdf->cell(22, 6, 'Date Payment', 1, 0, 'C', 1);
    $pdf->cell(55, 6, 'Cabang', 1, 0, 'C', 1);
    while ($_metListDN = mysql_fetch_array($metListDN_)) {
        $cell[$i][0] = $_metListDN['nomordebitnote'];
        $cell[$i][1] = _convertDate($_metListDN['tgldebitnote']);
        $cell[$i][2] = duit($_metListDN['premiclient']);
        $cell[$i][3] = $_metListDN['paidstatus'];
        $cell[$i][4] = _convertDate($_metListDN['paidtanggal']);
        $cell[$i][5] = $_metListDN['cabang'];
        $i++;
        $tTotalPremium += $_metListDN['premiclient'];
    }
    $pdf->Ln();

    for ($j<1;$j<$i;$j++) {
        $pdf->cell(8, 6, $j+1, 1, 0, 'C');
        $pdf->cell(40, 6, $cell[$j][0], 1, 0, 'C');
        $pdf->cell(18, 6, $cell[$j][1], 1, 0, 'L');
        $pdf->cell(30, 6, $cell[$j][2], 1, 0, 'R');
        $pdf->cell(15, 6, $cell[$j][3], 1, 0, 'C');
        $pdf->cell(22, 6, $cell[$j][4], 1, 0, 'C');
        $pdf->cell(55, 6, $cell[$j][5], 1, 0, 'L');
        $pdf->Ln();
    }

    $pdf->cell(66, 6, 'Total Premi', 1, 0, 'L', 1);
    $pdf->cell(30, 6, duit($tTotalPremium), 1, 0, 'R', 1);
    $pdf->cell(92, 6, ' ', 1, 0, 'L', 1);

    $pdf->Ln(10);
    $tglIndo__ = explode(" ", $tglIndo);
    $metTglIndo = str_replace($_blnIndo, $_blnIndo_, $tglIndo__[1]);
    $pdf->cell(140, 4, '', 0, 0, 'L', 0);
    $pdf->cell(48, 4, 'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'', 0, 0, 'C', 0);
    $pdf->Ln(20);
    $pdf->cell(140, 4, '', 0, 0, 'L', 0);
    $pdf->cell(48, 4, '( '.$thisEncrypter->decode($_REQUEST['Q']).' )', 0, 0, 'C', 0);

    $pdf->Output('PAYMENT_'.$_REQUEST['dtfrom'].'-'.$_REQUEST['dtto'].'.pdf', "I");
    ;
  break;

  case "dlPdfcn":
    $pdf=new FPDF('P', 'mm', 'A4');
    $pdf=new PDF_Code39();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    if ($_REQUEST['logCN']) {	// SET ID data debitnote dari FE
        $setIDCN = metDecrypt($_REQUEST["idc"]);
    } else {// SET ID data debitnote dari BE
        $setIDCN = $thisEncrypter->decode($_REQUEST['idc']);
    }
    $metCN = mysql_fetch_array(mysql_query('SELECT
    ajkcreditnote.id,
    ajkcobroker.`name` AS brokername,
    ajkclient.`name` AS clientname,
    ajkpeserta.idpeserta AS idpesertanya,
    ajkpeserta.nomorktp,
    ajkpeserta.nama,
    ajkpeserta.tgllahir,
    ajkpeserta.usia,
    ajkpeserta.plafond,
    ajkpeserta.tglakad,
    ajkpeserta.tenor,
    ajkpeserta.tglakhir,
    ajkpeserta.totalpremi,
    ajkpeserta.statusaktif,
    ajkcabang.`name` AS cabangname,
    ajkcreditnote.idbroker,
    ajkcreditnote.idclient,
    ajkcreditnote.idproduk,
    ajkcreditnote.idas,
    ajkcreditnote.idaspolis,
    ajkcreditnote.idpeserta,
    ajkcreditnote.iddn,
    ajkcreditnote.nomorcreditnote,
    ajkcreditnote.tglcreditnote,
    ajkcreditnote.tglklaim,
    ajkcreditnote.tglinvestigasi,
    ajkcreditnote.tglinvestigasiend,
    ajkcreditnote.nilaiclaimclient,
    ajkcreditnote.`status`,
    ajkcreditnote.tempatmeninggal,
    ajkcreditnote.penyebabmeninggal,
    ajkcreditnote.tglbayar,
    ajkcobroker.logo AS logobroker,
    ajkclient.logo AS logoclient,
    ajkpolis.produk
    FROM ajkcreditnote
    INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
    INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
    INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
    INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
    INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
    WHERE ajkcreditnote.id = "'.$setIDCN.'"'));

    $metdebitnote = mysql_fetch_array(mysql_query('SELECT id, nomordebitnote, tgldebitnote FROM ajkdebitnote WHERE id="'.$metCN['iddn'].'"'));
    $metCouses1 = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metCN['tempatmeninggal'].'" AND tipe="tempatmeninggal"'));
    $metCouses2 = mysql_fetch_array(mysql_query('SELECT * FROM ajkkejadianklaim WHERE id="'.$metCN['penyebabmeninggal'].'" AND tipe="penyebabmeninggal"'));

    if ($metCN['statusaktif']=="Claim") {
        $metTipeKlaim = 'Meninggal';
    } else {
        $metTipeKlaim = $metCN['statusaktif'];
    }
    $pathFile = '../'.$PathPhoto.''.$metCN['logobroker'];
    if (file_exists($pathFile)) {
        $metLogoBroker = $pathFile;
        $pdf->Image($metLogoBroker, 10, 7, 20, 20);
    }

    $pdf->SetFont('helvetica', 'B', 20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15, $metCN['brokername']);
    $pdf->SetFont('helvetica', '', 14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20, 'Broker Insurance');

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Text(86, 28, 'KWITANSI KLAIM');

    //$pdf->Text(85, 35,$metCN['nomordebitnote']);
    $pdf->Code39(82, 30, $metCN['nomorcreditnote']);


    $pdf->MultiCell(0, 25, '', 0);
    $pdf->Cell(39, 4, '', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->ln();
    $pdf->cell(35, 7, 'Perusahaan', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metCN['clientname'].'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Produk', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metCN['produk'].'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Nota Debit', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metdebitnote['nomordebitnote'].'', 0, 0, 'L', 0);						$pdf->cell(40, 7, 'Nota Kredit', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metCN['nomorcreditnote'].'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Tgl. Nota Debit', 0, 0, 'L', 0);		$pdf->cell(70, 7, ': '._convertDate($metdebitnote['tgldebitnote']).'', 0, 0, 'L', 0);			$pdf->cell(40, 7, 'Tgl. Nota Kredit', 0, 0, 'L', 0);			$pdf->cell(70, 7, ': '._convertDate($metCN['tglcreditnote']).'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'ID Peserta', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metCN['idpesertanya'].'', 0, 0, 'L', 0);									$pdf->cell(40, 7, 'Tempat Meninggal', 0, 0, 'L', 0);			$pdf->cell(70, 7, ': '.$metCouses1['nama'].'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Nama', 0, 0, 'L', 0);					$pdf->cell(70, 7, ': '.$metCN['nama'].'', 0, 0, 'L', 0);										$pdf->cell(40, 7, 'Penyebab Meninggal', 0, 0, 'L', 0);			$pdf->cell(70, 7, ': '.$metCouses2['nama'].'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Tgl. Lahir', 0, 0, 'L', 0);					$pdf->cell(70, 7, ': '._convertDate($metCN['tgllahir']).'', 0, 0, 'L', 0);					$pdf->cell(40, 7, 'Tgl. Klaim', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '.$metTipeKlaim.'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Usia', 0, 0, 'L', 0);					$pdf->cell(70, 7, ': '.$metCN['usia'].' tahun', 0, 0, 'L', 0);								$pdf->cell(40, 7, 'Tipe Klaim', 0, 0, 'L', 0);				$pdf->cell(70, 7, ': '._convertDate($metCN['tglklaim']).'', 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Plafond', 0, 0, 'L', 0);				$pdf->cell(10, 7, ': Rp', 0, 0, 'L', 0);	$pdf->cell(60, 7, duit($metCN['plafond']), 0, 0, 'L', 0);	$pdf->cell(40, 7, 'Status', 0, 0, 'L', 0);					$pdf->SetFont('helvetica', 'B', 12);	$pdf->cell(70, 7, ': '.$metCN['status'].'', 0, 0, 'L', 0);	$pdf->SetFont('helvetica', '', 12);	$pdf->ln();
    $pdf->cell(35, 7, 'Tenor', 0, 0, 'L', 0);					$pdf->cell(70, 7, ': '.$metCN['tenor'].' bulan', 0, 0, 'L', 0);								$pdf->cell(40, 7, 'Tgl. Investigasi', 0, 0, 'L', 0);		$pdf->cell(70, 7, ': '._convertDate($metCN['tglinvestigasi']).' to '._convertDate($metCN['tglinvestigasiend']), 0, 0, 'L', 0);	$pdf->ln();
    $pdf->cell(35, 7, 'Tgl. Akad', 0, 0, 'L', 0);			$pdf->cell(70, 7, ': '._convertDate($metCN['tglakad']).' to '._convertDate($metCN['tglakhir']).'', 0, 0, 'L', 0);	$pdf->cell(40, 7, 'Bayar Klaim', 0, 0, 'L', 0);	$pdf->SetFont('helvetica', 'B', 12);	$pdf->cell(70, 7, ': Rp. '.duit($metCN['nilaiclaimclient']).'', 0, 0, 'L', 0);	$pdf->SetFont('helvetica', '', 12);	$pdf->ln();
    $pdf->cell(35, 7, 'Premi', 0, 0, 'L', 0);				$pdf->cell(10, 7, ': Rp', 0, 0, 'L', 0);	$pdf->cell(60, 7, duit($metCN['totalpremi']), 0, 0, 'L', 0);					$pdf->cell(40, 7, 'Tgl. Bayar Klaim', 0, 0, 'L', 0);	$pdf->cell(70, 7, ': '._convertDate($metCN['tglbayar']).'', 0, 0, 'L', 0);	$pdf->ln();


    $pdf->AddPage();
    $pathFile = '../'.$PathPhoto.''.$metCN['logobroker'];
    if (file_exists($pathFile)) {
        $metLogoBroker = $pathFile;
        $pdf->Image($metLogoBroker, 10, 7, 20, 20);
    }
    $pdf->SetFont('helvetica', 'B', 20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15, $metCN['brokername']);
    $pdf->SetFont('helvetica', '', 14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20, 'Broker Insurance');
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Text(10, 30, 'DOKUMEN KLAIM');
    $pdf->ln(15);
    $pdf->cell(10, 6, 'No', 1, 0, 'C', 0);
    $pdf->cell(135, 6, 'Nama Dokumen', 1, 0, 'C', 0);
    $pdf->cell(22, 6, 'Ada', 1, 0, 'C', 0);
    $pdf->cell(22, 6, 'Tidak Ada', 1, 1, 'C', 0);
    $metDoc = mysql_query('SELECT ajkdocumentclaimmember.id AS iddokumenmember,
							  ajkdocumentclaimmember.iddoc AS iddokumenpartner,
							  ajkdocumentclaimmember.idmember,
							  ajkdocumentclaimmember.fileklaim,
							  ajkdocumentclaimpartner.iddoc AS iddokumen,
							  ajkdocumentclaim.namadokumen
						FROM ajkdocumentclaimmember
						INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
						INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
						WHERE ajkdocumentclaimmember.idmember = "'.$metCN['idpeserta'].'"');
    while ($metDoc_ = mysql_fetch_array($metDoc)) {
        if ($metDoc_['fileklaim']!=null) {
            $ketDoc1 = "v";
        } else {
            $ketDoc1 = "";
        }
        if ($metDoc_['fileklaim']==null) {
            $ketDoc2 = "x";
        } else {
            $ketDoc2 = "";
        }
        $pdf->cell(10, 6, ++$no, 1, 0, 'C', 0);
        $pdf->cell(135, 6, $metDoc_['namadokumen'], 1, 0, 'L', 0);
        $pdf->cell(22, 6, $ketDoc1, 1, 0, 'C', 0);
        $pdf->cell(22, 6, $ketDoc2, 1, 1, 'C', 0);
    }
    $pdf->Output($metCN['nomorcreditnote'].".pdf", "I");
  break;

  case "lprmember":
    $pdf=new FPDF('L', 'mm', 'A4');
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $tiga ='AND ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"';
    }

    $met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
							  FROM ajkcobroker
							  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
							  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
							  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $pathFile = '../'.$PathPhoto.''.$met_['logo'];
        if (file_exists($pathFile)) {
            $metLogoBroker = $pathFile;
            $pdf->Image($metLogoBroker, 10, 7, 20, 20);
        }
    } else {
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
        if (file_exists($pathFile)) {
            $metLogoBroker = $pathFile;
            $pdf->Image($metLogoBroker, 270, 7, 20, 15);
        }
    } else {
    }


    $pdf->ln(-12);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->cell(270, 4, 'MEMBERSHIP DATA REPORTS', 0, 0, 'C', 0);$pdf->ln();
    $pdf->cell(270, 4, $met_['brokername'], 0, 0, 'C', 0);$pdf->ln();
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $pdf->cell(270, 4, $met_['clientname'], 0, 0, 'C', 0);
        $pdf->ln();
    } else {
        $pdf->cell(270, 4, "ALL CLIENT", 0, 0, 'C', 0);
        $pdf->ln();
    }
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $pdf->cell(270, 4, $met_['produk'], 0, 0, 'C', 0);
        $pdf->ln();
    } else {
        $pdf->cell(270, 4, "ALL PRODUK", 0, 0, 'C', 0);
        $pdf->ln();
    }

    /*
        $pdf->cell(270,4,$met_['brokername'],0,0,'C',0);$pdf->ln();
        $pdf->cell(270,4,$met_['clientname'],0,0,'C',0);$pdf->ln();
        $pdf->cell(270,4,$met_['produk'],0,0,'C',0);$pdf->ln();
    */

    $pdf->cell(270, 4, $thisEncrypter->decode($_REQUEST['dtfrom']).' - '.$thisEncrypter->decode($_REQUEST['dtto']), 0, 0, 'C', 0);$pdf->ln();

    $pdf->setFont('Arial', '', 9);
    $pdf->setFillColor(233, 233, 233);
    $y_axis1 = 30;
    $pdf->setY($y_axis1);
    $pdf->setX(10);
    $pdf->cell(8, 5, 'No', 1, 0, 'C', 1);
    $pdf->cell(28, 5, 'Debitnote', 1, 0, 'C', 1);
    $pdf->cell(20, 5, 'Date DN', 1, 0, 'C', 1);
    $pdf->cell(20, 5, 'KTP', 1, 0, 'C', 1);
    $pdf->cell(16, 5, 'IDMember', 1, 0, 'C', 1);
    $pdf->cell(35, 5, 'Name', 1, 0, 'C', 1);
    $pdf->cell(17, 5, 'DOB', 1, 0, 'C', 1);
    $pdf->cell(8, 5, 'Usia', 1, 0, 'C', 1);
    $pdf->cell(20, 5, 'Plafond', 1, 0, 'C', 1);
    $pdf->cell(18, 5, 'Tanggal Mulai', 1, 0, 'C', 1);
    $pdf->cell(10, 5, 'Tenor', 1, 0, 'C', 1);
    $pdf->cell(18, 5, 'Tanggal Akhir', 1, 0, 'C', 1);
    $pdf->cell(13, 5, 'Rate', 1, 0, 'C', 1);
    $pdf->cell(21, 5, 'Premium', 1, 0, 'C', 1);
    $pdf->cell(30, 5, 'Cabang', 1, 0, 'C', 1);

    if ($thisEncrypter->decode($_REQUEST['st'])=="1") {
        $_statusaktif="Inforce";
    } elseif ($thisEncrypter->decode($_REQUEST['st'])=="2") {
        $_statusaktif="Lapse";
    } elseif ($thisEncrypter->decode($_REQUEST['st'])=="3") {
        $_statusaktif="Maturity";
    } else {
        $_statusaktif="Batal";
    }

    $met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';
    }
    if ($thisEncrypter->decode($_REQUEST['st'])) {
        $empat = 'AND ajkpeserta.statusaktif="'.strtoupper($_statusaktif).'"';
    }

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
    ajkpeserta.premirate,
    ajkpeserta.totalpremi,
    ajkpeserta.astotalpremi,
    ajkpeserta.statusaktif,
    ajkcabang.`name` AS cabang
    FROM ajkdebitnote
    INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
    INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
    WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkpeserta.del IS NULL AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"');
    while ($metCOB_ = mysql_fetch_array($metCOB)) {
        #			if ($met_['byrate']=="Age") {
        #				$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idpolis="'.$metCOB_['idproduk'].'" AND age="'.$metCOB_['usia'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
        #			}else{
        #				$metRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idpolis="'.$metCOB_['idproduk'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
        #			}
        #
        #			$metAsuransi = mysql_fetch_array(mysql_query('SELECT byrate FROM ajkpolisasuransi WHERE idbroker="'.$metCOB_['idbroker'].'" AND idcost="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND id="'.$metCOB_['idaspolis'].'"'));
        #			if ($metAsuransi['byrate']=="Age") {
        #				$metRateAs = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremiins WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND idpolis="'.$metCOB_['idaspolis'].'" AND age="'.$metCOB_['usia'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
        #			}else{
        #				$metRateAs = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremiins WHERE idbroker="'.$metCOB_['idbroker'].'" AND idclient="'.$metCOB_['idclient'].'" AND idproduk="'.$metCOB_['idproduk'].'" AND idas="'.$metCOB_['idas'].'" AND idpolis="'.$metCOB_['idaspolis'].'" AND '.$metCOB_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif"'));
        #			}

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
        $cell[$i][11] = $metCOB_['premirate'];
        $cell[$i][12] = duit($metCOB_['totalpremi']);
        $cell[$i][13] = $metCOB_['cabang'];
        $tPremi += $metCOB_['totalpremi'];
        $i++;
    }
    $pdf->Ln();
    for ($j<1;$j<$i;$j++) {
        $pdf->cell(8, 5, $j+1, 1, 0, 'C');
        $pdf->cell(28, 5, $cell[$j][0], 1, 0, 'C');
        $pdf->cell(20, 5, _convertDate($cell[$j][1]), 1, 0, 'C');
        $pdf->cell(20, 5, $cell[$j][2], 1, 0, 'C');
        $pdf->cell(16, 5, $cell[$j][3], 1, 0, 'C');
        $pdf->cell(35, 5, $cell[$j][4], 1, 0, 'L');
        $pdf->cell(17, 5, $cell[$j][5], 1, 0, 'C');
        $pdf->cell(8, 5, $cell[$j][6], 1, 0, 'C');
        $pdf->cell(20, 5, $cell[$j][7], 1, 0, 'C');
        $pdf->cell(18, 5, $cell[$j][8], 1, 0, 'C');
        $pdf->cell(10, 5, $cell[$j][9], 1, 0, 'C');
        $pdf->cell(18, 5, $cell[$j][10], 1, 0, 'C');
        $pdf->cell(13, 5, $cell[$j][11], 1, 0, 'C');
        $pdf->cell(21, 5, $cell[$j][12], 1, 0, 'R');
        $pdf->cell(30, 5, $cell[$j][13], 1, 0, 'C');
        $pdf->Ln();
    }

    $pdf->cell(231, 6, 'Total Premi', 1, 0, 'L', 1);
    $pdf->cell(21, 6, duit($tPremi), 1, 0, 'R', 1);
    $pdf->cell(30, 6, '', 1, 0, 'R', 1);

    $pdf->Output("Report_Member.pdf", "I");
    ;
  break;

  case "lprmemberIns":
    $pdf=new FPDF('L', 'mm', 'A3');
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    if ($thisEncrypter->decode($_REQUEST['idb'])) {
        $satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idc'])) {
        $dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['idp'])) {
        $tiga ='AND ajkpolis.id = "'.$thisEncrypter->decode($_REQUEST['idp']).'"';
    }
    if ($thisEncrypter->decode($_REQUEST['ida'])) {
        $empat ='AND ajkinsurance.id = "'.$thisEncrypter->decode($_REQUEST['ida']).'"';
    }

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


      if ($thisEncrypter->decode($_REQUEST['idb'])) {
          $pathFile = '../'.$PathPhoto.''.$met_['logo'];
          if (file_exists($pathFile)) {
              $metLogoBroker = $pathFile;
              $pdf->Image($metLogoBroker, 10, 7, 20, 20);
          }
      } else {
      }
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $pathFile = '../'.$PathPhoto.''.$met_['logoclient'];
          if (file_exists($pathFile)) {
              $metLogoBroker = $pathFile;
              $pdf->Image($metLogoBroker, 390, 7, 20, 15);
          }
      } else {
      }


      $pdf->ln(-12);
      $pdf->SetFont('helvetica', 'B', 10);
      $pdf->cell(390, 4, 'LAPORAN DATA DEBITUR ASURSANI '.$met_['insurancename'].'', 0, 0, 'C', 0);$pdf->ln();
      $pdf->cell(390, 4, $met_['brokername'], 0, 0, 'C', 0);$pdf->ln();
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $pdf->cell(390, 4, $met_['clientname'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(390, 4, "ALL CLIENT", 0, 0, 'C', 0);
          $pdf->ln();
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $pdf->cell(390, 4, $met_['produk'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(390, 4, "ALL PRODUK", 0, 0, 'C', 0);
          $pdf->ln();
      }
      //if ($thisEncrypter->decode($_REQUEST['ida'])) {	$pdf->cell(270,4,$met_['insurancename'],0,0,'C',0);	$pdf->ln();	}else{	$pdf->cell(270,4,"ALL INSURANCE",0,0,'C',0);$pdf->ln();	}

      $pdf->cell(390, 4, $thisEncrypter->decode($_REQUEST['dtfrom']).' - '.$thisEncrypter->decode($_REQUEST['dtto']), 0, 0, 'C', 0);$pdf->ln();

      $pdf->setFont('Arial', '', 9);
      $pdf->setFillColor(233, 233, 233);
      $y_axis1 = 30;
      $pdf->setY($y_axis1);
      $pdf->setX(10);
      $pdf->cell(8, 5, 'No', 1, 0, 'C', 1);
      $pdf->cell(45, 5, 'Asuransi', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Produk', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'ID Debitur', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Nama Debitur', 1, 0, 'C', 1);
      $pdf->cell(15, 5, 'Tgl Lahir', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Jenis Kelamin', 1, 0, 'C', 1);
      $pdf->cell(25, 5, 'Mulai Asuransi', 1, 0, 'C', 1);
      $pdf->cell(15, 5, 'JWP(bln)', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Plafond', 1, 0, 'C', 1);
      $pdf->cell(8, 5, 'Usia', 1, 0, 'C', 1);
      $pdf->cell(25, 5, 'Akhir Asuransi', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'Usia+JWP', 1, 0, 'C', 1);
      $pdf->cell(21, 5, 'Rate', 1, 0, 'C', 1);
      $pdf->cell(25, 5, 'Premi', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'Status', 1, 0, 'C', 1);
      $pdf->cell(35, 5, 'Cabang', 1, 0, 'C', 1);

      if ($thisEncrypter->decode($_REQUEST['st'])=="1") {
          $_statusaktif="Inforce";
      } elseif ($thisEncrypter->decode($_REQUEST['st'])=="2") {
          $_statusaktif="Lapse";
      } elseif ($thisEncrypter->decode($_REQUEST['st'])=="3") {
          $_statusaktif="Maturity";
      } else {
          $_statusaktif="Batal";
      }

      $met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
      if ($thisEncrypter->decode($_REQUEST['idb'])) {
          $satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';
      }
      if ($thisEncrypter->decode($_REQUEST['st'])) {
          $empat = 'AND ajkpeserta.statusaktif="'.strtoupper($_statusaktif).'"';
      }

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
      ajkpeserta.premirate,
      ajkpeserta.totalpremi,
      ajkpeserta.astotalpremi,
      ajkpeserta.statusaktif,
      ajkcabang.`name` AS cabang
      FROM ajkdebitnote
      INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
      INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
      WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkpeserta.del IS NULL AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"');
      while ($metCOB_ = mysql_fetch_array($metCOB)) {
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
          $cell[$i][11] = $metCOB_['premirate'];
          $cell[$i][12] = duit($metCOB_['totalpremi']);
          $cell[$i][13] = $metCOB_['cabang'];
          $tPremi += $metCOB_['totalpremi'];
          $i++;
      }
      $pdf->Ln();
      for ($j<1;$j<$i;$j++) {
          $pdf->cell(8, 5, $j+1, 1, 0, 'C');
          $pdf->cell(28, 5, $cell[$j][0], 1, 0, 'C');
          $pdf->cell(20, 5, _convertDate($cell[$j][1]), 1, 0, 'C');
          $pdf->cell(20, 5, $cell[$j][2], 1, 0, 'C');
          $pdf->cell(16, 5, $cell[$j][3], 1, 0, 'C');
          $pdf->cell(35, 5, $cell[$j][4], 1, 0, 'L');
          $pdf->cell(17, 5, $cell[$j][5], 1, 0, 'C');
          $pdf->cell(8, 5, $cell[$j][6], 1, 0, 'C');
          $pdf->cell(20, 5, $cell[$j][7], 1, 0, 'C');
          $pdf->cell(18, 5, $cell[$j][8], 1, 0, 'C');
          $pdf->cell(10, 5, $cell[$j][9], 1, 0, 'C');
          $pdf->cell(18, 5, $cell[$j][10], 1, 0, 'C');
          $pdf->cell(13, 5, $cell[$j][11], 1, 0, 'C');
          $pdf->cell(21, 5, $cell[$j][12], 1, 0, 'R');
          $pdf->cell(30, 5, $cell[$j][13], 1, 0, 'C');
          $pdf->Ln();
      }

      $pdf->cell(301, 6, 'Total Premi', 1, 0, 'L', 1);
      $pdf->cell(21, 6, '', 1, 0, 'R', 1);
      $pdf->cell(25, 6, duit($tPremi), 1, 0, 'R', 1);
      $pdf->cell(55, 6, '', 1, 0, 'R', 1);

      $pdf->Output("Report_Member.pdf", "I");
      ;
  break;

  case "member":
      $pdf=new FPDF('L', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      if ($_REQUEST['logMB']) {	// SET ID data debitnote dari FE
          $setID = metDecrypt($_REQUEST["pID"]);
      } else {// SET ID data debitnote dari BE
          $setID = $thisEncrypter->decode($_REQUEST['idd']);
      }
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
			ajkdebitnote.paidstatus,
			ajksignature.nama AS namesign,
			ajksignature.ttd,
			ajkcreditnote.id AS idcn,
      ajkinsurance.companyname as insname,
      ajkinsurance.logo as inslogo,
      ajkinsurance.pic as inspic
			FROM ajkdebitnote
			INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
			INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
			INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
			INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
      INNER JOIN ajkinsurance ON ajkinsurance.id = ajkdebitnote.idas
			LEFT JOIN ajkcreditnote ON ajkdebitnote.id = ajkcreditnote.iddn_exs
			INNER JOIN ajksignature ON ajkdebitnote.idbroker = ajksignature.idbroker AND ajkdebitnote.idclient = ajksignature.idpartner AND ajkdebitnote.idproduk = ajksignature.idproduk
			WHERE ajkdebitnote.id = "'.$setID.'" AND ajksignature.type = "BANKDN" AND ajksignature.`status` = "Aktif"'));


      // $pathFile = '../'.$PathPhoto.''.$metmember['inslogo'];
      if (file_exists($pathFile)) {
          $metLogoBroker = $pathFile;
          	$pdf->Image($metLogoBroker,10,7,50,15);
          // $pdf->Image($metLogoBroker, 10, 7, 20, 20);
      }

      // $pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,$metmember['brokername']);
      // $pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Broker Insurance');
      // $pdf->SetFont('helvetica', 'B', 14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(130, 30, 'Daftar Peserta Asuransi');
      $pdf->cell(277,5,'Daftar Peserta Asuransi',0,0,'C',0);

      $pdf->ln(15);
      $pdf->SetFont('helvetica', '', 10);
      $pdf->cell(30, 5, 'Perusahaan', 0, 0, 'L', 0);	$pdf->cell(170, 5, ': '.$metmember['clientname'], 0, 0, 'L', 0);	$pdf->cell(30, 5, 'Tanggal Debitnote', 0, 0, 'L', 0);		$pdf->cell(30, 5, ': '._convertDate($metmember['tgldebitnote']), 0, 0, 'L', 0);	$pdf->ln();
      $pdf->cell(30, 5, 'Produk', 0, 0, 'L', 0);	$pdf->cell(170, 5, ': '.$metmember['produk'], 0, 0, 'L', 0);		$pdf->cell(30, 5, 'Nota Debit', 0, 0, 'L', 0);				$pdf->cell(30, 5, ': '.$metmember['nomordebitnote'], 0, 0, 'L', 0);	$pdf->ln();
      $pdf->cell(30, 5, 'Cabang', 0, 0, 'L', 0);	$pdf->cell(170, 5, ': '.$metmember['cabang'], 0, 0, 'L', 0);		$pdf->cell(30, 5, 'Status', 0, 0, 'L', 0);				$pdf->cell(30, 5, ': '.$metmember['paidstatus'], 0, 0, 'L', 0);	$pdf->ln();

      $pdf->setFont('Arial', '', 9);
      $pdf->setFillColor(233, 233, 233);
      $y_axis1 = 55;
      $pdf->setY($y_axis1);
      $pdf->setX(10);

      $metListmember_ = mysql_query('SELECT * FROM ajkpeserta INNER JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi WHERE iddn="'.$metmember['id'].'" AND ajkpeserta.del IS NULL');

      $pdf->cell(8, 6, 'No', 1, 0, 'C', 1);
      $pdf->cell(35, 6, 'No Pinjaman', 1, 0, 'C', 1);
      $pdf->cell(20, 6, 'Id Peserta', 1, 0, 'C', 1);
      $pdf->cell(65, 6, 'Nama', 1, 0, 'C', 1);
      $pdf->cell(18, 6, 'Tgl. Lahir', 1, 0, 'C', 1);
      $pdf->cell(10, 6, 'Usia', 1, 0, 'C', 1);
      $pdf->cell(30, 6, 'Plafond', 1, 0, 'C', 1);
      $pdf->cell(25, 6, 'Tgl. Mulai', 1, 0, 'C', 1);
      $pdf->cell(10, 6, 'Tenor', 1, 0, 'C', 1);
      $pdf->cell(25, 6, 'Tgl. Akhir', 1, 0, 'C', 1);
      $pdf->cell(30, 6, 'Premi', 1, 0, 'C', 1);
      while ($_metListmember = mysql_fetch_array($metListmember_)) {
          $cell[$i][0] = $_metListmember['idpeserta'];
          $cell[$i][1] = $_metListmember['nama'];
          $cell[$i][2] = _convertDate($_metListmember['tgllahir']);
          $cell[$i][3] = $_metListmember['usia'];
          $cell[$i][4] = duit($_metListmember['plafond']);
          $cell[$i][5] = _convertDate($_metListmember['tglakad']);
          $cell[$i][6] = $_metListmember['tenor'];
          $cell[$i][7] = _convertDate($_metListmember['tglakhir']);
          $cell[$i][9] = duit($_metListmember['totalpremi']);
          $cell[$i][10] = $_metListmember['nopinjaman'];
          $i++;
          $tTotalPeserta += $_metListmember['nama'];
          $tTotalPremi += $_metListmember['totalpremi'];
      }
      $pdf->Ln();

      for ($j<1;$j<$i;$j++) {
          $pdf->cell(8, 6, $j+1, 1, 0, 'C');
          $pdf->cell(35, 6, $cell[$j][10], 1, 0, 'C');
          $pdf->cell(20, 6, $cell[$j][0], 1, 0, 'C');
          $pdf->cell(65, 6, $cell[$j][1], 1, 0, 'L');
          $pdf->cell(18, 6, $cell[$j][2], 1, 0, 'C');
          $pdf->cell(10, 6, $cell[$j][3], 1, 0, 'C');
          $pdf->cell(30, 6, $cell[$j][4], 1, 0, 'R');
          $pdf->cell(25, 6, $cell[$j][5], 1, 0, 'C');
          $pdf->cell(10, 6, $cell[$j][6], 1, 0, 'C');
          $pdf->cell(25, 6, $cell[$j][7], 1, 0, 'C');
          $pdf->cell(30, 6, $cell[$j][9], 1, 0, 'R');
          $pdf->Ln();
      }

      $pdf->cell(246, 6, 'Total Premi', 1, 0, 'L', 1);
      $pdf->cell(30, 6, duit($tTotalPremi), 1, 0, 'R', 1);


      /* 09112016
         $pdf->Ln();
         $pdf->setFont('Arial','',9);
         $pdf->MultiCell(200,6,'Catatan :',0,'L');//	$pdf->cell(75,6,'Jakarta, '.$futgl.'',0,0,'L');$pdf->Ln();
         $pdf->MultiCell(200,6,'Bukti Konfirmasi ini merupakan dokumen elektronik, sehingga cukup menggunakan cap dan tanda tangan elektronik.',0,'L');//	$pdf->cell(75,6,$met_asuransi['name'],0,0,'L');		$pdf->Ln();

         $tglIndo__ = explode(" ", $tglIndo);
         $metTglIndo = str_replace($_blnIndo,$_blnIndo_ , $tglIndo__[1]);
         $pdf->cell(232,4,'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'',0,0,'R',0);

         $pdf->ln();
         $pdf->cell(190,4,'',0,0,'L',0);$pdf->SetFont('helvetica','B',9);
         $pdf->cell(10,6,$metmember['brokername'],0,0,'L',0);

         $pdf->Ln(10);
         $pdf->Image('../'.$PathSignature.$metmember['ttd'],190);
         $pdf->SetFont('helvetica','B',9);
         $pdf->cell(190,6,' ', 0, 0, 'R');
         $pdf->cell(10,6,$metmember['namesign'], 0, 0, 'L');
      */

      //CEK DATA BATAL ATAU REFUND
      if ($metmember['idcn']) {
          $pdf->AddPage();
          $pathFile = '../'.$PathPhoto.''.$metmember['brokerlogo'];
          if (file_exists($pathFile)) {
              $metLogoBroker = $pathFile;
              $pdf->Image($metLogoBroker, 10, 7, 20, 20);
          }

          $pdf->SetFont('helvetica', 'B', 14);
          $pdf->SetTextColor(0, 0, 0);
          $pdf->Text(130, 30, 'Daftar Peserta Asuransi');

          $pdf->ln(15);
          $pdf->SetFont('helvetica', '', 10);
          $pdf->cell(30, 5, 'Perusahaan', 0, 0, 'L', 0);
          $pdf->cell(170, 5, ': '.$metmember['clientname'], 0, 0, 'L', 0);
          $pdf->cell(30, 5, 'Tanggal Debitnote', 0, 0, 'L', 0);
          $pdf->cell(30, 5, ': '._convertDate($metmember['tgldebitnote']), 0, 0, 'L', 0);
          $pdf->ln();
          $pdf->cell(30, 5, 'Produk', 0, 0, 'L', 0);
          $pdf->cell(170, 5, ': '.$metmember['produk'], 0, 0, 'L', 0);
          $pdf->cell(30, 5, 'Debitnote', 0, 0, 'L', 0);
          $pdf->cell(30, 5, ': '.$metmember['nomordebitnote'], 0, 0, 'L', 0);
          $pdf->ln();
          $pdf->cell(30, 5, 'Cabang', 0, 0, 'L', 0);
          $pdf->cell(170, 5, ': '.$metmember['cabang'], 0, 0, 'L', 0);		/*$pdf->cell(30,5,'Status',0,0,'L',0);				$pdf->cell(30,5,': '.$metmember['paidstatus'],0,0,'L',0); */	$pdf->ln();
          $pdf->setFont('Arial', '', 9);
          $pdf->setFillColor(233, 233, 233);
          $y_axis1 = 55;
          $pdf->setY($y_axis1);
          $pdf->setX(10);

          $metListmember_ = mysql_query('SELECT * FROM ajkpeserta WHERE iddn="'.$metmember['id'].'" AND del IS NULL');

          $pdf->cell(8, 6, 'No', 1, 0, 'C', 1);
          $pdf->cell(20, 6, 'ID Peserta', 1, 0, 'C', 1);
          $pdf->cell(65, 6, 'Name', 1, 0, 'C', 1);
          $pdf->cell(30, 6, 'Plafond', 1, 0, 'C', 1);
          $pdf->cell(20, 6, 'Tanggal Mulai', 1, 0, 'C', 1);
          $pdf->cell(10, 6, 'Tenor', 1, 0, 'C', 1);
          $pdf->cell(20, 6, 'Tanggal Akhir', 1, 0, 'C', 1);
          $pdf->cell(35, 6, 'Creditnote', 1, 0, 'C', 1);
          $pdf->cell(20, 6, 'Date Claim', 1, 0, 'C', 1);
          $pdf->cell(20, 6, 'Type', 1, 0, 'C', 1);
          $pdf->cell(30, 6, 'Premium', 1, 0, 'C', 1);
          $metDataCN = mysql_query('SELECT ajkpeserta.idpeserta,
								 ajkpeserta.nama,
								 ajkpeserta.plafond,
								 ajkpeserta.tglakad,
								 ajkpeserta.tenor,
								 ajkpeserta.tglakhir,
								 ajkcreditnote.nomorcreditnote,
								 ajkcreditnote.tglcreditnote,
								 ajkcreditnote.tglklaim,
								 ajkcreditnote.tipeklaim,
								 ajkcreditnote.nilaiclaimclient,
								 ajkcreditnote.nilaiclaimasuransi
						FROM ajkcreditnote
						INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
						WHERE ajkcreditnote.iddn_exs = "'.$metmember['id'].'" AND ajkcreditnote.nomorcreditnote !=""');
          while ($metDataCN_ = mysql_fetch_array($metDataCN)) {
              $cell[$i][0] = $metDataCN_['idpeserta'];
              $cell[$i][1] = $metDataCN_['nama'];
              $cell[$i][2] = duit($metDataCN_['plafond']);
              $cell[$i][3] = _convertDate($metDataCN_['tglakad']);
              $cell[$i][4] = $metDataCN_['tenor'];
              $cell[$i][5] = _convertDate($metDataCN_['tglakhir']);
              $cell[$i][6] = $metDataCN_['nomorcreditnote'];
              $cell[$i][7] = $metDataCN_['tglklaim'];
              $cell[$i][8] = $metDataCN_['tipeklaim'];
              $cell[$i][9] = duit($metDataCN_['nilaiclaimclient']);
              $i++;
              $tTotalPesertaCN += $metDataCN_['nama'];
              $tTotalPremiCN += $metDataCN_['nilaiclaimclient'];
          }
          $pdf->Ln();

          for ($j<1;$j<$i;$j++) {
              $pdf->cell(8, 6, $j-$j+1, 1, 0, 'C');
              $pdf->cell(20, 6, $cell[$j][0], 1, 0, 'C');
              $pdf->cell(65, 6, $cell[$j][1], 1, 0, 'L');
              $pdf->cell(30, 6, $cell[$j][2], 1, 0, 'C');
              $pdf->cell(20, 6, $cell[$j][3], 1, 0, 'C');
              $pdf->cell(10, 6, $cell[$j][4], 1, 0, 'R');
              $pdf->cell(20, 6, $cell[$j][5], 1, 0, 'C');
              $pdf->cell(35, 6, $cell[$j][6], 1, 0, 'C');
              $pdf->cell(20, 6, $cell[$j][7], 1, 0, 'C');
              $pdf->cell(20, 6, $cell[$j][8], 1, 0, 'C');
              $pdf->cell(30, 6, $cell[$j][9], 1, 0, 'R');
              $pdf->Ln();
          }
          $pdf->cell(248, 6, 'Total Nilai Creditnote', 1, 0, 'L', 1);
          $pdf->cell(30, 6, duit($tTotalPremiCN), 1, 0, 'R', 1);
          $pdf->Ln();
          $pdf->setFont('Arial', 'B', 9);
          $metGrandtotal = $tTotalPremi - $tTotalPremiCN;
          $pdf->MultiCell(200, 6, 'Grand Total Premium = '.duit($metGrandtotal), 0, 'L');//	$pdf->cell(75,6,'Jakarta, '.$futgl.'',0,0,'L');$pdf->Ln();
      }
      //CEK DATA BATAL ATAU REFUND

      $pdf->Ln();
      $pdf->setFont('Arial', '', 9);
      $pdf->MultiCell(200, 6, 'Catatan :', 0, 'L');//	$pdf->cell(75,6,'Jakarta, '.$futgl.'',0,0,'L');$pdf->Ln();
      $pdf->MultiCell(200, 6, 'Bukti Konfirmasi ini merupakan dokumen elektronik, sehingga cukup menggunakan cap dan tanda tangan elektronik.', 0, 'L');//	$pdf->cell(75,6,$met_asuransi['name'],0,0,'L');		$pdf->Ln();

      $tglIndo__ = explode(" ", $tglIndo);
      $metTglIndo = str_replace($_blnIndo, $_blnIndo_, $tglIndo__[1]);
      $pdf->cell(232, 4, 'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'', 0, 0, 'R', 0);

      $pdf->ln();
      $pdf->cell(190, 4, '', 0, 0, 'L', 0);$pdf->SetFont('helvetica', 'B', 9);
      $pdf->cell(50, 6, $metmember['insname'], 0, 0, 'C', 0);

      $pdf->Ln(10);
      //$pdf->Image('../'.$PathSignature.$metmember['ttd'],190);
      // $pdf->Image('../'.$PathSignature.$metmember['ttd'], 190);
      $pdf->SetFont('helvetica', 'B', 9);
      $pdf->cell(190, 6, ' ', 0, 0, 'R');
      // $pdf->cell(10, 6, $metmember['namesign'], 0, 0, 'L');

      $pdf->Output('MEMBER_'.$metmember['nomordebitnote'].".pdf", "I");
      ;
  break;

  case "rptdebitnote":

      /*
         echo $thisEncrypter->decode($_REQUEST['idb']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['idc']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['idp']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['dtfrom']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['dtto']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['st']).'<br />';
      */

      $pdf=new FPDF('P', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();

      $met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
      if ($thisEncrypter->decode($_REQUEST['idb'])) {
          $satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';
      }

      $met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
								  FROM ajkcobroker
								  LEFT JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
								  LEFT JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
								  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));




      $pdf->ln(-12);
      $pdf->SetFont('helvetica', 'B', 10);
      $pdf->cell(190, 4, 'REPORT DEBITNOTE', 0, 0, 'C', 0);$pdf->ln();
      $pdf->cell(190, 4, $met_['brokername'], 0, 0, 'C', 0);$pdf->ln();
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $pdf->cell(190, 4, $met_['clientname'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(190, 4, "ALL CLIENT", 0, 0, 'C', 0);
          $pdf->ln();
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $pdf->cell(190, 4, $met_['produk'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(190, 4, "ALL PRODUK", 0, 0, 'C', 0);
          $pdf->ln();
      }

      /*
              $pdf->cell(190,4,$met_['brokername'],0,0,'C',0);$pdf->ln();
              $pdf->cell(190,4,$met_['clientname'],0,0,'C',0);$pdf->ln();
              $pdf->cell(190,4,$met_['produk'],0,0,'C',0);$pdf->ln();
      */



      if ($_REQUEST['dtfrom'] or $_REQUEST['dtto']) {
          $pdf->cell(190, 4, $thisEncrypter->decode($_REQUEST['dtfrom']).' - '.$thisEncrypter->decode($_REQUEST['dtto']), 0, 0, 'C', 0);
          $pdf->ln();
      } else {
      }

      $pdf->setFont('Arial', '', 9);
      $pdf->setFillColor(233, 233, 233);
      $y_axis1 = 30;
      $pdf->setY($y_axis1);
      $pdf->setX(10);
      $pdf->cell(8, 5, 'No', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'Date DN', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Debitnote', 1, 0, 'C', 1);
      $pdf->cell(14, 5, 'Member', 1, 0, 'C', 1);
      $pdf->cell(15, 5, 'Status', 1, 0, 'C', 1);
      $pdf->cell(25, 5, 'Date Payment', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Premium', 1, 0, 'C', 1);
      $pdf->cell(50, 5, 'Cabang', 1, 0, 'C', 1);

      if ($thisEncrypter->decode($_REQUEST['idb'])) {
          $satu = 'AND ajkdebitnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $dua = 'AND ajkdebitnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $tiga = 'AND ajkdebitnote.idproduk="'.$met_idproduk[0].'"';
      }

      if ($thisEncrypter->decode($_REQUEST['st'])=="1") {
          $_datapaid="Paid";
      } elseif ($thisEncrypter->decode($_REQUEST['st'])=="2") {
          $_datapaid="Paid*";
      } else {
          $_datapaid="Unpaid";
      }
      if ($thisEncrypter->decode($_REQUEST['st'])) {
          $empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';
      }

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
      WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"
      GROUP BY ajkdebitnote.id');
      while ($metCOB_ = mysql_fetch_array($metCOB)) {
          if ($metCOB_['paidtanggal']=="" or $metCOB_['paidtanggal']=="0000-00-00") {
              $_tglbayar = '';
          } else {
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
      for ($j<1;$j<$i;$j++) {
          $pdf->cell(8, 5, $j+1, 1, 0, 'C');
          $pdf->cell(20, 5, _convertDate($cell[$j][0]), 1, 0, 'C');
          $pdf->cell(30, 5, $cell[$j][1], 1, 0, 'C');
          $pdf->cell(14, 5, $cell[$j][2], 1, 0, 'C');
          $pdf->cell(15, 5, $cell[$j][3], 1, 0, 'C');
          $pdf->cell(25, 5, $cell[$j][4], 1, 0, 'C');
          $pdf->cell(30, 5, $cell[$j][5], 1, 0, 'R');
          $pdf->cell(50, 5, $cell[$j][6], 1, 0, 'L');
          $pdf->Ln();
      }

      $pdf->SetFont('helvetica', 'B', 10);
      $pdf->cell(112, 6, 'Total Premi', 1, 0, 'L', 1);
      $pdf->cell(30, 6, duit($tPremi), 1, 0, 'R', 1);
      $pdf->cell(50, 6, '', 1, 0, 'R', 1);

      $pdf->Output("Report_Debitnote.pdf", "I");
      ;
  break;

  case "rptcreditnote":
      /*
         echo $thisEncrypter->decode($_REQUEST['idb']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['idc']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['idp']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['dtfrom']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['dtto']).'<br />';
         echo $thisEncrypter->decode($_REQUEST['st']).'<br />';
      */

      $pdf=new FPDF('L', 'mm', 'A4');
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();

      if ($_REQUEST['idb']) {
          $satu ='AND ajkcobroker.id = "'.$thisEncrypter->decode($_REQUEST['idb']).'"';
      }
      if ($_REQUEST['idc']) {
          $dua ='AND ajkclient.id = "'.$thisEncrypter->decode($_REQUEST['idc']).'"';
      }
      if ($_REQUEST['idp']) {
          $met_idproduk = explode("_", $thisEncrypter->decode($_REQUEST['idp']));
          $tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';
      }

      $met_ = mysql_fetch_array(mysql_query('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual, ajkpolis.byrate
								  FROM ajkcobroker
								  LEFT JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
								  LEFT JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
								  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));




      $pdf->ln(-12);
      $pdf->SetFont('helvetica', 'B', 10);
      $pdf->cell(275, 4, 'REPORT CREDITNOTE', 0, 0, 'C', 0);$pdf->ln();
      $pdf->cell(275, 4, $met_['brokername'], 0, 0, 'C', 0);$pdf->ln();
      if ($thisEncrypter->decode($_REQUEST['idc'])) {
          $pdf->cell(275, 4, $met_['clientname'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(275, 4, "ALL CLIENT", 0, 0, 'C', 0);
          $pdf->ln();
      }
      if ($thisEncrypter->decode($_REQUEST['idp'])) {
          $pdf->cell(275, 4, $met_['produk'], 0, 0, 'C', 0);
          $pdf->ln();
      } else {
          $pdf->cell(275, 4, "ALL PRODUK", 0, 0, 'C', 0);
          $pdf->ln();
      }

      if ($_REQUEST['dtfrom'] or $_REQUEST['dtto']) {
          $pdf->cell(275, 4, _convertDate(_convertDate2($thisEncrypter->decode($_REQUEST['dtfrom']))).' - '._convertDate(_convertDate2($thisEncrypter->decode($_REQUEST['dtto']))), 0, 0, 'C', 0);
          $pdf->ln();
      } else {
      }

      $pdf->setFont('Arial', '', 9);
      $pdf->setFillColor(233, 233, 233);
      $y_axis1 = 30;
      $pdf->setY($y_axis1);
      $pdf->setX(10);
      $pdf->cell(8, 5, 'No', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Creditnote', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Debitnote', 1, 0, 'C', 1);
      $pdf->cell(50, 5, 'Name', 1, 0, 'C', 1);
      $pdf->cell(30, 5, 'Value Claim', 1, 0, 'C', 1);
      $pdf->cell(35, 5, 'Status', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'Type Data', 1, 0, 'C', 1);
      $pdf->cell(20, 5, 'Date Claim', 1, 0, 'C', 1);
      $pdf->cell(50, 5, 'Cabang', 1, 0, 'C', 1);

      if ($_REQUEST['idb']) {
          $satu = 'AND ajkcreditnote.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'"';
      }
      if ($_REQUEST['idc']) {
          $dua = 'AND ajkcreditnote.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'"';
      }
      if ($_REQUEST['idp']) {
          $tiga = 'AND ajkcreditnote.idproduk="'.$met_idproduk[0].'"';
      }
      if ($_REQUEST['st']) {
          $empat = 'AND ajkcreditnote.tipeklaim="'.$thisEncrypter->decode($_REQUEST['st']).'"';
      }

      $metCOB = mysql_query('SELECT
      ajkcreditnote.id,
      ajkcreditnote.idbroker,
      ajkcreditnote.idclient,
      ajkcreditnote.idproduk,
      ajkpeserta.idpeserta,
      ajkpeserta.nama,
      ajkpeserta.tgllahir,
      ajkpeserta.usia,
      ajkpeserta.plafond,
      ajkpeserta.tglakad,
      ajkpeserta.tenor,
      ajkpeserta.tglakhir,
      ajkpeserta.totalpremi,
      ajkcabang.`name`AS nmcabang,
      ajkdebitnote.nomordebitnote,
      ajkcreditnote.tglcreditnote,
      ajkcreditnote.tglklaim,
      ajkcreditnote.nilaiklaimdiajukan,
      ajkcreditnote.nilaiclaimclient,
      ajkcreditnote.nilaiclaimasuransi,
      ajkcreditnote.nomorcreditnote,
      ajkcreditnote.status,
      ajkcreditnote.tipeklaim
      FROM ajkcreditnote
      INNER JOIN ajkpeserta ON ajkcreditnote.id = ajkpeserta.idcn
      INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
      INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
      WHERE ajkcreditnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkcreditnote.tglcreditnote BETWEEN "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtfrom'])).'" AND "'._convertDateEng2($thisEncrypter->decode($_REQUEST['dtto'])).'"
      GROUP BY ajkcreditnote.id');
      while ($metCOB_ = mysql_fetch_array($metCOB)) {
          $cell[$i][0] = substr($metCOB_['nomorcreditnote'], 3);
          $cell[$i][1] = substr($metCOB_['nomordebitnote'], 3);
          $cell[$i][2] = $metCOB_['nama'];
          $cell[$i][3] = duit($metCOB_['nilaiclaimclient']);
          $cell[$i][4] = $metCOB_['status'];
          $cell[$i][5] = $metCOB_['tipeklaim'];
          $cell[$i][6] = _convertDate($metCOB_['tglklaim']);
          $cell[$i][7] = $metCOB_['nmcabang'];
          $tClaimValue += $metCOB_['nilaiclaimclient'];
          $i++;
      }
      $pdf->Ln();
      for ($j<1;$j<$i;$j++) {
          $pdf->cell(8, 5, $j+1, 1, 0, 'C');
          $pdf->cell(30, 5, $cell[$j][0], 1, 0, 'C');
          $pdf->cell(30, 5, $cell[$j][1], 1, 0, 'C');
          $pdf->cell(50, 5, $cell[$j][2], 1, 0, 'L');
          $pdf->cell(30, 5, $cell[$j][3], 1, 0, 'R');
          $pdf->cell(35, 5, $cell[$j][4], 1, 0, 'C');
          $pdf->cell(20, 5, $cell[$j][5], 1, 0, 'C');
          $pdf->cell(20, 5, $cell[$j][6], 1, 0, 'C');
          $pdf->cell(50, 5, $cell[$j][7], 1, 0, 'L');
          $pdf->Ln();
      }

      $pdf->SetFont('helvetica', 'B', 10);
      $pdf->cell(118, 6, 'Total Premi', 1, 0, 'L', 1);
      $pdf->cell(30, 6, duit($tClaimValue), 1, 0, 'R', 1);
      $pdf->cell(125, 6, '', 1, 0, 'R', 1);

      $pdf->Output("Report_Creditnote.pdf", "I");
      ;
  break;

  default:
      $pdf=new FPDF('P', 'mm', 'A4');
      $pdf=new PDF_Code39();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      if ($_REQUEST['logDN']) {	// SET ID data debitnote dari FE
          $setID = metDecrypt($_REQUEST["pID"]);
          
      } else {// SET ID data debitnote dari BE
          $setID = $thisEncrypter->decode($_REQUEST['idd']);
      }

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
										   ajkpolis.bankdebitnotenama,
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
										   SUM(ajkpeserta.totalpremi) AS debtpremi,
										   ajksignature.nama AS namesign,
										   ajksignature.ttd,
										   ajkutilities.apl,
										   ajkutilities.posisi,
										   ajkutilities.type,
										   ajkutilities.logo AS logohead,
										   ajkutilities.logoposisix,
										   ajkutilities.logoposisiy,
										   ajkutilities.nama1,
										   ajkutilities.nama2,
										   ajkutilities.status
										   FROM ajkdebitnote
										   INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
										   INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
										   INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
										   INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
										   INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
										   INNER JOIN ajksignature ON ajkdebitnote.idbroker = ajksignature.idbroker AND ajkdebitnote.idclient = ajksignature.idpartner AND ajkdebitnote.idproduk = ajksignature.idproduk
										   LEFT JOIN ajkutilities ON ajkdebitnote.idbroker = ajkutilities.idbroker
										   WHERE ajkdebitnote.id = "'.$setID.'" AND ajksignature.type = "BANKKUITANSI" AND ajksignature.`status` = "Aktif"
										   GROUP BY ajkdebitnote.id'));

      $cekNilaiCN = mysql_fetch_array(mysql_query('SELECT ajkcreditnote.iddn,
														ajkcreditnote.nomorcreditnote,
														ajkcreditnote.tglcreditnote,
														ajkcreditnote.tglklaim,
														ajkcreditnote.tipeklaim,
														SUM(ajkcreditnote.nilaiclaimclient) AS nilaiclaimclient,
														SUM(ajkcreditnote.nilaiclaimasuransi) AS nilaiclaimasuransi
												FROM ajkcreditnote
												WHERE ajkcreditnote.iddn_exs = "'.$setID.'"
												GROUP BY ajkcreditnote.iddn'));

  if ($_REQUEST['mark']=="none") {	/*$pdf->ln(25);*/
  } else {
      if ($metDN['logohead'] and $metDN['status']=="Active") {
          $pdf->Image('../'.$PathPhoto.''.$metDN['logohead'], $metDN['logoposisix'], $metDN['logoposisiy'], 20, 20);
          $pdf->SetFont('helvetica', 'B', 22);
          $pdf->SetTextColor(255, 0, 0);
          $pdf->Text($metDN['logoposisix'] + 22, $metDN['logoposisixy'] + 20, $metDN['nama1']);

          $pdf->SetFont('helvetica', '', 16);
          $pdf->SetTextColor(0, 0, 0);
          $pdf->Text($metDN['logoposisix'] + 22, $metDN['logoposisixy'] + 25, $metDN['nama2']);
      } else {

    /*
    $pathFile = '../'.$PathPhoto.''.$metDN['brokerlogo'];
                if (file_exists($pathFile))
                {	$metLogoBroker = $pathFile;
                    //	$pdf->Image($metLogoBroker,10,7,50,15);
                    $pdf->Image($metLogoBroker,10,7,20,20);
                }
    */
      }
  }
      //$pdf->SetFont('helvetica','B',20);	$pdf->SetTextColor(255, 0, 0);	$pdf->Text(35, 15,$metDN['brokername']);
      //$pdf->SetFont('helvetica','',14);	$pdf->SetTextColor(0, 0, 0);	$pdf->Text(35, 20,'Pialang Asuransi');
      $pdf->ln(10);
      $pdf->SetFont('helvetica', 'B', 12);
      //$pdf->Text(85, $metDN['logoposisiy'] + 15,'INVOICE PREMIUM');
      $pdf->cell(190, 15, 'INVOICE PREMI', 0, 0, 'C', 0);$pdf->ln();

      //$pdf->Text(85, 35,$metDN['nomordebitnote']);
      $pdf->Code39(84, 40, $metDN['nomordebitnote']);

      $pdf->SetFont('helvetica', '', 9);
      $pdf->Text(15, 60, 'Terima Dari');		$pdf->Text(50, 60, ': '.$metDN['clientname'].' (Cabang : '.$metDN['cabangname'].')');
      $pdf->Text(15, 66, 'Uang sejumlah');

      $pdf->MultiCell(0, 18, '', 0);
      $pdf->Cell(39, 5, '', 0, 0, 'L');
      //CEK TERBILANG ANTARAN DN DAN CN
      if ($cekNilaiCN['iddn']) {
          $metGrandtotal_ = $metDN['premiclient'] - $cekNilaiCN['nilaiclaimclient'];
          $metterbilang_ = mametbilang(duitterbilang($metGrandtotal_)).'rupiah';
          $pdf->MultiCell(150, 5, ':'.ucwords($metterbilang_).'', 0);
      } else {
          $metterbilang_ = mametbilang(duitterbilang($metDN['premiclient'])).'rupiah';
          $pdf->MultiCell(150, 5, ':'.ucwords($metterbilang_).'', 0);
      }
      //CEK TERBILANG ANTARAN DN DAN CN

      $pdf->ln(); $pdf->cell(4, 5, '', 0, 0, 'L', 0);
      $pdf->cell(35, 5, 'Nama Produk', 0, 0, 'L', 0);			$pdf->cell(80, 5, ': '.$metDN['produk'].'', 0, 0, 'L', 0);
      $pdf->cell(32, 5, 'Premi Pokok', 0, 0, 'L', 0);			$pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);	$pdf->cell(25, 5, duit($metDN['debpremi']), 0, 0, 'R', 0);

      $pdf->ln(7); $pdf->cell(4, 5, '', 0, 0, 'L', 0);
      $pdf->cell(35, 5, 'Jumlah Debitur', 0, 0, 'L', 0);		$pdf->cell(80, 5, ': '.$metDN['jData'].' Debitur', 0, 0, 'L', 0);
      $pdf->cell(32, 5, 'Diskon', 0, 0, 'L', 0);				$pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);	$pdf->cell(25, 5, duit($metDN['debdiskon']), 0, 0, 'R', 0);

      $pdf->ln(7); $pdf->cell(4, 5, '', 0, 0, 'L', 0);
      $pdf->cell(35, 5, 'Tanggal Nota Debit', 0, 0, 'L', 0);		$pdf->cell(80, 5, ': '._convertDate($metDN['tgldebitnote']).'', 0, 0, 'L', 0);
      $pdf->cell(32, 5, 'Extrapremi', 0, 0, 'L', 0);			$pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);	$pdf->cell(25, 5, duit($metDN['debem']), 0, 0, 'R', 0);

      $pdf->ln(7); $pdf->cell(4, 5, '', 0, 0, 'L', 0);
      $tanggalwpc=date('Y-m-d', strtotime($metDN['tgldebitnote']."+ ".$metDN['wpc']." day"));
      // $pdf->cell(35, 5, 'Tanggal Jatuh Tempo', 0, 0, 'L', 0);	$pdf->cell(80, 5, ': '._convertDate($tanggalwpc).'', 0, 0, 'L', 0);
      $pdf->cell(115, 5, '', 0, 0, 'L', 0);
      $pdf->SetFont('helvetica', 'B', 9);	
      $pdf->Line(197, 93, 129, 93);
      $pdf->cell(32, 5, 'Total Premi', 0, 0, 'L', 0);			$pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);	$pdf->cell(25, 5, duit($metDN['premiclient']), 0, 0, 'R', 0);

      if ($cekNilaiCN['iddn']) {
          $pdf->ln(7);
          $pdf->cell(4, 5, '', 0, 0, 'L', 0);
          $pdf->cell(35, 5, ' ', 0, 0, 'L', 0);
          $pdf->cell(80, 5, '', 0, 0, 'L', 0);
          $pdf->SetFont('helvetica', '', 9);
          $pdf->Line(197, 93, 129, 93);
          $pdf->cell(32, 5, 'Nilai Creditnote', 0, 0, 'L', 0);
          $pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);
          $pdf->cell(25, 5, duit($cekNilaiCN['nilaiclaimclient']), 0, 0, 'R', 0);
          $pdf->ln(7);
          $pdf->cell(4, 5, '', 0, 0, 'L', 0);
          $pdf->cell(35, 5, ' ', 0, 0, 'L', 0);
          $pdf->cell(80, 5, '', 0, 0, 'L', 0);
          $pdf->SetFont('helvetica', 'B', 9);
          $pdf->Line(197, 107, 129, 107);
          $pdf->cell(32, 5, 'Grand Total Premium', 0, 0, 'L', 0);
          $pdf->cell(10, 5, ': Rp', 0, 0, 'L', 0);
          $pdf->cell(25, 5, duit($metGrandtotal_), 0, 0, 'R', 0);
      } else {
      }
      $pdf->ln(15);
      $pdf->cell(5, 4, '', 0, 0, 'L', 0);$pdf->SetFont('helvetica', '', 9);
      $tglIndo__ = explode(" ", $tglIndo);
      $metTglIndo = str_replace($_blnIndo, $_blnIndo_, $tglIndo__[1]);
      $pdf->cell(114, 4, 'Jakarta, '.$tglIndo__[0].' '.$metTglIndo.' '.$tglIndo__[2].'', 0, 0, 'L', 0);
      $pdf->cell(80, 4, 'Pembayaran dapat dilakukan pada account berikut :', 0, 0, 'L', 0);
      $pdf->ln();
      $pdf->cell(5, 4, '', 0, 0, 'L', 0);$pdf->SetFont('helvetica', 'B', 9);
      $pdf->cell(114, 4, $metDN['brokername'], 0, 0, 'L', 0);
      $pdf->SetFont('helvetica', '', 9);
      $pdf->cell(30, 4, 'Nama Bank', 0, 0, 'L', 0);	$pdf->cell(80, 4, $metDN['bankdebitnote'], 0, 0, 'L', 0);
      $pdf->ln();
      $pdf->cell(119, 4, '', 0, 0, 'L', 0);
      $pdf->cell(30, 4, 'Nama Account', 0, 0, 'L', 0);	$pdf->cell(80, 4, $metDN['bankdebitnotenama'], 0, 0, 'L', 0);
      $pdf->ln();
      $pdf->cell(119, 4, '', 0, 0, 'L', 0);
      $pdf->cell(30, 4, 'Nomor Rekening', 0, 0, 'L', 0);	$pdf->cell(80, 4, $metDN['bankdebitnoteaccount'], 0, 0, 'L', 0);
      $pdf->ln();
      $pdf->cell(119, 4, '', 0, 0, 'L', 0);
      $pdf->cell(30, 4, 'Cabang', 0, 0, 'L', 0);	$pdf->cell(80, 4, $metDN['bankdebitnotecabang'], 0, 0, 'L', 0);

      if ($_REQUEST['mark']=="none") {
          $pdf->ln(25);
      } else {
          $pdf->ln();
          // $pdf->Image('../'.$PathSignature.$metDN['ttd']);
      }

      $pdf->SetFont('helvetica', 'B', 9);
      $pdf->cell(10, 4, '', 0, 0, 'L', 0);
      // $pdf->cell(50, 4, $metDN['namesign'], 0, 0, 'L', 0);


      $pdf->Output($metDN['nomordebitnote'].".pdf", "I");
  ; 
} // switch
