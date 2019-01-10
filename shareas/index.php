<?php
	include "../param.php";
	$typeuploadnya = AES::decrypt128CBC($_REQUEST['a'],ENCRYPTION_KEY);
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
			<div id="content" class="content">
			</div>
		</div>	
		<?php
		_javascript();
		?>

		<script type="text/javascript">
			function input(val,key=""){
				document.getElementById("content").innerHTML = '<div class="spinner"> Loading... </div>'				
			 	$.ajax({
			 		url: 'data.php',
			 		global: false,
			 		type:"POST",
			 		data: {hn:val,id:key},
			 		success: function(data){
			 			document.getElementById("content").innerHTML = data;
			 			$('#target_kredit').mask('000,000,000,000,000' , {reverse: true});
			 			//$('#persentase').mask('000' , {reverse: true});
						$("#tgl_efektif").datepicker({
							format:'dd/mm/yyyy',
							autoclose:true,
						});			 			

						$("#tbl-share").DataTable({
							paging:false,
							ordering:false,
							info:false,
							filter:false,
							scrollX:true,
							responsive:true
						});
			 		}
			 	});
			}

			function simpan(form,val,balik=""){
				var button = document.getElementById("btn-update").innerHTML;
				document.getElementById("btn-update").innerHTML = "<i class='fa fa-spinner fa-spin' ></i> Loading..";
				var dataform = $('#'+form).serializeArray();
				dataform.push({name: 'hn', value: val});
		    //console.log(dataform);
		    $.ajax({
		            type: "POST",
		            url : "data.php",
		            data:dataform,
		            cache: false,
		            success: function(msg){
									document.getElementById("btn-update").innerHTML = button;
		            	if(msg==="success"){
		            	 msgbox("Data Berhasil Disimpan");
		            	 input(balik);
		            	}else{		            		
		            	 	msgbox("Data Gagal","error");
		            	}
		            }
		          });			
		  }	
		</script>

		<script>
			$(document).ready(function() {
			  App.init();
			  $(".active").removeClass("active");
			  <?php  
			  	if($typeuploadnya == "sharetarget"){
						echo 'input("inputtarget");
									document.getElementById("has_shareas").classList.add("active");
									document.getElementById("sub_sharetarget").classList.add("active");';
			  	}elseif($typeuploadnya == "shareas"){
			  		echo 'input("viewshare");
			  					document.getElementById("has_shareas").classList.add("active");
			  					document.getElementById("sub_shareas").classList.add("active");';
			  	}
			  ?>
				
				
			});
		</script>		
	</body>

</html>