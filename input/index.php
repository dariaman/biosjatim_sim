<?php
include "../param.php";
$setproduk = '';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<style type="text/css">
	#canvas{
  border: solid 1px blue;  
  width: 100%;
}
</style>

<?php
	_head($user,$namauser,$photo,$logo);
	
	if(isset($_REQUEST['pesan'])){
	echo '<script type="text/javascript">',
		     'setTimeout(function() {
			      toastr.options = {
			          closeButton: true,
			          progressBar: true,
			          showMethod: "slideDown",
			          timeOut: 4000
			      };
			      toastr.success("Input AJK Success", "Success");
		    	}, 1300);',
		    '</script>';
	}

	$newdata ="Asuransi Jiwa Kredit(AJK)";
	$cekprod =" AND general ='T'";
	$setproduk .='<div class="form-group">
                  <label class="control-label col-sm-2">Nama Produk <span class="text-danger">*</span></label>
                  <div class="col-sm-10">
                  	<select class="form-control" name="namaproduk">
											<option value="">-- Pilih Produk --</option>';
	$queryprod = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' ".$cekprod."");
	while($rowprod = mysql_fetch_array($queryprod)){
		if ($rowprod['status']=="Aktif") {
			$prodDis = '';
		}else{
			$prodDis = 'disabled';
		}
			$idprod = $rowprod['id'];
			$namaprod = $rowprod['produk'];
			$setproduk .= '<option value="'.$idprod.'" '.$prodDis.'>'.$namaprod.'</option>';
	}

	$setproduk .='</select>
                        </div>
                    </div>';
?>

