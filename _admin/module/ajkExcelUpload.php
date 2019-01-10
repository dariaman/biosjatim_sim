<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['exl']) {
	case "vExcel":
echo '<div class="page-header-section"><h2 class="title semibold">Modul View Rate</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=fileupload">'.BTN_BACK.'</a></div>
		</div>
		</div>';
echo '<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">';
$met = mysql_fetch_array($database->doQuery('SELECT ajkexcelupload.idb, ajkexcelupload.idc, ajkexcelupload.idp, ajkcobroker.`name` AS brokername, ajkcobroker.logo AS brokerlogo, ajkclient.`name` AS clientname, ajkclient.logo AS clientlogo, ajkpolis.policyauto, ajkpolis.policymanual
											 FROM ajkexcelupload
											 INNER JOIN ajkcobroker ON ajkexcelupload.idb = ajkcobroker.id
											 INNER JOIN ajkclient ON ajkexcelupload.idc = ajkclient.id
											 INNER JOIN ajkpolis ON ajkexcelupload.idp = ajkpolis.id
											 WHERE ajkexcelupload.idp = "'.$thisEncrypter->decode($_REQUEST['idp']).'"
											 GROUP BY ajkexcelupload.idp'));
echo '<div class="panel-body">
		<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['brokerlogo'].'" alt="" width="65px" height="65px"></div>
			<div class="col-md-10">
			<dl class="dl-horizontal">
				<dt>Broker</dt><dd>'.$met['brokername'].'</dd>
				<dt>Company</dt><dd>'.$met['clientname'].'</dd>
				<dt>Policy</dt><dd>'.$met['policyauto'].'</dd>
				<dt>Download Excel</dt><dd><a href="ajk.php?re=dlExcel&Rxls=ExlDL&idb='.$thisEncrypter->encode($met['idb']).'&idc='.$thisEncrypter->encode($met['idc']).'&idp='.$thisEncrypter->encode($met['idp']).'">Excel</a></dd>
			</dl>
			</div>
			<div class="col-md-1"><img class="img-rounded" src="../'.$PathPhoto.''.$met['clientlogo'].'" alt="" width="65px" height="65px"></div>
		</div>';
echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead>
		<tr>
		<th width="1%">No</th>
		<th>Field Excel</th>
		<th>Validation (Empty)</th>
		<th>Validation (Date)</th>
		<th>Validation (Same Data)</th>
		</tr>
	</thead>
	<tbody>';
$metExlUpload = $database->doQuery('SELECT ajkexcel.fieldname, ajkexcelupload.valempty, ajkexcelupload.valdate, ajkexcelupload.valsamedata
									FROM ajkexcelupload
									INNER JOIN ajkexcel ON ajkexcelupload.idxls = ajkexcel.id
									WHERE ajkexcelupload.idp = "'.$thisEncrypter->decode($_REQUEST['idp']).'"
									ORDER BY ajkexcel.fieldname ASC');
while ($metExlUpload_ = mysql_fetch_array($metExlUpload)) {
if ($metExlUpload_['valempty']=="Y") {	$excelempty = '<span class="badge badge-primary"><strong>'.$metExlUpload_['valempty'].'</strong></span>';	}
else{	$excelempty = '<span class="badge badge-warning"><strong>'.$metExlUpload_['valempty'].'</strong></span>';	}

if ($metExlUpload_['valdate']=="Y") {	$exceldate = '<span class="badge badge-primary"><strong>'.$metExlUpload_['valdate'].'</strong></span>';	}
else{	$exceldate = '<span class="badge badge-warning"><strong>'.$metExlUpload_['valdate'].'</strong></span>';	}

if ($metExlUpload_['valsamedata']=="Y") {	$excelsamedata = '<span class="badge badge-primary"><strong>'.$metExlUpload_['valsamedata'].'</strong></span>';	}
else{	$excelsamedata = '<span class="badge badge-warning"><strong>'.$metExlUpload_['valsamedata'].'</strong></span>';	}
echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metExlUpload_['fieldname'].'</td>
		<td align="center">'.$excelempty.'</td>
		<td align="center">'.$exceldate.'</td>
		<td align="center">'.$excelsamedata.'</td>
	</tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor From"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
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
		break;

	case "saveXls":
echo '<div class="page-header-section"><h2 class="title semibold">Modul Format Excel</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=fileupload">'.BTN_BACK.'</a></div>
		</div>
      </div>';
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
			<div class="panel-heading"><h3 class="panel-title">Format Upload Excel</h3></div>
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
			<table class="table table-striped table-bordered table-hover">
			<thead>';
		echo '<tr><th width="1%">#</th>
                <th>Field Name Excel</th>
                <th>Option</th>
                <th>Validation Empty</th>
                <th>Validation Date</th>
                <th>Validation Same Data</th>
            </tr>
            </thead>
            <tbody>
            <input type="hidden" name="idb" value="'.$_REQUEST['coBroker'].'">
            <input type="hidden" name="idc" value="'.$_REQUEST['coClient'].'">
            <input type="hidden" name="idp" value="'.$_REQUEST['coPolicy'].'">';
$metExcel = $database->doQuery('SELECT * FROM ajkexcel WHERE aktif !="tidak" ORDER BY fieldname ASC');
while ($metExcel_ = mysql_fetch_array($metExcel)) {
echo '<script type="text/javascript">
    function ShowHideDiv'.$metExcel_['id'].'(chkPassport'.$metExcel_['id'].') {
        var dvEMPTY_'.$metExcel_['id'].' = document.getElementById("dvEMPTY_'.$metExcel_['id'].'");
        	dvEMPTY_'.$metExcel_['id'].'.style.display = chkPassport'.$metExcel_['id'].'.checked ? "block" : "none";

        var dvDATE_'.$metExcel_['id'].' = document.getElementById("dvDATE_'.$metExcel_['id'].'");
        	dvDATE_'.$metExcel_['id'].'.style.display = chkPassport'.$metExcel_['id'].'.checked ? "block" : "none";

        var dvSAME_'.$metExcel_['id'].' = document.getElementById("dvSAME_'.$metExcel_['id'].'");
        	dvSAME_'.$metExcel_['id'].'.style.display = chkPassport'.$metExcel_['id'].'.checked ? "block" : "none";
    }
    </script>';
echo '<tr><td class="text-center">'.++$no.'</td>
		  <td>'.$metExcel_['fieldname'].'</td>
		  <td><label class="switch switch-lg">
              <input type="checkbox" name="fieldxls[]" id="chkPassport'.$metExcel_['id'].'" value="'.$metExcel_['id'].'" onclick="ShowHideDiv'.$metExcel_['id'].'(this)">
              <span class="switch"></span>
			  </label>
		  </td>
          <td class="text-center"><span class="checkbox custom-checkbox" id="dvEMPTY_'.$metExcel_['id'].'" style="display: none">
								  <input type="checkbox" name="cekempty[]" id="EMPTY_'.$metExcel_['id'].'" value="EMPTY_'.$metExcel_['id'].'" />
                                  <label for="EMPTY_'.$metExcel_['id'].'"></label>
                                  </span>
          </td>
          <td class="text-center"><span class="checkbox custom-checkbox" id="dvDATE_'.$metExcel_['id'].'" style="display: none">
								  <input type="checkbox" name="cekdate[]" id="DATE_'.$metExcel_['id'].'" value="DATE_'.$metExcel_['id'].'" />
                                  <label for="DATE_'.$metExcel_['id'].'"></label>
                                  </span>
          </td>
          <td class="text-center"><span class="checkbox custom-checkbox" id="dvSAME_'.$metExcel_['id'].'" style="display: none">
								  <input type="checkbox" name="ceksamedata[]" id="SAME_'.$metExcel_['id'].'" value="SAME_'.$metExcel_['id'].'" />
                                  <label for="SAME_'.$metExcel_['id'].'"></label>
                                  </span>
          </td>
      </tr>';
}
echo '<tr><td colspan="6"><div align="center"><input type="hidden" name="exl" value="setField">'.BTN_SUBMIT.'</div></td></tr>
		</tbody>
    		</table>
			</form>
		</div>
	</div>';
		;
		break;

case "setField":
echo '<div class="page-header-section"><h2 class="title semibold">Modul Field Excel</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';
echo '<div class="row">
		<div class="col-md-12">';
	foreach($_POST['fieldxls'] as $selectedXLS){
	//$xXLS .= '"'.$selectedXLS.'",';
	$metExl = $database->doQuery('INSERT INTO ajkexcelupload SET idb="'.$_REQUEST['idb'].'", idc="'.$_REQUEST['idc'].'", idp="'.$_REQUEST['idp'].'", idxls="'.$selectedXLS.'", input_by="'.$q['id'].'", input_date="'.$futgl.'"');
	}

	foreach($_POST['cekempty'] as $selectedEmpty){
//		echo substr($selectedEmpty, 6, 1).'<br />';
		$xEMPTY .= '"'.$selectedEmpty.'",';
		$cekData_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcelupload WHERE idb="'.$_REQUEST['idb'].'" AND idc="'.$_REQUEST['idc'].'" AND idp="'.$_REQUEST['idp'].'" AND idxls="'.substr($selectedEmpty, 6, 1).'"'));
		if ($cekData_['idxls']==substr($selectedEmpty, 6, 1)) {	$setEmpty_ = "Y";	}else{		$setEmpty_ = "T";	}
		$metExl = $database->doQuery('UPDATE ajkexcelupload  SET valempty="'.$setEmpty_.'" WHERE idb="'.$_REQUEST['idb'].'" AND
																								 idc="'.$_REQUEST['idc'].'" AND
																								 idp="'.$_REQUEST['idp'].'" AND
																								 idxls="'.substr($selectedEmpty, 6, 1).'"');
	}

	foreach($_POST['cekdate'] as $selectedDate){
//		echo substr($selectedDate, 5, 1).'<br />';
		$xDATE .= '"'.$selectedDate.'",';
		$cekData_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcelupload WHERE idb="'.$_REQUEST['idb'].'" AND idc="'.$_REQUEST['idc'].'" AND idp="'.$_REQUEST['idp'].'" AND idxls="'.substr($selectedDate, 5, 1).'"'));
		if ($cekData_['idxls']==substr($selectedDate, 5, 1)) {	$setDate_ = "Y";	}else{		$setDate_ = "T";	}
		$metExl = $database->doQuery('UPDATE ajkexcelupload  SET valdate="'.$setDate_.'" WHERE idb="'.$_REQUEST['idb'].'" AND
																								 idc="'.$_REQUEST['idc'].'" AND
																								 idp="'.$_REQUEST['idp'].'" AND
																								 idxls="'.substr($selectedDate, 5, 1).'"');
	}

	foreach($_POST['ceksamedata'] as $selectedSame){
		$xSAMEDATA .= '"'.$selectedSame.'",';
		$cekData_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcelupload WHERE idb="'.$_REQUEST['idb'].'" AND idc="'.$_REQUEST['idc'].'" AND idp="'.$_REQUEST['idp'].'" AND idxls="'.substr($selectedSame, 5, 1).'"'));
		if ($cekData_['idxls']==substr($selectedSame, 5, 1)) {	$setSameData_ = "Y";	}else{		$setSameData_ = "T";	}
		$metExl = $database->doQuery('UPDATE ajkexcelupload  SET valsamedata="'.$setSameData_.'" WHERE idb="'.$_REQUEST['idb'].'" AND
																								 idc="'.$_REQUEST['idc'].'" AND
																								 idp="'.$_REQUEST['idp'].'" AND
																								 idxls="'.substr($selectedSame, 5, 1).'"');
	}


//$metExl = $database->doQuery('INSERT INTO ajkexcelupload SET idb="'.$_REQUEST['idb'].'",
/*
echo('INSERT INTO ajkexcelupload SET idb="'.$_REQUEST['idb'].'",
															 idc="'.$_REQUEST['idc'].'",
			   												 idp="'.$_REQUEST['idp'].'",
			   												 idxls="'.$selectedXLS.'",
															 input_by="'.$q['id'].'",
			   												 input_time="'.$futgl.'"');
echo '<br />';
*/



/*
	foreach($_POST['cekempty'] as $selectedEmpty){
		echo $selectedEmpty.'-';
		$explodeEmpty = explode("EMPTY_", $selectedEmpty);
		if ($explodeEmpty[1]) {	$cekEmpty_ = "Y";	}else{	$cekEmpty_ = "T";	}
		echo $explodeEmpty[1].' - '.$cekEmpty_.'<br />';
	}

	foreach($_POST['cekdate'] as $selectedDate){
		echo $selectedDate.'-';
		$explodeDate = explode("DATE_", $selectedDate);
		if ($explodeDate[1]) {	$cekDate_ = "Y";	}else{	$cekDate_ = "T";	}
		echo $explodeDate[1].' - '.$cekEmpty_.'<br />';
	}

	foreach($_POST['ceksamedata'] as $selectedSame){
		echo $selectedSame.'-';
		$explodeSame = explode("SAME_", $selectedSame);
		if ($explodeSame[1]) {	$cekSame_ = "Y";	}else{	$cekSame_ = "T";	}
		echo $explodeSame[1].' - '.$cekSame_.'<br />';
	}
*/

echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=fileupload">
	<div class="alert alert-success fade in">
        <h4 class="semibold">Success!</h4>
        <p class="mb10">Field Excel for Uplaod was success created.</p>
      </div>
      </div>';
		echo '</div>
    </div>
</div>';
	;
	break;


	case "nUpload":
echo '<div class="page-header-section"><h2 class="title semibold">Modul Format Excel</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=fileupload">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Format Upload Excel</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coBroker" class="form-control" onChange="mametBrokerExcel(this);" required>
		            		<option value="">Select Broker</option>';
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
}
echo '</select>
			    </div>
		    </div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Company <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" id="coClient" onChange="mametClientExcel(this);" required>
		            		<option value="">Select Company</option>
				</select>
			    </div>
		    </div>
		    <div class="form-group">
		       	<label class="col-lg-2 control-label">Policy<strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10">
		        <select name="coPolicy" class="form-control" id="coPolicy" required>
		              			<option value="">Select Policy</option>
		        </select>
				</div>
			</div>';
echo '	</div>
			<div class="panel-footer"><input type="hidden" name="exl" value="saveXls">'.BTN_SUBMIT.'</div>
			</form>
		</div>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;
	default:
echo '<div class="page-header-section"><h2 class="title semibold">Modul Format Excel</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=fileupload&exl=nUpload">'.BTN_NEW.'</a></div>
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
		<th width="20%">Client</th>
		<th width="20%">Policy</th>
		<th width="1%">Download</th>
		<th width="10%">Option</th>
			</tr>
	</thead>
<tbody>';
$metExcel = $database->doQuery('SELECT
ajkcobroker.`name` AS brokername,
ajkclient.`name` AS clientname,
ajkpolis.policyauto,
ajkpolis.policymanual,
ajkexcel.fieldname,
ajkexcelupload.idp,
ajkexcelupload.valempty,
ajkexcelupload.valdate,
ajkexcelupload.valsamedata
FROM ajkexcelupload
INNER JOIN ajkexcel ON ajkexcelupload.idxls = ajkexcel.id
INNER JOIN ajkpolis ON ajkexcelupload.idp = ajkpolis.id
INNER JOIN ajkcobroker ON ajkexcelupload.idb = ajkcobroker.id
INNER JOIN ajkclient ON ajkexcelupload.idc = ajkclient.id
GROUP BY ajkexcelupload.idp');

while ($metExcel_ = mysql_fetch_array($metExcel)) {
echo '<tr>
		<td align="center">'.++$no.'</td>
		<td>'.$metExcel_['brokername'].'</td>
		<td align="center">'.$metExcel_['clientname'].'</td>
		<td align="center">'.$metExcel_['policyauto'].'</td>
		<td align="center">Download</td>
		<td align="center"><a href="ajk.php?re=fileupload&exl=vExcel&idp='.$thisEncrypter->encode($metExcel_['idp']).'">'.BTN_VIEW.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
		<tr>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Client"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Policy"></th>
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