<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../koneksi.php";
	include "../sendmail/sendmail.php";
	session_start();
	$user = $_REQUEST["usertemp"];
	if($user==""){
		$user = $_SESSION["Username"];
	}
	$namatopics = $_REQUEST["namatopics"];
	$comment = $_REQUEST["postcomment"];
	$category = $_REQUEST["idcat"];
	$topicid = $_REQUEST["top"];
	$file_name1 = $_FILES['files1']['name'];
	$file_tmp1 = $_FILES['files1']['tmp_name'];
	$file_name2 = $_FILES['files2']['name'];
	$file_tmp2 = $_FILES['files2']['tmp_name'];
	if(!empty($_POST['typetopics'])) {
		$typetopics = $_POST['typetopics'];
	}else{
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
	$foldername = date("M",strtotime($today)).date("y",strtotime($today));
	$path = '../assets/documents/discussion/'.$foldername;

if (!file_exists($path)) {
	mkdir($path, 0777);
	chmod($path, 0777);
}
if($comment!=""){
	mysql_query("UPDATE topics SET topic_subject = '".$namatopics."',topic_updatedate= '".$today."',topic_updateby = '".$user."', topic_mode = '".$typetopics."'
	WHERE topic_id = '".$topicid."'");

	$querytopic = mysql_query("SELECT * FROM topics WHERE topic_subject ='".$namatopics."' AND topic_cat = '".$category."' AND topic_by = '".$user."'");
	$rowtopic = mysql_fetch_array($querytopic);
	$idtopics = $rowtopic['topic_id'];

	if($comment !=""){
		if($file_name1!=""){
			$file1_info = pathinfo($file_name1);
			$file1_extension = strtolower($file1_info["extension"]); //image extension
			$file1_name_only = strtolower($file1_info["filename"]);//file name only, no extension
			$queryfilepost = mysql_query("SELECT * FROM posts WHERE post_by = '".$user."'");
			$num_file = mysql_num_rows($queryfilepost);
			$num_file = $num_file+1;
			$fullpath = '../assets/documents/discussion/'.$foldername.'/'.$fileattactment1;
			unlink($fullpath);
			$new_file_name1 = $user.'-'.$file1_name_only. '1-' .  $num_file . '.' . $file1_extension;
			$destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name1;
			move_uploaded_file($file_tmp1,$destination_folder) or die( "Could not upload file!");

			$namafile1 = $file_name1 ;
			$fileattactment1 = $new_file_name1;
		}else{
			$namafile1 = $filename1;
			$fileattactment1 = $fileattactment1;
		}
		if($file_name2!=""){
			$file2_info = pathinfo($file_name2);
			$file2_extension = strtolower($file2_info["extension"]); //image extension
			$file2_name_only = strtolower($file2_info["filename"]);//file name only, no extension
			$queryfilepost = mysql_query("SELECT * FROM posts WHERE post_by = '".$user."'");
			$num_file2 = mysql_num_rows($queryfilepost);
			$num_file2 = $num_file2+1;
			$fullpath = '../assets/documents/discussion/'.$foldername.'/'.$fileattactment2;
			unlink($fullpath);
			$new_file_name2 = $user.'-'.$file2_name_only. '2-' .  $num_file2 . '.' . $file2_extension;
			$destination_folder		= '../assets/documents/discussion/'.$foldername.'/'.$new_file_name2;
			move_uploaded_file($file_tmp2,$destination_folder) or die( "Could not upload file!");
			$namafile2 = $file_name2 ;
			$fileattactment2 = $new_file_name2;
		}else{
			$namafile2 = $file_name2;
			$fileattactment2 = $fileattactment2;
		}
		$querypost = mysql_query("SELECT * FROM posts WHERE post_topic = '".$idtopics."'");
		$num_post = mysql_num_rows($querypost);
		$totalpost = $num_post +1;
		mysql_query("UPDATE posts SET post_content = '".$comment."',
		post_update = '".$today."',
		post_attachment1 = '".$fileattactment1."',
		post_attachment2 = '".$fileattactment2."',
		post_attachmentname1 = '".$namafile1."',
		post_attachmentname2 = '".$namafile2."'
		WHERE post_topic = '".$topicid."' AND post_num = '1'") or die("gagal update");
	}
		header("location:../topics/?idcat=".$category);
}else{
	header("location:../topics/editthread.php?idcat=".$category."&top=".$topicid."pesan=Posting masih kosong");
}
	//kirimemail($ls_fromname,$ls_fromemail,$ls_toname, $ls_toemail,$ls_countemail, $ls_ccname, $ls_ccmail, $li_countcc, $ls_subject,$ls_body);

?>