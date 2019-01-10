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
echo '<div class="page-header-section"><h2 class="title semibold">Rate Insurance</h2></div>
		<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=insrate">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="savemerate") {
//echo $_REQUEST['coBroker'].'<br />';
//echo $_REQUEST['coClient'].'<br />';
//echo $_REQUEST['coProduct'].'<br />';
$_produk = explode("_", $_REQUEST['coProduct']);
//echo $_REQUEST['coPolicyInsurance'].'<br />';
$_asuransi = explode("_", $_REQUEST['coPolicyInsurance']);
//echo $_REQUEST['coPolicy'].'<br />';
$metRatePremi = mysql_fetch_array($database->doQuery('SELECT ajkinsurance.name AS clientname,
															 ajkinsurance.logo AS clientlogo,
															 ajkpolisasuransi.policyauto,
															 ajkpolisasuransi.policymanual,
															 ajkpolisasuransi.typerate,
															 ajkpolisasuransi.byrate,
															 IF(ajkpolisasuransi.calculatedrate="100", "Percent","Permil") AS calculatedrate
													FROM ajkinsurance
													INNER JOIN ajkpolisasuransi ON ajkinsurance.id = ajkpolisasuransi.idas
													WHERE ajkinsurance.id="'.$_asuransi[0].'" AND ajkpolisasuransi.id="'.$_REQUEST['coPolicy'].'" '));
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate Insurance</h3></div>
			<div class="panel-body">
				<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$metRatePremi['clientlogo'].'" alt="" width="65px" height="65px"></div>
				<div class="col-md-10">
					<dl class="dl-horizontal">
					<dt>Insurance</dt><dd>'.$metRatePremi['clientname'].'</dd>
					<dt>Policy</dt><dd>'.$metRatePremi['policyauto'].'</dd>
					<dt>Type Rate</dt><dd>'.$metRatePremi['typerate'].' by '.$metRatePremi['byrate'].'</dd>
					<dt>Percentage</dt><dd>'.$metRatePremi['calculatedrate'].'</dd>
					</dl>
				</div>
			</div>
			<div class="panel-heading"><h3 class="panel-title">'.$_FILES['fileRate']['name'].'</h3></div>
			<table class="table table-striped table-bordered">
			<thead>';
if ($metRatePremi['byrate']=="Age") {
$RateKolomiAge = '<th>Age From</th><th>Age To</th>';
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
                <th>Rate</th>
            </tr>
            </thead>
            <tbody>';
	for ($i=2; $i<=$hasildata; $i++)
	{
		if ($metRatePremi['byrate']=="Age") {
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//AGE FROM
		$data3=$data->val($i, 3);		//AGE TO
		$data4=$data->val($i, 4);		//TENOR AWAL
		$data5=$data->val($i, 5);		//TENOR AKHIR
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
		$data4=$data->val($i, 4);		//RATE
	if ($data2=="" OR !number_format($data2)) {	$error = '<td class="text-center danger">'.$data2.'</td>';	$mamet1=$error;	}else{	$mamet1 = '<td class="text-center">'.$data2.'</td>';	}
	if ($data3=="" OR !number_format($data3)) {	$error = '<td class="text-center danger">'.$data3.'</td>';	$mamet2=$error;	}else{	$mamet2 = '<td class="text-center">'.$data3.'</td>';	}
	if ($data4=="") {	$error = '<td class="text-center danger">'.$data4.'</td>';	$mamet3=$error;	}else{	$mamet3 = '<td class="text-center">'.$data4.'</td>';	}
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
                        <button type="button" class="btn btn-warning"><a href="ajk.php?re=insrate&op=nRate">Check your file and upload again !</a></button>
                        </div>
                        </div>';
}else{
move_uploaded_file($sourcefile,$direktori);
echo '<input type="hidden" name="mametFileRate" value="'.$FileNamRate.'">';
echo '<input type="hidden" name="coBroker" value="'.$_REQUEST['coBroker'].'">';
echo '<input type="hidden" name="coClient" value="'.$_REQUEST['coClient'].'">';
echo '<input type="hidden" name="coProduct" value="'.$_produk[0].'">';
echo '<input type="hidden" name="Insurance" value="'.$_asuransi[0].'">';
echo '<input type="hidden" name="coPolicy" value="'.$_REQUEST['coPolicy'].'">';
echo '<input type="hidden" name="MetbyRate" value="'.$metRatePremi['byrate'].'">';
$metValidRate = '<div class="panel-footer" align="center"><input type="hidden" name="op" value="savemeinsrate">'.BTN_SUBMIT.'</div>';
	}
	  echo ''.$metValidRate.'
			</tbody>
    		</table>
			</form>
		</div>
	</div>';
}
else{
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Upload New Rate Insurance</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);" required>
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
				<select name="coClient" class="form-control" id="coClient" onChange="mametClientProdukRateIns(this);" required>
				            		<option value="">Select Partner</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
	       	<label class="col-lg-2 control-label">Product <strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10">
		        <select name="coProduct" class="form-control" id="coProduct" onChange="mametInsuranceName(this);" required><option value="">Select Product</option></select>
				</div>
			</div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Insurance <span class="text-danger">*</span></label>
				<div class="col-sm-10">';
echo '<select name="coPolicyInsurance" class="form-control" id="coPolicyInsurance" onChange="mametInsuranceRate(this);" required><option value="">Select Insurance</option></select>';
/*
echo '			<select name="Insurance" class="form-control" onChange="mametInsuranceRate(this);" required>
		            		<option value="">Select Insurance</option>';
$metInsurance = $database->doQuery('SELECT id, name FROM ajkinsurance WHERE del IS NULL '.$q__.' ORDER BY name ASC');
while ($metInsurance_ = mysql_fetch_array($metInsurance)) {
echo '<option value="'.$metInsurance_['id'].'"'._selected($_REQUEST['Insurance'], $metInsurance_['id']).'>'.$metInsurance_['name'].'</option>';
}
echo '</select>
*/
echo '</div>
	    </div>
	    <div class="form-group">
           	<label class="col-lg-2 control-label">Policy Insurance<strong class="text-danger"> *</strong></label>
           	<div class="col-lg-10">
            <select name="coPolicy" class="form-control" id="coPolicyInsRate" required>
        	<option value="">Select Policy Insurance</option>
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

	case "savemeinsrate":
echo '<div class="page-header-section"><h2 class="title semibold">Rate Insurance</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';

/*
echo $_REQUEST['mametFileRate'].'<br />';
echo $_REQUEST['coBroker'].'<br />';
echo $_REQUEST['coClient'].'<br />';
echo $_REQUEST['coProduct'].'<br />';
echo $_REQUEST['Insurance'].'<br />';
echo $_REQUEST['coPolicy'].'<br />';
echo $_REQUEST['MetbyRate'].'<br />';
*/
if ($q['idbroker']=="") {
	$brokerID = $_REQUEST['coBroker'];
}else{
	$brokerID = $q['idbroker'];
}
$opDirFile = '../'.$PathRate.''.$_REQUEST['mametFileRate'].'';
$data = new Spreadsheet_Excel_Reader($opDirFile);
$hasildata = $data->rowcount($sheet_index=0);
for ($i=2; $i<=$hasildata; $i++)
{
	if ($_REQUEST['MetbyRate']=="Age") {
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//AGE FROM
		$data3=$data->val($i, 3);		//AGE TO
		$data4=$data->val($i, 4);		//TENOR AWAL
		$data5=$data->val($i, 5);		//TENOR AKHIR
		$data6=$data->val($i, 6);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkratepremiins SET idbroker="'.$brokerID.'",
															 	idclient="'.$_REQUEST['coClient'].'",
															 	idproduk="'.$_REQUEST['coProduct'].'",
															 	idas="'.$_REQUEST['Insurance'].'",
															 	idpolis="'.$_REQUEST['coPolicy'].'",
															 	agefrom="'.$data2.'",
															 	ageto="'.$data3.'",
															 	tenorfrom="'.$data4.'",
															 	tenorto="'.$data5.'",
															 	rate="'.$data6.'",
															 	fname="'.$_REQUEST['mametFileRate'].'",
															 	input_by="'.$q['id'].'",
															 	input_time="'.$futgl.'"');
	}else{
		$data1=$data->val($i, 1);		//NOMOR
		$data2=$data->val($i, 2);		//TENOR AWAL
		$data3=$data->val($i, 3);		//TENOR AKHIR
		$data4=$data->val($i, 4);		//RATE
$metPremi = $database->doQuery('INSERT INTO ajkratepremiins SET idbroker="'.$brokerID.'",
															 	idclient="'.$_REQUEST['coClient'].'",
															 	idproduk="'.$_REQUEST['coProduct'].'",
															 	idas="'.$_REQUEST['Insurance'].'",
															 	idpolis="'.$_REQUEST['coPolicy'].'",
															 	tenorfrom="'.$data2.'",
															 	tenorto="'.$data3.'",
															 	rate="'.$data4.'",
															 	fname="'.$_REQUEST['mametFileRate'].'",
															 	input_by="'.$q['id'].'",
															 	input_time="'.$futgl.'"');
	}
}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=insrate">
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
echo '<div class="page-header-section"><h2 class="title semibold">Modul View Rate</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=insrate">'.BTN_BACK.'</a></div>
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
$metFileRate = mysql_fetch_array($database->doQuery('SELECT ajkratepremiins.id, ajkratepremiins.fname, ajkratepremiins.status
							   						 FROM ajkratepremiins
							   						 WHERE ajkratepremiins.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND ajkratepremiins.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND ajkratepremiins.idpolis="'.$thisEncrypter->decode($_REQUEST['idpol']).'" AND ajkratepremiins.input_time="'.$thisEncrypter->decode($_REQUEST['time']).'"'));
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
echo '<a href="ajk.php?re=insrate&op=delinsrate&idf='.$metFileRate['fname'].'" onClick="if(confirm(\'Are you sure to delete this rate?\')){return true;}{return false;}"><div class="panel-toolbar text-right">'.BTN_DEL.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></a>';
}else{

}

echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		'.$kolomAge.'
		<th>Tenor From (month)</th>
		<th>Tenor To (month)</th>
		<th width="10%">Rate</th>
		</tr>
	</thead>
	<tbody>';
$metRate = $database->doQuery('SELECT ajkratepremiins.id, ajkratepremiins.agefrom, ajkratepremiins.ageto, ajkratepremiins.tenorfrom, ajkratepremiins.tenorto, ajkratepremiins.rate, ajkratepremiins.`status`
							   FROM ajkratepremiins
							   WHERE ajkratepremiins.idbroker="'.$thisEncrypter->decode($_REQUEST['idb']).'" AND
							   		 ajkratepremiins.idclient="'.$thisEncrypter->decode($_REQUEST['idc']).'" AND
							   		 ajkratepremiins.input_time="'.$thisEncrypter->decode($_REQUEST['time']).'" AND
									 ajkratepremiins.idpolis="'.$thisEncrypter->decode($_REQUEST['idpol']).'"');
while ($metRate_ = mysql_fetch_array($metRate)) {
if ($met['byrate']=="Age") {
$kolomViewAge = '<td align="center">'.$metRate_['agefrom'].'</td><td align="center">'.$metRate_['ageto'].'</td>';
}
echo '<tr>
		<td align="center">'.++$no.'</td>
		'.$kolomViewAge.'
		<td align="center">'.$metRate_['tenorfrom'].'</td>
		<td align="center">'.$metRate_['tenorto'].'</td>
		<td align="center">'.$metRate_['rate'].'</td>
	</tr>';
		}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		'.$kolomFootAge.'
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor From"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor To"></th>
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

	case "delinsrate":
$metRate = mysql_fetch_array($database->doQuery('SELECT ajkpolis.policyauto, ajkratepremiins.fname FROM ajkpolis INNER JOIN ajkratepremiins ON ajkpolis.id = ajkratepremiins.idpolis WHERE ajkratepremiins.fname="'.$_REQUEST['idf'].'"'));
$metNonActive = $database->doQuery('UPDATE ajkratepremiins SET status="NonAktif" WHERE fname="'.$metRate['fname'].'"');
echo '<div class="page-header-section"><h2 class="title semibold">Modul Rate</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=insrate">
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

	case "a":
		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">Rate Insurance</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=insrate&op=nRate">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="row">
      	<div class="col-md-12">
	       	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th>Partner</th>
		<th>Product</th>
		<th>Insurance</th>
		<th width="20%">Policy</th>
		<th width="10%">Rate</th>
		<th width="10%">Min</th>
		<th width="10%">Max</th>
		<th width="10%">Status</th>
		<th width="10%">Option</th>
			</tr>
	</thead>
<tbody>';
		$metClient = $database->doQuery('SELECT ajkratepremiins.id,
												ajkratepremiins.idbroker,
												ajkratepremiins.idclient,
												ajkratepremiins.idproduk,
												ajkratepremiins.idas,
												ajkratepremiins.idpolis,
												ajkratepremiins.status,
												ajkratepremiins.input_time,
												ajkclient.`name` AS nmPartner,
												ajkpolis.produk,
												ajkinsurance.`name` AS nmIns,
												ajkpolisasuransi.policyauto,
												ajkpolisasuransi.typerate,
												ajkpolisasuransi.byrate,
												ajkpolisasuransi.policymanual,
												Min(ajkratepremiins.rate) AS ratemin,
												Max(ajkratepremiins.rate) AS ratemax
												FROM ajkratepremiins
												INNER JOIN ajkcobroker ON ajkratepremiins.idbroker = ajkcobroker.id
												INNER JOIN ajkclient ON ajkratepremiins.idclient = ajkclient.id
												INNER JOIN ajkpolis ON ajkratepremiins.idproduk = ajkpolis.id
												INNER JOIN ajkinsurance ON ajkratepremiins.idas = ajkinsurance.id
												INNER JOIN ajkpolisasuransi ON ajkratepremiins.idpolis = ajkpolisasuransi.id
												WHERE ajkpolis.del IS NULL '.$q___2.'
												GROUP BY ajkratepremiins.idpolis, ajkratepremiins.input_time
												ORDER BY ajkpolisasuransi.id DESC');
while ($metClient_ = mysql_fetch_array($metClient)) {
if ($metClient_['status']=="Aktif") {
	$ratestatus='<span class="badge badge-primary">'.$metClient_['status'].'</span>';
}else{
	$ratestatus='<span class="badge badge-danger">'.$metClient_['status'].'</span>';
}
echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metClient_['nmPartner'].'</td>
		<td>'.$metClient_['produk'].'</td>
		<td>'.$metClient_['nmIns'].'</td>
		<td align="center">'.$metClient_['policyauto'].'</td>
		<td align="center">'.$metClient_['typerate'].' by '.$metClient_['byrate'].'</td>
		<td align="center">'.$metClient_['ratemin'].'</td>
		<td align="center">'.$metClient_['ratemax'].'</td>
		<td align="center">'.$ratestatus.'</td>
		<td align="center"><a href="ajk.php?re=insrate&op=rateview&idb='.$thisEncrypter->encode($metClient_['idbroker']).'&idc='.$thisEncrypter->encode($metClient_['idclient']).'&idp='.$thisEncrypter->encode($metClient_['idproduk']).'&ida='.$thisEncrypter->encode($metClient_['idas']).'&idpol='.$thisEncrypter->encode($metClient_['idpolis']).'&time='.$thisEncrypter->encode($metClient_['input_time']).'">'.BTN_VIEW.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
		<tr>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
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
