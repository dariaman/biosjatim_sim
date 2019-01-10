<?php
	$jangkrik = $_GET["jangkrik"];
	include("../param.php");

	$sql2 = "SELECT * FROM ajkpolis WHERE id = '".$jangkrik."'";
	$result2 = mysql_query($sql2);
	$row2 = mysql_fetch_assoc($result2);
	$cekProdGeneral = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneraltype WHERE id="'.$row2['idgeneral'].'"'));

if ($row2['general']=="T") {

}else{
echo '<h4 class="m-t-0">Data Objek</h4>';
echo '<div class="form-group">
	<label class="control-label col-sm-2">Alamat Member <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input name="metalamatmember" id="metalamatmember" class="form-control" placeholder="Alamat Member" type="text" value="'.$_REQUEST['metalamatmember'].'"></div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-2"> </label>
		<div class="col-sm-5"><input name="metkotamember" id="metkotamember" class="form-control" placeholder="Kabupate/Kota Member" type="text" value="'.$_REQUEST['metkotamember'].'"></div>
		<div class="col-sm-5"><input name="metkodeposmember" id="metkodepos" class="form-control" placeholder="Kode Pos Member" type="text" value="'.$_REQUEST['metkodeposmember'].'"></div>
	</div>
	<div class="form-group">
	  <label class="control-label col-sm-2">Nilai Diajukan <span class="text-danger">*</span></label>
	  	<div class="col-sm-10"><input name="nilaidiajukan" id="nilaidiajukan" class="form-control" placeholder="Silahkan Input Nilai yang diajukan" type="text"></div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-2">Alamat Objek <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input name="metalamatobjek" id="metalamatobjek" class="form-control" placeholder="Alamat Objek" type="text" value="'.$_REQUEST['metalamatobjek'].'"></div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-2"> </label>
		<div class="col-sm-5"><input name="metkotaobjek" id="metkotaobjek" class="form-control" placeholder="Kabupate/Kota Objek" type="text" value="'.$_REQUEST['metkotaobjek'].'"></div>
		<div class="col-sm-5"><input name="metkodeposobjek" id="metkodeposobjek" class="form-control" placeholder="Kode Pos Objek" type="text" value="'.$_REQUEST['metkodeposobjek'].'"></div>
	</div>
	<div class="form-group">
	<label class="control-label col-sm-2">Photo Debitur <span class="text-danger">*</span></label>
		<div class="col-sm-10"><input type="file" name="filephotodebitur" id="filephotodebitur" class="form-control" accept="image/*" required/></div>
	</div>';

}
?>