<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
	<!-- end #page-loader -->

	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><p id="modal-header">Modal Header</p></h4>
        </div>
        <div class="modal-body">
          <video id="video" width="100%" height="auto" autoplay></video>
        </div>
        <div class="modal-footer text-center" id="modal-footer">
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Capture</button> -->
          <!-- <a href="javascript:;" onclick="getpict('cvsdebitur');$('#myModal').modal('hide');" class="btn btn-default">Simpan</a> -->
        </div>
      </div>
      
    </div>
  </div>
  <!-- Modal -->

  <!-- Modal -->
  <div class="modal fade" id="myModalSignPad" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><p id="modal-header-sign">Modal Header</p></h4>
        </div>
        <div class="modal-body">
          <canvas id="signpad">Sign Pad</canvas>
        </div>
        <div class="modal-footer text-center" id="modal-footer-sign">
        </div>
      </div>
      
    </div>
  </div>
  <!-- Modal -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
		<?php
		_header($user,$namauser,$photo,$logo,$logoklient);
		_sidebar($user,$namauser,'','');
		?>
		<!-- begin #content -->
		<div id="content" class="content">
			<div class="panel p-30"><h4 class="m-t-0"><?php echo $newdata; ?></h4>
				<!-- begin section-container -->
				<div class="section-container section-with-top-border">
			    <h4 class="m-t-0">Input Data Debitur</h4>
			    <form action="doinput.php" id="inputmember" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-sm-2">Cabang </label>
                <div class="col-sm-10">
                	<label class="control-label "><?php echo $namacabang ?> </label>
                </div>
            </div>
            <?php
            echo $setproduk;
            ?>
            <div class="form-group">
                <label class="control-label col-sm-2">Nama <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<input name="namatertanggung" id="namatertanggung" class="form-control text-uppercase" placeholder="Nama" type="text">
                </div>
            </div>
						<div class="form-group">
                <label class="control-label col-sm-2">Jenis Kelamin <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<select class="form-control" name="jnsklmn">
										<option value="">-- Pilih --</option>
										<option value="L">Laki-Laki</option>
										<option value="P">Perempuan</option>
									</select>
                </div>
            </div>	                    
            <div class="form-group">
                <label class="control-label col-sm-2">Tanggal Lahir <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                  <div class="input-group date" >
                      <input type="text" id="tgllahir" name="tgllahir" class="form-control" placeholder="Tanggal Lahir" />
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Nomor Identitas <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input name="nomorktp" id="nomorktp" class="form-control" placeholder="Nomor Identitas" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Alamat <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input name="alamat" id="alamat" class="form-control" placeholder="Alamat" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Pekerjaan <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input name="pekerjaan" id="pekerjaan" class="form-control text-uppercase" placeholder="Pekerjaan" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Plafond Kredit <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<input name="plafon" id="plafon" class="form-control" placeholder="Silahkan Input Nilai Plafon Kredit" type="text">
                </div>
            </div>	                    
            <div class="form-group">
                <label class="control-label col-sm-2">Tenor (Bulan) <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                	<input name="tenor" id="tenor" class="form-control" placeholder="Silahkan Input Tenor" type="text">
                </div>
            </div>
            <div class="form-group">
            	<label class="control-label col-sm-2"></label>
            	<div class="col-md-10">
            		<div class="row">
            			<div class="col-md-4">
            				<div class="text-center"><canvas id="cvsdebitur_temp" width="360" height="280"></canvas></div>            				
            				<canvas id="cvsdebitur" width="700" height="1100" class="hidden"></canvas>
            				<div class="text-center"><a href="javascript:;" onclick="openmodal('Foto Debitur','cvsdebitur')" class="btn btn-default">Foto Debitur</a></div>
            			</div>
            			<div class="col-md-4">
            				<div class="text-center"><canvas id="cvsktp_temp" width="360" height="280"></canvas></div>
            				<canvas id="cvsktp" width="700" height="1100" class="hidden"></canvas>
            				<div class="text-center"><a href="javascript:;" onclick="openmodal('Foto Identitas Debitur','cvsktp')" class="btn btn-default">Foto Identitas Debitur</a></div>
            			</div>
            			<div class="col-md-4">
            				<div class="text-center"><canvas id="cvssk_temp" width="360" height="280"></canvas></div>
            				<canvas id="cvssk" width="700" height="1100" class="hidden"></canvas>
            				<div class="text-center"><a href="javascript:;" onclick="openmodal('SK','cvssk')" class="btn btn-default">SK</a></div>
            			</div>            			
            		</div>
            		<div class="row">
            			<div class="col-md-4">
            				<div class="text-center"><canvas id="cvsttddebitur_temp" width="360" height="280"></canvas></div>
            				<canvas id="cvsttddebitur" width="700" height="1100" class="hidden"></canvas>
            				<div class="text-center"><a href="javascript:;" onclick="openmodal('Tanda Tangan Debitur','cvsttddebitur')" class="btn btn-default">Tanda Tangan Debitur</a></div>
            			</div>
            			<div class="col-md-4">
            				<div class="text-center"><canvas id="cvsttdmarketing_temp" width="360" height="280"></canvas></div>
            				<canvas id="cvsttdmarketing" width="700" height="1100" class="hidden"></canvas>
            				<div class="text-center"><a href="javascript:;" onclick="openmodal('Tanda Tangan Marketing','cvsttdmarketing')" class="btn btn-default">Tanda Tangan Marketing</a></div>
            			</div>            			
            		</div>
            	</div>
            </div>
            <div class="form-group m-b-0">
              <div class="col-sm-12 text-center">
                <button type="submit" id="load" class="btn btn-success width-xs" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Loading..">Submit</button>
              </div>
            </div>
          </form>
	      </div>
	        <!-- end section-container -->
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
		function picturl(cvs,tipe){
			var hasil = cvs.toDataURL("image/"+tipe);
			return hasil;
		}
		$(document).ready(function() {
		  App.init();
      

			$(".active").removeClass("active");
			document.getElementById("has_input").classList.add("active");
			$('#inputmember').bootstrapValidator({
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
						validators: {	notEmpty: {	message: 'Silahkan pilih nama produk'	}	}
					},
					metalamatmember: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat member'	}	}
					},
					metkotamember: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat kota member'	}	}
					},
					metkodeposmember: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat kodepos member'	}	}
					},
					metalamatobjek: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat objek'	}	}
					},
					metkotaobjek: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat kota objek'	}	}
					},
					metkodeposobjek: {
						validators: {	notEmpty: {	message: 'Silahkan input alamat kodepos objek'	}	}
					},
					namatertanggung: {
						validators: {	notEmpty: {	message: 'Silahkan input nama tertanggung'	}	}
					},
					nomorktp: {
						validators: {	notEmpty: {	message: 'Silahkan input nomor KTP '	}	}
					},
					nomorpk: {
						validators: {	notEmpty: {	message: 'Silahkan input nomor PK'	}	}
					},
					tgllahir: {
						validators: {
							notEmpty: {	message: 'Silahkan input tanggal lahir'	},
							date: {	format: 'DD/MM/YYYY',
									message: 'Format tanggal lahir dd/mm/yyyy'
							}
						}
					},
					tglakad: {
						validators: {
							notEmpty: {	message: 'Silahkan input tanggal akad'	},
							date: {	format: 'DD/MM/YYYY',
									message: 'Format tanggal akad dd/mm/yyyy'
							}
						}
					},
					tenor: {
						validators: {	notEmpty: {	message: 'Silahkan input tenor (bulan)'	}	}
					},
					jnsklmn: {
						validators: {	notEmpty: {	message: 'Silahkan input jenis kelamin'	}	}
					},
					nilaidiajukan: {
						validators: {	notEmpty: {	message: 'Silahkan input nilai objek yang diajukan'	}	}
					},
					plafon: {
						validators: {	notEmpty: {	message: 'Silahkan input plafon'	}	}
					}
				}
			}).on('success.form.bv',function(e){
        e.preventDefault();
        var $form = $(e.target);
        var bv = $form.data('bootstrapValidator');
        var dataform = $form.serializeArray();
				
				var cvsdebitur = document.getElementById('cvsdebitur');
				var cvsktp = document.getElementById('cvsktp');
				var cvsttddebitur = document.getElementById('cvsttddebitur');
				var cvssk = document.getElementById('cvssk');
				var cvsttdmarketing = document.getElementById('cvsttdmarketing');

				dataform.push({name: "cvsdebitur", value: picturl(cvsdebitur,"jpeg")});
				dataform.push({name: "cvsktp", value: picturl(cvsktp,"jpeg")});
				dataform.push({name: "cvssk", value: picturl(cvssk,"jpeg")});
				dataform.push({name: "cvsttddebitur", value: picturl(cvsttddebitur,"png")});
				dataform.push({name: "cvsttdmarketing", value: picturl(cvsttdmarketing,"png")});

				$("#load").button('loading');
				//$("#submit").button('reset');
				//console.log(dataform);
        $.ajax({
	            type: "POST",
	            url : "doinput.php",
	            data:dataform,
	            cache: false,
	            success: function(msg){
	            	// if(msg==="success"){
	            	//  msgbox("Data Berhasil Disimpan");
	            	// }else{		            		
	            	//  	msgbox("Data Gagal","error");
	            	// }	            	
	            	alert(msg);
	            	console.log(msg);
	            	$("#load").button('reset');
	            }
	      });
			});

      
			$("#tgllahir").datepicker({
				todayHighlight: !0,
				format:'dd/mm/yyyy',
				autoclose: true
			}).on('changeDate', function(e) {
				$('#inputmember').bootstrapValidator('revalidateField', 'tgllahir');
			});      

			// $("#tglakad").datepicker({
			// 	todayHighlight: !0,
			// 	format:'dd/mm/yyyy',
			// 	autoclose: true
			// }).on('changeDate', function(e) {
			// 	$('#inputmember').bootstrapValidator('revalidateField', 'tglakad');
			// });

			$('#plafon').mask('000,000,000,000,000' , {reverse: true});
			$('#nilaidiajukan').mask('000,000,000,000,000' , {reverse: true});
			$('#tgllahir').mask('99/99/9999');
			$('#tglakad').mask('99/99/9999');
			$('#tenor').mask('000' , {reverse: true});

			// Grab elements, create settings, etc.
			var video = document.getElementById('video');

			// Get access to the camera!
			if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
		        video.src = window.URL.createObjectURL(stream);
		        video.play();
		    });
			}
      
		});

		function openmodal(param1,param2){
			$('#myModal').modal('show');
			document.getElementById("modal-header").innerHTML = param1;

			document.getElementById("modal-footer").innerHTML = '<a href="javascript:;" onclick="getpict(\''+param2+'\');$(\'#myModal\').modal(\'hide\');" class="btn btn-default">Simpan</a>';
		}

		function openmodalsign(param1,param2){
			$('#myModalSignPad').modal('show');
			document.getElementById("modal-header-sign").innerHTML = param1;

			document.getElementById("modal-footer-sign").innerHTML = '<a href="javascript:;" onclick="getpict(\''+param2+'\');$(\'#myModalSignPad\').modal(\'hide\');" class="btn btn-default">Simpan</a>';
		}

		function getpict(elemen){
			var canvas = document.getElementById(elemen);
			var canvas_temp = document.getElementById(elemen+'_temp');
			var context = canvas.getContext('2d');
			var context_temp = canvas_temp.getContext('2d');
			var video = document.getElementById('video');
			context_temp.drawImage(video, 0, 0, 360, 280);			
			context.drawImage(video, 0, 0, 700, 550);
		}

	</script>
</body>

</html>
