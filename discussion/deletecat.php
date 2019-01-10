<?php
    include "../koneksi.php";

    session_start();
    $catid = $_REQUEST["idcat"];
    $user = $_SESSION["User"];
    $level = $_SESSION["level"];
    $today = date("Y-m-d H:i:s");
    if ($level==99) {
        $rs = mysql_query("UPDATE categories SET del = 1, cat_userupdate = '".$user."', cat_updatedate = '".$today."' WHERE cat_id =".$catid);
    } else {
        $rs = mysql_query("UPDATE categories SET del = 1, cat_userupdate = '".$user."', cat_updatedate = '".$today."' WHERE cat_id =".$catid." AND cat_userinput = '".$user."'");
    }

    header("location:../discussion");
