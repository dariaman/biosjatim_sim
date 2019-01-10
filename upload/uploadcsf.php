<?php
include "../param.php";
include_once "../includes/functions.php";

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php
_head($user,$namauser,$photo,$logo);
?>

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
		<div id="content" class="content">
			<div class="panel p-30">
				<h4 class="m-t-0">Upload Certificate</h4>
				<div class="section-container section-with-top-border">			    
			    <form action="../api/apijatim.php" id="form-upload" name="form-upload" class="form-horizontal" method="post" enctype="multipart/form-data">
			    	<input type="hidden" name="han" value="uploadcsf">
			    	<div class="panel-body">
							<table id="data-pesertatemp" class="table table-bordered table-hover" width="100%">
								<thead >
									<tr class="primary">
										<th class="text-center">No</th>
										<th class="text-center">Produk</th>
										<th class="text-center">Nama</th>						
										<th class="text-center">Tanggal Lahir</th>
										<th class="text-center">Usia</th>
										<th class="text-center">Plafond</th>
										<th class="text-center">Tanggal Akad</th>										
										<th class="text-center">Tanggal Akhir</th>										
										<th class="text-center">Tenor</th>										
										<th class="text-center">Cabang</th>										
										<th class="text-center">Asuransi</th>
										<th class="text-center">No Sertifikat</th>
									</tr>
								</thead>
								<tbody>									
									<?php
										$no =  1;
										$error = 0;
										$file_name = $_FILES['fileupload']['name'];
										$ext = pathinfo($file_name, PATHINFO_EXTENSION);
										$file_name = $_FILES['fileupload']['tmp_name'];
										$file_info = pathinfo($file_name);
										$file_extension = $file_info["extension"];
										$namefile = $file_info["filename"].'.'.$file_extension;
										$inputFileName = $file_name;
										$_SESSION['file_temp'] = $namefile;
										$_SESSION['file_name'] = $_FILES['fileupload']['name'];	
																				
										$handle = fopen($file_name, "r");
										if ($handle) {
										    while (($line = fgets($handle)) !== false) {
									      	$data = explode('|', $line);
									       	$query = "SELECT *
									       					 FROM vpeserta
									       					 WHERE idpeserta = '".$data[0]."' and asuransi = '".$idas."'";
									       	$peserta = mysql_fetch_array(mysql_query($query));

									       	if($peserta['nama'] == ""){
									       		$error = 1;
									       		$errorall = '<span class="label label-danger">Data tidak terdapat di database</span>';
									       	}else{
									       		$error = 0;
									       	}

										      if($peserta['noasuransi'] == $data[1]){
										       	$error = 1;
										       	$errorcsf = '<span class="label label-danger">No Sertifikat sudah terdapat di database</span>';
										      }else{
										       	$error = 0;
										      }

													echo "<tr>";
									        echo "<td>".$no." </td>";
									       	echo "<td>".$peserta['produk']." $errorall</td>";
													echo "<td>".$peserta['nama']." $errorall</td>";
													echo "<td>".$peserta['tgllahir']." $errorall</td>";
													echo "<td>".$peserta['usia']." $errorall</td>";
													echo "<td>".$peserta['plafond']." $errorall</td>";
													echo "<td>".$peserta['tglakad']." $errorall</td>";
													echo "<td>".$peserta['tglakhir']." $errorall</td>";
													echo "<td>".$peserta['tenor']." $errorall</td>";
													echo "<td>".$peserta['nmcabang']." $errorall</td>";
													echo "<td>".$peserta['nm_asuransi']." $errorall</td>";
													echo "<td>".$data[1]." $errorcsf</td>";
													echo "</tr>";
										      $no++; 
										    }

										    if($error == 0 ){
										    	move_uploaded_file($file_name,'temp/'.$namefile) or die( "Could not upload file!");
										    	$disabledbtn = '';
										    }else{
										    	$disabledbtn = 'disabled';
										    }
										  
										  fclose($handle);										   
										}
									?>
								</tbody>
							</table>
							<div class="form-group m-b-0">
								<label class="control-label col-sm-12"></label>
								<div class="col-sm-6">
									<input type="submit" name="sub" class="btn btn-success width-xs" value="Submit" <?php echo $disabledbtn ?>>
									<a href="../upload?xq=<?php echo AES::encrypt128CBC($_REQUEST['xq'],ENCRYPTION_KEY)?>" class="btn btn-danger width-xs">Cancel</a>
								</div>
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
		$(document).ready(function() {
	    App.init();
	    Demo.init();

			$(".active").removeClass("active");
			document.getElementById("has_upload").classList.add("active");
			document.getElementById("idhas_uploadcsf").classList.add("active");
		});
		$("#data-pesertatemp").DataTable({	responsive: false,scrollX:true,paging:false	});

	</script>
</body>
</html>
