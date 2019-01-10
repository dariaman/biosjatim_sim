<?php
include "../param.php";
include_once('../includes/functions.php');
if (isset($_REQUEST['type'])) {
    $typedata = $_REQUEST['type'];
    $typedata = AES::decrypt128CBC($typedata, ENCRYPTION_KEY);

    $queryins = "SELECT * FROM ajkinsurance WHERE del is null";
    $result = mysql_query($queryins);

    while ($result_ = mysql_fetch_array($result)) {
    	$lsins = $lsins.'<option value="'.$result_['id'].'">'.$result_['name'].'</option>';	
    }


    $tgl_pengajuan = '
		<div class="form-group">
			<label class="control-label col-sm-3">Tanggal Pengajuan <span class="text-danger">*</span></label>
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" name="startdatep" id="startdatep" class="form-control" placeholder="Start Date"  />
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" name="enddatep" id="enddatep" class="form-control" placeholder="End Date"  />
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</div>
		</div>
	';

    if ($typedata == "peserta") {
        $ls_title = "Laporan Peserta";
        $tgl_pengajuan = '';
		    $ls_status = '<div class="form-group">
											<label class="control-label col-sm-3">Status </label>
											<div class="col-sm-6">
												<select class="form-control" name="status">
													<option value="">-- Pilih Status --</option>
													<option value="">ALL</option>
													<option value="Inforce">Inforce</option>
													<option value="Lapse">Lapse</option>
													<option value="Maturity">Maturity</option>
													<option value="Batal">Batal</option>
												</select>
											</div>
										</div>';     
				$ls_asuransi = '<div class="form-group">
											<label class="control-label col-sm-3">Asuransi </label>
											<div class="col-sm-6">
												<select class="form-control" name="asuransi">
													<option value="">-- Pilih Asuransi --</option>
													<option value="">All</option>
													'.$lsins.'
												</select>
											</div>
										</div>';  										   
    } elseif ($typedata == "debitnote") {
        $ls_title = "Laporan Debitnote";
        $tgl_pengajuan = '';
				$ls_status = '<div class="form-group">
											<label class="control-label col-sm-3">Status </label>
											<div class="col-sm-6">
												<select class="form-control" name="status">
													<option value="">-- Pilih Status --</option>
													<option value="">ALL</option>
													<option value="Paid">Paid</option>
													<option value="Unpaid">Unpaid</option>
												</select>
											</div>
										</div>';              
    } elseif ($typedata == "cnbatal") {
        $ls_title = "Laporan Data Batal";
        $ls_status = "";
    } elseif ($typedata == "cnrefund") {
        $ls_title = "Laporan Data Refund";
        $ls_status = "";
    } elseif ($typedata == "cnklaim") {
        $ls_title = "Laporan Data Klaim";
        $ls_status = "";
    } elseif ($typedata == "lapmemins") {
        $ls_title = "Laporan Peserta Asuransi ";
        $ls_status = "";
        $tgl_pengajuan = '';
    }    
} else {
    header("location:../dashboard");
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user, $namauser, $photo, $logo);
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
        _header($user, $namauser, $photo, $logo, $logoklient);
        _sidebar($user, $namauser, '', '');
        ?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30">
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
			    <h4 class="m-t-0"><?php echo $ls_title; ?></h4>
			    <form action="data.php?type=<?php echo $typedata; ?>" id="form-peserta" class="form-horizontal" method="post" enctype="multipart/form-data">
			    	<div class="form-group">
							<label class="control-label col-sm-3">Broker </label>
							<div class="col-sm-6">
								<label class="control-label "><?php echo $namebro ?> </label>
							</div>
						</div>
					<?php
					if($idas == ""){
					?>
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Partner </label>
							<div class="col-sm-6">
								<label class="control-label "><?php echo $namaklient ?> </label>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Cabang </label>
							<div class="col-sm-6">
								<?php
                    if ($levelcabang == 1) {
                        $cabangverifikasi = '';
                        $disable = '';
                        $option = '<option value="">-- Pilih Cabang --</option>
																	<option value="">ALL</option>';                        
                    } elseif ($levelcabang == 2) {
                        $cabangverifikasi = " and idreg = '".$regional."'";
                        $disable = '';
                        $option = '<option value="">-- Pilih Cabang --</option>
																	<option value="">ALL</option>';
                    } else {
                        $cabangverifikasi = " and er = '".$cabang."'";
                        //$disable = 'disabled';
                        $selected = 'selected';
                    }
                ?>
								<select class="form-control" name="cabang" <?php echo $disable; ?> >
									
									<?php
											echo $option;
                      $query = "SELECT * FROM ajkcabang WHERE del IS NULL ".$cabangverifikasi;

                      $querycabang = mysql_query("SELECT * FROM ajkcabang WHERE del IS NULL ".$cabangverifikasi);
                      while ($rowcab = mysql_fetch_array($querycabang)) {
                          $idcab = $rowcab['er'];
                          $namacab = $rowcab['name'];
                          echo '<option value="'.$idcab.'" '.$selected.'>'.$namacab.'</option>';
                      }
                  ?>
								</select>
              </div>
            </div>
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Produk </label>
							<div class="col-sm-6">
								<select class="form-control" name="namaproduk">
									<option value="">-- Pilih Produk --</option>
									<option value="">ALL</option>
									<?php
                    $queryprod = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND del IS NULL");
                    while ($rowprod = mysql_fetch_array($queryprod)) {
                        $idprod = $rowprod['id'];
                        $namaprod = $rowprod['produk'];
                        echo '<option value="'.$idprod.'">'.$namaprod.'</option>';
                    }
                   ?>
								</select>
						    </div>
						</div>
					<?php  						
					}	
					echo $ls_asuransi;
					?>
					
						<div class="form-group">
							<label class="control-label col-sm-3">Tanggal Akad <span class="text-danger">*</span></label>
							<div class="col-sm-3">
								<div class="input-group date">
									<input type="text" name="startdate" id="startdate" class="form-control" placeholder="Start Date"  />
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="input-group date">
									<input type="text" name="enddate" id="enddate" class="form-control" placeholder="End Date"  />
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<?= $tgl_pengajuan ?>
						<?php echo $ls_status ?>
						<div class="form-group m-b-0">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-6">
								<input type="hidden" id="hidType">
								<button type="submit" class="btn btn-success width-xs">Submit</button>
							</div>
						</div>
						<div id="progressbox" style="display:none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" id="progress_bar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
									<div id="statustxt" class="info"></div>
								</div>
							</div>
						</div>
					</form>
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
		  //test();
		  
		  $(".active").removeClass("active");
			document.getElementById("has_laporan").classList.add("active");
			document.getElementById("sub_laporan").classList.add("active");

			<?php
            if ($typedata == 'peserta') {
                echo 'document.getElementById("idsub_lappeserta").classList.add("active");';
				echo "validate_tgl1();";
            } elseif ($typedata == 'debitnote') {
                echo 'document.getElementById("idsub_lapdebitnote").classList.add("active");';
				echo "validate_tgl1();";
            } elseif ($typedata == 'cnbatal') {
                echo 'document.getElementById("idsub_lapcreditnotebatal").classList.add("active");';
            } elseif ($typedata == 'cnrefund') {
                echo 'document.getElementById("idsub_lapcreditnoterefund").classList.add("active");';
            } elseif ($typedata == 'cnklaim') {
                echo 'document.getElementById("idsub_lapcreditnoteklaim").classList.add("active");';
            }
            ?>
			 
			function test(){	
				var tgl1 = $("#startdate,#form-peserta").val();
				var tgl2 = $("#enddate,#form-peserta").val();
				var tgl3 = $("#startdatep,#form-peserta").val();
				var tgl4 = $("#enddatep,#form-peserta").val();
				  
				if(tgl1=='' && tgl2=='' && tgl3=='' && tgl4==''){
				    console.log(1);
				    //$("#hidType,#form-peserta").val(1); 
				    validate_tgl1();
				}
				
				if(tgl1!='' && tgl2=='' && tgl3=='' && tgl4==''){
					console.log(2);
					//$("#hidType,#form-peserta").val(2); 
				    validate_tgl1();
				}
				
				if(tgl3!='' && tgl4=='' && tgl1=='' && tgl2==''){
					console.log(3);
					//$("#hidType,#form-peserta").val(2); 
					validate_tgl2();
				} 
			}
			
			
			
			$("#startdate").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy',
				autoclose: true
			}).on('changeDate', function(e) {
				$('#form-peserta').bootstrapValidator('revalidateField', 'startdate');
				test(); 
			});

			$("#enddate").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy',
				autoclose: true
			}).on('changeDate', function(e) {
				$('#form-peserta').bootstrapValidator('revalidateField', 'enddate');
				test();
			});

			$("#startdatep").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy',
				autoclose: true
			}).on('changeDate', function(e) {
				$('#form-peserta').bootstrapValidator('revalidateField', 'startdatep');
				test(); 
			});
			
			$("#enddatep").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy',
				autoclose: true
			}).on('changeDate', function(e) {
				$('#form-peserta').bootstrapValidator('revalidateField', 'enddatep');
				test();
			});
			
			
			function validate_tgl1(){
				console.log(111);
			    $('#form-peserta').bootstrapValidator({
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
						startdate: {
							validators: {
								notEmpty: {
									message: 'Tanggal mulai tidak boleh kosong'
								},
								date: {
									format: 'DD/MM/YYYY',
									message: 'Format tanggal mulai dd/mm/yyyy'

								}
							}
						},
						enddate: {
							validators: {
								notEmpty: {
									message: 'Tanggal akhir tidak boleh kosong'
								},
								date: {
									format: 'DD/MM/YYYY',
									message: 'Format tanggal akhir dd/mm/yyyy'

								}
							}
						}
					} 
				});  
			}
			
			function validate_tgl2(){
				console.log(222);
			    $('#form-peserta').bootstrapValidator({
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
						startdatep: {
							validators: {
								notEmpty: {
									message: 'Tanggal mulai tidak boleh kosong'
								},
								date: {
									format: 'DD/MM/YYYY',
									message: 'Format tanggal mulai dd/mm/yyyy'

								}
							}
						},
						enddatep: {
							validators: {
								notEmpty: {
									message: 'Tanggal akhir tidak boleh kosong'
								},
								date: {
									format: 'DD/MM/YYYY',
									message: 'Format tanggal akhir dd/mm/yyyy'

								}
							}
						}
					} 
				});
			}
			
		});		
		 
	</script>
</body>

</html>
