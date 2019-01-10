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
switch ($_REQUEST['el']) {
	case "duseracc":
		;
		break;

	case "euseracc":
echo '<div class="page-header-section"><h2 class="title semibold">User Access</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=uaccess">'.BTN_BACK.'</a></div>
		</div>
	</div>';
$metUser = mysql_fetch_array($database->doQuery('SELECT * FROM useraccess WHERE id="'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
if ($metUser['tipe']=="" || $metUser['tipe']==NULL) {
	$modChecked = '<input type="radio"'.pilih($_REQUEST['modeuser'], "_A_").' name="modeuser" id="customradio19" value="_A_" '.$modCheck.' checked><label for="customradio19">&nbsp; Broker &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_C_").' name="modeuser" id="customradio18" value="_C_" '.$modCheck.' disabled><label for="customradio18">&nbsp; Client &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_T_").' name="modeuser" id="customradioga" value="_T_" '.$modCheck.' disabled><label for="customradioga">&nbsp; Insurance &nbsp;&nbsp;</label>';
}elseif($metUser['tipe']=="Bank" || $metUser['tipe']=="Dokter" || $metUser['tipe']=="Appraisal" || $metUser['tipe']=="Direksi" || $metUser['tipe']=="Kadiv") {
	$modChecked = '<input type="radio"'.pilih($_REQUEST['modeuser'], "_A_").' name="modeuser" id="customradio19" value="_A_" '.$modCheck.' disabled><label for="customradio19">&nbsp; Broker &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_C_").' name="modeuser" id="customradio18" value="_C_" '.$modCheck.' checked><label for="customradio18">&nbsp; Client &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_T_").' name="modeuser" id="customradioga" value="_T_" '.$modCheck.' disabled><label for="customradioga">&nbsp; Insurance &nbsp;&nbsp;</label>';
}else{
	$modChecked = '<input type="radio"'.pilih($_REQUEST['modeuser'], "_A_").' name="modeuser" id="customradio19" value="_A_" '.$modCheck.' disabled><label for="customradio19">&nbsp; Broker &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_C_").' name="modeuser" id="customradio18" value="_C_" '.$modCheck.' disabled><label for="customradio18">&nbsp; Client &nbsp;&nbsp;</label>
				   <input type="radio"'.pilih($_REQUEST['modeuser'], "_T_").' name="modeuser" id="customradioga" value="_T_" '.$modCheck.' checked><label for="customradioga">&nbsp; Insurance &nbsp;&nbsp;</label>';
}

if ($_REQUEST['met']=="saveeditme") {
$cekUname = mysql_fetch_array($database->doQuery('SELECT username FROM useraccess WHERE username="'.$_REQUEST['uname'].'"'));
	if ($_FILES['fileImage']['size'] / 1024 > $FILESIZE_2)	{
	$metnotif .= '<div class="col-md-12"><div class="alert alert-dismissable alert-danger">
				<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
            	</div></div>';
}
else{
	if ($_FILES['fileImage']['name']) {
		$nama_file =  strtolower(strtoupper($_REQUEST['fileImage']).$_REQUEST['uname'].'_'.$_FILES['fileImage']['name']);
		//echo $nama_file;
		$setPhotoUser = 'photo="'.$nama_file.'",
						 photothumb="'.$nama_file_thumb.'",';
		$nama_file_thumb =  strtolower("thumb_".strtoupper($_REQUEST['fileImage']).$_REQUEST['uname'].'_'.$_FILES['fileImage']['name']);
		metImage($nama_file);
	}else{
		$setPhotoUser = '';
	}

	if ($_REQUEST['coBroker']=="") 		{	$_qBroker='';		}else{	$_qBroker='idbroker="'.$_REQUEST['coBroker'].'",';	}
	if ($_REQUEST['coClient']=="") 		{	$_qClient='';		}else{	$_qClient='idclient="'.$_REQUEST['coClient'].'",';	}
	if ($_REQUEST['coRegional']=="") 	{	$_qRegional='';		}else{	$_qRegional='regional="'.$_REQUEST['coRegional'].'",';	}
	if ($_REQUEST['coCabang']=="") 		{	$_qBranch='';		}else{	$_qBranch='branch="'.$_REQUEST['coCabang'].'",';	}
	if ($_REQUEST['metspv']=="") 		{	$_qSupervisor='';	}else{	$_qSupervisor='supervisor="'.$_REQUEST['metspv'].'",';	}

	$metUser = $database->doQuery('UPDATE useraccess SET '.$_qBroker.'
														  '.$_qClient.'
														  '.$_qRegional.'
														  '.$_qBranch.'
														  tipe="'.$_REQUEST['tipe'].'",
														  firstname="'.$_REQUEST['fname'].'",
														  lastname="'.$_REQUEST['lname'].'",
														  gender="'.$_REQUEST['gender'].'",
														  dob="'._convertDate2($_REQUEST['dob']).'",
														  passw="'.md5($_REQUEST['pname']).'",
														  mamet="'.$_REQUEST['pname'].'",
														  level="'.$_REQUEST['ulevel'].'",
														  email="'.$_REQUEST['email'].'",
														  '.$_qSupervisor.'
														  '.$setPhotoUser.'
														  update_by="'.$q['id'].'",
														  update_time="'.$futgl.'"
									WHERE id="'.$metUser['id'].'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=uaccess">
				<div class="col-md-12"><div class="alert alert-dismissable alert-success">
				<strong>Success!</strong> Edit user access partner.
				</div></div>';
	}
}

echo '<div class="row">
	'.$metnotif.'<br />'.$metnotifCekUser.'
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">Edit Form User Access '.$metUser['tipe'].'</h3></div>
		<div class="panel-body">';
		echo '<div class="form-group">
		<label class="col-sm-2 control-label">Useraccess</label>
        <div class="col-sm-10">
        	<span class="radio custom-radio custom-radio-primary">
            '.$modChecked.'
            </span>
		</div>
	</div>

<!--Type User Adminsitrator-->
<div class="_A_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
	<div class=" col-sm-10">
		<div class="panel panel-success">
        <div class="panel-heading"><h3 class="panel-title">User Administrator</h3></div>
			<div class="panel-body">';
		if ($q['idbroker'] == NULL) {
			echo ''.$errorbroker.'<div class="form-group">
	  <label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
		<div class="col-sm-10">
	  	<select name="coBrokers" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['coBrokers'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
					</div>
				</div>
				'.$errorlevel_A.'
				<div class="form-group">
			    <label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
				   	<div class="col-sm-10">
			    		<select name="ulevel" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Office" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevel'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
					</div>
				</div>';
		}else{
			$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
			echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker Name &nbsp; </strong></a></p></div>
				  <div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$_broker['name'].'</strong></a></p></div>
				  <input type="hidden" name="coBrokers" value="'.$q['idbroker'].'">
				<div class="form-group">
				<br />'.$errorlevel_A.'
				<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="ulevelx" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Office" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($metUser['level'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
				</div>
				</div>';
		}
		echo '</div>
			</div>
		</div>
</div>
<!--Type User Adminsitrator-->

<!--Type User Client-->
<div class="_C_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
		<div class=" col-sm-10">
			<div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">User Client</h3></div>
		<div class="panel-body">
		<div class="form-group">';
		$typeuser = $errortipe.'<div class="form-group">
		<label class="col-sm-2 control-label">Type </label>
		<div class="col-sm-10">
		<span class="radio custom-radio custom-radio-primary">
		            <input type="radio"'.pilih($metUser['tipe'], "Bank").' name="tipe" id="customradio3" value="Bank"><label for="customradio3">&nbsp;&nbsp;Bank&nbsp;&nbsp;</label>
					<input type="radio"'.pilih($metUser['tipe'], "Dokter").' name="tipe" id="customradio6" value="Dokter"><label for="customradio6">&nbsp;&nbsp;Dokter&nbsp;&nbsp;</label>
					<input type="radio"'.pilih($metUser['tipe'], "Direksi").' name="tipe" id="customradio7" value="Direksi"><label for="customradio7">&nbsp;&nbsp;Direksi &nbsp;&nbsp;</label>
					<input type="radio"'.pilih($metUser['tipe'], "Kadiv").' name="tipe" id="customradio8" value="Kadiv"><label for="customradio8">&nbsp;&nbsp;Kadiv &nbsp;&nbsp;</label>
		            <input type="radio"'.pilih($metUser['tipe'], "Appraisal").' name="tipe" id="customradio5" value="Appraisal"><label for="customradio5">&nbsp;&nbsp;Appraisal &nbsp;&nbsp;</label>
		</span>
		</div>';
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
					<div class="col-sm-10">
					<select name="coClient" class="form-control" id="coClient" onChange="UserPartner(this);"><option value="">Select Partner</option></select>
					</div>
				</div>
				<div class="form-group">
				<label class="col-lg-2 control-label">Product</label>
					<div class="col-lg-10"><select name="coProduk" class="form-control" id="coProduk" onChange="UserProduk(this);"><option value="">Select Product</option></select></div>
				</div>
				<div class="form-group">
				  <label class="col-lg-2 control-label">Regional </label>
				  <div class="col-lg-10"><select name="coRegional" class="form-control" onChange="UserRegional(this);" id="coRegional" ><option value="">Select Regional</option></select></div>
			    </div>
				<div class="form-group">
				  <label class="col-lg-2 control-label">Branch </label>
				  <div class="col-lg-10"><select name="coCabang" class="form-control" id="coCabang"><option value="">Select Branch</option></select></div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label">Level </label>
					<div class="col-sm-10">
					<select name="ulevelclient" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($metUser['level'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
					</div>
				</div>

				'.$typeuser.'
				</div>';
		}else{
$userbroker = mysql_fetch_array($database->doQuery('SELECT
useraccess.id,
ajkcobroker.`name` AS cbroker,
ajkclient.`name` AS cperusahaan,
ajkregional.`name` AS cregional,
ajkcabang.`name` AS ccabang
FROM
useraccess
INNER JOIN ajkcobroker ON useraccess.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON useraccess.idclient = ajkclient.id
INNER JOIN ajkregional ON useraccess.regional = ajkregional.er
INNER JOIN ajkcabang ON useraccess.branch = ajkcabang.er
WHERE
useraccess.id = "'.$metUser['id'].'"'));
			echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker &nbsp; </strong></a></p></div><div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$userbroker['cbroker'].'</strong></a></p></div>
				  <div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Partner &nbsp; </strong></a></p></div><div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$userbroker['cperusahaan'].'</strong></a></p></div>
				  <div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Regional &nbsp; </strong></a></p></div><div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$userbroker['cregional'].'</strong></a></p></div>
				  <div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Cabang &nbsp; </strong></a></p></div><div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$userbroker['ccabang'].'</strong></a></p></div>
				  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">
				'.$errorlevel.'
				<div class="form-group">
					<label class="col-sm-2 control-label">Level </label>
					<div class="col-sm-10">
					<select name="ulevelclient" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($metUser['level'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
				</div>
				</div>

				'.$typeuser.'
				</div>';
		}
		echo '</div>
				</div>
    	</div>
		</div>
		</div>
<!--Type User Client-->

<!--Type User Third-->
<div class="_T_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
		<div class=" col-sm-10">
			<div class="panel panel-warning">
        <div class="panel-heading"><h3 class="panel-title">User Insurance</h3></div>
				<div class="panel-body">';
		if ($q['idbroker'] == NULL) {
			echo ''.$errorbroker.'<div class="form-group">
		  <label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
			<div class="col-sm-10">
		  	<select name="coBrokers" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['coBrokers'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
			echo '</select>
					</div>
					</div>
					'.$errorlevel.'
					<div class="form-group">
					<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
						<div class="col-sm-10">
						<select name="uleveltab" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['uleveltab'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
								</div>
							</div>
					</div>';
		}else{
			$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
			echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker Name &nbsp; </strong></a></p></div>
					  <div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$_broker['name'].'</strong></a></p></div>
					  <input type="hidden" name="coBrokers" value="'.$q['idbroker'].'">
						<div class="form-group">'.$errorasuransi.'
						<label class="col-sm-2 control-label">Insurance <span class="text-danger">*</span></label>
							<div class="col-sm-10">
							<select name="uAsuransi" class="form-control"><option value="">Select Insurance</option>';
			$metAsuransi = $database->doQuery('SELECT * FROM ajkinsurance WHERE idc="'.$_broker['id'].'" AND del IS NULL ORDER BY name ASC');
			while ($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
				echo '<option value="'.$metAsuransi_['id'].'"'._selected($metUser['idas'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
			}
			echo '</select>
				</div>
						</div>
						<div class="form-group">'.$errorlevelthird.'
						<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
							<div class="col-sm-10">
							<select name="ulevelthird" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE type="Third" AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($metUser['level'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
							</div>
						</div>';
		}
		echo '</div>
			</div>
		</div>
</div>
<!--Type User Tablet-->
		';

$_dob = explode("-", $metUser['dob']);
echo '<div class="form-group">
		  	<label class="control-label col-sm-2">Name <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row mb5"><div class="col-sm-6"><input name="fname" value="'.$metUser['firstname'].'" type="text" class="form-control" placeholder="Firstname" required></div>
								<div class="col-sm-6"><input name="lname" value="'.$metUser['lastname'].'" type="text" class="form-control" placeholder="Lastname"></div>
				</div>
        </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Gender <span class="text-danger">*</span></label>
    	<div class="col-sm-10">
        	<span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($metUser['gender'], "L").' name="gender" id="customradio1" value="L" required><label for="customradio1">&nbsp;&nbsp;Male&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($metUser['gender'], "P").' name="gender" id="customradio2" value="P" required><label for="customradio2">&nbsp;&nbsp;Female</label>
            </span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Date of Birth <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row">
            	<div class="col-md-12"><input type="text" name="dob" class="form-control" id="datepicker4" value="'.$_dob['1'].'/'.$_dob['2'].'/'.$_dob['0'].'" placeholder="Date of birth" required/></div>
            </div>
        </div>
    </div>
		<div class="form-group">
		  	<label class="control-label col-sm-2">Username <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="uname" value="'.$metUser['username'].'" type="text" class="form-control" placeholder="Username" required></div></div>
        '.$errorusername.'
			</div>
    </div>
    <div class="form-group">
		  	<label class="control-label col-sm-2">Password <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="pname" value="'.$metUser['mamet'].'" type="password" class="form-control" placeholder="Password" required></div></div>
        </div>
    </div>';

		echo '<div class="form-group">
			<label class="control-label col-sm-2">Email <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="email" type="text" class="form-control" data-parsley-trigger="change" data-parsley-type="email" value="'.$metUser['email'].'" required></div></div>
			'.$erroremail.'
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-2 control-label">Photo <span class="text-danger">*</span></label>
		    <div class="col-sm-10">';
		if ($metComp['logothumb']=="") {
			echo '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
		}else{
			echo '<div class="media-object"><img src="../'.$PathPhoto.''.$metComp['logothumb'].'" alt="" class="img-circle" width="150"></div>';
		}
		echo '<input type="file" name="fileImage" accept="image/*" required></div>
		</div>
		<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
		</form>
</div>
</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

	case "newUser":
echo '<div class="page-header-section"><h2 class="title semibold">User Access</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=uaccess">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="saveme") {
if ($_REQUEST['modeuser']=="_A_") {
//	echo $_REQUEST['modeuser'].'<br />';
	if ($q['idbroker'] == NULL) {
		if (!$_REQUEST['coBrokers']) {	$errorbroker = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama broker.</div>';	}
		if (!$_REQUEST['ulevel']) 	{	$errorlevel_A = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih level user.</div>';	}
	}else{
		if (!$_REQUEST['ulevelx']) 	{	$errorlevel_A = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih level user.</div>';	}
	}
//	echo $_REQUEST['coBroker'].'<br />';
//	echo $_REQUEST['ulevel'];
}elseif ($_REQUEST['modeuser']=="_C_") {
//	echo $_REQUEST['modeuser'].'<br />';
	if (!$_REQUEST['coClient']) 	{	$errorclient = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama perusahaan.</div>';	}
	if (!$_REQUEST['coProduk']) 	{	$errorproduk = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama produk.</div>';	}
	if (!$_REQUEST['coRegional']) 	{	$errorregional = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama regional.</div>';	}
	if (!$_REQUEST['coCabang']) 	{	$errorcabang = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama cabang.</div>';	}
	if (!$_REQUEST['ulevelclient']) {	$errorlevel = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih level user.</div>';	}
	if (!$_REQUEST['tipe']) 		{	$errortipe = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih tipe user.</div>';	}
//	echo $_REQUEST['coClient'].'<br />';
	$typeuserprod = explode("-", $_REQUEST['coProduk']);
//	echo $typeuserprod[0].'<br />';
//	echo $_REQUEST['coRegional'].'<br />';
//	echo $_REQUEST['coCabang'].'<br />';
}else{
	if (!$_REQUEST['uAsuransi']) 	{	$errorasuransi = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih nama broker.</div>';	}
	if (!$_REQUEST['ulevelthird']) 	{	$errorlevelthird = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Silahkan pilih level user.</div>';	}
}

//Checkusername
$cekUname = mysql_fetch_array($database->doQuery('SELECT id, username FROM useraccess WHERE username="'.$_REQUEST['uname'].'"'));
if ($cekUname['id']) {	$errorusername = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Username sudah digunakan.</div>';	}

//Checkemail
$cekUemail = mysql_fetch_array($database->doQuery('SELECT id, email FROM useraccess WHERE email="'.$_REQUEST['email'].'"'));
if ($cekUemail['id']) {	$erroremail = '<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Email sudah digunakan.</div>';	}
//Checkemail

if ($errorbroker OR $errorlevel OR $errorasuransi OR $errorlevelthird OR $errorclient OR $errorproduk OR $errorregional OR $errorcabang OR $errortipe OR $errorusername OR $erroremail) {

}else{
//RULES USER AKSES
echo '<br />';

	$nama_file =  strtolower(strtoupper($_REQUEST['fileImage']).$_REQUEST['uname'].'_'.$_FILES['fileImage']['name']);
	$nama_file_thumb =  strtolower("thumb_".strtoupper($_REQUEST['fileImage']).$_REQUEST['uname'].'_'.$_FILES['fileImage']['name']);
	metImage($nama_file);
if ($_REQUEST['modeuser']=="_A_") {
	if ($q['idbroker'] == NULL) {
	if ($_REQUEST['tipe']=="") {	$setidclient = 'tipe="0",';	}else{	$setidclient = 'tipe="'.$_REQUEST['tipe'].'",';	}
	$metGetUser = 'idbroker ="'.$_REQUEST['coBrokers'].'",
				   level ="'.$_REQUEST['ulevel'].'",
				  '.$setidclient.'';
	}else{
	if ($_REQUEST['tipe']=="") {	$setidclient = 'tipe="0",';	}else{	$setidclient = 'tipe="'.$_REQUEST['tipe'].'",';	}
	$metGetUser = 'idbroker ="'.$_REQUEST['coBrokers'].'",
				   level ="'.$_REQUEST['ulevelx'].'",
				   '.$setidclient.'';
	}
}elseif ($_REQUEST['modeuser']=="_C_") {
	$metGetUser = 'idbroker ="'.$_REQUEST['coBroker'].'",
				   level ="'.$_REQUEST['ulevelclient'].'",
				   idclient="'.$_REQUEST['coClient'].'",
				   tipe="'.$_REQUEST['tipe'].'",
				   regional="'.$_REQUEST['coRegional'].'",
				   branch="'.$_REQUEST['coCabang'].'",
				   ';
}elseif ($_REQUEST['modeuser']=="_T_") {
	$metGetUser = 'idbroker ="'.$_REQUEST['coBrokers'].'",
				   idas ="'.$_REQUEST['uAsuransi'].'",
				   level ="'.$_REQUEST['ulevelthird'].'",
				   tipe ="Insurance",';
}else{

}
	$metUser = $database->doQuery('INSERT INTO useraccess SET username="'.$_REQUEST['uname'].'",
															  passw="'.md5($_REQUEST['pname']).'",
															  mamet="'.$_REQUEST['pname'].'",
															  '.$metGetUser.'
															  firstname="'.$_REQUEST['fname'].'",
															  lastname="'.$_REQUEST['lname'].'",
															  gender="'.$_REQUEST['gender'].'",
															  dob="'._convertDateEng2($_REQUEST['dob']).'",
															  email="'.$_REQUEST['email'].'",
															  aktif="Y",
															  photo="'.$nama_file.'",
															  photothumb="'.$nama_file_thumb.'",
															  input_by="'.$q['id'].'",
															  input_time="'.$futgl.'"
															  ');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=uaccess">
			  <div class="col-md-12"><div class="alert alert-dismissable alert-success"><strong>Success!</strong> New user access..</div></div>';
}
//RULES USER AKSES
}

$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="row">
	'.$metnotif.'<br />'.$metnotifCekUser.'
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">New Form User Access</h3></div>
		<div class="panel-body">';
echo '<div class="form-group">
		<label class="col-sm-2 control-label">Useraccess</label>
        <div class="col-sm-10">
        	<span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($_REQUEST['modeuser'], "_A_").' name="modeuser" id="customradio19" value="_A_" required><label for="customradio19">&nbsp; Broker &nbsp;&nbsp;</label>
			<input type="radio"'.pilih($_REQUEST['modeuser'], "_C_").' name="modeuser" id="customradio18" value="_C_" required><label for="customradio18">&nbsp; Client &nbsp;&nbsp;</label>
			<input type="radio"'.pilih($_REQUEST['modeuser'], "_T_").' name="modeuser" id="customradioga" value="_T_" required><label for="customradioga">&nbsp; Insurance &nbsp;&nbsp;</label>
            </span>
		</div>
	</div>

<!--Type User Adminsitrator-->
<div class="_A_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
	<div class=" col-sm-10">
		<div class="panel panel-success">
        <div class="panel-heading"><h3 class="panel-title">User Administrator</h3></div>
			<div class="panel-body">';
if ($q['idbroker'] == NULL) {
echo ''.$errorbroker.'<div class="form-group">
	  <label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
		<div class="col-sm-10">
	  	<select name="coBrokers" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['coBrokers'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
echo '</select>
		</div>
	</div>
	'.$errorlevel_A.'
	<div class="form-group">
    <label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
	   	<div class="col-sm-10">
    		<select name="ulevel" class="form-control"><option value="">Select Level</option>';
	$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Office" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
	while ($metLevel_ = mysql_fetch_array($metLevel)) {
		echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevel'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
	}
echo '</select>
		</div>
	</div>';
}else{
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker Name &nbsp; </strong></a></p></div>
	  <div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$_broker['name'].'</strong></a></p></div>
	  <input type="hidden" name="coBrokers" value="'.$q['idbroker'].'">
	<div class="form-group">
	<br />'.$errorlevel_A.'
	<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
	<div class="col-sm-10">
	<select name="ulevelx" class="form-control"><option value="">Select Level</option>';
		$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Office" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
		while ($metLevel_ = mysql_fetch_array($metLevel)) {
			echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevelx'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
		}
		echo '</select>
	</div>
	</div>';
}
	echo '</div>
		</div>
	</div>
</div>
<!--Type User Adminsitrator-->
<!--Type User Client-->
<div class="_C_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
	<div class=" col-sm-10">
		<div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">User Client</h3></div>
	<div class="panel-body">
	<div class="form-group">';
$typeuser = $errortipe.'<div class="form-group">
	<label class="col-sm-2 control-label">Type </label>
	<div class="col-sm-10">
	<span class="radio custom-radio custom-radio-primary">
	            <input type="radio"'.pilih($_REQUEST['tipe'], "Bank").' name="tipe" id="customradio3" value="Bank"><label for="customradio3">&nbsp;&nbsp;Bank&nbsp;&nbsp;</label>
				<input type="radio"'.pilih($_REQUEST['tipe'], "Dokter").' name="tipe" id="customradio6" value="Dokter"><label for="customradio6">&nbsp;&nbsp;Dokter&nbsp;&nbsp;</label>
				<input type="radio"'.pilih($_REQUEST['tipe'], "Direksi").' name="tipe" id="customradio7" value="Direksi"><label for="customradio7">&nbsp;&nbsp;Direksi &nbsp;&nbsp;</label>
				<input type="radio"'.pilih($_REQUEST['tipe'], "Kadiv").' name="tipe" id="customradio8" value="Kadiv"><label for="customradio8">&nbsp;&nbsp;Kadiv &nbsp;&nbsp;</label>
	            <input type="radio"'.pilih($_REQUEST['tipe'], "Appraisal").' name="tipe" id="customradio5" value="Appraisal"><label for="customradio5">&nbsp;&nbsp;Appraisal &nbsp;&nbsp;</label>
	</span>
	</div>';
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
		<div class="col-sm-10">
		<select name="coClient" class="form-control" id="coClient" onChange="UserPartner(this);"><option value="">Select Partner</option></select>
		</div>
	</div>
	<div class="form-group">
	<label class="col-lg-2 control-label">Product</label>
		<div class="col-lg-10"><select name="coProduk" class="form-control" id="coProduk" onChange="UserProduk(this);"><option value="">Select Product</option></select></div>
	</div>
	<div class="form-group">
	  <label class="col-lg-2 control-label">Regional </label>
	  <div class="col-lg-10"><select name="coRegional" class="form-control" onChange="UserRegional(this);" id="coRegional" ><option value="">Select Regional</option></select></div>
    </div>
	<div class="form-group">
	  <label class="col-lg-2 control-label">Branch </label>
	  <div class="col-lg-10"><select name="coCabang" class="form-control" id="coCabang"><option value="">Select Branch</option></select></div>
	</div>

	<div class="form-group">
		<label class="col-sm-2 control-label">Level </label>
		<div class="col-sm-10">
		<select name="ulevelclient" class="form-control"><option value="">Select Level</option>';
		$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
		while ($metLevel_ = mysql_fetch_array($metLevel)) {
			echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevelclient'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
		}
		echo '</select>
		</div>
	</div>

	'.$typeuser.'
	</div>';
}else{
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker Name &nbsp; </strong></a></p></div>
	  <div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$_broker['name'].'</strong></a></p></div>
	  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">
	  <br /><br />'.$errorclient.'
	  <div class="form-group">
	  <label class="col-sm-2 control-label">Partner </label>
	  <div class="col-sm-10">
	  <select name="coClient" class="form-control" onChange="UserPartner(this);"><option value="">Select Partner</option>';
$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['coClient'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
}
echo '</select>
	  </div>
	  </div>
	  '.$errorproduk.'
	  <div class="form-group">
	  <label class="col-lg-2 control-label">Product </label>
	  <div class="col-lg-10"><select name="coProduk" class="form-control" onChange="UserProduk(this);" id="coProduk" ><option value="">Select Product</option></select></div>
	  </div>
	'.$errorregional.'
	  <div class="form-group">
	  <label class="col-lg-2 control-label">Regional </label>
	  <div class="col-lg-10"><select name="coRegional" class="form-control" onChange="UserRegional(this);" id="coRegional" ><option value="">Select Regional</option></select></div>
	  </div>
	'.$errorcabang.'
	  <div class="form-group">
	  <label class="col-lg-2 control-label">Branch </label>
	  <div class="col-lg-10"><select name="coCabang" class="form-control" id="coCabang"><option value="">Select Branch</option></select></div>
	  </div>
	'.$errorlevel.'
	<div class="form-group">
		<label class="col-sm-2 control-label">Level </label>
		<div class="col-sm-10">
		<select name="ulevelclient" class="form-control"><option value="">Select Level</option>';
	$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
	while ($metLevel_ = mysql_fetch_array($metLevel)) {
		echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevelclient'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
	}
	echo '</select>
	</div>
	</div>

	'.$typeuser.'
	</div>';
}
	echo '</div>
			</div>
    	</div>
	</div>
	</div>
<!--Type User Client-->
<!--Type User Tablet-->
<div class="_T_ box">
<label class="col-sm-2 control-label">&nbsp;</label>
	<div class=" col-sm-10">
		<div class="panel panel-warning">
        <div class="panel-heading"><h3 class="panel-title">User Insurance</h3></div>
			<div class="panel-body">';
if ($q['idbroker'] == NULL) {
echo ''.$errorbroker.'<div class="form-group">
	  <label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
		<div class="col-sm-10">
	  	<select name="coBrokers" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['coBrokers'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	}
echo '</select>
		</div>
		</div>
		'.$errorlevel.'
		<div class="form-group">
		<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
			<div class="col-sm-10">
			<select name="uleveltab" class="form-control"><option value="">Select Level</option>';
			$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE (type="Client" OR type IS NULL) AND aktif="Y" ORDER BY nama ASC');
			while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['uleveltab'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
			}
			echo '</select>
					</div>
				</div>
		</div>';
}else{
$_broker = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
echo '<div class="col-sm-2 text-right"><p class="meta nm"><a href="javascript:void(0);"><strong>Broker Name &nbsp; </strong></a></p></div>
		  <div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);"><strong>'.$_broker['name'].'</strong></a></p></div>
		  <input type="hidden" name="coBrokers" value="'.$q['idbroker'].'">
			<div class="form-group">'.$errorasuransi.'
			<label class="col-sm-2 control-label">Insurance <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="uAsuransi" class="form-control"><option value="">Select Insurance</option>';
	$metAsuransi = $database->doQuery('SELECT * FROM ajkinsurance WHERE idc="'.$_broker['id'].'" AND del IS NULL ORDER BY name ASC');
	while ($metAsuransi_ = mysql_fetch_array($metAsuransi)) {
		echo '<option value="'.$metAsuransi_['id'].'"'._selected($_REQUEST['uAsuransi'], $metAsuransi_['id']).'>'.$metAsuransi_['name'].'</option>';
	}
	echo '</select>
	</div>
			</div>
			<div class="form-group">'.$errorlevelthird.'
			<label class="col-sm-2 control-label">Level <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="ulevelthird" class="form-control"><option value="">Select Level</option>';
				$metLevel = $database->doQuery('SELECT * FROM leveluser WHERE type="Third" AND aktif="Y" ORDER BY nama ASC');
				while ($metLevel_ = mysql_fetch_array($metLevel)) {
				echo '<option value="'.$metLevel_['er'].'"'._selected($_REQUEST['ulevelthird'], $metLevel_['er']).'>'.$metLevel_['nama'].'</option>';
				}
		echo '</select>
				</div>
			</div>';
}
	echo '</div>
		</div>
	</div>
</div>
<!--Type User Tablet-->
	';

echo '<div class="form-group">
	  	<label class="control-label col-sm-2">Name <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row mb5"><div class="col-sm-6"><input name="fname" value="'.$_REQUEST['fname'].'" type="text" class="form-control" placeholder="Firstname" required></div>
								 <div class="col-sm-6"><input name="lname" value="'.$_REQUEST['lname'].'" type="text" class="form-control" placeholder="Lastname"></div>
			</div>
        </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label">Gender <span class="text-danger">*</span></label>
    	<div class="col-sm-10">
        	<span class="radio custom-radio custom-radio-primary">
            <input type="radio"'.pilih($_REQUEST['gender'], "L").' name="gender" id="customradio1" value="L" required><label for="customradio1">&nbsp;&nbsp;Male&nbsp;&nbsp;</label>
            <input type="radio"'.pilih($_REQUEST['gender'], "P").' name="gender" id="customradio2" value="P" required><label for="customradio2">&nbsp;&nbsp;Female</label>
            </span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Date of Birth <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        	<div class="row">
            	<div class="col-md-12"><input type="text" name="dob" class="form-control" id="datepicker4" value="'.$_REQUEST['dob'].'" placeholder="Date of birth" required/></div>
            </div>
        </div>
    </div>
	<div class="form-group">
	  	<label class="control-label col-sm-2">Username <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="uname" value="'.$_REQUEST['uname'].'" type="text" class="form-control" placeholder="Username" required></div></div>
        '.$errorusername.'
		</div>
    </div>
    <div class="form-group">
	  	<label class="control-label col-sm-2">Password <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="pname" value="'.$_REQUEST['pname'].'" type="password" class="form-control" placeholder="Password" required></div></div>
        </div>
    </div>';

echo '<div class="form-group">
		<label class="control-label col-sm-2">Email <span class="text-danger">*</span></label>
        <div class="col-sm-10">
        <div class="row mb5"><div class="col-sm-12"><input name="email" type="text" class="form-control" data-parsley-trigger="change" data-parsley-type="email" value="'.$_REQUEST['email'].'" required></div></div>
		'.$erroremail.'
		</div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Photo <span class="text-danger">*</span></label>
	    <div class="col-sm-10"><input type="file" name="fileImage" accept="image/*" required></div>
	</div>
	<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
	</form>
</div>
</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	default:
echo '<div class="page-header-section"><h2 class="title semibold">User Access</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=uaccess&el=newUser">'.BTN_NEW.'</a></div>
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
        <th width="1%">Photo</th>
        <th width="10%">Name</th>
        <th width="7%">Username</th>
        <th width="10%">Email</th>
        <th width="20%">Level</th>
        <th width="10%">Branch</th>
        <th width="10%">Option</th>
        </tr>
    </thead>
    <tbody>';
$metCOB = $database->doQuery('SELECT
useraccess.id,
useraccess.username,
ajkcobroker.`name` AS broker,
ajkclient.`name` AS client,
ajkcabang.`name` AS cabang,
useraccess.email,
useraccess.`level`,
useraccess.photo,
leveluser.nama AS leveluser,
CONCAT(useraccess.firstname," ",useraccess.lastname) AS namauser
FROM
useraccess
LEFT JOIN ajkcobroker ON useraccess.idbroker = ajkcobroker.id
LEFT JOIN ajkclient ON useraccess.idclient = ajkclient.id
LEFT JOIN ajkcabang ON useraccess.branch = ajkcabang.er
INNER JOIN leveluser ON useraccess.`level` = leveluser.er
WHERE useraccess.del IS NULL '.$q___User.'
ORDER BY useraccess.idclient ASC');
while ($metCOB_ = mysql_fetch_array($metCOB)) {
	if ($metCOB_['photo']=="") {
		$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
	}else{
		$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.''.$metCOB_['photo'].'" alt="" class="img-circle"></div>';
	}
if ($metCOB_['client']=="") {
	$_metComp = $metCOB_['broker'];
}else{
	$_metComp = $metCOB_['client'];
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$_metComp.'</td>
   	<td>'.$logoCOB.'</td>
   	<td><a href="ajk.php?re=cob&co=cobview&cid='.$thisEncrypter->encode($metCOB_['id']).'">'.$metCOB_['namauser'].'</a></td>
   	<td>'.$metCOB_['username'].'</td>
   	<td>'.$metCOB_['email'].'</td>
   	<td>'.$metCOB_['leveluser'].'</td>
   	<td>'.$metCOB_['cabang'].'</td>
   	<td align="center"><a href="ajk.php?re=uaccess&el=euseracc&cid='.$thisEncrypter->encode($metCOB_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Company"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Usernname"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Level"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
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

<script type="text/javascript">
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        if($(this).attr("value")=="_A_")	{	$(".box").not("._A_").hide();	$("._A_").show();	}
        if($(this).attr("value")=="_C_")	{	$(".box").not("._C_").hide();	$("._C_").show();	}
        if($(this).attr("value")=="_T_")	{	$(".box").not("._T_").hide();	$("._T_").show();	}
    });
});
</script>
<style type="text/css">
.box{	display: none;	}
</style>