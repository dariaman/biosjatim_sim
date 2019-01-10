<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['sum']) {
case "outstanding":
echo '<div class="page-header-section"><h2 class="title semibold">Summary Outstanding</h2></div>
      </div>';
echo '<div class="row">
	<div class="col-md-12 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Summary Outstanding</h3></div>
		<div class="panel-body">
			<div class="form-group">';
			if ($q['idbroker'] == NULL) {
			echo '<label class="col-sm-2 control-label">Broker</label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
				$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
				while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
		echo '</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Partner</label>
				<div class="col-sm-10"><select name="coClient" class="form-control" id="coClient" onChange="mametClientProdukRateIns(this);"><option value="">Select Partner</option></select></div>
			</div>

			<div class="form-group">
				<label class="col-lg-2 control-label">Product</label>
				<div class="col-lg-10"><select name="coProduct" class="form-control" id="coProduct"><option value="">Select Product</option></select></div>
			</div>';
			}else{
	echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
	$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
	echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
		<input type="hidden" name="coBroker" value="'.$q['idbroker'].'">
		<label class="col-sm-2 control-label">Partner</label>
			<div class="col-sm-10">
			<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);"><option value="">Select Partner</option>';
			$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
		echo '</select>
			</div>
		</div>
		<div class="form-group">
		<label class="col-lg-2 control-label">Product</label>
			<div class="col-lg-10"><select name="coProduct" class="form-control" id="coProduct"><option value="">Select Product</option></select></div>
		</div>';
		}
		echo '<div class="form-group">
                <label class="col-sm-2 control-label">Date of Debitnote <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<div class="row">
                    <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" required/></div>
                    </div>
                </div>
            </div>

			<div class="form-group">
			<label class="col-lg-2 control-label">Status</label>
            	<div class="col-sm-10">
				<select name="datastatus" class="form-control">
					<option value="">Select Status</option>
					<option value="1"'._selected($_REQUEST['datastatus'], "1").'>Inforce</option>
					<option value="2"'._selected($_REQUEST['datastatus'], "2").'>Lapse</option>
					<option value="3"'._selected($_REQUEST['datastatus'], "3").'>Maturity</option>
					<option value="4"'._selected($_REQUEST['datastatus'], "4").'>Batal</option>
			    </select>
				</div>
			</div>

		</div>
		<div class="panel-footer"><input type="hidden" name="sum" value="voutstanding">'.BTN_SUBMIT.'</div>
		</form>
</div>
</div>';

		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

case "voutstanding":
echo '<div class="page-header-section"><h2 class="title semibold">Summary Outstanding</h2></div>
      	<div class="page-header-section">
		</div>
      </div>
      <div class="row">
		<div class="col-md-12 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">';
if ($_REQUEST['coBroker']) {
	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';
	$satu_ ='AND ajkpeserta.idbroker = "'.$_REQUEST['coBroker'].'"';
}
if ($_REQUEST['coClient']) {
	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';
	$dua_ ='AND ajkpeserta.idclient = "'.$_REQUEST['coClient'].'"';
}
if ($_REQUEST['coProduct']) {
	$tiga ='AND ajkpolis.id = "'.$_REQUEST['coProduct'].'"';
	$tiga_ ='AND ajkpeserta.idpolicy = "'.$_REQUEST['coProduct'].'"';
}
$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
											  FROM ajkcobroker
											  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
											  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
											  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkdebitnote.idbroker="'.$_REQUEST['coBroker'].'"';	}

if ($_REQUEST['coClient']=="") {	$metClient = 'ALL CLIENT';
}else{
	$dua = 'AND ajkdebitnote.idclient="'.$_REQUEST['coClient'].'"';
	$metClient = ''.$met_['clientname'].'';	}

if ($_REQUEST['coProduct']=="") {	$metProduct = 'ALL PRODUCT';
}else{
	$tiga = 'AND ajkdebitnote.idproduk="'.$_REQUEST['coProduct'].'"';
	$metProduct = ''.$met_['produk'].'';	}

