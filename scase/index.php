<?php
include "../param.php";
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
				<h4 class="m-t-0">Special Case</h4>
				<div class="section-container section-with-top-border">
				 <h1>Harap Hubungi Adonai di : (021)-86909090 atau email ke : ajk@adonai.co.id</h1>
				</div>
			</div>
		</div>
	</div>
	<?php
	_javascript();
	?>

	<script>
		$(document).ready(function() {
	    App.init();
	    Demo.init();
			$(".active").removeClass("active");
			document.getElementById("has_case").classList.add("active");
		});
	</script>	
</body>
</html>