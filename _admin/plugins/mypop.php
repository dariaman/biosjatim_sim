<?php
include_once('../../includes/jjt1502.php');
include_once('../../includes/db.php');
function duit($value)
{
	$orro = number_format($value, 0, ',', '.');
	return $orro;
}
switch ($_REQUEST['err']) {
	case "guranteeext":
$metGE = mysql_fetch_array(mysql_query('SELECT
ajkgeneraljaminan.idbroker,
ajkgeneraljaminan.idpartner,
ajkgeneraljaminan.idproduk,
ajkgeneraljaminan.idguarantee,
ajkgeneraljaminan.wilayah,
ajkgeneraljaminan.carahitungkontribusi,
ajkgeneraljaminan.carahitungresiko,
ajkcobroker.`name` AS broker,
ajkclient.`name` AS partner,
ajkpolis.produk,
ajkgeneralnamajaminan.namajaminan
FROM ajkgeneraljaminan
INNER JOIN ajkcobroker ON ajkgeneraljaminan.idbroker = ajkcobroker.id
INNER JOIN ajkclient ON ajkgeneraljaminan.idpartner = ajkclient.id
INNER JOIN ajkpolis ON ajkgeneraljaminan.idproduk = ajkpolis.id
INNER JOIN ajkgeneralnamajaminan ON ajkgeneraljaminan.idguarantee = ajkgeneralnamajaminan.id
WHERE ajkgeneraljaminan.id ="'.$_REQUEST['idge'].'"'));


$metGEX = mysql_query('SELECT * FROM ajkgeneraljaminanrate WHERE idgeneraljaminan = "'.$_REQUEST['idge'].'"');

echo '<div class="modal-content">
		<div class="modal-header text-center">
        <div class="col-md-12 text-center">
        	<h4 class="semibold modal-title text-success">'.$metGE['broker'].'</h4>
			<h4 class="semibold modal-title text-success">'.$metGE['partner'].'</h4>
        	<h4 class="semibold modal-title text-primary">'.$metGE['produk'].'</h4>
        	<h4 class="semibold modal-title text-primary"></h4>
        </div>
		<p class="text-muted">'.$metGE['namajaminan'].'</p>
        </div>

<div class="table-responsive panel-collapse pull out">
	<table class="table table-hover table-bordered">
    <thead>
    	<tr><th class="text-center" rowspan="2">Note</th>
            <th class="text-center" colspan="2">Contribution Premuium</th>
            <th class="text-center" colspan="2">Risk Only</th>
            <th class="text-center" rowspan="2">Status</th>
        </tr>
        <tr><th class="text-center">Comprehensive</th><th>Total Loss Only</th>
        	<th class="text-center">Comprehensive</th><th>Total Loss Only</th>
        </tr>
    </thead>
    <tbody>';
while ($metGEX_ = mysql_fetch_array($metGEX)) {
	if ($metGEX_['area'] !="" OR $metGEX_['area'] =="0") {
	$metGEXRegional = mysql_fetch_array(mysql_query('SELECT * FROM ajkgeneralarea WHERE id="'.$metGEX_['area'].'"'));
	echo '<tr>
			<td><span class="label label-primary">'.$metGEXRegional['area'].' - '.$metGEXRegional['lokasi'].'</span></td>';
	}else{
	echo '<tr>
			<td><span class="label label-success">###</span></td>';
	}

	//HITUNG KONTRIBUSI
	if ($metGE['carahitungkontribusi']=="Rate") {
	echo '<td class="text-center"><strong>'.$metGEX_['c_cpr_rate'].'</strong></td>
		  <td class="text-center"><strong>'.$metGEX_['c_tlo_rate'].'</strong></td>';
	}elseif ($metGE['carahitungkontribusi']=="Plafond") {
	echo '<td><strong>'.duit($metGEX_['c_cpr_plafondstart']).' s/d '.duit($metGEX_['c_cpr_plafondend']).' <br />: '.$metGEX_['c_cpr_plafondpersen'].'% From UP</strong></td>
		  <td><strong>'.duit($metGEX_['c_tlo_plafondstart']).' s/d '.duit($metGEX_['c_tlo_plafondend']).' <br />: '.$metGEX_['c_tlo_plafondpersen'].'% From UP</strong></td>';
	}else{
	echo '<td><strong>'.$metGEX_['c_cpr_nilaipersen'].'% From UP ('.duit($metGEX_['c_cpr_nilaiminimum']).')</strong></td>
		  <td><strong>'.$metGEX_['c_tlo_nilaipersen'].'% From UP ('.duit($metGEX_['c_tlo_nilaiminimum']).')</strong></td>';
	}
	//HITUNG KONTRIBUSI

	//HITUNG TLO
	if ($metGE['carahitungresiko']=="Rate") {
		echo '<td class="text-center"><strong>'.$metGEX_['r_cpr_rate'].'</strong></td>
			  <td class="text-center"><strong>'.$metGEX_['r_tlo_rate'].'</strong></td>';
	}elseif ($metGE['carahitungkontribusi']=="Plafond") {
		echo '<td><strong>'.duit($metGEX_['r_cpr_plafondstart']).' s/d '.duit($metGEX_['r_cpr_plafondend']).' <br />: '.$metGEX_['r_cpr_plafondpersen'].'% From UP</strong></td>
			  <td><strong>'.duit($metGEX_['r_tlo_plafondstart']).' s/d '.duit($metGEX_['r_tlo_plafondend']).' <br />: '.$metGEX_['r_tlo_plafondpersen'].'% From UP</strong></td>';
	}else{
		echo '<td><strong>'.$metGEX_['r_cpr_nilaipersen'].'% From UP ('.duit($metGEX_['r_cpr_nilaiminimum']).')</strong></td>
			  <td><strong>'.$metGEX_['r_tlo_nilaipersen'].'% From UP ('.duit($metGEX_['r_tlo_nilaiminimum']).')</strong></td>';
	}
	//HITUNG TLO
	echo '<td><span class="label label-success">'.$metGEX_['status'].'</span></td>';
echo '</tr>';
}
echo '</tbody>
    </table>
    </div>
    </div>

        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>';

echo '<script>$(\'#bs-modal-lg\').on(\'shown.bs.modal\', function () {	$(this).removeData(\'bs.modal\');	});</script>';
		;
		break;
	case "d":
		;
		break;
	default:
		;
} // switch
?>