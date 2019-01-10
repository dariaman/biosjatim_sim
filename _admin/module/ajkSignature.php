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
switch ($_REQUEST['op']) {
	case "s":
		;
		break;
	case "signdnbank":
echo '<div class="page-header-section"><h2 class="title semibold">Signature of Debitnote Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signdnbanknew">'.BTN_NEW.'</a></div>
		</div>
      </div>';

		//echo '<div class="table-responsive panel-collapse pull out">
		echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		      <thead>
		      	<tr>
		        <th width="1%">No</th>
		        <th width="20%">Partner</th>
		        <th width="20%">Product</th>
		        <th width="10%">Type</th>
		        <th>Name</th>
		        <th width="10%">Sign</th>
		        <th width="1%">Status</th>
		        <th width="10%">Option</th>
		        </tr>
		    </thead>
		    <tbody>';
$metClient = $database->doQuery('SELECT
ajkcobroker.`name` AS namebroker,
ajkclient.`name` AS namepartner,
ajkpolis.produk AS nameproduk,
ajksignature.id,
ajksignature.type,
ajksignature.status,
ajksignature.nama AS namesign,
ajksignature.ttd
FROM ajksignature
INNER JOIN ajkcobroker ON ajksignature.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajksignature.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajksignature.idproduk = ajkpolis.id
WHERE ajksignature.type = "BANKDN" '.$q___.'');
while ($metClient_ = mysql_fetch_array($metClient)) {
if ($metClient_['ttd']=="") {
	$logoclient = '<div class="media-object"><img src="../'.$PathSignature.'logo.png" alt="" class="img-circle"></div>';
}else{
	$logoclient = '<div class="media-object"><img src="../'.$PathSignature.''.$metClient_['ttd'].'" alt="" class="img-circle"></div>';
}
if ($metClient_['status']=="Aktif") {
	$statusnya_ = '<span class="label label-success">'.$metClient_['status'].'</span>';
}else{
	$statusnya_ = '<span class="label label-danger">'.$metClient_['status'].'</span>';
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metClient_['namepartner'].'</td>
   	<td>'.$metClient_['nameproduk'].'</td>
   	<td align="center">'.$metClient_['type'].'</td>
   	<td>'.$metClient_['namesign'].'</td>
   	<td align="center"><img src="../'.$PathSignature.''.$metClient_['ttd'].'" width="75"></td>
   	<td align="center">'.$statusnya_.'</td>
   	<td align="center"><a href="ajk.php?re=signature&op=signdnbankedit&sid='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
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
	case "signdnbanknew":
echo '<div class="page-header-section"><h2 class="title semibold">Signature Of Debitnote Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signdnbank">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">New Signature Debitnote</h3></div>
			<div class="panel-body">
				<div class="form-group">';
if ($_REQUEST['els']=="ttdsave") {
	$ex_ = explode("_", $_REQUEST['coPolicy']);
	$PathSignature		= "../myFiles/_signature/".$foldername."";
	if (!file_exists($PathSignature)) 	{	mkdir($PathSignature, 0777);	chmod($PathSignature, 0777);	}
	$namafileupload =  str_replace(" ", "_", $foldername."SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
	$nama_fileupload =  str_replace(" ", "_", "SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
	$file_type = $_FILES['fileImage']['type']; //tipe file
	$source = $_FILES['fileImage']['tmp_name'];
	$direktori = "$PathSignature$nama_fileupload"; // direktori tempat menyimpan file
	move_uploaded_file($source,$direktori);

	$metSIGN = $database->doQuery('INSERT INTO ajksignature SET idbroker="'.$_REQUEST['coBroker'].'",
																idpartner="'.$_REQUEST['coClient'].'",
																idproduk="'.$ex_[0].'",
																type="BANKDN",
																nama="'.strtoupper($_REQUEST['signname']).'",
																ttd="'.$namafileupload.'",
																status="Aktif",
																inputby="'.$q['id'].'",
																inputdate="'.$futgl.'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=signature&op=signdnbank">
				 <div class="alert alert-dismissable alert-success">
                 <strong>Success!</strong> New sign of Debitnote.
                 </div>';
}
echo $metnotif;
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
	<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
		<div class="col-sm-10">
		<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
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
	</div>';
}else{
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
	  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
echo '<label class="col-sm-2 control-label">Partner<strong class="text-danger"> *</strong></label>
	<div class="col-sm-10">
	<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);" required><option value="">Select Partner</option>';
	$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
	while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
	echo '</select>
		</div>
	</div>
	<div class="form-group">
	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		<div class="col-lg-10"><select name="coPolicy" class="form-control" id="coProduct" required><option value="">Select Product</option></select></div>
	</div>';
}
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Name of Signature<span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="signname" value="'.$_REQUEST['signname'].'" class="form-control" placeholder="Name of Signature" required></div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Upload Image TTD<span class="text-danger">*</span></label>
    <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*" required></div>
	</div>';
echo '	</div>
		<div class="panel-footer"><input type="hidden" name="els" value="ttdsave">'.BTN_SUBMIT.'</div>
		</form>
		</div>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;
	case "signdnbankedit":
echo '<div class="page-header-section"><h2 class="title semibold">Signature Of Debitnote Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signdnbank">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metSign_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajksignature WHERE id="'.$thisEncrypter->decode($_REQUEST['sid']).'"'));
if ($metSign_['status']=="Aktif") {
	$metCh = 'checked';
}else{
	$metCh = '';
}
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Edit Signature Debitnote</h3></div>
				<div class="panel-body">
					<div class="form-group">';
if ($_REQUEST['els']=="ttdsaveedit") {
	if ($_FILES['fileImage']['name']) {
		$ex_ = explode("_", $_REQUEST['coPolicy']);
		$PathSignature		= "../myFiles/_signature/".$foldername."";
		if (!file_exists($PathSignature)) 	{	mkdir($PathSignature, 0777);	chmod($PathSignature, 0777);	}
		$namafileupload =  str_replace(" ", "_", $foldername."SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
		$nama_fileupload =  str_replace(" ", "_", "SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
		$file_type = $_FILES['fileImage']['type']; //tipe file
		$source = $_FILES['fileImage']['tmp_name'];
		$direktori = "$PathSignature$nama_fileupload"; // direktori tempat menyimpan file
		move_uploaded_file($source,$direktori);
		$_ttdnya = 'ttd="'.$namafileupload.'",';
	}else{
		$_ttdnya = '';
	}
	if ($_REQUEST['status']=="Aktif") {	$_metStatusttd='Aktif';	}else{	$_metStatusttd='Non Aktif';	}
	$metSIGN = $database->doQuery('UPDATE ajksignature SET nama="'.strtoupper($_REQUEST['signname']).'",
																'.$_ttdnya.'
																status="'.$_metStatusttd.'",
																updateby="'.$q['id'].'",
																updatedate="'.$futgl.'"
									WHERE id="'.$thisEncrypter->decode($_REQUEST['sid']).'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=signature&op=signdnbank">
			<div class="alert alert-dismissable alert-success">
			<strong>Success!</strong> Edit sign of Debitnote.
            </div>';
}
echo $metnotif;
if ($q['idbroker'] == NULL) {
echo '<label class="col-sm-2 control-label">Broker</label>
		<div class="col-sm-10">
		<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
	$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' AND id="'.$metSign_['idbroker'].'" ORDER BY name ASC');
	while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
	echo '</select>
	</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
	<div class="col-sm-10">
	<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
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
</div>';
	}else{
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
		  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
echo '<label class="col-sm-2 control-label">Partner</label>
	<div class="col-sm-10">
	<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);">';
	$metCoPartner = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" AND id="'.$metSign_['idpartner'].'" ORDER BY name ASC');
	while ($metCoPartner_ = mysql_fetch_array($metCoPartner)) {	echo '<option value="'.$metCoPartner_['id'].'"'._selected($metSign_['idpartner'], $metCoPartner_['id']).' disabled>'.$metCoPartner_['name'].'</option>';	}
	echo '</select>
			</div>
		</div>
		<div class="form-group">
		<label class="col-lg-2 control-label">Product</label>
			<div class="col-lg-10"><select name="coPolicy" class="form-control" id="coProduct">';
	$metCoProduk = $database->doQuery('SELECT * FROM ajkpolis WHERE del IS NULL AND idcost="'.$metSign_['idpartner'].'" AND id="'.$metSign_['idproduk'].'" ORDER BY produk ASC');
	while ($metCoProduk_ = mysql_fetch_array($metCoProduk)) {	echo '<option value="'.$metCoProduk_['id'].'"'._selected($metSign_['idproduk'], $metCoProduk_['id']).' disabled>'.$metCoProduk_['produk'].'</option>';	}
	echo '</select></div>
		</div>';
}
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Name of Signature<span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="signname" value="'.$metSign_['nama'].'" class="form-control" placeholder="Name of Signature" required></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Status Aktif</label>
		<div class="col-sm-10"><label class="switch switch-md switch-warning"><input type="checkbox" name="status" value="Aktif" '.$metCh.'><span class="switch"></span></label></div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Upload Image TTD<br /><img src="../'.$PathSignature.''.$metSign_['ttd'].'" width="100"></label>
    <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*"></div>
	</div>';
echo '	</div>
		<div class="panel-footer"><input type="hidden" name="els" value="ttdsaveedit">'.BTN_SUBMIT.'</div>
		</form>
		</div>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

	case "signkwbank":
echo '<div class="page-header-section"><h2 class="title semibold">Signature of Kuitansi Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signkwbanknew">'.BTN_NEW.'</a></div>
		</div>
      </div>';

//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
		    <th width="20%">Partner</th>
		    <th width="20%">Product</th>
		    <th width="10%">Type</th>
		    <th>Name</th>
		    <th width="10%">Sign</th>
		    <th width="1%">Status</th>
		    <th width="10%">Option</th>
		    </tr>
		</thead>
		<tbody>';
$metClient = $database->doQuery('SELECT
ajkcobroker.`name` AS namebroker,
ajkclient.`name` AS namepartner,
ajkpolis.produk AS nameproduk,
ajksignature.id,
ajksignature.type,
ajksignature.status,
ajksignature.nama AS namesign,
ajksignature.ttd
FROM ajksignature
INNER JOIN ajkcobroker ON ajksignature.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajksignature.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajksignature.idproduk = ajkpolis.id
WHERE ajksignature.type = "BANKKUITANSI" '.$q___.'');
		while ($metClient_ = mysql_fetch_array($metClient)) {
			if ($metClient_['ttd']=="") {
				$logoclient = '<div class="media-object"><img src="../'.$PathSignature.'logo.png" alt="" class="img-circle"></div>';
			}else{
				$logoclient = '<div class="media-object"><img src="../'.$PathSignature.''.$metClient_['ttd'].'" alt="" class="img-circle"></div>';
			}
			if ($metClient_['status']=="Aktif") {
				$statusnya_ = '<span class="label label-success">'.$metClient_['status'].'</span>';
			}else{
				$statusnya_ = '<span class="label label-danger">'.$metClient_['status'].'</span>';
			}
			echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metClient_['namepartner'].'</td>
   	<td>'.$metClient_['nameproduk'].'</td>
   	<td align="center">'.$metClient_['type'].'</td>
   	<td>'.$metClient_['namesign'].'</td>
   	<td align="center"><img src="../'.$PathSignature.''.$metClient_['ttd'].'" width="75"></td>
   	<td align="center">'.$statusnya_.'</td>
   	<td align="center"><a href="ajk.php?re=signature&op=signkwbankedit&sid='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
		}
		echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
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
	case "signkwbanknew":
echo '<div class="page-header-section"><h2 class="title semibold">Signature Kuitansi Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signkwbank">'.BTN_BACK.'</a></div>
		</div>
      </div>';
		$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
		echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<div class="panel-heading"><h3 class="panel-title">New Signature Debitnote</h3></div>
					<div class="panel-body">
						<div class="form-group">';
		if ($_REQUEST['els']=="ttdsave") {
			$ex_ = explode("_", $_REQUEST['coPolicy']);
			$PathSignature		= "../myFiles/_signature/".$foldername."";
			if (!file_exists($PathSignature)) 	{	mkdir($PathSignature, 0777);	chmod($PathSignature, 0777);	}
			$namafileupload =  str_replace(" ", "_", $foldername."SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
			$nama_fileupload =  str_replace(" ", "_", "SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
			$file_type = $_FILES['fileImage']['type']; //tipe file
			$source = $_FILES['fileImage']['tmp_name'];
			$direktori = "$PathSignature$nama_fileupload"; // direktori tempat menyimpan file
			move_uploaded_file($source,$direktori);

			$metSIGN = $database->doQuery('INSERT INTO ajksignature SET idbroker="'.$_REQUEST['coBroker'].'",
																idpartner="'.$_REQUEST['coClient'].'",
																idproduk="'.$ex_[0].'",
																type="BANKKUITANSI",
																nama="'.strtoupper($_REQUEST['signname']).'",
																ttd="'.$namafileupload.'",
																status="Aktif",
																inputby="'.$q['id'].'",
																inputdate="'.$futgl.'"');
			$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=signature&op=signkwbank">
						 <div class="alert alert-dismissable alert-success">
                 <strong>Success!</strong> New sign of Kuitansi Debitnote.
                 </div>';
		}
		echo $metnotif;
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
	<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
		<div class="col-sm-10">
		<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
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
	</div>';
		}else{
			echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
			$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
			echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
				  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
			echo '<label class="col-sm-2 control-label">Partner<strong class="text-danger"> *</strong></label>
				<div class="col-sm-10">
				<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);" required><option value="">Select Partner</option>';
			$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
					</div>
				</div>
				<div class="form-group">
				<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
					<div class="col-lg-10"><select name="coPolicy" class="form-control" id="coProduct" required><option value="">Select Product</option></select></div>
				</div>';
		}
		echo '<div class="form-group">
				<label class="col-sm-2 control-label">Name of Signature<span class="text-danger">*</span></label>
				<div class="col-sm-10"><input type="text" name="signname" value="'.$_REQUEST['signname'].'" class="form-control" placeholder="Name of Signature" required></div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Upload Image TTD<span class="text-danger">*</span></label>
		    <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*" required></div>
			</div>';
		echo '	</div>
				<div class="panel-footer"><input type="hidden" name="els" value="ttdsave">'.BTN_SUBMIT.'</div>
				</form>
				</div>
			</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;
	case "signkwbankedit":
echo '<div class="page-header-section"><h2 class="title semibold">Signature Of Debitnote Bank</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=signature&op=signdnbank">'.BTN_BACK.'</a></div>
		</div>
      </div>';
$metSign_ = mysql_fetch_array($database->doQuery('SELECT * FROM ajksignature WHERE id="'.$thisEncrypter->decode($_REQUEST['sid']).'"'));
if ($metSign_['status']=="Aktif") {
	$metCh = 'checked';
}else{
$metCh = '';
}
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Edit Signature Kuitansi Debitnote</h3></div>
				<div class="panel-body">
					<div class="form-group">';
if ($_REQUEST['els']=="ttdsaveedit") {
	if ($_FILES['fileImage']['name']) {
		$ex_ = explode("_", $_REQUEST['coPolicy']);
		$PathSignature		= "../myFiles/_signature/".$foldername."";
		if (!file_exists($PathSignature)) 	{	mkdir($PathSignature, 0777);	chmod($PathSignature, 0777);	}
		$namafileupload =  str_replace(" ", "_", $foldername."SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
		$nama_fileupload =  str_replace(" ", "_", "SIGN_".date("YmdHis")."_P".$_REQUEST['coClient'].'_'.$_FILES['fileImage']['name']);
		$file_type = $_FILES['fileImage']['type']; //tipe file
		$source = $_FILES['fileImage']['tmp_name'];
		$direktori = "$PathSignature$nama_fileupload"; // direktori tempat menyimpan file
		move_uploaded_file($source,$direktori);
		$_ttdnya = 'ttd="'.$namafileupload.'",';
	}else{
		$_ttdnya = '';
	}
	if ($_REQUEST['status']=="Aktif") {	$_metStatusttd='Aktif';	}else{	$_metStatusttd='Non Aktif';	}
	$metSIGN = $database->doQuery('UPDATE ajksignature SET nama="'.strtoupper($_REQUEST['signname']).'",
														'.$_ttdnya.'
														status="'.$_metStatusttd.'",
														updateby="'.$q['id'].'",
														updatedate="'.$futgl.'"
							WHERE id="'.$thisEncrypter->decode($_REQUEST['sid']).'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=signature&op=signkwbank">
			<div class="alert alert-dismissable alert-success">
			<strong>Success!</strong> Edit sign of Kuitansi Debitnote.
          </div>';
}
echo $metnotif;
if ($q['idbroker'] == NULL) {
echo '<label class="col-sm-2 control-label">Broker</label>
		<div class="col-sm-10">
		<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
	$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' AND id="'.$metSign_['idbroker'].'" ORDER BY name ASC');
	while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
	echo '</select>
	</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
			<div class="col-sm-10">
			<select name="coClient" class="form-control" id="coClient" onChange="mametClientUploadExcel(this);" required>
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
</div>';
}else{
	echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);">Broker Name &nbsp; </a></p></div>';
	$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
	echo '<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_broker['name'].'</a></p></div>
			  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
	echo '<label class="col-sm-2 control-label">Partner</label>
		<div class="col-sm-10">
		<select name="coClient" class="form-control" onChange="mametClientProdukRateIns(this);">';
	$metCoPartner = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" AND id="'.$metSign_['idpartner'].'" ORDER BY name ASC');
	while ($metCoPartner_ = mysql_fetch_array($metCoPartner)) {	echo '<option value="'.$metCoPartner_['id'].'"'._selected($metSign_['idpartner'], $metCoPartner_['id']).' disabled>'.$metCoPartner_['name'].'</option>';	}
	echo '</select>
				</div>
			</div>
			<div class="form-group">
			<label class="col-lg-2 control-label">Product</label>
				<div class="col-lg-10"><select name="coPolicy" class="form-control" id="coProduct">';
	$metCoProduk = $database->doQuery('SELECT * FROM ajkpolis WHERE del IS NULL AND idcost="'.$metSign_['idpartner'].'" AND id="'.$metSign_['idproduk'].'" ORDER BY produk ASC');
	while ($metCoProduk_ = mysql_fetch_array($metCoProduk)) {	echo '<option value="'.$metCoProduk_['id'].'"'._selected($metSign_['idproduk'], $metCoProduk_['id']).' disabled>'.$metCoProduk_['produk'].'</option>';	}
	echo '</select></div>
			</div>';
}
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Name of Signature<span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="text" name="signname" value="'.$metSign_['nama'].'" class="form-control" placeholder="Name of Signature" required></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Status Aktif</label>
		<div class="col-sm-10"><label class="switch switch-md switch-warning"><input type="checkbox" name="status" value="Aktif" '.$metCh.'><span class="switch"></span></label></div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Upload Image TTD<br /><img src="../'.$PathSignature.''.$metSign_['ttd'].'" width="100"></label>
	   <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*"></div>
	</div>';
echo '	</div>
		<div class="panel-footer"><input type="hidden" name="els" value="ttdsaveedit">'.BTN_SUBMIT.'</div>
		</form>
		</div>
	</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;



	default:
		;
} // switch

echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>