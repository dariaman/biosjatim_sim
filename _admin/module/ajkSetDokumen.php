<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;">
		<div class="page-header page-header-block">';
switch ($_REQUEST['cl']) {
	case "claim":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=newDocClaim">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
      <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <th width="80%">Field Name Excel</th>
        <th>Type</th>
        <th>Mandatory</th>
        <th>Edit</th>
        </tr>
    </thead>
    <tbody>';
$metDocClaim = $database->doQuery('SELECT * FROM ajkdocumentclaim ORDER BY id ASC');
while ($metDocClaim_ = mysql_fetch_array($metDocClaim)) {
echo '<tr>
	   	<td>'.$metDocClaim_['namadokumen'].'</td>
	   	<td>'.$metDocClaim_['type'].'</td>
	   	<td align="center">'.$metDocClaim_['opsional'].'</td>
	   	<td align="center"><a href="ajk.php?re=setdoc&cl=eddocclaim&fid='.$thisEncrypter->encode($metDocClaim_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
	    </table>
    </div>';
		;
		break;

	case "newDocClaim":
if ($_REQUEST['met']=="saveme") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkdocumentclaim WHERE namadokumen="'.strtoupper($_REQUEST['namadokumen']).'"'));
	if ($metExcelCek) {
		$metnotif .= '<div class="alert alert-dismissable alert-danger">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            	     <strong>Error!</strong> document name '.$_REQUEST['namadokumen'].' already exists.
                	 </div>';
		}else{
		$metExcel = $database->doQuery('INSERT INTO ajkdocumentclaim SET namadokumen="'.strtoupper($_REQUEST['namadokumen']).'", opsional="'.$_REQUEST['opsional'].'"');
		$metnotif .= '<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        	         <strong>Success!</strong> insert document name '.$_REQUEST['namadokumen'].'.
    				</div>';
	header('Location: ajk.php?re=setdoc&cl=claim');
	}
}
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=claim">'.BTN_BACK.'</a></div>
		</div>
      </div>
<div class="row">
'.$metnotif.'
<div class="col-md-12">
<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
	<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
	<div class="panel-body">
		<div class="form-group">
			<label class="col-sm-2 control-label">Document Type</label>
			<div class="col-sm-7">
				<select name="type" class="form-control" required>
					<option value="">- Pilih -</option>
					<option value="AJK">Default</option>
					<option value="DEATH">Death</option>
					<option value="PHK">PHK</option>
					<option value="PAW">PAW</option>
					<option value="OTHER">Other</option>
				</select>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-2 control-label">Document Name</label>
			<div class="col-sm-7">
				<input type="text" name="namadokumen" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Mandatory</label>
			<div class="col-sm-7">
				<select name="opsional" class="form-control" required>
					<option value="">- Pilih -</option>
					<option value="RUMAH">Rumah</option>
					<option value="RUMAH SAKIT">Rumah Sakit</option>
					<option value="KECELAKAAN">Kecelakaan</option>
					<option value="LUAR NEGRI">Luar Negri</option>
				</select>
			</div>
		</div>			
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	case "eddocclaim":
$metDocClaimCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkdocumentclaim WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"'));
if ($_REQUEST['met']=="saveme") {
	$metExcel = $database->doQuery('UPDATE ajkdocumentclaim SET namadokumen="'.strtoupper($_REQUEST['namadokumen']).'", opsional="'.$_REQUEST['opsional'].'" WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"');
	$metnotif .= '<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        	         <strong>Success!</strong> update document name '.$_REQUEST['namadokumen'].'.
    				</div>
    				<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc&cl=claim">';
}
if($metDocClaimCek['type']=="AJK"){
	$sAJK = "selected";
}elseif($metDocClaimCek['type']=="DEATH"){
	$sDEATH = "selected";
}elseif($metDocClaimCek['type']=="PHK"){
	$sPHK = "selected";
}elseif($metDocClaimCek['type']=="PAW"){
	$sPAW = "selected";
}elseif($metDocClaimCek['type']=="OTHER"){
	$sOTHER = "selected";
}

if($metDocClaimCek['opsional']=="RUMAH"){
	$sRUMAH = "selected";
}elseif($metDocClaimCek['opsional']=="RUMAH SAKIT"){
	$sRS = "selected";
}elseif($metDocClaimCek['opsional']=="KECELAKAAN"){
	$sKECELAKAAN = "selected";
}elseif($metDocClaimCek['opsional']=="LUAR NEGRI"){
	$sLN = "selected";
}

echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=claim">'.BTN_BACK.'</a></div>
		</div>
      </div>
<div class="row">
'.$metnotif.'
<div class="col-md-12">
<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
<div class="panel-heading"><h3 class="panel-title">Document Claim</h3></div>
	<div class="panel-body">
		<div class="form-group">
			<label class="col-sm-2 control-label">Document Type</label>
			<div class="col-sm-7">
				<select name="type" class="form-control" required>
					<option value="">- Pilih -</option>
					<option value="AJK" '.$sAJK.'>AJK</option>
					<option value="DEATH" '.$sDEATH.'>Death</option>
					<option value="PHK" '.$PHK.'>PHK</option>
					<option value="PAW" '.$sPAW.'>PAW</option>
					<option value="OTHER" '.$sOTHER.'>Other</option>
				</select>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label">Document Name</label>
			<div class="col-sm-7"><input type="text" name="namadokumen" class="form-control" value="'.$metDocClaimCek['namadokumen'].'" required></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Mandatory</label>
			<div class="col-sm-7">
				<select name="opsional" class="form-control" required>
					<option value="">- Pilih -</option>
					<option value="RUMAH" '.$sRUMAH.'>Rumah</option>
					<option value="RUMAH SAKIT" '.$sRS.'>Rumah Sakit</option>
					<option value="KECELAKAAN" '.$sKECELAKAAN.'>Kecelakaan</option>
					<option value="LUAR NEGRI" '.$sLN.'>Luar Negri</option>
				</select>
			</div>
		</div>					
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
</form>
</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

	case "setDocClaim":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim Partner</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Document Claim Partner</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-1 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-11">
					<select name="coBroker" class="form-control" onChange="mametBrokerDocClaim(this);" required>
           			<option value="">Select Broker</option>';
					while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
					echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
					}
			echo '</select>
					</div>
		    	</div>

				<div class="form-group">
				<label class="col-sm-1 control-label">Partner <span class="text-danger">*</span></label>
					<div class="col-sm-11">
					<select name="coClient" class="form-control" id="coClient" onChange="mametClientDocClaim(this);" required>
					<option value="">Select Partner</option>
					</select>
					</div>
				</div>

				<div class="form-group">
				<label class="col-lg-1 control-label">Product<strong class="text-danger"> *</strong></label>
		    		<div class="col-lg-11">
		        	<select name="coPolicy" class="form-control" id="coPolicy" required>
		        	<option value="">Select Product</option>
		        	</select>
		        	</div>
				</div>

				<div class="panel-footer"><h4 class="semibold text-primary mt0 mb5">Document Claim</h4></div>
				<br />';
