<?php
    include "../koneksi.php";

    session_start();
    $catid = $_REQUEST["idcat"];
    $topicid = $_REQUEST["top"];
    $postid = $_REQUEST["postid"];
    $user = $_SESSION["User"];
    $level = $_SESSION["level"];
    $today = date("Y-m-d H:i:s");
    if ($level==99) {
        $rs = mysql_query("DELETE FROM posts WHERE post_id =".$postid.' AND post_topic = '.$topicid);
    } else {
        $rs = mysql_query("DELETE FROM posts WHERE post_id =".$postid.' AND post_topic = '.$topicid.' AND post_by="'.$user.'"');
    }

    // print_r($topicid);
    header("location:../topics/viewpost.php?idcat=".$catid."&top=".$topicid);
