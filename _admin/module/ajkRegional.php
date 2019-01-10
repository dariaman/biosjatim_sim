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
switch ($_REQUEST['el']) {
	case "ncabang":
$metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM ajkcabang WHERE idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'" AND idreg="'.$thisEncrypter->decode($_REQUEST['rid']).'" AND idarea="'.$thisEncrypter->decode($_REQUEST['aid']).'"'));
echo '<div class="page-header-section"><h2 class="title semibold">Branch</h2></div>
      	<div class="page-header-section">
       <div class="toolbar"><a href="ajk.php?re=regional&el=narea&cid='.$_REQUEST['cid'].'&rid='.$_REQUEST['rid'].'">'.BTN_BACK.'</a> &nbsp;
							<a href="ajk.php?re=regional&el=ncabang&_rc=new&cid='.$_REQUEST['cid'].'&rid='.$_REQUEST['rid'].'&aid='.$_REQUEST['aid'].'">'.BTN_NEWBRANCH.'</a></div>
	</div>
    </div>';
if ($_REQUEST['_rc']=="new") {
	if ($_REQUEST['met']=="newBranch") {
	$metUpd = $database->doQuery('INSERT INTO ajkcabang SET idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'",
														  	idreg="'.$thisEncrypter->decode($_REQUEST['rid']).'",
														  	idarea="'.$thisEncrypter->decode($_REQUEST['aid']).'",
														  	name="'.strtoupper($_REQUEST['branchname']).'",
														  	inputby="'.$q['id'].'",
														  	inputtime="'.$futgl.'"');
	header("location:ajk.php?re=regional&el=ncabang&cid=".$_REQUEST['cid']."&rid=".$_REQUEST['rid']."&aid=".$_REQUEST['aid']."");
}
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<input type="hidden" name="cid" value="'.$_REQUEST['cid'].'">
			<input type="hidden" name="rid" value="'.$_REQUEST['rid'].'">
			<input type="hidden" name="aid" value="'.$_REQUEST['aid'].'">
			<div class="panel-heading"><h3 class="panel-title">New Branch</h3></div>
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-1 control-label">Branch <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input type="text" name="branchname" value="'.$_REQUEST['name'].'" class="form-control" placeholder="Branch" required></div>
					</div>
				</div>
				<div class="panel-footer"><input type="hidden" name="met" value="newBranch">'.BTN_SUBMIT.'</div>
			</div>
			</form>
	</div>';
}

if ($_REQUEST['ell']=="ecbg") {
	$metCabang = mysql_fetch_array($database->doQuery('SELECT * FROM ajkcabang WHERE er="'.$thisEncrypter->decode($_REQUEST['ccid']).'"'));
	if ($_REQUEST['met']=="savearea") {
		$metUpd = $database->doQuery('UPDATE ajkcabang SET name="'.strtoupper($_REQUEST['cabangname']).'" WHERE er="'.$thisEncrypter->decode($_REQUEST['ccid']).'"');
		header("location:ajk.php?re=regional&el=ncabang&cid=".$_REQUEST['cid']."&rid=".$_REQUEST['rid']."&aid=".$_REQUEST['aid']."");
	}
			echo '<div class="row">
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Edit Branch</h3></div>
			<input type="hidden" name="cid" value="'.$_REQUEST['cid'].'">
			<input type="hidden" name="rid" value="'.$_REQUEST['rid'].'">
			<input type="hidden" name="aid" value="'.$_REQUEST['aid'].'">
			<input type="hidden" name="ccid" value="'.$_REQUEST['ccid'].'">
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-1 control-label">Branch <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input type="text" name="cabangname" value="'.$metCabang['name'].'" class="form-control" placeholder="Regional" required></div>
					</div>
				</div>
				<div class="panel-footer"><input type="hidden" name="met" value="savearea">'.BTN_SUBMIT.'</div>
			</div>
		</form>
	</div>';
}
$metR_ = mysql_fetch_array($database->doQuery('SELECT ajkregional.er, ajkregional.idclient, ajkregional.`name` AS regional, ajkarea.`name` AS area
											   FROM ajkregional
											   INNER JOIN ajkarea ON ajkregional.er = ajkarea.idreg
											   WHERE ajkregional.er = "'.$thisEncrypter->decode($_REQUEST['rid']).'" AND ajkarea.er = "'.$thisEncrypter->decode($_REQUEST['aid']).'"'));
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel-footer"><strong>Regional '.$metR_['regional'].'</strong></div>
		<div class="panel-footer"><strong>Area '.$metR_['area'].'</strong></div>
		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th class="text-center" width="1%">No</th>
			<th class="text-center">Branch</th>
			<th class="text-center" width="1%">Option</th>
		</tr>
		</thead>
		<tbody>';
$metRegAreaCbg = $database->doQuery('SELECT * FROM ajkcabang WHERE ajkcabang.idclient = "'.$thisEncrypter->decode($_REQUEST['cid']).'" AND ajkcabang.idreg = "'.$thisEncrypter->decode($_REQUEST['rid']).'" AND ajkcabang.idarea = "'.$thisEncrypter->decode($_REQUEST['aid']).'" ');
while ($metRegAreaCbg_ = mysql_fetch_array($metRegAreaCbg)) {
echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$metRegAreaCbg_['name'].'</td>
		  <td align="center"><a href="ajk.php?re=regional&el=ncabang&ell=ecbg&cid='.$thisEncrypter->encode($metRegAreaCbg_['idclient']).'&rid='.$thisEncrypter->encode($metRegAreaCbg_['idreg']).'&aid='.$thisEncrypter->encode($metRegAreaCbg_['idarea']).'&ccid='.$thisEncrypter->encode($metRegAreaCbg_['er']).'">'.BTN_EDIT.'</a></td>
		</tr>';
}
echo '</tbody>
		<tfoot>
		<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		</tr>
		</tfoot></table>
		</div>';
		;
		break;

	case "narea":
$metReg = mysql_fetch_array($database->doQuery('SELECT * FROM ajkregional WHERE idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'" AND er="'.$thisEncrypter->decode($_REQUEST['rid']).'"'));
$metAreaReg = mysql_fetch_array($database->doQuery('SELECT * FROM ajkarea WHERE idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'" AND idreg="'.$thisEncrypter->decode($_REQUEST['rid']).'"'));
echo '<div class="page-header-section"><h2 class="title semibold">Area</h2></div>
      	<div class="page-header-section">
       <div class="toolbar"><a href="ajk.php?re=regional&el=nregional&cid='.$thisEncrypter->encode($metReg['idclient']).'">'.BTN_BACK.'</a> &nbsp;
							<a href="ajk.php?re=regional&el=narea&_ra=new&cid='.$thisEncrypter->encode($metReg['idclient']).'&rid='.$thisEncrypter->encode($metReg['er']).'">'.BTN_NEWAREA.'</a></div>
	</div>
    </div>';
if ($_REQUEST['_ra']=="new") {
	if ($_REQUEST['met']=="newarea") {
		$metUpd = $database->doQuery('INSERT INTO ajkarea SET idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'",
															  idreg="'.$thisEncrypter->decode($_REQUEST['rid']).'",
															  name="'.strtoupper($_REQUEST['areaname']).'",
															  inputby="'.$q['id'].'",
															  inputtime="'.$futgl.'"');
		header("location:ajk.php?re=regional&el=narea&cid=".$_REQUEST['cid']."&rid=".$_REQUEST['rid']."");
}
echo '<div class="row">
		<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<input type="hidden" name="cid" value="'.$_REQUEST['cid'].'">
			<input type="hidden" name="rid" value="'.$_REQUEST['rid'].'">
			<div class="panel-heading"><h3 class="panel-title">New Area</h3></div>
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-1 control-label">Area <span class="text-danger">*</span></label>
					<div class="col-sm-11"><input type="text" name="areaname" value="'.$_REQUEST['areaname'].'" class="form-control" placeholder="Area" required></div>
					</div>
				</div>
				<div class="panel-footer"><input type="hidden" name="met" value="newarea">'.BTN_SUBMIT.'</div>
			</div>
			</form>
</div>';
}

if ($_REQUEST['ell']=="earea") {
	$metArea = mysql_fetch_array($database->doQuery('SELECT * FROM ajkarea WHERE er="'.$thisEncrypter->decode($_REQUEST['aid']).'" AND idclient="'.$thisEncrypter->decode($_REQUEST['cid']).'" AND idreg="'.$thisEncrypter->decode($_REQUEST['rid']).'"'));
	if ($_REQUEST['met']=="savearea") {
		$metUpd = $database->doQuery('UPDATE ajkarea SET name="'.strtoupper($_REQUEST['areaname']).'" WHERE er="'.$thisEncrypter->decode($_REQUEST['aid']).'"');
		header("location:ajk.php?re=regional&el=narea&cid=".$_REQUEST['cid']."&rid=".$_REQUEST['rid']."");
	}
echo '<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">Edit Area</h3></div>
		<input type="hidden" name="cid" value="'.$_REQUEST['cid'].'">
		<input type="hidden" name="rid" value="'.$_REQUEST['rid'].'">
		<input type="hidden" name="aid" value="'.$_REQUEST['aid'].'">
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-1 control-label">Area <span class="text-danger">*</span></label>
				<div class="col-sm-11"><input type="text" name="areaname" value="'.$metArea['name'].'" class="form-control" placeholder="Regional" required></div>
				</div>
			</div>
			<div class="panel-footer"><input type="hidden" name="met" value="savearea">'.BTN_SUBMIT.'</div>
		</div>
	</form>
</div>';
}

$metR_ = mysql_fetch_array($database->doQuery('SELECT ajkregional.er, ajkregional.idclient, ajkregional.`name`
											   FROM ajkregional
											   WHERE ajkregional.er = "'.$thisEncrypter->decode($_REQUEST['rid']).'" AND
											   		 ajkregional.idclient = "'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
echo '<div class="row">
	<div class="col-md-12">
		<div class="panel-footer"><strong>Regional '.$metR_['name'].'</strong></div>
		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th class="text-center" width="1%">No</th>
			<th class="text-center">Area</th>
			<th class="text-center" width="10%">Branch</th>
			<th class="text-center" width="1%">Option</th>
		</tr>
		</thead>
		<tbody>';
$metRegArea = $database->doQuery('SELECT ajkarea.er, ajkarea.idclient, ajkarea.idreg, ajkarea.`name`
								  FROM ajkarea
								  WHERE ajkarea.idclient = "'.$thisEncrypter->decode($_REQUEST['cid']).'" AND ajkarea.idreg = "'.$thisEncrypter->decode($_REQUEST['rid']).'" ');
while ($metRegArea_ = mysql_fetch_array($metRegArea)) {
	$_cabang = mysql_fetch_array($database->doQuery('SELECT COUNT(name) AS jCabang FROM ajkcabang WHERE idclient="'.$metRegArea_['idclient'].'" AND idreg="'.$metRegArea_['idreg'].'" AND idarea="'.$metRegArea_['er'].'"'));
			echo '<tr><td align="center">'.++$no.'</td>
			  <td>'.$metRegArea_['name'].'</td>
			  <td align="center"><span class="label label-inverse"><a href="ajk.php?re=regional&el=ncabang&cid='.$thisEncrypter->encode($metRegArea_['idclient']).'&rid='.$thisEncrypter->encode($metRegArea_['idreg']).'&aid='.$thisEncrypter->encode($metRegArea_['er']).'"><font color="#FFF">'.$_cabang['jCabang'].'</font></a></td>
			  <td align="center"><a href="ajk.php?re=regional&el=narea&ell=earea&cid='.$thisEncrypter->encode($metRegArea_['idclient']).'&rid='.$thisEncrypter->encode($metRegArea_['idreg']).'&aid='.$thisEncrypter->encode($metRegArea_['er']).'">'.BTN_EDIT.'</a></td>
		</tr>';
}
	echo '</tbody>
	<tfoot>
	<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="search" class="form-control" name="search_engine" placeholder="Area"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	<th><input type="hidden" class="form-control" name="search_engine"></th>
	</tr>
	</tfoot></table>
	</div>';
		;
		break;


	case "nregional":
echo '<div class="page-header-section"><h2 class="title semibold">Regional</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=regional">'.BTN_BACK.'</a> &nbsp; <a href="ajk.php?re=regional&el=nregional&_r=new&cid='.$_REQUEST['cid'].'">'.BTN_NEWREGIONAL.'</a></div>
		</div>
      </div>';
if ($_REQUEST['_r']=="new") {
//$metReg = mysql_fetch_array($database->doQuery('SELECT * FROM ajkregional WHERE er="'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
	if ($_REQUEST['met']=="newreg") {
		$metUpd = $database->doQuery('INSERT INTO ajkregional SET idclient="'.$_REQUEST['cid'].'",
																  name="'.strtoupper($_REQUEST['regionalname']).'",
																  inputby="'.$q['id'].'",
																  inputtime="'.$futgl.'"');
		header("location:ajk.php?re=regional&el=nregional&cid=".$thisEncrypter->encode($_REQUEST['cid'])."");
	}
echo '<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<input type="hidden" name="cid" value="'.$thisEncrypter->decode($_REQUEST['cid']).'">
	<div class="panel-heading"><h3 class="panel-title">New Regional</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-1 control-label">Regional <span class="text-danger">*</span></label>
			<div class="col-sm-11"><input type="text" name="regionalname" value="'.$_REQUEST['regionalname'].'" class="form-control" placeholder="Regional" required></div>
			</div>
		</div>
		<div class="panel-footer"><input type="hidden" name="met" value="newreg">'.BTN_SUBMIT.'</div>
	</div>
	</form>
</div>';
}
if ($_REQUEST['ell']=="eregional") {
$metReg = mysql_fetch_array($database->doQuery('SELECT * FROM ajkregional WHERE er="'.$thisEncrypter->decode($_REQUEST['rid']).'"'));
	if ($_REQUEST['met']=="savereg") {
		$metUpd = $database->doQuery('UPDATE ajkregional SET name="'.strtoupper($_REQUEST['regionalname']).'" WHERE er="'.$thisEncrypter->decode($_REQUEST['rid']).'"');
		header("location:ajk.php?re=regional&el=nregional&cid=".$thisEncrypter->encode($metReg['idclient'])."");
	}
echo '<div class="row">
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">Edit Regional</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-1 control-label">Regional <span class="text-danger">*</span></label>
			<div class="col-sm-11"><input type="text" name="regionalname" value="'.$metReg['name'].'" class="form-control" placeholder="Regional" required></div>
			</div>
		</div>
		<div class="panel-footer"><input type="hidden" name="met" value="savereg">'.BTN_SUBMIT.'</div>
	</div>
	</form>
</div>';
}
echo '<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead><tr><th class="text-center" width="1%">No</th>
		       <th class="text-center">Partner</th>
		       <th class="text-center" width="20%">Regional</th>
		       <th class="text-center" width="20%">Area</th>
		       <th class="text-center" width="20%">Branch</th>
		       <th class="text-center" width="1%">Option</th>
		   </tr>
	</thead>
	<tbody>';
$_regional = $database->doQuery('SELECT ajkregional.er, ajkregional.`name` AS regional, ajkclient.id, ajkclient.`name` AS partner
								 FROM ajkregional
								 INNER JOIN ajkclient ON ajkregional.idclient = ajkclient.id
								 WHERE ajkregional.idclient ="'.$thisEncrypter->decode($_REQUEST['cid']).'"');
while ($_regional_ = mysql_fetch_array($_regional)) {
$_area = mysql_fetch_array($database->doQuery('SELECT er, COUNT(name) AS jArea FROM ajkarea WHERE idclient="'.$_regional_['id'].'" AND idreg="'.$_regional_['er'].'"'));
$_cabang = mysql_fetch_array($database->doQuery('SELECT er, COUNT(name) AS jCabang FROM ajkcabang WHERE idclient="'.$_regional_['id'].'" AND idreg="'.$_regional_['er'].'"'));


echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$_regional_['partner'].'</td>
		  <td>'.$_regional_['regional'].'</td>
		  <td align="center"><span class="label label-default"><a href="ajk.php?re=regional&el=narea&cid='.$thisEncrypter->encode($_regional_['id']).'&rid='.$thisEncrypter->encode($_regional_['er']).'">'.$_area['jArea'].'</a></span></td>
		  <td align="center"><span class="label label-inverse"><a href="ajk.php?re=regional&el=ncabang&cid='.$thisEncrypter->encode($_regional_['id']).'&rid='.$thisEncrypter->encode($_regional_['er']).'&aid='.$thisEncrypter->encode($_cabang['er']).'"><font color="#FFF">'.$_cabang['jCabang'].'</font></a></span></td>
		  <td align="center"><a href="ajk.php?re=regional&el=nregional&ell=eregional&cid='.$thisEncrypter->encode($_regional_['id']).'&rid='.$thisEncrypter->encode($_regional_['er']).'">'.BTN_EDIT.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Regional"></th>
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
	default:
echo '<div class="page-header-section"><h2 class="title semibold">Regional</h2></div>
      	<div class="page-header-section">
		</div>
      </div>';

//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">

	<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	<thead><tr><th class="text-center" width="1%">No</th>
		       <th class="text-center" width="1%">Logo</th>
		       <th class="text-center">Client</th>
		       <th class="text-center" width="10%">Regional</th>
		       <th class="text-center" width="10%">Area</th>
		       <th class="text-center" width="10%">Branch</th>
		       <th class="text-center" width="1%">Option</th>
		   </tr>
	</thead>
	<tbody>';
$metRegProduk = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL  '.$q__.' ORDER BY name DESC');
while ($metRegProduk_ = mysql_fetch_array($metRegProduk)) {
$_reg = mysql_fetch_array($database->doQuery('SELECT COUNT(name) AS jRegional FROM ajkregional WHERE idclient="'.$metRegProduk_['id'].'"'));
$_area = mysql_fetch_array($database->doQuery('SELECT COUNT(name) AS jArea FROM ajkarea WHERE idclient="'.$metRegProduk_['id'].'"'));
$_cabang = mysql_fetch_array($database->doQuery('SELECT COUNT(name) AS jCabang FROM ajkcabang WHERE idclient="'.$metRegProduk_['id'].'"'));
if ($metRegProduk_['logo']=="") {
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
}else{
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.''.$metRegProduk_['logo'].'" alt="" class="img-circle"></div>';
}

echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$logoclient.'</td>
		  <td>'.$metRegProduk_['name'].'</td>
		  <td align="center"><span class="label label-primary">'.$_reg['jRegional'].'</span></td>
		  <td align="center"><span class="label label-default">'.$_area['jArea'].'</span></td>
		  <td align="center"><span class="label label-inverse">'.$_cabang['jCabang'].'</span></td>
		  <td align="center"><a href="ajk.php?re=regional&el=nregional&cid='.$thisEncrypter->encode($metRegProduk_['id']).'">'.BTN_VIEW.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
			<th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
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