$metDoc = $database->doQuery('SELECT * FROM ajkdocumentclaim ORDER BY namadokumen ASC');
while ($metDoc_ = mysql_fetch_array($metDoc)) {
echo '<div class="col-sm-12"><span class="checkbox custom-checkbox" id="'.$metDoc_['id'].'">
	<input type="checkbox" name="docClaim[]" id="docclaim_'.$metDoc_['id'].'" value="docclaim_'.$metDoc_['id'].'" />
	<label for="docclaim_'.$metDoc_['id'].'"> &nbsp;'.$metDoc_['namadokumen'].'</label>
    </span></div>';
}

echo '</div>
	<div class="panel-footer"><input type="hidden" name="cl" value="docClaimPolicy">'.BTN_SUBMIT.'</div>

	</div>
	</form>
</div>';

echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
//echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
//	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	case "docClaimPolicy":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim Partner</h2></div></div>
	<div class="row">
	<div class="col-md-12">';
if (!$_REQUEST['docClaim']) {
echo '<center><div class="alert alert-danger"><strong>Document claim not selected!</div>
	  <a href="ajk.php?re=setdoc&cl=setDocClaim"><button type="Button" class="btn btn-lg btn-danger">Back to setup document claim</button></a></center>';
}else{
	foreach($_REQUEST['docClaim'] as $k => $val){
	$idDoc_ = explode("docclaim_", $val);
	$met_doc = $database->doQuery('INSERT INTO ajkdocumentclaimpartner SET idbroker="'.$_REQUEST['coBroker'].'", idclient="'.$_REQUEST['coClient'].'", idpolicy="'.$_REQUEST['coPolicy'].'", iddoc="'.$idDoc_[1].'"');
	}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc">
	<div class="alert alert-success fade in">
	<h4 class="semibold">Success!</h4>
<p class="mb10"><strong>Document claim</strong> has been succesfully saved.</p>
    </div>';
}
echo '</div>
</div>';
	;
	break;


