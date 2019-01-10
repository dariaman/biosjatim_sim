<?php
	include "../param.php";
	$deklarasi = AES::decrypt128CBC($_REQUEST['xq'],ENCRYPTION_KEY);

	$value = explode('|', $_REQUEST['namaproduk']); 
	$produk = $value[0];
	$idcabang = $value[1];
	$idasuransi = $value[2];	
	//$produk = $_REQUEST['namaproduk'];


?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user,$namauser,$photo,$logo);
?>

<script>
	function toggle(source) {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(:disabled)');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i] != source)
			checkboxes[i].checked = source.checked;
		}
	}
</script>

<style type="text/css">
	#icheckForm .radio label, #icheckForm .checkbox label {
  	padding-left: 0;
	}
</style>


<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
		_header($user,$namauser,$photo,$logo,$logoklient);
		_sidebar($user,$namauser,'','');
		?>
		<!-- begin #content -->
		<div class="content"  id="content">
			<div class="panel p-30">
				<?php 
					if($deklarasi=="deklarasi"){
				?>
				<!-- end section-container -->
				<h4 class="m-t-0">Deklarasi</h4>
				<div class="section-container section-with-top-border">
			    <form action="?xq=<?php echo AES::encrypt128CBC('view',ENCRYPTION_KEY); ?>" id="deklarasi" class="form-horizontal" method="post" enctype="multipart/form-data">
			    	<?php echo $setlokasi;	?>
         		<!-- <input type="hidden" name="xq" value="<?php //echo $typeuploadnya;	?>"/> -->
						<div class="form-group">
              <label class="control-label col-sm-3">Nama Partner </label>
              <div class="col-sm-6">
              	<label class="control-label "><?php echo $namaklient ?> </label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-3">Nama Produk <span class="text-danger">*</span></label>
              <div class="col-sm-6">
              	<select class="form-control" name="namaproduk">
									<option value="">-- Pilih Produk --</option>
										<?php
											if($cabang != 0){
												$qcabang = ' ajkspk.cabang = "'.$cabang.'" and ';
											}
											//$queryprod = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND del IS NULL");
											$queryprod = mysql_query("SELECT ajkpolis.produk,
																												ajkpolis.id as idproduk,	
																												ajkinsurance.name as nm_asuransi,
																												ajkinsurance.id as idins,
																												ajkcabang.er as idcabang,
																												ajkcabang.name as nm_cabang,
																												count(produk)as cnt
																										FROM ajkspk 
																										INNER JOIN ajkpolis
																										ON ajkpolis.id = ajkspk.idproduk
																										INNER JOIN ajkcabang
																										ON ajkcabang.er = ajkspk.cabang
																										LEFT JOIN ajkinsurance
																										ON ajkinsurance.id = ajkspk.asuransi
																										WHERE idbroker = '".$idbro."' and 
																										idpartner = '".$idclient."' and 
																										ajkcabang.del is null and
																										ajkinsurance.del is null and
																										".$qcabang."
																										ajkspk.del is null and
																										statusspk = 'Aktif' and
																										ajkspk.nomorspk not in (select nomorspk from ajkpeserta_temp where nomorspk is not null and nomorspk != '') and 
																										ajkspk.nomorspk not in (select nomorspk from ajkpeserta where nomorspk is not null and nomorspk != '')
																										GROUP BY ajkpolis.produk,ajkinsurance.name,ajkspk.cabang");
											while($rowprod = mysql_fetch_array($queryprod)){
												//$qspkaktif = mysql_fetch_array(mysql_query("select count(*)as cnt from ajkspk where idproduk = '".$rowprod['id']."' and idbroker = '".$idbro."' and idpartner = '".$idclient."' and statusspk = 'Aktif'"));
												//$idprod = $rowprod['id'];
												//$namaprod = $rowprod['produk'];
												$idprod = $rowprod['idproduk'];
												$prod = $rowprod['produk'];
												$idcabang = $rowprod['idcabang'];
												$nm_cabang = $rowprod['nm_cabang'];
												$nm_asuransi = $rowprod['nm_asuransi'];
												$idasuransi = $rowprod['idins'];
												$cnt = $rowprod['cnt'];
												$value = $idprod.'|'.$idcabang.'|'.$idasuransi;
												echo '<option value="'.$value.'">'.$prod.' - '.$nm_asuransi.' - '.$nm_cabang.' ('.$cnt.')</option>';
											}
										?>
								</select>
              </div>
            </div>
            <div class="form-group m-b-0">
              <label class="control-label col-sm-3"></label>
              <div class="col-sm-6">
                  <button type="submit" class="btn btn-success width-xs">Search</button>
              </div>
            </div>
            <div id="progressbox" style="display:none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar"
								aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
								<div id="statustxt" class="info"></div>
								</div>
							</div>
						</div>
          </form>
        </div>
        <!-- end section-container -->
        <?php 
        	}elseif($deklarasi=="view"){
        ?>
				<!-- section-container -->
				<h4 class="m-t-0">Deklarasi</h4> 
				<div class="section-container section-with-top-border">
					<form action="#" id="form-deklarasi" class="form-horizontal" method="post" enctype="multipart/form-data">
			      <table id="data-table" data-order='[[1,"asc"]]' class="table table-bordered table-hover" width="100%">
		          <thead>
								<tr class="success">
									<th class="text-center" width="1%">No</th>
									<th width="1%" class="text-center">
										<center>
											<div class="checkbox">
											  <input onClick="toggle(this)" class="styled" type="checkbox" >
											  <label for="checkbox"></label>
											</div>
										</center>
									</th>
									<th class="text-center" width="30%">Produk</th>
									<th class="text-center" width="20%">Nomor SPK</th>
									<th class="text-center" width="20%">Asuransi</th>
									<th class="text-center" width="70%">Nama</th>
									<th class="text-center" width="30%">Tanggal Lahir</th>
									<th class="text-center" width="1%">Tenor (Bulan)</th>
									<th class="text-center" width="30%">Plafond</th>
									<th class="text-center">Status</th>
									<th class="text-center">Cabang</th>
									<th class="text-center">User Input</th>
									<th class="text-center">Tanggal Input</th>
								</tr>
							</thead>
		 					<tbody>
							<?php
								$query = "SELECT 	ajkspk.id,
																	ajkpolis.produk,
																	ajkspk.nomorspk,
																	ajkspk.nama,
																	ajkspk.dob,
																	ajkspk.tenor,
																	ajkspk.plafond,
																	ajkspk.mppbln,
																	ajkspk.statusspk,
																	ajkcabang.name as cabang,
																	CONCAT(useraccess.firstname,' ',useraccess.lastname)as userinput,
																	DATE_FORMAT(ajkspk.input_date, '%Y-%m-%d') AS tglinput,
																	ajkinsurance.name as nm_asuransi
													FROM ajkspk
													INNER JOIN ajkpolis
													ON ajkpolis.id = ajkspk.idproduk
													INNER JOIN ajkcabang
													ON ajkcabang.er= ajkspk.cabang
													INNER JOIN useraccess
													ON useraccess.id = ajkspk.input_by
													LEFT JOIN ajkinsurance
													ON ajkinsurance.id = ajkspk.asuransi													
													WHERE ajkspk.del is NULL AND	
																ajkpolis.del is NULL AND
																ajkcabang.del is NULL AND
																ajkinsurance.del is NULL AND
																ajkspk.statusspk = 'Aktif' AND
																ajkpolis.id = '".$produk."' AND
																ajkspk.cabang = '".$idcabang."' AND
																ajkspk.asuransi = '".$idasuransi."' AND
																ajkspk.nomorspk not in (SELECT nomorspk FROM ajkpeserta_temp where ifnull(nomorspk,'') != '')
													ORDER BY ajkspk.input_date DESC";

								$queryspk = mysql_query($query);

								$li_row = 1;

								while($rowspk = mysql_fetch_array($queryspk)){
			            echo '<tr class="odd gradeX">
				                  <td class="text-center">'.$li_row.'</td>
													<td>
														<center>
															<div class="checkbox">
								                <input name="approve[]" id="approve_'.$rowspk['id'].'" class="styled" type="checkbox" value="'.$rowspk['id'].'">
								                <label for="checkbox"></label>
												      </div>
														</center>
													</td>
													<td>'.$rowspk['produk'].'</td>
													<td>'.$rowspk['nomorspk'].'</td>
													<td>'.$rowspk['nm_asuransi'].'</td>
													<td><a title="View Detail"  href="../validasi?type='.AES::encrypt128CBC('viewdebitur',ENCRYPTION_KEY).'&nospk='.AES::encrypt128CBC($rowspk['nomorspk'],ENCRYPTION_KEY).'" target="_blank">'.$rowspk['nama'].'</a></td>
													<td>'._convertDate($rowspk['dob']).'</td>
													<td class="text-center">'.$rowspk['tenor'].'</td>
													<td class="text-right">'.duit($rowspk['plafond']).'</td>
													<td><span class="label label-warning">'.$rowspk['statusspk'].'</span></td>
													<td>'.$rowspk['cabang'].'</td>
													<td>'.$rowspk['userinput'].'</td>
													<td>'._convertDate($rowspk['tglinput']).'</td>
			                 	</tr>';

									$li_row++;
			          }
							?>
							</tbody>
		        </table>						
						<div class="form-group m-b-0">
		        	<label class="control-label col-sm-12"></label>
		          <div class="col-sm-6">
		          	<!-- <input name="sub" class="btn btn-success width-xs" value="Approved" type="submit"> -->
		          	<a href="javascript:;" onclick="f_deklarasi();" class="btn btn-success width-xs">Submit</a>
		        	</div>
		       	</div>		        
					</form>
	      </div>
        <!-- end section-container -->

      	<?php 
					}      	
      	?>
	    </div>
      <?php
      _footer();
      ?>
		</div>
		<!-- end #content -->
	</div>
	<!-- end page container -->
	<?php
	_javascript();
	?>

	<script>
		$(document).ready(function() {
	    App.init();
	    Demo.init();

			$(".active").removeClass("active");
			document.getElementById("has_input").classList.add("active");
			document.getElementById("idhas_input").classList.add("active");
			document.getElementById("idsub_deklarasi").classList.add("active");
			

			$("#data-table").DataTable({
				responsive: true,
				"paging":   false,
				"ordering": false,
				"info":     false,
				"autoWidth":false
			})

			$('#deklarasi').bootstrapValidator({
				err: {
					container: 'tooltip'
				},
				framework: 'bootstrap',
				icon: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},

				fields: {
					namaproduk: {
						validators: {
							notEmpty: {
								message: 'Silahkan pilih nama produk'
							}
						}
					}
				}
			});
		});

		function reload(){
			location.reload();
		}
		function f_deklarasi(){			
			var container = document.getElementById("content").innerHTML;
			var dataform = $('#form-deklarasi').serializeArray();

			document.getElementById("content").innerHTML = '<div class="spinner">Loading...</div>';
			
			$.ajax({
				url: 'dodeklarasi.php',
				type:"POST",
				data:dataform,
				success: function(data){
					document.getElementById("content").innerHTML = container;
					if(data === "success"){
						msgbox("success");
						location.reload();
					}else{
						msgbox("failed");
					}
					
				}
			});
		}
	</script>
</body>

</html>
