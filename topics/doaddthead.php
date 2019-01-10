<?php
// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
    include "../param.php";
    // include "../sendmail/sendmail.php";
    // session_start();
    $user = $_REQUEST["usertemp"];
    if ($user=="") {
        $user = $_SESSION["User"];
    }
    $namatopics = $_REQUEST["namatopics"];
    $comment = $_REQUEST["postcomment"];
    $category = $_REQUEST["idcat"];
    $file_name1 = $_FILES['files1']['name'];
    $file_tmp1 = $_FILES['files1']['tmp_name'];
    $file_name2 = $_FILES['files2']['name'];
    $file_tmp2 = $_FILES['files2']['tmp_name'];
    if (!empty($_POST['typetopics'])) {
        $typetopics = $_POST['typetopics'];
    } else {
        $typetopics = 'Normal';
    }
    $today = date("Y-m-d H:i:s");
    $ls_body = 'testing mail body';
    $new_file_name1 ="";
    $new_file_name2 ="";
    //$ls_toemail = $email;
    //$ls_toname = $fname;
    $ls_subject = "";
    $ls_countemail = 1;
    $ls_fromname = 'no replay';
    $ls_fromemail = 'noreplay@kode.web.id';
    $ls_ccname = '';
    $ls_ccmail = '';
    $li_countcc = 0;
    $foldername = date("M", strtotime($today)).date("y", strtotime($today));
    $path = '../assets/documents/discussion/'.$foldername;

if (!file_exists($path)) {
    mkdir($path, 0777);
    chmod($path, 0777);
}
if ($comment!="") {
    mysql_query("INSERT INTO topics (topic_subject,topic_date,topic_cat,topic_by, topic_mode)
	VALUES('".$namatopics."','".$today."','".$category."','".$user."','".$typetopics."')");

    $querytopic = mysql_query("SELECT * FROM topics WHERE topic_subject ='".$namatopics."' AND topic_cat = '".$category."' AND topic_by = '".$user."'");
    $rowtopic = mysql_fetch_array($querytopic);
    $idtopics = $rowtopic['topic_id'];

    if ($comment !="" and $comment != null) {
        if ($file_name1!="") {
            $file1_info = pathinfo($file_name1);
            $file1_extension = strtolower($file1_info["extension"]); //image extension
            $file1_name_only = strtolower($file1_info["filename"]);//file name only, no extension
            // $queryfilepost = mysql_query("SELECT * FROM posts WHERE post_by = '".$user."'");
            // $num_file = mysql_num_rows($queryfilepost);
            // $num_file = $num_file+1;
            $unique = uniqid();

            $new_file_name1 = $user.'-'.$file1_name_only. '-'.$unique . '.' . $file1_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name1;
            move_uploaded_file($file_tmp1, $destination_folder) or die("Could not upload file!");
        }
        if ($file_name2!="") {
            $file2_info = pathinfo($file_name2);
            $file2_extension = strtolower($file2_info["extension"]); //image extension
            $file2_name_only = strtolower($file2_info["filename"]);//file name only, no extension
            $unique = uniqid();

            $new_file_name2 = $user.'-'.$file2_name_only. '-' .  $unique . '.' . $file2_extension;
            $destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name2;
            move_uploaded_file($file_tmp2, $destination_folder) or die("Could not upload file!");
        }
        $querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$idtopics."'");
        $num_post = mysql_num_rows($querypost);
        $totalpost = $num_post +1;
        mysql_query("INSERT INTO posts (post_num,post_content, post_date, post_topic,post_attachment1,post_attachmentname1,post_attachment2,post_attachmentname2,post_by) VALUES ('".$totalpost."','".$comment."','".$today."','".$idtopics."','".$new_file_name1."','".$file_name1."','".$new_file_name2."','".$file_name2."','".$user."')");

        $notifdesc = $namatopics.' by '.$user;
        $icon = '<i class="fa fa-forumbee media-object bg-red"></i>';
        mysql_query("INSERT INTO notification (namanotif,description,icon,noticdate,notifuser,notiffrom)
	VALUES ('New Thread','".$notifdesc."','".$icon."','".$today."',null,'".$user."')");
    }
    header("location:../topics/?idcat=".$category);
} else {
    header("location:../topics/addthread.php?idcat=".$category."&pesan=Posting masih kosong");
}
    //kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);