if ($_REQUEST['datastatus']=="") {
	$metStatus = 'ALL STATUS';
	$empat = 'AND ajkpeserta.statusaktif IN ("Inforce", "Lapse", "Maturity")';
}else{
	if ($_REQUEST['datastatus']=="1") {			$_statusaktif="Inforce";
	}elseif ($_REQUEST['datastatus']=="2") {	$_statusaktif="Lapse";
	}elseif ($_REQUEST['datastatus']=="3") {	$_statusaktif="Maturity";
	}elseif ($_REQUEST['datastatus']=="4") {	$_statusaktif="Batal";
	}else{	$_statusaktif !="";	}

	$empat = 'AND ajkpeserta.statusaktif="'.$_statusaktif.'"';
	$metStatus = ''.strtoupper($_statusaktif).'';
}

$summaryOutstanding = $database->doQuery('SELECT ajkdebitnote.id,
												 ajkdebitnote.nomordebitnote,
												 ajkpeserta.idbroker,
												 ajkpeserta.idclient,
												 ajkpeserta.idpolicy,
												 Count(ajkpeserta.nama) AS tdata,
												 Sum(ajkpeserta.plafond) AS tplafond,
												 Sum(ajkpeserta.totalpremi) AS tpremi,
												 ajkpeserta.statusaktif,
												 ajkregional.`name` AS namaregional
										FROM ajkdebitnote
										INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
										INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
										WHERE ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"
											  '.$satu_.'
											  '.$dua_.'
											  '.$tiga_.'
											  '.$empat.' AND
											  ajkdebitnote.del IS NULL AND
											  ajkpeserta.del IS NULL AND
											  ajkregional.del IS NULL
										GROUP BY ajkdebitnote.idbroker,
												 ajkdebitnote.idclient,
												 ajkregional.`name`
										ORDER BY Count(ajkpeserta.nama) DESC,
												 ajkdebitnote.idbroker ASC,
												 ajkdebitnote.idclient ASC,
												 ajkdebitnote.idproduk ASC');

$summaryAllOutstanding = $database->doQuery('SELECT Count(ajkpeserta.nama) AS tdata,
											 		Sum(ajkpeserta.plafond) AS tplafond,
											 		Sum(ajkpeserta.totalpremi) AS tpremi
											FROM ajkdebitnote
											INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
											INNER JOIN ajkregional ON ajkdebitnote.idregional = ajkregional.er
											WHERE ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"
											  '.$satu_.'
											  '.$dua_.'
											  '.$tiga_.'
											  '.$empat.' AND
											  ajkdebitnote.del IS NULL AND
											  ajkpeserta.del IS NULL AND
											  ajkregional.del IS NULL
										GROUP BY ajkdebitnote.idbroker,
												 ajkdebitnote.idclient,
												 ajkregional.`name`
										ORDER BY ajkdebitnote.idbroker ASC,
												 ajkdebitnote.idclient ASC,
												 ajkdebitnote.idproduk ASC,
												 ajkregional.`name` ASC');

while ($summaryAllOutstanding_ = mysql_fetch_array($summaryAllOutstanding)) {
	$alldebitur +=$summaryAllOutstanding_['tdata'];
	$allplafond +=$summaryAllOutstanding_['tplafond'];
	$allpremium +=$summaryAllOutstanding_['tpremi'];
}

while ($summaryOutstanding_ = mysql_fetch_array($summaryOutstanding)) {
	$smrdebitur .='<li class="list-group-item">'.$summaryOutstanding_['namaregional'].' <span class="badge badge-danger">'.duitdollar($summaryOutstanding_['tdata'] / $alldebitur * 100).'%</span> <span class="badge badge-primary">'.duit($summaryOutstanding_['tdata']).'</span> </li>';
	$smrplafond .='<li class="list-group-item">'.$summaryOutstanding_['namaregional'].' <span class="badge badge-danger">'.duitdollar($summaryOutstanding_['tplafond'] / $allplafond * 100).'%</span> <span class="badge badge-info">'.duit($summaryOutstanding_['tplafond']).'</span></li>';
	$smrpremium .='<li class="list-group-item">'.$summaryOutstanding_['namaregional'].' <span class="badge badge-danger">'.duitdollar($summaryOutstanding_['tpremi'] / $allpremium * 100).'%</span><span class="badge badge-success">'.duit($summaryOutstanding_['tpremi']).'</span></li>';
}
echo '<div class="row">
	<div class="col-lg-12">
		<div class="tab-content">
	    	<div class="tab-pane active" id="profile">
	        <form class="panel form-horizontal form-bordered" name="form-profile">
				<div class="panel-body pt0 pb0">
	            	<div class="form-group header bgcolor-default">
	                	<div class="col-md-12">
	            		<ul class="list-table">
	            		<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$met_['logo'].'" alt="" width="75px"></li>
						<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$met_['brokername'].'</h4></li>
						<!--<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=lprmember&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
											   		<a href="ajk.php?re=dlPdf&pdf=lprmember&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
						</li>-->
						</ul>
						</div>
	                </div>
					<div class="form-group">
	                	<div class="col-xs-12 col-sm-12 col-md-12">
                            	<div class="panel-body">
                            		<dl class="dl-horizontal">
                                        <dt class="text-primary semibold mt0">Company</dt><dd>'.$metClient.'</dd>
                                        <dt class="text-primary semibold mt0">Product</dt><dd>'.$metProduct.'</dd>
                                        <dt class="text-primary semibold mt0">Status</dt><dd>'.$metStatus.'</dd>
                                        <dt class="text-primary semibold mt0">Date of Debitnote </dt><dd>'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</dd>
										<div class="text-primary text-right semibold mt0"><a href="ajk.php?re=summary&sum=doutstanding&coBroker='.$thisEncrypter->encode($_REQUEST['coBroker']).'&coClient='.$thisEncrypter->encode($_REQUEST['coClient']).'&coProduct='.$thisEncrypter->encode($_REQUEST['coProduct']).'&datastatus='.$thisEncrypter->encode($_REQUEST['datastatus']).'&datefrom='.$thisEncrypter->encode(_convertDateEng2($_REQUEST['datefrom'])).'&dateto='.$thisEncrypter->encode(_convertDateEng2($_REQUEST['dateto'])).'"><button type="button" class="btn btn-info btn-rounded"><i class="ico-eye"></i> Detail</button></a></div>
                                    </dl>
									<div class="row">
                                    <div class="col-md-4 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">
                        				<div class="panel panel-primary">
                            				<div class="panel-heading">
                                			<h3 class="panel-title"><i class="ico-users mr5"></i> Debitur </h3>
                                				<div class="panel-toolbar text-right"><strong>'.duit($alldebitur).'</div>
                            				</div>
                            				<div class="panel-collapse pull out">
                                				<div class="panel-body">'.$smrdebitur.'</div>
                            				</div>
                        				</div>
                    				</div>

                    				<div class="col-md-4 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">
                        				<div class="panel panel-info">
                            				<div class="panel-heading">
                                			<h3 class="panel-title"><i class="ico-money mr5"></i> Plafond</h3>
                                				<div class="panel-toolbar text-right">'.duit($allplafond).'</div>
                            				</div>
                            				<div class="panel-collapse pull out">
                                				<div class="panel-body">'.$smrplafond.'</div>
                            				</div>
                        				</div>
                    				</div>

                    				<div class="col-md-4 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">
                        				<div class="panel panel-success">
                            				<div class="panel-heading">
                                			<h3 class="panel-title"><i class="ico-database2 mr5"></i> Premium</h3>
                                				<div class="panel-toolbar text-right">'.duit($allpremium).'</div>
                            				</div>
                            				<div class="panel-collapse pull out">
                                				<div class="panel-body">'.$smrpremium.'</div>
                            				</div>
                        				</div>
                    				</div>
                    				</div>

                            	</div>
	                    </div>
	                </div>

			</div>
	    </form>
	</div>
	</div>
	</div>
</div>';

		echo '</div>
</div>';
	;
	break;

case "doutstanding":
echo '<div class="page-header-section"><h2 class="title semibold">Detail Summary Outstanding</h2></div>
      </div>';
$_broker = $thisEncrypter->decode($_REQUEST['coBroker']);
$_client = $thisEncrypter->decode($_REQUEST['coClient']);
$_produk = $thisEncrypter->decode($_REQUEST['coProduct']);
$_status = $thisEncrypter->decode($_REQUEST['datastatus']);
$_tglmulai = $thisEncrypter->decode(_convertDateEng(_convertDate($_REQUEST['datefrom'])));
$_tglakhir = $thisEncrypter->decode(_convertDateEng(_convertDate($_REQUEST['dateto'])));

if ($_broker=="") {
	$satu = '';
}else{
	$satu = 'AND ajkdebitnote.idbroker="'.$_broker.'"';
}

if ($_client=="") {
	$dua = '';
}else{
	$dua = 'AND ajkdebitnote.idclient="'.$_client.'"';
}

if ($_produk=="") {
	$tiga = '';
}else{
	$tiga = 'AND id="'.$_produk.'"';
}

if ($_status=="") {
	$metStatus = 'ALL STATUS';
	$empat = 'AND ajkpeserta.statusaktif IN ("Inforce", "Lapse", "Maturity")';
}else{
	if ($_status=="1") {			$_statusaktif="Inforce";
	}elseif ($_status=="2") {	$_statusaktif="Lapse";
	}elseif ($_status=="3") {	$_statusaktif="Maturity";
	}elseif ($_status=="4") {	$_statusaktif="Batal";
	}else{	$_statusaktif !="";	}
	$empat = 'AND ajkpeserta.statusaktif="'.$_statusaktif.'"';
	$metStatus = ''.strtoupper($_statusaktif).'';
}

$metClientdetail = $database->doQuery('SELECT ajkdebitnote.id,
											  ajkdebitnote.idclient,
											  ajkdebitnote.nomordebitnote,
											  ajkclient.`name` AS namaperusahaan
								FROM ajkdebitnote
								INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
								WHERE ajkdebitnote.tgldebitnote BETWEEN "'.$_tglmulai.'" AND "'.$_tglakhir.'" '.$dua.' AND ajkdebitnote.del IS NULL
								GROUP BY ajkdebitnote.idclient
								ORDER BY ajkdebitnote.idclient DESC');
while ($detail1 = mysql_fetch_array($metClientdetail)) {
echo '<div class="panel panel-info">
		<div class="panel-heading">
        <h3 class="panel-title"><i class="ico-archive mr5"></i> '.$detail1['namaperusahaan'].'</h3>
        	<div class="panel-toolbar text-right">
            	<div class="option">
                <button class="btn demo" data-toggle="panelrefresh"><i class="reload"></i></button>
                <button class="btn up" data-toggle="panelcollapse"><i class="arrow"></i></button>
                <button class="btn" data-toggle="panelremove" data-parent=".col-md-4"><i class="remove"></i></button>
                </div>
            </div>
        </div>
        <div class="panel-collapse pull out">
        	<div class="panel-body">';
	if ($_produk=="") {
		$metProductdetail = $database->doQuery('SELECT * FROM ajkpolis WHERE idcost="'.$detail1['idclient'].'" '.$tiga.'');
	}else{
		$metProductdetail = $database->doQuery('SELECT * FROM ajkpolis WHERE idcost="'.$detail1['idclient'].'"  '.$tiga.'');
	}
	while ($detail2 = mysql_fetch_array($metProductdetail)) {
		//echo $detail2['produk'].'<br />';
		echo '<div class="panel panel-inverse">
				<div class="panel-heading">
                <h3 class="panel-title">'.$detail2['produk'].'</h3>
                	<div class="panel-toolbar text-right">
                    	<div class="option">
                    	<!-- Data -->
						</div>
					</div>
                </div>';

$jangkriktglmulai = explode("-",$_tglmulai);
$jangkrikblnmulai_ = $jangkriktglmulai[0].'-'.$jangkriktglmulai[1];
$jangkrikthnmulai_ = $jangkriktglmulai[0];

$jangkriktglakhir = explode("-",$_tglakhir);
$jangkrikbulanakhir_ = $jangkriktglakhir[0].'-'.$jangkriktglakhir[1];
$jangkriktahunakhir_ = $jangkriktglakhir[0];
$jangkrikMet = $database->doQuery('SELECT Count(ajkpeserta.nama) AS jData,
										  Sum(ajkpeserta.plafond) AS jPlaond,
										  Sum(ajkpeserta.totalpremi) AS jPremi,
										  ajkregional.`name` AS regional,
										  Count(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m-%d") BETWEEN "'.$_tglakhir.'" AND "'.$_tglakhir.'" THEN ajkpeserta.nama END) AS jDailyData,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m-%d") BETWEEN "'.$_tglakhir.'" AND "'.$_tglakhir.'" THEN ajkpeserta.plafond END) AS jDailyPlafond,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m-%d") BETWEEN "'.$_tglakhir.'" AND "'.$_tglakhir.'" THEN ajkpeserta.totalpremi END) AS jDailyPremi,
										  Count(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.$jangkrikblnmulai_.'" AND "'.$jangkrikblnmulai_.'" THEN ajkpeserta.nama END) AS jMonthlyData,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.$jangkrikblnmulai_.'" AND "'.$jangkrikblnmulai_.'" THEN ajkpeserta.plafond END) AS jMonthlyPlafond,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y-%m") BETWEEN "'.$jangkrikblnmulai_.'" AND "'.$jangkrikblnmulai_.'" THEN ajkpeserta.totalpremi END) AS jMonthlyPremi,
										  Count(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.$jangkriktahunakhir_.'" AND "'.$jangkriktahunakhir_.'" THEN ajkpeserta.nama END) AS jYearlyData,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.$jangkriktahunakhir_.'" AND "'.$jangkriktahunakhir_.'" THEN ajkpeserta.plafond END) AS jYearlyPlafond,
										  Sum(CASE WHEN DATE_FORMAT(ajkdebitnote.tgldebitnote,"%Y") BETWEEN "'.$jangkriktahunakhir_.'" AND "'.$jangkriktahunakhir_.'" THEN ajkpeserta.totalpremi END) AS jYearlyPremi
										  FROM ajkpeserta
										  INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
										  INNER JOIN ajkregional ON ajkpeserta.regional = ajkregional.er
										  WHERE ajkdebitnote.tgldebitnote BETWEEN "'.$_tglmulai.'" AND "'.$_tglakhir.'"
										  		'.$satu.'
										  		'.$dua.'
										  		'.$empat.' AND
										  		ajkpeserta.idpolicy ="'.$detail2['id'].'" AND
										  		ajkpeserta.statusaktif <> "" AND
										  		ajkpeserta.statuslunas <> ""
										  GROUP BY ajkpeserta.idbroker,
										  		   ajkpeserta.idclient,
										  		   ajkpeserta.regional');
                        echo '<table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Regional</th>
                                            <th width="1%">Daily Debitur</th>
                                            <th width="10%">Daily Plafond</th>
                                            <th width="10%">Daily Premi</th>
                                            <th width="1%">Monthly Debitur</th>
                                            <th width="10%">Monthly Plafond</th>
                                            <th width="10%">Monthly Premi</th>
                                            <th width="1%">Yearly Debitur</th>
                                            <th width="10%">Yearly Plafond</th>
                                            <th width="10%">Yearly Premi</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
				while ($jangkrikMet_ = mysql_fetch_array($jangkrikMet)) {
                 					echo '<tr>
                                            <td>'.$jangkrikMet_['regional'].'</td>
                                            <td class="text-center"><span class="label label-teal">'.duit($jangkrikMet_['jDailyData']).'</span></td>
                                            <td class="text-right"><span class="label label-teal">'.duit($jangkrikMet_['jDailyPlafond']).'</span></td>
                                            <td class="text-right"><span class="label label-teal">'.duit($jangkrikMet_['jDailyPremi']).'</span></td>
                                            <td class="text-center"><span class="label label-primary">'.duit($jangkrikMet_['jMonthlyData']).'</span></td>
                                            <td class="text-right"><span class="label label-primary">'.duit($jangkrikMet_['jMonthlyPlafond']).'</span></td>
                                            <td class="text-right"><span class="label label-primary">'.duit($jangkrikMet_['jMonthlyPremi']).'</span></td>
                                            <td class="text-center"><span class="label label-success">'.duit($jangkrikMet_['jYearlyData']).'</span></td>
                                            <td class="text-right"><span class="label label-success">'.duit($jangkrikMet_['jYearlyPlafond']).'</span></td>
                                            <td class="text-right"><span class="label label-success">'.duit($jangkrikMet_['jYearlyPremi']).'</span></td>
                                        </tr>';
				}
							echo '</tbody>
                                </table>
            </div>';

	}
		echo '</div>
        	</div>
		<div class="indicator"><span class="spinner"></span></div>
	</div>';
}
	;
	break;


	case "claim":
echo '<div class="page-header-section"><h2 class="title semibold">Summary Claim</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
	<div class="col-md-12 animation" data-toggle="waypoints" data-showanim="fadeInDown" data-hideanim="fadeOutDown" data-offset="80%">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Summary Claim</h3></div>
		<div class="panel-body">
			<div class="form-group">';
		if ($q['idbroker'] == NULL) {
			echo '<label class="col-sm-2 control-label">Broker</label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Partner</label>
				<div class="col-sm-10"><select name="coClient" class="form-control" id="coClient" onChange="mametClientProdukRateIns(this);"><option value="">Select Partner</option></select></div>
			</div>

			<div class="form-group">
				<label class="col-lg-2 control-label">Product</label>
				<div class="col-lg-10"><select name="coProduct" class="form-control" id="coProduct"><option value="">Select Product</option></select></div>
			</div>';
		}else{
			echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
			$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
			echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
				<input type="hidden" name="coBroker" value="'.$q['idbroker'].'">
				<label class="col-sm-2 control-label">Partner</label>
					<div class="col-sm-10">
					<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);"><option value="">Select Partner</option>';
			$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
					</div>
				</div>
				<div class="form-group">
				<label class="col-lg-2 control-label">Product</label>
					<div class="col-lg-10"><select name="coProduct" class="form-control" id="coProduct"><option value="">Select Product</option></select></div>
				</div>';
		}
		echo '<div class="form-group">
                <label class="col-sm-2 control-label">Input Date <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<div class="row">
                    <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" required/></div>
                    <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" required/></div>
                    </div>
                </div>
            </div>

		<div class="form-group">
		<label class="col-lg-2 control-label">Status Claim</label>
            	<div class="col-sm-10">
			<select name="datastatus" class="form-control">
				<option value="">Select Status</option>
				<option value="1"'._selected($_REQUEST['datastatus'], "1").'>Inforce</option>
				<option value="2"'._selected($_REQUEST['datastatus'], "2").'>Lapse</option>
				<option value="3"'._selected($_REQUEST['datastatus'], "3").'>Maturity</option>
				<option value="4"'._selected($_REQUEST['datastatus'], "4").'>Batal</option>
		    </select>
			</div>
		</div>

		</div>
		<div class="panel-footer"><input type="hidden" name="sum" value="voutstanding">'.BTN_SUBMIT.'</div>
		</form>
</div>
</div>';

		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;
	case "d":
		;
		break;
	default:
		;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>
