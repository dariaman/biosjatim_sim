<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['op']) {
	case "new":
if ($_REQUEST['met']=="saveme") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcel WHERE fieldname="'.strtoupper($_REQUEST['fieldname']).'"'));
	if ($metExcelCek) {
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Error!</strong> field name '.$_REQUEST['fieldname'].' was insert.
                 </div>';
	}else{
	$metExcel = $database->doQuery('INSERT INTO ajkexcel SET fieldname="'.strtoupper($_REQUEST['fieldname']).'"');
	$metnotif .= '<div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> insert field name '.$_REQUEST['fieldname'].'.
                 </div>';
}
//	header('Location: ajk.php?re=exl&op=new');
}
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Excel</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=exl">'.BTN_BACK.'</a></div>
		</div>
		</div>
		<div class="row">
      	'.$metnotif.'
			<div class="col-md-12">
            <form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
            <div class="panel-heading"><h3 class="panel-title">Field Upload Excel</h3></div>
				<div class="panel-body">
            		<div class="form-group">
                	<label class="col-sm-2 control-label">Field Name Excel</label>
                	<div class="col-sm-10"><input type="text" name="fieldname" class="form-control" required></div>
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

	case "editfield":
$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcel WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"'));
if ($_REQUEST['met']=="editme") {
	$metExcelCek = mysql_fetch_array($database->doQuery('SELECT * FROM ajkexcel WHERE fieldname="'.strtoupper($_REQUEST['fieldname']).'"'));
	if ($metExcelCek) {
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Error!</strong> field name '.$_REQUEST['fieldname'].' was insert.
                 </div>';
	}else{
	$metExcel = $database->doQuery('UPDATE ajkexcel SET fieldname="'.strtoupper($_REQUEST['fieldname']).'" WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"');
	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=exl">
				 <div class="alert alert-dismissable alert-success">
				 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                 <strong>Success!</strong> edit field name '.$_REQUEST['fieldname'].'.
                 </div>';
	}
}
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Excel</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=exl">'.BTN_BACK.'</a></div>
		</div>
	</div>
	<div class="row">
	'.$metnotif.'
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered" action="#" data-parsley-validate>
			<div class="panel-heading"><h3 class="panel-title">Edit Field Name Upload Excel</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Field Name Excel</label>
				<div class="col-sm-10"><input type="text" name="fieldname" class="form-control" value="'.$metExcelCek['fieldname'].'" required></div>
				</div>
			</div>
			<div class="panel-footer"><input type="hidden" name="met" value="editme">'.BTN_SUBMIT.'</div>
		</form>
		</div>
	</div>';
		;
		break;

	default:
if ($_REQUEST['act']=="t_actived") {
$metExcel = $database->doQuery('UPDATE ajkexcel SET aktif="tidak" WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"');
header('Location: ajk.php?re=exl');
}elseif($_REQUEST['act']=="y_actived"){
$metExcel = $database->doQuery('UPDATE ajkexcel SET aktif="ya" WHERE id="'.$thisEncrypter->decode($_REQUEST['fid']).'"');
header('Location: ajk.php?re=exl');
}else{	}
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Excel</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=exl&op=new">'.BTN_NEW.'</a></div>
		</div>
      </div>';
echo '<div class="table-responsive panel-collapse pull out">
      <table class="table table-hover table-bordered">
      <thead>
      	<tr>
        <th width="80%">Field Name Excel</th>
        <th>Active</th>
        <th>Edit</th>
        </tr>
    </thead>
    <tbody>';
$metExl = $database->doQuery('SELECT * FROM ajkexcel ORDER BY id ASC');
while ($metExl_ = mysql_fetch_array($metExl)) {
if ($metExl_['aktif']=="ya") {
	$fieldActive = '<a href="ajk.php?re=exl&act=t_actived&fid='.$thisEncrypter->encode($metExl_['id']).'"><span class="label label-success">'.$metExl_['aktif'].'</span></a>';
}else{
	$fieldActive = '<a href="ajk.php?re=exl&act=y_actived&fid='.$thisEncrypter->encode($metExl_['id']).'"><span class="label label-danger">'.$metExl_['aktif'].'</span></a>';
}
echo '<tr>
   	<td>'.$metExl_['fieldname'].'</td>
   	<td align="center">'.$fieldActive.'</td>
   	<td align="center"><a href="ajk.php?re=exl&op=editfield&fid='.$thisEncrypter->encode($metExl_['id']).'">'.BTN_EDIT.'</a></td>
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
