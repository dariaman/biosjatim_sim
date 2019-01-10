<?php
    include "../koneksi.php";

    session_start();
    $catid = $_REQUEST["idcat"];
    $topicid = $_REQUEST["top"];
    $user = $_SESSION["User"];
    $level = $_SESSION["level"];
    $today = date("Y-m-d H:i:s");
    if ($level==99) {
        $rs = mysql_query("UPDATE topics SET del = 1, topic_updateby = '".$user."', topic_updatedate = '".$today."'  WHERE topic_cat = ".$catid." AND topic_id =".$topicid);
    } else {
        $rs = mysql_query("UPDATE topics SET del = 1, topic_updateby = '".$user."', topic_updatedate = '".$today."' WHERE topic_cat = ".$catid." AND topic_id =".$topicid." AND topic_by = '".$user."'");
    }

    header("location:../topics?idcat=".$catid);
