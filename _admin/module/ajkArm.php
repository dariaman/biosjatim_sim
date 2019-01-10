<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');

$today = date("Y-m-d G:i:s");
	echo '
	<section id="main" role="main">
	<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
	<div class="page-header page-header-block">';

switch ($_REQUEST['py']) {
	case "debitnote":
		echo '<div class="page-header-section"><h2 class="title semibold">Modul Agreement</h2></div>
				<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=arm">'.BTN_BACK.'</a></div></div>
			</div>';
		$metPay = mysql_fetch_array($database->doQuery('SELECT
		ajkdebitnote.id,
		ajkcobroker.`name` AS brokername,
		ajkcobroker.logo,
		ajkclient.`name` AS clientname,
		ajkpolis.produk,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.tgldebitnote,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.premiclient,
		ajkdebitnote.premiclientdibayar
		FROM ajkdebitnote
		INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
		INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
		WHERE ajkdebitnote.id = "'.$thisEncrypter->decode($_REQUEST['idpay']).'"'));

		$_premioutstanding = $metPay['premiclient'] - $metPay['premiclientdibayar'];
		if ($_premioutstanding > 0 ) {
			$_premioutstanding_ = '<span class="label label-danger">'.duit($_premioutstanding).'</span>';
		}else{
			$_premioutstanding_ = '<span class="label label-success">'.duit($_premioutstanding).'</span>';
		}

		if ($_REQUEST['met']=="savepayment") {
			$totaldibayar = $metPay['premiclientdibayar'] + $_REQUEST['paymentpremium'];
			if ($totaldibayar > $metPay['premiclient']) {	$notifMetError ='<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> Payment reject.</div>';	}
			if (_convertDateEng2($_REQUEST['datepay']) > $futoday) {	$notifMetError ='<div class="alert alert-dismissable alert-danger"><strong>Error!</strong> the payment date is not allowed the current date.</div>';	}
			if ($notifMetError) {

			}else{
			if ($totaldibayar == $metPay['premiclient']) {
				$paidstatus_ = "Paid";
				$metUpdatePeserta = $database->doQuery('UPDATE ajkpeserta SET statuslunas="1", tgllunas="'._convertDateEng2($_REQUEST['datepay']).'" WHERE iddn="'.$metPay['id'].'"');
			}else{
				$paidstatus_ = "Paid*";
			}
			$metPayment = $database->doQuery('UPDATE ajkdebitnote SET paidtanggal="'._convertDateEng2($_REQUEST['datepay']).'", premiclientdibayar="'.$totaldibayar.'", paidstatus="'.$paidstatus_.'", update_by="'.$q['id'].'", update_time="'.$futgl.'" WHERE id="'.$thisEncrypter->decode($_REQUEST['idpay']).'"');

		echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=arm">
			  <div class="alert alert-dismissable alert-success">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  <strong>Success!</strong> Update payment Debit Note number '.$metPay['nomordebitnote'].'.
		      </div>';
			}
		}

		if ($metPay['logo']=="") {
			$logoclient = '<img class="img-circle img-bordered" src="../'.$PathPhoto.'logo.png" alt="" width="75px">';
		}else{
			$logoclient = '<img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metPay['logo'].'" alt="" width="75px">';
		}
		echo '<div class="row">
				<div class="col-lg-12">
			        	<div class="tab-content">
			            	<div class="tab-pane active" id="profile">
			<form method="post" class="panel panel-color-top panel-default form-horizontal form-bordered"  action="#" data-parsley-validate enctype="multipart/form-data">
								<div class="panel-body pt0 pb0">
			                    	<div class="form-group header bgcolor-default">
			                        	<div class="col-md-12">
			            					<ul class="list-table">
			            					<li style="width:80px;">'.$logoclient.'</li>
											<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metPay['brokername'].'</h4></li>
											</ul>
										</div>
			                        </div>
									<div class="form-group">
			                            <div class="col-xs-12 col-sm-12 col-md-12">
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Partner &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$metPay['clientname'].'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Product &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$metPay['produk'].'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Debitote &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$metPay['nomordebitnote'].'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Date Debitote &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'._convertDate($metPay['tgldebitnote']).'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Nett Premium &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.duit($metPay['premiclient']).'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Paid Payment &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.duit($metPay['premiclientdibayar']).'</a></p></div>
		<div class="col-sm-2 text-right"><p class="meta nm text-left">Outstanding Premium &nbsp; </p></div>
		<div class="col-sm-10"><p class="meta nm"><a href="javascript:void(0);">'.$_premioutstanding_.'</a></p></div>
								        </div>
			                        </div>

									<div class="form-group header bgcolor-default">
		                            <div class="col-md-12"><h5 class="semibold text-primary nm">Update Payment</h5></div>
		                            </div>';
		if ($metPay['paidstatus']=="Paid") {
		echo '<div class="panel-body"><div class="row">
		        	<div class="alert alert-success fade in">
		            <h4 class="semibold">Payment has been paid!</h4>
					<p class="mb10">Debit Note have been paid on '._convertDate($metPay['paidtanggal']).'.</p>
		        </div>
		    </div></div>';
		}else{
			echo '<div class="form-group">
			      '.$notifMetError.'
				  <label class="control-label col-sm-2">Payment Premium</label>
		          	<div class="col-sm-10">
						<div class="row">
		                    <div class="col-md-12"><input type="text" name="paymentpremium" class="form-control" data-parsley-type="number" value="'.$_premioutstanding.'" placeholder="Payment Premium" required/></div>
						</div>
						</div>
		            </div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Date Payment</label>
						<input type="hidden" name="idpay" value="'.$thisEncrypter->encode($metPay['id']).'">
		                <div class="col-sm-10"><input type="text" name="datepay" class="form-control" id="datepicker1" placeholder="Date Payment Debit Note" required/></div>
			        </div>
				</div>
				<div class="panel-footer"><input type="hidden" name="met" value="savepayment">'.BTN_SUBMIT.'</div>
			    </div>
		';
		}
		echo '</form>
			</div>
		</div>';
		echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
				;
	break;

	case "setpayment":
		$idpeserta = $thisEncrypter->decode($_REQUEST['id']);
		$qpeserta = mysql_fetch_array(mysql_query("SELECT * FROM ajkpeserta WHERE idpeserta = '".$idpeserta."' and del is null"));
		$qbyr = mysql_fetch_array(mysql_query("SELECT sum(nilaibayar)as bayar FROM ajkbayar WHERE idpeserta = '".$idpeserta."' and del is null"));
		$premi = $qpeserta['totalpremi'] - $qbyr['bayar'];

		
		if($_POST['btnpay']=="procpay"){
    	$premi = str_replace(",","",$_POST['txtpremi']); 
    	$tgl = $_POST['txt_tglln'];
    	$noref = $_POST['txt_noreff']; 
    	$noloan = $qpeserta['nopinjaman'];
    	$premi_sys = $_POST['txt_premi'];
    	$ket = $_POST['txt_ket'];    	
    	$nilaibayar = $qbyr['bayar'] + $premi;

    	if($premi_sys == $nilaibayar){
    		$query1 = "UPDATE ajkpeserta 
    							SET statuslunas = '1',
    									tgllunas='".$today."',
    									statusaktif = 'Approve'
    							WHERE idpeserta = '".$idpeserta."'";
    		mysql_query($query1);
    	}

    	$query2 = " INSERT ajkbayar 
									SET nopinjaman ='".$noloan."',
											idpeserta = '".$idpeserta."',
											nilaibayar = '".$premi."',
											tglbayar = '".$tgl."',
											norefbayar = '".$noref."',
											keterangan = '".$ket."',
											input_by = '".$q['id']."',
											input_date = '".$today."';";
    	
    	mysql_query($query2);
    	echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=arm&py=members">
			  <div class="alert alert-dismissable alert-success">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  <strong>Update Success!
			  </div>';
    }

		echo '
						<div class="page-header-section"><h2 class="title semibold">Payment '.$qpeserta['nama'].' - ['.$qpeserta['idpeserta'].']</h2></div>
						<div class="page-header-section"></div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<form action="#" method="post" id="form2">
									<div class="form-group">
										<label class="col-sm-2 control-label">Nilai Bayar <span class="text-danger">*</span></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="txtpremi" id="txtpremi" value="'.$premi.'" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Tgl Bayar <span class="text-danger">*</span></label>
										<div class="col-sm-10">
											<input type="text" class="form-control datepicker" name="txt_tglln" value="'.$tgl.'" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">No Ref <span class="text-danger">*</span></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="txt_noreff" value="'.$noref.'" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Keterangan <span class="text-danger">*</span></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="txt_ket" value="'.$ket.'" required>
										</div>
									</div>
									<input type="hidden" id="btnpay" name="btnpay" value="procpay">

								  <div class="panel-footer text-center">
										<button type="submit" class="btn btn-success text-center"><i class="ico-save"></i> Save</button>										
										<a href="ajk.php?re=arm&py=members" class="btn btn-danger" ><i class="ico-close"></i> Close</a>
								  </div>
							  </form>
							</div>
						</div>
					</div>
					<script type="text/javascript" src="templates/{template_name}/javascript/jquery.inputmask.bundle.js"></script>
					<script type="text/javascript">  

						$("#txtpremi").inputmask("numeric", {
							radixPoint: ".",
							groupSeparator: ",",
							digits: 2,
							autoGroup: true,
							prefix: "", //No Space, this will truncate the first character
							rightAlign: false,
							oncleared: function () { self.Value(""); }
						});  
						$(function(){
						  $(".datepicker").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
						});
				  </script>';
	break;

	case "members":
		$qmember = "SELECT idpeserta,
											 nama,
											 nomorpk,
											 tglakad,
											 plafond,
											 tenor,
											 premirate,
											 premirate_sys,
											 premi,
											 premi_sys,
											 totalpremi,
											 astotalpremi,
											 tgllunas,
											 statuslunas,
											 ajkpolis.produk,
											 ajkcabang.name as nmcabang,
											 nopinjaman,
											 nm_kategori_profesi,
											 (SELECT sum(nilaibayar) FROM ajkbayar WHERE ajkbayar.idpeserta = ajkpeserta.idpeserta and del is null)as nilaibayar
								FROM ajkpeserta
								INNER JOIN ajkpolis
								ON ajkpolis.id = ajkpeserta.idpolicy
								INNER JOIN ajkcabang
								ON ajkcabang.er = ajkpeserta.cabang
								LEFT JOIN ajkprofesi
								ON ajkprofesi.ref_mapping = ajkpeserta.pekerjaan
								LEFT JOIN ajkkategoriprofesi
								ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
								WHERE statusaktif = 'Pending'";;
		$_SESSION['lprmemberarm'] = $thisEncrypter->encode($qmember);

		if ($_REQUEST['btnsubmit']=="submit") {
			$query = '';
			foreach($_REQUEST['idtemp'] as $k => $val){
				
				$qpes = mysql_fetch_array(mysql_query("SELECT * FROM ajkpeserta WHERE idpeserta = '".$val."' and del is null"));
				$qbyr = mysql_fetch_array(mysql_query("SELECT sum(nilaibayar)as nilaibayar FROM ajkbayar WHERE idpeserta = '".$val."' and del is null"));
				$premibayar = $qpes['premi'] - $qbyr['nilaibayar'];

				$query = "UPDATE ajkpeserta SET statuslunas='1',tgllunas=now(),statusaktif = 'Approve',approve_by = '".$q['id']."',approve_time='".$today."' WHERE idpeserta = '".$val."'; ";
	    	$query2 = " INSERT ajkbayar 
										SET nopinjaman ='".$qpes['nopinjaman']."',
												idpeserta = '".$val."',
												nilaibayar = '".$premibayar."',
												tglbayar = '".$today."',
												norefbayar = '".$qpes['noreflunas']."',
												keterangan = 'Approve',
												input_by = '".$q['id']."',
												input_date = '".$today."'";	

				mysql_query($query);
				mysql_query($query2);
			}
			echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=arm&py=members">
			  <div class="alert alert-dismissable alert-success">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  <strong>Approve Success!</div>';
		}
		
		echo '<script type="text/javascript" src="templates/{template_name}/javascript/jquery.inputmask.bundle.js"></script>';
		echo '<script>
				$(function(){
				  $(".datepicker").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
				});
			  </script>';
			   
		echo '
			<div class="page-header-section"><h2 class="title semibold">Payment Outstanding</h2></div>
			<div class="page-header-section"></div>
		</div>
		<div class="row">
		<div class="col-md-12">
		<div class="panel panel-default">
		<a href="ajk.php?re=dlExcel&Rxls=lprmemberarm" target="_blank"><img src="../image/excel.png" width="20"><br>Excel</a>

		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<table class="table table-hover table-bordered table-striped table-responsive" id="">
			<thead>
				<tr>
					<th width="2%"><input type="checkbox" id="selectall"/></th>					
					<th width="5%">Nomor Pinjaman</th>
					<th width="5%">Nama</th>					
					<th width="5%">Tgl Akad</th>
					<th width="2%">Plafond</th>
					<th width="1%">Tenor</th>
					<th width="1%">Pekerjaan</th>
					<th width="1%">Premi</th>
					<th width="1%">Bayar</th>
					<th width="1%">Selisih</th>
					<th width="5%">Cabang</th>		
					<th width="5%">Action</th>			
				</tr>
			</thead>
			<tbody>';

		$metMember = $database->doQuery($qmember);
		while ($metMember_ = mysql_fetch_array($metMember)) {			
			$pes = $thisEncrypter->encode($metMember_['idpeserta']);
			$selisih = $metMember_['premi'] - $metMember_['nilaibayar'];
			$dataceklist = '<input type="checkbox" class="case" name="idtemp[]" value="'.$metMember_['idpeserta'].'">';
			echo '
			<tr>
				<td align="center">'.$dataceklist.' '.++$no.'</td>
		   	<td align="center">'.$metMember_['nopinjaman'].'</td>
		   	<td>'.$metMember_['nama'].'</td>
		   	<td align="center">'._convertDate($metMember_['tglakad']).'</td>
		   	<td align="right">'.duit($metMember_['plafond']).'</td>
		   	<td align="center">'.$metMember_['tenor'].'</td>
				<td align="center">'.$metMember_['nm_kategori_profesi'].'</td>
				<td align="center">'.duit($metMember_['premi']).'</td>
				<td align="center">'.duit($metMember_['nilaibayar']).'</td>
				<td align="center">'.duit($selisih).'</td>
		   	<td>'.$metMember_['nmcabang'].'</td>
		   	<td><a href="ajk.php?re=arm&py=setpayment&id='.$pes.'" class="btn btn-primary">Payment</a></td>
	    </tr>';  
						
		}
				echo '
							</tbody>
		       	</table>
		       	<div class="panel-footer"><input type="hidden" name="btnsubmit" value="submit">'.BTN_SUBMIT.'</div>
		       	</form>
		    	</div>
		    				
			    </div>

				</div>
		  </div>
		</div> 

		<script language="javascript">
			$(function(){
			    $("#selectall").click(function () {	$(\'.case\').attr(\'checked\', this.checked);	});			    // add multiple select / deselect functionality
			    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
			        if($(".case").length == $(".case:checked").length) {
			            $("#selectall").attr("checked", "checked");
			        } else {
			            $("#selectall").removeAttr("checked");
			        }

			    });
			});
		</script>';	
	break;

	case "ins":
		$qmember = "SELECT idpeserta,
											 nama,
											 nomorpk,
											 tglakad,
											 plafond,
											 tenor,
											 premirate,
											 premirate_sys,
											 premi,
											 premi_sys,
											 totalpremi,
											 astotalpremi,
											 tgllunas,
											 statuslunas,
											 ajkpolis.produk,
											 ajkcabang.name as nmcabang,
											 nopinjaman,
											 nm_kategori_profesi,
											 (SELECT sum(nilaibayar) FROM ajkbayar WHERE ajkbayar.idpeserta = ajkpeserta.idpeserta and del is null)as nilaibayar
								FROM ajkpeserta
								INNER JOIN ajkpolis
								ON ajkpolis.id = ajkpeserta.idpolicy
								INNER JOIN ajkcabang
								ON ajkcabang.er = ajkpeserta.cabang
								LEFT JOIN ajkprofesi
								ON ajkprofesi.ref_mapping = ajkpeserta.pekerjaan
								LEFT JOIN ajkkategoriprofesi
								ON ajkkategoriprofesi.id = ajkprofesi.idkategoriprofesi
								WHERE stsbayaras is null and approve_by is not null";;
		$_SESSION['lprmemberarm'] = $thisEncrypter->encode($qmember);

		if ($_REQUEST['btnsubmit']=="submit") {
			$query = '';
			foreach($_REQUEST['idtemp'] as $k => $val){
				
				$query = "UPDATE ajkpeserta SET stsbayaras='1',tglbayaras=now() WHERE idpeserta = '".$val."'; ";

				mysql_query($query);
			}
			echo '<meta http-equiv="refresh" content="2; url=ajk.php?re=arm&py=members">
			  <div class="alert alert-dismissable alert-success">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			  <strong>Approve Success!</div>';
		}
		
		echo '<script type="text/javascript" src="templates/{template_name}/javascript/jquery.inputmask.bundle.js"></script>';
		echo '<script>
				$(function(){
				  $(".datepicker").datepicker({dateFormat: "yy-mm-dd", changeMonth: true, changeYear: true});
				});
			  </script>';
			   
		echo '
			<div class="page-header-section"><h2 class="title semibold">Payment To Insurance</h2></div>
			<div class="page-header-section"></div>
		</div>
		<div class="row">
		<div class="col-md-12">
		<div class="panel panel-default">
		

		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<table class="table table-hover table-bordered table-striped table-responsive" id="">
			<thead>
				<tr>
					<th width="2%"><input type="checkbox" id="selectall"/></th>					
					<th width="5%">Nomor Pinjaman</th>
					<th width="5%">Nama</th>					
					<th width="5%">Tgl Akad</th>
					<th width="2%">Plafond</th>
					<th width="1%">Tenor</th>
					<th width="1%">Pekerjaan</th>
					<th width="1%">Premi</th>
					<th width="5%">Cabang</th>			
				</tr>
			</thead>
			<tbody>';

		$metMember = $database->doQuery($qmember);
		while ($metMember_ = mysql_fetch_array($metMember)) {			
			$pes = $thisEncrypter->encode($metMember_['idpeserta']);
			$dataceklist = '<input type="checkbox" class="case" name="idtemp[]" value="'.$metMember_['idpeserta'].'">';
			echo '
			<tr>
				<td align="center">'.$dataceklist.' '.++$no.'</td>
		   	<td align="center">'.$metMember_['nopinjaman'].'</td>
		   	<td>'.$metMember_['nama'].'</td>
		   	<td align="center">'._convertDate($metMember_['tglakad']).'</td>
		   	<td align="right">'.duit($metMember_['plafond']).'</td>
		   	<td align="center">'.$metMember_['tenor'].'</td>
				<td align="center">'.$metMember_['nm_kategori_profesi'].'</td>
				<td align="center">'.duit($metMember_['premi']).'</td>
		   	<td>'.$metMember_['nmcabang'].'</td>
	    </tr>';  
						
		}
				echo '
							</tbody>
		       	</table>
		       	<div class="panel-footer"><input type="hidden" name="btnsubmit" value="submit">'.BTN_SUBMIT.'</div>
		       	</form>
		    	</div>
		    				
			    </div>

				</div>
		  </div>
		</div> 

		<script language="javascript">
			$(function(){
			    $("#selectall").click(function () {	$(\'.case\').attr(\'checked\', this.checked);	});			    // add multiple select / deselect functionality
			    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
			        if($(".case").length == $(".case:checked").length) {
			            $("#selectall").attr("checked", "checked");
			        } else {
			            $("#selectall").removeAttr("checked");
			        }

			    });
			});
		</script>';	
	break;

	default:
		echo '
		<div class="page-header-section"><h2 class="title semibold">Payment Debit Note</h2></div>
		<div class="page-header-section"></div>
		</div>
		<div class="row">
		<div class="col-md-12">
		<div class="panel panel-default">

		<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
			<thead>
				<tr>
				<th width="1%">No</th>
					<th>Partner</th>
					<th>Product</th>
					<th width="1%">Date DN</th>
					<th>Debit Note</th>
					<th width="1%">Members</th>
					<th width="1%">Nett Premium</th>
					<th width="10%">Status</th>
					<th width="10%">Date Paid</th>
					<th width="10%">Branch</th>
				</tr>
			</thead>
			<tbody>';

		$metDebitnote = $database->doQuery('SELECT
		Count(ajkpeserta.nama) AS jData,
		ajkcobroker.`name` AS namebroker,
		ajkclient.`name` AS nameclient,
		ajkpolis.produk,
		ajkcabang.`name` AS cabang,
		ajkdebitnote.id,
		ajkdebitnote.nomordebitnote,
		ajkdebitnote.premiclient,
		ajkdebitnote.premiasuransi,
		ajkdebitnote.paidstatus,
		ajkdebitnote.paidtanggal,
		ajkdebitnote.tgldebitnote
		FROM ajkdebitnote
		INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
		INNER JOIN ajkcobroker ON ajkdebitnote.idbroker = ajkcobroker.id
		INNER JOIN ajkclient ON ajkdebitnote.idclient = ajkclient.id
		INNER JOIN ajkpolis ON ajkdebitnote.idproduk = ajkpolis.id
		INNER JOIN ajkcabang ON ajkdebitnote.idcabang = ajkcabang.er
		WHERE ajkdebitnote.del IS NULL '.$q___3.'
		GROUP BY ajkdebitnote.id
		ORDER BY ajkdebitnote.id DESC');
		while ($metDebitnote_ = mysql_fetch_array($metDebitnote)) {
			if ($metDebitnote_['paidstatus']=="Unpaid") {
				$metPaid_ = '<span class="label label-inverse">'.$metDebitnote_['paidstatus'].'</span>';
			}elseif ($metDebitnote_['paidstatus']=="Paid*") {
				$metPaid_ = '<span class="label label-danger">'.$metDebitnote_['paidstatus'].'</span>';
			}else{
				$metPaid_ = '<span class="label label-success">'.$metDebitnote_['paidstatus'].'</span>';
			}
			echo '<tr>
			   	<td align="center">'.++$no.'</td>
			   	<td>'.$metDebitnote_['nameclient'].'</td>
			   	<td align="center">'.$metDebitnote_['produk'].'</td>
			   	<td align="center">'._convertDate($metDebitnote_['tgldebitnote']).'</td>
			   	<td><a href="ajk.php?re=dlPdf&pID='.$thisEncrypter->encode($metDebitnote_['nomordebitnote']).'&idd='.$thisEncrypter->encode($metDebitnote_['id']).'" target="_blank">'.$metDebitnote_['nomordebitnote'].'</a></td>
			   	<td align="center"><a href="ajk.php?re=dlPdf&pdf=member&pID='.$thisEncrypter->encode($metDebitnote_['nomordebitnote']).'&idd='.$thisEncrypter->encode($metDebitnote_['id']).'" target="_blank">'.$metDebitnote_['jData'].'</a></td>
			   	<td align="right">'.duit($metDebitnote_['premiclient']).'</td>
			   	<td align="center"><a href="ajk.php?re=arm&py=debitnote&idpay='.$thisEncrypter->encode($metDebitnote_['id']).'">'.$metPaid_.'</a></td>
			   	<td align="center">'._convertDate($metDebitnote_['paidtanggal']).'</td>
			   	<td>'.$metDebitnote_['cabang'].'</td>
			    </tr>';
		}
				echo '
							</tbody>
							<tfoot>
				        <tr>
					        <th><input type="hidden" class="form-control" name="search_engine"></th>
			            <th><input type="search" class="form-control" name="search_engine" placeholder="Partner"></th>
			            <th><input type="search" class="form-control" name="search_engine" placeholder="Product"></th>
			            <th><input type="hidden" class="form-control" name="search_engine"></th>
			            <th><input type="search" class="form-control" name="search_engine" placeholder="Debit Note"></th>
			            <th><input type="hidden" class="form-control" name="search_engine"></th>
			            <th><input type="hidden" class="form-control" name="search_engine"></th>
			            <th><input type="search" class="form-control" name="search_engine" placeholder="Status"></th>
			            <th><input type="hidden" class="form-control" name="search_engine"></th>
			            <th><input type="search" class="form-control" name="search_engine"></th>
				        </tr>
			        </tfoot>
		       	</table>
		    	</div>
				</div>
		  </div>
		</div>';
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>