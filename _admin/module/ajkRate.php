<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['op']) {
	case "nRate":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Premium</h2></div>
		<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=ratepremi">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="savemerate") {
$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.name AS brokername,
															 ajkcobroker.logo AS brokerlogo,
															 ajkclient.name AS clientname,
															 ajkclient.logo AS clientlogo,
															 ajkpolis.policyauto,
															 ajkpolis.policymanual,
															 ajkpolis.produk,
															 ajkpolis.typerate,
															 ajkpolis.byrate,
															 IF(ajkpolis.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkcobroker
													INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													WHERE ajkcobroker.id="'.$_REQUEST['coBroker'].'" AND ajkclient.id="'.$_REQUEST['coClient'].'" AND ajkpolis.id="'.$_REQUEST['coPolicy'].'" '));
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate</h3></div>
			<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['brokerlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
					<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metRatePremi['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metRatePremi['clientname'].'</dd>
					<dt>Policy</dt><dd>'.$metRatePremi['produk'].'</dd>
					<dt>Type Rate</dt><dd>'.$metRatePremi['typerate'].' by '.$metRatePremi['byrate'].'</dd>
					<dt>Percentage</dt><dd>'.$metRatePremi['calculatedrate'].'</dd>
					</dl>
				</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>
			<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>
			<table class="table table-striped table-bordered">
			<thead>';
if ($metRatePremi['byrate']=="Age") {
$RateKolomiAge = '<th>Age From</th>
				  <th>Age To</th>';
}
$FileNamRate =  $futoday.'_'.$metRatePremi['brokername'].'_'.$metRatePremi['clientname'].'_'.$_FILES['fileRate']['name'];
$sourcefile = $_FILES['fileRate']['tmp_name'];
$direktori = "../$PathRate$FileNamRate"; // direktori tempat menyimpan file
$data = new Spreadsheet_Excel_Reader($sourcefile);
$hasildata = $data->rowcount($sheet_index=0);
	 echo '<tr><th width="1%">#</th>
                '.$RateKolomiAge.'
                <th>Tenor From (month)</th>
                <th>Tenor To (month)</th>
                <th>Rate ND</th>
                <th>Rate PA</th>
                <th>Rate PHK</th>
                <th>Rate KM</th>
                <th>Rate</th>
            </tr>
            </thead>
            <tbody>';
	for ($i=2; $i<=$hasildata; $i++)
	{
		if ($metRatePremi['byrate']=="Age") {
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//AGE AWAL
		$data3=$data->val($i, 3);		//AGE AKHIR
		$data4=$data->val($i, 4);		//TENOR AWAL
		$data5=$data->val($i, 5);		//TENOR AKHIR
		$data6=$data->val($i, 6);		//RATE
		$data7=$data->val($i, 7);		//RATE ND
		$data8=$data->val($i, 8);		//RATE PA
		$data9=$data->val($i, 9);		//RATE PHK
		$data10=$data->val($i, 10);		//RATE KREDIT MACET
	if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
	if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
	if ($data4=="" OR !number_format($data4)) {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.$data4.'</td>';	}
	if ($data5=="" OR !number_format($data5)) {	$error = '<td class="text-center danger">'.$data5.'</td>';	$mamet4=$error;	}else{	$mamet4 = '<td class="text-center">'.$data5.'</td>';	}
	if ($data6=="") {	$error = '<td class="text-center danger">'.$data6.'</td>';	$mamet5=$error;	}else{	$mamet5 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data6).'</td>';	}
	if ($data7=="") {	$error = '<td class="text-center danger">'.$data7.'</td>';	$mamet6=$error;	}else{	$mamet6 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data7).'</td>';	}
	if ($data8=="") {	$error = '<td class="text-center danger">'.$data8.'</td>';	$mamet7=$error;	}else{	$mamet7 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data8).'</td>';	}
	if ($data9=="") {	$error = '<td class="text-center danger">'.$data9.'</td>';	$mamet8=$error;	}else{	$mamet8 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data9).'</td>';	}
	if ($data10=="") {	$error = '<td class="text-center danger">'.$data10.'</td>';	$mamet9=$error;	}else{	$mamet9 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data10).'</td>';	}
		}else{
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//TENOR AWAL
		$data3=$data->val($i, 3);		//TENOR AKHIR
		$data4=$data->val($i, 4);		//RATE
		$data5=$data->val($i, 5);		//RATE ND
		$data6=$data->val($i, 6);		//RATE PA
		$data7=$data->val($i, 7);		//RATE PHK
		$data8=$data->val($i, 8);		//RATE KREDIT MACET
	if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
	if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
	if ($data4=="") {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-cente"><strong>'.str_replace($_separatorsRate,$_separatorsRate_,$data4).'</strong></td>';	}
	if ($data5=="") {	$error = '<td class="text-center danger">'.$data5.'</td>';	$mamet4=$error;	}else{	$mamet4 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data5).'</td>';	}
	if ($data6=="") {	$error = '<td class="text-center danger">'.$data6.'</td>';	$mamet5=$error;	}else{	$mamet5 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data6).'</td>';	}
	if ($data7=="") {	$error = '<td class="text-center danger">'.$data7.'</td>';	$mamet6=$error;	}else{	$mamet6 = '<td class="text-center">'.str_replace($_separatorsRate,$_separatorsRate_,$data7).'</td>';	}
	if ($data8=="") {	$error = '<td class="text-center danger">'.$data8.'</td>';	$mamet7=$error;	}else{	$mamet7 = '<td class="text-center info">'.str_replace($_separatorsRate,$_separatorsRate_,$data8).'</td>';	}
		}
	  echo '<tr><td class="text-center">'.++$no.'</td>
                '.$mamet1.'
                '.$mamet2.'
                '.$mamet3.'
                '.$mamet4.'
                '.$mamet5.'
                '.$mamet6.'
                '.$mamet7.'
                '.$mamet8.'
                '.$mamet9.'
            </tr>';
	}
	if ($error) {
		$metValidRate = '<div class="col-md-12">
                        <div class="alert alert-danger fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <h4 class="semibold">Warning!</h4>
                        <p class="mb10">there was an error with the upload rate.</p>
                        <button type="button" class="btn btn-warning"><a href="re=ratepremi&op=nRate">Check your file and upload again !</a></button>
                        </div>
                        </div>';
}else{
move_uploaded_file($sourcefile,$direktori);
echo '<input type="hidden" name="mametFileRate" value="'.$FileNamRate.'">';
echo '<input type="hidden" name="coBroker" value="'.$_REQUEST['coBroker'].'">';
echo '<input type="hidden" name="coClient" value="'.$_REQUEST['coClient'].'">';
echo '<input type="hidden" name="coPolicy" value="'.$_REQUEST['coPolicy'].'">';
echo '<input type="hidden" name="MetbyRate" value="'.$metRatePremi['byrate'].'">';
$metValidRate = '<div class="panel-footer" align="center"><input type="hidden" name="op" value="savemeratepremi">'.BTN_SUBMIT.'</div>';
	}
	  echo ''.$metValidRate.'
			</tbody>
    		</table>
			</form>
		</div>
	</div>';
}else{
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBroker(this);" required>
		            		<option value="">Select Broker</option>';
		while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
			echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
		}
		echo '</select>
			    </div>
			    </div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" id="coClient" onChange="mametClient(this);" required>
		            		<option value="">Select Partner</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
            	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
            	<div class="col-lg-10">
                <select name="coPolicy" class="form-control" id="coPolicy" required>
                			<option value="">Select Product</option>
                </select>
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">File Upload<span class="text-danger">*</span></label>
                <div class="col-sm-10"><input type="file" name="fileRate" accept="application/vnd.ms-excel" required></div>
			</div>';
