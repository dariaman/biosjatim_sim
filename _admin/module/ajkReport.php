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
switch ($_REQUEST["ro"]) {
	case "x":
		;
	break;

	case "rptlistdebitnote":
			echo '<div class="page-header-section"><h2 class="title semibold">Report Data Debitnote</h2></div>
			      	<div class="page-header-section">
					</div>
			      </div>';
			echo '<div class="row">
			<div class="col-md-12">';
			if ($_REQUEST['coBroker']) {	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']) {	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';	}
			if ($_REQUEST['coProduct']) {	$tiga ='AND ajkpolis.id = "'.$_REQUEST['coProduct'].'"';	}
			$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
														  FROM ajkcobroker
														  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
														  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
														  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


			if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkdebitnote.idbroker="'.$_REQUEST['coBroker'].'"';	}

			if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
			}else{
				$dua = 'AND ajkdebitnote.idclient="'.$_REQUEST['coClient'].'"';
				$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

			if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
			}else{
				$tiga = 'AND ajkdebitnote.idproduk="'.$_REQUEST['coProduct'].'"';
				$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

			if ($_REQUEST['statuspaid']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS</a></p></div></div>';
			}else{
				if ($_REQUEST['statuspaid']=="1") 		{	$_datapaid="Paid";
				}elseif ($_REQUEST['statuspaid']=="2")	{	$_datapaid="Paid*";
				}else{	$_datapaid="Unpaid";	}

				$empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';
				$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_datapaid).'</a></p></div></div>';	
			}

			if ($_REQUEST['datefrom']) {	
				// $enam ='AND ajkdebitnote.tgldebitnote BETWEEN "''" and "'._convertDateEng2($_REQUEST['dateto'].'"');	
				$enam = "AND ajkdebitnote.tgldebitnote BETWEEN '"._convertDateEng2($_REQUEST['datefrom'])."' AND '"._convertDateEng2($_REQUEST['dateto'])."' ";
			}

			
			// if ($_REQUEST['dateakadfrom']) {	
			// 	$enam ='AND ajkpeserta.tglakad BETWEEN '._convertDateEng2($_REQUEST['dateakadfrom']).' and '._convertDateEng2($_REQUEST['dateakadto']);	
			// }

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
									<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=rptdebitnote&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['statuspaid']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
														   <a href="ajk.php?re=dlPdf&pdf=rptdebitnote&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['statuspaid']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
									</li>
									</ul>
									</div>
				                </div>
								<div class="form-group">
				                	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
										'.$metClient.'
				                        '.$metProduct.'
										'.$metStatus.'
				                        <div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</a></p></div></div>
				                    </div>
				                </div>';
			$metCOBdata = mysql_fetch_array($database->doQuery('SELECT ajkdebitnote.id, Count(ajkpeserta.nama) AS jmember
																FROM ajkdebitnote
																INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
																INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
																WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND
																	  ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"
																GROUP BY ajkdebitnote.id'));
			if ($metCOBdata['id']) {
			echo '<div class="panel panel-default">
			<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
			<thead>
			<tr><th width="1%">No</th>
			    <th width="1%">Date DN</th>
			    <th>Debitnote</th>
			    <th width="1%">Member</th>
			    <th width="1%">Premium</th>
			    <th width="1%">Status</th>
			    <th width="15%">Date Payment</th>
			    <th width="10%">Branch</th>
			    </tr>
			</thead>
			<tbody>';

			$query = 'SELECT
								ajkdebitnote.id,
								ajkdebitnote.idbroker,
								ajkdebitnote.idclient,
								ajkdebitnote.idproduk,
								ajkdebitnote.idas,
								ajkdebitnote.idaspolis,
								ajkcabang.`name` AS cabang,
								ajkdebitnote.tgldebitnote,
								ajkdebitnote.nomordebitnote,
								ajkdebitnote.premiclient,
								ajkdebitnote.paidstatus,
								ajkdebitnote.paidtanggal,
								ajkdebitnote.premiasuransi,
								Count(ajkpeserta.nama) AS jmember
								FROM ajkdebitnote
								INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
								INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
								WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.'
								GROUP BY ajkdebitnote.id';
			// echo $query;
			$metCOB = $database->doQuery($query);

			while ($metCOB_ = mysql_fetch_array($metCOB)) {
			if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
				$tgllunas = '';
			}else{
				$tgllunas = _convertDate($metCOB_['paidtanggal']);
			}
			echo '<tr>
			   	<td align="center">'.++$no.'</td>
			   	<td align="center">'._convertDate($metCOB_['tgldebitnote']).'</td>
			   	<td>'.$metCOB_['nomordebitnote'].'</td>
			   	<td align="center">'.duit($metCOB_['jmember']).'</td>
			   	<td align="right">'.duit($metCOB_['premiclient']).'</td>
			   	<td align="center">'.$metCOB_['paidstatus'].'</td>
			   	<td align="center">'.$tgllunas.'</td>
			   	<td>'.$metCOB_['cabang'].'</td>
			    </tr>';
					}
					echo '</tbody>
				<tfoot>
				<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Debitnote"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Member"></th>
					<th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
					<th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
				</tr>
				</tfoot></table>
							</div>
						</div>
				    </form>
				</div>
				</div>
				</div>
			</div>';
			}else{	echo '<div class="alert alert-dismissable alert-danger text-center"><strong> Data pemilihan laporan debitnote tidak ada.</strong></div>';	}
			echo '</div>
			</div>';
					;
	break;

	case "rptdebitnote":
			echo '<div class="page-header-section"><h2 class="title semibold">Report Data Debitnote</h2></div>
			      	<div class="page-header-section">
					</div>
			      </div>';
			echo '<div class="row">
				<div class="col-md-12">
				<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<div class="panel-heading"><h3 class="panel-title">Data Debitnote</h3></div>
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
				  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
			echo '<label class="col-sm-2 control-label">Partner</label>
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
            			<div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" /></div>
                  <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" /></div>
                </div>
              </div>
            </div>
            <!--
						<div class="form-group">
							<label class="col-sm-2 control-label">Tgl Akad <span class="text-danger">*</span></label>
			        <div class="col-sm-10">
			        	<div class="row">
            			<div class="col-md-6"><input type="text" name="dateakadfrom" class="form-control" id="datepicker-akadfrom" value="'.$_REQUEST['dateakadfrom'].'" placeholder="From" /></div>
                  <div class="col-md-6"><input type="text" name="dateakadto" class="form-control" id="datepicker-akadto" value="'.$_REQUEST['dateakadto'].'" placeholder="to" /></div>
                </div>
              </div>
            </div>
            -->
			    	<div class="form-group">
			     		<label class="col-lg-2 control-label">Status</label>
              <div class="col-sm-10">
				        <select name="statuspaid" class="form-control">
									<option value="">Select status payment</option>
									<option value="1"'._selected($_REQUEST['statuspaid'], "1").'>Paid</option>
									<option value="2*"'._selected($_REQUEST['statuspaid'], "2*").'>Paid*</option>
									<option value="3"'._selected($_REQUEST['statuspaid'], "3").'>Unpaid</option>
			        	</select>
							</div>
						</div>
					</div>
					<div class="panel-footer"><input type="hidden" name="ro" value="rptlistdebitnote">'.BTN_SUBMIT.'</div>
					</form>
			</div>
			</div>';

			echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
			echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
					;
	break;

	case "rptpay":
			echo '<div class="page-header-section"><h2 class="title semibold">Report Payment</h2></div>
			      	<div class="page-header-section">
					</div>
			      </div>';
			echo '<div class="row">
			<div class="col-md-12">';
			if ($_REQUEST['coBroker']) {	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']) {	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';	}
			$met_idproduk = explode("_", $_REQUEST['coProduct']);
			if ($_REQUEST['coProduct']) {	$tiga ='AND ajkpolis.id = "'.$met_idproduk[0].'"';	}
			$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
														  FROM ajkcobroker
														  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
														  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
														  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


					if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkdebitnote.idbroker="'.$_REQUEST['coBroker'].'"';	}

					if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
					}else{
						$dua = 'AND ajkdebitnote.idclient="'.$_REQUEST['coClient'].'"';
						$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

					if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
					}else{
						$tiga = 'AND ajkdebitnote.idproduk="'.$_REQUEST['coProduct'].'"';
						$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

					if ($_REQUEST['datapaid']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS</a></p></div></div>';
					}else{
						if ($_REQUEST['datapaid']=="1") 		{	$_datapaid="Paid";
						}elseif ($_REQUEST['datapaid']=="2")	{	$_datapaid="Paid*";
						}else{	$_datapaid="Unpaid";	}
						$empat = 'AND ajkdebitnote.paidstatus="'.$_datapaid.'"';
						$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_datapaid).'</a></p></div></div>';	}

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
									<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=armpayment&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datapaid']).'&Q='.$thisEncrypter->encode($q['firstname']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
														   <a href="ajk.php?re=dlPdf&pdf=armpayment&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datapaid']).'&Q='.$thisEncrypter->encode($q['firstname']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
									</li>
									</ul>
									</div>
				                </div>
								<div class="form-group">
				                	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
										'.$metClient.'
				                        '.$metProduct.'
										'.$metStatus.'
				                        <div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</a></p></div></div>
				                    </div>
				                </div>
			<div class="panel panel-default">
			<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
			<thead>
			<tr><th width="1%">No</th>
			    <th>Debitnote</th>
			    <th width="1%">Date DN</th>
			    <th width="1%">Premium</th>
			    <th width="1%">Status</th>
			    <th width="15%">Date Payment</th>
			    <th width="10%">Branch</th>
			    </tr>
			</thead>
			<tbody>';

			$metCOB = $database->doQuery('SELECT
			ajkdebitnote.id,
			ajkdebitnote.idbroker,
			ajkdebitnote.idclient,
			ajkdebitnote.idproduk,
			ajkdebitnote.idas,
			ajkdebitnote.idaspolis,
			ajkcabang.`name` AS cabang,
			ajkdebitnote.tgldebitnote,
			ajkdebitnote.nomordebitnote,
			ajkdebitnote.premiclient,
			ajkdebitnote.paidstatus,
			ajkdebitnote.paidtanggal
			FROM ajkdebitnote
			INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
			WHERE ajkdebitnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkdebitnote.tgldebitnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"');
			while ($metCOB_ = mysql_fetch_array($metCOB)) {
			if ($metCOB_['paidtanggal']=="" OR $metCOB_['paidtanggal']=="0000-00-00") {
				$tgllunas = '';
			}else{
				$tgllunas = _convertDate($metCOB_['paidtanggal']);
			}
			echo '<tr>
			   	<td align="center">'.++$no.'</td>
			   	<td>'.$metCOB_['nomordebitnote'].'</td>
			   	<td align="center">'._convertDate($metCOB_['tgldebitnote']).'</td>
			   	<td align="right">'.duit($metCOB_['premiclient']).'</td>
			   	<td align="center">'.$metCOB_['paidstatus'].'</td>
			   	<td align="center">'.$tgllunas.'</td>
			   	<td>'.$metCOB_['cabang'].'</td>
			    </tr>';
					}
					echo '</tbody>
				<tfoot>
				<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Debitnote"></th>
					<th><input type="search" class="form-control" name="search_engine"></th>
					<th><input type="hidden" class="form-control" name="search_engine" placeholder="status"></th>
					<th><input type="search" class="form-control" name="search_engine"></th>
					<th><input type="hidden" class="form-control" name="search_engine"></th>
					<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
				</tr>
				</tfoot></table>
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

	case "rptpayment":
		echo '<div class="page-header-section"><h2 class="title semibold">Report Payment</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
		echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Data Members</h3></div>
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
				  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
		echo '<label class="col-sm-2 control-label">Partner</label>
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
					<select name="datapaid" class="form-control">
					<option value="">Select Payment</option>
					<option value="1"'._selected($_REQUEST['datapaid'], "1").'>Paid</option>
					<option value="2"'._selected($_REQUEST['datapaid'], "2").'>Paid*</option>
					<option value="3"'._selected($_REQUEST['datapaid'], "3").'>Unpaid</option>
				    </select>
					</div>
				</div>

			</div>
			<div class="panel-footer"><input type="hidden" name="ro" value="rptpay">'.BTN_SUBMIT.'</div>
			</form>
			</div>
		</div>';
		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
			;
	break;

	case "rptmember":
			echo '<div class="page-header-section"><h2 class="title semibold">Report Data Members</h2></div>
			  	<div class="page-header-section">
			</div>
			  </div>';
			echo '<div class="row">
			<div class="col-md-12">';
			if ($_REQUEST['coBroker']) {	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']) {	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';	}
			if ($_REQUEST['coProduct']) {	$tiga ='AND ajkpolis.id = "'.$_REQUEST['coProduct'].'"';	}
			$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
												  FROM ajkcobroker
												  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
												  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
												  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


			if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkpeserta.idbroker="'.$_REQUEST['coBroker'].'"';	}

			if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
			}else{
			$dua = 'AND ajkpeserta.idclient="'.$_REQUEST['coClient'].'"';
			$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

			if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
			}else{
			$tiga = 'AND ajkpeserta.idpolicy="'.$_REQUEST['coProduct'].'"';
			$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

			if ($_REQUEST['datastatus']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS</a></p></div></div>';
			}else{
			if ($_REQUEST['datastatus']=="1") {			$_statusaktif="Inforce";
			}elseif ($_REQUEST['datastatus']=="2") {	$_statusaktif="Lapse";
			}elseif ($_REQUEST['datastatus']=="3") {	$_statusaktif="Maturity";
			}elseif ($_REQUEST['datastatus']=="4") {	$_statusaktif="Batal";
			}elseif ($_REQUEST['datastatus']=="5") {	$_statusaktif="Pending";
			}elseif ($_REQUEST['datastatus']=="6") {	$_statusaktif="Approve";
			}else{	$_statusaktif !="";	}

			$empat = 'AND ajkpeserta.statusaktif="'.$_statusaktif.'"';
			$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_statusaktif).'</a></p></div></div>';	}

			echo '
			<div class="row">
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
										<li class="text-right">
											<a title="Bank" href="ajk.php?re=dlExcel&Rxls=lprmember&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
											<a href="ajk.php?re=dlPdf&pdf=lprmember&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
										</li>
									</ul>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 text-center">
									'.$metClient.'
									'.$metProduct.'
									'.$metStatus.'
									<div class="table-layout mt1 mb0">
										<div class="col-sm-12">
											<p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</a></p>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
									<thead>
										<tr>
											<th width="1%">No</th>
											<th width="1%">Debitnote</th>
											<th width="1%">Date DN</th>
											<th width="1%">KTP</th>
											<th width="1%">ID Member</th>
											<th>Name</th>
											<th width="1%">BOD</th>
											<th width="1%">Age</th>
											<th width="1%">Plafond</th>
											<th width="10%">Insurance Start</th>
											<th width="1%">Tenor</th>
											<th width="10%">Insurnce End</th>
											<th width="1%">Nett Premium</th>
											<th width="1%">Tgl Lunas</th>
											<th width="1%">Branch</th>
										</tr>
									</thead>
									<tbody>';
			$query = 'SELECT
			ajkdebitnote.nomordebitnote,
			ajkdebitnote.tgldebitnote,
			ajkpeserta.idpeserta,
			ajkpeserta.nopinjaman,
			ajkpeserta.nomorktp,
			ajkpeserta.nama,
			ajkpeserta.tgllahir,
			ajkpeserta.usia,
			IF(ajkpeserta.gender="L", "Laki-laki","Perempuan") AS jnskelamin,
			ajkpeserta.plafond,
			ajkclient.name AS perusahaan,
			ajkpeserta.tglakad,
			ajkpeserta.tenor,
			ajkpeserta.tglakhir,
			ajkpeserta.premirate,
			ajkpeserta.totalpremi,
			ajkpeserta.statusaktif,
			ajkpeserta.tgllunas,
			ajkpeserta.noasuransi,
			ajkpeserta.norebroker,
			ajkpeserta.noreasuransi,
			ajkkategoriprofesi.nm_kategori_profesi,
			ajkpolis.produk AS produk,
			ajkcabang.`name` AS cabang,
			ajkinsurance.name as nmasuransi,
			ajkpeserta.keterangan
			FROM ajkpeserta
			LEFT JOIN ajkdebitnote ON ajkdebitnote.id = ajkpeserta.iddn
			INNER JOIN ajkclient ON ajkpeserta.idclient = ajkclient.id
			INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
			INNER JOIN ajkinsurance ON ajkinsurance.id = ajkpeserta.asuransi
			INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
			LEFT JOIN ajkprofesi ON ajkpeserta.pekerjaan = ajkprofesi.ref_mapping
			LEFT JOIN ajkkategoriprofesi ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
			WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND 
			ajkpeserta.del IS NULL AND 
			ajkpeserta.tglakad BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND 
			"'._convertDateEng2($_REQUEST['dateto']).'"';
			
			$_SESSION['lprmember'] = $thisEncrypter->encode($query);

			$metCOB = $database->doQuery($query);

			while ($metCOB_ = mysql_fetch_array($metCOB)) {
			echo '<tr>
				<td align="center">'.++$no.'</td>
				<td align="center">'.$metCOB_['nomordebitnote'].'</td>
				<td align="center">'.$metCOB_['tgldebitnote'].'</td>
				<td align="center">'.$metCOB_['nomorktp'].'</td>
				<td align="center">'.$metCOB_['idpeserta'].'</td>
				<td>'.$metCOB_['nama'].'</td>
				<td align="center">'.$metCOB_['tgllahir'].'</td>
				<td align="center">'.$metCOB_['usia'].'</td>
				<td align="right">'.duit($metCOB_['plafond']).'</td>
				<td align="center">'._convertDate($metCOB_['tglakad']).'</td>
				<td align="center">'.$metCOB_['tenor'].'</td>
				<td align="center">'._convertDate($metCOB_['tglakhir']).'</td>
				<td align="right">'.duit($metCOB_['totalpremi']).'</td>
				<td align="center">'._convertDate($metCOB_['tgllunas']).'</td>
				<td>'.$metCOB_['cabang'].'</td>
			</tr>';
			}
			echo '
													</tbody>
													<tfoot>
														<tr>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="search" class="form-control" name="search_engine" placeholder="Debitnote"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="hidden" class="form-control" name="search_engine"></th>
															<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
								  </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
			;
	break;

	case "rptcreditnote":
		echo '<div class="page-header-section"><h2 class="title semibold">Report Data Creditnote</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
		echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Data Creditnote</h3></div>
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
				  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
			echo '<label class="col-sm-2 control-label">Partner</label>
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
			<label class="col-sm-2 control-label">Date of Creditnote <span class="text-danger">*</span></label>
			<div class="col-sm-10">
		    <div class="row">
		    	<div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" required/></div>
		        <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" required/></div>
		    </div>
		    </div>
		</div>
		<div class="form-group">
		<label class="col-lg-2 control-label">Type Klaim</label>
			<div class="col-sm-10">
			<select name="tipeklaim" class="form-control">
			<option value="">Select type claim creditnote</option>
			<option value="Batal"'._selected($_REQUEST['tipeklaim'], "Batal").'>Batal</option>
			<option value="Claim"'._selected($_REQUEST['tipeklaim'], "Claim").'>Claim</option>
			<option value="Refund"'._selected($_REQUEST['tipeklaim'], "Refund").'>Refund</option>
			<option value="Topup"'._selected($_REQUEST['tipeklaim'], "Topup").'>Topup</option>
			</select>
			</div>
		</div>

		</div>
		<div class="panel-footer"><input type="hidden" name="ro" value="rptliscreditnote">'.BTN_SUBMIT.'</div>
		</form>
		</div>
		</div>';

		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
			;
	break;

	case "rptliscreditnote":
			echo '<div class="page-header-section"><h2 class="title semibold">Report Data Creditnote</h2></div>
				 <div class="page-header-section"></div>
			</div>';
			echo '<div class="row">
				<div class="col-md-12">';
			if ($_REQUEST['coBroker']) {	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']) {	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';		$_metCLient = '&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'';	}
			if ($_REQUEST['coProduct']) {	$tiga ='AND ajkpolis.id = "'.$_REQUEST['coProduct'].'"';	$_metProduk = '&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'';	}
			$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
														  FROM ajkcobroker
														  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
														  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
														  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));

			if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkcreditnote.idbroker="'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
			}else{
				$dua = 'AND ajkcreditnote.idclient="'.$_REQUEST['coClient'].'"';
				$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

			if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
			}else{
				$metEx = explode("_",$_REQUEST['coProduct']);
				$tiga = 'AND ajkcreditnote.idproduk="'.$metEx[0].'"';
				$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

			if ($_REQUEST['statuscn']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS CLAIM</a></p></div></div>';
			}else{
			$empat = 'AND ajkcreditnote.tipeklaim="'.$_REQUEST['tipeklaim'].'"';
			$_metStatusCN = '&st='.$thisEncrypter->encode($_REQUEST['tipeklaim']).'';
			$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_datapaid).'</a></p></div></div>';	}

			/*
			echo $_REQUEST['coBroker'].'<br />';
			echo $_REQUEST['coClient'].'<br />';
			echo $_REQUEST['coProduct'].'<br />';
			echo $_REQUEST['datefrom'].'<br />';
			echo $_REQUEST['dateto'].'<br />';
			echo $_REQUEST['tipeklaim'].'<br />';
			*/
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
									<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=rptcreditnote&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['tipeklaim']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
														   <a href="ajk.php?re=dlPdf&pdf=rptcreditnote&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).''.$_metCLient.''.$_metProduk.'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).''.$_metStatusCN.'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
									</li>
									</ul>
									</div>
				                </div>
								<div class="form-group">
				                	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
										'.$metClient.'
				                        '.$metProduct.'
										'.$metStatus.'
				                        <div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</a></p></div></div>
				                    </div>
				                </div>';
			$metCOBdata = mysql_fetch_array($database->doQuery('SELECT ajkcreditnote.id, Count(ajkpeserta.nama) AS jmember
																FROM ajkcreditnote
																INNER JOIN ajkpeserta ON ajkcreditnote.id = ajkpeserta.idcn
																INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
																INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
																WHERE ajkcreditnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkcreditnote.tglcreditnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"
																GROUP BY ajkcreditnote.id'));
			if ($metCOBdata['id']) {
			echo '<div class="panel panel-default">
			<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
			<thead>
			<tr><th width="1%">No</th>
			    <th width="1%">Creditnote</th>
			    <th width="1%">Debitnote</th>
			    <th>Name</th>
			    <th width="1%">Nilai Claim</th>
			    <th width="1%">Status</th>
			    <th width="1%">Type Data</th>
			    <th width="1%">Date Claim</th>
			    <th width="10%">Branch</th>
			    </tr>
			</thead>
			<tbody>';
			$metCOB = $database->doQuery('SELECT
			ajkcreditnote.id,
			ajkcreditnote.idbroker,
			ajkcreditnote.idclient,
			ajkcreditnote.idproduk,
			ajkpeserta.idpeserta,
			ajkpeserta.nama,
			ajkpeserta.tgllahir,
			ajkpeserta.usia,
			ajkpeserta.plafond,
			ajkpeserta.tglakad,
			ajkpeserta.tenor,
			ajkpeserta.tglakhir,
			ajkpeserta.totalpremi,
			ajkcabang.`name`AS nmcabang,
			ajkdebitnote.nomordebitnote,
			ajkcreditnote.tglcreditnote,
			ajkcreditnote.tglklaim,
			ajkcreditnote.nilaiklaimdiajukan,
			ajkcreditnote.nilaiclaimclient,
			ajkcreditnote.nilaiclaimasuransi,
			ajkcreditnote.nomorcreditnote,
			ajkcreditnote.status,
			ajkcreditnote.tipeklaim
			FROM
			ajkcreditnote
			INNER JOIN ajkpeserta ON ajkcreditnote.id = ajkpeserta.idcn
			INNER JOIN ajkdebitnote ON ajkcreditnote.iddn = ajkdebitnote.id
			INNER JOIN ajkcabang ON ajkpeserta.cabang = ajkcabang.er
			WHERE ajkcreditnote.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND ajkcreditnote.tglcreditnote BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"
			GROUP BY ajkcreditnote.id');
			while ($metCOB_ = mysql_fetch_array($metCOB)) {
			echo '<tr>
			   	<td align="center">'.++$no.'</td>
			   	<td align="center">'.$metCOB_['nomorcreditnote'].'</td>
			   	<td>'.$metCOB_['nomordebitnote'].'</td>
			   	<td align="center">'.$metCOB_['nama'].'</td>
			   	<td align="right">'.duit($metCOB_['nilaiclaimclient']).'</td>
			   	<td align="center">'.$metCOB_['status'].'</td>
			   	<td align="center">'.$metCOB_['tipeklaim'].'</td>
			   	<td align="center">'._convertDate($metCOB_['tglklaim']).'</td>
			   	<td>'.$metCOB_['nmcabang'].'</td>
			    </tr>';
			}
			echo '</tbody>
				<tfoot>
				<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Creditnote"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Debitnote"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Member"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Type"></th>
						<th><input type="hidden" class="form-control" name="search_engine"></th>
						<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
				</tr>
				</tfoot></table>
								</div>
							</div>
				    </form>
				</div>
				</div>
				</div>
			</div>';
			}else{	echo '<div class="alert alert-dismissable alert-danger text-center"><strong> Data pemilihan laporan creditnote tidak ada.</strong></div>';	}
			echo '</div>
			</div>';
				;
	break;

	case "rptInsurance":
		echo '
		<div class="page-header-section"><h2 class="title semibold">Report Data Insurance</h2></div>
	  	<div class="page-header-section"></div>
	  </div>
		<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Data Members</h3></div>
				<div class="panel-body">
					<div class="form-group">';

		if ($q['idbroker'] == NULL) {
			echo '
			<label class="col-sm-2 control-label">Broker</label>
			<div class="col-sm-10">
			<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);"><option value="">Select Broker</option>';

			$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	
				echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	
			}

			echo '
			</select>
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
				  	<input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
			echo '<label class="col-sm-2 control-label">Partner</label>
						<div class="col-sm-10">
						<select name="coClient" id="coClient" class="form-control" onChange="mametClientProdukRateIns(this);"><option value="">Select Partner</option>';
			$metCoBroker = $database->doQuery('SELECT * FROM ajkclient WHERE del IS NULL AND idc="'.$q['idbroker'].'" ORDER BY name ASC');

			while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {	
				echo '<option value="'.$metCoBroker_['id'].'"'._selected($_REQUEST['cobroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';	
			}
			echo '
			</select>
			</div>
			</div>
			<div class="form-group">
			<label class="col-lg-2 control-label">Product </label>
			<div class="col-lg-10">
			<select name="coProduct" class="form-control" id="coProduct" onChange="mametInsuranceName(this);"><option value="">Select Product</option></select>
			</div>
			</div>';
		}
		echo '<div class="form-group">
				<label class="col-sm-2 control-label">Insurance </label>
				<div class="col-sm-10">';
		echo '<select name="coPolicyInsurance" class="form-control" id="coPolicyInsurance" onChange="mametInsuranceRate(this);"><option value="">Select Insurance</option></select>
					</div>
				</div>
				<!--<div class="form-group">
			    <label class="col-lg-2 control-label">Policy Insurance </label>
			    	<div class="col-lg-10">
			        <select name="coPolicy" class="form-control" id="coPolicyInsRate">
			        <option value="">Select Policy Insurance</option>
			        </select>
			        </div>
				</div>-->';
		echo '<div class="form-group">
			<label class="col-sm-2 control-label">Tgl Akad <span class="text-danger">*</span></label>
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
			<div class="panel-footer"><input type="hidden" name="ro" value="rptmemberIns">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';

			echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
			echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
			;
	break;

	case "rptmemberIns":
		echo '<div class="page-header-section"><h2 class="title semibold">Report Data Insurance Members</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
		echo '<div class="row">
		<div class="col-md-12">';
		if ($_REQUEST['coBroker']) 			{	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
		if ($_REQUEST['coClient']) 			{	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';	}
		if ($_REQUEST['coPolicyInsurance']) {	$metInsuranceExp = explode("_", $_REQUEST['coPolicyInsurance']);	$tiga ='AND ajkinsurance.id = "'.$metInsuranceExp[0].'"';	$tiga_ ='AND ajkdebitnote.idas = "'.$metInsuranceExp[0].'"';	}
		if ($_REQUEST['coProduct']) 		{	$metProduk = explode("_", $_REQUEST['coProduct']);					$empat ='AND ajkpolis.id = "'.$metProduk[0].'"';			$empat_ ='AND ajkdebitnote.idproduk = "'.$metProduk[0].'"';	}
		
		$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id,
																												 ajkcobroker.logo,
																												 ajkcobroker.`name` AS brokername,
																												 ajkclient.`name` AS clientname,
																												 ajkclient.logo AS logoclient,
																												 ajkpolis.produk,
																												 ajkpolis.policymanual,
																												 ajkinsurance.`name` AS insurancename
																										  FROM ajkcobroker
																										  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
																										  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
																										  INNER JOIN ajkinsurance ON ajkcobroker.id = ajkinsurance.idc
																										  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.' '.$empat.''));


				if ($_REQUEST['coBroker']=="") {	
					$metClient = '';	
				}else{	
					$satu = 'AND ajkdebitnote.idbroker="'.$_REQUEST['coBroker'].'"';	
				}

				if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
				}else{
					$dua = 'AND ajkdebitnote.idclient="'.$_REQUEST['coClient'].'"';
					$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

				if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
				}else{
					$tiga = 'AND ajkdebitnote.idproduk="'.$_REQUEST['coProduct'].'"';
					$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

				if ($_REQUEST['coPolicyInsurance']=="") {	$metInsurance = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL INSURANCE</a></p></div></div>';
				}else{
					$empat = 'AND ajkinsurance.id = "'.$_REQUEST['coPolicyInsurance'].'"';
					$metInsurance = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['insurancename'].'</a></p></div></div>';	}

				if ($_REQUEST['datastatus']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS</a></p></div></div>';
				}else{
					if ($_REQUEST['datastatus']=="1") {			$_statusaktif="Inforce";
					}elseif ($_REQUEST['datastatus']=="2") {	$_statusaktif="Lapse";
					}elseif ($_REQUEST['datastatus']=="3") {	$_statusaktif="Maturity";
					}elseif ($_REQUEST['datastatus']=="4") {	$_statusaktif="Batal";
					}else{	$_statusaktif !="";	}

					$lima = 'AND ajkpeserta.statusaktif="'.$_statusaktif.'"';
					$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_statusaktif).'</a></p></div></div>';	}

				echo '
				<div class="row">
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
										<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=lprmemberIns&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($metProduk[0]).'&ida='.$thisEncrypter->encode($metInsuranceExp[0]).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
										<a href="ajk.php?re=dlPdf&pdf=lprmemberIns&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($metProduk[0]).'&ida='.$thisEncrypter->encode($metInsuranceExp[0]).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrom']).'&dtto='.$thisEncrypter->encode($_REQUEST['dateto']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 text-center">
								'.$metClient.'
								'.$metProduct.'
								'.$metInsurance.'
								'.$metStatus.'
								<div class="table-layout mt1 mb0">
									<div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrom'])).' to '._convertDate(_convertDateEng2($_REQUEST['dateto'])).'</a></p></div>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
								<thead>
									<tr>
										<th width="1%">No</th>
										<th width="10%">Insurance</th>
										<th width="10%">Product</th>
										<th width="1%">ID Member</th>
										<th width="1%">No DN</th>
										<th width="1%">Tgl DN</th>
										<th>Name</th>
										<th width="1%">BOD</th>
										<th width="1%">Gender</th>
										<th width="10%">Insurance Start</th>
										<th width="10%">Insurnce End</th>
										<th width="1%">Tenor</th>
										<th width="1%">Plafond</th>
										<th width="1%">Age</th>										
										<th width="1%">Age + Tenor</th>
										<th width="1%">Rate</th>
										<th width="1%">Nett Premium</th>
										<th width="1%">Branch</th>
									</tr>
								</thead>
								<tbody>';

				$query = 'SELECT
				ajkdebitnote.nomordebitnote,
				ajkdebitnote.tgldebitnote,
				ajkpeserta.idpeserta,
				ajkpeserta.nomorktp,
				ajkpeserta.nama,
				ajkpeserta.tgllahir,
				IF(ajkpeserta.gender="L", "Laki-laki","Perempuan") AS gender,
				ajkpeserta.usia,
				ajkpeserta.plafond,
				ajkpeserta.tglakad,
				ajkpeserta.tenor,
				ajkpeserta.tglakhir,
				IF(ajkpeserta.gender="L", "Laki-laki","Perempuan") AS jnskelamin,
				ajkpeserta.astotalpremi,
				ajkpeserta.aspremirate,
				ajkpeserta.statusaktif,
				ajkcabang.`name` AS cabang,
				ajkinsurance.`name` AS asuransi,
				ajkpeserta.noasuransi,
				ajkkategoriprofesi.nm_kategori_profesi,
				ajkpeserta.norebroker,
				ajkpeserta.tgllunas,
				ajkpeserta.noreasuransi,
				ajkpolis.produk AS produk,
				ajkpeserta.astotalpremi*ajkinsurance.brokerage/100 as brokerage,
				ajkpeserta.astotalpremi*ajkinsurance.feebase/100 as feebase,
				ajkpeserta.astotalpremi*ajkinsurance.cad_klaim/100 as cad_klaim,
				ajkpeserta.astotalpremi*ajkinsurance.cad_premi/100 as cad_premi,
				(ajkpeserta.astotalpremi*ajkinsurance.brokerage/100)*ajkinsurance.ppn/100 as ppn,
				(ajkpeserta.astotalpremi*ajkinsurance.brokerage/100)*ajkinsurance.pph/100 as pph
				FROM ajkdebitnote
				INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
				INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
				INNER JOIN ajkinsurance ON ajkpeserta.asuransi = ajkinsurance.id
				INNER JOIN ajkpolis ON ajkpeserta.idpolicy = ajkpolis.id
				LEFT JOIN ajkprofesi ON ajkprofesi.id = ajkpeserta.pekerjaan
				LEFT JOIN ajkkategoriprofesi ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
				WHERE ajkpeserta.id !="" '.$satu.' '.$dua.' '.$tiga_.' '.$empat_.' '.$lima.' AND ajkpeserta.del IS NULL AND ajkpeserta.tglakad BETWEEN "'._convertDateEng2($_REQUEST['datefrom']).'" AND "'._convertDateEng2($_REQUEST['dateto']).'"';
				
				$_SESSION['lprmemberIns'] = $thisEncrypter->encode($query);

				$metCOB = $database->doQuery($query);

				while ($metCOB_ = mysql_fetch_array($metCOB)) {
					$usiatenor = round($metCOB_['tenor'] / 12) + $metCOB_['usia'];
					echo '<tr>
					   	<td align="center">'.++$no.'</td>
					   	<td align="center">'.$metCOB_['asuransi'].'</td>
					   	<td align="center">'.$metCOB_['produk'].'</td>
					   	<td align="center">'.$metCOB_['idpeserta'].'</td>
							<td align="center">'.$metCOB_['nomordebitnote'].'</td>
					   	<td align="center">'.$metCOB_['tgldebitnote'].'</td>
					   	<td>'.$metCOB_['nama'].'</td>
					   	<td align="center">'._convertDate($metCOB_['tgllahir']).'</td>
					   	<td align="center">'.$metCOB_['gender'].'</td>
					   	<td align="center">'._convertDate($metCOB_['tglakad']).'</td>
					   	<td align="center">'._convertDate($metCOB_['tglakhir']).'</td>
					   	<td align="center">'.$metCOB_['tenor'].'</td>
					   	<td align="right">'.duit($metCOB_['plafond']).'</td>
					   	<td align="center">'.$metCOB_['usia'].'</td>					   	
					   	<td align="right">'.$usiatenor.'</td>
					   	<td align="right">'.$metCOB_['aspremirate'].'</td>
					   	<td align="right">'.duit($metCOB_['astotalpremi']).'</td>
					   	<td>'.$metCOB_['cabang'].'</td>
					    </tr>';
				}
				echo '</tbody>
				<tfoot>
				<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Debitnote"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>				
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
				</tr>
				</tfoot></table>
				</div>
				</div>
				</form>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>';
			;
	break;

	case "spk":
		echo '<div class="page-header-section"><h2 class="title semibold">Report Data SPK</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>
		      <div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Data SPK</h3></div>
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
						  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
					echo '<label class="col-sm-2 control-label">Partner</label>
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
					<label class="col-sm-2 control-label">Date of Input SPK</label>
		            <div class="col-sm-10">
		            	<div class="row">
		                <div class="col-md-6"><input type="text" name="datefrominputspk" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrominputspk'].'" placeholder="From" required/></div>
		                <div class="col-md-6"><input type="text" name="datetoinputspk" class="form-control" id="datepicker-to" value="'.$_REQUEST['datetoinputspk'].'" placeholder="To" required/></div>
		                </div>
		            </div>
		        </div>
				    <div class="form-group">
				     	<label class="col-lg-2 control-label">Status</label>
		                <div class="col-sm-10">
					        <select name="datastatus" class="form-control">
							<option value="">Select Status</option>
							<option value="Request"'._selected($_REQUEST['datastatus'], "Request").'>Request</option>
							<option value="Pending"'._selected($_REQUEST['datastatus'], "Pending").'>Pending</option>
							<option value="Proses"'._selected($_REQUEST['datastatus'], "Proses").'>Proses</option>
							<option value="Approve"'._selected($_REQUEST['datastatus'], "Approve").'>Approve</option>
							<option value="Aktif"'._selected($_REQUEST['datastatus'], "Aktif").'>Aktif</option>
							<option value="Realisasi"'._selected($_REQUEST['datastatus'], "Realisasi").'>Realisasi</option>
							<option value="Batal"'._selected($_REQUEST['datastatus'], "Batal").'>Batal</option>
				        	</select>
						</div>
					</div>

				</div>
			<div class="panel-footer"><input type="hidden" name="ro" value="rptSPK">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';

			echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
			echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
			;
	break;

	case "rptSPK":
		echo '<div class="page-header-section"><h2 class="title semibold">Report Data SPK</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>
		<div class="row">
			<div class="col-md-12">';
			if ($_REQUEST['coBroker']) {	$satu ='AND ajkcobroker.id = "'.$_REQUEST['coBroker'].'"';	}
			if ($_REQUEST['coClient']) {	$dua ='AND ajkclient.id = "'.$_REQUEST['coClient'].'"';	}
			if ($_REQUEST['coProduct']) {	$tiga ='AND ajkpolis.id = "'.$_REQUEST['coProduct'].'"';	}
		$met_ = mysql_fetch_array($database->doQuery('SELECT ajkcobroker.id, ajkcobroker.logo, ajkcobroker.`name` AS brokername, ajkclient.`name` AS clientname, ajkclient.logo AS logoclient, ajkpolis.produk, ajkpolis.policymanual
													  FROM ajkcobroker
													  INNER JOIN ajkclient ON ajkcobroker.id = ajkclient.idc
													  INNER JOIN ajkpolis ON ajkclient.id = ajkpolis.idcost
													  WHERE ajkcobroker.del IS NULL '.$satu.' '.$dua.'  '.$tiga.''));


		if ($_REQUEST['coBroker']=="") {	$metClient = '';	}else{	$satu = 'AND ajkspk.idbroker="'.$_REQUEST['coBroker'].'"';	}

		if ($_REQUEST['coClient']=="") {	$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL CLIENT</a></p></div></div>';
		}else{
			$dua = 'AND ajkspk.idpartner="'.$_REQUEST['coClient'].'"';
			$metClient = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['clientname'].'</a></p></div></div>';	}

		if ($_REQUEST['coProduct']=="") {	$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL PRODUCT</a></p></div></div>';
		}else{
			$tiga = 'AND ajkspk.idproduk="'.$_REQUEST['coProduct'].'"';
			$metProduct = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.$met_['produk'].'</a></p></div></div>';	}

		if ($_REQUEST['datastatus']=="") {	$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">ALL STATUS</a></p></div></div>';
		}else{
			$empat = 'AND ajkspk.statusspk="'.$_REQUEST['datastatus'].'"';
			$metStatus = '<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'.strtoupper($_statusaktif).'</a></p></div></div>';	}

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
								<li class="text-right"><a href="ajk.php?re=dlExcel&Rxls=lprdataspk&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrominputspk']).'&dtto='.$thisEncrypter->encode($_REQUEST['datetoinputspk']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/excel.png" width="20"></a> &nbsp;
													   <!--<a href="ajk.php?re=dlPdf&pdf=lprdataspk&idb='.$thisEncrypter->encode($_REQUEST['coBroker']).'&idc='.$thisEncrypter->encode($_REQUEST['coClient']).'&idp='.$thisEncrypter->encode($_REQUEST['coProduct']).'&dtfrom='.$thisEncrypter->encode($_REQUEST['datefrominputspk']).'&dtto='.$thisEncrypter->encode($_REQUEST['datetoinputspk']).'&st='.$thisEncrypter->encode($_REQUEST['datastatus']).'" target="_blank"><img src="../image/dninvoice.png" width="20"></a>-->
								</li>
								</ul>
								</div>
			                </div>
							<div class="form-group">
			                	<div class="col-xs-12 col-sm-12 col-md-12 text-center">
									'.$metClient.'
			                        '.$metProduct.'
									'.$metStatus.'
			                        <div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">'._convertDate(_convertDateEng2($_REQUEST['datefrominputspk'])).' to '._convertDate(_convertDateEng2($_REQUEST['datetoinputspk'])).'</a></p></div></div>
			                    </div>
			                </div>
		<div class="panel panel-default">
		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
		<thead>
		<tr><th width="1%">No</th>
		    <th width="1%">Partner</th>
		    <th width="1%">Product</th>
		    <th width="1%">SPK</th>
		    <th width="1%">Status</th>
		    <th>Name</th>
		    <th width="1%">BOD</th>
		    <th width="1%">Age</th>
		    <th width="10%">Insurance Start</th>
		    <th width="1%">Tenor</th>
		    <th width="10%">Insurnce End</th>
		    <th width="1%">Plafond</th>
		    <th width="1%">Premium</th>
		    <th width="1%">EM</th>
		    <th width="1%">Nett Premium</th>
		    <th width="1%">Branch</th>
		    <th width="1%">Input Date</th>
		    </tr>
		</thead>
		<tbody>';

		$metSPK = $database->doQuery('SELECT
		ajkspk.id,
		ajkspk.idbroker,
		ajkspk.idpartner,
		ajkspk.idproduk,
		ajkcobroker.`name` AS namabroker,
		ajkclient.`name` AS namaperusahaan,
		ajkpolis.produk AS namaproduk,
		ajkratepremi.rate,
		ajkspk.nomorspk,
		ajkspk.statusspk,
		ajkspk.nama,
		ajkspk.dob,
		ajkspk.usia,
		ajkspk.tglakad,
		ajkspk.tenor,
		ajkspk.tglakhir,
		ajkspk.mppbln,
		ajkspk.plafond,
		ajkspk.premi,
		ajkspk.em,
		ajkspk.premiem,
		ajkspk.nettpremi,
		ajkspk.cabang,
		ajkcabang.`name` AS namacabang,
		DATE_FORMAT(ajkspk.input_date,"%Y-%m-%d") AS tglinput
		FROM ajkspk
		INNER JOIN ajkcobroker ON ajkspk.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkspk.idpartner = ajkclient.id
		INNER JOIN ajkpolis ON ajkspk.idproduk = ajkpolis.id
		LEFT JOIN ajkratepremi ON ajkspk.idrate = ajkratepremi.id
		INNER JOIN ajkcabang ON ajkspk.cabang = ajkcabang.er
		WHERE
		ajkspk.del IS NULL '.$satu.' '.$dua.' '.$tiga.' '.$empat.' AND DATE_FORMAT(ajkspk.input_date,"%Y-%m-%d") BETWEEN "'._convertDateEng2($_REQUEST['datefrominputspk']).'" AND "'._convertDateEng2($_REQUEST['datetoinputspk']).'"
		ORDER BY ajkspk.input_date DESC');
		while ($metSPK_ = mysql_fetch_array($metSPK)) {
		echo '<tr>
		   	<td align="center">'.++$no.'</td>
		   	<td align="center">'.$metSPK_['namaperusahaan'].'</td>
		   	<td align="center">'.$metSPK_['namaproduk'].'</td>
		   	<td align="center">'.$metSPK_['nomorspk'].'</td>
		   	<td align="center">'.$metSPK_['statusspk'].'</td>
		   	<td>'.$metSPK_['nama'].'</td>
		   	<td align="center">'.$metSPK_['dob'].'</td>
		   	<td align="center">'.$metSPK_['usia'].'</td>
		   	<td align="center">'._convertDate($metSPK_['tglakad']).'</td>
		   	<td align="center">'.$metSPK_['tenor'].'</td>
		   	<td align="center">'._convertDate($metSPK_['tglakhir']).'</td>
		   	<td align="right">'.duit($metSPK_['plafond']).'</td>
		   	<td align="right">'.duit($metSPK_['premi']).'</td>
		   	<td align="right">'.duit($metSPK_['em']).'</td>
		   	<td align="right">'.duit($metSPK_['nettpremi']).'</td>
		   	<td>'.$metSPK_['namacabang'].'</td>
		   	<td>'._convertDate($metSPK_['tglinput']).'</td>
		    </tr>';
				}
				echo '</tbody>
			<tfoot>
			<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="SPK"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Usia"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Tenor"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="EM"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
				<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
				<th><input type="hidden" class="form-control" name="search_engine"></th>
			</tr>
			</tfoot></table>
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

	default:
		echo '<div class="page-header-section"><h2 class="title semibold">Report Data Members</h2></div>
		      	<div class="page-header-section">
				</div>
		      </div>';
		echo '<div class="row">
			<div class="col-md-12">
			<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Data Members</h3></div>
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
			  <input type="hidden" name="coBroker" value="'.$q['idbroker'].'">';
		echo '<label class="col-sm-2 control-label">Partner</label>
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
		                <label class="col-sm-2 control-label">Tgl Akad <span class="text-danger">*</span></label>
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
							<option value="5"'._selected($_REQUEST['datastatus'], "5").'>Pending</option>
							<option value="6"'._selected($_REQUEST['datastatus'], "6").'>Approve</option>
				        	</select>
						</div>
					</div>

				</div>
			<div class="panel-footer"><input type="hidden" name="ro" value="rptmember">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';

		echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
			;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>