<?php
include "../param.php";

$today = date('Y-m-d H:i:s');
$nospk = $_REQUEST['nomorspk'];
$namadebitur = strtoupper($_REQUEST['namadebitur']);
$nomorktp = $_REQUEST['nomorktp'];
foreach($_POST['jeniskelamin'] as $jeniskelamin) {
	$gender =$jeniskelamin;
}
$tgllahir = $_REQUEST['tgllahir'];
$tgllahir = substr($tgllahir, 6, 10).'-'.substr($tgllahir, 3, 2).'-'.substr($tgllahir, 0, 2);
$alamat = strtoupper($_REQUEST['alamat']);
$pekerjaan = strtoupper($_REQUEST['pekerjaan']);
$jumlahpinjaman = $_REQUEST['jumlahpinjaman'];
$jumlahpinjaman = str_replace(",", '', $jumlahpinjaman);
$jangkawaktupinjaman = $_REQUEST['jangkawaktupinjaman'];


$typedata = AES::encrypt128CBC("dataspk",ENCRYPTION_KEY);

mysql_query("UPDATE ajkspk SET nama = '".$namadebitur."', nomorktp = '".$nomorktp."', jeniskelamin = '".$gender."', dob = '".$tgllahir."', alamat = '".$alamat."', pekerjaan = '".$pekerjaan."',
plafond = '".$jumlahpinjaman."', tenor = '".$jangkawaktupinjaman."',
update_by = '".$iduser."'
WHERE nomorspk = '".$nospk."'") or die("Error message = ".mysql_error());

header("location:../validasi/?type=".$typedata);

?>