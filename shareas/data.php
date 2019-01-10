<?php 
	include "../han.php";
	 
	session_start();
	$user = $_SESSION['User'];
	if($user==""){
		header("location:../login");
	}
	$app = "bios";
	$queryuser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$user."'");
	$rowuser = mysql_fetch_array($queryuser);
	$idbro = $rowuser['idbroker'];
	$idclient = $rowuser['idclient'];

	$month = date('F Y');
	$today = date('d/m/Y');

	switch ($_POST['hn']) {
	 	case 'viewshare':
	 		$query = "SELECT ajkinsurance.id,
	 										 name,
	 										 concat(share_persentage,'%')as share_persentage,
	 										 date_format(eff_s,'%d-%m-%Y')as eff_s
								FROM ajkshareins
								INNER JOIN ajkinsurance
								ON ajkinsurance.id = ajkshareins.idinsurance
								WHERE idbroker = '".$idbro."' and
											idclient = '".$idclient."' and
											now() BETWEEN eff_s and eff_e
								ORDER BY ajkinsurance.name";

			$quersum = mysql_fetch_array(mysql_query("SELECT sum(share_persentage)as total_share
																								FROM ajkshareins
																								WHERE idbroker = '".$idbro."' and
																											idclient = '".$idclient."' and
																											now() BETWEEN eff_s and eff_e"));
			$equery = mysql_query($query);
			$no= 1;
			$rshare = "";
			while($share = mysql_fetch_array($equery)){
				$rshare = $rshare.'<tr>
														<td class="text-center" width="1%">'.$no.'</td>
														<td class="text-center">'.$share['name'].'</td>
														<td class="text-center" width="1%">'.$share['share_persentage'].'</td>
														<td class="text-center" width="5%">'.$share['eff_s'].'</td>
														<td class="text-center" width="2%"><a href="javascript:;" class="btn btn-primary btn-sm" onclick="input(\'inputshare\',\''.md5($app.$share['id']).'\');">Edit</a></td>
													</tr>';
				$no++;
			} 
			$foot = '<tr>
								<td colspan="2" class="text-right"><b>Total Share</b></td>
								<td colspan="3" class="text-center"><b>'.$quersum['total_share'].'%</b></td>
							 </tr>';


	 		echo '<div class="panel panel-inverse">
						  <div class="panel-heading">
						    <h4 class="panel-title text-center">Share Asuransi</h4>
						  </div>
						  <div class="panel-body ">
						  	<a href="javascript:;" onclick="input(\'inputshare\');" class="btn btn-success">New</a>
							  <table id="tbl-share" name="tbl-share" class="table table-bordered table-hover" width="100%">
			 						<thead>
			 							<tr class="primary">
			 								<th class="text-center">No</th>
			 								<th class="text-center">Asuransi</th>
			 								<th class="text-center">Persentase</th>
			 								<th class="text-center">Tgl Efektif</th>
			 								<th class="text-center">Option</th>
			 							</tr>
			 						</thead>
									<tbody>
									'.$rshare.'
			 						</tbody>
			 						<tfoot>
			 						'.$foot.'
			 						</tfoot>
		 						</table>
		 					</div>
		 				</div>';
	 	break;

	 	case 'viewsharedashboard':
	 		$query = "SELECT ajkinsurance.id,
	 										 name,
	 										 share_persentage,
	 										 ifnull(persentase_target,0)as persentase_target
								FROM ajkshareins
								INNER JOIN ajkinsurance
								ON ajkinsurance.id = ajkshareins.idinsurance
								INNER JOIN ajksharehis
									 ON ajkshareins.idinsurance = ajksharehis.idinsurance and
											ajkshareins.idclient = ajksharehis.idclient and
											ajkshareins.idbroker = ajksharehis.idbroker and
											ajksharehis.bulan = DATE_FORMAT(now(),'%c') and
											ajksharehis.tahun = DATE_FORMAT(now(),'%Y')								
								WHERE ajkshareins.idbroker = '".$idbro."' and
											ajkshareins.idclient = '".$idclient."' and
											now() BETWEEN eff_s and eff_e
								ORDER BY ajkinsurance.name";

			$no= 1;
			$rshare = "";
			$equery = mysql_query($query);
			while($share = mysql_fetch_array($equery)){
				$targetpersen = $share['persentase_target'];
				if($targetpersen <= 50){
					$flag = "success";
				}elseif($targetpersen > 50 and $targetpersen <= 90){
					$flag = "warning";
				}else{
					$flag = "danger";
				}


				$rshare = $rshare.'<tr>
														<td class="text-center" width="1%">'.$no.'</td>
														<td class="text-center" width="20%">'.$share['name'].'</td>
														<td class="text-center" width="1%">'.$share['share_persentage'].'%</td>
														<td class="text-center" width="10%"><div class="progress"><div class="progress-bar progress-bar-'.$flag.'" style="width:'.$targetpersen.'%"><font color="black">'.$targetpersen.'%</font></div></div</td>
													</tr>';
				$no++;
			} 

	 		echo '<table id="tbl-share" name="tbl-share" class="table" width="100%">
							<tbody>
							'.$rshare.'
	 						</tbody>
 						</table>';
	 	break;

	 	case 'inputtarget':
	 		$qtarget = "SELECT * 
	 								FROM ajktargetpusat 
	 								WHERE bulan = DATE_FORMAT(now(),'%c')";

	 		$target = mysql_fetch_array(mysql_query($qtarget));
		 	echo '<div class="panel panel-inverse">
						  <div class="panel-heading">
						      <h4 class="panel-title text-center">Target Kredit Bulan '.$month.'</h4>
						  </div>
						  <div class="panel-body ">
						  	<form action="#" id="frminput" class="form-horizontal" method="post" enctype="multipart/form-data">

							    <div class="form-group">
						        <label class="control-label col-md-1">Jumlah Kredit</label>
						        <div class="col-md-11">
						          <input id="target_kredit" name="target_kredit" class="form-control" type="text" value="'.$target['target_kredit'].'" placeholder="Target Kredit" required>
						        </div>
							    </div>

							    <div class=" text-center">
							    	<a href="javascript:;" id="btn-update" onclick="simpan(\'frminput\',\'simpantarget\',\'inputtarget\');" class="btn btn-primary">Update</a>
							    </div>					    
						  	</form>
						  </div>
						</div>';	 	
		break;

	 	case 'simpantarget';
	 		$target = str_replace(',', '', $_POST['target_kredit']) ;	
	 		 
	 		$query = "UPDATE ajktargetpusat 
	 							SET target_kredit = '".$target."'
	 							WHERE idbroker = ".$idbro." and
											idclient = ".$idclient." and 
											bulan = DATE_FORMAT(now(),'%c') and 
	 										tahun = DATE_FORMAT(now(),'%Y') ";
	 		 $update = mysql_query($query);
	 		 if($update){
	 		 	echo "success";
	 		 }else{
	 		 	echo mysql_error();
	 		 }
	 	break;

	 	case 'inputshare':
	 		$primary =  $_POST['id'];

	 		if($primary != ""){
				$query = "SELECT ajkinsurance.id,
												 name,
												 share_persentage,
												 date_format(eff_s,'%d-%m-%Y')as eff_s
									FROM ajkshareins
									INNER JOIN ajkinsurance
									ON ajkinsurance.id = ajkshareins.idinsurance
									WHERE idbroker = ".$idbro." and
												idclient = ".$idclient." and 
												now() BETWEEN eff_s and eff_e and
												md5(concat('".$app."',ajkinsurance.id)) = '".$primary."'";				

				$rquery = mysql_fetch_array(mysql_query($query));

				$ins = '<option value="'.$rquery['id'].'" selected>'.$rquery['name'].'</option>';
				$shareins = $rquery['share_persentage'];
	 		}else{
	 			$shareins = "";
		 		$query = "SELECT * 
									FROM ajkinsurance 
									WHERE del is null and
												idC = ".$idclient;
		 		$qasuransi = mysql_query($query);
		 		$ins='<option value="">- Pilih -</option>';
		 		while($rasuransi = mysql_fetch_array($qasuransi)){
		 			$ins = $ins.'<option value="'.$rasuransi['id'].'">'.$rasuransi['name'].'</option>';
		 		}
	 		}

		 	echo '<div class="panel panel-inverse">
						  <div class="panel-heading">
						  	<div class="panel-heading-btn">
						  		<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" onclick="input(\'viewshare\');"><i class="fa fa-times"></i></a>
						  	</div>
						      <h4 class="panel-title text-center">Share Asuransi</h4>
						  </div>
						  <div class="panel-body ">
						  	<form action="#" id="frminput" class="form-horizontal" method="post" enctype="multipart/form-data">

							    <div class="form-group">
						        <label class="control-label col-md-1">Asuransi</label>
						        <div class="col-md-11">
						          <!--<input id="asuransi" name="asuransi" class="form-control" type="text" placeholder="" required>-->
						          <select id="asuransi" name="asuransi" class="form-control">
						           	'.$ins.'
						          </select>
						        </div>
							    </div>
							    <div class="form-group">
						        <label class="control-label col-md-1">Persentase Share</label>
						        <div class="col-md-11">
						          <input id="persentase" name="persentase" class="form-control" type="text" value="'.$shareins.'" placeholder="Persentase Share" required>
						        </div>
							    </div>
							    <div class="form-group">
						        <label class="control-label col-md-1">Tgl Efektif</label>
						        <div class="col-md-11">
						          <input id="tgl_efektif" name="tgl_efektif" class="form-control" type="text" value="'.$today.'" placeholder="Tgl Efektif" required>
						        </div>
							    </div>
							    <div class=" text-center">
							    	<a href="javascript:;" id="btn-update" onclick="simpan(\'frminput\',\'simpanshare\',\'viewshare\');" class="btn btn-primary">Update</a>
							    </div>					    
						  	</form>
						  </div>
						</div>';	 
	 	break;

	 	case 'simpanshare':
	 		$asuransi = $_POST['asuransi'];
	 		$share = $_POST['persentase'];
	 		$effdate = $_POST['tgl_efektif'];

	 		$efdate = explode('/', $effdate);
	 		$tgl_efektif = $efdate[2].'-'.$efdate[1].'-'.$efdate[0];

			$query = 'INSERT INTO ajkshareins
								SET idbroker = "'.$idbro.'",	
										idclient = "'.$idclient.'",
										idinsurance = "'.$asuransi.'",
										share_persentage = "'.$share.'",
										eff_s = "'.$tgl_efektif.'",
										eff_e = "2500-01-01",
										inputby = "'.$user.'",
										inputdate = "'.date('Y-m-d h:i:s').'"';

			$qupdate = "UPDATE ajkshareins 
									SET eff_e = DATE_ADD('".$tgl_efektif."',INTERVAL -1 DAY)
									WHERE idbroker = '".$idbro."' and 
												idclient = '".$idclient."' and 
												idinsurance = '".$asuransi."' 
									ORDER BY inputdate DESC
									LIMIT 1";
			$update = mysql_query($qupdate);
 			if($update){
		 		$insert = mysql_query($query);
		 		if($insert){
		 			echo "success";	
		 		}else{
		 			echo mysql_error();
		 		} 								
 			}else{
				echo mysql_error();
 			}
	 	break;
	 }
?>