echo '	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savemerate">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
}
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
/*
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
*/
		;
		break;
	case "savemeratepremi":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Premium</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
/*
echo $_REQUEST['mametFileRate'].'<br />';
echo $_REQUEST['coBroker'].'<br />';
echo $_REQUEST['coClient'].'<br />';
echo $_REQUEST['coPolicy'].'<br />';
echo $_REQUEST['MetbyRate'].'<br />';
*/
$opDirFile = '../'.$PathRate.''.$_REQUEST['mametFileRate'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++)
{
	if ($_REQUEST['MetbyRate']=="Age") {
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//AGE AWAL
		$data3=$data->val($i, 3);		//AGE AKHIR
		$data4=$data->val($i, 4);		//TENOR AWAL
		$data5=$data->val($i, 5);		//TENOR AKHIR
		$data6=$data->val($i, 6);		//RATE ND
		$data7=$data->val($i, 7);		//RATE PA
		$data8=$data->val($i, 8);		//RATE PHK
		$data9=$data->val($i, 9);		//RATE KM
		$data10=$data->val($i, 10);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkratepremi SET idbroker="'.$_REQUEST['coBroker'].'",
															 idclient="'.$_REQUEST['coClient'].'",
															 idpolis="'.$_REQUEST['coPolicy'].'",
															 agefrom="'.$data2.'",
															 ageto="'.$data3.'",
															 tenorfrom="'.$data4.'",
															 tenorto="'.$data5.'",
															 ratend="'.str_replace($_separatorsRate,$_separatorsRate_,$data6).'",
															 ratepa="'.str_replace($_separatorsRate,$_separatorsRate_,$data7).'",
															 ratephk="'.str_replace($_separatorsRate,$_separatorsRate_,$data8).'",
															 ratekm="'.str_replace($_separatorsRate,$_separatorsRate_,$data9).'",
															 rate="'.str_replace($_separatorsRate,$_separatorsRate_,$data10).'",
															 fname="'.$_REQUEST['mametFileRate'].'",
															 eff_from="'.$futoday.'",
															 eff_to="2500-12-30",
															 input_by="'.$q['id'].'",
															 input_time="'.$futgl.'"');
	}else{
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//TENOR AWAL
		$data3=$data->val($i, 3);		//TENOR AKHIR
		$data4=$data->val($i, 4);		//RATE ND
		$data5=$data->val($i, 5);		//RATE PA
		$data6=$data->val($i, 6);		//RATE PHK
		$data7=$data->val($i, 7);		//RATE KM
		$data8=$data->val($i, 8);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkratepremi SET idbroker="'.$_REQUEST['coBroker'].'",
															 idclient="'.$_REQUEST['coClient'].'",
															 idpolis="'.$_REQUEST['coPolicy'].'",
															 tenorfrom="'.$data2.'",
															 tenorto="'.$data3.'",
															 ratend="'.str_replace($_separatorsRate,$_separatorsRate_,$data4).'",
															 ratepa="'.str_replace($_separatorsRate,$_separatorsRate_,$data5).'",
															 ratephk="'.str_replace($_separatorsRate,$_separatorsRate_,$data6).'",
															 ratekm="'.str_replace($_separatorsRate,$_separatorsRate_,$data7).'",
															 rate="'.str_replace($_separatorsRate,$_separatorsRate_,$data8).'",
															 fname="'.$_REQUEST['mametFileRate'].'",
															 eff_from="'.$futoday.'",
															 eff_to="2500-12-30",
															 input_by="'.$q['id'].'",
															 input_time="'.$futgl.'"');
	}
}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=ratepremi">
	<div class="alert alert-success fade in">
        <h4 class="semibold">Success!</h4>
        <p class="mb10">Rate Policy <strong>'.$_REQUEST['mametFileRate'].'</strong> was success uploaded.</p>
      </div>
      </div>';
