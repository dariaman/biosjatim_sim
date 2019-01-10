<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../param.php";
	include("../resize-class.php");
	$nama = 'FotoGeneral';
	$today = date('Y-m-d H:i:s');
	$foldername = date("y",strtotime($today)).date("m",strtotime($today));
	$path = '../myFiles/_photogeneral/'.$foldername;

	if (!file_exists($path)) {
		mkdir($path, 0777);
		chmod($path, 0777);
	}
	$nomorktp = $_REQUEST['idk'];
	$inputtime = $_REQUEST['idp'];
	if(isset($_FILES['files']['name'])){
		foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			$file_name = $_FILES['files']['name'][$key];
			$file_size = $_FILES['files']['size'][$key];
			$file_tmp = $_FILES['files']['tmp_name'][$key];
			$file_type= $_FILES['files']['type'][$key];
			if($file_name!=""){

				$qgallery = mysql_query("SELECT * FROM ajkphotoklaim");
				$num_gallery = mysql_num_rows($qgallery);
				$num_gallery = $num_gallery+1;
				$image_info = pathinfo($file_name);
				$image_extension = strtolower($image_info["extension"]); //image extension
				$image_name_only = strtolower($image_info["filename"]);//file name only, no extension
				$new_file_name = $image_name_only. '-' .  $num_gallery . '.' . $image_extension;
				$destination_folder		= $path.'/'.$new_file_name;
				move_uploaded_file($tmp_name,$destination_folder) or die( "Could not upload file!");

				// *** 1) Initialise / load image
				$resizeObj[$key] = new resize($destination_folder);

				$resizeObj[$key] -> resizeImage(470, 350,"auto");
				$resizeObj[$key] -> saveImage($destination_folder);
				mysql_query("insert into ajkphotoklaim (idpeserta,photo,type,input_by,input_date)
				values ('".$nomorktp."','".$new_file_name."','awal','".$iduser."','".$today."')")or die("gagal masuk");
			}
		}
	}
	header("location: upload.php?inpt=".$inputtime."&pesan=Upload gambar berhasil");

?>