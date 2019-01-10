<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
    include "../param.php";
    // include "../sendmail/sendmail.php";
    ini_set('session.gc_maxlifetime', 84600);
    $expireTime = 60*60*24*100; // 100 days
    session_set_cookie_params($expireTime);
    session_start();
    $comment = $_REQUEST["postcomment"];
    $idtopics = $_REQUEST["idtop"];
    $category = $_REQUEST["idcat"];
    $startpage = $_REQUEST["start"];
    $postid = $_REQUEST["postid"];
    $user = $_SESSION["User"];
    $file_name1 = $_FILES['files1']['name'];
    $file_tmp1 = $_FILES['files1']['tmp_name'];
    $file_name2 = $_FILES['files2']['name'];
    $file_tmp2 = $_FILES['files2']['tmp_name'];

    $today = date("Y-m-d H:i:s");
    $ls_body = 'testing mail body';
    $new_file_name1 ="";
    $new_file_name2 ="";
    //$ls_toemail = $email;
    //$ls_toname = $fname;
    $ls_subject = "[AppCorvy] Kode Verifikasi";
    $ls_countemail = 1;
    $ls_fromname = 'no replay';
    $ls_fromemail = 'noreplay@kode.web.id';
    $ls_ccname = '';
    $ls_ccmail = '';
    $li_countcc = 0;

    $querypost = mysql_query("SELECT * FROM posts WHERE post_id = '".$postid."'");
    $rowpost = mysql_fetch_array($querypost);
    $filename1 = $rowpost['post_attachmentname1'];
    $filename2 = $rowpost['post_attachmentname2'];
    $fileattactment1 = $rowpost['post_attachment1'];
    $fileattactment2 = $rowpost['post_attachment2'];
    $postdate = $rowpost['post_date'];

    $foldername = date("M", strtotime($postdate)).date("y", strtotime($postdate));
    $path = '../assets/documents/discussion/'.$foldername;

if (!file_exists($path)) {
    mkdir($path, 0777);
    chmod($path, 0777);
}

    if ($comment !="") {
        if ($file_name1!="") {
            $file1_info = pathinfo($file_name1);
            $file1_extension = strtolower($file1_info["extension"]); //image extension
            $file1_name_only = strtolower($file1_info["filename"]);//file name only, no extension
            // $queryfilepost = mysql_query("SELECT * FROM posts WHERE post_by = '".$user."'");
            // $num_file = mysql_num_rows($queryfilepost);
            // $num_file = $num_file+1;
            $unique = uniqid();
            $fullpath = '../assets/documents/discussion/'.$foldername.'/'.$fileattactment1;
            unlink($fullpath);
            $new_file_name1 = $user.'-'.$file1_name_only. '-' .  $unique . '.' . $file1_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name1;
            move_uploaded_file($file_tmp1, $destination_folder) or die("Could not upload file!");

            $namafile1 = $file_name1 ;
            $fileattactment1 = $new_file_name1;
        } else {
            $namafile1 = $filename1;
            $fileattactment1 = $fileattactment1;
        }
        if ($file_name2!="") {
            $file2_info = pathinfo($file_name2);
            $file2_extension = strtolower($file2_info["extension"]); //image extension
            $file2_name_only = strtolower($file2_info["filename"]);//file name only, no extension
            $unique = uniqid();
            $fullpath = '../assets/documents/discussion/'.$foldername.'/'.$fileattactment2;
            unlink($fullpath);
            $new_file_name2 = $user.'-'.$file2_name_only. '-' .  $unique . '.' . $file2_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name2;
            move_uploaded_file($file_tmp2, $destination_folder) or die("Could not upload file!");
            $namafile2 = $file_name2 ;
            $fileattactment2 = $new_file_name2;
        } else {
            $namafile2 = $filename2;
            $fileattactment2 = $fileattactment2;
        }
        $querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$idtopics."'");
        $num_post = mysql_num_rows($querypost);
        $totalpost = $num_post +1;
        // print_r($filename2);
        // exit;
        mysql_query("UPDATE posts SET post_content = '".$comment."',
					post_update = '".$today."',
					post_attachment1 = '".$fileattactment1."',
					post_attachment2 = '".$fileattactment2."',
					post_attachmentname1 = '".$namafile1."',
					post_attachmentname2 = '".$namafile2."'
					WHERE post_id = '".$postid."'") or die("gagal update");
    }

    //kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
    header("location:viewpost.php?idcat=".$category."&top=".$idtopics."&start=".$startpage."#".$totalpost);
