<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
 echo ini_get('display_errors');
 if (!ini_get('display_errors')) {
     ini_set('display_errors', '1');
 }
 echo ini_get('display_errors');
    include "../param.php";

    // session_start();
    $namakategori = $_REQUEST["namakategori"];
    $description = $_REQUEST["description"];
    $typecat = $_REQUEST["typecat"];
    $userakses = $_REQUEST["userakses"];
    $user = $_SESSION["User"];
    $file_name1 = $_FILES['files1']['name'];
    $file_tmp1 = $_FILES['files1']['tmp_name'];
    $today = date("Y-m-d H:i:s");
    $new_file_name1 = null;

      // print_r($namakategori);exit;

        if ($file_name1!="") {
            $file1_info = pathinfo($file_name1);
            $file1_extension = strtolower($file1_info["extension"]); //image extension
            $file1_name_only = strtolower($file1_info["filename"]);//file name only, no extension
            $unique = uniqid();
            // $queryfile = mysql_query("SELECT * FROM agentdetail WHERE year(inputdate) = '".date('Y', strtotime($today))."' AND MONTH(inputdate) = '".date('m', strtotime($today))."'");
            // $num_file = mysql_num_rows($queryfile);
            // $num_file = $num_file+1;

            $new_file_name1 = $user.'-'.$file1_name_only. '-' . $unique . '.' . $file1_extension;
            $destination_folder		= '../assets/img/category/'.$new_file_name1;
            move_uploaded_file($file_tmp1, $destination_folder) or die("Could not upload file!");
            include("resize-class.php");

            // *** 1) Initialise / load image
            $resizeObj = new resize($destination_folder);

            // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
            $resizeObj -> resizeImage(128, 128, 'crop');

            // *** 3) Save image
            $resizeObj -> saveImage($destination_folder, 100);
        }

        mysql_query("INSERT INTO categories (cat_name,cat_description, cat_type,cat_group, cat_images,cat_userinput,cat_userdate)
										 VALUES ('".$namakategori."','".mysql_escape_string($description)."','".$typecat."','".$userakses."','".$new_file_name1."','".$user."','".$today."')");

    //kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
    header("location:../discussion");
