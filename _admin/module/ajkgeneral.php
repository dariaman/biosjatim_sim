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
switch ($_REQUEST['er']) {
	case "edgeneral":
$metGen = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE id="'.$thisEncrypter->decode($_REQUEST['idg']).'"'));
		if ($_REQUEST['met']=="saveme") {
			$metCompany = $database->doQuery('UPDATE ajkgeneraltype SET type="'.strtoupper($_REQUEST['generalname']).'",
																 		keterangan="'.strtoupper($_REQUEST['metGeneral']).'"
																		WHERE id="'.$thisEncrypter->decode($_REQUEST['idg']).'"');
			$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=general">
			<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success!</strong> Edit General Name '.strtoupper($_REQUEST['generalname']).'.
            </div>';
		}
		$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
		echo '<div class="page-header-section"><h2 class="title semibold">General</h2></div>
				<div class="page-header-section">
				<div class="toolbar"><a href="ajk.php?re=general">'.BTN_BACK.'</a></div>
				</div>
			</div>
		<div class="row">
		'.$metnotif.'
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">New General</h3></div>
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
						<div class="col-sm-10">
						<select name="cobroker" class="form-control" required>
				            		<option value="">Select Broker</option>';
		while ($metBroker_ = mysql_fetch_array($metBroker)) {
			echo '<option value="'.$metBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metBroker['id']).'>'.$metBroker_['name'].'</option>';
		}
		echo '</select>
					    </div>
				    </div>
					<div class="form-group">
						<label class="col-sm-2 control-label">General Name <span class="text-danger">*</span></label>
						<div class="col-sm-10"><input type="text" name="generalname" value="'.$metGen['type'].'" class="form-control" placeholder="General Name" required></div>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label">Type General <span class="text-danger">*</span></label>
						<div class="col-sm-10">
						<select name="metGeneral" class="form-control" required>
				            		<option value="">Select Type General</option>
									<option value="GENERAL"'._selected($metGen['keterangan'], "GENERAL").'>GENERAL</option>
									<option value="GENERAL + AJK"'._selected($metGen['keterangan'], "GENERAL + AJK").'>GENERAL + AJK</option>
						</select>
			    		</div>
		    		</div>
					<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
					</form>
				</div>
				</div>';
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

	case "newgeneral":
if ($_REQUEST['met']=="saveme") {
$metCompany = $database->doQuery('INSERT INTO ajkgeneraltype SET type="'.strtoupper($_REQUEST['generalname']).'",
																 keterangan="'.strtoupper($_REQUEST['metGeneral']).'",
																 idb="'.$_REQUEST['cobroker'].'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=general">
			<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success!</strong> Input General Name '.strtoupper($_REQUEST['generalname']).'.
            </div>';
}
$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="page-header-section"><h2 class="title semibold">General</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=general">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">New General</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="cobroker" class="form-control" required>
		            		<option value="">Select Broker</option>';
while ($metBroker_ = mysql_fetch_array($metBroker)) {
	echo '<option value="'.$metBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
}
echo '</select>
			    </div>
		    </div>
			<div class="form-group">
				<label class="col-sm-2 control-label">General Name <span class="text-danger">*</span></label>
				<div class="col-sm-10"><input type="text" name="generalname" value="'.$_REQUEST['generalname'].'" class="form-control" placeholder="General Name" required></div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label">Type General <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="metGeneral" class="form-control" required>
		            		<option value="">Select Type General</option>
							<option value="GENERAL"'._selected($_REQUEST['metGeneral'], "GENERAL").'>GENERAL</option>
							<option value="GENERAL + AJK"'._selected($_REQUEST['metGeneral'], "GENERAL + AJK").'>GENERAL + AJK</option>
				</select>
			    </div>
		    </div>
			<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
		;
		break;

case "newkodegeneral":
$_gen = mysql_fetch_array($database->doQuery('SELECT * FROM ajkgeneraltype WHERE id="'.metDecrypt($_REQUEST['idg']).'"'));
$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
//echo metDecrypt($_REQUEST['idb']).'<br />';
//echo metDecrypt($_REQUEST['idg']).'<br />';
echo '<div class="page-header-section"><h2 class="title semibold">General</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=general">'.BTN_BACK.'</a></div>
		</div>
	</div>
<div class="row">
'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
	<div class="panel-heading"><h3 class="panel-title">'.$_gen['type'].' ( '.$_gen['keterangan'].')</h3></div>
		<div class="panel-body">
			<div class="form-group">
			<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
				<div class="col-sm-10">
				<select name="cobroker" class="form-control" required>
		            		<option value="">Select Broker</option>';
		while ($metBroker_ = mysql_fetch_array($metBroker)) {
			echo '<option value="'.$metBroker_['id'].'"'._selected(metDecrypt($_REQUEST['idb']), $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
		}
		echo '</select>
			    </div>
		    </div>
			<div class="form-group">
				<label class="col-sm-2 control-label">General Code<br />{for upload excel}<span class="text-danger">*</span></label>
				<div class="col-sm-10"><input type="text" name="generalcode" value="'.$_REQUEST['generalcode'].'" class="form-control" placeholder="General Code" required></div>
			</div>
			<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;

case "guarantee":
echo '<div class="page-header-section"><h2 class="title semibold">List Guarantee Extendeed</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=general&er=nguarantee">'.BTN_NEW.'</a></div></div>
      </div>';
echo '<div class="row">
	      	<div class="col-md-12">
	        	<div class="panel panel-default">

	<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
	      <thead>
	      	<tr>
	        <th width="1%">No</th>
	        <th width="30%">Broker</th>
	        <th>Guarantee</th>
	        <th width="15%">Option</th>
	        </tr>
	    </thead>
	    <tbody>';
$metClient = $database->doQuery('SELECT ajkcobroker.id AS idbroker, ajkcobroker.`name`, ajkgeneralnamajaminan.namajaminan
								 FROM ajkgeneralnamajaminan
								 INNER JOIN ajkcobroker ON ajkgeneralnamajaminan.idbroker = ajkcobroker.id
								 WHERE ajkgeneralnamajaminan.del IS NULL '.$q___6.'
								 ORDER BY ajkgeneralnamajaminan.namajaminan ASC');

while ($metClient_ = mysql_fetch_array($metClient)) {
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metClient_['name'].'</td>
   	<td>'.$metClient_['namajaminan'].'</td>
   	<td align="center"><a href="ajk.php?re=general&er=edgeneral&idg='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a> &nbsp; '.$_metG.'</td>
    </tr>';
}
echo '</tbody>
	<tfoot>
        <tr>
        	<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Guarantee"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
	</tr>
	</tfoot></table>
	</div>
	</div>
	</div>
</div>';
	;
	break;

case "nguarantee":
if ($_REQUEST['met']=="saveme") {
$metCompany = $database->doQuery('INSERT INTO ajkgeneralnamajaminan SET idbroker="'.$_REQUEST['cobroker'].'",
																 		namajaminan="'.strtoupper($_REQUEST['guaranteename']).'",
																 		inputby="'.$q['id'].'"');
$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=general&er=guarantee">
			<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Success!</strong> Input Guarantee Name '.strtoupper($_REQUEST['generalname']).'.
            </div>';
		}
$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
echo '<div class="page-header-section"><h2 class="title semibold">New Guarantee</h2></div>
			<div class="page-header-section">
			<div class="toolbar"><a href="ajk.php?re=general&er=guarantee">'.BTN_BACK.'</a></div>
			</div>
		</div>
	<div class="row">
		<div class="col-md-12">
		'.$metnotif.'
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">New Form Guarantee</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="cobroker" class="form-control" required>
			            		<option value="">Select Broker</option>';
		while ($metBroker_ = mysql_fetch_array($metBroker)) {
			echo '<option value="'.$metBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metBroker_['id']).'>'.$metBroker_['name'].'</option>';
		}
		echo '</select>
				    </div>
			    </div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Guarantee Name <span class="text-danger">*</span></label>
					<div class="col-sm-10"><input type="text" name="guaranteename" value="'.$_REQUEST['guaranteename'].'" class="form-control" placeholder="Guarantee Name" required></div>
				</div>
				<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
				</form>
			</div>
			</div>';
	echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
	;
	break;


	default:
echo '<div class="page-header-section"><h2 class="title semibold">List General</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=general&er=newgeneral">'.BTN_NEW.'</a></div></div>
      </div>';

		//echo '<div class="table-responsive panel-collapse pull out">
		echo '<div class="row">
		      	<div class="col-md-12">
		        	<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		      <thead>
		      	<tr>
		        <th width="1%">No</th>
		        <th width="30%">Broker</th>
		        <th>General</th>
		        <th width="20%">Type</th>
		        <th width="15%">Option</th>
		        </tr>
		    </thead>
		    <tbody>';
$metClient = $database->doQuery('SELECT ajkcobroker.id AS idbroker,
										ajkcobroker.`name`,
										ajkgeneraltype.id,
										ajkgeneraltype.idb,
										ajkgeneraltype.type,
										ajkgeneraltype.keterangan,
										ajkgeneraltype.kode
										FROM ajkcobroker
										INNER JOIN ajkgeneraltype ON ajkcobroker.id = ajkgeneraltype.idb
										WHERE ajkcobroker.del IS NULL '.$q___6.'
										ORDER BY ajkgeneraltype.idb ASC, ajkgeneraltype.type ASC');
while ($metClient_ = mysql_fetch_array($metClient)) {
/* Dihide sementara 22082016
$metCode = mysql_fetch_array($database->doQuery('SELECT id FROM ajkgeneralkode WHERE idbroker="'.$metClient_['idb'].'" AND idlistgeneral="'.$metClient_['id'].'"'));
if ($metCode['id']) {
	$_metG = '<a href="ajk.php?re=general&er=vwgeneral&idg='.metEncrypt($metClient_['id']).'">'.BTN_VIEW.'</a>';
}else{
	$_metG = '<a href="ajk.php?re=general&er=newkodegeneral&idb='.metEncrypt($metClient_['idb']).'&idg='.metEncrypt($metClient_['id']).'">'.BTN_CODE.'</a>';
}
*/
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metClient_['name'].'</td>
   	<td>'.$metClient_['type'].'</td>
   	<td align="center">'.$metClient_['keterangan'].'</td>
   	<td align="center"><a href="ajk.php?re=general&er=edgeneral&idg='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a> &nbsp; '.$_metG.'</td>
    </tr>';
}
echo '</tbody>
		<tfoot>
        <tr>
        	<th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Broker"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="General"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
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