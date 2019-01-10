<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// Copyright (C) 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';

echo '<div class="page-header-section"><h2 class="title semibold">Maps Tablet</h2></div>
		<div class="page-header-section">
		</div>
	</div>
      <div class="row">
      	<div class="col-md-12">
        	<div class="panel panel-default">

<table class="table table-hover table-bordered table-striped table-responsive" id="column-filtering">
<thead>
<tr><th width="1%" class="text-center">No</th>
	<th class="text-center">Name</th>
	<th width="1%" class="text-center">Phone</th>
	<th class="text-center">Latitude</th>
	<th class="text-center">Longitude</th>
	<th class="text-center">Map</th>
	<th width="1%" class="text-center">Date</th>
</tr>
</thead>
<tbody>';

$metGPS = $database->doQuery('SELECT
CONCAT(useraccess.firstname," ",useraccess.lastname) AS namamarketing,
ajkgps.id,
ajkgps.longitude,
ajkgps.latitude,
ajkgps.phone,
DATE_FORMAT(ajkgps.datettime,"%Y-%m-%d") AS tgl
FROM ajkgps
INNER JOIN useraccess ON ajkgps.username = useraccess.id
WHERE ajkgps.id != "" '.$q___.'
ORDER BY ajkgps.id DESC');
while ($metGPS_ = mysql_fetch_array($metGPS)) {
if ($metGPS_['latitude'] == "" OR $metGPS_['longitude']=="") {
	$viewgpsnya = '';
}else{
	$viewgpsnya = '<a href="ajk.php?re=gpsbios&vgps='.$thisEncrypter->encode($metGPS_['id']).'"><span class="label label-primary">view</span></a>';
}
echo '<tr>
   	<td align="center">'.++$no.'</td>
   	<td>'.$metGPS_['namamarketing'].'</td>
   	<td>'._convertDate($metGPS_['phone']).'</td>
   	<td align="center">'.$metGPS_['longitude'].'</td>
   	<td align="center">'.$metGPS_['latitude'].'</td>
   	<td align="center">'.$viewgpsnya.'</td>
   	<td align="center"><strong>'.$metGPS_['tgl'].'</strong></td>
	</tr>';
}
echo '</tbody>
		<tfoot>
        <tr><th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="search" class="form-control" name="search_engine" placeholder="Name"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
            <th><input type="hidden" class="form-control" name="search_engine"></th>
        </tr>
        </tfoot></table>
    	</div>
		</div>';
if ($_REQUEST['vgps']) {
$vGPS = mysql_fetch_array($database->doQuery('SELECT
ajkgps.id,
ajkgps.longitude,
ajkgps.latitude
FROM ajkgps
WHERE ajkgps.id = "'.$thisEncrypter->decode($_REQUEST['vgps']).'"'));
	echo '<div id="map" class="col-md-12"></div>
					<script>
					window.onload = function() {
					var latlng = new google.maps.LatLng('.$vGPS['latitude'].', '.$vGPS['longitude'].');
					var map = new google.maps.Map(document.getElementById(\'map\'), {
					center: latlng,
					zoom: 15,
					mapTypeId: google.maps.MapTypeId.ROADMAP
					});
					var marker = new google.maps.Marker({
					position: latlng,
					map: map,
					title: \'Set lat/lon values for this object\',	draggable: true
					});
					google.maps.event.addListener(marker, \'dragend\', function(a) {
					console.log(a);
					var div = document.createElement(\'div\');
					div.innerHTML = a.latLng.lat().toFixed(4) + \', \' + a.latLng.lng().toFixed(4);
					document.getElementsByTagName(\'body\')[0].appendChild(div);
					});
					};
					</script>';
echo '<style>	#map {	height: 500px;	}	</style>';
}
echo '</div>
</div></div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>