echo '</div>
    </div>
</div>';
		;
		break;
	case "rateview":
echo '<div class="page-header-section"><h2 class="title semibold">View Rate Premium</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=ratepremi">'.BTN_BACK.'</a></div>
		</div>
		</div>';
echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
$met = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.`name` AS broker, ajkcobroker.`logo` AS brokerlogo, ajkclient.`name` AS client, ajkclient.`logo` AS clientlogo, ajkpolis.policyauto, ajkpolis.policymanual, ajkpolis.typerate, ajkpolis.byrate, ajkpolis.calculatedrate, ajkpolis.produk
											 FROM ajkcobroker
											 INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
											 INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
											 WHERE ajkcobroker.id="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkclient.id="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkpolis.id="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkcobroker.del IS NULL AND ajkclient.del IS NULL AND ajkpolis.del IS NULL'));

if ($met['byrate']=="Age") {
	$kolomAge = '<th>Age From</th><th>Age To</th>';
	$kolomFootAge = '<th><input type="search" class="form-control" name="search_engine" placeholder="Age"></th>';
}
$metFileRate = mysql_fetch_array($database->doQuery('SELECT ajkratepremi.id, ajkratepremi.fname, ajkratepremi.status
							   						 FROM ajkratepremi
							   						 WHERE ajkratepremi.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkratepremi.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkratepremi.idpolis="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkratepremi.input_time="'.$thisEncrypter->decode($_REQUEST['time']).'"'));
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$met['broker'].'</dd>
				<dt>Company</dt><dd>'.$met['client'].'</dd>
				<dt>Product</dt><dd>'.$met['produk'].'</dd>
				<dt>Type Rate</dt><dd>'.$met['typerate'].' by '.$met['byrate'].'</dd>
				<dt>Percentage</dt><dd>/'.$met['calculatedrate'].'</dd>
				<dt>File</dt><dd><a href="../'.$PathRate.''.$metFileRate['fname'].'">'.$metFileRate['fname'].'</a></dd>
				<dt>Status Rate</dt><dd>'.$metFileRate['status'].'</a></dd>
			</dl>
			</div>
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="65px" height="65px"></div>
		</div>';
if ($metFileRate['status']=="Aktif") {
echo '<a href="ajk.php?re=ratepremi&op=delratepremi&idf='.$metFileRate['fname'].'" onClick="if(confirm(\'Are you sure to delete this rate?\')){return true;}{return false;}"><div class="panel-toolbar text-right">'.BTN_DEL.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></a>';
}else{

}

echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		'.$kolomAge.'
		<th>Tenor From (month)</th>
		<th>Tenor To (month)</th>
		<th width="10%">Rate ND</th>
		<th width="10%">Rate PA</th>
		<th width="10%">Rate PHK</th>
		<th width="10%">Rate KM</th>
		<th width="10%">Rate ALL</th>
		</tr>
	</thead>
	<tbody>';
$metRate = $database->doQuery('SELECT ajkratepremi.id, ajkratepremi.agefrom, ajkratepremi.ageto, ajkratepremi.tenorfrom, ajkratepremi.tenorto, ajkratepremi.ratend, ajkratepremi.ratepa, ajkratepremi.ratephk, ajkratepremi.ratekm, ajkratepremi.rate, ajkratepremi.`status`
							   FROM ajkratepremi
							   WHERE ajkratepremi.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkratepremi.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkratepremi.idpolis="'.$thisEncrypter->decode($_REQUEST['idp']).'" AND ajkratepremi.input_time="'.$thisEncrypter->decode($_REQUEST['time']).'"');
while ($metRate_ = mysql_fetch_array($metRate)) {
if ($met['byrate']=="Age") {
$kolomViewAge = '<td align="center">'.$metRate_['agefrom'].'</td>
				 <td align="center">'.$metRate_['ageto'].'</td>';
}
echo '<tr>
		<td align="center">'.++$no.'</td>
		'.$kolomViewAge.'
		<td align="center">'.$metRate_['tenorfrom'].'</td>
		<td align="center">'.$metRate_['tenorto'].'</td>
		<td align="center">'.$metRate_['ratend'].'</td>
		<td align="center">'.$metRate_['ratepa'].'</td>
		<td align="center">'.$metRate_['ratephk'].'</td>
		<td align="center">'.$metRate_['ratekm'].'</td>
		<td align="center">'.$metRate_['rate'].'</td>
	</tr>';
		}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		'.$kolomFootAge.'
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor From"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor To"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate ND"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate PA"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate PHK"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate PKM"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate"></th>
	</tr>
	</tfoot>
	</table>
			</div>
		</div>
	</div>
</div>';

		;
		break;
	case "delratepremi":
$metRate = mysql_fetch_array($database->doQuery('SELECT ajkpolis.policyauto, ajkratepremi.fname FROM ajkpolis INNER JOIN ajkratepremi ON ajkpolis.id = ajkratepremi.idpolis WHERE ajkratepremi.fname="'.$_REQUEST['idf'].'"'));
$metNonActive = $database->doQuery('UPDATE ajkratepremi SET status="NonAktif" WHERE fname="'.$metRate['fname'].'"');
echo '<div class="page-header-section"><h2 class="title semibold">Rate Premium</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=ratepremi">
	<div class="alert alert-success fade in">
            <h4 class="semibold">Success Delete!</h4>
            <p class="mb10">Rate premium for policy '.$metRate['policyauto'].' was deleted on '.$futgl.'</p>
      </div>
      </div>';
echo '</div>
    </div>
</div>';

		;
		break;

case "rateclaim":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=rateclaim&op=nrateclaim">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="row">
      	<div class="col-md-12">
	       	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th>Company</th>
		<th width="20%">Policy</th>
		<th width="10%">Rate</th>
		<th width="10%">Min</th>
		<th width="10%">Max</th>
		<th width="10%">Status</th>
		<th width="10%">Option</th>
			</tr>
	</thead>
<tbody>';
	$metClient = $database->doQuery('SELECT ajkrateklaim.id,
												ajkrateklaim.idbroker,
												ajkrateklaim.idclient,
												ajkrateklaim.idpolis,
												ajkrateklaim.status,
												ajkclient.`name`,
												ajkpolis.policyauto,
												ajkpolis.typerate,
												ajkpolis.byrate,
												ajkpolis.policymanual,
												Min(ajkrateklaim.rate) AS ratemin,
												Max(ajkrateklaim.rate) AS ratemax
												FROM ajkrateklaim
												INNER JOIN ajkclient ON ajkrateklaim.idclient = ajkclient.id
												INNER JOIN ajkpolis ON ajkrateklaim.idpolis = ajkpolis.id
												WHERE ajkclient.del IS NULL '.$q__.'
												GROUP BY ajkrateklaim.idpolis, ajkrateklaim.input_time
												ORDER BY ajkpolis.id DESC');
		while ($metClient_ = mysql_fetch_array($metClient)) {
			if ($metClient_['status']=="Aktif") {
				$ratestatus='<span class="badge badge-primary">'.$metClient_['status'].'</span>';
			}else{
				$ratestatus='<span class="badge badge-danger">'.$metClient_['status'].'</span>';
			}
			echo '<tr>
	<td align="center">'.++$no.'</td>
	<td>'.$metClient_['name'].'</td>
	<td align="center">'.$metClient_['policyauto'].'</td>
	<td align="center">'.$metClient_['typerate'].' by '.$metClient_['byrate'].'</td>
	<td align="center">'.$metClient_['ratemin'].'</td>
	<td align="center">'.$metClient_['ratemax'].'</td>
	<td align="center">'.$ratestatus.'</td>
	<td align="center"><a href="ajk.php?re=ratepremi&op=rateview&idb='.$thisEncrypter->encode($metClient_['idbroker']).'&idc='.$thisEncrypter->encode($metClient_['idclient']).'&idp='.$thisEncrypter->encode($metClient_['idpolis']).'">'.BTN_VIEW.'</a></td>
	</tr>';
		}
		echo '</tbody>
	<tfoot>
	<tr>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Policy"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Rate"></th>
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

case "nrateclaim":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Claim</h2></div>
		<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=rateclaim&op=rateclaim">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="savemerate") {
$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.name AS brokername,
															 ajkcobroker.logo AS brokerlogo,
															 ajkclient.name AS clientname,
															 ajkclient.logo AS clientlogo,
															 ajkpolis.policyauto,
															 ajkpolis.policymanual,
															 ajkpolis.typerate,
															 ajkpolis.byrate,
															 IF(ajkpolis.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkcobroker
													INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													WHERE ajkcobroker.id="'.$_REQUEST['coBroker'].'" AND ajkclient.id="'.$_REQUEST['coClient'].'" AND ajkpolis.id="'.$_REQUEST['coPolicy'].'" '));
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate Claim</h3></div>
			<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['brokerlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
					<dl class="dl-horizontal">
					<dt>Broker</dt><dd>'.$metRatePremi['brokername'].'</dd>
					<dt>Company</dt><dd>'.$metRatePremi['clientname'].'</dd>
					<dt>Policy</dt><dd>'.$metRatePremi['policyauto'].'</dd>
					<dt>Type Rate</dt><dd>'.$metRatePremi['typerate'].' by '.$metRatePremi['byrate'].'</dd>
					<dt>Percentage</dt><dd>'.$metRatePremi['calculatedrate'].'</dd>
					</dl>
				</div>
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
			</div>
			<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>
			<table class="table table-striped table-bordered">
			<thead>';
if ($metRatePremi['byrate']=="Age") {
	$RateKolomiAge = '<th>Age</th>';
}
$FileNamRate =  'RATECLAIM_'.$futoday.'_'.$metRatePremi['brokername'].'_'.$metRatePremi['clientname'].'_'.$_FILES['fileRate']['name'];
$sourcefile = $_FILES['fileRate']['tmp_name'];
$direktori = "../$PathRate$FileNamRate"; // direktori tempat menyimpan file
$data = new Spreadsheet_Excel_Reader($sourcefile);
$hasildata = $data->rowcount($sheet_index=0);
echo '<tr><th width="1%">#</th>
		  '.$RateKolomiAge.'
		  <th>Tenor From (month)</th>
		  <th>Tenor To (month)</th>
		  <th>Current Month</th>
		  <th>Rate Claim</th>
	</tr>
	</thead>
	<tbody>';
for ($i=2; $i<=$hasildata; $i++)
{
	if ($metRatePremi['byrate']=="Age") {
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//AGE
		$data3=$data->val($i, 3);		//TENOR AWAL
		$data4=$data->val($i, 4);		//TENOR AKHIR
		$data5=$data->val($i, 5);		//BULAN BERJALAN
		$data6=$data->val($i, 6);		//RATE
		if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
		if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
		if ($data4=="" OR !number_format($data4)) {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.$data4.'</td>';	}
		if ($data5=="" OR !number_format($data5)) {	$error = '<td class="text-center danger">'.$data5.'</td>';	$mamet4=$error;	}else{	$mamet4 = '<td class="text-center">'.$data5.'</td>';	}
		if ($data6=="") {	$error = '<td class="text-center danger">'.$data6.'</td>';	$mamet5=$error;	}else{	$mamet5 = '<td class="text-center">'.$data6.'</td>';	}
	}else{
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//TENOR AWAL
		$data3=$data->val($i, 3);		//TENOR AKHIR
		$data4=$data->val($i, 4);		//BULAN BERJALAN
		$data5=$data->val($i, 5);		//RATE
		if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
		if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
		if ($data4=="" OR !number_format($data4)) {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.$data4.'</td>';	}
		if ($data5=="") {	$error = '<td class="text-center danger">'.$data5.'</td>';	$mamet4=$error;	}else{	$mamet4 = '<td class="text-center">'.$data5.'</td>';	}
	}
	echo '<tr><td class="text-center">'.++$no.'</td>
                '.$mamet1.'
                '.$mamet2.'
                '.$mamet3.'
                '.$mamet4.'
                '.$mamet5.'
            </tr>';
}
if ($error) {
$metValidRate = '<div class="col-md-12">
					<div class="alert alert-danger fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h4 class="semibold">Warning!</h4>
                    <p class="mb10">there was an error with the upload rate.</p>
                    <button type="button" class="btn btn-warning"><a href="re=ratepremi&op=nRate">Check your file and upload again !</a></button>
                    </div>
                </div>';
}else{
move_uploaded_file($sourcefile,$direktori);
echo '<input type="hidden" name="mametFileRate" value="'.$FileNamRate.'">';
echo '<input type="hidden" name="coBroker" value="'.$_REQUEST['coBroker'].'">';
echo '<input type="hidden" name="coClient" value="'.$_REQUEST['coClient'].'">';
echo '<input type="hidden" name="coPolicy" value="'.$_REQUEST['coPolicy'].'">';
echo '<input type="hidden" name="MetbyRate" value="'.$metRatePremi['byrate'].'">';
$metValidRate = '<div class="panel-footer" align="center"><input type="hidden" name="op" value="savemeratepremiclaim">'.BTN_SUBMIT.'</div>';
}
echo ''.$metValidRate.'
			</tbody>
			</table>
			</form>
			</div>
		</div>';
}else{
	$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBroker(this);" required>
			            		<option value="">Select Broker</option>';
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
}
echo '</select>
			    </div>
			    </div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" id="coClient" onChange="mametClientRateClaim(this);" required>
		            		<option value="">Select Partner</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
            	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
            	<div class="col-lg-10">
                <select name="coPolicy" class="form-control" id="coPolicy" required>
                			<option value="">Select Product</option>
                </select>
                </div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">File Upload<span class="text-danger">*</span></label>
                <div class="col-sm-10"><input type="file" name="fileRate" accept="application/vnd.ms-excel" required></div>
			</div>';
echo '	</div>
		<div class="panel-footer"><input type="hidden" name="met" value="savemerate">'.BTN_SUBMIT.'</div>
		</form>
	</div>
</div>';
}
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
/*
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
*/
	;
	break;

case "savemeratepremiclaim":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Claim</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
		/*
		   echo $_REQUEST['mametFileRate'].'<br />';
		   echo $_REQUEST['coBroker'].'<br />';
		   echo $_REQUEST['coClient'].'<br />';
		   echo $_REQUEST['coPolicy'].'<br />';
		   echo $_REQUEST['MetbyRate'].'<br />';
		*/
$opDirFile = '../'.$PathRate.''.$_REQUEST['mametFileRate'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++)
{
	if ($_REQUEST['MetbyRate']=="Age") {
	$data1=$data->val($i, 1);		//NOMOR
	$data2=$data->val($i, 2);		//AGE
	$data3=$data->val($i, 3);		//TENOR AWAL
	$data4=$data->val($i, 4);		//TENOR AKHIR
	$data5=$data->val($i, 5);		//BULAN BERJALAN
	$data6=$data->val($i, 6);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkrateklaim SET idbroker="'.$_REQUEST['coBroker'].'",
															 idclient="'.$_REQUEST['coClient'].'",
															 idpolis="'.$_REQUEST['coPolicy'].'",
															 age="'.$data2.'",
															 tenorfrom="'.$data3.'",
															 tenorto="'.$data4.'",
															 currentmonth="'.$data5.'",
															 rate="'.$data6.'",
															 fname="'.$_REQUEST['mametFileRate'].'",
															 input_by="'.$q['id'].'",
															 input_time="'.$futgl.'"');
}else{
	$data1=$data->val($i, 1);		//NOMOR
	$data2=$data->val($i, 2);		//TENOR AWAL
	$data3=$data->val($i, 3);		//TENOR AKHIR
	$data4=$data->val($i, 4);		//BULN BERJALAN
	$data5=$data->val($i, 5);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkrateklaim SET idbroker="'.$_REQUEST['coBroker'].'",
															 idclient="'.$_REQUEST['coClient'].'",
															 idpolis="'.$_REQUEST['coPolicy'].'",
															 tenorfrom="'.$data2.'",
															 tenorto="'.$data3.'",
															 currentmonth="'.$data4.'",
															 rate="'.$data5.'",
															 fname="'.$_REQUEST['mametFileRate'].'",
															 input_by="'.$q['id'].'",
															 input_time="'.$futgl.'"');
			}
		}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=rateclaim&op=rateclaim">
		<div class="alert alert-success fade in">
	        <h4 class="semibold">Success!</h4>
	        <p class="mb10">Rate Policy <strong>'.$_REQUEST['mametFileRate'].'</strong> was success uploaded.</p>
	      </div>
	      </div>';
echo '</div>
	    </div>
	</div>';
	;
	break;

	case "a":
		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">Rate Premium</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=ratepremi&op=nRate">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="row">
      	<div class="col-md-12">
	       	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th>Company</th>
		<th width="20%">Product</th>
		<th width="10%">Rate</th>
		<th width="10%">Min</th>
		<th width="10%">Max</th>
		<th width="10%">Status</th>
		<th width="10%">Option</th>
			</tr>
	</thead>
<tbody>';
		$metClient = $database->doQuery('SELECT ajkratepremi.id,
												ajkratepremi.idbroker,
												ajkratepremi.idclient,
												ajkratepremi.idpolis,
												ajkratepremi.status,
												ajkratepremi.input_time,
												ajkclient.`name`,
												ajkpolis.policyauto,
												ajkpolis.typerate,
												ajkpolis.byrate,
												ajkpolis.produk,
												ajkpolis.policymanual,
												Min(ajkratepremi.rate) AS ratemin,
												Max(ajkratepremi.rate) AS ratemax
												FROM ajkratepremi
												INNER JOIN ajkclient ON ajkratepremi.idclient = ajkclient.id
												INNER JOIN ajkpolis ON ajkratepremi.idpolis = ajkpolis.id
												WHERE ajkclient.del IS NULL '.$q__.'
												GROUP BY ajkratepremi.idpolis, ajkratepremi.input_time
												ORDER BY ajkpolis.id DESC');
while ($metClient_ = mysql_fetch_array($metClient)) {
if ($metClient_['status']=="Aktif") {
	$ratestatus='<span class="badge badge-primary">'.$metClient_['status'].'</span>';
}else{
	$ratestatus='<span class="badge badge-danger">'.$metClient_['status'].'</span>';
}
echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metClient_['name'].'</td>
		<td align="center">'.$metClient_['produk'].'</td>
		<td align="center">'.$metClient_['typerate'].' by '.$metClient_['byrate'].'</td>
		<td align="center">'.$metClient_['ratemin'].'</td>
		<td align="center">'.$metClient_['ratemax'].'</td>
		<td align="center">'.$ratestatus.'</td>
		<td align="center"><a href="ajk.php?re=ratepremi&op=rateview&idb='.$thisEncrypter->encode($metClient_['idbroker']).'&idc='.$thisEncrypter->encode($metClient_['idclient']).'&idp='.$thisEncrypter->encode($metClient_['idpolis']).'&time='.$thisEncrypter->encode($metClient_['input_time']).'">'.BTN_VIEW.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
		<tr>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Policy"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Rate"></th>
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
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>
