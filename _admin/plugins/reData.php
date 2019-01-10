<?php
include_once('../../includes/jjt1502.php');
switch ($_REQUEST['mp']) {
	case "metclient":
echo "<select name='coClient' onChange='mametClient(this);'><option value=\"\">Select Partner</option>";
$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
echo "</select>";
		;
		break;

	case "metclientmedical":
		echo "<select name='coClient' onChange='mametClientMedical(this);'><option value=\"\">Select Partner</option>";
		$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
		while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
		{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
		echo "</select>";
		;
		break;

	case "metpolicy":
echo "<select name='coPolicy' onChange='DinamisWilayah(this);'><option value=\"\">Select Product</option>";
$brokerMetPolicy = mysql_query('select id, idcost, policyauto, typerate, byrate, produk from ajkpolis where idcost="'.$_GET['kode'].'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{
	$cekRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremi WHERE idclient="'.$_GET['kode'].'" AND idpolis="'.$brokerMetPolicy_['id'].'" AND status ="Aktif"'));
	if ($cekRate['idpolis']) {
	echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}else{
	echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}
}
echo "</select>";
		;
		break;

	case "metpolicyMedical":
		echo "<select name='coPolicy' onChange='DinamisWilayah(this);'><option value=\"\">Select Product</option>";
		$brokerMetPolicy = mysql_query('select id, idcost, policyauto, typerate, byrate, produk, freecover from ajkpolis where idcost="'.$_GET['kode'].'"');
		while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
		{
			$cekRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkmedical WHERE idpartner="'.$_GET['kode'].'" AND idproduk="'.$brokerMetPolicy_['id'].'" AND status="Aktif" AND del IS NULL'));
			if ($brokerMetPolicy_['freecover']=="Y") {
					echo '<option value="#" disabled>'.$brokerMetPolicy_['produk'].' (Free Cover)</option>';
			}else{
				if ($cekRate['idproduk']) {
					echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].'</option>';
				}else{
					echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].'</option>';
				}
			}
		}
		echo "</select>";
		;
		break;


	case "metpolicyClaim":
echo "<select name='coPolicy' onChange='DinamisWilayah(this);'><option value=\"\">Select Product</option>";
$brokerMetPolicy = mysql_query('select id, idcost, policyauto, typerate, byrate, produk from ajkpolis where idcost="'.$_GET['kode'].'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{
	$cekRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkrateklaim WHERE idclient="'.$_GET['kode'].'" AND idpolis="'.$brokerMetPolicy_['id'].'" AND status ="Aktif"'));
	if ($cekRate['idpolis']) {
		echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].' [Already Upload]</option>';
	}else{
		echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].'</option>';
		}
}
echo "</select>";
	;
	break;

	case "metclientExl":
echo "<select name='coClient' onChange='mametClientExcel(this);'><option value=\"\">Select Company</option>";
$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
echo "</select>";
		;
		break;

	case "metpolicyExl":
echo "<select name='coPolicy'><option value=\"\">Select Product</option>";
$brokerMetPolicy = mysql_query('select id, idcost, policyauto, typerate, byrate from ajkpolis where idcost="'.$_GET['kode'].'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{
	$cekRate = mysql_fetch_array(mysql_query('SELECT * FROM ajkexcelupload WHERE idc="'.$_GET['kode'].'" AND idp="'.$brokerMetPolicy_['id'].'" AND del IS NULL'));
	if ($cekRate['idp']) {
		echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['policyauto'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}else{
		echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['policyauto'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}
}
echo "</select>";
		;
		break;


	case "metclientUploadExl":
echo "<select name='coClient' onChange='mametClientExcel(this);'><option value=\"\">Select Partner</option>";
$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
echo "</select>";
	;
	break;

case "metpolicyUploadExl":
echo "<select name='coPolicy'><option value=\"\">Select Product</option>";
		$brokerMetPolicy = mysql_query('SELECT ajkpolis.id, ajkpolis.idcost, ajkpolis.policyauto, ajkpolis.produk, ajkclient.`name`, ajkexcelupload.idxls, ajkratepremi.rate
											  FROM ajkpolis
											  INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
											  LEFT JOIN ajkexcelupload ON ajkpolis.idcost = ajkexcelupload.idc AND ajkpolis.id = ajkexcelupload.idp
											  LEFT JOIN ajkratepremi ON ajkpolis.idcost = ajkratepremi.idclient AND ajkpolis.id = ajkratepremi.idpolis
											  WHERE ajkpolis.idcost = "'.$_GET['kode'].'"
											  GROUP BY ajkpolis.id');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{
	if ($brokerMetPolicy_['idxls'] ==null) {
//		echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['policyauto'].' [Setup Format Excel]</option>'; DISABLED SEMENTARA FORMAT EXCEL
		echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].'</option>';
	}elseif ($brokerMetPolicy_['rate'] ==null) {
		echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].' [Upload Rate]</option>';
	}else{
		echo '<option value="'.$brokerMetPolicy_['id'].'"><strong>'.$brokerMetPolicy_['produk'].'</strong></option>';
	}
}
echo "</select>";
	;
	break;

