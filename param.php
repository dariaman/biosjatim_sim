<?php
    include "../koneksi.php";
    include '../PHPExcel/IOFactory.php'; //marked by chrismanuel at 20180312
    include "../umur.php";
    include "../sendmail/sendmail.php";
    include "../includes/functions.php";
    session_start();
    $path="https://".$_SERVER['SERVER_NAME']."/";
    $user = $_SESSION['User'];
    if ($user=="") {
        echo "<script>window.location='".$path."/dologout.php'</script>";
    // header("location:../dologout.php");
    } elseif (isset($_SESSION['EXPIRED']) && (time() - $_SESSION['EXPIRED'] > 1800)) {
        // last request was more than 30 minutes ago
        echo "<script>window.location='".$path."/dologout.php'</script>";
        // setUserLog($_SESSION["uid"], 'logout');
        // session_unset();
        // session_destroy();
        // header('location:../login?pesan=Sesi anda telah berakhir. Silahkan login kembali.');
        // echo "<script>window.location='".$path."/login?pesan=Sesi anda telah berakhir. Silahkan login kembali.'</script>";
    }
    $_SESSION['EXPIRED'] = time();
    $app = "bios";
    $queryuser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$user."'");
    $rowuser = mysql_fetch_array($queryuser);
    $namauser = $rowuser['firstname'];
    $lastname = $rowuser['lastname'];
    $emailuser = $rowuser['email'];
    $iduser = $rowuser['id'];
    $idsupervisor = $rowuser['supervisor'];
    $photo = $rowuser['photo'];
    $idbro = $rowuser['idbroker'];
    $idas = $rowuser['idas'];
    $cabang = $rowuser['branch'];
    $idclient = $rowuser['idclient'];
    $qklient = mysql_query("SELECT * FROM ajkclient WHERE id= '".$idclient."'");
    $rklient = mysql_fetch_array($qklient);
    $namaklient = $rklient['name'];
    $logoklient =  $rklient['logo'];
    $useremail = $rowuser['email'];
    $level = $rowuser['level'];
    $tipe = $rowuser['tipe'];
    $gender = $rowuser['gender'];
    // $lastdayakad = $rowuser['lastdayinsurance'];
    $branchid = $rowuser['branch'];
    if ($gender=="L") {
        $jeniskelamin = "Laki-Laki";
    } else {
        $jeniskelamin = "Perempuan";
    }
    $dob = $rowuser['dob'];
    $dob = date("d M Y", strtotime($dob));
    $inputtime = $rowuser['input_time'];
    $inputtime = date("d M Y", strtotime($inputtime));
    $updatetime = $rowuser['update_time'];
    $updatetime = date("d M Y", strtotime($updatetime));

    $querylevel = mysql_query("SELECT * FROM leveluser WHERE er = '".$level."'");
    $rowlevel = mysql_fetch_array($querylevel);
    $namalevel = $rowlevel['nama'];

    $querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id ='".$idbro."'");
    $rowbroker = mysql_fetch_array($querybroker);
    $namebro = $rowbroker['name'];
    $logo = $rowbroker['logo'];
    $address1 = $rowbroker['address1'];
    $address2 = $rowbroker['address2'];
    $city = $rowbroker['city'];
    $postcode = $rowbroker['postcode'];

    $alamat = $address1.' '.$address2.' '.$city.' '.$postcode;
    $phoneoffice = $rowbroker['phoneoffice'];
    $phonehp = $rowbroker['phonehp'];
    $phonefax = $rowbroker['phonefax'];

    $querycab = mysql_query("SELECT * FROM ajkcabang WHERE er = '".$branchid."'");
    $rowcab = mysql_fetch_array($querycab);
    $namacabang = $rowcab['name'];
    $levelcabang = $rowcab['level'];
    $regional = $rowcab['idreg'];
    $mamettoday = date("Y-m-d H:i:s");

    $queryins = mysql_query("SELECT * FROM ajkinsurance WHERE id = '".$idas."'");
    $rowins = mysql_fetch_array($queryins);
    $nmins = $rowins['name'];
    $logoins = $rowins['logo'];