case "seteditdoc":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim Partner</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metCoBroker = mysql_fetch_array($database->doQuery('SELECT ajkpolis.id AS idpolis, ajkcobroker.`name` AS namebroker, ajkclient.`name` AS nameclient, ajkpolis.produk, ajkcobroker.id AS idbroker, ajkclient.id AS idclient
													 FROM ajkpolis
													 INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
													 INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
													 WHERE ajkpolis.id = "'.metDecrypt($_REQUEST['iddoc']).'"'));
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate>
			<input type="hidden" name="iddoc" value="'.metDecrypt($_REQUEST['iddoc']).'">
			<input type="hidden" name="coBroker" value="'.$metCoBroker['idbroker'].'">
			<input type="hidden" name="coClient" value="'.$metCoBroker['idclient'].'">
			<input type="hidden" name="coPolicy" value="'.$metCoBroker['idpolis'].'">
			<div class="panel-heading"><h3 class="panel-title">Document Claim Partner</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-1 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input name="coBroker" value="'.$metCoBroker['namebroker'].'" type="text" class="form-control" disabled></div>
		    	</div>

				<div class="form-group">
				<label class="col-sm-1 control-label">Partner <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input name="coClient" value="'.$metCoBroker['nameclient'].'" type="text" class="form-control" disabled></div>
				</div>

				<div class="form-group">
				<label class="col-lg-1 control-label">Product<strong class="text-danger"> *</strong></label>
		    		<div class="col-lg-11"><input name="coPolicy" value="'.$metCoBroker['produk'].'" type="text" class="form-control" disabled></div>
				</div>

				<div class="panel-footer"><h4 class="semibold text-primary mt0 mb5">Document Claim</h4></div>
			<br />';
$metDoc = $database->doQuery('SELECT * FROM ajkdocumentclaim ORDER BY namadokumen ASC');
while ($metDoc_ = mysql_fetch_array($metDoc)) {
$metDocClaim = mysql_fetch_array($database->doQuery('SELECT * FROM ajkdocumentclaimpartner WHERE idpolicy = "'.metDecrypt($_REQUEST['iddoc']).'" AND iddoc="'.$metDoc_['id'].'" AND del IS NULL'));
if ($metDocClaim['id']) {
	$checkDocClass = ' custom-checkbox-teal';
	$CheckDoc = '<input type="checkbox" name="docClaim[]" id="docclaim_'.$metDoc_['id'].'" value="docclaim_'.$metDoc_['id'].'" checked disabled/>';
}else{
	$checkDocClass = '';
	$CheckDoc = '<input type="checkbox" name="docClaim[]" id="docclaim_'.$metDoc_['id'].'" value="docclaim_'.$metDoc_['id'].'"/>';
}
echo '<div class="col-sm-12"><span class="checkbox custom-checkbox '.$checkDocClass.'" id="'.$metDoc_['id'].'">
		'.$CheckDoc.'
		<label for="docclaim_'.$metDoc_['id'].'"> &nbsp;'.$metDoc_['namadokumen'].'</label>
	    </span></div>';
}

echo '</div>
		<div class="panel-footer"><input type="hidden" name="cl" value="docEdClaimPolicy">'.BTN_SUBMIT.'</div>

		</div>
		</form>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

case "docEdClaimPolicy":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim Partner</h2></div></div>
	<div class="row">
	<div class="col-md-12">';
if (!$_REQUEST['docClaim']) {
echo '<center><div class="alert alert-danger"><strong>Document claim not selected!</div>
	  <a href="ajk.php?re=setdoc&cl=seteditdoc&iddoc='.metEncrypt($_REQUEST['iddoc']).'"><button type="Button" class="btn btn-lg btn-danger">Back to setup document claim</button></a></center>';
}else{
	foreach($_REQUEST['docClaim'] as $k => $val){
	$idDoc_ = explode("docclaim_", $val);
	$met_doc = mysql_fetch_array($database->doQuery('SELECT * FROM ajkdocumentclaimpartner WHERE idbroker="'.$_REQUEST['coBroker'].'" AND idclient="'.$_REQUEST['coClient'].'" AND idpolicy="'.$_REQUEST['coPolicy'].'" AND iddoc="'.$idDoc_[1].'"'));
		if ($met_doc['id']) {
			$met_doc = $database->doQuery('UPDATE ajkdocumentclaimpartner SET del=null WHERE id="'.$met_doc['id'].'"');
		}else{
			$met_doc = $database->doQuery('INSERT INTO ajkdocumentclaimpartner SET idbroker="'.$_REQUEST['coBroker'].'", idclient="'.$_REQUEST['coClient'].'", idpolicy="'.$_REQUEST['coPolicy'].'", iddoc="'.$idDoc_[1].'"');
		}
}
echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc">
		<div class="alert alert-success fade in">
		<h4 class="semibold">Success!</h4>
    <p class="mb10"><strong>Document claim</strong> has been succesfully edited.</p>
    </div>';
		}
echo '</div>
	</div>';
	;
	break;

	case "setviewdoc":
echo '<div class="page-header-section"><h2 class="title semibold">View Document Claim Partner</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metCoBroker = mysql_fetch_array($database->doQuery('SELECT
ajkdocumentclaim.namadokumen,
ajkdocumentclaimpartner.id AS iddocpartner,
ajkdocumentclaimpartner.del,
ajkdocumentclaim.id,
ajkcobroker.id AS brokerid,
ajkcobroker.`name` AS brokername,
ajkclient.id AS clientid,
ajkclient.`name` AS clientname,
ajkpolis.id AS produkid,
ajkpolis.produk AS produk
FROM ajkdocumentclaimpartner
INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
INNER JOIN ajkcobroker ON ajkdocumentclaimpartner.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkdocumentclaimpartner.idclient = ajkclient.id
INNER JOIN ajkpolis ON ajkdocumentclaimpartner.idpolicy = ajkpolis.id
WHERE ajkdocumentclaimpartner.idpolicy ="'.metDecrypt($_REQUEST['iddoc']).'"'));
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate>
			<input type="hidden" name="iddoc" value="'.metDecrypt($_REQUEST['iddoc']).'">
			<input type="hidden" name="coBroker" value="'.$metCoBroker['brokerid'].'">
			<input type="hidden" name="coClient" value="'.$metCoBroker['clientid'].'">
			<input type="hidden" name="coPolicy" value="'.$metCoBroker['produkid'].'">
			<div class="panel-heading"><h3 class="panel-title">Document Claim Partner</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-1 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input name="coBroker" value="'.$metCoBroker['brokername'].'" type="text" class="form-control" disabled></div>
		    	</div>

				<div class="form-group">
				<label class="col-sm-1 control-label">Partner <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input name="coClient" value="'.$metCoBroker['clientname'].'" type="text" class="form-control" disabled></div>
				</div>

				<div class="form-group">
				<label class="col-lg-1 control-label">Product<strong class="text-danger"> *</strong></label>
		    		<div class="col-lg-11"><input name="coPolicy" value="'.$metCoBroker['produk'].'" type="text" class="form-control" disabled></div>
				</div>

				<div class="panel-footer"><h4 class="semibold text-primary mt0 mb5">Document Claim</h4></div>
			<br />
			<div class="col-sm-12">
		<span class="checkbox custom-checkbox '.$checkDocClass.'" id="'.$metDoc_['id'].'">';
if ($_REQUEST['ddoc']=="deldocc") {
$metDelDoc = $database->doQuery('UPDATE ajkdocumentclaimpartner SET del ="1" WHERE id="'.metDecrypt($_REQUEST['docDel']).'"');
header("location:ajk.php?re=setdoc&cl=setviewdoc&iddoc=".$_REQUEST['iddoc']."");
}
$metDoc = $database->doQuery('SELECT ajkdocumentclaim.namadokumen, ajkdocumentclaimpartner.id AS iddocpartner, ajkdocumentclaimpartner.del, ajkdocumentclaim.id
							FROM ajkdocumentclaimpartner
							INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
							WHERE ajkdocumentclaimpartner.idpolicy = "'.metDecrypt($_REQUEST['iddoc']).'" AND del IS NULL
							ORDER BY namadokumen ASC');
while ($metDoc_ = mysql_fetch_array($metDoc)) {
//<button class="btn btn-sm btn-teal mb5" id="bootbox-confirm">Confirm</button>
echo '<div class="col-sm-12">
		<label><a href="ajk.php?re=setdoc&cl=setviewdoc&ddoc=deldocc&iddoc='.metEncrypt($metCoBroker['produkid']).'&docDel='.metEncrypt($metDoc_['iddocpartner']).'" onClick="if(confirm(\'Delete this document claim ?\')){return true;}{return false;}">'.BTN_DEL.'</a></label>
		<label for="'.$metDoc_['id'].'"> &nbsp;'.$metDoc_['namadokumen'].'</label>
    	</span>
    </div>';
	}

echo '</div>
		</div>
		</form>
	</div>';
	;
	break;

case "pod":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=newpod">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
      <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <th>Place of Death</th>
        <th width="1%">Edit</th>
        </tr>
    </thead>
    <tbody>';
$metClaimPlace = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="tempatmeninggal" AND del IS NULL ORDER BY id ASC');
while ($metClaimPlace_ = mysql_fetch_array($metClaimPlace)) {
echo '<tr>
   	<td>'.$metClaimPlace_['nama'].'</td>
   	<td align="center"><a href="ajk.php?re=setdoc&cl=edpod&fid='.$thisEncrypter->encode($metClaimPlace_['id']).'">'.BTN_EDIT.'</a></td>
</tr>';
}
echo '</tbody>
	    </table>
    </div>';
	;
	break;
case "newpod":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=pod">'.BTN_BACK.'</a></div>
		</div>
      </div>';
if ($_REQUEST['met']=="savepod") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE nama="'.strtoupper($_REQUEST['placename']).'" AND tipe="tempatmeninggal"'));
	if ($metExcelCek) {
		$metnotif .= '<div class="alert alert-dismissable alert-danger">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            	     <strong>Error!</strong> Place of death '.$_REQUEST['namadokumen'].' already exists.
                	 </div>';
	}else{
		$metExcel = $database->doQuery('INSERT INTO ajkkejadianklaim SET nama="'.strtoupper($_REQUEST['placename']).'", tipe="tempatmeninggal"');
		$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc&cl=pod">
					<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        	         <strong>Success!</strong> Place of death '.$_REQUEST['namadokumen'].' have been insert.
    				</div>';
	}
}
echo '<div class="row">
<div class="col-md-12">
'.$metnotif.'
<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
	<div class="panel-heading"><h3 class="panel-title">Place of death</h3></div>
	<div class="panel-body">
		<div class="form-group">
		<label class="col-sm-1 control-label">Place</label>
		<div class="col-sm-11"><input type="text" name="placename" class="form-control" required></div>
		</div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savepod">'.BTN_SUBMIT.'</div>
	</form>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
case "edpod":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=pod">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metplacedeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"'));
if ($_REQUEST['met']=="saveedpod") {
$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE nama="'.strtoupper($_REQUEST['placename']).'" AND tipe="tempatmeninggal"'));
	if ($metExcelCek) {
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
         	     <strong>Error!</strong> Place of death '.$_REQUEST['namadokumen'].' already exists.
              	 </div>';
	}else{
	$metExcel = $database->doQuery('UPDATE ajkkejadianklaim SET nama="'.strtoupper($_REQUEST['placename']).'" WHERE id="'.$metplacedeath['id'].'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc&cl=pod">
					<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
       	         <strong>Success!</strong> Place of death '.$_REQUEST['namadokumen'].' have been edited.
    			</div>';
		}
	}
echo '<div class="row">
<div class="col-md-12">
'.$metnotif.'
<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
	<div class="panel-heading"><h3 class="panel-title">Place of death</h3></div>
	<div class="panel-body">
		<div class="form-group">
		<label class="col-sm-1 control-label">Place</label>
		<div class="col-sm-11"><input type="text" name="placename" value="'.$metplacedeath['nama'].'" class="form-control" required></div>
		</div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveedpod">'.BTN_SUBMIT.'</div>
	</form>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;


case "cod":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=newcod">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
     <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <th>Causes of Death</th>
        <th width="1%">Edit</th>
        </tr>
    </thead>
    <tbody>';
$metClaimPlace = $database->doQuery('SELECT * FROM ajkkejadianklaim WHERE tipe="penyebabmeninggal" AND del IS NULL ORDER BY id ASC');
while ($metClaimPlace_ = mysql_fetch_array($metClaimPlace)) {
echo '<tr>
	   	<td>'.$metClaimPlace_['nama'].'</td>
	   	<td align="center"><a href="ajk.php?re=setdoc&cl=edcod&fid='.$thisEncrypter->encode($metClaimPlace_['id']).'">'.BTN_EDIT.'</a></td>
	</tr>';
}
echo '</tbody>
	    </table>
    </div>';
	;
	break;
	case "newcod":
		echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=cod">'.BTN_BACK.'</a></div>
		</div>
      </div>';
if ($_REQUEST['met']=="savecod") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE nama="'.strtoupper($_REQUEST['placename']).'" AND tipe="penyebabmeninggal"'));
	if ($metExcelCek) {
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
           	     <strong>Error!</strong> Cause of death '.$_REQUEST['namadokumen'].' already exists.
               	 </div>';
	}else{
	$metExcel = $database->doQuery('INSERT INTO ajkkejadianklaim SET nama="'.strtoupper($_REQUEST['placename']).'", tipe="penyebabmeninggal"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc&cl=cod">
					<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        	         <strong>Success!</strong> Cause of death '.$_REQUEST['namadokumen'].' have been insert.
   				</div>';
		}
	}
echo '<div class="row">
<div class="col-md-12">
'.$metnotif.'
<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
	<div class="panel-heading"><h3 class="panel-title">Cause of death</h3></div>
	<div class="panel-body">
		<div class="form-group">
		<label class="col-sm-1 control-label">Causes</label>
		<div class="col-sm-11"><input type="text" name="placename" class="form-control" required></div>
		</div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="savecod">'.BTN_SUBMIT.'</div>
	</form>
	</div>
</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;
	case "edcod":
echo '<div class="page-header-section"><h2 class="title semibold">Setup Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=cod">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metplacedeath = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"'));
if ($_REQUEST['met']=="saveedcod") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkkejadianklaim WHERE nama="'.strtoupper($_REQUEST['placename']).'" AND tipe="penyebabmeninggal"'));
	if ($metExcelCek) {
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
         	     <strong>Error!</strong> Cause of death '.$_REQUEST['namadokumen'].' already exists.
              	 </div>';
	}else{
	$metExcel = $database->doQuery('UPDATE ajkkejadianklaim SET nama="'.strtoupper($_REQUEST['placename']).'" WHERE id="'.$metplacedeath['id'].'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=setdoc&cl=cod">
					<div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
       	         <strong>Success!</strong> Cause of death '.$_REQUEST['namadokumen'].' have been edited.
    			</div>';
	}
}
echo '<div class="row">
	<div class="col-md-12">
	'.$metnotif.'
	<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
		<div class="panel-heading"><h3 class="panel-title">Cause of death</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-1 control-label">Causes</label>
			<div class="col-sm-11"><input type="text" name="placename" value="'.$metplacedeath['nama'].'" class="form-control" required></div>
			</div>
		</div>
		<div class="panel-footer"><input type="hidden" name="met" value="saveedcod">'.BTN_SUBMIT.'</div>
		</form>
		</div>
	</div>';
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">Setup Document Claim</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=setdoc&cl=setDocClaim">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
	<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
      <thead>
      	<tr>
        <th width="80%">Partner</th>
        <th>Product</th>
        <th>Document</th>
        <th>Edit</th>
        </tr>
    </thead>
    <tbody>';
$metDocClaim = $database->doQuery('SELECT ajkclient.`name` AS clientname, ajkpolis.produk, Count(ajkdocumentclaim.namadokumen) AS dokumen, ajkdocumentclaimpartner.idpolicy
								   FROM ajkdocumentclaimpartner
								   INNER JOIN ajkdocumentclaim ON ajkdocumentclaimpartner.iddoc = ajkdocumentclaim.id
								   INNER JOIN ajkclient ON ajkdocumentclaimpartner.idclient = ajkclient.id
								   INNER JOIN ajkpolis ON ajkdocumentclaimpartner.idpolicy = ajkpolis.id
								   WHERE ajkdocumentclaimpartner.del IS NULL '.$q___.'
								   GROUP BY ajkpolis.id
								   ORDER BY clientname ASC');
while ($metDocClaim_ = mysql_fetch_array($metDocClaim)) {
echo '<tr>
	   	<td>'.$metDocClaim_['clientname'].'</td>
	   	<td align="center">'.$metDocClaim_['produk'].'</td>
	   	<td align="center"><a href="ajk.php?re=setdoc&cl=setviewdoc&iddoc='.metEncrypt($metDocClaim_['idpolicy']).'"><span class="number"><span class="label label-primary">'.$metDocClaim_['dokumen'].'</span></span><a></td>
	   	<td align="center"><a href="ajk.php?re=setdoc&cl=seteditdoc&iddoc='.metEncrypt($metDocClaim_['idpolicy']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
	    </table>
    </div>';
	;
} // switch

echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>