<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// Copyright (C) 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['cc']) {
    case "setCancel":
        echo '<div class="page-header-section"><h2 class="title semibold">Cancel Member</h2></div>
		      </div>';
        //CREATE CN BATAL
        $metCN = mysql_fetch_array($database->doQuery('SELECT Max(idcn) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idb'].'" AND del IS NULL'));
        if ($_REQUEST['idb'] < 9) {
            $kodeBroker = '0'.$_REQUEST['idb'];
        } else {
            $kodeBroker = $_REQUEST['idb'];
        }
        $fakcekcn = $metCN['idcn'] + 1; $idNumber = 100000000 + $fakcekcn;		$autoNumber = substr($idNumber, 1);	// ID PESERTA //
        $creditnoteNumber = "CN.B".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
        //CREATE CN BATAL
        $metDbtrBatal = mysql_fetch_array($database->doQuery('SELECT ajkpeserta.id,
																	 ajkpeserta.idbroker,
																	 ajkpeserta.idclient,
																	 ajkpeserta.idpolicy,
																	 ajkpeserta.iddn,
																	 ajkpeserta.idpeserta,
																	 ajkpeserta.nama,
																	 ajkpeserta.totalpremi,
																	 ajkpeserta.astotalpremi,
																	 ajkpeserta.regional,
																	 ajkpeserta.area,
																	 ajkpeserta.cabang,
																	 ajkdebitnote.idas,
																	 ajkdebitnote.idaspolis
																FROM ajkpeserta
																INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
																WHERE ajkpeserta.id = "'.$_REQUEST['idm'].'"'));
        $metBatal = $database->doQuery('INSERT INTO ajkcreditnote SET idbroker = "'.$metDbtrBatal['idbroker'].'",
																	  idclient = "'.$metDbtrBatal['idclient'].'",
																	  idproduk = "'.$metDbtrBatal['idpolicy'].'",
																	  idas = "'.$metDbtrBatal['idas'].'",
																	  idaspolis = "'.$metDbtrBatal['idaspolis'].'",
																	  idpeserta = "'.$metDbtrBatal['id'].'",
																	  idregional = "'.$metDbtrBatal['regional'].'",
																	  idcabang = "'.$metDbtrBatal['cabang'].'",
																	  iddn = "'.$metDbtrBatal['iddn'].'",
																	  idcn = "'.$fakcekcn.'",
																	  nomorcreditnote = "'.$creditnoteNumber.'",
																	  tglcreditnote = "'.$futoday.'",
																	  tglklaim ="'.$futoday.'",
																	  nilaiclaimclient = "'.$metDbtrBatal['totalpremi'].'",
																	  nilaiclaimasuransi = "'.$metDbtrBatal['astotalpremi'].'",
																	  status = "Batal",
																	  tipeklaim = "Batal",
																	  keterangan ="'.$_REQUEST['notecancel'].'",
																	  create_by = "'.$q['id'].'",
																	  create_time = "'.$futgl.'"');
        $metCNBatal = mysql_fetch_array($database->doQuery('SELECT id FROM ajkcreditnote ORDER BY id DESC'));
        $metDebBatal = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$metCNBatal['id'].'", statusaktif="Batal", statuspeserta="Batal" WHERE id="'.$_REQUEST['idm'].'"');
        echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim&cc=ncancel">
			  <div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Success!</strong> Data debitur has been canceled by '.$q['username'].'</div>';
            ;
    break;

    case "newClaimCancel":
        echo '<div class="page-header-section"><h2 class="title semibold">Cancel Member</h2></div>
		      <div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=cclaim&cc=ncancel">'.BTN_BACK.'</a></div></div>
		      </div>';
        echo '<div class="row">
				<div class="col-md-12">';
        $metComp = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.`name` AS brokername,
																ajkclient.`name` AS clientname,
																ajkclient.logo,
																ajkpolis.produk,
																ajkpolis.klaimrate,
																ajkpolis.klaimpercentage,
																ajkdebitnote.idas,
																ajkdebitnote.idaspolis,
																ajkdebitnote.nomordebitnote,
																ajkdebitnote.paidstatus,
																ajkdebitnote.paidtanggal,
																ajkdebitnote.tgldebitnote,
																ajkcabang.`name` AS cabang,
																ajkpeserta.id,
																ajkpeserta.idbroker,
																ajkpeserta.idclient,
																ajkpeserta.idpolicy,
																ajkpeserta.iddn,
																ajkpeserta.regional,
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
																ajkpeserta.statusaktif
														FROM ajkpeserta
														INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
														INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
														INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
														INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
														INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
														WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        echo '<div class="tab-content">
				<div class="tab-pane active" id="profile">
				<form class="panel form-horizontal form-bordered" name="form-profile" method="post" action=""  data-parsley-validate enctype="multipart/form-data">
					<div class="panel-body pt0 pb0">
						<div class="form-group header bgcolor-default">
							<div class="col-md-12">
							<ul class="list-table">
								<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['clientname'].'</h4></li>
								<li class="text-right"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['produk'].'</h4></li>
							</ul>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">ID Debitur</a></p></div><div class="col-sm-10"><p class="meta nm">'.$metComp['idpeserta'].'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Name</a></p></div><div class="col-sm-10"><p class="meta nm">'.$metComp['nama'].'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">D.O.B</a></p></div><div class="col-sm-10"><p class="meta nm">'._convertDate($metComp['tgllahir']).'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Age</a></p></div><div class="col-sm-10"><p class="meta nm">'.$metComp['usia'].' years</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">KTP Number</a></p></div><div class="col-sm-10"><p class="meta nm">'.$metComp['nomorktp'].'</p></div></div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Plafond</a></p></div><div class="col-sm-10"><p class="meta nm">'.duit($metComp['plafond']).'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Insurance Date</a></p></div><div class="col-sm-10"><p class="meta nm">'._convertDate($metComp['tglakad']).' - '._convertDate($metComp['tglakhir']).'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Tenor</a></p></div><div class="col-sm-10"><p class="meta nm">'.duit($metComp['tenor']).' months</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Total Premium</a></p></div><div class="col-sm-10"><p class="meta nm">'.duit($metComp['totalpremi']).'</p></div></div>
							<div class="table-layout mt1 mb0"><div class="col-sm-2"><p class="meta nm"><a href="javascript:void(0);">Branch</a></p></div><div class="col-sm-10"><p class="meta nm">'.$metComp['cabang'].'</p></div></div>
						</div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Note Cancel Member<span class="text-danger"> *</span></label>
						<input type="hidden" name="idm" value="'.$thisEncrypter->decode($_REQUEST['idm']).'">
						<input type="hidden" name="idb" value="'.$metComp['idbroker'].'">
		            	<div class="col-sm-10"><textarea class="form-control" rows="5" name="notecancel" required>'.$_REQUEST['notecancel'].'</textarea></div>
		            </div>
				</div>
			<div class="panel-footer text-center"><input type="hidden" name="cc" value="setCancel">'.BTN_SUBMIT.'</div>
			</form>
			</div>';
        echo '</div></div>';
        echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
            ;
    break;

    case "ncancel":
        $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE status = "Approve" '.$q___.' AND tipeklaim="Batal" AND del IS NULL'));
        if ($metDataMbr['jdata']>=1) {
            $batalREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqBatal&back=ncancel" title="jumlah pengajuan data klaim"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        } else {
            $batalREG = '';
        }

        echo '<div class="page-header-section"><h2 class="title semibold">Cancel Member</h2></div>
				<div class="page-header-section">
				'.$batalREG.'
				</div>
			</div>
			<div class="row">
			<div class="col-md-12">';
        if ($_REQUEST['src']=="claimdatacancel") {
            if ($_REQUEST['idmember']=="" and $_REQUEST['name']=="") {
                $metnotif .= '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Error!</strong> Please insert id member or name !</div>';
            } else {
                if ($_REQUEST['idmember'] and !$_REQUEST['name']) {
                    $satu = 'AND ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%"';
                }
                if ($_REQUEST['name'] and !$_REQUEST['idmember']) {
                    $dua = 'AND ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }
                if ($_REQUEST['name'] and $_REQUEST['idmember']) {
                    $tiga = 'AND ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%" OR ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }

                $metKlaim = $database->doQuery('SELECT
		ajkpolis.id AS idproduk,
		ajkpolis.produk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS brokerclient,
		ajkpeserta.id AS idm,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.tgllahir,
		ajkpeserta.usia,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.statuslunas,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkpeserta.astotalpremi,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkpolis.jumlahharibatal,
		datediff(current_date(), ajkdebitnote.tgldebitnote) AS jumlahhari
		FROM ajkpeserta INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkpeserta.statusaktif = "Inforce" AND ajkpeserta.statuslunas="0" '.$q___1.' '.$satu.' '.$dua.' '.$tiga.'
		AND ajkpolis.jumlahharibatal >= datediff(current_date(), ajkdebitnote.tgldebitnote)');

                $metSRC .= '<div class="panel panel-default">
					<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
					<thead>
					<tr><th width="1%">No</th>
						<th>Partner</th>
						<th>Product</th>
						<th width="20%">Debit Note</th>
						<th width="1%">Date Debit Note</th>
						<th width="1%">ID Member</th>
						<th width="1%">Member</th>
						<th width="1%">Age</th>
						<th width="1%">Plafond</th>
						<th width="1%">Start Date Insurance</th>
						<th width="1%">Tenor</th>
						<th width="1%">End Date Insurance</th>
						<th width="1%">Premium (Bank)</th>
						<th width="1%">Premium (Ins)</th>
						<th width="1%">Branch</th>
						<th width="1%">Cancel Member (days)</th>
						<th width="1%">Option</th>
					</tr>
					</thead>
					<tbody>';
                while ($metKlaim_ = mysql_fetch_array($metKlaim)) {
                    $_metCN = mysql_fetch_array($database->doQuery('SELECT id, idpeserta, status FROM ajkcreditnote WHERE idpeserta="'.$metKlaim_['idm'].'" AND del IS NULL'));
                    if ($_metCN['id']) {
                        $met_btn = '<td align="center"><span class="label label-primary">'.$_metCN['status'].'</span></td>';
                    } else {
                        $met_btn = '<td align="center"><a href="ajk.php?re=cclaim&cc=newClaimCancel&idm='.$thisEncrypter->encode($metKlaim_['idm']).'">'.BTN_CANCEL.'</a></td>';
                    }
                    $metSRC .= '<tr><td align="center">'.++$no.'</td>
						<td>'.$metKlaim_['brokerclient'].'</td>
						<td>'.$metKlaim_['produk'].'</td>
						<td>'.$metKlaim_['nomordebitnote'].'</td>
						<td align="center">'._convertDate($metKlaim_['tgldebitnote']).'</td>
						<td align="center">'.$metKlaim_['idpeserta'].'</td>
						<td>'.$metKlaim_['nama'].'</td>
						<td align="center">'.$metKlaim_['usia'].'</td>
						<td align="right">'.duit($metKlaim_['plafond']).'</td>
						<td align="center">'._convertDate($metKlaim_['tglakad']).'</td>
						<td align="center">'.$metKlaim_['tenor'].'</td>
						<td align="center">'._convertDate($metKlaim_['tglakhir']).'</td>
						<td align="right">'.duit($metKlaim_['totalpremi']).'</td>
						<td align="right">'.duit($metKlaim_['astotalpremi']).'</td>
						<td align="center">'.$metKlaim_['cabang'].'</td>
						<td align="center"><button type="button" class="btn btn-inverse btn-rounded btn-xs mb5"><strong>'.duit($metKlaim_['jumlahharibatal']).'</strong></button> -
										   <button type="button" class="btn btn-info btn-rounded btn-xs mb5"><strong>'.duit($metKlaim_['jumlahhari']).'</strong></button>
						</td>
						'.$met_btn.'
					</tr>';
                }
                //$metSRC = $_REQUEST['idmember'].'<br />'.$_REQUEST['name'].'<br />';
                $metSRC .= '</tbody>
					<tfoot>
					<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Member"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
					</tr>
					</tfoot></table>
					</div>
				</div>
			</div>
		</div>';
            }
        }
        echo '<div class="row">
			<div class="col-md-12">
			'.$metnotif.'
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">New Form Cancel Member</h3></div>
				<div class="panel-body">
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">ID Member</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="idmember" class="form-control" data-parsley-type="number" value="'.$_REQUEST['idmember'].'" placeholder="ID Member"/></div></div>
						</div>
			        </div>
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">Name</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="name" class="form-control" value="'.$_REQUEST['name'].'" placeholder="Name"/></div></div>
						</div>
			        </div>
		        </div>
				<div class="panel-footer"><input type="hidden" name="src" value="claimdatacancel">'.BTN_SUBMIT.'</div>
		    </form>
		    </div>
			</div>';
        echo $metSRC;
        echo '</div>
			</div>';
            ;
    break;

    case "reqBatal":
        echo '<div class="page-header-section"><h2 class="title semibold">Cancel Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc='.$_REQUEST['back'].'">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
			<th width="1%">Broker</th>
			<th>Partner</th>
			<th>Product</th>
			<th>Asuransi</th>
			<th>ID Member</th>
			<th>Name</th>
			<th>Date Cancel</th>
			<th>Premium Cancel</th>
			<th>Status</th>
			<th>Staff</th>
			<th>Input Date</th>
			<th>SPV</th>
			<th>Approve Date</th>
		</tr>
		</thead>
		<tbody>';
        $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS broker,
		ajkclient.`name` AS client,
		ajkpolis.produk,
		ajkinsurance.`name` AS asuransi,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.input_by,
		ajkcreditnote.status AS statusklaim,
		ajkcreditnote.tipeklaim,
		DATE_FORMAT(ajkcreditnote.input_time,"%Y-%m-%d") AS tglinput,
		spvinput.firstname AS userstaff,
		DATE_FORMAT(ajkcreditnote.approve_time,"%Y-%m-%d") AS tglapprove,
		spvapprove.firstname AS userspv
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN useraccess AS spvinput ON ajkcreditnote.input_by = spvinput.id
		INNER JOIN useraccess AS spvapprove ON ajkcreditnote.approve_by = spvapprove.id
		WHERE ajkcreditnote.status = "Approve" AND
			  ajkcreditnote.del IS NULL '.$q___1.'  AND
			  ajkcreditnote.tipeklaim = "Batal"
		ORDER BY ajkcreditnote.id DESC');
                while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
                    echo '<tr>
		   	<td align="center">'.++$no.'</td>
		   	<td>'.$metCreditnote_['broker'].'</td>
		   	<td>'.$metCreditnote_['client'].'</td>
		   	<td>'.$metCreditnote_['produk'].'</td>
		   	<td>'.$metCreditnote_['asuransi'].'</td>
		   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
		   	<td><a href="ajk.php?re=cclaim&cc=reqBatalData&id='.$thisEncrypter->encode($metCreditnote_['id']).'">'.$metCreditnote_['nama'].'</a></td>
		   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
		   	<td>'.duit($metCreditnote_['nilaiclaimclient']).'</td>
		   	<td>'.$metCreditnote_['statusklaim'].'</td>
		   	<td>'.$metCreditnote_['userstaff'].'</td>
		   	<td>'._convertDate($metCreditnote_['tglinput']).'</td>
		   	<td>'.$metCreditnote_['userspv'].'</td>
		   	<td>'._convertDate($metCreditnote_['tglapprove']).'</td>
		    </tr>';
                }
                echo '</tbody>
				<tfoot>
		        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="ID Member"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		        </tr>
		        </tfoot></table>
		    	</div>
				</div>
		    </div>
		</div>';
                ;
    break;

    case "reqBatalData":
        echo '<div class="page-header-section"><h2 class="title semibold">Cancel Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqBatal">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idpeserta AS idmember,
		ajkcreditnote.idproduk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkinsurance.`name` AS asuransi,
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
		ajkpeserta.statusaktif,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.tglklaim,
		ajkcreditnote.keterangan,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.nilaiclaimdibayar
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkcreditnote.id = "'.$thisEncrypter->decode($_REQUEST['id']).'"'));
        if ($_REQUEST['approvebatal']=="DataApprovBatal") {
            /*	echo $_REQUEST['idbroker'].'<br />';
                echo $_REQUEST['idpeserta'].'<br />';
                echo $_REQUEST['cnID'].'<br />'; */
            $metCN = mysql_fetch_array($database->doQuery('SELECT Max(idcn) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idbroker'].'" AND del IS NULL'));
            if ($_REQUEST['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idbroker'];
            } else {
                $kodeBroker = $_REQUEST['idbroker'];
            }
            $fakcekcn = $metCN['idcn'] + 1;
            $idNumber = 100000000 + $fakcekcn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //
            $creditnoteNumber = "CN.B".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
            $metCreateBtl = $database->doQuery('UPDATE ajkcreditnote SET idcn="'.$fakcekcn.'",
												   					 nomorcreditnote="'.$creditnoteNumber.'",
												   					 tglcreditnote="'.$futoday.'",
												   					 status="Batal",
												   					 create_by = "'.$q['id'].'",
												   					 create_time = "'.$futgl.'"
												   					 WHERE id="'.$_REQUEST['cnID'].'"');
            $metDebBatal = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$_REQUEST['cnID'].'", statusaktif="Batal", statuspeserta="Batal" WHERE id="'.$_REQUEST['idpeserta'].'"');
            echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim&cc=ncancel">
			  <div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Success!</strong> Data debitur has been canceled by '.$q['username'].'</div>';
        }
        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<input type="hidden" name="idbroker" value="'.$metData['idbroker'].'">
				<input type="hidden" name="idpeserta" value="'.$metData['idmember'].'">
				<input type="hidden" name="cnID" value="'.$metData['id'].'">
				<div class="panel-heading"><h3 class="panel-title">Data Cancel Member</h3></div>
				<div class="panel-body">
					<div class="alert alert-dismissable alert-success text-center">
					<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
					</div>
					<div class="col-md-7">
					<dl class="dl-horizontal">
			        	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
			            <dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
			            <dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
			            <dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
			            <dt>Age</dt><dd>'.$metData['usia'].' years</dd>
			            <dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
						<dt>Cancel Date</dt><dd><strong>'._convertDate($metData['tglklaim']).'</strong></dd>
						<dt>Cancel Note</dt><dd><strong>'.$metData['keterangan'].'</strong></dd>
					</dl>
					</div>
					<div class="col-md-5">
					<dl class="dl-horizontal">
						<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					    <dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					    <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
					    <dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					    <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					    <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
					    <dt>Current Cancel Date</dt><dd>';
            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            echo $wordyear.' '.$wordmonth.' '.$wordday;
            $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
            //echo '<br />'.$thnbln;
            //echo '<br />'.$bulanjalan;
            echo '</dd>
					<dd>
				<dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimdibayar']).'</span></dd>
							</dl>
						</div>
					</div>
			<div class="panel-footer"><input type="hidden" name="approvebatal" value="DataApprovBatal">'.BTN_SUBMIT.'</div>

				</form>
				</div>';
                echo '</div>
			</div>';

            ;
    break;

    case "uplDokumenClaimGeneral":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		      	<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqClaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
                $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idproduk,
		ajkcreditnote.idas,
		ajkcreditnote.idaspolis,
		ajkcreditnote.idpeserta,
		ajkcreditnote.idregional,
		ajkcreditnote.idcabang,
		ajkcreditnote.iddn,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.`status`,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.tglklaimloss,
		ajkcreditnote.tipeklaimgeneral,
		ajkcreditnote.ketklaimgeneral,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkdebitnote.tgldebitnote,
		ajkinsurance.`name` AS insurancename,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.idpeserta AS pesertaid,
		ajkpeserta.tgllahir,
		ajkpeserta.usia,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkpeserta.statusaktif,
		ajkdebitnote.nomordebitnote,
		ajkregional.`name` AS regional,
		ajkcabang.`name` AS cabang
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
		INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.idpeserta = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['tempatmeninggal'].'"'));
        $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['penyebabmeninggal'].'"'));

                if ($metData['tipeklaimgeneral']=="FIRE") {
                    $metDateKlaimloss = explode(" ", $metData['tglklaimloss']);
                    $_setClaimGen ='<dt>Date and Time of Loss</dt><dd>'._convertDate($metDateKlaimloss[0]).' '.$metDateKlaimloss[1].'</dd>
										<dt>Cause of Loss</dt><dd>'.$metData['ketklaimgeneral'].'</dd>
										<dt>Estimated Claim Value</dt><dd>'.duit($metData['nilaiclaimclient']).'</dd>';
                } elseif ($metData['tipeklaimgeneral']=="AJK") {
                    $_setClaimGen = '<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
									<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
									<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>';
                    $_setClaimGenVil .= '<dt>Current Date Claim</dt>
							        <dd>';
                    $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
                    $monthins = explode(",", $monthins);
                    //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
                    if ($monthins[0]>1) {
                        $wordyear = "".$monthins[0]." years";
                    } else {
                        $wordyear = "".$monthins[0]." year";
                    }
                    if ($monthins[1]>1) {
                        $wordmonth = "".$monthins[1]." months";
                    } else {
                        $wordmonth = "".$monthins[1]." month";
                    }
                    if ($monthins[1]>1) {
                        $wordday = "".$monthins[2]." days";
                    } else {
                        $wordday = "".$monthins[2]." day";
                    }
                    $_setClaimGenVil .= $wordyear.' '.$wordmonth.' '.$wordday;
                    $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
                    //echo '<br />'.$thnbln;
                    //echo '<br />'.$bulanjalan;
                    $_setClaimGenVil .= '</dd>
							            <dt>Current Month</dt>
							            <dd>';
                    if ($monthins[2] > 0) {
                        $bulanjalan = $monthins[1] + 1;
                    } else {
                        $bulanjalan = $monthins[1];
                    }
                    $blnberjalan = $thnbln + $bulanjalan;
                    if ($blnberjalan > 1) {
                        $blnberjalan_ = $blnberjalan.' months';
                    } else {
                        $blnberjalan_ = $blnberjalan.' month';
                    }
                    $_setClaimGenVil .= $blnberjalan_;
                    $_setClaimGenVil .= '</dd>';
                } else {
                    $_setClaimGen = '<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
									<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
									<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
									<dt>Date and Time of Loss</dt><dd>'._convertDate($metDateKlaimloss[0]).' '.$metDateKlaimloss[1].'</dd>
									<dt>Cause of Loss</dt><dd>'.$metData['ketklaimgeneral'].'</dd>
									<dt>Estimated Claim Value</dt><dd>'.duit($metData['nilaiclaimclient']).'</dd>';
                    $_setClaimGenVil .= '<dt>Current Date Claim</dt>
							        <dd>';
                    $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
                    $monthins = explode(",", $monthins);
                    //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
                    if ($monthins[0]>1) {
                        $wordyear = "".$monthins[0]." years";
                    } else {
                        $wordyear = "".$monthins[0]." year";
                    }
                    if ($monthins[1]>1) {
                        $wordmonth = "".$monthins[1]." months";
                    } else {
                        $wordmonth = "".$monthins[1]." month";
                    }
                    if ($monthins[1]>1) {
                        $wordday = "".$monthins[2]." days";
                    } else {
                        $wordday = "".$monthins[2]." day";
                    }
                    $_setClaimGenVil = $wordyear.' '.$wordmonth.' '.$wordday;
                    $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
                    //echo '<br />'.$thnbln;
                    //echo '<br />'.$bulanjalan;
                    $_setClaimGenVil .= '</dd>
							            <dt>Current Month</dt>
							            <dd>';
                    if ($monthins[2] > 0) {
                        $bulanjalan = $monthins[1] + 1;
                    } else {
                        $bulanjalan = $monthins[1];
                    }
                    $blnberjalan = $thnbln + $bulanjalan;
                    if ($blnberjalan > 1) {
                        $blnberjalan_ = $blnberjalan.' months';
                    } else {
                        $blnberjalan_ = $blnberjalan.' month';
                    }
                    $_setClaimGenVil .= $blnberjalan_;
                    $_setClaimGenVil .= '</dd>';
                }
                echo '<div class="row">
				<div class="col-md-12">
				<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" enctype="multipart/form-data" data-parsley-validate>
					<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
						<div class="panel-body">
						<div class="alert alert-dismissable alert-success text-center">
						<input type="hidden" name="idm" value="'.$metData['idpeserta'].'">
						<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
						</div>
						<div class="col-md-7">
						<dl class="dl-horizontal">
			               	<dt>Type Claim</dt><dd>'.$metData['tipeklaimgeneral'].'</dd>
			               	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
			               	<dt>ID Member</dt><dd>'.$metData['pesertaid'].'</dd>
			               	<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
			               	<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
			               	<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
			               	<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
							'.$_setClaimGen.'
						</dl>
					</div>
					<div class="col-md-5">
						<dl class="dl-horizontal">
					       	<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					       	<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					        <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
							<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					        <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					        <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
							'.$_setClaimGenVil.'
						  	<dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimclient']).'</span></dd>
						  	<dt>Status Claim</dt><dd><span class="semibold text-danger">'.$metData['status'].'</span></dd>
						</dl>
					</div>
				</div>';


                echo '<div class="panel-body">
					<table class="table table-hover table-bordered">
				    <thead>
				    <tr><th>Document Claim</th>
				        <th>File Upload</th>
				    </tr>
				    </thead>
				    <tbody>';
                if ($_REQUEST['docClaim']=="uploadclaim") {
                    //echo $thisEncrypter->decode($_REQUEST['idm']).'-'.$thisEncrypter->decode($_REQUEST['iddoc']);
                    if ($_REQUEST['met']=="uploadDokClam_") {
                        //echo $_FILES['documentClaim']['name'];
                        $filenameclaim = 'CLAIM_'.$_REQUEST['idm'].'_'.$thisEncrypter->decode($_REQUEST['iddoc']).'_'.$_FILES['documentClaim']['name'];
                        echo $filenameclaim.'<br />';
                        $metClaim = $database->doQuery('UPDATE ajkdocumentclaimmember SET fileklaim="'.$filenameclaim.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"');
                        $KlaimTemp = $_FILES['documentClaim']['tmp_name'];
                        $dirKlaim = '../'.$PathDokumen.''.$filenameclaim; // direktori tempat menyimpan file
                        move_uploaded_file($KlaimTemp, $dirKlaim);
                        header("location:ajk.php?re=cclaim&cc=uplDokumenClaimGeneral&idm=".$thisEncrypter->encode($_REQUEST['idm'])."");
                    }
                    /*
                       <form name="but1" method="post" action="#" data-parsley-validate enctype="multipart/form-data">
                       <div class="col-sm-6" align="right"><input type="file" name="documentClaim" accept="application/pdf" required></div>
                       <input type="hidden" name="id" value="'.$thisEncrypter->decode($_REQUEST['id']).'">
                       <input type="hidden" name="met" value="uploadDokClam_">'.BTN_SUBMIT.'
                       </form>
                    */
                    echo '<form method="post" action="#" data-parsley-validate enctype="multipart/form-data">
						  <div class="col-sm-6" align="right"><input type="file" name="documentClaim" accept="application/pdf" required></div>
						  <input type="hidden" name="met" value="uploadDokClam_">'.BTN_SUBMIT.'</div>
						  </form>';
                }
                $metDok = $database->doQuery('SELECT ajkdocumentclaimmember.id,
											 ajkdocumentclaimmember.iddoc,
											 ajkdocumentclaimmember.idmember,
											 ajkdocumentclaimmember.fileklaim,
											 ajkdocumentclaimpartner.iddoc,
											 ajkdocumentclaim.namadokumen
											 FROM ajkdocumentclaimmember
											 INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
											 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
											 WHERE ajkdocumentclaimmember.idmember = "'.$metData['idpeserta'].'"
											 ORDER BY ajkdocumentclaim.id ASC');
                while ($metDok_ = mysql_fetch_array($metDok)) {
                    if ($metDok_['fileklaim'] != null) {
                        $_metUplaod = '<a href="../'.$PathDokumen.''.$metDok_['fileklaim'].'" target="_blank">'.BTN_VIEW.'</a>';
                    } else {
                        $_metUplaod = '<a href="ajk.php?re=cclaim&cc=uplDokumenClaimGeneral&idm='.$thisEncrypter->encode($metData['idpeserta']).'&docClaim=uploadclaim&iddoc='.$thisEncrypter->encode($metDok_['id']).'">'.BTN_UPLOADCLAIM.'</a>';
                    }
                    echo '<tr><td width="80%">'.$metDok_['namadokumen'].'</td>
					  	  <td>'.$_metUplaod.'</td>
					  </tr>';
                }
                echo '</tbody>
						</table>
							</div>
						</div>
					</div>
				</div>
				</form>';
                echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
					  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                ;
    break;

    case "setReqClaimGeneral":
        foreach ($_REQUEST['dokKlaim'] as $key => $val) {
            $docKlaim = $database->doQuery('INSERT INTO ajkdocumentclaimmember SET iddoc="'.$val.'", idmember="'.$_REQUEST['idm'].'"');
        }
        header('location:ajk.php?re=cclaim&cc=uplDokumenClaimGeneral&idm='.$thisEncrypter->encode($_REQUEST['idm']).'');
            ;
    break;

    case "uplClaimGeneral":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idproduk,
		ajkcreditnote.idas,
		ajkcreditnote.idaspolis,
		ajkcreditnote.idpeserta,
		ajkcreditnote.idregional,
		ajkcreditnote.idcabang,
		ajkcreditnote.iddn,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.`status`,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.tglklaimloss,
		ajkcreditnote.tipeklaimgeneral,
		ajkcreditnote.ketklaimgeneral,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkdebitnote.tgldebitnote,
		ajkinsurance.`name` AS insurancename,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.idpeserta AS pesertaid,
		ajkpeserta.tgllahir,
		ajkpeserta.usia,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkpeserta.statusaktif,
		ajkdebitnote.nomordebitnote,
		ajkregional.`name` AS regional,
		ajkcabang.`name` AS cabang
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
		INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.idpeserta = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['tempatmeninggal'].'"'));
        $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['penyebabmeninggal'].'"'));
        if ($metData['tipeklaimgeneral']=="FIRE") {
            $metDateKlaimloss = explode(" ", $metData['tglklaimloss']);
            $_setClaimGen ='<dt>Date and Time of Loss</dt><dd>'._convertDate($metDateKlaimloss[0]).' '.$metDateKlaimloss[1].'</dd>
							<dt>Cause of Loss</dt><dd>'.$metData['ketklaimgeneral'].'</dd>
							<dt>Estimated Claim Value</dt><dd>'.duit($metData['nilaiclaimclient']).'</dd>';
        } elseif ($metData['tipeklaimgeneral']=="AJK") {
            $_setClaimGen = '<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
							<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
							<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>';
            $_setClaimGenVil .= '<dt>Current Date Claim</dt>
					        <dd>';
            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            $_setClaimGenVil .=  $wordyear.' '.$wordmonth.' '.$wordday;
            $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
            $_setClaimGenVil .= '</dd>
					            <dt>Current Month</dt>
					            <dd>';
            if ($monthins[2] > 0) {
                $bulanjalan = $monthins[1] + 1;
            } else {
                $bulanjalan = $monthins[1];
            }
            $blnberjalan = $thnbln + $bulanjalan;
            if ($blnberjalan > 1) {
                $blnberjalan_ = $blnberjalan.' months';
            } else {
                $blnberjalan_ = $blnberjalan.' month';
            }
            //echo $blnberjalan_;
            $_setClaimGenVil .= $blnberjalan_;
            $_setClaimGenVil .= '</dd>';
        } elseif ($metData['tipeklaimgeneral']=="PHK") {
            $metDateKlaimloss = explode(" ", $metData['tglklaim']);
            $_setClaimGen ='<dt>Date of PHK</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
								<dt>Note</dt><dd>'.$metData['ketklaimgeneral'].'</dd>';
        } elseif ($metData['tipeklaimgeneral']=="KREDIT MACET") {
            $metDateKlaimloss = explode(" ", $metData['tglklaim']);
            $_setClaimGen ='<dt>Date of Bad Credit</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
								<dt>Note</dt><dd>'.$metData['ketklaimgeneral'].'</dd>';
        } else {
            $_setClaimGen = '<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
							<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
							<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
							<dt>Date and Time of Loss</dt><dd>'._convertDate($metDateKlaimloss[0]).' '.$metDateKlaimloss[1].'</dd>
							<dt>Cause of Loss</dt><dd>'.$metData['ketklaimgeneral'].'</dd>
							<dt>Estimated Claim Value</dt><dd>'.duit($metData['nilaiclaimclient']).'</dd>';
            $_setClaimGenVil = '<dt>Current Date Claim</dt>
					        <dd>';
            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            $_setClaimGenVil =  $wordyear.' '.$wordmonth.' '.$wordday;
            $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
            //echo '<br />'.$thnbln;
            //echo '<br />'.$bulanjalan;
            $_setClaimGenVil =  '</dd>
				<dt>Current Month</dt>
				<dd>';
            if ($monthins[2] > 0) {
                $bulanjalan = $monthins[1] + 1;
            } else {
                $bulanjalan = $monthins[1];
            }
            $blnberjalan = $thnbln + $bulanjalan;
            if ($blnberjalan > 1) {
                $blnberjalan_ = $blnberjalan.' months';
            } else {
                $blnberjalan_ = $blnberjalan.' month';
            }
            //echo $blnberjalan_;
            $_setClaimGenVil =  '</dd>';
        }
        echo '<div class="row">
				<div class="col-md-12">
				<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
					<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
						<div class="panel-body">
						<div class="alert alert-dismissable alert-success text-center">
						<input type="hidden" name="idm" value="'.$metData['idpeserta'].'">
						<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
						</div>
						<div class="col-md-7">
						<dl class="dl-horizontal">
			               	<dt>Type Claim</dt><dd>'.$metData['tipeklaimgeneral'].'</dd>
			               	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
			               	<dt>ID Member</dt><dd>'.$metData['pesertaid'].'</dd>
			               	<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
			               	<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
			               	<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
			               	<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
							'.$_setClaimGen.'
						</dl>
					</div>
					<div class="col-md-5">
						<dl class="dl-horizontal">
					       	<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					       	<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					        <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
							<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					        <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					        <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
							'.$_setClaimGenVil.'
						  	<dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimclient']).'</span></dd>
						  	<dt>Status Claim</dt><dd><span class="semibold text-danger">'.$metData['status'].'</span></dd>
						</dl>
					</div>
				</div>';
        echo '<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
			<div class="panel-body">
			<table class="table">
			<tbody>';
            if ($tmptDeath['nama']=="HOSPITAL") {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Police" AND ';
            } elseif ($tmptDeath['nama']=="HOME") {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Hospital" AND ajkdocumentclaim.opsional !="Police" AND ';
            } else {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Hospital" AND ';
            }
            $metDok = $database->doQuery('SELECT ajkdocumentclaimpartner.id,
												 ajkdocumentclaimpartner.idbroker,
												 ajkdocumentclaimpartner.idclient,
												 ajkdocumentclaimpartner.idpolicy,
												 ajkdocumentclaim.namadokumen,
												 ajkdocumentclaim.opsional
												 FROM ajkdocumentclaimpartner
												 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
												 WHERE ajkdocumentclaimpartner.idbroker="'.$metData['idbroker'].'" AND
												 	   ajkdocumentclaimpartner.idclient="'.$metData['idclient'].'" AND
												 	   ajkdocumentclaimpartner.idpolicy="'.$metData['idproduk'].'" AND
												 	   '.$dokumenPOD.'
												 	   ajkdocumentclaimpartner.del IS NULL
												 ORDER BY ajkdocumentclaimpartner.iddoc ASC');
        $no=1;
        while ($metDok_ = mysql_fetch_array($metDok)) {
            echo '<tr><td width="1%">
				<div class="checkbox custom-checkbox nm">
				<input type="checkbox" name="dokKlaim[]" id="customcheckbox'.$no.'" value="'.$metDok_['id'].'" checked>
				<label for="customcheckbox'.$no.'"></label>
				</div>
			</td>
			<td>'.$metDok_['namadokumen'].'</td>
			</tr>';
            $no++;
        }
        echo '</tbody>
			</table>
			</div>
			<div class="panel-footer"><input type="hidden" name="cc" value="setReqClaimGeneral">'.BTN_SUBMIT.'</div>
				</div>
				</form>';
        echo '</div>';
            ;
    break;

    case "generalclaim":
        $metKlaim = mysql_fetch_array($database->doQuery('SELECT ajkpeserta.id,
																 ajkpeserta.idpeserta,
																 ajkpeserta.idbroker,
																 ajkpeserta.idclient,
																 ajkpeserta.idpolicy AS produk,
																 ajkpeserta.iddn,
																 ajkdebitnote.idas,
																 ajkdebitnote.idaspolis,
																 ajkpeserta.nama
															FROM ajkpeserta
															INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
															WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        echo '<div class="alert alert-info fade in">
		        <h4 class="semibold">Claim created!</h4>
				<p class="mb10">Data claims on behalf of <strong>'.$metKlaim['nama'].' ('.$metKlaim['idpeserta'].')</strong> has been created.</p>
		        <a href="ajk.php?re=cclaim"><button type="button" class="btn btn-success">Go to Creditnote</button></a>
		        <a href="ajk.php?re=cclaim&cc=uplClaimGeneral&idm='.$thisEncrypter->encode($metKlaim['id']).'"><button type="button" class="btn btn-danger">Upload Document Claim</button></a>
		      </div>';
            ;
    break;

    case "setClaimGeneralDokumen":
        /*
        echo 'ok<br />';
            echo $thisEncrypter->decode($_REQUEST['idm']).'<br />';
            echo $_REQUEST['tipeklaim'].'<br />';
            echo $_REQUEST['tmptmeninggal'].'<br />';
            echo $_REQUEST['penyebabmeninggal'].'<br />';
            echo $_REQUEST['dod'].'<br />';
            echo $_REQUEST['dodloss'].'<br />';
            echo $_REQUEST['keterangan'].'<br />';
            echo $_REQUEST['nilaiklaim'].'<br />';
            echo $_REQUEST['photo1'].'<br />';
            echo $_REQUEST['photo2'].'<br />';
            echo $_REQUEST['photo3'].'<br />';
        */
        $metKlaim = mysql_fetch_array($database->doQuery('SELECT ajkpeserta.id AS idpeserta,
																 ajkpeserta.idbroker,
																 ajkpeserta.idclient,
																 ajkpeserta.idpolicy AS produk,
																 ajkpeserta.iddn,
																 ajkdebitnote.idas,
																 ajkdebitnote.idaspolis,
																 ajkpeserta.regional,
																 ajkpeserta.cabang
															FROM ajkpeserta
															INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
															WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        //echo $metKlaim['idpeserta'].' - '.$metKlaim['idclient'];
        if ($_REQUEST['tipeklaim']=="fire") {
            $tglclaimfire = explode(" ", $_REQUEST['dodloss']);
            $_cliamajkfire = 'tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
							  tipeklaimgeneral="'.strtoupper($_REQUEST['tipeklaim']).'",
							  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
							  nilaiclaimclient="'.$_REQUEST['nilaiklaim'].'",';
        } elseif ($_REQUEST['tipeklaim']=="ajk") {
            $_cliamajkfire = 'tempatmeninggal="'.$_REQUEST['tmptmeninggal'].'",
							  penyebabmeninggal="'.$_REQUEST['penyebabmeninggal'].'",
							  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",';
        } else {
            $tglclaimfire = explode(" ", $_REQUEST['dodloss']);
            $_cliamajkfire = 'tempatmeninggal="'.$_REQUEST['tmptmeninggal'].'",
							  penyebabmeninggal="'.$_REQUEST['penyebabmeninggal'].'",
							  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",
							  tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
							  tipeklaimgeneral="'.strtoupper($_REQUEST['tipeklaim']).'",
							  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
							  nilaiclaimclient="'.$_REQUEST['nilaiklaim'].'",';
        }
        $metClaim_ = $database->doQuery('INSERT INTO ajkcreditnote SET idbroker="'.$metKlaim['idbroker'].'",
																	   idclient="'.$metKlaim['idclient'].'",
																	   idproduk="'.$metKlaim['produk'].'",
																	   idas="'.$metKlaim['idas'].'",
																	   idaspolis="'.$metKlaim['idaspolis'].'",
																	   idpeserta="'.$metKlaim['idpeserta'].'",
																	   idregional="'.$metKlaim['regional'].'",
																	   idcabang="'.$metKlaim['cabang'].'",
																	   iddn="'.$metKlaim['iddn'].'",
																	   '.$_cliamajkfire.'
																	   status="Request",
																	   tipeklaim="Claim",
																	   input_by="'.$q['id'].'",
																	   input_time="'.$futgl.'"');
        if ($_REQUEST['photo1']) {
            $metPhotoClaim_ = $database->doQuery('INSERT INTO ajkphotoklaim SET idpeserta="'.$metKlaim['idpeserta'].'", photo="'.$_REQUEST['photo1'].'", type="kejadian", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
        } else {
        }
        if ($_REQUEST['photo2']) {
            $metPhotoClaim_ = $database->doQuery('INSERT INTO ajkphotoklaim SET idpeserta="'.$metKlaim['idpeserta'].'", photo="'.$_REQUEST['photo2'].'", type="kejadian", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
        } else {
        }
        if ($_REQUEST['photo3']) {
            $metPhotoClaim_ = $database->doQuery('INSERT INTO ajkphotoklaim SET idpeserta="'.$metKlaim['idpeserta'].'", photo="'.$_REQUEST['photo3'].'", type="kejadian", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
        } else {
        }
        header('location:ajk.php?re=cclaim&cc=generalclaim&idm='.$thisEncrypter->encode($metKlaim['idpeserta']).'');
            ;
    break;

    case "setClaimGeneralCancel":
        unlink('../'.$PathPhotoGeneral.'/'.$_REQUEST['p1']);
        unlink('../'.$PathPhotoGeneral.'/'.$_REQUEST['p2']);
        unlink('../'.$PathPhotoGeneral.'/'.$_REQUEST['p3']);
        header('location:ajk.php?re=cclaim&cc=nclaim');
            ;
    break;

    case "setClaimGeneral":
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkdebitnote.idas,
		ajkdebitnote.idaspolis,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.tgldebitnote,
		ajkcabang.`name` AS cabang,
		ajkpeserta.id,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
		ajkpeserta.regional,
		ajkpeserta.cabang,
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
		ajkpeserta.paketasuransi,
		ajkpeserta.okupasi,
		ajkpeserta.kelas,
		ajkpeserta.lokasi,
		ajkpeserta.alamatobjek,
		ajkpeserta.premifire,
		ajkpeserta.premipa,
		ajkpeserta.nilaijaminan,
		ajkpeserta.statusaktif
		FROM ajkpeserta
		INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=newClaim&idm='.$thisEncrypter->encode($metData['id']).'">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        /*
        echo $thisEncrypter->decode($_REQUEST['idm']).'<br />';
        echo $_REQUEST['tipeklaim'].'<br />';
        echo $_REQUEST['tmptmeninggal'].'<br />';
        echo $_REQUEST['penyebabmeninggal'].'<br />';
        echo $_REQUEST['dod'].'<br />';
        echo $_REQUEST['dodtime'].'<br />';
        echo $_REQUEST['keterangan'].'<br />';
        echo $_REQUEST['nilaiklaim'].'<br />';
        echo $_FILES['uploadphoto1']['name'].'<br />';
        echo $_FILES['uploadphoto2']['name'].'<br />';
        */


        if ($_FILES['uploadphoto1']['size'] / 1024 > $FILESIZE_2 or
            $_FILES['uploadphoto2']['size'] / 1024 > $FILESIZE_2 or
            $_FILES['uploadphoto3']['size'] / 1024 > $FILESIZE_2) {
            echo '<div class="alert alert-dismissable alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
						<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
		           	</div>';
        } else {
            if ($_FILES['uploadphoto1']!="") {
                $nama_file = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto1']['name']);
                $file_type = $_FILES['uploadphoto1']['type']; //tipe file
                $source = $_FILES['uploadphoto1']['tmp_name'];
                $direktori = "../$PathPhotoGeneral/$nama_file"; // direktori tempat menyimpan file
                move_uploaded_file($source, $direktori);
                gambar_kecil($direktori, $file_type);
            } else {
            }

            if ($_FILES['uploadphoto2']!="") {
                $nama_file = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto2']['name']);
                $file_type = $_FILES['uploadphoto2']['type']; //tipe file
                $source = $_FILES['uploadphoto2']['tmp_name'];
                $direktori = "../$PathPhotoGeneral/$nama_file"; // direktori tempat menyimpan file
                move_uploaded_file($source, $direktori);
                gambar_kecil($direktori, $file_type);
            } else {
            }

            if ($_FILES['uploadphoto3']!="") {
                $nama_file = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto3']['name']);
                $file_type = $_FILES['uploadphoto3']['type']; //tipe file
                $source = $_FILES['uploadphoto3']['tmp_name'];
                $direktori = "../$PathPhotoGeneral/$nama_file"; // direktori tempat menyimpan file
                move_uploaded_file($source, $direktori);
                gambar_kecil($direktori, $file_type);
            } else {
            }
        }

        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" enctype="multipart/form-data" data-parsley-validate>
			<input type="hidden" name="idm" value="'.$thisEncrypter->encode($metData['id']).'">
			<input type="hidden" name="tipeklaim" value="'.$_REQUEST['tipeklaim'].'">
			<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
				<div class="panel-body">
					<div class="alert alert-dismissable alert-success text-center">
					<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
					</div>
					<div class="col-md-7">
					<dl class="dl-horizontal">
						<dt>Type Klaim</dt><dd><strong>'.strtoupper($_REQUEST['tipeklaim']).'</strong></dd>
						<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
						<dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
						<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
						<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
						<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
						<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>

					</dl>
					</div>
					<div class="col-md-5">
					<dl class="dl-horizontal">
						<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
						<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
						<dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
						<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
						<dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
						<dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
					</dl>
					</div>';

            $metPaket = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Paket Asuransi" AND kode="'.$metData['paketasuransi'].'"'));
            $metOkupasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Okupasi" AND kode="'.$metData['okupasi'].'"'));
            $metKelas = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Kelas" AND kode="'.$metData['kelas'].'"'));
            $metLokasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Lokasi" AND kode="'.$metData['lokasi'].'"'));
        echo '<div class="col-md-7">
				<dl class="dl-horizontal">
					<dt>Paket Insurance</dt><dd>'.$metPaket['keterangan'].'</dd>
					<dt>Okupasi</dt><dd>'.$metOkupasi['keterangan'].'</dd>
					<dt>Kelas</dt><dd>'.$metKelas['keterangan'].'</dd>
					<dt>Location</dt><dd><strong>'.$metLokasi['keterangan'].'</strong></dd>
					<dt>Address</dt><dd><strong>'.$metData['alamatobjek'].'</strong></dd>
				</dl>
			</div>
			<div class="col-md-5">
				<dl class="dl-horizontal">
					<dt>Nilai Jaminan</dt><dd>'.duit($metData['nilaijaminan']).'</dd>
					<dt>Premuim Fire</dt><dd>'.duit($metData['premifire']).'</dd>
					<dt>Premium PA</dt><dd>'.duit($metData['premipa']).'</dd>
				</dl>
			</div>
		</div>';

        if ($_REQUEST['tipeklaim']=="fire") {
            echo '<input type="hidden" name="dodloss" value="'.$_REQUEST['dodtime'].'">';
            echo '<input type="hidden" name="keterangan" value="'.$_REQUEST['keterangan'].'">';
            echo '<input type="hidden" name="nilaiklaim" value="'.$_REQUEST['nilaiklaim'].'">';

            echo '<div class="col-md-12">
			<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Date and Time of Loss</dt><dd>'.$_REQUEST['dodtime'].'</dd>
					<dt>Cause of Loss</dt><dd>'.$_REQUEST['keterangan'].'</dd>
					<dt>Estimated Claim Value</dt><dd>'.duit($_REQUEST['nilaiklaim']).'</dd>
				</dl>
			</div>
			</div>
			<dt>&nbsp;</dt><dd> </dd>
		<div class="panel-heading"><h3 class="panel-title">Photo Claim</h3></div>
		<div class="row" id="shuffle-grid">';
            if ($_FILES['uploadphoto1']!="") {
                $nama_file1 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto1']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file1.'">
		        	<div class="thumbnail">
		            	<div class="media">
		                	<div class="indicator"><span class="spinner"></span></div>
		                    <div class="overlay">
		                    <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file1.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
		                    </div>
		                    <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file1.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file1.'" alt="Photo" width="100%" />
		                </div>
		            </div>
				<input type="hidden" name="photo1" value="'.$nama_file1.'">
		        </div>';
            } else {
            }

            if ($_FILES['uploadphoto2']!="") {
                $nama_file2 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto2']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file2.'">
		        	<div class="thumbnail">
		            	<div class="media">
		                	<div class="indicator"><span class="spinner"></span></div>
		                    <div class="overlay">
		                    <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file2.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
		                    </div>
		                    <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file2.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file2.'" alt="Photo" width="100%" />
		                </div>
		            </div>
		        <input type="hidden" name="photo2" value="'.$nama_file2.'">
				</div>';
            } else {
            }

            if ($_FILES['uploadphoto3']!="") {
                $nama_file3 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto3']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file3.'">
		       	<div class="thumbnail">
		           	<div class="media">
		               	<div class="indicator"><span class="spinner"></span></div>
		                   <div class="overlay">
		                   <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file3.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
		                   </div>
		                   <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file3.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file3.'" alt="Photo" width="100%" />
		               </div>
		           </div>
		       <input type="hidden" name="photo3" value="'.$nama_file3.'">
			   </div>';
            } else {
            }
            echo '</div><br />';
        } elseif ($_REQUEST['tipeklaim']=="ajk") {
            $metPlaceDeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" AND id="'.$_REQUEST['tmptmeninggal'].'"'));
            $metCauseDeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" AND id="'.$_REQUEST['penyebabmeninggal'].'"'));
            echo '<input type="hidden" name="tmptmeninggal" value="'.$_REQUEST['tmptmeninggal'].'">';
            echo '<input type="hidden" name="penyebabmeninggal" value="'.$_REQUEST['penyebabmeninggal'].'">';
            echo '<input type="hidden" name="dod" value="'.$_REQUEST['dod'].'">';
            echo '<div class="col-md-12">
			<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Place of Death</dt><dd>'.$metPlaceDeath['nama'].'</dd>
					<dt>Cause of Death</dt><dd>'.$metCauseDeath['nama'].'</dd>
					<dt>Date of Death</dt><dd>'.$_REQUEST['dod'].'</dd>
				</dl>
			</div>
		</div>
		<dt>&nbsp;</dt><dd> </dd>';
        } else {
            $metPlaceDeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" AND id="'.$_REQUEST['tmptmeninggal'].'"'));
            $metCauseDeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" AND id="'.$_REQUEST['penyebabmeninggal'].'"'));
            echo '<input type="hidden" name="tmptmeninggal" value="'.$_REQUEST['tmptmeninggal'].'">';
            echo '<input type="hidden" name="penyebabmeninggal" value="'.$_REQUEST['penyebabmeninggal'].'">';
            echo '<input type="hidden" name="dod" value="'.$_REQUEST['dod'].'">';
            echo '<input type="hidden" name="dodloss" value="'.$_REQUEST['dodtime'].'">';
            echo '<input type="hidden" name="keterangan" value="'.$_REQUEST['keterangan'].'">';
            echo '<input type="hidden" name="nilaiklaim" value="'.$_REQUEST['nilaiklaim'].'">';
            echo '<div class="col-md-12">
			<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Place of Death</dt><dd>'.$metPlaceDeath['nama'].'</dd>
					<dt>Cause of Death</dt><dd>'.$metCauseDeath['nama'].'</dd>
					<dt>Date of Death</dt><dd>'.$_REQUEST['dod'].'</dd>
					<dt>Date and Time of Loss</dt><dd>'.$_REQUEST['dodtime'].'</dd>
					<dt>Cause of Loss</dt><dd>'.$_REQUEST['keterangan'].'</dd>
					<dt>Estimated Claim Value</dt><dd>'.duit($_REQUEST['nilaiklaim']).'</dd>
				</dl>
			</div>
		</div>
		<dt>&nbsp;</dt><dd> </dd>
		<div class="panel-heading"><h3 class="panel-title">Photo Claim</h3></div>
		<div class="row" id="shuffle-grid">';
            if ($_FILES['uploadphoto1']!="") {
                $nama_file1 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto1']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file1.'">
				<div class="thumbnail">
		        	<div class="media">
		            	<div class="indicator"><span class="spinner"></span></div>
			            	<div class="overlay">
			                <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file1.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
			                </div>
			                <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file1.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file1.'" alt="Photo" width="100%" />
			            </div>
			        </div>
			    <input type="hidden" name="photo1" value="'.$nama_file1.'">
				</div>';
            } else {
            }

            if ($_FILES['uploadphoto2']!="") {
                $nama_file2 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto2']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file2.'">
					        	<div class="thumbnail">
					            	<div class="media">
					                	<div class="indicator"><span class="spinner"></span></div>
					                    <div class="overlay">
					                    <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file2.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
					                    </div>
					                    <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file2.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file2.'" alt="Photo" width="100%" />
					                </div>
					            </div>
					<input type="hidden" name="photo2" value="'.$nama_file2.'">
					        </div>';
            } else {
            }

            if ($_FILES['uploadphoto3']!="") {
                $nama_file3 = strtolower(strtoupper($thisEncrypter->decode($_REQUEST['idm']))."_".$_FILES['uploadphoto3']['name']);
                echo '<div class="col-md-4 shuffle" data-groups=\'["nature"]\' data-date-created="'.date('Ymd').'" data-title="'.$nama_file3.'">
		       	<div class="thumbnail">
		           	<div class="media">
		               	<div class="indicator"><span class="spinner"></span></div>
		                   <div class="overlay">
		                   <div class="toolbar"><a href="../'.$PathPhotoGeneral.'/'.$nama_file3.'" class="btn btn-default magnific" title="view picture"><i class="ico-search"></i></a></div>
		                   </div>
		                   <img data-toggle="unveil" src="../'.$PathPhotoGeneral.'/'.$nama_file3.'" data-src="../'.$PathPhotoGeneral.'/'.$nama_file3.'" alt="Photo" width="100%" />
		               </div>
		           </div>
			<input type="hidden" name="photo3" value="'.$nama_file3.'">
			</div>';
            } else {
            }
            echo '</div><br />';
        }

        echo '<div class="panel-footer">
			<div class="col-md-6 text-right"><a href="ajk.php?re=cclaim&cc=setClaimGeneralCancel&p1='.$nama_file1.'&p2='.$nama_file2.'&p3='.$nama_file3.'">'.BTN_CANCEL.'</a></div>
			<div class="col-md-6"><input type="hidden" name="cc" value="setClaimGeneralDokumen">'.BTN_SUBMIT.'</div>
			</div>
		</div>
		</form>';
        echo '<link rel="stylesheet" href="templates/{template_name}/plugins/magnific/css/magnific.css">
			  <script type="text/javascript" src="templates/{template_name}/javascript/pace.min.js"></script>
		      <script type="text/javascript" src="templates/{template_name}/plugins/magnific/js/jquery.magnific-popup.js"></script>
		      <script type="text/javascript" src="templates/{template_name}/plugins/shuffle/js/jquery.shuffle.js"></script>
		      <script type="text/javascript" src="templates/{template_name}/javascript/backend/pages/media-gallery.js"></script>';
            ;
    break;

    case "klaimgeneral":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=nclaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.general,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.idas,
		ajkdebitnote.idaspolis,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.tgldebitnote,
		ajkcabang.`name` AS cabang,
		ajkinsurance.name AS insurancename,
		ajkpeserta.id,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
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
		ajkpeserta.paketasuransi,
		ajkpeserta.okupasi,
		ajkpeserta.kelas,
		ajkpeserta.lokasi,
		ajkpeserta.alamatobjek,
		ajkpeserta.premifire,
		ajkpeserta.premipa,
		ajkpeserta.nilaijaminan,
		ajkpeserta.regional,
		ajkpeserta.cabang,
		ajkpeserta.statusaktif
		FROM ajkpeserta
		INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		INNER JOIN ajkinsurance ON ajkdebitnote.idas = ajkinsurance.id
		WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate enctype="multipart/form-data">
			<input type="hidden" name="idm" value="'.$thisEncrypter->encode($metData['id']).'">
			<input type="hidden" name="tipe" value="'.$_REQUEST['tipe'].'">
			<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
			<div class="panel-body">
				<div class="col-md-12">
					<dl class="dl-horizontal">
						<dt>Partner</dt><dd><strong>'.strtoupper($metData['clientname']).'</strong></dd>
						<dt>Product</dt><dd>'.$metData['produk'].'</dd>
						<dt>Insurance</dt><dd>'.$metData['insurancename'].'</dd>
						<dt>Claim</dt><dd><strong>'.strtoupper($_REQUEST['tipe']).'</strong></dd>

					</dl>
				</div>
			</div>

			<div class="panel-body pt0 pb0">
				<div class="form-group header bgcolor-default">
		        <div class="col-md-12"><h4 class="semibold text-primary mt0 mb5">Data Debitur</h4></div>
				</div>
			</div>
			<div class="panel-body">
				<div class="col-md-7">
				<dl class="dl-horizontal">
					<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
					<dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
					<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
					<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
					<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
					<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
				</dl>
				</div>
				<div class="col-md-5">
				<dl class="dl-horizontal">
					<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					<dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
					<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					<dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					<dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
				</dl>
				</div>';
                $metPaket = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Paket Asuransi" AND kode="'.$metData['paketasuransi'].'"'));
                $metOkupasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Okupasi" AND kode="'.$metData['okupasi'].'"'));
                $metKelas = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Kelas" AND kode="'.$metData['kelas'].'"'));
                $metLokasi = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE type="Lokasi" AND kode="'.$metData['lokasi'].'"'));
        if ($metData['general']=="Y") {
            echo '<div class="col-md-7">
				<dl class="dl-horizontal">
				<dt>Paket Insurance</dt><dd>'.$metPaket['keterangan'].'</dd>
				<dt>Okupasi</dt><dd>'.$metOkupasi['keterangan'].'</dd>
				<dt>Kelas</dt><dd>'.$metKelas['keterangan'].'</dd>
				<dt>Location</dt><dd><strong>'.$metLokasi['keterangan'].'</strong></dd>
				<dt>Address</dt><dd><strong>'.$metData['alamatobjek'].'</strong></dd>
				</dl>
				</div>
				<div class="col-md-5">
				<dl class="dl-horizontal">
					<dt>Nilai Jaminan</dt><dd>'.duit($metData['nilaijaminan']).'</dd>
					<dt>Premuim Fire</dt><dd>'.duit($metData['premifire']).'</dd>
					<dt>premium PA</dt><dd>'.duit($metData['premipa']).'</dd>
				</dl>
				</div>
			</div>
			<div class="panel-body pt0 pb0">
				<div class="form-group header bgcolor-default">
		        <div class="col-md-12"><h4 class="semibold text-primary mt0 mb5">Form Claim '.ucwords($_REQUEST['tipe']).'</h4></div>
				</div>
			</div>
			<div class="panel-body">';
        } else {
        }
        if ($_REQUEST['coc']=="setReqClaimGeneral") {
            foreach ($_FILES['uploadphoto1']['tmp_name'] as $key => $tmp_name) {
                $file_name = $metData['id'].'_'.$metData['nama'].'_'.$_FILES['uploadphoto1']['name'][$key];
                $file_size = $_FILES['uploadphoto1']['size'][$key];
                $file_tmp = $_FILES['uploadphoto1']['tmp_name'][$key];
                $file_type = $_FILES['uploadphoto1']['type'][$key];
                if ($file_size > $FILESIZE_2) {
                    $errorfile = '<div class="alert alert-dismissable alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
								<strong>Error!</strong> File photo <strong>'.$_FILES['uploadphoto1']['name'][$key].'</strong> is too large !
		           			</div>';
                } else {
                    echo $errorfile.'';
                }
            }
            if ($errorfile) {
            } else {
                foreach ($_FILES['uploadphoto1']['tmp_name'] as $key => $tmp_name) {
                    $file_name = $metData['id'].'_'.$metData['nama'].'_'.$_FILES['uploadphoto1']['name'][$key];
                    $file_type = $_FILES['uploadphoto1']['type'][$key];
                    $file_tmp = $_FILES['uploadphoto1']['tmp_name'][$key];
                    echo $file_name.'<br />';
                    $direktori = "../$PathPhotoGeneral/$file_name"; // direktori tempat menyimpan file
                    move_uploaded_file($file_tmp, $direktori);
                    gambar_kecil($direktori, $file_type);
                    $metPhotoClaim_ = $database->doQuery('INSERT INTO ajkphotoklaim SET idpeserta="'.$metData['id'].'", photo="'.$file_name.'", type="kejadian", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
                }
                echo $_REQUEST['tipeklaim'].'<br />';
                echo $_REQUEST['dodtime'].'<br />';
                echo $_REQUEST['keterangan'].'<br />';
                echo $_REQUEST['nilaiklaim'].'<br />';
                echo $_REQUEST['tmptmeninggal'].'<br />';
                echo $_REQUEST['penyebabmeninggal'].'<br />';
                echo $_REQUEST['dod'].'<br />';
                echo $_REQUEST['tipe'].'<br />';

                if ($_REQUEST['tipe']=="fire") {
                    $tglclaimfire = explode(" ", $_REQUEST['dodtime']);
                    $_cliamajkfire = 'tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
									  tipeklaimgeneral="'.strtoupper($_REQUEST['tipe']).'",
									  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
									  nilaiclaimclient="'.$_REQUEST['nilaiklaim'].'",';
                } elseif ($_REQUEST['tipe']=="phk") {
                    $tglclaimfire = explode(" ", $_REQUEST['dodtime']);
                    $_cliamajkfire = 'tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
									  tipeklaimgeneral="'.strtoupper($_REQUEST['tipe']).'",
									  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",
									  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
									  nilaiclaimclient="'.$metData['nilaijaminan'].'",';
                } elseif ($_REQUEST['tipe']=="kreditmacet") {
                    $tglclaimfire = explode(" ", $_REQUEST['dodtime']);
                    $_cliamajkfire = 'tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
									  tipeklaimgeneral="KREDIT MACET",
									  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",
									  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
									  nilaiclaimclient="'.$metData['nilaijaminan'].'",';
                } elseif ($_REQUEST['tipe']=="ajk") {
                    $_cliamajkfire = 'tipeklaimgeneral="'.strtoupper($_REQUEST['tipe']).'",
									  tempatmeninggal="'.$_REQUEST['tmptmeninggal'].'",
									  penyebabmeninggal="'.$_REQUEST['penyebabmeninggal'].'",
									  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",
									  nilaiclaimclient="'.$metData['nilaijaminan'].'",';
                } else {
                    $tglclaimfire = explode(" ", $_REQUEST['dodtime']);
                    $_cliamajkfire = 'tempatmeninggal="'.$_REQUEST['tmptmeninggal'].'",
									  penyebabmeninggal="'.$_REQUEST['penyebabmeninggal'].'",
									  tglklaim="'._convertDateEng2($_REQUEST['dod']).'",
									  tglklaimloss="'._convertDateEng2($tglclaimfire[0]).' '.$tglclaimfire[1].'",
									  tipeklaimgeneral="'.strtoupper($_REQUEST['tipe']).'",
									  ketklaimgeneral="'.strtoupper($_REQUEST['keterangan']).'",
									  nilaiclaimclient="'.$_REQUEST['nilaiklaim'].'",';
                }
                $metClaim_ = $database->doQuery('INSERT INTO ajkcreditnote SET idbroker="'.$metData['idbroker'].'",
																	   idclient="'.$metData['idclient'].'",
																	   idproduk="'.$metData['idpolicy'].'",
																	   idas="'.$metData['idas'].'",
																	   idaspolis="'.$metData['idaspolis'].'",
																	   idpeserta="'.$metData['id'].'",
																	   idregional="'.$metData['regional'].'",
																	   idcabang="'.$metData['cabang'].'",
																	   iddn="'.$metData['iddn'].'",
																	   '.$_cliamajkfire.'
																	   status="Request",
																	   tipeklaim="Claim",
																	   input_by="'.$q['id'].'",
																	   input_time="'.$futgl.'"');
            }
            /*
            if (filesize($_FILES['uploadphoto1']['tmp_name']) > $FILESIZE_2)	{
                    echo '<div class="alert alert-dismissable alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                                <strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'MB !
                           </div>';
                }
                else{
                echo $_FILES['uploadphoto1']['name'].'<br />';
                echo $_FILES['uploadphoto1']['size'].'<br />';
                }
                echo $_FILES['uploadphoto2']['name'].'<br />';
                echo $_FILES['uploadphoto3']['name'].'<br />';
            */
            header('location:ajk.php?re=cclaim&cc=generalclaim&idm='.$thisEncrypter->encode($metData['idpeserta']).'');
        }

        if ($_REQUEST['tipe']=="fire") {
            echo '<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Date and Time of Loss</dt><dd><input type="text" name="dodtime" class="form-control" id="datetime-picker" value="'.$_REQUEST['dodtime'].'" placeholder="Select a date" required/></dd>
					<dt>Cause of Loss</dt><dd><textarea class="form-control" rows="3" name="keterangan" value="'.$_REQUEST['keterangan'].'" placeholder="Cause of Accident" required/>'.$_REQUEST['keterangan'].'</textarea></dd>
					<dt>Estimated Claim Value</dt><dd><input type="text" name="nilaiklaim" class="form-control" value="'.$_REQUEST['nilaiklaim'].'" placeholder="Proposed Value" required/></dd>
					<br /><div class="col-sm-4 note note-primary mb1">You can upload more than one photo with maximum size 2MB.</div>
					<dt>Upload Photo Loss 1</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
					<dt>Upload Photo Loss 2</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
					<dt>Upload Photo Loss 3</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
				</dl>
			</div>';
        } elseif ($_REQUEST['tipe']=="phk") {
            echo '<div class="col-md-12">
				<dl class="dl-horizontal">
		          	<dt>Date of PHK</dt><dd><input type="text" name="dod" class="form-control" id="datepicker1" value="'.$_REQUEST['dod'].'" placeholder="Select a date" required></dd>
					<dt>Note</dt><dd><textarea class="form-control" rows="3" name="keterangan" value="'.$_REQUEST['keterangan'].'" placeholder="Note" required/>'.$_REQUEST['keterangan'].'</textarea></dd>
					<br /><div class="col-sm-4 note note-primary mb1">You can upload more than one photo with maximum size 2MB.</div>
					<dt>Upload Dokumen PHK</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
				</dl>
			</div>';
        } elseif ($_REQUEST['tipe']=="kreditmacet") {
            echo '<div class="col-md-12">
			<dl class="dl-horizontal">
				<dt>Date of Bad Credit</dt><dd><input type="text" name="dod" class="form-control" id="datepicker1" value="'.$_REQUEST['dod'].'" placeholder="Select a date" required></dd>
				<dt>Note</dt><dd><textarea class="form-control" rows="3" name="keterangan" value="'.$_REQUEST['keterangan'].'" placeholder="Note" required/>'.$_REQUEST['keterangan'].'</textarea></dd>
				<br /><div class="col-sm-4 note note-primary mb1">You can upload more than one photo with maximum size 2MB.</div>
				<dt>Document Bad Credit</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
			</dl>
			</div>';
        } elseif ($_REQUEST['tipe']=="ajk") {
            echo '<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Place of Death</dt>
					<dd><select name="tmptmeninggal" class="form-control" required>
		            	<option value="">Choose</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['tmptmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
					</dd>
					<dt>Cause of Death</dt>
					<dd><select name="penyebabmeninggal" class="form-control" required>
		            	<option value="">Choose</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['penyebabmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
					</dd>
		          	<dt>Date of Death</dt><dd><input type="text" name="dod" class="form-control" id="datepicker1" value="'.$_REQUEST['dod'].'" placeholder="Select a date" required/></dd>
				</dl>
			</div>';
        } else {
            echo '<div class="col-md-12">
				<dl class="dl-horizontal">
					<dt>Place of Death</dt>
					<dd><select name="tmptmeninggal" class="form-control" required>
		            	<option value="">Select Place of Death</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['tmptmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
					</dd>
					<dt>Cause of Death</dt>
					<dd><select name="penyebabmeninggal" class="form-control" required>
		            	<option value="">Select Cause of Death</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['penyebabmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
					</dd>
		          	<dt>Date of Death</dt><dd><input type="text" name="dod" class="form-control" id="datepicker1" value="'.$_REQUEST['dod'].'" placeholder="Select a date" required></dd>
					<dt>Date and Time of Loss</dt><dd><input type="text" name="dodtime" class="form-control" id="datetime-picker" value="'.$_REQUEST['dodtime'].'" placeholder="Select a date" required></dd>
					<dt>Cause  of Loss</dt><dd><textarea class="form-control" rows="3" name="keterangan" value="'.$_REQUEST['keterangan'].'" placeholder="Cause of Fire" required>'.$_REQUEST['keterangan'].'</textarea></dd>
					<dt>Estimated Claim Value</dt><dd><input type="text" name="nilaiklaim" class="form-control" value="'.$_REQUEST['nilaiklaim'].'" placeholder="Proposed Value" required></dd>
					<dt>Upload Photo Loss 1</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
					<dt>Upload Photo Loss 2</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
					<dt>Upload Photo Loss 3</dt><dd><div class="input-group"><input type="file" name="uploadphoto1[]" multiple accept="image/*"></div></dd>
				</dl>
			</div>';
        }
        echo '</div>
			<div class="panel-footer"><input type="hidden" name="coc" value="setReqClaimGeneral">'.BTN_SUBMIT.'</div>
		</div>
		</form>';
        echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                ;
    break;

    case "apprclaimdata":

        if ($_REQUEST['doinv']) {
            $metUpdtData = 'status="Investigation", tglinvestigasi="'._convertDateEng2($_REQUEST['doinv']).'"';
        } else {
        }

        if ($_REQUEST['doinvend']) {
            $metUpdtData = 'status="Approve",
											tglinvestigasiend="'._convertDateEng2($_REQUEST['doinvend']).'",
											ahliwarisnama="'.$_REQUEST['namaahliwaris'].'",
											ahliwarisalamat="'.$_REQUEST['alamatahliwaris'].'",
											ahliwaristlp="'.$_REQUEST['tlpahliwaris'].'",
											dokterinvestigasi="'.$_REQUEST['dokterinvestigasi'].'"';
        } else {
        }

        if ($_REQUEST['tglinfoasuransi']) {
            $metUpdtData = 'status="Proses Asuransi",
											tgllengkapdokumen="'._convertDateEng2($_REQUEST['tgllengkapdokumen']).'",
											tglinfoasuransi="'._convertDateEng2($_REQUEST['tglinfoasuransi']).'",
											tglkirimdokumenasuransi="'._convertDateEng2($_REQUEST['tgldokumenasuransi']).'"';
            $cekIDcn = mysql_fetch_array($database->doQuery('SELECT * FROM ajkcreditnote WHERE id="'.$_REQUEST['id'].'"'));
            $updateKlaimDebitur = $database->doQuery('UPDATE ajkpeserta SET statusaktif="Claim" WHERE id="'.$cekIDcn['idpeserta'].'"');
        } else {
        }

        if ($_REQUEST['tglbayarclaim']) {
            $metUpdtData = 'status="Dibayar Asuransi",
											tglbayar="'._convertDateEng2($_REQUEST['tglbayarclaim']).'",
											nilaiclaimdibayar="'.$_REQUEST['nilaiklaimdibayar'].'"';

            $cekIDcn = mysql_fetch_array($database->doQuery('SELECT * FROM ajkcreditnote WHERE id="'.$_REQUEST['id'].'"'));
            //SET NOMOR CN KLAIM
            if ($cekIDcn['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idb'];
            } else {
                $kodeBroker = $cekIDcn['idbroker'];
            }
            $fakcekcn = $cekIDcn['idcn'];
            $idNumber = 100000000 + $fakcekcn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //
            $creditnoteNumber = "CN.K".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
            $creditnoteNumberNomor = ', nomorcreditnote="'.$creditnoteNumber.'", tglcreditnote="'.$futoday.'", create_by="'.$q['id'].'", create_time="'.$futgl.'"';
            //SET NOMOR CN KLAIM
            $updateKlaimDebitur = $database->doQuery('UPDATE ajkpeserta SET statusaktif="Claim" WHERE id="'.$cekIDcn['idpeserta'].'"');
        } else {
        }

        $metStatus = $database->doQuery('UPDATE ajkcreditnote SET '.$metUpdtData.' '.$creditnoteNumberNomor.', update_by="'.$q['id'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
        echo '<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<strong>Success!</strong> update status data claim by '.$q['firstname'].'.
			</div><meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim">';
            ;
    break;

//
    case "apprclaim":
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkinsurance.`name` AS asuransi,
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idpeserta AS idmember,
		ajkcreditnote.idproduk,
		ajkcreditnote.tglklaim,
		ajkcreditnote.tglinvestigasi,
		ajkcreditnote.tglinvestigasiend,
		ajkcreditnote.ahliwarisnama,
		ajkcreditnote.ahliwarisalamat,
		ajkcreditnote.ahliwaristlp,
		ajkcreditnote.dokterinvestigasi,
		ajkcreditnote.tgllengkapdokumen,
		ajkcreditnote.tglinfoasuransi,
		ajkcreditnote.tglkirimdokumenasuransi,
		ajkcreditnote.tglbayar,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.status,
		ajkcreditnote.keterangan,
		ajkcreditnote.nilaiclaimclient,
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
		ajkpeserta.statusaktif,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkinsurance ON ajkpeserta.asuransi = ajkinsurance.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkcreditnote.id = "'.$thisEncrypter->decode($_REQUEST['m']).'"'));

        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		        <!--<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqClaim">'.BTN_BACK.'</a></div>-->
		        <div class="toolbar"><a href="ajk.php?re=cclaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';

        $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['tempatmeninggal'].'"'));
        $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['penyebabmeninggal'].'"'));

                if ($metData['status']=="Proses Penyelia") {
                    $formStatusklaim = '<div class="col-md-3">Tanggal Mulai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker1" value="'.$_REQUEST['doinv'].'" placeholder="Select a date" required/></div>';
                } elseif ($metData['status']=="Investigation") {
                    $formStatusklaim = '<div class="col-md-3">Tanggal Mulai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker1" value="'._convertDate3(_convertDate(_convertDate($metData['tglinvestigasi']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Dokter Investigasi</div><div class="col-md-9"><input type="text" name="dokterinvestigasi" class="form-control" value="'.$_REQUEST['dokterinvestigasi'].'" placeholder="Dokter investigasi" required/></div>
						  				<div class="col-md-3">Tanggal Selesai Investigasi</div><div class="col-md-9"><input type="text" name="doinvend" class="form-control" id="datepicker2" value="'.$_REQUEST['doinvend'].'" placeholder="Select a date" required/></div>';
                    $formStatusklaim1 = '<div class="col-md-3">Nama Ahliwaris</div><div class="col-md-9"><input type="text" name="namaahliwaris" class="form-control" value="'.$_REQUEST['namaahliwaris'].'" placeholder="Nama ahli waris" required/></div>
						  				<div class="col-md-3">Alamat Ahliwaris</div><div class="col-md-9"><textarea name="alamatahliwaris" class="form-control" rows="3" placeholder="Alamat Ahliwaris" required>'.$_REQUEST['alamatahliwaris'].'</textarea></div>
						  				<div class="col-md-3">Telephone Ahliwaris</div><div class="col-md-9"><input type="text" name="tlpahliwaris" class="form-control" value="'.$_REQUEST['tlpahliwaris'].'" placeholder="Telephone ahli waris" required/></div>';
                } elseif ($metData['status']=="Proses Adonai" and $metData['tipeklaim']!="Death") {
                    $formStatusklaim = '<div class="col-md-3">Tgl Kelengkapan Dokumen</div><div class="col-md-9"><input type="text" name="tgllengkapdokumen" class="form-control" id="datepicker3" value="'.$_REQUEST['tgllengkapdokumen'].'" placeholder="Select a date" required/></div>
															<div class="col-md-3">Tgl Info ke Asuransi</div><div class="col-md-9"><input type="text" name="tglinfoasuransi" class="form-control" id="datepicker31" value="'.$_REQUEST['tglinfoasuransi'].'" placeholder="Select a date" required/></div>
															<div class="col-md-3">Tgl Kirim Dokumen ke Asuransi</div><div class="col-md-9"><input type="text" name="tgldokumenasuransi" class="form-control" id="datepicker32" value="'.$_REQUEST['tgldokumenasuransi'].'" placeholder="Select a date" required/></div>';
                } elseif ($metData['status']=="Proses Adonai" and $metData['tipeklaim']=="Death") {
                    $formStatusklaim = '<div class="col-md-3">Tanggal Mulai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker1" value="'._convertDate3(_convertDate(_convertDate($metData['tglinvestigasi']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Dokter Investigasi</div><div class="col-md-9"><input type="text" name="dokterinvestigasi" class="form-control" value="'.$metData['dokterinvestigasi'].'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tanggal Selesai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker2" value="'._convertDate3(_convertDate(_convertDate($metData['tglinvestigasiend']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Kelengkapan Dokumen</div><div class="col-md-9"><input type="text" name="tgllengkapdokumen" class="form-control" id="datepicker3" value="'.$_REQUEST['tgllengkapdokumen'].'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Info ke Asuransi</div><div class="col-md-9"><input type="text" name="tglinfoasuransi" class="form-control" id="datepicker31" value="'.$_REQUEST['tglinfoasuransi'].'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Kirim Dokumen ke Asuransi</div><div class="col-md-9"><input type="text" name="tgldokumenasuransi" class="form-control" id="datepicker32" value="'.$_REQUEST['tgldokumenasuransi'].'" placeholder="Select a date" required/></div>';
                    $formStatusklaim1 = '<div class="col-md-3">Nama Ahliwaris</div><div class="col-md-9"><input type="text" name="namaahliwaris" class="form-control" value="'.$metData['ahliwarisnama'].'" placeholder="Nama ahli waris" required/></div>
						  				<div class="col-md-3">Alamat Ahliwaris</div><div class="col-md-9"><textarea name="alamatahliwaris" class="form-control" rows="3" placeholder="Alamat Ahliwaris" required>'.$metData['ahliwarisalamat'].'</textarea></div>
						  				<div class="col-md-3">Telephone Ahliwaris</div><div class="col-md-9"><input type="text" name="tlpahliwaris" class="form-control" value="'.$metData['ahliwaristlp'].'" placeholder="Telephone ahli waris" required/></div>';
                } elseif ($metData['status']=="Proses Asuransi" and $metData['tipeklaim']!="Death") {
                    $formStatusklaim = '<div class="col-md-3">Tgl Kelengkapan Dokumen</div><div class="col-md-9"><input type="text" name="tgllengkapdokumen" class="form-control" id="datepicker3" value="'._convertDate3(_convertDate(_convertDate($metData['tgllengkapdokumen']))).'" placeholder="Select a date" required/></div>
															<div class="col-md-3">Tgl Info ke Asuransi</div><div class="col-md-9"><input type="text" name="tglinfoasuransi" class="form-control" id="datepicker31" value="'._convertDate3(_convertDate(_convertDate($metData['tglinfoasuransi']))).'" placeholder="Select a date" required/></div>
															<div class="col-md-3">Tgl Kirim Dokumen ke Asuransi</div><div class="col-md-9"><input type="text" name="tgldokumenasuransi" class="form-control" id="datepicker32" value="'._convertDate3(_convertDate(_convertDate($metData['tglkirimdokumenasuransi']))).'" placeholder="Select a date" required/></div>';
                    $formStatusklaim1 = '<div class="col-md-3">Tgl Bayar Klaim Ke Bank</div><div class="col-md-9"><input type="text" name="tglbayarclaim" class="form-control" id="datepicker33" value="'.$_REQUEST['tglbayarclaim'].'" placeholder="Select a date" required/></div>
															 <div class="col-md-3">Total Klaim Dibayar</div><div class="col-md-9"><input type="text" name="nilaiklaimdibayar" class="form-control" value="'.$_REQUEST['nilaiklaimdibayar'].'" placeholder="Total Klaim Dibayar" required/></div>';
                } elseif ($metData['status']=="Proses Asuransi" and $metData['tipeklaim']=="Death") {
                    $formStatusklaim = '<div class="col-md-3">Tanggal Mulai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker1" value="'._convertDate3(_convertDate(_convertDate($metData['tglinvestigasi']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Dokter Investigasi</div><div class="col-md-9"><input type="text" name="dokterinvestigasi" class="form-control" value="'.$metData['dokterinvestigasi'].'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tanggal Selesai Investigasi</div><div class="col-md-9"><input type="text" name="doinv" class="form-control" id="datepicker2" value="'._convertDate3(_convertDate(_convertDate($metData['tglinvestigasiend']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Kelengkapan Dokumen</div><div class="col-md-9"><input type="text" name="tgllengkapdokumen" class="form-control" id="datepicker3" value="'._convertDate3(_convertDate(_convertDate($metData['tgllengkapdokumen']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Info ke Asuransi</div><div class="col-md-9"><input type="text" name="tglinfoasuransi" class="form-control" id="datepicker31" value="'._convertDate3(_convertDate(_convertDate($metData['tglinfoasuransi']))).'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Tgl Kirim Dokumen ke Asuransi</div><div class="col-md-9"><input type="text" name="tgldokumenasuransi" class="form-control" id="datepicker32" value="'._convertDate3(_convertDate(_convertDate($metData['tglkirimdokumenasuransi']))).'" placeholder="Select a date" required/></div>';
                    $formStatusklaim1 = '<div class="col-md-3">Nama Ahliwaris</div><div class="col-md-9"><input type="text" name="namaahliwaris" class="form-control" value="'.$metData['ahliwarisnama'].'" placeholder="Nama ahli waris" required/></div>
						  				<div class="col-md-3">Alamat Ahliwaris</div><div class="col-md-9"><textarea name="alamatahliwaris" class="form-control" rows="3" placeholder="Alamat Ahliwaris" required>'.$metData['ahliwarisalamat'].'</textarea></div>
						  				<div class="col-md-3">Telephone Ahliwaris</div><div class="col-md-9"><input type="text" name="tlpahliwaris" class="form-control" value="'.$metData['ahliwaristlp'].'" placeholder="Telephone ahli waris" required/></div>
										<div class="col-md-3">Tgl Bayar Klaim Ke Bank</div><div class="col-md-9"><input type="text" name="tglbayarclaim" class="form-control" id="datepicker33" value="'.$_REQUEST['tglbayarclaim'].'" placeholder="Select a date" required/></div>
										<div class="col-md-3">Total Klaim Dibayar</div><div class="col-md-9"><input type="text" name="nilaiklaimdibayar" class="form-control" value="'.$_REQUEST['nilaiklaimdibayar'].'" placeholder="Total Klaim Dibayar" required/></div>';
                } elseif ($metData['status']=="Approve Paid") {
                    $formStatusklaim = '<div class="col-md-3">Tanggal Mulai Investigasi</div><div class="col-md-9">'._convertDate($metData['tglinvestigasi']).'</div>
										<div class="col-md-3">Tanggal Selesai Investigasi</div><div class="col-md-9">'._convertDate($metData['tglinvestigasiend']).'</div>
										<div class="col-md-3">Tanggal Pembayaran Klaim</div><div class="col-md-9">'._convertDate($metData['tglbayar']).'</div>';
                } else {
                }

        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<input type="hidden" name="idtabcn" value="'.$thisEncrypter->encode($metData['id']).'">
			<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
				<div class="panel-body">
					<div class="alert alert-dismissable alert-success text-center">
							<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
							</div>
							<div class="col-md-6">
							<div class="col-md-3">Debitnote</div><div class="col-md-9"><strong>'.$metData['nomordebitnote'].'</strong></div>
							<div class="col-md-3">Tanggal Debitnote</div><div class="col-md-9">'._convertDate($metData['tgldebitnote']).'</div>
							<div class="col-md-3">KTP</div><div class="col-md-9">'.$metData['nomorktp'].'</div>
							<div class="col-md-3">ID Member</div><div class="col-md-9">'.$metData['idpeserta'].'</div>
							<div class="col-md-3">Nama</div><div class="col-md-9">'.$metData['nama'].'</div>
							<div class="col-md-3">Tanggal Lahir</div><div class="col-md-9">'.$metData['tgllahir'].'</div>
							<div class="col-md-3">Usia</div><div class="col-md-9">'.$metData['usia'].' tahun</div>
							<div class="col-md-3">Plafond</div><div class="col-md-9">'.duit($metData['plafond']).'</div>
							<div class="col-md-3">Jangka Waktu</div><div class="col-md-9">'.$metData['tenor'].' bulan</div>
							<div class="col-md-3">Tanggal Asuransi</div><div class="col-md-9">'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</div>
							<div class="col-md-3">Total Premium</div><div class="col-md-9"><strong>'.duit($metData['totalpremi']).'</strong></div>
							'.$formStatusklaim1.'
							</div>

							<div class="col-md-6">
							<div class="col-md-3">Status Member</div><div class="col-md-9">'.$metData['statusaktif'].'</div>
							<div class="col-md-3">Status Klaim</div><div class="col-md-9"><span class="semibold text-danger">'.$metData['status'].'</span></div>
							<div class="col-md-3">Tempat Meninggal</div><div class="col-md-9">'.$tmptDeath['nama'].'</div>
							<div class="col-md-3">Penyebab Meninggal</div><div class="col-md-9">'.$pybbDeath['nama'].'</div>
							<div class="col-md-3">Tanggal Meninggal</div><div class="col-md-9">'._convertDate($metData['tglklaim']).'</div>
							<div class="col-md-3">Tanggal Klaim Saat Ini</div><div class="col-md-9">';
                            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
                            $monthins = explode(",", $monthins);
                            if ($monthins[0]>1) {
                                $wordyear = "".$monthins[0]." years";
                            } else {
                                $wordyear = "".$monthins[0]." year";
                            }
                            if ($monthins[1]>1) {
                                $wordmonth = "".$monthins[1]." months";
                            } else {
                                $wordmonth = "".$monthins[1]." month";
                            }
                            if ($monthins[1]>1) {
                                $wordday = "".$monthins[2]." days";
                            } else {
                                $wordday = "".$monthins[2]." day";
                            }
                            echo $wordyear.' '.$wordmonth.' '.$wordday;
                                $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
                            echo '</div>
							<div class="col-md-3">Bulan Berjalan</div><div class="col-md-9">';
                            if ($monthins[2] > 0) {
                                $bulanjalan = $monthins[1] + 1;
                            } else {
                                $bulanjalan = $monthins[1];
                            }
                            $blnberjalan = $thnbln + $bulanjalan;
                            if ($blnberjalan > 1) {
                                $blnberjalan_ = $blnberjalan.' months';
                            } else {
                                $blnberjalan_ = $blnberjalan.' month';
                            }
                            echo $blnberjalan_;
                            echo '</div>
							<div class="col-md-3">Rate Klaim</div><div class="col-md-9">';
                            if ($metData['klaimrate']=="Table") {
                                $metRateKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajkrateklaim WHERE idbroker="'.$metData['idbroker'].'" AND
																													   idclient="'.$metData['idclient'].'" AND
																													   idpolis="'.$metData['idproduk'].'" AND
																													   "'.$metData['tenor'].'" BETWEEN tenorfrom AND tenorto AND
																													   currentmonth="'.$blnberjalan.'"'));
                                $_klaimRate = $metRateKlaim['rate'];
                                $nilai_K = $metData['plafond'] * ($_klaimRate / 1000);
                            } else {
                                $_klaimRate = $metData['klaimpercentage'].'%';
                                $nilai_K = $metData['plafond'] * ($_klaimRate / 100);
                            }
                        echo $_klaimRate;
                            echo '</div>
							<div class="col-md-3">Pembayaran Klaim</div><div class="col-md-9">'.duit($metData['nilaiclaimclient']).'</div>
							<div class="col-md-3">Tanggal Meninggal</div><div class="col-md-9">'._convertDate($metData['tglklaim']).'</div>
							'.$formStatusklaim.'
					  	</div>
							</div>
							<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
							<div class="panel-body">';
        if ($_REQUEST['approvedata']=="ReqDataApprove") {
            $claimMember = $database->doQuery('UPDATE ajkcreditnote SET status="Process", keterangan="'.$_REQUEST['noteclaim'].'" WHERE id="'.$thisEncrypter->decode($_REQUEST['id']).'"');
            echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim">
				<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
				<strong>Success!</strong> data claim in processing by '.$q['firstname'].'.
		    </div>';
        }
        echo '<table class="table table-hover table-bordered">
			<thead>
			<tr><th>File name</th>
				<th>File upload</th>
			</tr>
			</thead>
			<tbody>';
        //$metDok = $database->doQuery('SELECT ajkdocumentclaimmember.id,ajkdocumentclaimmember.fileklaim, ajkdocumentclaim.namadokumen
        $metDok = $database->doQuery('SELECT ajkdocumentclaimmember.id,
											 ajkdocumentclaimmember.iddoc,
											 ajkdocumentclaimmember.idmember,
											 ajkdocumentclaimmember.fileklaim,
											 ajkdocumentclaim.namadokumen
									 FROM ajkdocumentclaimmember
									 INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
									 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
									 WHERE ajkdocumentclaimmember.idmember = "'.$metData['idmember'].'"');
        while ($metDok_ = mysql_fetch_array($metDok)) {
            if ($metDok_['fileklaim'] != null) {
                $_metUplaod = '<a href="../'.$PathDokumen.''.$metDok_['fileklaim'].'" target="_blank">'.BTN_VIEW.'</a>';
            } else {
                //$_metUplaod = '<a href="ajk.php?re=cclaim&cc=reqClaimData&docClaim=uploadclaim&id='.$thisEncrypter->encode($metData['idmember']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'">'.BTN_UPLOADCLAIM2.'</a>';
                if ($metData['status']!="Approve Paid") {
                    $_metUplaod = '<a href="ajk.php?re=cclaim&cc=reqClaimData&docClaim=uploadclaim&id='.$thisEncrypter->encode($metData['id']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'" title="upload document claim"><span class="label label-warning">Upload Document</span></a>';
                } else {
                    $_metUplaod = '<span class="label label-danger">FIle claim not uploaded</span>';
                }
            }
            echo '<tr><td width="80%">'.$metDok_['namadokumen'].'</td>
				  <td>'.$_metUplaod.'</td>
			  </tr>';
        }
        echo '</tbody>
			</table>
			<div class="form-group">
				<div class="col-sm-1"><span class="text-warning">Note</span></div>
		        <div class="col-sm-11">'.$metData['keterangan'].'</div>
			</div>';
        if ($metData['status']=="Approve Paid") {
        } else {
            echo '<input type="hidden" name="id" value="'.$metData['id'].'">
			  <div class="panel-footer"><input type="hidden" name="cc" value="apprclaimdata">'.BTN_SUBMIT.'</div>';
        }
        echo '</div>
			</form>
			</div>';
        echo '</div>
				</div>';
        echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
			  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                ;
    break;

    case "reqClaimData":
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idpeserta AS idmember,
		ajkcreditnote.idproduk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkinsurance.`name` AS asuransi,
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
		ajkpeserta.statusaktif,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.tglklaim,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.nilaiclaimdibayar
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkcreditnote.id = "'.$thisEncrypter->decode($_REQUEST['id']).'"'));

        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqClaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';

        $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['tempatmeninggal'].'"'));
        $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['penyebabmeninggal'].'"'));
        //UPLOAD DOKUMEN KLAIM
        if ($_REQUEST['docClaim']=="uploadclaim") {
            //echo $thisEncrypter->decode($_REQUEST['idm']).'-'.$thisEncrypter->decode($_REQUEST['iddoc']);
            $metDokumen = mysql_fetch_array($database->doQuery('SELECT ajkdocumentclaimmember.id, ajkdocumentclaim.namadokumen
															FROM ajkdocumentclaimmember
															INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
															INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
															WHERE ajkdocumentclaimmember.id = "'.$thisEncrypter->decode($_REQUEST['iddoc']).'"'));
            if ($_REQUEST['mett']=="uploadDokClam_") {
                //echo $_FILES['documentClaim']['name'];
                $filenameclaim = 'CLAIM_'.$_REQUEST['id'].'_'.$thisEncrypter->decode($_REQUEST['iddoc']).'_'.$_FILES['documentClaim']['name'];
                $metClaim = $database->doQuery('UPDATE ajkdocumentclaimmember SET fileklaim="'.$filenameclaim.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"');
                $KlaimTemp = $_FILES['documentClaim']['tmp_name'];
                $dirKlaim = '../'.$PathDokumen.''.$filenameclaim; // direktori tempat menyimpan file
                //move_uploaded_file($KlaimTemp,$dirKlaim);
                //header("location:ajk.php?re=cclaim&cc=uplClaim&idm=".$thisEncrypter->encode($_REQUEST['id'])."");
                header("location:ajk.php?re=cclaim&cc=reqClaimData&id=".$_REQUEST['id']."");
            }
            echo '<form method="post" action="#" data-parsley-validate enctype="multipart/form-data">
						  <div class="col-sm-4" align="right">'.$metDokumen['namadokumen'].' : </div>
						  <div class="col-sm-4" align="left"><input type="file" name="documentClaim" accept="application/pdf" required></div>
						  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
						  <input type="hidden" name="mett" value="uploadDokClam_">'.BTN_SUBMIT.'
						  </form>';
        } elseif ($_REQUEST['docClaim']=="deldocumentclaim") {
            echo $thisEncrypter->decode($_REQUEST['id']).'<br />';
            echo $thisEncrypter->decode($_REQUEST['iddoc']);
            $metDoc_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajkdocumentclaimmember WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"'));
            echo $metDoc_['fileklaim'];
            unlink('../'.$PathDokumen.''.$metDoc_['fileklaim']);
            $metDoc__ = $database->doQuery('UPDATE ajkdocumentclaimmember SET fileklaim = NULL WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"');
            header("location:ajk.php?re=cclaim&cc=reqClaimData&id=".$_REQUEST['id']."");
        }
        //UPLOAD DOKUMEN KLAIM

        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<input type="hidden" name="idbroker" value="'.$metData['idbroker'].'">
				<input type="hidden" name="idpeserta" value="'.$metData['idmember'].'">
				<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
				<div class="panel-body">
					<div class="alert alert-dismissable alert-success text-center">
					<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
					</div>
					<div class="col-md-7">
					<dl class="dl-horizontal">
			        	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
			            <dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
			            <dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
			            <dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
			            <dt>Age</dt><dd>'.$metData['usia'].' years</dd>
			            <dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
						<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
						<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
						<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
					</dl>
					</div>
					<div class="col-md-5">
					<dl class="dl-horizontal">
						<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					    <dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					    <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
					    <dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					    <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					    <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
					    <dt>Current Date Claim</dt>
					<dd>';
            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            echo $wordyear.' '.$wordmonth.' '.$wordday;
                $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
                //echo '<br />'.$thnbln;
                //echo '<br />'.$bulanjalan;
            echo '</dd>
		            <dt>Current Month</dt>
		            <dd>';	if ($monthins[2] > 0) {
                $bulanjalan = $monthins[1] + 1;
            } else {
                $bulanjalan = $monthins[1];
            }
            $blnberjalan = $thnbln + $bulanjalan;
            if ($blnberjalan > 1) {
                $blnberjalan_ = $blnberjalan.' months';
            } else {
                $blnberjalan_ = $blnberjalan.' month';
            }
            echo $blnberjalan_;
                if ($metData['klaimrate']=="Table") {
                    $metRateKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajkrateklaim WHERE idbroker="'.$metData['idbroker'].'" AND
																							        idclient="'.$metData['idclient'].'" AND
																							        idpolis="'.$metData['idproduk'].'" AND
																							        "'.$metData['tenor'].'" BETWEEN tenorfrom AND tenorto AND
																							        currentmonth="'.$blnberjalan.'"'));
                    $_klaimRate = $metRateKlaim['rate'];
                    $nilai_K = $metData['plafond'] * ($_klaimRate / 1000);
                    $_klaimrate = '<dt>Rate Claim</dt><dd><span class="semibold text-success">'.$_klaimRate.'</span></dd>';
                } else {
                    $_klaimRate = $metData['klaimpercentage'];
                    $nilai_K = $metData['plafond'] * ($_klaimRate / 100);
                    $_klaimrate = '<dt>Rate Claim</dt><dd><span class="semibold text-success">'.$metData['klaimpercentage'].'%</span></dd>';
                }
            echo '</dd>
				  '.$_klaimrate.'
				  <dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimdibayar']).'</span></dd>
							</dl>
						</div>
					</div>
					<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
					<div class="panel-body">';
        if ($_REQUEST['approvedata']=="ReqDataApprove") {
            $metCN = mysql_fetch_array($database->doQuery('SELECT Max(idcn) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idbroker'].'"AND del IS NULL'));
            if ($_REQUEST['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idbroker'];
            } else {
                $kodeBroker = $_REQUEST['idbroker'];
            }
            $fakcekcn = $metCN['idcn'] + 1;
            $idNumber = 100000000 + $fakcekcn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //
            /* 20160905
            $creditnoteNumber = "CN.".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
            //echo '<br />'.$creditnoteNumber;
            $claimMember = $database->doQuery('UPDATE ajkcreditnote SET idcn="'.$fakcekcn.'", nomorcreditnote="'.$creditnoteNumber.'", tglcreditnote ="'.$futoday.'", status="Process", keterangan="'.$_REQUEST['noteclaim'].'" WHERE id="'.$thisEncrypter->decode($_REQUEST['id']).'"');
            $claimPeserta = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$thisEncrypter->decode($_REQUEST['id']).'", statusaktif="'.$metData['tipeklaim'].'" WHERE id="'.$_REQUEST['idpeserta'].'"');
            */
            $claimMember = $database->doQuery('UPDATE ajkcreditnote SET idcn="'.$fakcekcn.'", status="Pending", keterangan="'.$_REQUEST['noteclaim'].'" WHERE id="'.$thisEncrypter->decode($_REQUEST['id']).'"');
            $claimPeserta = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$thisEncrypter->decode($_REQUEST['id']).'", statusaktif="Pending" WHERE id="'.$_REQUEST['idpeserta'].'"');

            echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim"><div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Success!</strong> data claim in processing by '.$q['firstname'].'.</div>';
        }


                if ($_REQUEST['docClaim_']=="uploadclaim_") {
                    //echo $thisEncrypter->decode($_REQUEST['idm']).'-'.$thisEncrypter->decode($_REQUEST['iddoc']);
                    if ($_REQUEST['met']=="uploadDokClam_") {
                        //echo $_FILES['documentClaim']['name'];
                        $filenameclaim = 'CLAIM_'.$thisEncrypter->decode($_REQUEST['idm']).'_'.$thisEncrypter->decode($_REQUEST['iddoc']).'_'.$_FILES['documentClaim']['name'];
                        $metClaim = $database->doQuery('UPDATE ajkdocumentclaimmember SET fileklaim="'.$filenameclaim.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"');
                        $KlaimTemp = $_FILES['documentClaim']['tmp_name'];
                        $dirKlaim = '../'.$PathDokumen.''.$filenameclaim; // direktori tempat menyimpan file
                        move_uploaded_file($KlaimTemp, $dirKlaim);
                        //header("location:ajk.php?re=cclaim&cc=uplClaim&idm=".$_REQUEST['idm']."");
                    }
                    echo '<form method="post" action="#" data-parsley-validate enctype="multipart/form-data">
						  <div class="col-sm-6" align="right"><input type="file" name="documentClaim" accept="application/pdf" required></div>
						  <input type="hidden" name="met" value="uploadDokClam_">'.BTN_SUBMIT.'
						  </form>';
                }

        echo '<table class="table table-hover table-bordered">
					<thead>
					<tr><th>File name</th>
						<th>File upload</th>
					</tr>
					</thead>
					<tbody>';
        $metDok = $database->doQuery('SELECT ajkdocumentclaimmember.id,
											 ajkdocumentclaimmember.iddoc,
											 ajkdocumentclaimmember.idmember,
											 ajkdocumentclaimmember.fileklaim,
											 ajkdocumentclaimpartner.iddoc,
											 ajkdocumentclaim.namadokumen
									FROM ajkdocumentclaimmember
									INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
									INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
									WHERE ajkdocumentclaimmember.idmember = "'.$metData['idpeserta'].'"
									ORDER BY ajkdocumentclaim.id ASC');
        while ($metDok_ = mysql_fetch_array($metDok)) {
            if ($metDok_['fileklaim'] != null) {
                $_metUplaod = '<a href="../'.$PathDokumen.''.$metDok_['fileklaim'].'" title="view document claim" target="_blank">'.BTN_VIEW.'</a> &nbsp;
						   <a href="ajk.php?re=cclaim&cc=reqClaimData&docClaim=deldocumentclaim&id='.$thisEncrypter->encode($metData['id']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'" class="pull-right" title="delete document claim">'.BTN_DEL.'</a>';
            } else {
                $_metUplaod = '<a href="ajk.php?re=cclaim&cc=reqClaimData&docClaim=uploadclaim&id='.$thisEncrypter->encode($metData['id']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'" title="upload document claim"><span class="label label-warning">Upload Document</span></a>';
                //$_metUplaod = '<span class="label label-danger">FIle claim not uploaded</span>';
            //$_metUplaod = '<a href="ajk.php?re=cclaim&cc=uplClaim&docClaim_=uploadclaim_&idm='.$thisEncrypter->encode($metData['idpeserta']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'"><span class="label label-warning">Upload Document</span></a>';
            }

            echo '<tr><td width="80%">'.$metDok_['namadokumen'].'</td>
				  <td>'.$_metUplaod.'</td>
			  </tr>';
        }
        echo '</tbody>
			</table>
			<div class="form-group">
				<label class="col-sm-1 control-label">Note<span class="text-danger"> *</span></label>
		        <div class="col-sm-10"><textarea class="form-control" name="noteclaim" rows="3" required></textarea></div>
			</div>
			<div class="panel-footer"><input type="hidden" name="approvedata" value="ReqDataApprove">'.BTN_SUBMIT.'</div>

				</form>
				</div>';
        echo '</div>
			</div>';
        echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
			  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
            ;
    break;

    case "reqClaim":
        echo '<div class="page-header-section"><h2 class="title semibold">Credit Note</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
			<th width="1%">Broker</th>
			<th>Partner</th>
			<th>Product</th>
			<th>Asuransi</th>
			<th>ID Member</th>
			<th>Name</th>
			<th>Date Claim</th>
			<th>Payment Claim</th>
			<th>Status</th>
			<th>Input User</th>
			<th>Input Date</th>
		</tr>
		</thead>
		<tbody>';
        $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS broker,
		ajkclient.`name` AS client,
		ajkpolis.produk,
		ajkinsurance.`name` AS asuransi,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		useraccess.firstname AS userinput,
		ajkcreditnote.input_by,
		DATE_FORMAT(ajkcreditnote.input_time,"%Y-%m-%d") AS tglinput,
		ajkcreditnote.approve_by,
		DATE_FORMAT(ajkcreditnote.approve_time,"%Y-%m-%d") AS tglapprve,
		ajkcreditnote.status AS statusklaim,
		ajkcreditnote.tipeklaim
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN useraccess ON ajkcreditnote.input_by = useraccess.id
		WHERE (ajkcreditnote.status = "Process" OR ajkcreditnote.status = "Approve") AND
			   ajkcreditnote.del IS NULL '.$q___1.'  AND
			   ajkcreditnote.tipeklaim = "Claim" AND ajkpeserta.del IS NULL
		ORDER BY ajkcreditnote.id DESC');
        while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
            echo '<tr>
		   	<td align="center">'.++$no.'</td>
		   	<td>'.$metCreditnote_['broker'].'</td>
		   	<td>'.$metCreditnote_['client'].'</td>
		   	<td>'.$metCreditnote_['produk'].'</td>
		   	<td>'.$metCreditnote_['asuransi'].'</td>
		   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
		   	<td><a href="ajk.php?re=cclaim&cc=reqClaimData&id='.$thisEncrypter->encode($metCreditnote_['id']).'">'.$metCreditnote_['nama'].'</a></td>
		   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
		   	<td>'.duit($metCreditnote_['nilaiclaimclient']).'</td>
		   	<td>'.$metCreditnote_['statusklaim'].'</td>
		   	<td>'.$metCreditnote_['userinput'].'</td>
		   	<td>'._convertDate($metCreditnote_['tglinput']).'</td>
		    </tr>';
        }
        echo '</tbody>
				<tfoot>
		        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="ID Member"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		        </tr>
		        </tfoot></table>
		    	</div>
				</div>
		    </div>
		</div>';
                ;
    break;

    case "uplClaim":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idproduk,
		ajkcreditnote.idas,
		ajkcreditnote.idaspolis,
		ajkcreditnote.idpeserta,
		ajkcreditnote.idregional,
		ajkcreditnote.idcabang,
		ajkcreditnote.iddn,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.nilaiclaimdibayar,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.`status`,
		ajkcreditnote.tipeklaim,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkinsurance.`name` AS insurancename,
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
		ajkdebitnote.nomordebitnote,
		ajkregional.`name` AS regional,
		ajkcabang.`name` AS cabang
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
		INNER JOIN ajkregional ON ajkcreditnote.idregional = ajkregional.er
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.idpeserta = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['tempatmeninggal'].'"'));
        $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$metData['penyebabmeninggal'].'"'));
        echo '<div class="row">
			<div class="col-md-12">
					<div class="alert alert-dismissable alert-success text-center">
					<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
					</div>
					<div class="col-md-7">
					<dl class="dl-horizontal">
		               	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
		               	<dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
		               	<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
		               	<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
		               	<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
		               	<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
						<dt>Place of Death</dt><dd>'.$tmptDeath['nama'].'</dd>
						<dt>Cause of Death</dt><dd>'.$pybbDeath['nama'].'</dd>
						<dt>Date of Death</dt><dd>'._convertDate($metData['tglklaim']).'</dd>
					</dl>
				</div>
				<div class="col-md-5">
					<dl class="dl-horizontal">
				       	<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
				       	<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
				        <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
						<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
				        <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
				        <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
				        <dt>Current Date Claim</dt>
				        <dd>';
                $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
                $monthins = explode(",", $monthins);
                //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
                if ($monthins[0]>1) {
                    $wordyear = "".$monthins[0]." years";
                } else {
                    $wordyear = "".$monthins[0]." year";
                }
                if ($monthins[1]>1) {
                    $wordmonth = "".$monthins[1]." months";
                } else {
                    $wordmonth = "".$monthins[1]." month";
                }
                if ($monthins[1]>1) {
                    $wordday = "".$monthins[2]." days";
                } else {
                    $wordday = "".$monthins[2]." day";
                }
                echo $wordyear.' '.$wordmonth.' '.$wordday;
                $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
                //echo '<br />'.$thnbln;
                //echo '<br />'.$bulanjalan;
                echo '</dd>
				            <dt>Current Month</dt>
				            <dd>';	if ($monthins[2] > 0) {
                    $bulanjalan = $monthins[1] + 1;
                } else {
                    $bulanjalan = $monthins[1];
                }
                $blnberjalan = $thnbln + $bulanjalan;
                if ($blnberjalan > 1) {
                    $blnberjalan_ = $blnberjalan.' months';
                } else {
                    $blnberjalan_ = $blnberjalan.' month';
                }
                echo $blnberjalan_;
                echo '</dd>
					  <dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimdibayar']).'</span></dd>
					  <dt>Status Claim</dt><dd><span class="semibold text-danger">'.$metData['status'].'</span></dd>
						</dl>
					</div>
				</div>';

        if ($_REQUEST['docClaim']=="uploadclaim") {
            //echo $thisEncrypter->decode($_REQUEST['idm']).'-'.$thisEncrypter->decode($_REQUEST['iddoc']);
            if ($_REQUEST['met']=="uploadDokClam_") {
                //echo $_FILES['documentClaim']['name'];
                $filenameclaim = 'CLAIM_'.$thisEncrypter->decode($_REQUEST['idm']).'_'.$thisEncrypter->decode($_REQUEST['iddoc']).'_'.$_FILES['documentClaim']['name'];
                $metClaim = $database->doQuery('UPDATE ajkdocumentclaimmember SET fileklaim="'.$filenameclaim.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['iddoc']).'"');
                $KlaimTemp = $_FILES['documentClaim']['tmp_name'];
                $dirKlaim = '../'.$PathDokumen.''.$filenameclaim; // direktori tempat menyimpan file
                move_uploaded_file($KlaimTemp, $dirKlaim);
                header("location:ajk.php?re=cclaim&cc=uplClaim&idm=".$_REQUEST['idm']."");
            }
            echo '<form method="post" action="#" data-parsley-validate enctype="multipart/form-data">
			  <div class="col-sm-6" align="right"><input type="file" name="documentClaim" accept="application/pdf" required></div>
			  <input type="hidden" name="met" value="uploadDokClam_">'.BTN_SUBMIT.'</div>
			  </form>';
        }
        echo '<div class="panel-body">
			<table class="table table-hover table-bordered">
		    <thead>
		    <tr><th>Document Claim</th>
		        <th>File Upload</th>
		    </tr>
		    </thead>
		    <tbody>';
        $metDok = $database->doQuery('SELECT ajkdocumentclaimmember.id,
											 ajkdocumentclaimmember.iddoc,
											 ajkdocumentclaimmember.idmember,
											 ajkdocumentclaimmember.fileklaim,
											 ajkdocumentclaimpartner.iddoc,
											 ajkdocumentclaim.namadokumen
											 FROM ajkdocumentclaimmember
											 INNER JOIN ajkdocumentclaimpartner ON ajkdocumentclaimmember.iddoc = ajkdocumentclaimpartner.id
											 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
											 WHERE ajkdocumentclaimmember.idmember = "'.$metData['idpeserta'].'"
											 ORDER BY ajkdocumentclaim.id ASC');
        while ($metDok_ = mysql_fetch_array($metDok)) {
            if ($metDok_['fileklaim'] != null) {
                $_metUplaod = '<a href="../'.$PathDokumen.''.$metDok_['fileklaim'].'" target="_blank">'.BTN_VIEW.'</a>';
            } else {
                $_metUplaod = '<a href="ajk.php?re=cclaim&cc=uplClaim&docClaim=uploadclaim&idm='.$thisEncrypter->encode($metData['idpeserta']).'&iddoc='.$thisEncrypter->encode($metDok_['id']).'">'.BTN_UPLOADCLAIM.'</a>';
            }
            echo '<tr><td width="80%">'.$metDok_['namadokumen'].'</td>
			  	  <td>'.$_metUplaod.'</td>
			  </tr>';
        }
        echo '</tbody>
				</table>
		<a href="ajk.php?re=cclaim&cc=reqClaim">'.BTN_GOTOCREDITNOTE.'</a>
					</div>
				</div>
			</div>
		</div>';
        echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
			  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                ;
    break;

    case "setReqClaim":

        /*
        echo $_REQUEST['regional'].'<br />';
        echo $_REQUEST['cabang'].'<br />';
        echo $_REQUEST['idb'].'<br />';
        echo $_REQUEST['idp'].'<br />';
        echo $_REQUEST['idc'].'<br />';
        echo $_REQUEST['idas'].'<br />';
        echo $_REQUEST['idaspolis'].'<br />';
        echo $_REQUEST['pod'].'<br />';
        echo $_REQUEST['cod'].'<br />';
        echo $_REQUEST['dod'].'<br />';
        echo $_REQUEST['idm'].'<br />';
        echo $_REQUEST['nilaiklaimmember'].'<br /><br />';
        */

        $metMemKlaim = mysql_fetch_array($database->doQuery('SELECT id, iddn, nama, idpeserta FROM ajkpeserta WHERE id="'.$_REQUEST['idm'].'"'));
        $metMemKlaimRegKlaim = mysql_fetch_array($database->doQuery('UPDATE ajkpeserta SET statusaktif="Request", statuspeserta="Death" WHERE id="'.$_REQUEST['idm'].'"'));

        $metCN = $database->doQuery('INSERT INTO ajkcreditnote SET idbroker="'.$_REQUEST['idb'].'",
																   idclient="'.$_REQUEST['idc'].'",
																   idproduk="'.$_REQUEST['idp'].'",
																   idas="'.$_REQUEST['idas'].'",
																   idaspolis="'.$_REQUEST['idaspolis'].'",
																   idpeserta="'.$_REQUEST['idm'].'",
																   idregional="'.$_REQUEST['regional'].'",
																   idcabang="'.$_REQUEST['cabang'].'",
																   iddn="'.$metMemKlaim['iddn'].'",
																   nilaiclaimclient="'.$_REQUEST['nilaiklaimmember'].'",
																   nilaiclaimasuransi="'.$_REQUEST['nilaiklaimmember'].'",
																   tglklaim="'._convertDate($_REQUEST['dod']).'",
																   tempatmeninggal="'.$_REQUEST['pod'].'",
																   penyebabmeninggal="'.$_REQUEST['cod'].'",
																   status="Process",
																   tipeklaim="Claim",
																   input_by="'.$q['id'].'",
																   input_time="'.$futoday.'"');
        echo '<br /><br />';
        foreach ($_REQUEST['dokKlaim'] as $key => $val) {
            $docKlaim = $database->doQuery('INSERT INTO ajkdocumentclaimmember SET iddoc="'.$val.'", idmember="'.$_REQUEST['idm'].'"');
            //echo('INSERT INTO ajkdocumentclaimmember SET iddoc="'.$val.'", idmember="'.$_REQUEST['idm'].'"');
        //echo $val.'<br />';
        }
        echo '<div class="alert alert-info fade in">
		        <h4 class="semibold">Claim created!</h4>
				<p class="mb10">Data claims on behalf of <strong>'.$metMemKlaim['nama'].' ('.$metMemKlaim['idpeserta'].')</strong> has been created.</p>
		        <a href="ajk.php?re=cclaim"><button type="button" class="btn btn-success">Go to Credit Note</button></a>
		        <a href="ajk.php?re=cclaim&cc=uplClaim&idm='.$thisEncrypter->encode($_REQUEST['idm']).'"><button type="button" class="btn btn-info">Upload Document Claim</button></a>
		      </div>';
            ;
    break;

    case "setClaim":
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkdebitnote.idas,
		ajkdebitnote.idaspolis,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.tgldebitnote,
		ajkcabang.`name` AS cabang,
		ajkpeserta.id,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
		ajkpeserta.regional,
		ajkpeserta.cabang,
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
		ajkpeserta.statusaktif
		FROM ajkpeserta
		INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=newClaim&idm='.$thisEncrypter->encode($metData['id']).'">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        if (_convertDateEng2($_REQUEST['dod']) > $futoday) {
            echo '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Error!</strong> Date of death should not be more than the current date !</div>
		<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim&cc=newClaim&idm='.$_REQUEST['idm'].'">';
        } else {
            /*
            echo $_REQUEST['tmptmeninggal'].'<br />';
            echo $_REQUEST['penyebabmeninggal'].'<br />';
            echo $_REQUEST['dod'].'<br />';
            echo $_REQUEST['idm'].'<br />';
            */
            $tmptDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$_REQUEST['tmptmeninggal'].'"'));
            $pybbDeath = mysql_fetch_array($database->doQuery('SELECT id, nama FROM ajkkejadianklaim WHERE id="'.$_REQUEST['penyebabmeninggal'].'"'));
            echo '<div class="row">
				<div class="col-md-12">
				<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
					<input type="hidden" name="regional" value="'.$metData['regional'].'">
					<input type="hidden" name="cabang" value="'.$metData['cabang'].'">
					<input type="hidden" name="idb" value="'.$metData['idbroker'].'">
					<input type="hidden" name="idc" value="'.$metData['idclient'].'">
					<input type="hidden" name="idp" value="'.$metData['idpolicy'].'">
					<input type="hidden" name="iddn" value="'.$metData['iddn'].'">
					<input type="hidden" name="idas" value="'.$metData['idas'].'">
					<input type="hidden" name="idaspolis" value="'.$metData['idaspolis'].'">
					<input type="hidden" name="klaimdiajukan" value="'.$_REQUEST['klaimdiajukan'].'">
					<input type="hidden" name="pod" value="'.$_REQUEST['tmptmeninggal'].'">
					<input type="hidden" name="cod" value="'.$_REQUEST['penyebabmeninggal'].'">
					<input type="hidden" name="tglterima" value="'._convertDate(_convertDateEng2($_REQUEST['tglterima'])).'">
					<input type="hidden" name="tgllengkap" value="'._convertDate(_convertDateEng2($_REQUEST['tgllengkap'])).'">
					<input type="hidden" name="tglinfoasuransi" value="'._convertDate(_convertDateEng2($_REQUEST['tglinfoasuransi'])).'">
					<input type="hidden" name="tglkirimasuransi" value="'._convertDate(_convertDateEng2($_REQUEST['tglkirimasuransi'])).'">
					<input type="hidden" name="dod" value="'._convertDate(_convertDateEng2($_REQUEST['dod'])).'">
					<input type="hidden" name="idm" value="'.$thisEncrypter->decode($_REQUEST['idm']).'">
					<div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
						<div class="panel-body">
						<div class="alert alert-dismissable alert-success text-center">
		                <strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
						</div>
						<div class="col-md-7">
						<dl class="dl-horizontal">
		                	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
		                	<dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
		                	<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
		                	<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
		                	<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
		                	<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd><br />
		                	<dt>Value claim submitted</dt><dd><span class="semibold text-danger">'.duit($_REQUEST['klaimdiajukan']).'</span></dd>
							<dt>Place of Death</dt><dd><input type="hidden" name="podmember" value="'.$tmptDeath['id'].'">'.$tmptDeath['nama'].'</dd>
							<dt>Cause of Death</dt><dd><input type="hidden" name="codmember" value="'.$pybbDeath['id'].'">'.$pybbDeath['nama'].'</dd>
							<dt>Date of Death</dt><dd><input type="hidden" name="dodmember" value="'._convertDate(_convertDateEng2($_REQUEST['dod'])).'">'._convertDate(_convertDateEng2($_REQUEST['dod'])).'</dd>
						</dl>
					</div>
					<div class="col-md-5">
						<dl class="dl-horizontal">
				        	<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
				        	<dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
				            <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
				            <dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
				            <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
				            <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
				            <dt>Current Date Claim</dt>
				            <dd>';
            $monthins = datediff($metData['tglakad'], _convertDateEng2($_REQUEST['dod']));
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            echo $wordyear.' '.$wordmonth.' '.$wordday;
            $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
            //echo '<br />'.$thnbln;
            //echo '<br />'.$bulanjalan;
            echo '</dd>
				            <dt>Current Month</dt>
				            <dd>';
            if ($monthins[2] > 0) {
                $bulanjalan = $monthins[1] + 1;
            } else {
                $bulanjalan = $monthins[1];
            }
            $blnberjalan = $thnbln + $bulanjalan;
            if ($blnberjalan > 1) {
                $blnberjalan_ = $blnberjalan.' months';
            } else {
                $blnberjalan_ = $blnberjalan.' month';
            }
            echo $blnberjalan_;
            if ($metData['klaimrate']=="Table") {
                $metRateKlaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajkrateklaim
																							  WHERE idbroker="'.$metData['idbroker'].'" AND
																							        idclient="'.$metData['idclient'].'" AND
																							        idpolis="'.$metData['idpolicy'].'" AND
																							        "'.$metData['tenor'].'" BETWEEN tenorfrom AND tenorto AND
																							        currentmonth="'.$blnberjalan.'"'));
                $_klaimRate = $metRateKlaim['rate'];
                $nilai_K = $metData['plafond'] * ($_klaimRate / 1000);
                $nilaiklaim_ = '<dt>Rate Claim</dt><dd><span class="semibold text-success">'.$_klaimRate.'</span></dd>
														<dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($nilai_K).'</span></dd>';
            } else {
                $_klaimRate = $metData['klaimpercentage'];
                $nilai_K = $metData['plafond'] * ($_klaimRate / 100);
                $nilaiklaim_ = '<dt>Payment Claim</dt><dd><span class="semibold text-danger">'.duit($nilai_K).'</span></dd>';
            }
            echo '</dd>
				            <input type="hidden" name="nilaiklaimmember" value="'.$nilai_K.'">'.$nilaiklaim_.'
						</dl>
					</div>
				</div>
				<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
				<div class="panel-body">
					<table class="table">
		        	<tbody>';
            if ($tmptDeath['nama']=="HOSPITAL") {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Police" AND ';
            } elseif ($tmptDeath['nama']=="HOME") {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Hospital" AND ajkdocumentclaim.opsional !="Police" AND ';
            } else {
                $dokumenPOD = 'ajkdocumentclaim.opsional !="Hospital" AND ';
            }
            $metDok = $database->doQuery('SELECT ajkdocumentclaimpartner.id,
											 ajkdocumentclaimpartner.idbroker,
											 ajkdocumentclaimpartner.idclient,
											 ajkdocumentclaimpartner.idpolicy,
											 ajkdocumentclaim.namadokumen,
											 ajkdocumentclaim.opsional
									 FROM ajkdocumentclaimpartner
									 INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
									 WHERE ajkdocumentclaimpartner.idbroker="'.$metData['idbroker'].'" AND
									 	   ajkdocumentclaimpartner.idclient="'.$metData['idclient'].'" AND
									 	   ajkdocumentclaimpartner.idpolicy="'.$metData['idpolicy'].'" AND
									 	   '.$dokumenPOD.'
									 	   ajkdocumentclaimpartner.del IS NULL
									 ORDER BY ajkdocumentclaimpartner.iddoc ASC');
            $no=1;
            while ($metDok_ = mysql_fetch_array($metDok)) {
                echo '<tr><td width="1%">
				  <div class="checkbox custom-checkbox nm">
		          <input type="hidden" name="dokKlaim[]" id="customcheckbox'.$no.'" value="'.$metDok_['id'].'" checked>
		          <input type="checkbox" name="dokKlaim[]" id="customcheckbox'.$no.'" value="'.$metDok_['id'].'" checked disabled>
		          <label for="customcheckbox'.$no.'"></label>
		          </div>
		          </td>
		          <td>'.$metDok_['namadokumen'].'</td>
			  </tr>';
                $no++;
            }
            echo '</tbody>
		          </table>
			</div>
			<div class="panel-footer"><input type="hidden" name="cc" value="setReqClaim">'.BTN_SUBMIT.'</div>
			</div>
			</form>
			</div>';
            echo '</div>
		</div>';
            echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
				<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
        }
            ;
    break;

    case "newClaim":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div>
		      <div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=cclaim&cc=nclaim">'.BTN_BACK.'</a></div></div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.phk,
		ajkpolis.wp,
		ajkpolis.general,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.tgldebitnote,
		ajkcabang.`name` AS cabang,
		ajkpeserta.id,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
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
		ajkpeserta.statusaktif
		FROM ajkpeserta
		INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		WHERE ajkpeserta.id = "'.$thisEncrypter->decode($_REQUEST['idm']).'"'));

        echo '<div class="row">
				<div class="col-md-12">';
        //CEK KLAIM GENERAL
        if ($metData['general']=="Y") {
            echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate>
			  <input type="hidden" name="idm" value="'.$thisEncrypter->encode($metData['id']).'">
			  <div class="panel-heading"><h3 class="panel-title">Select Claim Member</h3></div>
			  	<div class="panel-body">
		    			<div class="col-sm-12 text-center">
		        		<span class="radio custom-radio custom-radio-primary">
		            	<div class="col-sm-4">
		                    <!--<span class="radio custom-radio custom-radio-primary text-right">
		                    <input type="radio" id="customradio1" name="tipe" value="ajk"><label for="customradio1">&nbsp;&nbsp;<strong>AJK</strong></label>
		                    </span>-->
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=ajk"><font color="white"><button type="button" class="btn btn-danger">A J K</button></a>
		                </div>
						<div class="col-sm-4">
						    <!--<span class="radio custom-radio custom-radio-teal text-center">
		                    <input type="radio" id="customradio2" name="tipe" value="fire"><label for="customradio2">&nbsp;&nbsp;<strong>FIRE</strong></label>
		                    </span>-->
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=fire"><button type="button" class="btn btn-danger"><font color="white">F I R E</button></a>
		                </div>
		    			<div class="col-sm-4">
						    <!--<span class="radio custom-radio custom-radio-teal text-left">
		                    <input type="radio" id="customradio3" name="tipe" value="ajkfire"><label for="customradio3">&nbsp;&nbsp;<strong>AJK + FIRE</strong></label>
		                    </span>-->
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=ajkfire"><font color="white"><button type="button" class="btn btn-danger">A J K + F I R E</button></a>
		                </div>
		            	</span>
					</div>
				</div>
				<!--<div class="panel-footer text-center">'.BTN_SUBMIT.'</div>-->
			</div>
			</form>';
        }
        //CEK KLAIM PHK dan WP
        elseif ($metData['phk']=="Y" or $metData['wp']=="Y") {
            echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate>
			  <input type="hidden" name="idm" value="'.$thisEncrypter->encode($metData['id']).'">
			  <div class="panel-heading"><h3 class="panel-title">Select Claim Member</h3></div>
			  	<div class="panel-body">
		    			<div class="col-sm-12 text-center">
		        		<span class="radio custom-radio custom-radio-primary">
		            	<div class="col-sm-4">
		                    <!--<span class="radio custom-radio custom-radio-primary text-right">
		                    <input type="radio" id="customradio1" name="tipe" value="ajk"><label for="customradio1">&nbsp;&nbsp;<strong>AJK</strong></label>
		                    </span>-->
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=ajk"><font color="white"><button type="button" class="btn btn-warning">A J K</button></a>
		                </div>
						<div class="col-sm-4">
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=phk"><button type="button" class="btn btn-warning"><font color="white">P H K</button></a>
		                </div>
		    			<div class="col-sm-4">
		                    <a href="ajk.php?re=cclaim&cc=klaimgeneral&idm='.$thisEncrypter->encode($metData['id']).'&tipe=kreditmacet"><font color="white"><button type="button" class="btn btn-warning">Kredit Macet</button></a>
		                </div>
		            	</span>
					</div>
				</div>
				<!--<div class="panel-footer text-center">'.BTN_SUBMIT.'</div>-->
			</div>
			</form>';
        }
        //CEK KLAIM PHK dan WP
        else {
            echo '<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate>
			  <input type="hidden" name="idm" value="'.$thisEncrypter->encode($metData['id']).'">
			  <div class="panel-heading"><h3 class="panel-title">Data Claim Member</h3></div>
				<div class="panel-body">
						<div class="alert alert-dismissable alert-success text-center">
		                <strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
						</div>
						<div class="col-md-6">
						<dl class="dl-horizontal">
		                	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
		                	<dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
		                	<dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
		                	<dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
		                	<dt>Age</dt><dd>'.$metData['usia'].' years</dd>
		                	<dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd><br />
		                	<dt>Value claim submitted</dt><dd><strong><input name="klaimdiajukan" value="'.$_REQUEST['klaimdiajukan'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Value claim submitted" required></strong></dd>
							<dt>Place of Death</dt>
							<dd><select name="tmptmeninggal" class="form-control" required>
				            	<option value="">Choose</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['tmptmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
							</dd>
							<dt>Cause of Death</dt>
							<dd><select name="penyebabmeninggal" class="form-control" required>
				            	<option value="">Choose</option>';
            $metPlaceDeath = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" ORDER by nama ASC');
            while ($metPlaceDeath_ = mysql_fetch_array($metPlaceDeath)) {
                echo '<option value="'.$metPlaceDeath_['id'].'"'._selected($_REQUEST['penyebabmeninggal'], $metPlaceDeath_['id']).'>'.$metPlaceDeath_['nama'].'</option>';
            }
            echo '</select>
							</dd>
		                	<dt>Date of Death</dt><dd><input type="text" name="dod" class="form-control" id="datepicker3" value="'.$_REQUEST['dod'].'" placeholder="Select a date" required/></dd>
						</dl>
						</div>
						<div class="col-md-6">
						<dl class="dl-horizontal">
		                	<dt>Date of Debitnote</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
		                	<dt>Debitnote</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
		                	<dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
		                	<dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
		                	<dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
		                	<dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd><br />
		                	<dt>Date of receipt documents</dt><dd><input type="text" name="tglterima" class="form-control" id="datepicker31" value="'.$_REQUEST['tglterima'].'" placeholder="Date of receipt documents"/></dd>
		                	<dt>Date of completion <br />documents</dt><dd><input type="text" name="tgllengkap" class="form-control" id="datepicker32" value="'.$_REQUEST['tgllengkap'].'" placeholder="Date of completion documents"/></dd>
		                	<dt>Date of info insurance</dt><dd><input type="text" name="tglinfoasuransi" class="form-control" id="datepicker33" value="'.$_REQUEST['tglinfoasuransi'].'" placeholder="Date of info insurance" /></dd>
		                	<dt>Date of send documents <br />to insurance</dt><dd><input type="text" name="tglkirimasuransi" class="form-control" id="datepicker34" value="'.$_REQUEST['tglkirimasuransi'].'" placeholder="Date of send documents to insurance"/></dd>
						</dl>
						</div>
						</div>
					<div class="panel-footer"><input type="hidden" name="cc" value="setClaim">'.BTN_SUBMIT.'</div>
					</div>
					</form>';
        }
        echo '	</div>';
        echo '</div>
			</div>';
        echo '<!--<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>-->
			<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
                ;
    break;

    case "nclaim":
        echo '<div class="page-header-section"><h2 class="title semibold">Claim Member</h2></div></div>
			<div class="row">
			<div class="col-md-12">';
        if ($_REQUEST['src']=="claimdata") {
            if ($_REQUEST['idmember']=="" and $_REQUEST['name']=="") {
                $metnotif .= '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Error!</strong> Please insert id member or name member !</div>';
            } else {
                if ($_REQUEST['idmember'] and !$_REQUEST['name']) {
                    $satu = 'ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%"';
                }
                if ($_REQUEST['name'] and !$_REQUEST['idmember']) {
                    $dua = 'ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }
                if ($_REQUEST['name'] and $_REQUEST['idmember']) {
                    $tiga = 'ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%" OR ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }
                $metKlaim = $database->doQuery('SELECT
		ajkpolis.id AS idproduk,
		ajkpolis.produk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS brokerclient,
		ajkpeserta.id AS idm,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.tgllahir,
		ajkpeserta.usia,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.statuslunas,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkpeserta.astotalpremi,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote
		FROM ajkpeserta
		INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkpeserta.statusaktif = "Inforce" AND ajkpeserta.statuslunas="1" '.$q___1.' AND ( '.$satu.' '.$dua.' '.$tiga.')');

                $metSRC .= '<div class="panel panel-default">
		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
			<th>Partner</th>
			<th>Product</th>
			<th width="20%">Debit Note</th>
			<th width="1%">Date Debit Note</th>
			<th width="1%">ID Member</th>
			<th width="1%">Member</th>
			<th width="1%">Age</th>
			<th width="1%">Plafond</th>
			<th width="1%">Start Date Insurance</th>
			<th width="1%">Tenor</th>
			<th width="1%">End Date Insurance</th>
			<th width="1%">Premium (Bank)</th>
			<th width="1%">Premium (Ins)</th>
			<th width="1%">Branch</th>
			<th width="1%">Option</th>
		</tr>
		</thead>
		<tbody>';
                while ($metKlaim_ = mysql_fetch_array($metKlaim)) {
                    $_metCN = mysql_fetch_array($database->doQuery('SELECT id, idpeserta, status FROM ajkcreditnote WHERE idpeserta="'.$metKlaim_['idm'].'" AND del IS NULL'));
                    if ($_metCN['id']) {
                        $met_btn = '<td align="center"><span class="label label-primary">'.$_metCN['status'].'</span></td>';
                    } else {
                        $met_btn = '<td align="center"><a href="ajk.php?re=cclaim&cc=newClaim&idm='.$thisEncrypter->encode($metKlaim_['idm']).'">'.BTN_KLAIM.'</a></td>';
                    }
                    $metSRC .= '<tr><td align="center">'.++$no.'</td>
						<td>'.$metKlaim_['brokerclient'].'</td>
		   				<td>'.$metKlaim_['produk'].'</td>
		   				<td>'.$metKlaim_['nomordebitnote'].'</td>
		   				<td align="center">'._convertDate($metKlaim_['tgldebitnote']).'</td>
		   				<td align="center">'.$metKlaim_['idpeserta'].'</td>
		   				<td>'.$metKlaim_['nama'].'</td>
		   				<td align="center">'.$metKlaim_['usia'].'</td>
		   				<td align="right">'.duit($metKlaim_['plafond']).'</td>
		   				<td align="center">'._convertDate($metKlaim_['tglakad']).'</td>
		   				<td align="center">'.$metKlaim_['tenor'].'</td>
		   				<td align="center">'._convertDate($metKlaim_['tglakhir']).'</td>
		   				<td align="right">'.duit($metKlaim_['totalpremi']).'</td>
		   				<td align="right">'.duit($metKlaim_['astotalpremi']).'</td>
		   				<td align="center">'.$metKlaim_['cabang'].'</td>
		   				'.$met_btn.'
		    		</tr>';
                }
                //$metSRC = $_REQUEST['idmember'].'<br />'.$_REQUEST['name'].'<br />';
                $metSRC .= '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Member"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			</tr>
			</tfoot></table>
						</div>
					</div>
				</div>
			</div>';
            }
        }
        echo '<div class="row">
			<div class="col-md-12">
			'.$metnotif.'
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">New Form Claim</h3></div>
				<div class="panel-body">
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">ID Member</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="idmember" class="form-control" data-parsley-type="number" value="'.$_REQUEST['idmember'].'" placeholder="ID Member"/></div></div>
						</div>
			        </div>
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">Name</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="name" class="form-control" value="'.$_REQUEST['name'].'" placeholder="Name"/></div></div>
						</div>
			        </div>
		        </div>
				<div class="panel-footer"><input type="hidden" name="src" value="claimdata">'.BTN_SUBMIT.'</div>
		    </form>
		    </div>
			</div>';
        echo $metSRC;
        echo '</div>
			</div>';
                ;
    break;

    case "dbatal":
        // $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE (status = "Process" OR status = "Approve") '.$q___.' AND tipeklaim = "Batal" AND del IS NULL'));
        $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE status = "Approve" '.$q___.' AND tipeklaim="Batal" AND del IS NULL'));
        //echo $metDataMbr['jdata'];
        // if ($metDataMbr['jdata']>=1) {
        // 	$claimREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqClaim" title="jumlah pengajuan data klaim"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        // }else{
        // 	$claimREG = '';
        // }

        if ($metDataMbr['jdata']>=1) {
            $batalREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqBatal&back=dbatal" title="jumlah pengajuan data klaim"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        } else {
            $batalREG = '';
        }

        echo '<div class="page-header-section"><h2 class="title semibold">Credit Note Cancel</h2></div>
						      	<div class="page-header-section">
								'.$batalREG.'
								</div>
						      </div>';
                echo '<div class="row">
						      	<div class="col-md-12">
						        	<div class="panel panel-default">

						<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
						<thead>
						<tr><th width="1%">No</th>
							<th width="1%">Broker</th>
							<th>Partner</th>
							<th>Product</th>
							<th width="1%">Credit Note</th>
							<th width="1%">ID Member</th>
							<th width="1%">Name</th>
							<th width="1%">Plafond</th>
							<th width="1%">Start Insurance</th>
							<th width="1%">Tenor</th>
							<th width="1%">Last Insurance</th>
							<th width="1%">Date Claim</th>
							<th width="10%">Payment Claim</th>
							<th width="10%">Status</th>
							<th width="10%">Branch</th>
						</tr>
						</thead>
						<tbody>';
                $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS namebroker,
		ajkclient.`name` AS nameclient,
		ajkpolis.produk,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.nomorcreditnote,
		ajkcreditnote.`status`,
		ajkcreditnote.tglbayar,
		ajkcreditnote.tglklaim,
		ajkcreditnote.status,
		ajkcreditnote.nilaiclaimclient
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.status != "Request" AND ajkcreditnote.tipeklaim = "Batal" AND ajkcreditnote.del IS NULL '.$q___1.'
		ORDER BY ajkcreditnote.id DESC');
                while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
                    if ($metCreditnote_['status']=="Process") {
                        $metglow = 'info';
                    } elseif ($metCreditnote_['status']=="Batal" or $metCreditnote_['status']=="Cancel") {
                        $metglow = 'danger';
                    } elseif ($metCreditnote_['status']=="Investigation") {
                        $metglow = 'warning';
                    } elseif ($metCreditnote_['status']=="Approve Unpaid") {
                        $metglow = 'primary';
                    } elseif ($metCreditnote_['status']=="Approve Paid") {
                        $metglow = 'success';
                    } else {
                        $metglow = 'warning';
                    }
                    echo '<tr>
				   	<td align="center">'.++$no.'</td>
				   	<td>'.$metCreditnote_['namebroker'].'</td>
				   	<td>'.$metCreditnote_['nameclient'].'</td>
				   	<td align="center">'.$metCreditnote_['produk'].'</td>
				   	<td align="center"><a href="ajk.php?re=dlPdf&pdf=dlPdfcn&cID='.$thisEncrypter->encode($metCreditnote_['nomorcreditnote']).'&idc='.$thisEncrypter->encode($metCreditnote_['id']).'" target="blank">'.$metCreditnote_['nomorcreditnote'].'</a></td>
				   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
				   	<td align="center">'.$metCreditnote_['nama'].'</td>
				   	<td align="right">'.duit($metCreditnote_['plafond']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakad']).'</td>
				   	<td align="center">'.$metCreditnote_['tenor'].'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakhir']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
				   	<td align="right">'.duit($metCreditnote_['nilaiclaimclient']).'</td>
				   	<td align="center"><a href="#"><span class="semibold text-'.$metglow.'">'.$metCreditnote_['status'].'</span></a></td>
				   	<td>'.$metCreditnote_['cabang'].'</td>
				    </tr>';
                }
                echo '</tbody>
								<tfoot>
						        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
						            <th><input type="hidden" class="form-control" name="search_engine"></th>
						            <th><input type="search" class="form-control" name="search_engine" placeholder="Cabang"></th>
						        </tr>
						        </tfoot></table>
						    	</div>
								</div>
						    </div>
						</div>';
                ;
    break;

    case "drefund":
        $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE (status = "Process" OR status = "Approve") '.$q___.' AND tipeklaim IN ("Refund","Topup") AND del IS NULL'));
        //echo $metDataMbr['jdata'];
        if ($metDataMbr['jdata']>=1) {
            $claimREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqRefund" title="jumlah pengajuan data Refund"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        } else {
            $claimREG = '';
        }
        echo '<div class="page-header-section"><h2 class="title semibold">Credit Note Refund</h2></div>
				      	<div class="page-header-section">
						'.$claimREG.'
						</div>
				      </div>';
                echo '<div class="row">
				      	<div class="col-md-12">
				        	<div class="panel panel-default">

				<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
				<thead>
				<tr><th width="1%">No</th>
					<th width="1%">Broker</th>
					<th>Partner</th>
					<th>Product</th>
					<th width="1%">Credit Note</th>
					<th width="1%">ID Member</th>
					<th width="1%">Name</th>
					<th width="1%">Plafond</th>
					<th width="1%">Start Insurance</th>
					<th width="1%">Tenor</th>
					<th width="1%">Last Insurance</th>
					<th width="1%">Date Claim</th>
					<th width="10%">Payment Claim</th>
					<th width="10%">Status</th>
					<th width="10%">Branch</th>
				</tr>
				</thead>
				<tbody>';
                $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS namebroker,
		ajkclient.`name` AS nameclient,
		ajkpolis.produk,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.nomorcreditnote,
		ajkcreditnote.`status`,
		ajkcreditnote.tglbayar,
		ajkcreditnote.tglklaim,
		ajkcreditnote.status,
		ajkcreditnote.nilaiclaimclient
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.status != "Request" AND ajkcreditnote.tipeklaim IN ("Refund","Topup") AND ajkcreditnote.del IS NULL '.$q___1.'
		ORDER BY ajkcreditnote.id DESC');
                while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
                    if ($metCreditnote_['status']=="Process") {
                        $metglow = 'info';
                    } elseif ($metCreditnote_['status']=="Batal" or $metCreditnote_['status']=="Cancel") {
                        $metglow = 'danger';
                    } elseif ($metCreditnote_['status']=="Investigation") {
                        $metglow = 'warning';
                    } elseif ($metCreditnote_['status']=="Approve Unpaid") {
                        $metglow = 'primary';
                    } elseif ($metCreditnote_['status']=="Approve Paid") {
                        $metglow = 'success';
                    } else {
                        $metglow = 'warning';
                    }
                    echo '<tr>
				   	<td align="center">'.++$no.'</td>
				   	<td>'.$metCreditnote_['namebroker'].'</td>
				   	<td>'.$metCreditnote_['nameclient'].'</td>
				   	<td align="center">'.$metCreditnote_['produk'].'</td>
				   	<td align="center"><a href="ajk.php?re=dlPdf&pdf=dlPdfcn&cID='.$thisEncrypter->encode($metCreditnote_['nomorcreditnote']).'&idc='.$thisEncrypter->encode($metCreditnote_['id']).'" target="blank">'.$metCreditnote_['nomorcreditnote'].'</a></td>
				   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
				   	<td align="center">'.$metCreditnote_['nama'].'</td>
				   	<td align="right">'.duit($metCreditnote_['plafond']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakad']).'</td>
				   	<td align="center">'.$metCreditnote_['tenor'].'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglakhir']).'</td>
				   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
				   	<td align="right">'.duit($metCreditnote_['nilaiclaimclient']).'</td>
				   	<td align="center"><a href="ajk.php?re=cclaim&cc=reqRefundData&id='.$thisEncrypter->encode($metCreditnote_['id']).'"><span class="semibold text-'.$metglow.'">'.$metCreditnote_['status'].'</span></a></td>
				   	<td>'.$metCreditnote_['cabang'].'</td>
				    </tr>';
                }
                echo '</tbody>
						<tfoot>
				        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
				            <th><input type="hidden" class="form-control" name="search_engine"></th>
				            <th><input type="search" class="form-control" name="search_engine" placeholder="Cabang"></th>
				        </tr>
				        </tfoot></table>
				    	</div>
						</div>
				    </div>
				</div>';
            ;
    break;

    case "reqRefund":
        echo '<div class="page-header-section"><h2 class="title semibold">Credit Note</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
			<th width="1%">Broker</th>
			<th>Partner</th>
			<th>Product</th>
			<th>Asuransi</th>
			<th>ID Member</th>
			<th>Name</th>
			<th>Date Claim</th>
			<th>Payment Claim</th>
			<th>Status</th>
			<th>Input User</th>
			<th>Input Date</th>
		</tr>
		</thead>
		<tbody>';
        $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS broker,
		ajkclient.`name` AS client,
		ajkpolis.produk,
		ajkinsurance.`name` AS asuransi,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tglklaim,
		ajkcreditnote.nilaiclaimclient,
		useraccess.firstname AS userinput,
		ajkcreditnote.input_by,
		DATE_FORMAT(ajkcreditnote.input_time,"%Y-%m-%d") AS tglinput,
		ajkcreditnote.approve_by,
		DATE_FORMAT(ajkcreditnote.approve_time,"%Y-%m-%d") AS tglapprve,
		ajkcreditnote.status AS statusklaim,
		ajkcreditnote.tipeklaim
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN useraccess ON ajkcreditnote.input_by = useraccess.id
		WHERE (ajkcreditnote.status = "Process" OR ajkcreditnote.status = "Approve") AND
			   ajkcreditnote.del IS NULL '.$q___1.'  AND
			   ajkcreditnote.tipeklaim IN ("Refund","Topup") AND ajkpeserta.del IS NULL
		ORDER BY ajkcreditnote.id DESC');
        while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
            echo '<tr>
		   	<td align="center">'.++$no.'</td>
		   	<td>'.$metCreditnote_['broker'].'</td>
		   	<td>'.$metCreditnote_['client'].'</td>
		   	<td>'.$metCreditnote_['produk'].'</td>
		   	<td>'.$metCreditnote_['asuransi'].'</td>
		   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
		   	<td><a href="ajk.php?re=cclaim&cc=reqRefundData&id='.$thisEncrypter->encode($metCreditnote_['id']).'">'.$metCreditnote_['nama'].'</a></td>
		   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
		   	<td>'.duit($metCreditnote_['nilaiclaimclient']).'</td>
		   	<td>'.$metCreditnote_['statusklaim'].'</td>
		   	<td>'.$metCreditnote_['userinput'].'</td>
		   	<td>'._convertDate($metCreditnote_['tglinput']).'</td>
		    </tr>';
        }
        echo '</tbody>
				<tfoot>
		        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="ID Member"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		        </tr>
		        </tfoot></table>
		    	</div>
				</div>
		    </div>
		</div>';
                ;
    break;

    case "reqRefundData":
        echo '<div class="page-header-section"><h2 class="title semibold">Refund Member</h2></div>
		      	<div class="page-header-section">
		        <div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqRefund">'.BTN_BACK.'</a></div>
				</div>
		      </div>';
        $metData = mysql_fetch_array($database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcreditnote.idbroker,
		ajkcreditnote.idclient,
		ajkcreditnote.idpeserta AS idmember,
		ajkcreditnote.idproduk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkpolis.klaimrate,
		ajkpolis.klaimpercentage,
		ajkinsurance.`name` AS asuransi,
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
		ajkpeserta.statusaktif,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkcreditnote.tipeklaim,
		ajkcreditnote.tglklaim,
		ajkcreditnote.keterangan,
		ajkcreditnote.nilaiclaimclient
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkinsurance ON ajkcreditnote.idas = ajkinsurance.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkcreditnote.id = "'.$thisEncrypter->decode($_REQUEST['id']).'"'));
        if ($_REQUEST['approverefund']=="DataApprovRefund") {
            $metCN = mysql_fetch_array($database->doQuery('SELECT Max(idcn) AS idcn FROM ajkcreditnote WHERE idbroker ="'.$_REQUEST['idbroker'].'" AND del IS NULL'));
            if ($_REQUEST['idbroker'] < 9) {
                $kodeBroker = '0'.$_REQUEST['idbroker'];
            } else {
                $kodeBroker = $_REQUEST['idbroker'];
            }
            $fakcekcn = $metCN['idcn'] + 1;
            $idNumber = 100000000 + $fakcekcn;
            $autoNumber = substr($idNumber, 1);	// ID PESERTA //
            $creditnoteNumber = "CN.B".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
            $metCreateBtl = $database->doQuery('UPDATE ajkcreditnote SET idcn="'.$fakcekcn.'",
												   					 nomorcreditnote="'.$creditnoteNumber.'",
												   					 tglcreditnote="'.$futoday.'",
												   					 status="Restitusi",
												   					 create_by = "'.$q['id'].'",
												   					 create_time = "'.$futgl.'"
												   					 WHERE id="'.$_REQUEST['cnID'].'"');
            $metDebBatal = $database->doQuery('UPDATE ajkpeserta SET idcn="'.$_REQUEST['cnID'].'", statusaktif="Refund", statuspeserta="Restitusi" WHERE id="'.$_REQUEST['idpeserta'].'"');
            echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=cclaim&cc=restitusi">
			  <div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Success!</strong> Data debitur has been canceled by '.$q['username'].'</div>';
        }
        echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<input type="hidden" name="idbroker" value="'.$metData['idbroker'].'">
				<input type="hidden" name="idpeserta" value="'.$metData['idmember'].'">
				<input type="hidden" name="cnID" value="'.$metData['id'].'">
				<div class="panel-heading"><h3 class="panel-title">Data Refund Member</h3></div>
				<div class="panel-body">
					<div class="alert alert-dismissable alert-success text-center">
					<strong>'.$metData['brokername'].'</strong><br />'.$metData['clientname'].' ('.$metData['produk'].')
					</div>
					<div class="col-md-7">
					<dl class="dl-horizontal">
			        	<dt>KTP</dt><dd>'.$metData['nomorktp'].'</dd>
			            <dt>ID Member</dt><dd>'.$metData['idpeserta'].'</dd>
			            <dt>Name</dt><dd><strong>'.$metData['nama'].'</strong></dd>
			            <dt>DOB</dt><dd>'._convertDate($metData['tgllahir']).'</dd>
			            <dt>Age</dt><dd>'.$metData['usia'].' years</dd>
			            <dt>Status Member</dt><dd>'.$metData['statusaktif'].'</dd>
						<dt>Refund Date</dt><dd><strong>'._convertDate($metData['tglklaim']).'</strong></dd>
						<dt>Refund Note</dt><dd><strong>'.$metData['keterangan'].'</strong></dd>
					</dl>
					</div>
					<div class="col-md-5">
					<dl class="dl-horizontal">
						<dt>Date Debit Note</dt><dd>'._convertDate($metData['tgldebitnote']).'</dd>
					    <dt>Debit Note</dt><dd><strong>'.$metData['nomordebitnote'].'</strong></dd>
					    <dt>Plafond</dt><dd>'.duit($metData['plafond']).'</dd>
					    <dt>Date Insurance</dt><dd>'._convertDate($metData['tglakad']).' to '._convertDate($metData['tglakhir']).'</dd>
					    <dt>Tenor</dt><dd>'.$metData['tenor'].' month</dd>
					    <dt>Total Premium</dt><dd><strong>'.duit($metData['totalpremi']).'</strong></dd>
					    <dt>Current Refund Date</dt><dd>';
            $monthins = datediff($metData['tglakad'], $metData['tglklaim']);
            $monthins = explode(",", $monthins);
            //echo '<br />'.$monthins[0].'-'.$monthins[1].'-'.$monthins[2];
            if ($monthins[0]>1) {
                $wordyear = "".$monthins[0]." years";
            } else {
                $wordyear = "".$monthins[0]." year";
            }
            if ($monthins[1]>1) {
                $wordmonth = "".$monthins[1]." months";
            } else {
                $wordmonth = "".$monthins[1]." month";
            }
            if ($monthins[1]>1) {
                $wordday = "".$monthins[2]." days";
            } else {
                $wordday = "".$monthins[2]." day";
            }
            echo $wordyear.' '.$wordmonth.' '.$wordday;
            $thnbln = $monthins[0] * 12;	//jumlah tahun * 12
            //echo '<br />'.$thnbln;
            //echo '<br />'.$bulanjalan;
            echo '</dd>
					<dd>
				<dt>Payment Refund</dt><dd><span class="semibold text-danger">'.duit($metData['nilaiclaimclient']).'</span></dd>
							</dl>
						</div>
					</div>
			<div class="panel-footer"><input type="hidden" name="approverefund" value="DataApprovRefund">'.BTN_SUBMIT.'</div>

				</form>
				</div>';
                echo '</div>
			</div>';

            ;
    break;
    case "refund":
        $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE status = "Approve" '.$q___.' AND tipeklaim="Restitusi" AND del IS NULL'));
        if ($metDataMbr['jdata']>=1) {
            $batalREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqRefund" title="jumlah pengajuan data refund"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        } else {
            $batalREG = '';
        }

        echo '<div class="page-header-section"><h2 class="title semibold">Member Refund</h2></div>
				<div class="page-header-section">
				'.$batalREG.'
				</div>
			</div>
			<div class="row">
			<div class="col-md-12">';
        if ($_REQUEST['src']=="claimdatarestitusi") {
            if ($_REQUEST['idmember']=="" and $_REQUEST['name']=="") {
                $metnotif .= '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><strong>Error!</strong> Please insert id member or name !</div>';
            } else {
                if ($_REQUEST['idmember'] and !$_REQUEST['name']) {
                    $satu = 'AND ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%"';
                }
                if ($_REQUEST['name'] and !$_REQUEST['idmember']) {
                    $dua = 'AND ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }
                if ($_REQUEST['name'] and $_REQUEST['idmember']) {
                    $tiga = 'AND ajkpeserta.idpeserta LIKE "%'.$_REQUEST['idmember'].'%" OR ajkpeserta.nama LIKE "%'.$_REQUEST['name'].'%"';
                }
                $metKlaim = $database->doQuery('SELECT
		ajkpolis.id AS idproduk,
		ajkpolis.produk,
		ajkcobroker.`name` AS brokername,
		ajkclient.`name` AS brokerclient,
		ajkpeserta.id AS idm,
		ajkpeserta.idbroker,
		ajkpeserta.idclient,
		ajkpeserta.idpolicy,
		ajkpeserta.iddn,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.tgllahir,
		ajkpeserta.usia,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.statuslunas,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkpeserta.astotalpremi,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkpolis.jumlahharibatal,
		datediff(current_date(), ajkdebitnote.tgldebitnote) AS jumlahhari
		FROM ajkpeserta INNER JOIN ajkcobroker ON ajkpeserta.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
		INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
		INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
		WHERE ajkpeserta.statusaktif = "Inforce" AND ajkpeserta.statuslunas="0" '.$q___1.' '.$satu.' '.$dua.' '.$tiga.'
		AND ajkpolis.jumlahharibatal >= datediff(current_date(), ajkdebitnote.tgldebitnote)');

                $metSRC .= '<div class="panel panel-default">
					<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
					<thead>
					<tr><th width="1%">No</th>
						<th>Partner</th>
						<th>Product</th>
						<th width="20%">Debit Note</th>
						<th width="1%">Date Debit Note</th>
						<th width="1%">ID Member</th>
						<th width="1%">Member</th>
						<th width="1%">Age</th>
						<th width="1%">Plafond</th>
						<th width="1%">Start Date Insurance</th>
						<th width="1%">Tenor</th>
						<th width="1%">End Date Insurance</th>
						<th width="1%">Premium (Bank)</th>
						<th width="1%">Premium (Ins)</th>
						<th width="1%">Branch</th>
						<th width="1%">Cancel Member (days)</th>
						<th width="1%">Option</th>
					</tr>
					</thead>
					<tbody>';
                while ($metKlaim_ = mysql_fetch_array($metKlaim)) {
                    $_metCN = mysql_fetch_array($database->doQuery('SELECT id, idpeserta, status FROM ajkcreditnote WHERE idpeserta="'.$metKlaim_['idm'].'" AND del IS NULL'));
                    if ($_metCN['id']) {
                        $met_btn = '<td align="center"><span class="label label-primary">'.$_metCN['status'].'</span></td>';
                    } else {
                        $met_btn = '<td align="center"><a href="ajk.php?re=cclaim&cc=newClaimCancel&idm='.$thisEncrypter->encode($metKlaim_['idm']).'">'.BTN_CANCEL.'</a></td>';
                    }
                    $metSRC .= '<tr><td align="center">'.++$no.'</td>
						<td>'.$metKlaim_['brokerclient'].'</td>
						<td>'.$metKlaim_['produk'].'</td>
						<td>'.$metKlaim_['nomordebitnote'].'</td>
						<td align="center">'._convertDate($metKlaim_['tgldebitnote']).'</td>
						<td align="center">'.$metKlaim_['idpeserta'].'</td>
						<td>'.$metKlaim_['nama'].'</td>
						<td align="center">'.$metKlaim_['usia'].'</td>
						<td align="right">'.duit($metKlaim_['plafond']).'</td>
						<td align="center">'._convertDate($metKlaim_['tglakad']).'</td>
						<td align="center">'.$metKlaim_['tenor'].'</td>
						<td align="center">'._convertDate($metKlaim_['tglakhir']).'</td>
						<td align="right">'.duit($metKlaim_['totalpremi']).'</td>
						<td align="right">'.duit($metKlaim_['astotalpremi']).'</td>
						<td align="center">'.$metKlaim_['cabang'].'</td>
						<td align="center"><button type="button" class="btn btn-inverse btn-rounded btn-xs mb5"><strong>'.duit($metKlaim_['jumlahharibatal']).'</strong></button> -
										   <button type="button" class="btn btn-info btn-rounded btn-xs mb5"><strong>'.duit($metKlaim_['jumlahhari']).'</strong></button>
						</td>
						'.$met_btn.'
					</tr>';
                }
                //$metSRC = $_REQUEST['idmember'].'<br />'.$_REQUEST['name'].'<br />';
                $metSRC .= '</tbody>
					<tfoot>
					<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Member"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
					</tr>
					</tfoot></table>
					</div>
				</div>
			</div>
		</div>';
            }
        }
        echo '<div class="row">
			<div class="col-md-12">
			'.$metnotif.'
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">New Form Restitution Member</h3></div>
				<div class="panel-body">
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">ID Member</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="idmember" class="form-control" data-parsley-type="number" value="'.$_REQUEST['idmember'].'" placeholder="ID Member"/></div></div>
						</div>
			        </div>
			    	<div class="form-group">
				    	<label class="control-label col-sm-2">Name</label>
			            <div class="col-sm-10">
						<div class="row"><div class="col-md-10"><input type="text" name="name" class="form-control" value="'.$_REQUEST['name'].'" placeholder="Name"/></div></div>
						</div>
			        </div>
		        </div>
				<div class="panel-footer"><input type="hidden" name="src" value="claimdatarestitusi">'.BTN_SUBMIT.'</div>
		    </form>
		    </div>
			</div>';
        echo $metSRC;
        echo '</div>
			</div>';
            ;
    break;
    default:
        $metDataMbr = mysql_fetch_array($database->doQuery('SELECT COUNT(id) AS jdata FROM ajkcreditnote WHERE status = "Process" '.$q___.' AND tipeklaim="Claim" AND del IS NULL'));
        //echo $metDataMbr['jdata'];
        if ($metDataMbr['jdata']>=1) {
            $claimREG = '<div class="toolbar"><a href="ajk.php?re=cclaim&cc=reqClaim" title="jumlah pengajuan data klaim"><span class="number"><span class="label label-danger">'.$metDataMbr['jdata'].' Data</span></span></a></div>';
        } else {
            $claimREG = '';
        }
        echo '<div class="page-header-section"><h2 class="title semibold">Credit Note Claim</h2></div>
		      	<div class="page-header-section">
				'.$claimREG.'
				</div>
		      </div>';
        echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
			<th width="1%">Broker</th>
			<th>Partner</th>
			<th>Product</th>
			<th width="1%">Credit Note</th>
			<th width="1%">ID Member</th>
			<th width="1%">Name</th>
			<th width="1%">Plafond</th>
			<th width="1%">Start Insurance</th>
			<th width="1%">Tenor</th>
			<th width="1%">Last Insurance</th>
			<th width="1%">Date Claim</th>
			<th width="10%">Payment Claim</th>
			<th width="10%">Status</th>
			<th width="10%">Branch</th>
		</tr>
		</thead>
		<tbody>';
        $metCreditnote = $database->doQuery('SELECT
		ajkcreditnote.id,
		ajkcobroker.`name` AS namebroker,
		ajkclient.`name` AS nameclient,
		ajkpolis.produk,
		ajkpeserta.idpeserta,
		ajkpeserta.nomorktp,
		ajkpeserta.nama,
		ajkpeserta.plafond,
		ajkpeserta.tglakad,
		ajkpeserta.tenor,
		ajkpeserta.tglakhir,
		ajkpeserta.totalpremi,
		ajkcabang.`name` AS cabang,
		ajkcreditnote.tempatmeninggal,
		ajkcreditnote.penyebabmeninggal,
		ajkcreditnote.nomorcreditnote,
		ajkcreditnote.`status`,
		ajkcreditnote.tglbayar,
		ajkcreditnote.tglklaim,
		ajkcreditnote.status,
		ajkcreditnote.nilaiclaimclient,
		ajkcreditnote.nilaiclaimdibayar
		FROM ajkcreditnote
		INNER JOIN ajkcobroker ON ajkcreditnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkcreditnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkcreditnote.idproduk = ajkpolis.id
		INNER JOIN ajkpeserta ON ajkcreditnote.idpeserta = ajkpeserta.id
		INNER JOIN ajkcabang ON ajkcreditnote.idcabang = ajkcabang.er
		WHERE ajkcreditnote.status NOT IN ("Request", "Process") AND ajkcreditnote.tipeklaim in ("Death","PHK","PAW") AND ajkcreditnote.del IS NULL '.$q___1.'
		ORDER BY ajkcreditnote.id DESC');
        while ($metCreditnote_ = mysql_fetch_array($metCreditnote)) {
            if ($metCreditnote_['status']=="Process") {
                $metglow = 'info';
            } elseif ($metCreditnote_['status']=="Batal" or $metCreditnote_['status']=="Cancel") {
                $metglow = 'danger';
            } elseif ($metCreditnote_['status']=="Investigation") {
                $metglow = 'warning';
            } elseif ($metCreditnote_['status']=="Approve Unpaid") {
                $metglow = 'primary';
            } elseif ($metCreditnote_['status']=="Approve Paid") {
                $metglow = 'success';
            } else {
                $metglow = 'warning';
            }
            echo '<tr>
		   	<td align="center">'.++$no.'</td>
		   	<td>'.$metCreditnote_['namebroker'].'</td>
		   	<td>'.$metCreditnote_['nameclient'].'</td>
		   	<td align="center">'.$metCreditnote_['produk'].'</td>
		   	<td align="center"><a href="ajk.php?re=dlPdf&pdf=dlPdfcn&cID='.$thisEncrypter->encode($metCreditnote_['nomorcreditnote']).'&idc='.$thisEncrypter->encode($metCreditnote_['id']).'" target="blank">'.$metCreditnote_['nomorcreditnote'].'</a></td>
		   	<td align="center">'.$metCreditnote_['idpeserta'].'</td>
		   	<td align="center">'.$metCreditnote_['nama'].'</td>
		   	<td align="right">'.duit($metCreditnote_['plafond']).'</td>
		   	<td align="center">'._convertDate($metCreditnote_['tglakad']).'</td>
		   	<td align="center">'.$metCreditnote_['tenor'].'</td>
		   	<td align="center">'._convertDate($metCreditnote_['tglakhir']).'</td>
		   	<td align="center">'._convertDate($metCreditnote_['tglklaim']).'</td>
		   	<td align="right">'.duit($metCreditnote_['nilaiclaimdibayar']).'</td>
		   	<td align="center"><a href="ajk.php?re=cclaim&cc=apprclaim&m='.$thisEncrypter->encode($metCreditnote_['id']).'&back='.$thisEncrypter->encode('default').'"><span class="semibold text-'.$metglow.'">'.$metCreditnote_['status'].'</span></a></td>
		   	<td>'.$metCreditnote_['cabang'].'</td>
		    </tr>';
        }
        echo '</tbody>
				<tfoot>
		        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
		            <th><input type="hidden" class="form-control" name="search_engine"></th>
		            <th><input type="search" class="form-control" name="search_engine" placeholder="Cabang"></th>
		        </tr>
		        </tfoot></table>
		    	</div>
				</div>
		    </div>
		</div>';
                ;
    } // switch

    echo '</div>
			<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
	    </section>';