case "metpolicyIns":
echo "<select name='coPolicy'><option value=\"\">Select Product</option>";
$brokerMetPolicy = mysql_query('select * from ajkpolisasuransi where idcost="'.$_GET['kode'].'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{
	echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['policyauto'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
}
echo "</select>";
	;
	break;

case "metclientRateIns":
	echo "<select name='coClient' onChange='mametClientProdukRateIns(this);'><option value=\"\">Select Partner</option>";
	$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
		while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
		{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
	echo "</select>";
	;
	break;

case "metproductRateIns":
	echo "<select name='coPolicy'><option value=\"\">Select Product</option>";
	$brokerMetPolicy = mysql_query('SELECT ajkpolis.id, ajkpolis.idcost, ajkpolis.policyauto, ajkpolis.produk, ajkclient.`name`, ajkexcelupload.idxls, ajkratepremi.rate, ajkcobroker.id AS idbroker
								  FROM ajkpolis
								  INNER JOIN ajkclient ON ajkpolis.idcost = ajkclient.id
								  INNER JOIN ajkcobroker ON ajkclient.idc = ajkcobroker.id
								  LEFT JOIN ajkexcelupload ON ajkpolis.idcost = ajkexcelupload.idc AND ajkpolis.id = ajkexcelupload.idp
								  LEFT JOIN ajkratepremi ON ajkpolis.idcost = ajkratepremi.idclient AND ajkpolis.id = ajkratepremi.idpolis
								  WHERE ajkpolis.idcost = "'.$_GET['kode'].'"
								  GROUP BY ajkpolis.id');
	while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
	{
		if ($brokerMetPolicy_['idxls'] ==null) {
		//echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].' [Setup Format Excel]</option>'; disabled sementara 03062016
		echo '<option value="'.$brokerMetPolicy_['id'].'_'.$brokerMetPolicy_['idbroker'].'_'.$brokerMetPolicy_['idcost'].'">'.$brokerMetPolicy_['produk'].'</option>';
		}elseif ($brokerMetPolicy_['rate'] ==null) {
		echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].' [Rate Existing]</option>';
		}else{
		echo '<option value="'.$brokerMetPolicy_['id'].'_'.$brokerMetPolicy_['idbroker'].'_'.$brokerMetPolicy_['idcost'].'"><strong>'.$brokerMetPolicy_['produk'].'</strong></option>';
		}
	}
;
break;

case "mametInsuranceName_":
$kecoa = explode("_", $_GET['kode']);
$idproduk = $kecoa[0];
$idbroker = $kecoa[1];
$idpartner = $kecoa[2];
echo "<select name='coPolicy'><option value=\"\">Select Insurance</option>";
$brokerMetPolicy = mysql_query('select * from ajkinsurance where idc="'.$kecoa[1].'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{	echo '<option value="'.$brokerMetPolicy_['id'].'_'.$idbroker.'_'.$idproduk.'_'.$idpartner.'">'.$brokerMetPolicy_['name'].'</option>';	}
echo "</select>";
	;
	break;

case "metpolicyInsRate":
$semut = explode("_", $_GET['kode']);
$idinsurance = $semut[0];
$idproduk = $semut[2];
$idbroker = $semut[1];
$idpartner = $semut[3];
echo "<select name='coPolicyInsRate'><option value=\"\">Select Policy Insurance</option>";
$brokerMetPolicy = mysql_query('select * from ajkpolisasuransi where idbroker="'.$idbroker.'" AND idcost="'.$idpartner.'" AND idproduk="'.$idproduk.'" AND idas="'.$idinsurance.'"');
while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
{	$cekRateIns= mysql_fetch_array(mysql_query('SELECT * FROM ajkratepremiins WHERE idbroker="'.$idbroker.'" AND idclient="'.$idpartner.'" AND idproduk="'.$idproduk.'" AND idas="'.$idinsurance.'" AND status="Aktif"'));
	if ($cekRateIns['id']) {
	echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['policyauto'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}else{
	echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['policyauto'].' ['.$brokerMetPolicy_['typerate'].' by '.$brokerMetPolicy_['byrate'].']</option>';
	}
}
echo "</select>";
	;
	break;

case "metclientDocClaim":
	echo "<select name='coClient' onChange='mametClientDocClaim(this);'><option value=\"\">Select Partner</option>";
	$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'"');
		while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
		{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
	echo "</select>";
	;
	break;

case "metpolicyDocClaim":
echo "<select name='coPolicy' onChange='DinamisWilayah(this);'><option value=\"\">Select Product</option>";
	$brokerMetPolicy = mysql_query('select id, idcost, policyauto, typerate, byrate, produk from ajkpolis where idcost="'.$_GET['kode'].'"');
		while ($brokerMetPolicy_=mysql_fetch_array($brokerMetPolicy))
		{
//			echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].'</option>';
			$cekDoc_ = mysql_fetch_array(mysql_query('SELECT * FROM ajkdocumentclaimpartner WHERE idpolicy="'.$brokerMetPolicy_['id'].'"'));
			if ($cekDoc_['idpolicy']) {
				echo '<option value="'.$brokerMetPolicy_['id'].'" disabled>'.$brokerMetPolicy_['produk'].'</option>';
			}else{
				echo '<option value="'.$brokerMetPolicy_['id'].'">'.$brokerMetPolicy_['produk'].'</option>';
			}
		}
	echo "</select>";
	;
	break;

	case "metClientRegional":
echo "<select name='coClient' onChange='mametClientProdukRateIns(this);'><option value=\"\">Select Partner</option>";
$brokerMetClient = mysql_query('select id, name from ajkclient where idc="'.$_GET['kode'].'" ORDER BY name ASC');
while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
{	echo '<option value="'.$brokerMetClient_['id'].'">'.$brokerMetClient_['name'].'</option>';	}
echo "</select>";
	;
	break;


	case "_userproduct":
echo "<select name='coClient' onChange='UserProduk(this);'><option value=\"\">Select Product</option>";
	$brokerMetClient = mysql_query('select id, idcost, produk from ajkpolis where idcost="'.$_GET['kode'].'" ORDER BY produk ASC');
	while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
	{	echo '<option value="'.$brokerMetClient_['id'].'-'.$brokerMetClient_['idcost'].'">'.$brokerMetClient_['produk'].'</option>';	}
	echo "</select>";
	;
	break;

	case "_userRegional":
$metEx = explode("-", $_GET['kode']);
echo "<select name='coRegional' onChange='UserRegional(this);'><option value=\"\">Select Regional</option>";
		$brokerMetClient = mysql_query('select er, name from ajkregional where idclient="'.$metEx[1].'" ORDER BY name ASC');
		while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
		{	echo '<option value="'.$brokerMetClient_['er'].'">'.$brokerMetClient_['name'].'</option>';	}
		echo "</select>";
		;
		break;

	case "_userCabang":
	echo "<select name='coCabang'><option value=\"\">Select Branch</option>";
	$brokerMetClient = mysql_query('select er, name from ajkcabang where idreg="'.$_GET['kode'].'" ORDER BY name ASC');
	while ($brokerMetClient_ = mysql_fetch_array($brokerMetClient))
	{	echo '<option value="'.$brokerMetClient_['er'].'">'.$brokerMetClient_['name'].'</option>';	}
	echo "</select>";
	;
	break;



	default:
		;
} // switch


?>