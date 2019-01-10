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
	case "vIns":
		;
		break;
	case "dIns":
		;
		break;
	case "newIns":
		if ($_REQUEST['met']=="saveme") {
			if ($_FILES['fileImage']['size'] / 1024 > $FILESIZE_2)	{
				$metnotif .= '<div class="alert alert-dismissable alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Error!</strong> File tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
				         	</div>';
			}else{				

				$nama_file = 'Insurance_'.strtolower(strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
				$nama_file_thumb = 'Insurance_'.strtolower("thumb_".strtoupper($_REQUEST['companyname']).$_FILES['fileImage']['name']);
				metImage($nama_file);
				$metCompany = $database->doQuery('INSERT INTO ajkinsurance SET name="'.strtoupper($_REQUEST['companyname']).'",
																			idc="'.$_REQUEST['cobroker'].'",
																			address1="'.ucwords($_REQUEST['street']).'",
																			address2="'.ucwords($_REQUEST['addressline']).'",
																			city="'.strtoupper($_REQUEST['city']).'",
																			postcode="'.$_REQUEST['postcode'].'",
																			phoneoffice="'.$_REQUEST['phoneoffice'].'",
																			phonehp="'.$_REQUEST['phonehp'].'",
																			phonefax="'.$_REQUEST['phonefax'].'",
																			logo="'.$nama_file.'",
																			logothumb="'.$nama_file_thumb.'",
																			input_by="'.$q['id'].'",
																			input_time="'.$futgl.'"');

				$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=ins">
										 <div class="alert alert-dismissable alert-success">
										 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
				            <strong>Success!</strong> Input Company '.strtoupper($_REQUEST['companyname']).'.
				            </div>';
			}
		}

				$metBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
				echo '<div class="page-header-section"><h2 class="title semibold">Insurance</h2></div>
					<div class="page-header-section">
					<div class="toolbar"><a href="ajk.php?re=ins">'.BTN_BACK.'</a></div>
					</div>
				</div>
				<div class="row">
				'.$metnotif.'
				<div class="col-md-12">
				<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
				<div class="panel-heading"><h3 class="panel-title">New Form Insurance</h3></div>
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
						<label class="col-sm-2 control-label">Insurance<span class="text-danger">*</span></label>
						<div class="col-sm-10"><input type="text" name="companyname" value="'.$_REQUEST['companyname'].'" class="form-control" placeholder="Insurance Name" required></div>
						</div>

					<div class="form-group">
					<label class="control-label col-sm-2">Address <span class="text-danger">*</span></label>
					<div class="col-sm-10">
						<div class="row mb5"><div class="col-sm-12"><input name="street" value="'.$_REQUEST['street'].'" type="text" class="form-control" placeholder="Street Address" required></div></div>
						<div class="row mb5"><div class="col-sm-12"><input name="addressline" value="'.$_REQUEST['addressline'].'" type="text" class="form-control" placeholder="Address Line 2"></div></div>
							<div class="row">
					        <div class="col-xs-6 pr5"><input name="city" value="'.$_REQUEST['city'].'" type="text" class="form-control" placeholder="City" required></div>
					        <div class="col-xs-6 pl5"><input name="postcode" value="'.$_REQUEST['postcode'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Postcode" required></div>
							</div>
					    </div>
					</div>

					<div class="form-group">
					<label class="control-label col-sm-2">Phone <span class="text-danger">*</span></label>
						<div class="col-sm-10">
							<div class="row">
					        	<div class="col-xs-4 pr5"><input name="phoneoffice" value="'.$_REQUEST['phoneoffice'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Phone Office" required></div>
					            <div class="col-xs-4 pc5"><input name="phonehp" value="'.$_REQUEST['phonehp'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Handphone" required></div>
					            <div class="col-xs-4 pl5"><input name="phonefax" value="'.$_REQUEST['phonefax'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Fax" required></div>
							</div>
						</div>
					</div>

					<div class="form-group">
					<label class="col-sm-2 control-label">Logo <span class="text-danger">*</span></label>
						<div class="col-sm-10"><input type="file" name="fileImage" accept="image/*" required></div>
					</div>

					</div>
					<div class="panel-footer"><input type="hidden" name="met" value="saveme">'.BTN_SUBMIT.'</div>
					</form>
					</div>
				</div>';
				echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
				/*
				echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
				  <script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
				*/
					;
	break;

	case "policy":
echo '<div class="page-header-section"><h2 class="title semibold">Policy</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=ins&er=newpolicy">'.BTN_NEW.'</a></div>
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
		<th>Partner</th>
		<th>Product</th>
		<th>Insurance</th>
		<th>Policy</th>
		<th width="1%">TypeRate</th>
		<th width="1%">Share</th>
		<th width="1%">Option</th>
	</tr>
	</thead>
	<tbody>';
$metPolicy = $database->doQuery('SELECT
ajkinsurance.`name`,
ajkpolisasuransi.id,
if(ajkpolisasuransi.policyauto="",ajkpolisasuransi.policymanual,ajkpolisasuransi.policyauto) AS nopolicy,
ajkpolisasuransi.produk,
ajkpolisasuransi.typerate,
ajkpolisasuransi.start_date,
ajkpolisasuransi.end_date,
ajkpolisasuransi.agestart,
ajkpolisasuransi.ageend,
ajkpolisasuransi.brokrage,
ajkpolisasuransi.shareins,
ajkpolisasuransi.adminfee,
ajkpolisasuransi.diskon,
ajkpolisasuransi.ppn,
ajkpolisasuransi.pph,
ajkpolisasuransi.freecover,
ajkpolisasuransi.filepks,
ajkpolisasuransi.shareproduk,
ajkclient.`name` AS partner,
ajkpolis.produk
FROM
ajkpolisasuransi
INNER JOIN ajkinsurance ON ajkpolisasuransi.idas = ajkinsurance.id
INNER JOIN ajkclient ON ajkpolisasuransi.idcost = ajkclient.id
INNER JOIN ajkpolis ON ajkpolisasuransi.idproduk = ajkpolis.id
WHERE ajkpolisasuransi.del IS NULL AND ajkinsurance.del IS NULL '.$q___.'
ORDER BY ajkinsurance.id DESC');
while ($metPolicy_ = mysql_fetch_array($metPolicy)) {
if ($metPolicy_['shareproduk']=="") {
	$metShareProduk = '';
}else{
	$metShareProduk = '<span class="label label-success">'.$metPolicy_['shareproduk'].'%</span>';
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metPolicy_['partner'].'</td>
   	<td>'.$metPolicy_['produk'].'</td>
   	<td>'.$metPolicy_['name'].'</td>
   	<td><a href="ajk.php?re=client&op=polview&pid='.$thisEncrypter->encode($metPolicy_['id']).'">'.$metPolicy_['nopolicy'].'</a></td>
   	<td align="center">'.$metPolicy_['typerate'].'</td>
   	<td align="center">'.$metShareProduk.'</td>
   	<td align="center"><a href="ajk.php?re=ins&er=poledt&pid='.$thisEncrypter->encode($metPolicy_['id']).'">'.BTN_EDIT.'</a></td>
    </tr>';
}
echo '</tbody>
		<tfoot>
		<tr>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Policy"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Type Rate"></th>
		<th><input type="search" class="form-control" name="search_engine" placeholder="Share"></th>
		<th><input type="hidden" class="form-control" name="search_engine"></th>
		</tr>
		</tfoot></table>
				</div>
			</div>
		</div>
	</div>';
	;
	break;

	case "newpolicy":
$polis =mysql_fetch_array($database->doQuery('SELECT idp FROM ajkpolisasuransi ORDER BY id DESC'));
if ($polis['idp']=="") {	$xidPol = 1;	}	else	{	$xidPol = $polis['idp'] + 1;	}
$numb = 100000 + $xidPol; $numb1 = substr($numb,1);
$RNoPolis = 'INS.'.$DatePolis.''.$numb1.''.$xidPol;

echo '<div class="page-header-section"><h2 class="title semibold">Policy Insurance</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=ins&er=policy">'.BTN_BACK.'</a></div>
		</div>
	</div>';
if ($_REQUEST['met']=="savemepolis") {
	if ($_FILES['filePKS']['size'] / 1024 > $FILESIZE_2)	{
	$metnotif .= '<div class="alert alert-dismissable alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Error!</strong> File PKS tidak boleh lebih dari '.$FILESIZE_2 / 1024 .'Mb !
           	</div>';
	}
	else{
	$nama_file =  'PKS_INS_'.$futgl1.'_'.$_REQUEST['pcompany'].'_'.$xidPol.'_'.$_FILES['filePKS']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
	$sourceFILE = $_FILES['filePKS']['tmp_name'];
	$direktori = '../'.$PathDokumen.'/'.$nama_file;
	move_uploaded_file($sourceFILE,$direktori);
	$metPolicyNew = $database->doQuery('INSERT INTO ajkpolisasuransi SET idbroker="'.$_REQUEST['coBroker'].'",
																		idcost="'.$_REQUEST['coClient'].'",
																		idproduk="'.$_REQUEST['coProduct'].'",
																		idas="'.$_REQUEST['pcompany'].'",
																 		idp="'.$xidPol.'",
																 		policyauto="'.$RNoPolis.'",
																 		policymanual="'.strtoupper($_REQUEST['manualpolicy']).'",
																 		produk="'.strtoupper($_REQUEST['productname']).'",
																 		typerate="'.$_REQUEST['typerate'].'",
																 		byrate="'.$_REQUEST['byrate'].'",
																 		calculatedrate="'.$_REQUEST['ratecalculate'].'",
																 		refundrate="'.$_REQUEST['raterefund'].'",
																 		refundpercentage="'.$_REQUEST['percentageRefund'].'",
																 		klaimrate="'.$_REQUEST['rateklaim'].'",
																 		klaimpercentage="'.$_REQUEST['percentageClaim'].'",
																 		start_date="'._convertDateEng2($_REQUEST['datefrom']).'",
																 		end_date="'._convertDateEng2($_REQUEST['dateto']).'",
																 		lastdayinsurance="'.$_REQUEST['bs-touchspin-basic'].'",
																 		wpc="'.$_REQUEST['bs-touchspin-wpc'].'",
																 		minimumpremi="'.$_REQUEST['minpremi'].'",
																 		shareins="'.$_REQUEST['bs-touchspin-shareins'].'",
																 		brokrage="'.$_REQUEST['bs-touchspin-brokrage'].'",
																 		adminfee="'.$_REQUEST['adminfee'].'",
																 		diskon="'.$_REQUEST['bs-touchspin-discount'].'",
																 		ppn="'.$_REQUEST['bs-touchspin-ppn'].'",
																 		pph="'.$_REQUEST['bs-touchspin-pph'].'",
																 		bankdebitnote="'.$_REQUEST['dnbankname'].'",
																 		bankdebitnotecabang="'.$_REQUEST['dnbankbranch'].'",
																 		bankdebitnoteaccount="'.$_REQUEST['dnbankaccount'].'",
																 		bankcreditnote="'.$_REQUEST['cnbankname'].'",
																 		bankcreditnotecabang="'.$_REQUEST['cnbankbranch'].'",
																 		bankcreditnoteaccount="'.$_REQUEST['cnbankaccount'].'",
																 		freecover="'.$_REQUEST['freecover'].'",
																 		shareproduk="'.$_REQUEST['bs-touchspin-shareasuransi'].'",
																 		filepks="'.$nama_file.'",
																 		input_by="'.$q['id'].'",
																 		input_date="'.$futgl.'"');

	$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=ins&er=policy">
					 <div class="alert alert-dismissable alert-success">
					 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Success!</strong> New Policy Insurance number '.$RNoPolis.'.
                 </div>';
			}
		}

echo '<script type="text/javascript">
	function ohYesOhNo() {	if (document.getElementById("customradio4").checked) {	document.getElementById("ifYes").style.display = "block";	}	else {	document.getElementById("ifYes").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function SikAsik() {	if (document.getElementById("customradio6").checked) {	document.getElementById("ifAsik").style.display = "block";	}	else {	document.getElementById("ifAsik").style.display = "none";	}	}
	</script>
<div class="row">
'.$metnotif.'
	<div class="col-md-12">
	<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
		<div class="panel-heading"><h3 class="panel-title">New Policy Insurance</h3></div>
			<div class="panel-body">
				<div class="form-group">
				<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);" required>
					            		<option value="">Select Broker</option>';
$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
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
	       	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
		       	<div class="col-lg-10">
		        <select name="coProduct" class="form-control" id="coProduct" required>
			              			<option value="">Select Product</option>
		        </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Insurance <span class="text-danger">*</span></label>
			<div class="col-sm-10">
	        <select name="pcompany" class="form-control" required>
	        	<option value="">Select Insurance</option>';
$metInsurance = $database->doQuery('SELECT id, name FROM ajkinsurance WHERE del IS NULL '.$q__.' ORDER BY name ASC');
while ($metInsurance_ = mysql_fetch_array($metInsurance)) {
echo '<option value="'.$metInsurance_['id'].'"'._selected($_REQUEST['pcompany'], $metInsurance_['id']).'>'.$metInsurance_['name'].'</option>';
}
echo '			</select>
        	</div>
       	</div>
		<div class="form-group">
		<label class="control-label col-sm-2">Policy Number</label>
			<div class="col-sm-10">
		    <div class="row mb5"><div class="col-sm-12"><input name="autopolicy" value="'.$RNoPolis.'" type="text" class="form-control" placeholder="Agreement System" disabled></div></div>
			<div class="row mb5"><div class="col-sm-12"><input name="manualpolicy" value="'.$_REQUEST['manualpolicy'].'" type="text" class="form-control" placeholder="Policy Manual"></div></div>
		    </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Date of Policy <span class="text-danger">*</span></label>
		    <div class="col-sm-10">
		    	<div class="row">
		        <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$_REQUEST['datefrom'].'" placeholder="From" required/></div>
		        <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$_REQUEST['dateto'].'" placeholder="to" required/></div>
				</div>
			</div>
		</div>
		            <div class="form-group">
			            <label class="control-label col-sm-2">Bank for Debitnote <span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
						<div class="row">
		                	<div class="col-xs-4 pr5"><input name="dnbankname" value="'.$_REQUEST['dnbankname'].'" type="text" class="form-control" placeholder="Bank" required></div>
		                	<div class="col-xs-4 pc5"><input name="dnbankbranch" value="'.$_REQUEST['dnbankbranch'].'" type="text" class="form-control" placeholder="Branch" required></div>
		                    <div class="col-xs-4 pl5"><input name="dnbankaccount" value="'.$_REQUEST['dnbankaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
						</div>
						</div>
		            </div>
		            <div class="form-group">
			            <label class="control-label col-sm-2">Bank for Creditnote <span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
						<div class="row">
		                	<div class="col-xs-4 pr5"><input name="cnbankname" value="'.$_REQUEST['cnbankname'].'" type="text" class="form-control" placeholder="Bank" required></div>
		                	<div class="col-xs-4 pc5"><input name="cnbankbranch" value="'.$_REQUEST['cnbankbranch'].'" type="text" class="form-control" placeholder="Branch" required></div>
		                    <div class="col-xs-4 pl5"><input name="cnbankaccount" value="'.$_REQUEST['cnbankaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
						</div>
						</div>
		            </div>

		            <div class="form-group">
			            <label class="control-label col-sm-2">Rate Premium<span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
						<div class="row">
		                	<div class="col-xs-6 pr5">
		                		<select name="typerate" class="form-control" required>
		                        <option value="">Select Type Rate</option>
		                        <option value="Decrease"'._selected($_REQUEST["typerate"], "Decrease").'>Decrease</option>
		                        <option value="Flat"'._selected($_REQUEST["typerate"], "Flat").'>Flat</option>
		                        </select>
							</div>
		                	<div class="col-xs-6 pr5">
		                		<select name="byrate" class="form-control" required>
		                        <option value="">Select By Rate</option>
		                        <option value="Age"'._selected($_REQUEST["byrate"], "Age").'>Age</option>
		                        <option value="Table"'._selected($_REQUEST["byrate"], "Table").'>Table</option>
		                        </select>
							</div>
						</div>
						</div>
		            </div>

		            <div class="form-group">
		            <label class="col-sm-2 control-label">Calculated Rate <span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
		                    <span class="radio custom-radio custom-radio-primary">
		                    	<input type="radio"'.pilih($_REQUEST['ratecalculate'], "100").' name="ratecalculate" id="customradio1" value="100" required><label for="customradio1">&nbsp;&nbsp;100 (persen)&nbsp;&nbsp;</label>
		                    	<input type="radio"'.pilih($_REQUEST['ratecalculate'], "1000").' name="ratecalculate" id="customradio2" value="1000" required><label for="customradio2">&nbsp;&nbsp;1000 (permil)</label>
		                    </span>
						</div>
					</div>

		            <div class="form-group">
			            <label class="control-label col-sm-2">Formula Refund<span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
		                    <span class="radio custom-radio custom-radio-primary">
		                    	<input type="radio"'.pilih($_REQUEST['raterefund'], "Table").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio3" value="Table" required><label for="customradio3">&nbsp;&nbsp;by Table Rate Refund&nbsp;&nbsp;</label>
		                    	<input type="radio"'.pilih($_REQUEST['raterefund'], "Percentage").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio4" value="Percentage" required><label for="customradio4">&nbsp;&nbsp;by Percentage</label>
			                    <div id="ifYes" '.($_REQUEST["raterefund"]=="customradio3" ? " style=\"display:block\"":" style=\"display:none\"").' required>
								<div class="row mb5"><div class="col-sm-12"><input name="percentageRefund" class="form-control" value="'.$_REQUEST['percentageRefund'].'" data-parsley-type="number" type="text" placeholder="Percentage Refund"></div></div>
								</div>
		                    </span>
						</div>
		            </div>

		            <div class="form-group">
			            <label class="control-label col-sm-2">Formula Claim<span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
		                    <span class="radio custom-radio custom-radio-primary">
		                    	<input type="radio"'.pilih($_REQUEST['rateklaim'], "Table").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio5" value="Table" required><label for="customradio5">&nbsp;&nbsp;by Table Rate Claim&nbsp;&nbsp;</label>
		                    	<input type="radio"'.pilih($_REQUEST['rateklaim'], "Percentage").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio6" value="Percentage" required><label for="customradio6">&nbsp;&nbsp;by Percentage</label>
			                    <div id="ifAsik" '.($_REQUEST["raterefund"]=="customradio6" ? " style=\"display:block\"":" style=\"display:none\"").' required>
								<div class="row mb5"><div class="col-sm-12"><input name="percentageClaim" class="form-control" value="'.$_REQUEST['percentageClaim'].'" data-parsley-type="number" type="text" placeholder="Percentage Claim"></div></div>
								</div>
		                    </span>
						</div>
		            </div>

					<div class="form-group">
		            	<label class="col-sm-2 control-label">Last Day Covered <span class="text-danger">*</span></label>
		                <div class="col-sm-2"><input type="text" name="bs-touchspin-basic" value="'.$_REQUEST['bs-touchspin-basic'].'" placeholder="Day" required></div>
		            </div>

					<div class="form-group">
		            	<label class="col-sm-2 control-label">W.P.C <span class="text-danger">*</span></label>
		                <div class="col-sm-2"><input type="text" name="bs-touchspin-wpc" value="'.$_REQUEST['bs-touchspin-wpc'].'" placeholder="Day" required></div>
		            </div>

		            <div class="form-group">
			            <label class="control-label col-sm-2">Minimum Premium</label>
		            	<div class="col-sm-10">
						<div class="row">
		                    <div class="col-md-12"><input type="text" name="minpremi" class="form-control" data-parsley-type="number" value="'.$_REQUEST['minpremi'].'" placeholder="Minimum Premium" required/></div>
						</div>
						</div>
		            </div>

		            <div class="form-group">
			            <label class="control-label col-sm-2">Admin Fee</label>
		            	<div class="col-sm-10">
						<div class="row">
		                    <div class="col-md-12"><input type="text" name="adminfee" class="form-control" data-parsley-type="number" value="'.$_REQUEST['adminfee'].'" placeholder="Admin Fee" required/></div>
						</div>
						</div>
		            </div>

					<div class="form-group">
		            	<label class="col-sm-2 control-label">Discount</label>
		                <div class="col-sm-3"><input type="text" name="bs-touchspin-discount" value="'.$_REQUEST['bs-touchspin-discount'].'" placeholder="Discount"></div>
		            </div>

					<div class="form-group">
		            	<label class="col-sm-2 control-label">PPN</label>
		                <div class="col-sm-3"><input type="text" name="bs-touchspin-ppn" value="'.$_REQUEST['bs-touchspin-ppn'].'" placeholder="PPN"></div>
		            </div>

					<div class="form-group">
		            	<label class="col-sm-2 control-label">PPh</label>
		                <div class="col-sm-3"><input type="text" name="bs-touchspin-pph" value="'.$_REQUEST['bs-touchspin-pph'].'" placeholder="PPh"></div>
		            </div>

		            <div class="form-group">
		            <label class="col-sm-2 control-label">Free Covered <span class="text-danger">*</span></label>
		            	<div class="col-sm-10">
		                    <span class="radio custom-radio custom-radio-primary">
		                    	<input type="radio"'.pilih($_REQUEST['freecover'], "Y").' name="freecover" id="customradio7" value="Y" required><label for="customradio7">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
		                    	<input type="radio"'.pilih($_REQUEST['freecover'], "T").' name="freecover" id="customradio8" value="T" required><label for="customradio8">&nbsp;&nbsp;No</label>
		                    </span>
						</div>
					</div>
					<div class="form-group">
		            	<label class="col-sm-2 control-label">Share <span class="text-danger">*</span></label>
		                <div class="col-sm-2"><input type="text" name="bs-touchspin-shareasuransi" value="'.$_REQUEST['bs-touchspin-shareasuransi'].'" placeholder="Share" required></div>
		            </div>
					<div class="form-group">
						<label class="col-sm-2 control-label">File Policy<span class="text-danger">*</span></label>
		                <div class="col-sm-10"><input type="file" name="filePKS" accept="application/pdf" required></div>
					</div>

				</div>
			<div class="panel-footer"><input type="hidden" name="met" value="savemepolis">'.BTN_SUBMIT.'</div>
			</form>
		</div>
		</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
/*
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
	<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
*/
	;
	break;

	case "poledt":
$polis =mysql_fetch_array($database->doQuery('SELECT * FROM ajkpolisasuransi WHERE id="'.$thisEncrypter->decode($_REQUEST['pid']).'"'));

echo '<div class="page-header-section"><h2 class="title semibold">Policy Insurance</h2></div>
	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=ins&er=policy">'.BTN_BACK.'</a></div></div>
	</div>';
if ($_REQUEST['met']=="edmepolis") {

	if ($_FILES['filePKS']['name']) {
		$nama_file =  'PKS_INS_'.$futgl1.'_'.$_REQUEST['pcompany'].'_'.$xidPol.'_'.$_FILES['filePKS']['name'];	//NAMAFILE {type_idcost_idnomorpolisauto_namafile}
		$sourceFILE = $_FILES['filePKS']['tmp_name'];
		$direktori = '../'.$PathDokumen.'/'.$nama_file;
		move_uploaded_file($sourceFILE,$direktori);
		$insPKS = 'filepks="'.$nama_file.'",';
	}else{
		$insPKS = '';
	}
	$metPolicyNew = $database->doQuery('UPDATE ajkpolisasuransi SET idcost="'.$_REQUEST['coPartner'].'",
																	idproduk="'.$_REQUEST['coProduct'].'",
																	idas="'.$_REQUEST['pcompany'].'",
																 	policymanual="'.strtoupper($_REQUEST['manualpolicy']).'",
																 	produk="'.strtoupper($_REQUEST['productname']).'",
																 	typerate="'.$_REQUEST['typerate'].'",
																 	byrate="'.$_REQUEST['byrate'].'",
																 	calculatedrate="'.$_REQUEST['ratecalculate'].'",
																 	refundrate="'.$_REQUEST['raterefund'].'",
																 	refundpercentage="'.$_REQUEST['percentageRefund'].'",
																 	klaimrate="'.$_REQUEST['rateklaim'].'",
																 	klaimpercentage="'.$_REQUEST['percentageClaim'].'",
																 	start_date="'._convertDate2($_REQUEST['datefrom']).'",
																 	end_date="'._convertDate2($_REQUEST['dateto']).'",
																 	lastdayinsurance="'.$_REQUEST['bs-touchspin-basic'].'",
																 	wpc="'.$_REQUEST['bs-touchspin-wpc'].'",
																 	plafondstart="'.$_REQUEST['plafondfrom'].'",
																 	plafondend="'.$_REQUEST['plafondto'].'",
																 	minimumpremi="'.$_REQUEST['minpremi'].'",
																 	agestart="'.$_REQUEST['agefrom'].'",
																 	ageend="'.$_REQUEST['ageto'].'",
																 	shareins="'.$_REQUEST['bs-touchspin-shareins'].'",
																 	brokrage="'.$_REQUEST['bs-touchspin-brokrage'].'",
																 	adminfee="'.$_REQUEST['adminfee'].'",
																 	diskon="'.$_REQUEST['bs-touchspin-discount'].'",
																 	ppn="'.$_REQUEST['bs-touchspin-ppn'].'",
																 	pph="'.$_REQUEST['bs-touchspin-pph'].'",
																 	bankdebitnote="'.$_REQUEST['dnbankname'].'",
																 	bankdebitnotecabang="'.$_REQUEST['dnbankbranch'].'",
																 	bankdebitnoteaccount="'.$_REQUEST['dnbankaccount'].'",
																 	bankcreditnote="'.$_REQUEST['cnbankname'].'",
																 	bankcreditnotecabang="'.$_REQUEST['cnbankbranch'].'",
																 	bankcreditnoteaccount="'.$_REQUEST['cnbankaccount'].'",
																 	freecover="'.$_REQUEST['freecover'].'",
																 	shareproduk="'.$_REQUEST['bs-touchspin-shareasuransi'].'",
																 	setphoto="'.$_REQUEST['uploadphoto'].'",
																 	setktp="'.$_REQUEST['uploadktp'].'",
																 	'.$insPKS.'
																 	update_by="'.$q['id'].'",
																 	update_date="'.$futgl.'"
										WHERE id="'.$polis['id'].'"');

$metnotif .= '<meta http-equiv="refresh" content="2; url=ajk.php?re=ins&er=policy">
				<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Success!</strong> Edit Policy Insurance number '.$polis['policyauto'].'.
			</div>';
}

echo '<script type="text/javascript">
	function ohYesOhNo() {	if (document.getElementById("customradio4").checked) {	document.getElementById("ifYes").style.display = "block";	}	else {	document.getElementById("ifYes").style.display = "none";	}	}
	</script>
	<script type="text/javascript">
	function SikAsik() {	if (document.getElementById("customradio6").checked) {	document.getElementById("ifAsik").style.display = "block";	}	else {	document.getElementById("ifAsik").style.display = "none";	}	}
	</script>
	<div class="row">
	'.$metnotif.'
		<div class="col-md-12">
		<form method="post" class="panel panel-color-top panel-default form-horizontal" action="#" data-parsley-validate enctype="multipart/form-data">
			<div class="panel-heading"><h3 class="panel-title">Edit Policy Insurance</h3></div>
				<div class="panel-body">
					<div class="form-group">
					<label class="col-sm-2 control-label">Broker <span class="text-danger">*</span></label>
						<div class="col-sm-10">
						<select name="coBroker" class="form-control" onChange="mametBrokerRateIns(this);" required>
						            		<option value="">Select Broker</option>';
		$metCoBroker = $database->doQuery('SELECT id, name FROM ajkcobroker WHERE del IS NULL '.$q_.' ORDER BY name ASC');
		while ($metCoBroker_ = mysql_fetch_array($metCoBroker)) {
			echo '<option value="'.$metCoBroker_['id'].'"'._selected($polis['idbroker'], $metCoBroker_['id']).'>'.$metCoBroker_['name'].'</option>';
		}
		echo '</select>
					    </div>
				    </div>
				<div class="form-group">
				<label class="col-sm-2 control-label">Partner <span class="text-danger">*</span></label>
					<div class="col-sm-10">
					<select name="coPartner" class="form-control" required>
						            		<option value="">Select Partner</option>';
$metCoPartner = $database->doQuery('SELECT id, idc, name FROM ajkclient WHERE del IS NULL AND idc="'.$polis['idbroker'].'" ORDER BY name ASC');
while ($metCoPartner_ = mysql_fetch_array($metCoPartner)) {
echo '<option value="'.$metCoPartner_['id'].'"'._selected($polis['idcost'], $metCoPartner_['id']).'>'.$metCoPartner_['name'].'</option>';
}
echo '</select>
				    </div>
			    </div>
		    <div class="form-group">
		       	<label class="col-lg-2 control-label">Product<strong class="text-danger"> *</strong></label>
			       	<div class="col-lg-10">
				<select name="coProduct" class="form-control" required>
					            		<option value="">Select Product</option>';
$metCoProduct = $database->doQuery('SELECT * FROM ajkpolis WHERE del IS NULL AND idcost="'.$polis['idcost'].'" ORDER BY produk ASC');
while ($metCoProduct_ = mysql_fetch_array($metCoProduct)) {
echo '<option value="'.$metCoProduct_['id'].'"'._selected($polis['idcost'], $metCoProduct_['id']).'>'.$metCoProduct_['produk'].'</option>';
}
echo '</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Insurance <span class="text-danger">*</span></label>
					<div class="col-sm-10">
			        <select name="pcompany" class="form-control" required>
			        	<option value="">Select Insurance</option>';
$metInsurance = $database->doQuery('SELECT id, name FROM ajkinsurance WHERE del IS NULL AND idc="'.$polis['idbroker'].'" ORDER BY name ASC');
while ($metInsurance_ = mysql_fetch_array($metInsurance)) {
echo '<option value="'.$metInsurance_['id'].'"'._selected($polis['idas'], $metInsurance_['id']).'>'.$metInsurance_['name'].'</option>';
}
echo '</select>
	       	</div>
	   	</div>
		<div class="form-group">
		<label class="control-label col-sm-2">Policy Number</label>
			<div class="col-sm-10">
		    <div class="row mb5"><div class="col-sm-12"><input name="autopolicy" value="'.$polis['policyauto'].'" type="text" class="form-control" placeholder="Agreement System" disabled></div></div>
			<div class="row mb5"><div class="col-sm-12"><input name="manualpolicy" value="'.$polis['policymanual'].'" type="text" class="form-control" placeholder="Policy Manual"></div></div>
		    </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Date of Policy <span class="text-danger">*</span></label>
		    <div class="col-sm-10">
		    	<div class="row">
		        <div class="col-md-6"><input type="text" name="datefrom" class="form-control" id="datepicker-from" value="'.$polis['start_date'].'" placeholder="From" required/></div>
		        <div class="col-md-6"><input type="text" name="dateto" class="form-control" id="datepicker-to" value="'.$polis['end_date'].'" placeholder="to" required/></div>
				</div>
			</div>
		</div>
	    <div class="form-group">
	        <label class="control-label col-sm-2">Bank for Debitnote <span class="text-danger">*</span></label>
	          	<div class="col-sm-10">
					<div class="row">
	                	<div class="col-xs-4 pr5"><input name="dnbankname" value="'.$polis['bankdebitnote'].'" type="text" class="form-control" placeholder="Bank" required></div>
	                	<div class="col-xs-4 pc5"><input name="dnbankbranch" value="'.$polis['bankdebitnotecabang'].'" type="text" class="form-control" placeholder="Branch" required></div>
	                    <div class="col-xs-4 pl5"><input name="dnbankaccount" value="'.$polis['bankdebitnoteaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
					</div>
				</div>
	          </div>
				            <div class="form-group">
					            <label class="control-label col-sm-2">Bank for Creditnote <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
								<div class="row">
				                	<div class="col-xs-4 pr5"><input name="cnbankname" value="'.$polis['bankcreditnote'].'" type="text" class="form-control" placeholder="Bank" required></div>
				                	<div class="col-xs-4 pc5"><input name="cnbankbranch" value="'.$polis['bankcreditnotecabang'].'" type="text" class="form-control" placeholder="Branch" required></div>
				                    <div class="col-xs-4 pl5"><input name="cnbankaccount" value="'.$polis['bankcreditnoteaccount'].'" type="text" data-parsley-type="number" class="form-control" placeholder="Account Number" required></div>
								</div>
								</div>
				            </div>

				            <div class="form-group">
					            <label class="control-label col-sm-2">Rate Premium<span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
								<div class="row">
				                	<div class="col-xs-6 pr5">
				                		<select name="typerate" class="form-control" required>
				                        <option value="">Select Type Rate</option>
				                        <option value="Decrease"'._selected($polis["typerate"], "Decrease").'>Decrease</option>
				                        <option value="Flat"'._selected($polis["typerate"], "Flat").'>Flat</option>
				                        </select>
									</div>
				                	<div class="col-xs-6 pr5">
				                		<select name="byrate" class="form-control" required>
				                        <option value="">Select By Rate</option>
				                        <option value="Age"'._selected($polis["byrate"], "Age").'>Age</option>
				                        <option value="Table"'._selected($polis["byrate"], "Table").'>Table</option>
				                        </select>
									</div>
								</div>
								</div>
				            </div>

				            <div class="form-group">
				            <label class="col-sm-2 control-label">Calculated Rate <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['calculatedrate'], "100").' name="ratecalculate" id="customradio1" value="100" required><label for="customradio1">&nbsp;&nbsp;100 (persen)&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['calculatedrate'], "1000").' name="ratecalculate" id="customradio2" value="1000" required><label for="customradio2">&nbsp;&nbsp;1000 (permil)</label>
				                    </span>
								</div>
							</div>

				            <div class="form-group">
					            <label class="control-label col-sm-2">Formula Refund<span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['refundrate'], "Table").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio3" value="Table" required><label for="customradio3">&nbsp;&nbsp;by Table Rate Refund&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['refundrate'], "Percentage").' name="raterefund" onclick="javascript:ohYesOhNo();" id="customradio4" value="Percentage" required><label for="customradio4">&nbsp;&nbsp;by Percentage</label>
					                    <div id="ifYes" '.($polis["raterefund"]=="customradio3" ? " style=\"display:block\"":" style=\"display:none\"").' required>
										<div class="row mb5"><div class="col-sm-12"><input name="percentageRefund" class="form-control" value="'.$polis['refundpercentage'].'" data-parsley-type="number" type="text" placeholder="Percentage Refund"></div></div>
										</div>
				                    </span>
								</div>
				            </div>

				            <div class="form-group">
					            <label class="control-label col-sm-2">Formula Claim<span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['klaimrate'], "Table").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio5" value="Table" required><label for="customradio5">&nbsp;&nbsp;by Table Rate Claim&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['klaimrate'], "Percentage").' name="rateklaim" onclick="javascript:SikAsik();" id="customradio6" value="Percentage" required><label for="customradio6">&nbsp;&nbsp;by Percentage</label>
					                    <div id="ifAsik" '.($polis["raterefund"]=="customradio6" ? " style=\"display:block\"":" style=\"display:none\"").' required>
										<div class="row mb5"><div class="col-sm-12"><input name="percentageClaim" class="form-control" value="'.$polis['klaimpercentage'].'" data-parsley-type="number" type="text" placeholder="Percentage Claim"></div></div>
										</div>
				                    </span>
								</div>
				            </div>

							<div class="form-group">
				            	<label class="col-sm-2 control-label">Last Day Covered <span class="text-danger">*</span></label>
				                <div class="col-sm-2"><input type="text" name="bs-touchspin-basic" value="'.$polis['lastdayinsurance'].'" placeholder="Day" required></div>
				            </div>

							<div class="form-group">
				            	<label class="col-sm-2 control-label">W.P.C <span class="text-danger">*</span></label>
				                <div class="col-sm-2"><input type="text" name="bs-touchspin-wpc" value="'.$polis['wpc'].'" placeholder="Day" required></div>
				            </div>

				            <!--
				            <div class="form-group">
					            <label class="control-label col-sm-2">Plafond <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
								<div class="row">
				                    <div class="col-md-6"><input type="text" name="plafondfrom" class="form-control" data-parsley-type="number" value="'.$polis['plafondstart'].'" placeholder="From" required/></div>
				                    <div class="col-md-6"><input type="text" name="plafondto" class="form-control" data-parsley-type="number" value="'.$polis['plafondend'].'" placeholder="to" required/></div>
								</div>
								</div>
				            </div>

				            <div class="form-group">
					            <label class="control-label col-sm-2">Age <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
								<div class="row">
				                    <div class="col-md-6"><input type="text" name="agefrom" class="form-control" data-parsley-type="number" value="'.$polis['agestart'].'" placeholder="From" required/></div>
				                    <div class="col-md-6"><input type="text" name="ageto" class="form-control" data-parsley-type="number" value="'.$polis['ageend'].'" placeholder="to" required/></div>
								</div>
								</div>
				            </div>
				            -->

				            <div class="form-group">
					            <label class="control-label col-sm-2">Minimum Premium</label>
				            	<div class="col-sm-10">
								<div class="row">
				                    <div class="col-md-12"><input type="text" name="minpremi" class="form-control" data-parsley-type="number" value="'.$polis['minimumpremi'].'" placeholder="Minimum Premium" required/></div>
								</div>
								</div>
				            </div>

				            <div class="form-group">
					            <label class="control-label col-sm-2">Admin Fee</label>
				            	<div class="col-sm-10">
								<div class="row">
				                    <div class="col-md-12"><input type="text" name="adminfee" class="form-control" data-parsley-type="number" value="'.$polis['adminfee'].'" placeholder="Admin Fee" required/></div>
								</div>
								</div>
				            </div>

							<div class="form-group">
				            	<label class="col-sm-2 control-label">Discount</label>
				                <div class="col-sm-3"><input type="text" name="bs-touchspin-discount" value="'.$polis['diskon'].'" placeholder="Discount"></div>
				            </div>

							<div class="form-group">
				            	<label class="col-sm-2 control-label">PPN</label>
				                <div class="col-sm-3"><input type="text" name="bs-touchspin-ppn" value="'.$polis['ppn'].'" placeholder="PPN"></div>
				            </div>

							<div class="form-group">
				            	<label class="col-sm-2 control-label">PPh</label>
				                <div class="col-sm-3"><input type="text" name="bs-touchspin-pph" value="'.$polis['pph'].'" placeholder="PPh"></div>
				            </div>

				            <div class="form-group">
				            <label class="col-sm-2 control-label">Free Covered <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['freecover'], "Y").' name="freecover" id="customradio7" value="Y" required><label for="customradio7">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['freecover'], "T").' name="freecover" id="customradio8" value="T" required><label for="customradio8">&nbsp;&nbsp;No</label>
				                    </span>
								</div>
							</div>

				            <div class="form-group">
				            <label class="col-sm-2 control-label">Upload Photo <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['setphoto'], "Y").' name="uploadphoto" id="customradio9" value="Y" required><label for="customradio9">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['setphoto'], "T").' name="uploadphoto" id="customradio10" value="T" required><label for="customradio10">&nbsp;&nbsp;No</label>
				                    </span>
								</div>
							</div>

				            <div class="form-group">
				            <label class="col-sm-2 control-label">Upload KTP <span class="text-danger">*</span></label>
				            	<div class="col-sm-10">
				                    <span class="radio custom-radio custom-radio-primary">
				                    	<input type="radio"'.pilih($polis['setktp'], "Y").' name="uploadktp" id="customradio11" value="Y" required><label for="customradio11">&nbsp;&nbsp;Yes&nbsp;&nbsp;</label>
				                    	<input type="radio"'.pilih($polis['setktp'], "T").' name="uploadktp" id="customradio12" value="T" required><label for="customradio12">&nbsp;&nbsp;No</label>
				                    </span>
								</div>
							</div>
					<div class="form-group">
		            	<label class="col-sm-2 control-label">Share <span class="text-danger">*</span></label>
		                <div class="col-sm-2"><input type="text" name="bs-touchspin-shareasuransi" value="'.$polis['shareproduk'].'" placeholder="Share" required></div>
		            </div>
							<div class="form-group">
							<label class="col-sm-2 control-label">File Policy </label>
		                		<div class="col-sm-10"><input type="file" name="filePKS" accept="application/pdf"></div>
							</div>

						</div>
					<div class="panel-footer"><input type="hidden" name="met" value="edmepolis">'.BTN_SUBMIT.'</div>
					</form>
				</div>
				</div>';
echo "<script language=\"JavaScript\" src=\"plugins/reData.js\"></script>";
echo '<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
/*
echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
*/
	;
	break;

	case "insview":
echo '<div class="page-header-section"><h2 class="title semibold">Insurance</h2></div>
		<div class="page-header-section">
		<div class="toolbar"><a href="ajk.php?re=ins">'.BTN_BACK.'</a></div>
		</div>
	</div>';
$metComp = mysql_fetch_array($database->doQuery('SELECT * FROM ajkinsurance WHERE id="'.$thisEncrypter->decode($_REQUEST['cid']).'"'));
$metCdebitur = mysql_fetch_array($database->doQuery('SELECT Count(ajkpeserta.nama) AS jNama, SUM(ajkpeserta.totalpremi) AS jTP, SUM(ajkpeserta.plafond) AS jTPlafond FROM ajkpeserta WHERE ajkpeserta.idclient = "'.$metComp['id'].'" AND ajkpeserta.iddn != "" AND ajkpeserta.del IS NULL'));
$metCdebitnote = mysql_fetch_array($database->doQuery('SELECT Count(ajkdebitnote.nomordebitnote) AS jDN FROM ajkdebitnote WHERE ajkdebitnote.idclient = "'.$metComp['id'].'" AND ajkdebitnote.del IS NULL'));
$metCcreditnote = mysql_fetch_array($database->doQuery('SELECT Count(ajkcreditnote.nomorcreditnote) AS jCN, SUM(ajkcreditnote.nilaiclaimclient) AS jNilaiCN FROM ajkcreditnote WHERE ajkcreditnote.idclient = "'.$metComp['id'].'" AND ajkcreditnote.tipeklaim !="Batal" AND ajkcreditnote.del IS NULL'));
echo '<div class="row">
    	<div class="col-lg-3">
        	<ul class="list-group list-group-tabs">
            	<li class="list-group-item active"><a href="#profile" data-toggle="tab"><i class="ico-office mr5"></i> Profile Company</a></li>
            	<li class="list-group-item"><a href="#Agreement" data-toggle="tab"><i class="ico-archive2 mr5"></i> Agreement</a></li>
            	<!--<li class="list-group-item"><a href="#debitur" data-toggle="tab"><i class="ico-shield3 mr5"></i> Debitur</a></li>
            	<li class="list-group-item"><a href="#regional" data-toggle="tab"><i class="ico-globe mr5"></i> Regional</a></li>
            	<li class="list-group-item"><a href="#account" data-toggle="tab"><i class="ico-user mr5"></i> Account</a></li>-->
            </ul>
            <!--
			<hr>
            <ul class="nav nav-section nav-justified mt15">
            <li><div class="section"><h4 class="nm semibold">'.duit($metCdebitur['jNama']).'</h4><p class="nm text-muted">Debitur</p></div></li>
            <li><div class="section"><h4 class="nm semibold">'.duit($metCdebitnote['jDN']).'</h4><p class="nm text-muted">Debitnote</p></div></li>
            <li><div class="section"><h4 class="nm semibold">'.duit($metCcreditnote['jCN']).'</h4><p class="nm text-muted">Creditnote</p></div></li>
            </ul>
            <hr>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Plafond</h5>
                    <h4 class="semibold mb0">'.duit($metCdebitur['jTPlafond']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-primary"><i class="ico-money"></i></a>
            </div>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Premium</h5>
                    <h4 class="semibold mb0">'.duit($metCdebitur['jTP']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-success"><i class="ico-money"></i></a>
            </div>
            <div class="widget panel list-group list-group-tabs">
                <ul class="list-unstyled panel-body" style="z-index:2;">
                	<li class="text-center">
                    <h5 class="semibold mb0 nm text-muted">Claim</h5>
                    <h4 class="semibold mb0">'.duit($metCcreditnote['jNilaiCN']).'</h4>
                    </li>
                </ul>
                <a href="javascript:void(0);" class="panel-ribbon panel-ribbon-danger"><i class="ico-money"></i></a>
            </div>
            -->
        </div>

		<div class="col-lg-9">
        	<div class="tab-content">
            	<div class="tab-pane active" id="profile">
                <form class="panel form-horizontal form-bordered" name="form-profile">
					<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4></li>
								</ul>
							</div>
                        </div>
						<div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12">
								<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Address</a></p></div></div>
                                <div class="text-default"><p>'.$metComp['address1'].'<br />'.$metComp['address2'].'<br />'.$metComp['city'].'<br />'.$metComp['postcode'].'</p></div>
								<div class="table-layout mt1 mb0"><div class="col-sm-12"><p class="meta nm"><a href="javascript:void(0);">Phone</a></p></div></div>
                                <div class="text-default"><p>'.$metComp['phonehp'].'<br />'.$metComp['phoneoffice'].'<br />'.$metComp['phonefax'].'</p></div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>

                <div class="tab-pane" id="Agreement">
				<form class="panel form-horizontal form-bordered" name="form-Agreement">
                	<div class="panel-body pt0 pb0">
                    	<div class="form-group header bgcolor-default">
                        	<div class="col-md-12">
            					<ul class="list-table">
            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
								<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Agreement / Product</h4></li>
								</ul>
							</div>
						</div>';

$metAgreement = $database->doQuery('SELECT ajkclient.`name` AS namabroker,
										   ajkpolis.produk,
										   ajkpolisasuransi.filepks,
										   ajkpolisasuransi.status
									FROM ajkpolisasuransi
									INNER JOIN ajkclient ON ajkpolisasuransi.idcost = ajkclient.id
									INNER JOIN ajkpolis ON ajkpolisasuransi.idproduk = ajkpolis.id
									WHERE ajkpolisasuransi.idas = "'.$metComp['id'].'"');
		while ($metAgreement_ = mysql_fetch_array($metAgreement)) {
			if ($metAgreement_['status']=="Aktif") {
				$agreestatus= '<button type="button" class="btn btn-teal mb5"><i class="ico-file"></i> Active</button>';
			}else{
				$agreestatus= '<button type="button" class="btn btn-danger mb5"><i class="ico-cancel"></i> Not Active</button>';
			}

			if ($metAgreement_['filepks']=="") {
				$agreefile= '<a href="#"><button type="button" class="btn btn-default mb5"><i class="ico-file-pdf"></i></button></a>';
			}else{
				$agreefile= '<a href="../'.$PathDokumen.''.$metAgreement_['filepks'].'" target="_blank"><button type="button" class="btn btn-primary mb5"><i class="ico-file-pdf"></i></button></a>';
			}

			if ($metAgreement_['filedeklarasi']=="") {
				$agreedeklarasi= '<a href="#"><button type="button" class="btn btn-default mb5"><i class="ico-file-excel"></i></button></a>';
			}else{
				$agreedeklarasi= '<a href="../'.$PathDokumen.''.$metAgreement_['filepks'].'" target="_blank"><button type="button" class="btn btn-success mb5"><i class="ico-file-excel"></i></button></a>';
			}
			$rAgree .= '<div class="table-layout mt1 mb0">
							<div class="col-sm-4"><p class="meta nm"><a href="javascript:void(0);">'.$metAgreement_['namabroker'].'</a></p></div>
							<div class="col-sm-5"><p class="meta nm"><a href="javascript:void(0);">'.$metAgreement_['produk'].'</a></p></div>
							<div class="col-sm-1 text-center"><p class="meta nm">'.$agreestatus.'</p></div>
							<div class="col-sm-2 text-right">'.$agreefile.'</div>
						</div>
						';
		}
		echo '<div class="form-group">
		                            <div class="col-xs-12 col-sm-12 col-md-12">
										'.$rAgree.'
		                            </div>
		                        </div>

			                </div>
		                </form>
		                </div>

		                <div class="tab-pane" id="debitur">
						<form class="panel form-horizontal form-bordered" name="form-debitur">
		                	<div class="panel-body pt0 pb0">
		                    	<div class="form-group header bgcolor-default">
		                        	<div class="col-md-12">
		            					<ul class="list-table">
		            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
										<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Debitur</h4></li>
										</ul>
									</div>
								</div>';
		$metDebitur = $database->doQuery('SELECT ajkpeserta.statusaktif,
										Count(ajkpeserta.nama) AS jDebitur
								  FROM ajkpeserta
								  WHERE ajkpeserta.iddn != "" AND ajkpeserta.idclient = '.$metComp['id'].' AND ajkpeserta.del IS NULL
								  GROUP BY ajkpeserta.statusaktif');
		while ($metDebitur_ = mysql_fetch_array($metDebitur)) {
			if ($metDebitur_['statusaktif']=="Batal" OR $metDebitur_['statusaktif']=="Reject") {
				$setColor = "panel-inverse";
			}elseif ($metDebitur_['statusaktif']=="Maturity") {
				$setColor = "panel-default";
			}elseif ($metDebitur_['statusaktif']=="Approve") {
				$setColor = "panel-primary";
			}elseif ($metDebitur_['statusaktif']=="Pending" OR $metDebitur_['statusaktif']=="Refund" OR $metDebitur_['statusaktif']=="Upload" OR $metDebitur_['statusaktif']=="Request") {
				$setColor = "panel-warning";
			}elseif ($metDebitur_['statusaktif']=="Claim" OR $metDebitur_['statusaktif']=="Lapse") {
				$setColor = "panel-danger";
			}else{
				$setColor = "panel-success";
			}
			$rDebitur .='<div class="col-xs-6 col-sm-6 col-md-6">
						<div class="panel '.$setColor.'">
			            <div class="panel-heading"><h3 class="panel-title"><i class="ico-stats-up mr5"></i> '.$metDebitur_['statusaktif'].'</h3></div>
        		<div class="indicator"><span class="spinner"></span></div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
            	<div class="panel '.$setColor.'">
			            <div class="panel-heading"><h3 class="panel-title"><i class="ico-people mr5"></i> '.$metDebitur_['jDebitur'].' Debitur</h3></div>
        		<div class="indicator"><span class="spinner"></span></div>
                </div>
            </div>';
		}
		echo '<div class="form-group">
		                            '.$rDebitur.'
		                        </div>
			                </div>
		                </form>
		                </div>

		                <div class="tab-pane" id="regional">
						<form class="panel form-horizontal form-bordered" name="form-regional">
		                	<div class="panel-body pt0 pb0">
		                    	<div class="form-group header bgcolor-default">
		                        	<div class="col-md-12">
		            					<ul class="list-table">
		            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
										<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Regional</h4></li>
										</ul>
									</div>
								</div>';
		$rRegion = $database->doQuery('SELECT ajkregional.`name` AS regional,
									  ajkarea.`name` AS area,
									  ajkcabang.`name` AS cabang
							   FROM ajkregional
							   INNER JOIN ajkarea ON ajkregional.er = ajkarea.idreg
							   INNER JOIN ajkcabang ON ajkarea.er = ajkcabang.idarea
							   WHERE ajkregional.idclient = '.$metComp['id'].' AND
							   		 ajkregional.del IS NULL AND
							   		 ajkarea.del IS NULL AND
							   		 ajkcabang.del IS NULL
							   	ORDER BY regional, cabang ASC');
		while ($rRegion_ = mysql_fetch_array($rRegion)) {
			$metRegion .= '<tr><td align="center">'.++$no.'</td>
						   <td align="center">'.$rRegion_['regional'].'</td>
						   <td align="center">'.$rRegion_['area'].'</td>
						   <td align="center">'.$rRegion_['cabang'].'</td>
					</tr>';
		}
		echo '<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
									<thead>
									<tr><th width="1%">No</th>
										<th>Regional</th>
										<th>Area</th>
										<th>Branch</th>
									</tr>
									</thead>
									<tbody>
									'.$metRegion.'
									</tbody>
									<tfoot>
									<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Regional"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Area"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
									</tr>
									</tfoot></table>
									</div>
									</div>
		                        </div>
			                </div>
		                </form>
		                </div>';
		$rAccount = $database->doQuery('SELECT useraccess.photo,
									   CONCAT(useraccess.firstname," ",useraccess.lastname) AS namauser,
									   useraccess.username,
									   leveluser.nama AS leveluser,
									   useraccess.email,
									   useraccess.supervisor,
									   ajkcabang.`name` AS cabang,
									   useraccess.aktif
								FROM useraccess
								INNER JOIN leveluser ON useraccess.`level` = leveluser.er
								INNER JOIN ajkcabang ON useraccess.branch = ajkcabang.er
								WHERE useraccess.idclient = "'.$metComp['id'].'" AND useraccess.idas IS NULL AND useraccess.del IS NULL
								ORDER BY leveluser ASC');
		while ($rAccount_ = mysql_fetch_array($rAccount)) {
			if ($rAccount_['photo']=="") {
				$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
			}else{
				$logoCOB = '<div class="media-object"><img src="../'.$PathPhoto.''.$rAccount_['photo'].'" alt="" class="img-circle"></div>';
			}

			if ($rAccount_['aktif']=="Y") {
				$metAktifnya = '<span class="label label-primary">Active</span>';
			}else{
				$metAktifnya = '<span class="label label-Danger">Not Active</span>';
			}
			$metstaffspv = mysql_fetch_array($database->doQuery('SELECT id, username FROM useraccess WHERE id="'.$rAccount_['supervisor'].'"'));
			$$metAccount .='<tr><td align="center">'.++$no1.'</td>
							   <td align="center">'.$logoCOB.'</td>
							   <td>'.$rAccount_['namauser'].'</td>
							   <td>'.$rAccount_['username'].'</td>
							   <td align="center">'.$rAccount_['leveluser'].'</td>
							   <td><a href="mailto:'.$metComp['email'].'">'.$rAccount_['email'].'</a></td>
							   <td>'.$rAccount_['cabang'].'</td>
							   <td align="center">'.$metAktifnya.'</td>
							   <td>'.$metstaffspv['username'].'</td>
							</tr>';
		}
		echo '<div class="tab-pane" id="account">
						<form class="panel form-horizontal form-bordered" name="form-account">
		                	<div class="panel-body pt0 pb0">
		                    	<div class="form-group header bgcolor-default">
		                        <div class="col-md-12">
		            					<ul class="list-table">
		            					<li style="width:80px;"><img class="img-circle img-bordered" src="../'.$PathPhoto.''.$metComp['logo'].'" alt="" width="75px"></li>
										<li class="text-left"><h4 class="semibold ellipsis semibold text-primary mt0 mb5">'.$metComp['name'].'</h4><h4 class="semibold ellipsis semibold text-success mt0 mb5">Account</h4></li>
										</ul>
									</div>
								</div>
								<div class="form-group">
		                            <div class="row">
								<div class="col-md-12">
									<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
									<thead>
									<tr><th width="1%">No</th>
										<th>Photo</th>
										<th>Name</th>
										<th>Username</th>
										<th>Level</th>
										<th>Email</th>
										<th>Branch</th>
										<th>Active</th>
										<th>Supervisor</th>
									</tr>
									</thead>
									<tbody>
									'.$$metAccount.'
									</tbody>
									<tfoot>
									<tr><th><input type="hidden" class="form-control" name="search_engine"></th>
										<th><input type="hidden" class="form-control" name="search_engine"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Username"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Level"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Email"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Branch"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Active"></th>
										<th><input type="search" class="form-control" name="search_engine" placeholder="Supervisor"></th>
									</tr>
									</tfoot></table>
									</div>
									</div>
		                        </div>

			                </div>
		                </form>
		                </div>
					</div>
		        </div>
		    </div>';
		;
		break;


	default:
echo '<div class="page-header-section"><h2 class="title semibold">Insurance</h2></div>
      	<div class="page-header-section">
        <div class="toolbar"><a href="ajk.php?re=ins&er=newIns">'.BTN_NEW.'</a></div>
		</div>
      </div>';

//echo '<div class="table-responsive panel-collapse pull out">
echo '<div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">
<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%">No</th>
	<th>Broker</th>
	<th width="1%">Logo</th>
	<th>Insurance</th>
	<th width="30%">Address</th>
	<th width="10%">Option</th>
</tr>
</thead>
<tbody>';
//$metClient = $database->doQuery('SELECT * FROM ajkinsurance WHERE ajkinsurance.del IS NULL ORDER BY ajkinsurance.id DESC');
$metClient = $database->doQuery('SELECT ajkinsurance.id, ajkinsurance.`name`, ajkinsurance.phoneoffice, ajkinsurance.phonefax, ajkinsurance.logo, ajkinsurance.logothumb, ajkcobroker.`name` AS brokername
								 FROM ajkinsurance
								 INNER JOIN ajkcobroker ON ajkinsurance.idc = ajkcobroker.id
								 WHERE ajkinsurance.del IS NULL '.$q__.'
								 ORDER BY ajkinsurance.id DESC');
while ($metClient_ = mysql_fetch_array($metClient)) {
if ($metClient_['logo']=="") {
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.'logo.png" alt="" class="img-circle"></div>';
}else{
	$logoclient = '<div class="media-object"><img src="../'.$PathPhoto.''.$metClient_['logo'].'" alt="" class="img-circle"></div>';
}
echo '<tr><td align="center">'.++$no.'</td>
		  <td>'.$metClient_['brokername'].'</td>
		  <td>'.$logoclient.'</td>
		  <td><a href="ajk.php?re=ins&er=insview&cid='.$thisEncrypter->encode($metClient_['id']).'">'.$metClient_['name'].'</a></td>
		  <td>'.$metClient_['address1'].'</td>
		  <td align="center"><a href="ajk.php?re=ins&op=insedt&cid='.$thisEncrypter->encode($metClient_['id']).'">'.BTN_EDIT.'</a></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="hidden" class="form-control" name="search_engine"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Insurance"></th>
		    <th><input type="search" class="form-control" name="search_engine" placeholder="Address"></th>
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