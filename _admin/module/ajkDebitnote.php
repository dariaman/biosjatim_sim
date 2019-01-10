<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['edn']) {
    case "x":
    ;
    break;

    case "dncreate":
        echo '<div class="page-header-section"><h2 class="title semibold">Create Invoice Debit Note</h2></div>
		      	<div class="page-header-section">
						</div>
		      </div>';

        $metDebitnote = $database->doQuery('SELECT *
																				FROM ajkpeserta
																				WHERE idbroker="'.$_REQUEST['idbroker'].'" AND
																					  idclient="'.$_REQUEST['idclient'].'" AND
																					  idpolicy="'.$_REQUEST['idproduk'].'" AND
																					  cabang="'.$_REQUEST['cbg'].'" AND
																					  asuransi="'.$_REQUEST['insuranceID'].'" AND
																					  statusaktif="Approve"  AND
																					  del IS NULL AND
																					  iddn IS NULL AND
																						tglakad="'.date('Y-m-d', strtotime($_REQUEST['dTime'])).'"');

        while ($metDebitnote_ = mysql_fetch_array($metDebitnote)) {

            $metDebAsuransi = $database->doQuery('UPDATE ajkpeserta SET aspremirate=premirate, aspremi=premi, astotalpremi=totalpremi, statusaktif="Inforce" WHERE id="'.$metDebitnote_['id'].'"');
            $totalpremi += $metDebitnote_['totalpremi'];
            $totalpremiAs += $premiAsTotal;
            $metUserInput = $metDebitnote_['input_by'];
        }

        
        $metDN = mysql_fetch_array($database->doQuery('SELECT id, idbroker, iddn, tgldebitnote, input_time FROM ajkdebitnote WHERE idbroker="'.$_REQUEST['idbroker'].'" AND del IS NULL ORDER BY iddn DESC'));
        
        if ($_REQUEST['idbroker'] < 9) {
            $kodeBroker = '0'.$_REQUEST['idbroker'];
        } else {
            $kodeBroker = $_REQUEST['idbroker'];
        }

        $fakcekdn = $metDN['iddn'] + 1; $idNumber = 100000000 + $fakcekdn;		$autoNumber = substr($idNumber, 1);	// ID PESERTA //

        $debitnoteNumber = "DN.".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;

        $metReg = mysql_fetch_array($database->doQuery('SELECT ajkcabang.er AS erCbg, ajkcabang.`name`, ajkregional.er AS erReg, ajkregional.`name`
														FROM ajkcabang
														INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
														WHERE ajkcabang.er = "'.$_REQUEST['cbg'].'"'));

        $metCreateDN = $database->doQuery('INSERT INTO ajkdebitnote SET idbroker="'.$_REQUEST['idbroker'].'",
																		idclient="'.$_REQUEST['idclient'].'",
																		idproduk="'.$_REQUEST['idproduk'].'",
																		idas="'.$_REQUEST['insuranceID'].'",
																		idaspolis="'.$metAs['id'].'",
																		idregional="'.$metReg['erReg'].'",
																		idcabang="'.$_REQUEST['cbg'].'",
																		iddn="'.$fakcekdn.'",
																		nomordebitnote="'.$debitnoteNumber.'",
																		premiclient="'.$totalpremi.'",
																		premiasuransi="'.$totalpremiAs.'",
																		tgldebitnote="'.$futoday.'",
																		input_by="'.$q['id'].'",
																		input_time="'.$futgl.'",
																		del = NULL,
																		paidstatus = "Paid",
																		paidtanggal = "'.date('Y-m-d', strtotime($_REQUEST['dTime'])).'"');
        $sql = mysql_query('SELECT * FROM ajkdebitnote WHERE del IS NULL ORDER BY id DESC');
        $metDNnew = mysql_fetch_array($sql);


        $metDebitnotePeserta = $database->doQuery('	UPDATE ajkpeserta
																								SET iddn="'.$metDNnew['id'].'"
																								WHERE idbroker="'.$_REQUEST['idbroker'].'" AND
																											idclient="'.$_REQUEST['idclient'].'" AND
																											idpolicy="'.$_REQUEST['idproduk'].'" AND
																											cabang="'.$_REQUEST['cbg'].'" AND
																											asuransi="'.$_REQUEST['insuranceID'].'" AND
																											statusaktif="Inforce" AND
																											del IS NULL AND
																											iddn IS NULL');

        $cekUserInput = mysql_fetch_array($database->doQuery('SELECT idclient FROM useraccess WHERE id="'.$metUserInput.'"'));

        if ($cekUserInput['idclient'] == null) {
            $userInputPeserta = 'AND ajkclient.id="'.$metDNnew['idclient'].'"';
        } else {
            $userInputPeserta = 'AND useraccess.idclient="'.$metDNnew['idclient'].'" AND useraccess.id = "'.$metUserInput.'"';
        }

        $_metBroker = mysql_fetch_array($database->doQuery('SELECT
																												useraccess.id,
																												useraccess.idbroker,
																												useraccess.idclient,
																												useraccess.firstname,
																												useraccess.lastname,
																												useraccess.email,
																												ajkcobroker.`name` AS namebroker,
																												ajkclient.`name` AS nameclient,
																												ajkpolis.produk
																												FROM useraccess
																												INNER JOIN ajkcobroker ON useraccess.idbroker = ajkcobroker.id
																												INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
																												INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
																												WHERE ajkpolis.id="'.$metDNnew['idproduk'].'" '.$userInputPeserta.''));

        $subject = 'New Debitnote number';
        $message .= '<table style="font-family: verdana,arial,sans-serif;	font-size:11px;	color:#333333;	border-width: 1px;	border-color: #666666;	border-collapse: collapse;">
					<thead><tbody>
					<tr><td colspan="2">Dear '.$_metBroker['firstname'].''.$_metBroker['lastname'].',</td></tr>
					<tr><th width="20%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Broker</th><td> &nbsp;'.$_metBroker['namebroker'].'</td></tr>
					<tr><th style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Partner</th><td> &nbsp;'.$_metBroker['nameclient'].'</td></tr>
					<tr><th style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Product</th><td> &nbsp;'.$_metBroker['produk'].'</td></tr>
					<tr><th style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #dedede;">Debit Note</th><td> &nbsp;'.$metDNnew['nomordebitnote'].'</td></tr>
					</thead></tbody>
		    		</table>';

        $message .='<table style="font-family: verdana,arial,sans-serif;	font-size:11px;	color:#333333;	border-width: 1px;	border-color: #666666;	border-collapse: collapse;">
  			<thead>
  			<tr><th width="1%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">No</th>
		        <th width="15%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Name</th>
		        <th width="10%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">DOB</th>
		        <th width="10%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">KTP</th>
		        <th width="5%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">P.K</th>
		        <th width="1%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Age</th>
		        <th width="10%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Plafond</th>
		        <th width="8%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Start Date</th>
		        <th width="1%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Tenor</th>
		        <th width="8%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Last Date</th>
		        <th width="15%" style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666;	background-color: #dedede;">Premium</th>
		    </tr>
		    </thead>
		    <tbody>';
        $metDebitur = $database->doQuery('SELECT * FROM ajkpeserta WHERE iddn="'.$metDNnew['id'].'"');
        $xcounter=1;
        $xpremi_asuransi=0;
        while ($metDebitur_ = mysql_fetch_array($metDebitur)) {
            //$emailuserinput= mysql_fetch_array($database->doQuery('SELECT id, firstname, email FROM useraccess WHERE id="'.$metDebitur_['input_by'].'"'));
            $emailuserchecker= mysql_fetch_array($database->doQuery('SELECT id, firstname, email FROM useraccess WHERE id="'.$metDebitur_['checker_by'].'"'));
            $emailuserapprove= mysql_fetch_array($database->doQuery('SELECT id, firstname, email FROM useraccess WHERE id="'.$metDebitur_['approve_by'].'"'));
            $message .='<tr>
						<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'.++$no.'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;">'.$metDebitur_['nama'].'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'._convertDate($metDebitur_['tgllahir']).'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'.$metDebitur_['nomorktp'].'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'.$metDebitur_['nomorpk'].'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'.$metDebitur_['usia'].'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="right">'.duit($metDebitur_['plafond']).'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'._convertDate($metDebitur_['tglakad']).'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'.$metDebitur_['tenor'].'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="center">'._convertDate($metDebitur_['tglakhir']).'</td>
			   			<td style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #ffffff;" align="right">'.duit($metDebitur_['totalpremi']).'</td>
			    	</tr>';
            $mailtotalpremium +=$metDebitur_['totalpremi'];

            $xpremi_asuransi+=$metDebitur_['astotalpremi'];
            $xcounter++;
        }

        $message .='<tr>
					<th style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #dedede;" align="center" colspan="10">Total </th>
					<th style="border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #dedede;" align="right">'.duit($mailtotalpremium).' </th>
					</tr>
    		</tbody>
    		</table>';

        $ajkmailto = $_metBroker['email'];
        $ajkmailnameto = $_metBroker['firstname'];
        $ajkmailtocount = 1;
        $ajkmailfromname = $q['firstname'];
        $ajkmailfrom = $q['email'];
        $ajkmailccname = $emailuserapprove['firstname'].'|'.$emailuserchecker['firstname'];
        $ajkmailccmail = $emailuserapprove['email'].'|'.$emailuserchecker['email'];
        $ajkmailcccount = 2;
        //kirimemail($ajkmailfromname,$ajkmailfrom,$ajkmailnameto, $ajkmailto,$ajkmailtocount, $ajkmailccname, $ajkmailccmail, $ajkmailcccount, $subject,$message);
        //EMAIL

        echo '<meta http-equiv="refresh" content="1; url=ajk.php?re=dn&edn=dninv">
			 <div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<strong>Success!</strong> New number Debit Note '.$debitnoteNumber.' by '.$q['firstname'].'.
		    </div>';

        //       echo '<div class="row">
      		// 	<div class="col-md-12">
      		// 	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
      		// 	<div class="panel-heading"><h3 class="panel-title">Notification Email</h3></div>
      		// 		<div class="panel-body">
      		// 			<div class="form-group">
      		// 				<div class="col-sm-12">Successfully send mail to : '.$_metBroker['firstname'].'</div>
      		// 			</div>
      		// 		</div>
      		// 	</form>
      		// '.$message.'
      		// </div>
      		// </div>';;
    break;

    case "dncreateRT":
        $metDebitnote = $database->doQuery('SELECT *
																				FROM ajkpeserta
																				WHERE idbroker="'.$_REQUEST['idbroker'].'" AND
																					  idclient="'.$_REQUEST['idclient'].'" AND
																					  idpolicy="'.$_REQUEST['idproduk'].'" AND
																					  cabang="'.$_REQUEST['cbg'].'" AND
																					  statusaktif="Approve"  AND
																					  del IS NULL AND
																					  iddn IS NULL AND
																					  input_time="'.$_REQUEST['dTime'].'" AND
																					  approve_time="'.$_REQUEST['dTimeAppr'].'"');

        while ($metDebitnote_ = mysql_fetch_array($metDebitnote)) {
            $metInsurance = mysql_fetch_array($database->doQuery('SELECT 	ajkdebitnote.idas,
																																		ajkdebitnote.idaspolis,
																																		ajkpeserta.id AS iddebitur,
																																		ajkpeserta.tenor AS tenorlama,
																																		ajkpeserta.tglakad AS tglakadlama,
																																		ajkpeserta.totalpremi AS totalpremilamabank,
																																		ajkpeserta.astotalpremi AS totalpremilamaasuransi,
																																		ajkpeserta.iddn AS dnlama,
																																		ajkdebitnote.id AS iddebitnote,
																																		ajkinsurance.`name` AS namaasuransi,
																																		ajkpolis.refundrate,
																																		ajkpolis.refundpercentage
																														FROM ajkpeserta
																														INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																														INNER JOIN ajkinsurance ON ajkdebitnote.idas = ajkinsurance.id
																														INNER JOIN ajkpolisasuransi ON ajkdebitnote.idaspolis = ajkpolisasuransi.id
																														INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
																														WHERE ajkpeserta.nomorktp = "'.$metDebitnote_['nomorktp'].'" AND
																																	ajkpeserta.statusaktif = "Inforce"'));


            $metAs = mysql_fetch_array($database->doQuery('SELECT * FROM ajkpolisasuransi WHERE idas="'.$metInsurance['idas'].'" AND idproduk="'.$_REQUEST['idproduk'].'" '));
            if ($metAs['byrate']=="Age") {
                $metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremiins WHERE idbroker="'.$_REQUEST['idbroker'].'" AND idclient="'.$_REQUEST['idclient'].'" AND idproduk="'.$_REQUEST['idproduk'].'" AND idas="'.$metInsurance['idas'].'" AND idpolis="'.$metInsurance['idaspolis'].'" AND '.$metDebitnote_['usia'].' BETWEEN agefrom AND ageto AND '.$metDebitnote_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
            } else {
                $metRate = mysql_fetch_array($database->doQuery('SELECT * FROM ajkratepremiins WHERE idbroker="'.$_REQUEST['idbroker'].'" AND idclient="'.$_REQUEST['idclient'].'" AND idproduk="'.$_REQUEST['idproduk'].'" AND idas="'.$metInsurance['idas'].'" AND idpolis="'.$metInsurance['idaspolis'].'" AND '.$metDebitnote_['tenor'].' BETWEEN tenorfrom AND tenorto AND status="Aktif" AND del IS NULL'));
            }
            $premiAs = $metDebitnote_['plafond'] * ($metRate['rate'] / $metAs['calculatedrate']);
            $premiAsDiskon = $premiAs * ($metAs['diskon'] / 100);
            $premiAsTotal = ROUND($premiAs - $premiAsDiskon);

            //UPDATE PREMI ASURANSI
            $metSetAuto = substr($metSetAutoNumber + $metDebitnote_['id'], 1);
            $totalpremi += $metDebitnote_['totalpremi'] + $metDebitnote_['premigeneral'] + $metDebitnote_['premipa'];
            $totalpremiAs += $premiAsTotal;
            $metUserInput = $metDebitnote_['input_by'];

            //LAST ID
            $metDN = mysql_fetch_array($database->doQuery('SELECT id, idbroker, iddn, tgldebitnote, input_time FROM ajkdebitnote WHERE idbroker="'.$_REQUEST['idbroker'].'" AND del IS NULL ORDER BY iddn DESC'));
            //echo $metDN['id'].'_'.$futgldn;
            if ($_REQUEST['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idbroker'];
            } else {
                $kodeBroker = $_REQUEST['idbroker'];
            }

            $fakcekdn = $metDN['iddn'] + 1;
            $idNumber = 100000000 + $fakcekdn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //

            $debitnoteNumber = "DN.".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;

            $metReg = mysql_fetch_array($database->doQuery('SELECT ajkcabang.er AS erCbg, ajkcabang.`name`, ajkregional.er AS erReg, ajkregional.`name`
															FROM ajkcabang
															INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
															WHERE ajkcabang.er = "'.$_REQUEST['cbg'].'"'));

            $metCreateDN = $database->doQuery('	INSERT INTO ajkdebitnote
																					SET idbroker="'.$_REQUEST['idbroker'].'",
																							idclient="'.$_REQUEST['idclient'].'",
																							idproduk="'.$_REQUEST['idproduk'].'",
																							idas="'.$metInsurance['idas'].'",
																							idaspolis="'.$metInsurance['idaspolis'].'",
																							idregional="'.$metReg['erReg'].'",
																							idcabang="'.$_REQUEST['cbg'].'",
																							iddn="'.$fakcekdn.'",
																							nomordebitnote="'.$debitnoteNumber.'",
																							premiclient="'.$totalpremi.'",
																							premiasuransi="'.$totalpremiAs.'",
																							tgldebitnote="'.$futoday.'",
																							input_by="'.$q['id'].'",
																							input_time="'.$futgl.'"');

            $metNewDN = mysql_fetch_array($database->doQuery('SELECT Max(id) AS iddebitnote FROM ajkdebitnote WHERE idbroker ="'.$_REQUEST['idbroker'].'" AND del IS NULL'));
            $metDebAsuransi = $database->doQuery('UPDATE ajkpeserta SET iddn="'.$metNewDN['iddebitnote'].'", idpeserta="'.$metSetAuto.'", aspremirate="'.$metRate['rate'].'", aspremi="'.$premiAs.'", aspremidiskon="'.$premiAsDiskon.'", astotalpremi="'.$premiAsTotal.'", statusaktif="Inforce" WHERE id="'.$metDebitnote_['id'].'"');


            //CEK DATA LAMA UNTUK DILAPSE
            $metCN = mysql_fetch_array($database->doQuery('SELECT Max(id) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idbroker'].'" AND del IS NULL'));
            if ($_REQUEST['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idbroker'];
            } else {
                $kodeBroker = $_REQUEST['idbroker'];
            }
            $fakcekcn = $metCN['idcn'] + 1;
            $idNumber = 100000000 + $fakcekcn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //
            if ($metDebitnote_['tiperefund']=="Refund") {
                $kodeRefund = 'R';
            } else {
                $kodeRefund = 'T';
            }
            $creditnoteNumber = "CN.".$kodeRefund."".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;

            $tenortopupberjalan = datediff($metInsurance['tglakadlama'], $metDebitnote_['tglakad']);
            $tenortopupberjalan_ = explode(",", $tenortopupberjalan);
            if ($tenortopupberjalan_[2] > 1) {
                $jumlahblnhari = 1;
            } else {
                $jumlahblnhari = 0;
            }
            $tenortopupberjalan__ = ($tenortopupberjalan_[0] * 12) + $tenortopupberjalan_[1] + $jumlahblnhari;	//tenor berjalan
            $tenorsisa = $metInsurance['tenorlama'] - $tenortopupberjalan__;	//tenor sisa

            if ($metInsurance['refundrate']=="Percentage") {
                $raterefund = $metInsurance['refundpercentage'];
            } else {
                //table
                $raterefund = '';
            }

            $penutupanbank = $metInsurance['totalpremilamabank'] * $raterefund / 100;
            $nilairefundbank = $tenortopupberjalan__ / $metInsurance['tenorlama'] * ($metInsurance['totalpremilamabank'] - $penutupanbank);

            $penutupanasuransi = $metInsurance['totalpremilamaasuransi'] * $raterefund / 100;
            $nilairefundasuransi = $tenortopupberjalan__ / $metInsurance['tenorlama'] * ($metInsurance['totalpremilamaasuransi'] - $penutupanasuransi);
            $metRefund = $database->doQuery('	INSERT INTO ajkcreditnote
																				SET idbroker="'.$_REQUEST['idbroker'].'",
																						idclient="'.$_REQUEST['idclient'].'",
																						idproduk="'.$_REQUEST['idproduk'].'",
																						idas="'.$metInsurance['idas'].'",
																						idaspolis="'.$metInsurance['idaspolis'].'",
																						idpeserta="'.$metInsurance['iddebitur'].'",
																						idregional="'.$metReg['erReg'].'",
																						idcabang="'.$_REQUEST['cbg'].'",
																						iddn="'.$metInsurance['dnlama'].'",
																						iddn_exs="'.$metNewDN['iddebitnote'].'",
																						idcn="'.$fakcekcn.'",
																						nomorcreditnote="'.$creditnoteNumber.'",
																						tglcreditnote="'.$metDebitnote_['tglakad'].'",
																						tglklaim="'.$metDebitnote_['tglakad'].'",
																						nilaiclaimclient="'.$nilairefundbank.'",
																						nilaiclaimasuransi="'.$nilairefundasuransi.'",
																						status="Approve",
																						tipeklaim="'.$metDebitnote_['tiperefund'].'",
																						create_by="'.$q['id'].'",
																						create_time="'.$futgl.'"');

            $metCNLastID = mysql_fetch_array($database->doQuery('SELECT MAX(id) AS lastID FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idbroker'].'" AND del IS NULL'));
            //UPDATE PESERTA LAMA REFUND JADI LAPSE
            $metDebLama = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$metCNLastID['lastID'].'", statusaktif="Lapse" WHERE id="'.$metInsurance['iddebitur'].'"');
            //UPDATE PESERTA LAMA REFUND JADI LAPSE
            //CEK DATA LAMA UNTUK DILAPSE
            echo '<!--<meta http-equiv="refresh" content="1; url=ajk.php?re=dn&edn=dninv">-->
						<div class="alert alert-dismissable alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Success!</strong> New number Debit Note '.$debitnoteNumber.' by '.$q['firstname'].'.
					    </div>';
        };
    break;

    case "DNview":
        echo '<div class="page-header-section"><h2 class="title semibold">Create Invoice Debit Note</h2></div>
		      	<div class="page-header-section">
				<div class="toolbar"><a href="ajk.php?re=dn&edn=dninv">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        $met = mysql_fetch_array($database->doQuery('	SELECT ajkpeserta.idbroker,
																												ajkpeserta.idclient,
																												ajkpeserta.idpolicy,
																												ajkpeserta.cabang,
																												ajkcabang.name AS nmCabang,
																												ajkregional.er AS idReg,
																												COUNT(ajkpeserta.nama) AS jData,
																												SUM(ajkpeserta.totalpremi) AS jPremind,
																												SUM(ajkpeserta.premigeneral) AS jpremigeneral,
																												SUM(ajkpeserta.premipa) AS jPremipa,
																												ajkcobroker.`name` AS brokername,
																												ajkcobroker.logo AS brokerlogo,
																												ajkclient.`name` AS clientname,
																												ajkclient.logo AS clientlogo,
																												ajkpolis.policyauto AS policyauto,
																												ajkpolis.produk,
																												ajkpolis.general,
																												ajkpeserta.asuransi
																									FROM ajkpeserta
																									INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
																									INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
																									INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
																									INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
																									INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
																									WHERE ajkpeserta.iddn IS NULL AND
																												ajkpeserta.del IS NULL AND
																												ajkpeserta.statusaktif="Approve" AND
																												ajkpeserta.asuransi="'.$thisEncrypter->decode($_REQUEST['idas']).'" AND
																												ajkpeserta.tglakad="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND
																												ajkpeserta.cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'"
																									GROUP BY ajkpeserta.input_time'));

        $metAsuransi = $database->doQuery('	SELECT 	ajkinsurance.id,
																							  ajkinsurance.name,
																							  ajkratepremiins.id AS idratepremiinsurance,
																							  ajkratepremiins.idbroker,
																							  ajkratepremiins.idclient,
																							  ajkratepremiins.idproduk
																				FROM ajkinsurance
																				INNER JOIN ajkratepremiins ON ajkinsurance.id = ajkratepremiins.idas
																				WHERE ajkinsurance.idc = "'.$met['idbroker'].'" AND
																					  ajkratepremiins.idclient = "'.$met['idclient'].'" AND
																					  ajkratepremiins.idproduk = "'.$met['idpolicy'].'" AND
																					  ajkratepremiins.status = "Aktif" AND
																					  ajkinsurance.del IS NULL
																				GROUP BY ajkratepremiins.idpolis, ajkratepremiins.idas');
                $_ttlpremigeneral = $met['jpremigeneral'];
                $_ttlpremipa = $met['jPremipa'];
                $_grandtotal = $met['jPremind'] + $_ttlpremigeneral + $_ttlpremipa;

                echo '<div class="panel-body">
						<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="100px" height="65px"></div>
							<div class="col-md-10">
							<dl class="dl-horizontal">
								<dt>Broker</dt><dd>'.$met['brokername'].'</dd>
								<dt>Company</dt><dd>'.$met['clientname'].'</dd>
								<dt>Product</dt><dd>'.$met['produk'].'</dd>
								<dt>Total Premium</dt><dd><span class="label label-primary">'.duit($_grandtotal).'</span></dd>
								<dt>Branch</dt><dd>'.$met['nmCabang'].'</dd>
							</dl>
						</div>
						<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="100px" height="65px"></div>
					</div>';

                $cePremiAsuransi = mysql_fetch_array($database->doQuery('SELECT idbroker, idclient, idpolicy, regional FROM ajkpeserta WHERE idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'" AND tglakad="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND approve_time="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'" AND del IS NULL'));
                echo '<div class="table-responsive panel-collapse pull out">
					<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
					<input type="hidden" name="dTime" value="'.$thisEncrypter->decode($_REQUEST['dtime']).'">
					<input type="hidden" name="dTimeAppr" value="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'">
					<input type="hidden" name="idbroker" value="'.$cePremiAsuransi['idbroker'].'">
					<input type="hidden" name="idclient" value="'.$cePremiAsuransi['idclient'].'">
					<input type="hidden" name="idproduk" value="'.$thisEncrypter->decode($_REQUEST['idp']).'">
					<input type="hidden" name="reg" value="'.$cePremiAsuransi['regional'].'">
					<input type="hidden" name="cbg" value="'.$thisEncrypter->decode($_REQUEST['cbg']).'">';

                echo '<div class="panel-body">
						<div class="row">
						<div class="col-sm-5"><div class="note note-info mb15"><strong>PERSENTASE PENUTUPAN ASURANSI TERHADAP BANK</strong></div></div>';
                $metAsuransiData = $database->doQuery('SELECT ajkinsurance.id,
															  ajkinsurance.idc,
															  ajkinsurance.`name` AS insurance,
															  Count(ajkpeserta.nama) AS debitur,
															  Sum(ajkpeserta.plafond) AS plafond,
															  Sum(ajkpeserta.totalpremi) AS premibank,
															  Sum(ajkpeserta.astotalpremi) AS premiasuransi,
															  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.date("Y-m").'" AND "'.date("Y-m").'" THEN ajkpeserta.totalpremi END) AS jmlPremiBankMonth,
															  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.date("Y-m").'" AND "'.date("Y-m").'" THEN ajkpeserta.astotalpremi END) AS jmlPremiAsuransiMonth,
															  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.date("Y").'" AND "'.date("Y").'" THEN ajkpeserta.totalpremi END) AS jmlPremiBankYear,
															  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.date("Y").'" AND "'.date("Y").'" THEN ajkpeserta.astotalpremi END) AS jmlPremiAsuransiYear
																FROM ajkpeserta
																	INNER JOIN ajkdebitnote
																	ON ajkdebitnote.id = ajkpeserta.iddn
																	INNER JOIN ajkinsurance
																	ON ajkinsurance.id = ajkdebitnote.idas
																		WHERE ajkpeserta.idclient = "'.$met['idclient'].'"
																		AND ajkpeserta.idbroker = "'.$met['idbroker'].'"
																		AND	ajkinsurance.del IS NULL
																	GROUP BY ajkinsurance.id');

                echo '<table class="table table-hover table-bordered">
				      <thead>
				      	<tr>
				        <th class="text-center" width="20%">Insurance</th>
				        <th class="text-center">Total Debitur</th>
				        <th class="text-center">Total Plafond</th>
				        <th class="text-center">Total Premium Bank</th>
				        <th class="text-center">Total Premium Insurance</th>
						<th class="text-center">Premium Bank This Month</th>
				        <th class="text-center">Premium Insurance This Month</th>
				        <th class="text-center">This Month</th>
				        <th class="text-center">Premium Bank This Year</th>
				        <th class="text-center">Premium Insurance This Year</th>
				        <th class="text-center">This Year</th>
				        </tr>
				    </thead>
				    <tbody>';
                while ($metAsuransiData_ = mysql_fetch_array($metAsuransiData)) {
                    $_persentase = mysql_fetch_array($database->doQuery('SELECT ajkinsurance.id,
																																			ajkinsurance.`name` AS insurance,
																																			Count(ajkpeserta.nama) AS debitur,
																																			Sum(ajkpeserta.plafond) AS plafond,
																																			Sum(ajkpeserta.totalpremi) AS premibank,
																																			Sum(ajkpeserta.astotalpremi) AS premiasuransi,
																																			Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.date("Y-m").'" AND "'.date("Y-m").'" THEN ajkpeserta.totalpremi END) AS jmlPremiBankMonth,
																																			Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.date("Y-m").'" AND "'.date("Y-m").'" THEN ajkpeserta.astotalpremi END) AS jmlPremiAsuransiMonth,
																																			Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.date("Y").'" AND "'.date("Y").'" THEN ajkpeserta.totalpremi END) AS jmlPremiBankYear,
																																			Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.date("Y").'" AND "'.date("Y").'" THEN ajkpeserta.astotalpremi END) AS jmlPremiAsuransiYear
																															FROM ajkclient
																															INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
																															INNER JOIN ajkpolisasuransi ON ajkclient.id = ajkpolisasuransi.idcost AND ajkpolis.id = ajkpolisasuransi.idproduk
																															INNER JOIN ajkinsurance ON ajkpolisasuransi.idas = ajkinsurance.id
																															INNER JOIN ajkdebitnote ON ajkinsurance.id = ajkdebitnote.idas
																															INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
																															WHERE ajkclient.idc = "'.$met['idbroker'].'"  AND
																																		ajkclient.id = "'.$met['idclient'].'"'));
                    //$_metpersentase = $metAsuransiData_['premiasuransi'] / $_persentase['premiasuransi'] * 100;
                    $_metpersentasebulan = $metAsuransiData_['jmlPremiAsuransiMonth'] / $_persentase['jmlPremiAsuransiMonth'] * 100;
                    $_metpersentasetahun = $metAsuransiData_['jmlPremiAsuransiYear'] / $_persentase['jmlPremiAsuransiYear'] * 100;
                    echo '<tr>
							<td>'.$metAsuransiData_['insurance'].'</td>
							<td class="text-center">'.duit($metAsuransiData_['debitur']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['plafond']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['premibank']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['premiasuransi']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['jmlPremiBankMonth']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['jmlPremiAsuransiMonth']).'</td>
							<td class="text-center"><span class="number text-muted bold"><span class="label label-primary">'.duitkoma($_metpersentasebulan).' %</span></span></td>
							<td class="text-right">'.duit($metAsuransiData_['jmlPremiBankYear']).'</td>
							<td class="text-right">'.duit($metAsuransiData_['jmlPremiAsuransiYear']).'</td>
							<td class="text-center"><span class="number text-muted bold"><span class="label label-primary">'.duitkoma($_metpersentasetahun).' %</span></span></td>
						</tr>';
                }

                if ($met['general']=="Y") {
                    $kolom_EXL = '<th class="text-center" width="1%">Premi ND</th>
					       		  <!--<th class="text-center" width="1%">Discount</th>
					       		  <th class="text-center" width="1%">Adminfee</th>-->
					       		  <th class="text-center" width="1%">Total Premi ND</th>
					       		  <th class="text-center" width="1%">Premi Fire</th>
					       		  <th class="text-center" width="1%">Premi PA</th>
					       		  <th class="text-center" width="1%">Total Premi</th>';
                } else {
                    $kolom_EXL = '<th class="text-center" width="1%">Premi</th>
								  <!--<th class="text-center" width="1%">Discount</th>
					       		  <th class="text-center" width="1%">Adminfee</th>-->
								  <th class="text-center" width="1%">Total Premi</th>
								  <th class="text-center" width="6%">Photo</th>';
                }

                //cek jika asuransinya sudah di setting dari awal - hansen - 20180124
                if ($met['asuransi']!= "") {
                    // $spk = mysql_fetch_array(mysql_query("SELECT asuransi,ajkinsurance.name as nm_asuransi
                    // 																			FROM ajkspk
                    // 																			INNER JOIN ajkinsurance
                    // 																			ON ajkinsurance.id = ajkspk.asuransi
                    // 																			WHERE nomorspk = '".$met['nomorspk']."'"));
                    $spk = mysql_fetch_array(mysql_query("SELECT ajkinsurance.name as nm_asuransi,id
																								FROM ajkinsurance
																								WHERE id = '".$met['asuransi']."' "));
                    $insurance = '<select name="insuranceID" class="form-control">
										<option value="'.$spk['id'].'" selected>'.$spk['nm_asuransi'].'</option>
									 </select>';
                } else {
                    $insurance = '<select name="insuranceID" class="form-control" required>
							    	<option value="">Select Insurance</option>';

                    while ($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
                        if (!$metAsuransi_['idratepremiinsurance']) {
                            $insurance = $insurance.'<option value="">Rate insurance not upload</option>';
                        } else {
                            $insurance = $insurance.'<option value="'.$metAsuransi_['id'].'"'._selected($_REQUEST['insuranceID'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
                        }
                    }
                    $insurance = $insurance.'</select>';
                }

                //cek jika asuransinya sudah di setting dari awal - hansen - 20180124 - END

                echo '</table><hr>
							<div class="col-sm-5"><div class="note note-success mb15"><strong>PILIH ASURANSI UNTUK MEMBUAT DEBITNOTE</strong></div></div>
							<div class="col-sm-7"> &nbsp; </div><br /><br /><br />
							<div class="col-md-1">Insurance <span class="text-danger">*</span></div>
							<div class="col-md-11">
							'.$insurance.'
							</div>
						</div>
					</div>
		      <table class="table table-hover table-bordered">
			      <thead>
			      	<tr>
			        <th class="text-center" width="1%">No</th>
			        <th class="text-center" width="8%">No.Pinjaman</th>
			        <th class="text-center">Name</th>
							<th class="text-center">Kategori</th>
			        <th class="text-center" width="8%">DOB</th>
			        <th class="text-center" width="10%">KTP</th>
			        <th class="text-center" width="1%">Age</th>
			        <th class="text-center" width="1%">Plafond</th>
			        <th class="text-center" width="8%">Start Insurance</th>
			        <th class="text-center" width="1%">Tenor</th>
			        <th class="text-center" width="8%">Last Insurance</th>
              <th class="text-center" width="8%">Rate</th>
							'.$kolom_EXL.'
			        </tr>
			    	</thead>
			    	<tbody>';
                            $metExl = $database->doQuery('SELECT ajkpeserta.nama,
																 ajkpeserta.tgllahir,
																 ajkpeserta.nomorspk,
																 ajkpeserta.nomorktp,
																 ajkpeserta.nomorpk,
																 ajkpeserta.usia,
																 ajkpeserta.plafond,
																 ajkpeserta.tglakad,
																 ajkpeserta.tenor,
																 ajkpeserta.tglakhir,
																 ajkpeserta.premi,
                                 ajkpeserta.premirate,
																 ajkpeserta.diskonpremi,
																 ajkpeserta.biayaadmin,
																 ajkpeserta.totalpremi,
																 ajkpeserta.premigeneral,
																 ajkpeserta.premipa,
																 IF(ajkpolis.general="Y",(ajkpeserta.totalpremi + ajkpeserta.premigeneral + ajkpeserta.premipa), ajkpeserta.totalpremi) AS totalpremi_general,
																 ajkpeserta.input_time,
																 ajkpeserta.id,
																 ajkpeserta.idbroker,
																 ajkpeserta.idclient,
																 ajkpeserta.idpolicy,
																 ajkclient.`name`,
																 ajkpolis.policyauto,
																 ajkpolis.policymanual,
																 ajkpolis.shareins,
																 ajkpolis.general,
																 ajkpeserta.input_time,
																 ajkpeserta.nopinjaman,																 
																 ajkkategoriprofesi.nm_kategori_profesi
																 FROM ajkpeserta
																 INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
																 INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
                                 LEFT JOIN ajkprofesi ON ajkprofesi.ref_mapping = ajkpeserta.pekerjaan
																 LEFT JOIN ajkkategoriprofesi ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
																 WHERE ajkpeserta.iddn IS NULL AND
																 	   ajkpeserta.del IS NULL AND
																 	   ajkpeserta.statusaktif="Approve" AND
																 	   ajkpeserta.tglakad="'.date('Y-m-d', strtotime($thisEncrypter->decode($_REQUEST['dtime']))).'" AND
																 	   ajkpeserta.cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'" AND
																 	   ajkpeserta.idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND
																		 ajkpeserta.asuransi="'.$thisEncrypter->decode($_REQUEST['idas']).'"
																 ORDER BY ajkpeserta.id ASC');


                while ($metExl_ = mysql_fetch_array($metExl)) {

                    //CEK DATA GENERAL
                    if ($metExl_['general']=="Y") {
                        $_met_EXL = '<td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premi']).'</span></td>
						   	 <!--<td align="right"><strong>'.duit($metExl_['diskonpremi']).'</strong></td>
						   	 <td align="right"><strong>'.duit($metExl_['biayaadmin']).'</strong></td>-->
						   	 <td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi']).'</span></td>
						   	 <td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premigeneral']).'</span></td>
				   			 <td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premipa']).'</span></td>
				   			 <td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi_general']).'</span></td>';
                    } else {
                        $_met_EXL = '<td align="right"><span class="number"><span class="label label-success"><font color="black">'.duit($metExl_['premi']).'</font></span></td>
							 <!--<td align="right"><strong>'.duit($metExl_['diskonpremi']).'</strong></td>
							 <td align="right"><strong>'.duit($metExl_['biayaadmin']).'</strong></td>-->
						   	 <td align="right"><span class="number"><span class="label label-primary"><font color="black">'.duit($metExl_['totalpremi']).'</font></span></td>';
                    }
                    //CEK DATA GENERAL

                    //PHOTO MEMBER
                    if ($metExl_['nomorspk'] != "") {
                        $metPhotoSPK = mysql_fetch_array($database->doQuery('SELECT *,DATE_FORMAT(ajkspk.input_date, "%Y-%m-%d") AS tglinput FROM ajkspk WHERE idbroker="'.$metExl_['idbroker'].'" AND idpartner="'.$metExl_['idclient'].'" AND idproduk="'.$metExl_['idpolicy'].'" AND nomorspk="'.$metExl_['nomorspk'].'"'));
                        $photoSPKnya = '<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['photodebitur2'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
											    <div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['photoktp'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['photosk'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['ttddebitur'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['ttdmarketing'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['photobydokter'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['ttddokter'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
													<script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
										      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
										      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';
                        $metphoto ='<div class="col-xs-12 col-sm-12 col-md-12">
													<div class="row" id="shuffle-grid">
														<div class="col-md-12 shuffle" data-groups=\'["nature"]\' data-date-created="'._convertDate($metPhotoSPK['tglinput']).'" data-title="background1">
						    							<div class="thumbnail">
						        						<div class="media">
																	<div class="indicator"><span class="spinner"></span></div>
																	<div class="overlay">
							                		'.$photoSPKnya.'
																	<div class="toolbar"><a href="../myFiles/_ajk/'.$metPhotoSPK['photodebitur1'].'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
		                						</div>
		            								<img data-toggle="unveil" src="../myFiles/_ajk/'.$metPhotoSPK['photodebitur1'].'" data-src="../myFiles/_ajk/'.$metPhotoSPK['photodebitur1'].'" alt="Photo" width="100%" height="50"/>
		        									</div>
							    					</div>
													</div>
												</div>';
                    } else {
                        $metphoto ='<center><img src="../image/nophoto.png" width="30"></center>';
                    }
                    //PHOTO MEMBER
                    echo '<tr>
				   	<td align="center">'.++$no.'</td>
				   	<td align="center">'.$metExl_['nopinjaman'].'</td>
				   	<td>'.$metExl_['nama'].'</td>
						<td>'.$metExl_['nm_kategori_profesi'].'</td>
				   	<td align="center">'._convertDate($metExl_['tgllahir']).'</td>
				   	<td align="center">'.$metExl_['nomorktp'].'</td>
				   	<td align="center">'.$metExl_['usia'].'</td>
				   	<td align="right">'.duit($metExl_['plafond']).'</td>
				   	<td align="center">'._convertDate($metExl_['tglakad']).'</td>
				   	<td align="center">'.$metExl_['tenor'].'</td>
				   	<td align="center">'._convertDate($metExl_['tglakhir']).'</td>
            <td align="center">'.round($metExl_['premirate'],2).'</td>
				   	'.$_met_EXL.'
				   	<td>'.$metphoto.'</td>
				    </tr>';
                }
                echo '</tbody> </table>';
                    echo '<div class="panel-footer" align="center"><input type="hidden" name="edn" value="dncreate">'.BTN_CREATEDN.'</div>';

                    echo '</form>
				    </div>
				    </div>';
                echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                        ;
    break;

    case "DNviewRT":
        echo '<div class="page-header-section"><h2 class="title semibold">Create Invoice Debit Note</h2></div>
			      	<div class="page-header-section">
					<div class="toolbar"><a href="ajk.php?re=dn&edn=dninv">'.BTN_BACK.'</a></div>
					</div>
			      </div>';
                    $met = mysql_fetch_array($database->doQuery('SELECT ajkpeserta.idbroker,
																ajkpeserta.idclient,
																ajkpeserta.idpolicy,
																ajkpeserta.cabang,
																ajkcabang.name AS nmCabang,
																ajkregional.er AS idReg,
																COUNT(ajkpeserta.nama) AS jData,
																SUM(ajkpeserta.totalpremi) AS jPremind,
																SUM(ajkpeserta.premigeneral) AS jpremigeneral,
																SUM(ajkpeserta.premipa) AS jPremipa,
																ajkcobroker.`name` AS brokername,
																ajkcobroker.logo AS brokerlogo,
																ajkclient.`name` AS clientname,
																ajkclient.logo AS clientlogo,
																ajkpolis.policyauto AS policyauto,
																ajkpolis.produk,
																ajkpolis.general
																FROM ajkpeserta
																INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
																INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
																INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
																INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
																INNER JOIN ajkregional ON ajkcabang.idreg = ajkregional.er
																WHERE ajkpeserta.iddn IS NULL AND ajkpeserta.del IS NULL AND ajkpeserta.statusaktif="Approve" AND ajkpeserta.input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND ajkpeserta.approve_time="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'" AND ajkpeserta.cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'"
																GROUP BY ajkpeserta.input_time'));
                    //$metAsuransi = $database->doQuery('SELECT * FROM ajkinsurance WHERE idc = "'.$met['idbroker'].'" AND del IS NULL');
                    $metAsuransi = $database->doQuery('SELECT ajkinsurance.id,
													  ajkinsurance.name,
													  ajkratepremiins.id AS idratepremiinsurance,
													  ajkratepremiins.idbroker,
													  ajkratepremiins.idclient,
													  ajkratepremiins.idproduk
												FROM ajkinsurance
												INNER JOIN ajkratepremiins ON ajkinsurance.id = ajkratepremiins.idas
												WHERE ajkinsurance.idc = "'.$met['idbroker'].'" AND
													  ajkratepremiins.idclient = "'.$met['idclient'].'" AND
													  ajkratepremiins.idproduk = "'.$met['idpolicy'].'" AND
													  ajkinsurance.del IS NULL AND
													  ajkratepremiins.status = "Aktif"
												GROUP BY ajkratepremiins.idpolis');
                $_ttlpremigeneral = $met['jpremigeneral'];
                $_ttlpremipa = $met['jPremipa'];
                $_grandtotal = $met['jPremind'] + $_ttlpremigeneral + $_ttlpremipa;

                  echo '<div class="panel-body">
									<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="100px" height="65px"></div>
										<div class="col-md-10">
										<dl class="dl-horizontal">
											<dt>Broker</dt><dd>'.$met['brokername'].'</dd>
											<dt>Company</dt><dd>'.$met['clientname'].'</dd>
											<dt>Product</dt><dd>'.$met['produk'].'</dd>
											<dt>Total Premium</dt><dd><span class="label label-primary">'.duit($_grandtotal).'</span></dd>
											<dt>Branch</dt><dd>'.$met['nmCabang'].'</dd>
										</dl>
									</div>
									<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="100px" height="65px"></div>
								</div>';

                $cePremiAsuransi = mysql_fetch_array($database->doQuery('SELECT idbroker, idclient, idpolicy, regional FROM ajkpeserta WHERE idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'" AND input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND approve_time="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'" AND del IS NULL'));
                  echo '<div class="table-responsive panel-collapse pull out">
									<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
									<input type="hidden" name="dTime" value="'.$thisEncrypter->decode($_REQUEST['dtime']).'">
									<input type="hidden" name="dTimeAppr" value="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'">
									<input type="hidden" name="idbroker" value="'.$cePremiAsuransi['idbroker'].'">
									<input type="hidden" name="idclient" value="'.$cePremiAsuransi['idclient'].'">
									<input type="hidden" name="idproduk" value="'.$thisEncrypter->decode($_REQUEST['idp']).'">
									<input type="hidden" name="reg" value="'.$cePremiAsuransi['regional'].'">
									<input type="hidden" name="cbg" value="'.$thisEncrypter->decode($_REQUEST['cbg']).'">';

                echo '<div class="panel-body"><div class="row">';
                    $metAsuransiData = $database->doQuery('SELECT ajkinsurance.id,
														  ajkinsurance.idc,
														  ajkinsurance.`name` AS insurance,
														  Sum(ajkdebitnote.premiclient) AS premibank,
														  Sum(ajkdebitnote.premiasuransi) AS premiasuransi,
														  Count(ajkpeserta.nama) AS debitur,
														  SUM(ajkpeserta.plafond) AS plafond
														  FROM ajkinsurance
														  INNER JOIN ajkdebitnote ON ajkinsurance.id = ajkdebitnote.idas
														  INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
														  WHERE ajkinsurance.idc = "'.$met['idbroker'].'" AND ajkinsurance.del IS NULL
														  GROUP BY ajkinsurance.id');

                    echo '<table class="table table-hover table-bordered">
							      <thead>
							      	<tr>
							        <th class="text-center">Insurance</th>
							        <th class="text-center">Summary Debitur</th>
							        <th class="text-center">Summary Plafond</th>
							        <th class="text-center">Summary Premium Bank</th>
							        <th class="text-center">Summary Premium Insurance</th>
							        <th class="text-center">Percent</th>
							        </tr>
							    </thead>
							    <tbody>';
                    while ($metAsuransiData_ = mysql_fetch_array($metAsuransiData)) {
                        $_persentase = mysql_fetch_array($database->doQuery('SELECT ajkinsurance.id, ajkinsurance.`name` AS insurance, Sum(ajkdebitnote.premiclient) AS premibank, Sum(ajkdebitnote.premiasuransi) AS premiasuransi, Count(ajkpeserta.nama) AS debitur, Sum(ajkpeserta.plafond) AS plafond, ajkdebitnote.idbroker FROM ajkinsurance INNER JOIN ajkdebitnote ON ajkinsurance.id = ajkdebitnote.idas INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn WHERE ajkinsurance.del IS NULL AND ajkinsurance.idc = "'.$metAsuransiData_['idc'].'"'));
                        $_metpersentase = $metAsuransiData_['premiasuransi'] / $_persentase['premiasuransi'] * 100;
                        echo '<tr>
												<td>'.$metAsuransiData_['insurance'].'</td>
												<td class="text-center">'.duit($metAsuransiData_['debitur']).'</td>
												<td class="text-center">'.duit($metAsuransiData_['plafond']).'</td>
												<td class="text-center">'.duit($metAsuransiData_['premibank']).'</td>
												<td class="text-center">'.duit($metAsuransiData_['premiasuransi']).'</td>
												<td class="text-center"><span class="number text-muted bold"><span class="label label-primary">'.duit($_metpersentase).' %</span></span></td>
											</tr>';
                    }
                echo '</table>
							  </div>
							  </div>
						      <table class="table table-hover table-bordered">
						      <thead>
						      	<tr>
						        <th class="text-center" width="1%">No</th>
						        <th class="text-center">Insurance</th>
						        <th class="text-center">ID Peserta</th>
						        <th class="text-center">Name</th>
						        <th class="text-center" width="8%">DOB</th>
						        <th class="text-center" width="10%">KTP</th>
						        <th class="text-center" width="10%">P.K</th>
						        <th class="text-center" width="1%">Age</th>
						        <th class="text-center" width="1%">Plafond</th>
						        <th class="text-center" width="8%">Start Insurance</th>
						        <th class="text-center" width="1%">Tenor</th>
						        <th class="text-center" width="8%">Last Insurance</th>
								<th class="text-center" width="1%">Premi</th>
								<th class="text-center" width="1%">Total Premi</th>
						        </tr>
						    </thead>
						    <tbody>';
                    $metExl = $database->doQuery('SELECT ajkpeserta.nama,
												 ajkpeserta.tgllahir,
												 ajkpeserta.nomorktp,
												 ajkpeserta.nomorpk,
												 ajkpeserta.usia,
												 ajkpeserta.plafond,
												 ajkpeserta.tglakad,
												 ajkpeserta.tenor,
												 ajkpeserta.tglakhir,
												 ajkpeserta.premi,
												 ajkpeserta.diskonpremi,
												 ajkpeserta.biayaadmin,
												 ajkpeserta.totalpremi,
												 ajkpeserta.premigeneral,
												 ajkpeserta.premipa,
												 IF(ajkpolis.general="Y",(ajkpeserta.totalpremi + ajkpeserta.premigeneral + ajkpeserta.premipa), ajkpeserta.totalpremi) AS totalpremi_general,
												 ajkpeserta.input_time,
												 ajkpeserta.id,
												 ajkpeserta.idpolicy,
												 ajkclient.`name`,
												 ajkpolis.policyauto,
												 ajkpolis.policymanual,
												 ajkpolis.shareins,
												 ajkpolis.general,
												 ajkpeserta.input_time
												 FROM ajkpeserta
												 INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
												 INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
												 WHERE ajkpeserta.iddn IS NULL AND
												 	   ajkpeserta.del IS NULL AND
												 	   ajkpeserta.statusaktif="Approve" AND
												 	   ajkpeserta.input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND
												 	   ajkpeserta.approve_time="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'" AND
												 	   ajkpeserta.cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'" AND
												 	   ajkpeserta.idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'"
												 ORDER BY ajkpeserta.id ASC');
                    while ($metExl_ = mysql_fetch_array($metExl)) {
                        //CEK DATA LAMANYA
                        $metInsurance = mysql_fetch_array($database->doQuery('SELECT ajkdebitnote.id AS iddebitnote,
																				 ajkdebitnote.idas,
															  					 ajkdebitnote.idaspolis,
															  					 ajkpeserta.id AS iddebitur,
															  					 ajkpeserta.idpeserta,
																				 ajkpeserta.nama,
															  					 ajkpeserta.nomorktp,
															  					 ajkpeserta.nomorpk,
															  					 ajkpeserta.usia,
															  					 ajkpeserta.tgllahir,
															  					 ajkpeserta.plafond,
															  					 ajkpeserta.tglakad,
															  					 ajkpeserta.tenor,
															  					 ajkpeserta.tglakhir,
															  					 ajkpeserta.premi,
															  					 ajkpeserta.totalpremi,
															  					 ajkinsurance.`name` AS namaasuransi
																		  FROM ajkpeserta
																		  INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																		  INNER JOIN ajkinsurance ON ajkdebitnote.idas = ajkinsurance.id
																		  INNER JOIN ajkpolisasuransi ON ajkdebitnote.idaspolis = ajkpolisasuransi.id
																		  WHERE ajkpeserta.nomorktp = "'.$metExl_['nomorktp'].'" AND
																		  		ajkpeserta.statusaktif = "Inforce"'));
                        echo '<tr class="info"><td align="center">'.++$no.'</td>
									  <td><strong>'.$metInsurance['namaasuransi'].'</strong></td>
									  <td><strong>'.$metInsurance['idpeserta'].'</strong></td>
									  <td><strong>'.$metInsurance['nama'].'</strong></td>
									  <td align="center"><strong>'._convertDate($metInsurance['tgllahir']).'</strong></td>
									  <td align="center">'.$metInsurance['nomorktp'].'</td>
									  <td align="center">'.$metInsurance['nomorpk'].'</td>
									  <td align="center">'.$metInsurance['usia'].'</td>
									  <td align="center">'.duit($metInsurance['plafond']).'</td>
									  <td align="center">'._convertDate($metInsurance['tglakad']).'</td>
									  <td align="center">'.$metInsurance['tenor'].'</td>
									  <td align="center">'._convertDate($metInsurance['tglakhir']).'</td>
									  <td align="center"><strong>'.duit($metInsurance['premi']).'</strong></td>
									  <td align="center"><strong>'.duit($metInsurance['totalpremi']).'</strong></td>

								  </tr>';
                        //CEK DATA LAMANYA
                        echo '<tr>
								   	<td align="center">#</td>
									<input type="hidden" name="idas" value="'.$metInsurance['idas'].'">
									<input type="hidden" name="idaspolis" value="'.$metInsurance['idaspolis'].'">
								   	<td>########</td>
								   	<td>########</td>
								   	<td>'.$metExl_['nama'].'</td>
								   	<td align="center">'._convertDate($metExl_['tgllahir']).'</td>
								   	<td align="center">'.$metExl_['nomorktp'].'</td>
								   	<td align="center">'.$metExl_['nomorpk'].'</td>
								   	<td align="center">'.$metExl_['usia'].'</td>
								   	<td align="right">'.duit($metExl_['plafond']).'</td>
								   	<td align="center">'._convertDate($metExl_['tglakad']).'</td>
								   	<td align="center">'.$metExl_['tenor'].'</td>
								   	<td align="center">'._convertDate($metExl_['tglakhir']).'</td>
								   	<td align="right"><span class="number"><span class="label label-success">'.duit($metExl_['premi']).'</span></td>
									<td align="right"><span class="number"><span class="label label-primary">'.duit($metExl_['totalpremi']).'</span></td>
								</tr>';
                    }
                            echo '</tbody>
						    </table>
							<div class="panel-footer" align="center"><input type="hidden" name="edn" value="dncreateRT">'.BTN_CREATEDN.'</div>
							</form>
						    </div>
						    </div>';
                        echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                        ;
    break;

    case "dninv":
            echo '<div class="page-header-section"><h2 class="title semibold">Create Invoice Debit Note</h2></div>
		      	<div class="page-header-section">
						</div>
				      </div>';
                echo '<div class="table-responsive panel-collapse pull out">
				      <table class="table table-hover table-bordered">
				      <thead>
				      	<tr><th class="text-center" width="1%">No</th>
				        	<th class="text-center" width="1%">Insurance</th>
				        	<th class="text-center" width="1%">Product</th>
				        	<th class="text-center" width="1%">Type</th>
				        	<th class="text-center" width="1%">Data</th>
				        	<th class="text-center" width="5%">Total Premium</th>
				        	<th class="text-center" width="1%">Cabang</th>
				        	<th class="text-center" width="1%">Date Upload</th>
				        	<th class="text-center" width="1%">Time Upload</th>
				        	<th class="text-center" width="1%">Date Approve</th>
				        	<th class="text-center" width="1%">Time Approve</th>
				        	<th class="text-center" width="1%">Create</th>
				        </tr>
				    </thead>
				    <tbody>';

                $metExl = $database->doQuery('SELECT Count(ajkpeserta.nama) AS jData,
													 SUM(ajkpeserta.totalpremi) AS jPremind,
													 SUM(ajkpeserta.premigeneral) AS jpremigeneral,
													 SUM(ajkpeserta.premipa) AS jPremipa,
													 ajkpeserta.input_time,
													 ajkpeserta.tglakad,
													 ajkpeserta.id, ajkclient.`name`,
													 ajkpolis.id, ajkpolis.policyauto,
													 ajkpolis.policymanual,
													 ajkpolis.produk,
													 ajkpeserta.tiperefund,
													 ajkpeserta.cabang,
													 ajkpeserta.input_time,
													 ajkpeserta.approve_time,
													 ajkpeserta.asuransi,
                           ajkinsurance.name as nm_asuransi,
													 ajkcabang.name AS nmCabang
											  FROM ajkpeserta
											  INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
											  INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
											  INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
                        INNER JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
											  WHERE ajkpeserta.iddn IS NULL AND ajkpeserta.statusaktif="Approve" '.$q___1.' AND ajkpeserta.del IS NULL
											  GROUP BY ajkpeserta.idpolicy,ajkpeserta.cabang, ajkpeserta.tglakad, ajkpeserta.asuransi
											  ORDER BY ajkpeserta.input_time DESC');
                while ($metExl_ = mysql_fetch_array($metExl)) {
                  $_dttime = explode(" ", $metExl_['input_time']);
                  $_dttimeApp = explode(" ", $metExl_['approve_time']);
                  $_grandtotal = $metExl_['jPremind'] + $_ttlpremigeneral + $_ttlpremipa;

                  if ($metExl_['tiperefund']==null) {
                      $metTipe = '<span class="label label-success">Existing</span>';
                      $metCreate__ = '<a href="ajk.php?re=dn&edn=DNview&idas='.$thisEncrypter->encode($metExl_['asuransi']).'&dtime='.$thisEncrypter->encode($metExl_['tglakad']).'&dtimeAppr='.$thisEncrypter->encode($metExl_['approve_time']).'&idp='.$thisEncrypter->encode($metExl_['id']).'&cbg='.$thisEncrypter->encode($metExl_['cabang']).'">'.BTN_VIEW.'</a>';
                  } else {
                      $metTipe = '<span class="label label-warning">'.$metExl_['tiperefund'].'</span>';
                      $metCreate__ = '<a href="ajk.php?re=dn&edn=DNviewRT&dtime='.$thisEncrypter->encode($metExl_['tglakad']).'&dtimeAppr='.$thisEncrypter->encode($metExl_['approve_time']).'&idp='.$thisEncrypter->encode($metExl_['id']).'&cbg='.$thisEncrypter->encode($metExl_['cabang']).'">'.BTN_VIEW2.'</a>';
                  }
                  echo '<tr>
							   	<td align="center">'.++$no.'</td>
							   	<td>'.$metExl_['nm_asuransi'].'</td>
							   	<td>'.$metExl_['produk'].'</td>
							   	<td>'.$metTipe.'</td>
							   	<td align="center"><span class="label label-primary">'.$metExl_['jData'].'</span></td>
							   	<td align="center"><span class="label label-primary">'.duit($_grandtotal).'</span></td>
							   	<td>'.$metExl_['nmCabang'].'</td>
							   	<td align="center">'._convertDate($_dttime[0]).'</td>
							   	<td align="center">'.$_dttime[1].'</td>
							   	<td align="center">'._convertDate($_dttimeApp[0]).'</td>
							   	<td align="center">'.$_dttimeApp[1].'</td>
							   	<td align="center">'.$metCreate__.'</td>
							    </tr>';
                }
                echo '</tbody>
				    </table>
				    </div>';
                        ;
    break;

    case "DNDelete":
        echo '<div class="page-header-section"><h2 class="title semibold">Delete Data Member Declaration</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
        $metDelData = $database->doQuery('UPDATE ajkpeserta SET del="1",
																statusaktif="Batal",
																keterangan="Data Create DN dibatalkan",
																update_by="'.$q['id'].'",
																update_time="'.$futgl.'"
										WHERE idpolicy="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND
											  cabang="'.$thisEncrypter->decode($_REQUEST['cbg']).'" AND
											  input_time="'.$thisEncrypter->decode($_REQUEST['dtime']).'" AND
											  approve_time="'.$thisEncrypter->decode($_REQUEST['dtimeAppr']).'"');
        echo '<meta http-equiv="refresh" content="1; url=ajk.php?re=dn&edn=dninv">
			<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<strong>Success!</strong> Data successfully deleted by '.$q['firstname'].'.
		    </div>';
            ;
    break;

    case "deldnunpaid":
                if ($_REQUEST['met']=="delDNmet") {
                    //echo $_REQUEST['delID'].'<br />';
                    //echo $_REQUEST['catatanbatal'].'<br />';
                    $debitnoteDel = $database->doQuery('UPDATE ajkdebitnote SET keterangan="'.$_REQUEST['catatanbatal'].'" WHERE id="'.$_REQUEST['delID'].'"');

                    $debitnoteDelSel = $database->doQuery('SELECT ajkdebitnote.id,
																  ajkdebitnote.idbroker,
																  ajkdebitnote.idclient,
																  ajkdebitnote.idproduk,
																  ajkdebitnote.idas,
																  ajkdebitnote.idaspolis,
																  ajkdebitnote.idcabang,
																  ajkdebitnote.idregional,
																  ajkpeserta.id AS idpeserta,
																  ajkpeserta.nama,
																  ajkpeserta.totalpremi,
																  ajkpeserta.astotalpremi
														FROM ajkdebitnote
														INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
														WHERE ajkdebitnote.id ="'.$_REQUEST['delID'].'" AND ajkpeserta.del IS NULL AND ajkpeserta.idcn IS NULL');
                    while ($debitnoteDelSel_ = mysql_fetch_array($debitnoteDelSel)) {
                        $metCN = mysql_fetch_array($database->doQuery('SELECT Max(idcn) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$debitnoteDelSel_['idbroker'].'" AND del IS NULL'));
                        if ($_REQUEST['idbroker'] < 9) {
                            $kodeBroker = '0'.$debitnoteDelSel_['idbroker'];
                        } else {
                            $kodeBroker = $debitnoteDelSel_['idbroker'];
                        }
                        $fakcekcn = $metCN['idcn'] + 1;
                        $idNumber = 100000000 + $fakcekcn;
                        $autoNumber = substr($idNumber, 1);	// ID PESERTA //
                        $creditnoteNumber = "CN.B".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;

                        $creditnoteDel = $database->doQuery('INSERT INTO ajkcreditnote SET idbroker="'.$debitnoteDelSel_['idbroker'].'",
																					   idclient="'.$debitnoteDelSel_['idclient'].'",
																					   idproduk="'.$debitnoteDelSel_['idproduk'].'",
																					   idas="'.$debitnoteDelSel_['idas'].'",
																					   idaspolis="'.$debitnoteDelSel_['idaspolis'].'",
																					   idpeserta="'.$debitnoteDelSel_['idpeserta'].'",
																					   idregional="'.$debitnoteDelSel_['idregional'].'",
																					   idcabang="'.$debitnoteDelSel_['idcabang'].'",
																					   iddn="'.$debitnoteDelSel_['id'].'",
																					   idcn="'.$fakcekcn.'",
																					   nomorcreditnote="'.$creditnoteNumber.'",
																					   tglcreditnote="'.$futoday.'",
																					   tglklaim="'.$futoday.'",
																					   nilaiclaimclient="'.$debitnoteDelSel_['totalpremi'].'",
																					   nilaiclaimasuransi="'.$debitnoteDelSel_['astotalpremi'].'",
																					   status="Batal",
																					   tipeklaim="Batal",
																					   keterangan="'.$_REQUEST['catatanbatal'].'",
																					   input_by="'.$q['id'].'",
																					   input_time="'.$futgl.'"');
                        //echo '<br /><br />';
                        $metCNDel_ = mysql_fetch_array($database->doQuery('SELECT id FROM ajkcreditnote WHERE del IS NULL ORDER BY id DESC'));
                        //echo $metCNDel_['id'];
                        //echo '<br />';
                        $metPesertaBatal = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$metCNDel_['id'].'", statusaktif="Batal", statuspeserta="Batal" WHERE id="'.$debitnoteDelSel_['idpeserta'].'"');
                        //echo '<br />';
                    }
                    echo '<meta http-equiv="refresh" content="1; url=ajk.php?re=dn">
					<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<strong>Success!</strong> Data successfully deleted by '.$q['firstname'].'.
				    </div>';
                }
                $metDNdel = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.`name` AS brokername,
																		 ajkcobroker.logo AS brokerlogo,
																		 ajkclient.`name` AS clientname,
																		 ajkclient.logo AS clientlogo,
																		 ajkcabang.`name` AS cabang,
																		 ajkpolis.produk,
																		 ajkdebitnote.nomordebitnote,
																		 ajkdebitnote.paidstatus
																FROM ajkdebitnote
																INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
																INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
																INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
																INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
																WHERE ajkdebitnote.id = "'.$thisEncrypter->decode($_REQUEST['dID']).'"'));
                echo '<div class="page-header-section"><h2 class="title semibold">Delete Data Debit Note</h2></div>
					<div class="page-header-section">
					<div class="toolbar"><a href="ajk.php?re=dn">'.BTN_BACK.'</a></div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="tab-content">
					    	<div class="tab-pane active" id="profile">
					        <form method="post" class="panel form-horizontal form-bordered" name="form-profile" action="#" data-parsley-validate enctype="multipart/form-data">
							<input type="hidden" name="delID" value="'.$thisEncrypter->decode($_REQUEST['dID']).'">
							<div class="panel-body pt0 pb0">
					        	<div class="form-group header bgcolor-default">
					            	<div class="col-md-6">
					            	<ul class="list-table">
					            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metDNdel['brokerlogo'].'" alt="" width="75px"></li>
										<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metDNdel['brokername'].'</h4></li>
									</ul>
									</div>
									<div class="col-md-6">
					            	<ul class="list-table">
										<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metDNdel['clientname'].'</h4></li>
										<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metDNdel['clientlogo'].'" alt="" width="75px"></li>
									</ul>
									</div>
					            </div>
								<div class="form-group">
					            	<div class="col-xs-12 col-sm-12 col-md-7">
											<div class="col-sm-3"><a href="javascript:void(0);">Product</a></div><div class="col-sm-9">'.$metDNdel['produk'].'&nbsp;</div>
											<div class="col-sm-3"><a href="javascript:void(0);">Nomor Debitnote</a></div><div class="col-sm-9">'.$metDNdel['nomordebitnote'].'&nbsp;</div>
											<div class="col-sm-3"><a href="javascript:void(0);">Status</a></div><div class="col-sm-9"><button type="button" class="btn btn-warning btn-xs mb5"><strong>'.$metDNdel['paidstatus'].'</strong></button></div>
											<div class="col-sm-3"><a href="javascript:void(0);">Branch</a></div><div class="col-sm-9"><button type="button" class="btn btn-info btn-xs mb5"><strong>'.$metDNdel['cabang'].'</strong></button></div>
											<div class="col-md-12"><h4 class="semibold text-success mt0 mb5">Data Debitur</h4></div>
											<table class="table table-hover table-bordered">
				                                    <thead>
				                                        <tr>
				                                            <th>Name</th>
				                                            <th width="1%">Plafond</th>
				                                            <th>Start Ins</th>
				                                            <th width="1%">Tenor</th>
				                                            <th>End Ins</th>
				                                            <th>Nett Premi Client</th>
				                                            <th>Nett Premi Ins</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>';
                $metDelDeb = $database->doQuery('SELECT * FROM ajkpeserta WHERE iddn="'.$thisEncrypter->decode($_REQUEST['dID']).'"');
                while ($metDelDeb_ = mysql_fetch_array($metDelDeb)) {
                    echo '<tr>
						<td>'.$metDelDeb_['nama'].'</td>
				        <td><span class="label label-primary">'.duit($metDelDeb_['plafond']).'</span></td>
				        <td class="text-center"><span class="sparklines" sparkType="bar" sparkBarColor="#a0d569">'._convertDate($metDelDeb_['tglakad']).'</span></td>
				        <td class="text-center"><span class="sparklines" sparkType="bar" sparkBarColor="#a0d569">'.$metDelDeb_['tenor'].'</span></td>
				        <td class="text-center"><span class="sparklines" sparkType="bar" sparkBarColor="#a0d569">'._convertDate($metDelDeb_['tglakhir']).'</span></td>
				        <td class="text-right"><span class="sparklines" sparkType="bar" sparkBarColor="#a0d569">'.duit($metDelDeb_['totalpremi']).'</span></td>
				        <td class="text-right"><span class="sparklines" sparkType="bar" sparkBarColor="#a0d569">'.duit($metDelDeb_['astotalpremi']).'</span></td>
					</tr>';
                }
                                                echo '</tbody>
				                                </table>
									</div>
					                <div class="col-xs-12 col-sm-12 col-md-5">
									<div class="row mb5"><div class="col-sm-12"><textarea name="catatanbatal" type="text" class="form-control" placeholder="Reason the cancellation debitnote" rows="15" required>'.$_REQUEST['catatanbatal'].'</textarea></div></div>
									<div class="text-right"><input type="hidden" name="met" value="delDNmet">'.BTN_SUBMIT.'</div>
									</div>
					            </div>
					        </div>
					        </form>
					    </div>
					</div>
					</div>
				</div>';
                    ;
    break;

    default:
		      echo '<div class="page-header-section"><h2 class="title semibold">Debit Note</h2></div>
		    	<div class="page-header-section">
			</div>
		    </div>
		    <div class="row">
		    	<div class="col-md-12">
		      	<div class="panel panel-default">

				<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
				<thead>
				<tr><th width="1%" class="text-center">No</th>
					<th class="text-center">Product</th>
					<th width="1%" class="text-center">Date DN</th>
					<th class="text-center">Debit Note</th>
					<th width="1%" class="text-center">Data</th>
					<th width="1%" class="text-center">Premium</th>
					<th width="1%" class="text-center">Status</th>
					<th width="1%" class="text-center">WPC</th>
					<th width="1%" class="text-center">Paid Date</th>
					<th width="1%">Branch</th>
					<th width="10%">Invoice</th>
				</tr>
				</thead>
				<tbody>';

                $metDebitnote = $database->doQuery('SELECT
								Count(ajkpeserta.nama) AS jData,
								ajkcobroker.`name` AS namebroker,
								ajkclient.`name` AS nameclient,
								ajkpolis.produk,
								ajkcabang.`name` AS cabang,
								ajkdebitnote.id,
								ajkdebitnote.nomordebitnote,
								ajkdebitnote.premiclient,
								ajkdebitnote.premiasuransi,
								ajkdebitnote.paidstatus,
								ajkdebitnote.paidtanggal,
								ajkdebitnote.tgldebitnote,
								DATE_ADD(ajkdebitnote.tgldebitnote,INTERVAL ajkpolis.wpc DAY) AS wpc,
								Sum(ajkcreditnote.nilaiclaimclient) AS nilaicnclient,
								Sum(ajkcreditnote.nilaiclaimasuransi) AS nilaicnasuransi,
								ajkcreditnote.`status` AS statuscn,
								ajkcreditnote.tipeklaim
								FROM ajkdebitnote
								INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
								INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
								INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
								INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
								INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
								LEFT JOIN ajkcreditnote ON ajkpeserta.id = ajkcreditnote.idpeserta
								WHERE ajkdebitnote.del IS NULL AND  ajkpeserta.del IS NULL '.$q___1.'
								GROUP BY ajkdebitnote.id
								ORDER BY ajkdebitnote.input_time DESC');

                while ($metDebitnote_ = mysql_fetch_array($metDebitnote)) {
                    if ($metDebitnote_['paidstatus']=="Paid") {
                        $metstatusdn = '<span class="label label-primary">'.$metDebitnote_['paidstatus'].'</span>';
                        $metTglLunas = _convertDate($metDebitnote_['paidtanggal']);
                        $metDNCreateDel ='';
                    } elseif ($metDebitnote_['paidstatus']=="Unpaid") {
                        $metTglLunas = '';
                        if ($metDebitnote_['statuscn']=="Batal" and $metDebitnote_['tipeklaim']=="Batal") {
                            $metstatusdn = '<span class="label label-danger">'.$metDebitnote_['tipeklaim'].'</span>';
                            $metDNCreateDel ='';
                        } else {
                            $metstatusdn = '<span class="label label-warning">'.$metDebitnote_['paidstatus'].'</span>';
                            $metDNCreateDel ='<a title="delete all data debitnote" href="ajk.php?re=dn&edn=deldnunpaid&dID='.$thisEncrypter->encode($metDebitnote_['id']).'"><button type="button" class="btn btn-danger btn-rounded btn-xs mb5"><i class="ico-trash"></i></button></a>';
                        }
                    } else {
                        $metstatusdn = '<span class="label label-warning">'.$metDebitnote_['paidstatus'].'</span>';
                        $metTglLunas = _convertDate($metDebitnote_['paidtanggal']);
                        $metDNCreateDel ='';
                    }


                    echo '<tr>
								   	<td align="center">'.++$no.'</td>
								   	<td align="center">'.$metDebitnote_['produk'].'</td>
								   	<td align="center">'._convertDate($metDebitnote_['tgldebitnote']).'</td>
								   	<td><strong>'.$metDebitnote_['nomordebitnote'].'</strong></td>
								   	<td align="center"><strong>'.$metDebitnote_['jData'].'</strong></td>
								   	<td align="right"><span class="label label-primary">'.duit($metDebitnote_['premiclient']).'</span></td>
								   	<td align="center">'.$metstatusdn.'</td>
								   	<td align="center">'._convertDate($metDebitnote_['wpc']).'</td>
								   	<td align="center">'.$metTglLunas.'</td>
								   	<td>'.$metDebitnote_['cabang'].'</td>
								   	<td align="center">
								   		<a title="invoice not signature" href="ajk.php?re=dlPdf&pID='.$thisEncrypter->encode($metDebitnote_['nomordebitnote']).'&idd='.$thisEncrypter->encode($metDebitnote_['id']).'&mark=none" target="_blank"><button type="button" class="btn btn-default btn-rounded btn-xs mb5"><i class="ico-file-pdf"></i></button></a> &nbsp;
					 					  <a title="invoice signature" href="ajk.php?re=dlPdf&pID='.$thisEncrypter->encode($metDebitnote_['nomordebitnote']).'&idd='.$thisEncrypter->encode($metDebitnote_['id']).'" target="_blank"><button type="button" class="btn btn-success btn-rounded btn-xs mb5"><i class="ico-file-pdf"></i></button></a> &nbsp;
					 					  <a title="list debitur" href="ajk.php?re=dlPdf&pdf=member&pID='.$thisEncrypter->encode($metDebitnote_['nomordebitnote']).'&idd='.$thisEncrypter->encode($metDebitnote_['id']).'" target="_blank"><button type="button" class="btn btn-primary btn-rounded btn-xs mb5"><i class="ico-file-pdf"></i></button></a>
										</td>
									</tr>';
                }
                echo '</tbody>
									<tfoot>
						        <tr>
						        	<th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Debit Note"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Data"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
					            <th><input type="hidden" class="form-control" name="search_engine"></th>
						        </tr>
					        </tfoot>
				        </table>
				    	</div>
						</div>
				    </div>
				</div>';
                        ;
                } // switch
                echo '</div>
						<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
				    </section>';
