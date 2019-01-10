<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
      	<div class="container-fluid">
        	<div class="page-header page-header-block">
            <div class="page-header-section"><h4 class="title semibold"><span class="figure"><i class="ico-home2"></i></span> Dashboards</h4></div>
            </div>
            <a href="../myFiles/160610-Tutorial-BSM.pdf" target="_blank">download manual book</a><br />';
$metGrafDN = $database->doQuery('SELECT DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") AS debitnotemonth,
										DATE_FORMAT(ajkdebitnote.tgldebitnote, "%Y") AS debitnoteyear,
										ajkdebitnote.tgldebitnote, count(ajkpeserta.nama) AS tmember,
										ajkpeserta.statuslunas, SUM(ajkpeserta.totalpremi) AS tpremi,
										SUM(ajkpeserta.plafond) AS tplafond,
										SUM(IF(ajkpeserta.statuslunas="0", ajkpeserta.totalpremi, 0)) AS premilunas,
										SUM(IF(ajkpeserta.statuslunas="1", ajkpeserta.totalpremi, 0)) AS premiblmlunas
								FROM ajkpeserta
								INNER JOIN ajkdebitnote ON ajkpeserta.iddn = ajkdebitnote.id
								WHERE ajkdebitnote.del IS NULL '.$q___1.'
								GROUP BY debitnotemonth');
while ($metGrafDN_ = mysql_fetch_array($metGrafDN)) {
    if ($metGrafDN_['debitnotemonth']=="01") {
        $bln_ = "Jan";
    } elseif ($metGrafDN_['debitnotemonth']=="02") {
        $bln_ = "Feb";
    } elseif ($metGrafDN_['debitnotemonth']=="03") {
        $bln_ = "Mar";
    } elseif ($metGrafDN_['debitnotemonth']=="04") {
        $bln_ = "Apr";
    } elseif ($metGrafDN_['debitnotemonth']=="05") {
        $bln_ = "Mei";
    } elseif ($metGrafDN_['debitnotemonth']=="06") {
        $bln_ = "Jun";
    } elseif ($metGrafDN_['debitnotemonth']=="07") {
        $bln_ = "Jul";
    } elseif ($metGrafDN_['debitnotemonth']=="08") {
        $bln_ = "Ags";
    } elseif ($metGrafDN_['debitnotemonth']=="09") {
        $bln_ = "Sep";
    } elseif ($metGrafDN_['debitnotemonth']=="10") {
        $bln_ = "Okt";
    } elseif ($metGrafDN_['debitnotemonth']=="11") {
        $bln_ = "Nov";
    } else {
        $bln_ = "Des";
    }
    //['Jan', 4700],
    $metPremiPaid .= '[\''.$bln_.'\','.$metGrafDN_['premilunas'].'],';
    $metPremiUnpaid .= '[\''.$bln_.'\','.$metGrafDN_['premiblmlunas'] .'],';

    $jPlafond +=$metGrafDN_['tplafond'];
    $jPremi +=$metGrafDN_['tpremi'];
    $jPeserta +=$metGrafDN_['tmember'];
    $jPremiPaid +=$metGrafDN_['premilunas'];
    $jPremiUnpaid +=$metGrafDN_['premiblmlunas'];
}
/*
echo $metPremiPaid.'<br />';
echo $metPremiUnpaid.'<br />';
*/
//echo $metPlafond.'<br />';
//echo $metPremium.'<br />';


$metPeserta = $database->doQuery('SELECT ajkdebitnote.id,
										 ajkdebitnote.tgldebitnote,
										 DATE_FORMAT(ajkdebitnote.tgldebitnote, "%d") AS debitnoteday,
										 DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") AS debitnotemonth,
										 SUBSTR(DAYNAME(ajkdebitnote.tgldebitnote), 1, 3) AS nameday,
										 Sum(ajkdebitnote.premiclient) AS totalpremi,
										 ajkdebitnote.tgldebitnote,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 01 then ajkpeserta.nama END) AS TPJan,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 02 then ajkpeserta.nama END) AS TPFeb,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 03 then ajkpeserta.nama END) AS TPMar,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 04 then ajkpeserta.nama END) AS TPApr,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 05 then ajkpeserta.nama END) AS TPMei,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 06 then ajkpeserta.nama END) AS TPJun,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 07 then ajkpeserta.nama END) AS TPJul,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 08 then ajkpeserta.nama END) AS TPAgs,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 09 then ajkpeserta.nama END) AS TPSep,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 10 then ajkpeserta.nama END) AS TPOkt,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 11 then ajkpeserta.nama END) AS TPNov,
										 Count(case when DATE_FORMAT(ajkdebitnote.tgldebitnote, "%m") = 12 then ajkpeserta.nama END) AS TPDes,
										 Count(ajkpeserta.nama) AS tmember
								FROM ajkdebitnote
								INNER JOIN ajkpeserta ON ajkdebitnote.id = ajkpeserta.iddn
								WHERE ajkdebitnote.del IS NULL '.$q___1.'
								GROUP BY debitnotemonth');
while ($metPeserta_ = mysql_fetch_array($metPeserta)) {
    //$jPlafond_ .='[\''.$metPeserta_['nameday'].'\', '.$metPeserta_['tmember'].'],';
    $memberJan += $metPeserta_['TPJan'];
    $memberFeb +=$metPeserta_['TPFeb'];
    $memberMar +=$metPeserta_['TPMar'];
    $memberApr +=$metPeserta_['TPApr'];
    $memberMei +=$metPeserta_['TPMei'];
    $memberJun +=$metPeserta_['TPJun'];
    $memberJul +=$metPeserta_['TPJul'];
    $memberAgs +=$metPeserta_['TPAgs'];
    $memberSep +=$metPeserta_['TPSep'];
    $memberOkt +=$metPeserta_['TPOkt'];
    $memberNov +=$metPeserta_['TPNov'];
    $memberDes +=$metPeserta_['TPDes'];

    /*
    $jDebitur .= $metPeserta_['tmember'].'-';
    $mettotdata_ += $metPeserta_['tmember'];
    */
}
$jDebitur_ = explode("-", $jDebitur);
if ($jDebitur_[0]=="") {
    $jDebitur__1 = 0;
} else {
    $jDebitur__1 = $jDebitur_[0];
}
if ($jDebitur_[1]=="") {
    $jDebitur__2 = 0;
} else {
    $jDebitur__2 = $jDebitur_[1];
}
if ($jDebitur_[2]=="") {
    $jDebitur__3 = 0;
} else {
    $jDebitur__3 = $jDebitur_[2];
}
if ($jDebitur_[3]=="") {
    $jDebitur__4 = 0;
} else {
    $jDebitur__4 = $jDebitur_[3];
}
if ($jDebitur_[4]=="") {
    $jDebitur__5 = 0;
} else {
    $jDebitur__5 = $jDebitur_[4];
}
if ($jDebitur_[5]=="") {
    $jDebitur__6 = 0;
} else {
    $jDebitur__6 = $jDebitur_[5];
}
if ($jDebitur_[6]=="") {
    $jDebitur__7 = 0;
} else {
    $jDebitur__7 = $jDebitur_[6];
}
if ($jDebitur_[7]=="") {
    $jDebitur__8 = 0;
} else {
    $jDebitur__8 = $jDebitur_[7];
}
if ($jDebitur_[8]=="") {
    $jDebitur__9 = 0;
} else {
    $jDebitur__9 = $jDebitur_[8];
}
if ($jDebitur_[9]=="") {
    $jDebitur__10 = 0;
} else {
    $jDebitur__10 = $jDebitur_[9];
}
if ($jDebitur_[10]=="") {
    $jDebitur__11 = 0;
} else {
    $jDebitur__11 = $jDebitur_[10];
}
if ($jDebitur_[11]=="") {
    $jDebitur__12 = 0;
} else {
    $jDebitur__12 = $jDebitur_[11];
}
$metStatData = $database->doQuery('SELECT
COUNT(nama) statusjData,
statusaktif
FROM ajkpeserta
WHERE del IS NULL AND iddn !="" '.$q___.'
GROUP BY ajkpeserta.statusaktif
ORDER BY statusaktif DESC');
while ($metStatData_ = mysql_fetch_array($metStatData)) {
    $_metStat .= '<li class="list-group-item">'.$metStatData_['statusaktif'].' <span class="semibold pull-right">'.duit($metStatData_['statusjData']).'</span></li>';
}

echo '	<div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-sm-12">
                <div class="panel ">
                  <div class="panel-heading pt10">
                    <div class="panel-toolbar"><h5 class="semibold nm ellipsis">Payment Premium</h5></div>
                  </div>
                  <div class="panel-body pt0">
                    <div class="chart mt10" id="chart-payments" style="height:250px;"></div>
                  </div>
                  <div class="panel-footer hidden-xs">
                    <ul class="nav nav-section nav-justified">
                      <li>
                        <div class="section">
                          <h4 class="bold text-default mt0 mb5" data-toggle="counterup">'.duit($jPeserta).'</h4>
                          <p class="nm text-muted">
                            <span class="semibold">Member</span>
                          </p>
                        </div>
                      </li>
                      <li>
                        <div class="section">
                          <h4 class="bold text-default mt0 mb5" data-toggle="counterup">'.duit($jPremiPaid).'</h4>
                          <p class="nm text-muted">
                            <span class="semibold">Paid</span>
                            <span class="text-muted mr5 ml5">&nbsp;</span>
                            <span class="text-success"></i> '.duit($jPremiPaid / $jPremi * 100).'%</span>
                          </p>
                        </div>
                      </li>
                      <li>
                        <div class="section">
                          <h4 class="bold text-default mt0 mb5"><span data-toggle="counterup">'.duit($jPremiUnpaid).'</span></h4>
                          <p class="nm text-muted">
                            <span class="semibold">Unpaid</span>
                            <span class="text-muted mr5 ml5">&nbsp;</span>
                            <span class="text-success">'.duit($jPremiUnpaid / $jPremi * 100).'%</span>
                          </p>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="panel panel-minimal">
              <div class="panel">
                <div class="panel-body">
                <!--<h4 class="semibold nm">Total <code>'.duit($mettotdata_).'</code> Debitur</h4>
                <h4 class="thin mt5 text-muted"> <span data-toggle="counterup"></span></h4>
                <div class="chart" style="height:120px;" id="statspayments"></div>-->
                  <center><div id="chartContainer1" style="width: 100%; height: 300px;display: inline-block;"></div><br /><br /><br />
                </div>
              </div>
            </div>
          </div>

					<div class="col-md-6">
						<div class="panel panel-default">
              <div class="panel-heading">
                  <h5 class="panel-title"><i class="ico-database3 mr5"></i>Status Debitur</h5>
              </div>
              <ul class="list-group">
              	'.$_metStat.'
                 <li class="list-group-item"><i class="ico-circle mr5 text-success"></i>Your IP<span class="pull-right semibold">'.$alamat_ip.'</span></li>
              </ul>
            </div>
		      </div>

          <div class="col-md-6">
          <div class="panel panel-default">
          <div class="panel-heading">
              <h5 class="panel-title"><i class="glyphicon glyphicon-user"></i> User Online <a id="refresh-btn" class="btn btn-xs"><i class="glyphicon glyphicon-refresh"></i></a></h5>
          </div>
          <ul class="list-group">
            <li class="list-group-item">
              <div class="input-group">
                <input class="form-control input-sm" type="text" id="search-online" placeholder="Cari...">
                <div class="input-group-addon">
                  <a id="search-button" href="javascript:void(0)"><i class="glyphicon glyphicon-search"></i></a>
                </div>
              </div>
            </li>
         </ul>

          <div id="online-grid" class="list-group" style="height:342px;overflow-x:auto"></div>
          </div>
          </div>



        </div>
                    <!--/ END Right Side -->
                </div>
            </div>
            <!--/ END Template Container -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->
        </section>
        <!--/ END Template Main -->';

echo '<link rel="stylesheet" href="templates/{template_name}/plugins/selectize/css/selectize.css">
      <link rel="stylesheet" href="templates/{template_name}/plugins/flot/css/flot.css">
      	    <!--/ GRAFIK -->
	  <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.resize.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.categories.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.time.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.tooltip.js"></script>
      <script type="text/javascript" src="templates/{template_name}/plugins/flot/js/jquery.flot.spline.js"></script>
';

echo "<script>
function getOnline(loader=true){
  $.ajax({
    type  : 'GET',
    dataType: 'html',
    url   	: 'onlinegrid.php',
    data  : {
      'search'	: $('#search-online').val(),
    },
    success : function(msg) {
      $( '#online-grid' ).html(msg);
    },
    beforeSend: function( xhr ) {
      if(loader==true){
        $( '#online-grid' ).html('<div class=\"text-center\"><i class=\"fa fa-cog fa-spin fa-2x\"></div>');
      }
    }
  });
}

$.plot('#chart-payments', [{
	label: 'Premium Paid',
    color: 'blue',
    data: [
    		".$metPremiPaid."
        ]
    }, {
    label: 'Premium Unpaid',
    color: '#DC554F',
    data: [
    		".$metPremiUnpaid."
        ]
    }], {
    series: {
    	lines: {	show: true	},
        splines: {	show: false,
                   	tension: 0.4,
                   	lineWidth: 2,
                    fill: 0.8
		},
        points: {	show: true,
                    radius: 4
                }
    },
    grid: {
    	borderColor: 'rgba(0, 0, 0, 0.05)',
        borderWidth: 1,
        hoverable: true,
        backgroundColor: 'transparent'
	},
    tooltip: true,
    tooltipOpts: {
    	content: '%x : %y',
        defaultTheme: false
	},
    xaxis: {
    	tickColor: 'rgba(0, 0, 0, 0.05)',
        mode: 'categories'
    },
    yaxis: {	tickColor: 'rgba(0, 0, 0, 0.05)'	},
    shadowSize: 10
});
</script>";

echo "<script>
'use strict';

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define([
            'selectize',
            'jquery.flot',
            'jquery.flot.resize',
            'jquery.flot.categories',
            'jquery.flot.time',
            'jquery.flot.tooltip',
            'jquery.flot.spline'
        ], factory);
    } else {
        factory();
    }
}(function () {

    $(function () {

      getOnline();
      $('#refresh-btn').click(function(){
        getOnline();
      })

      $('#search-button').click(function(){
        getOnline();
      })

      var startcurrent = 10;
      setInterval(function() {
        getOnline(false);
      }, 10000);

		// Stats
        // ================================
        // default options
        var option = {
            series: {
                lines: { show: false },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 2,
                    fill: 0.5
                },
                points: {
                    show: false,
                    radius: 4
                }
            },
            grid: {
                borderColor: '#eee',
                borderWidth: 0,
                hoverable: true,
                backgroundColor: '#fcfcfc'
            },
            tooltip: true,
            tooltipOpts: { content: '%x : %y' },
            xaxis: {
                tickColor: '#fcfcfc',
                mode: 'categories'
            },
            yaxis: { tickColor: '#eee' },
            shadowSize: 0
        };

		// Selectize
        // ================================
        $('#selectize-customselect').selectize();

		// Stats #1
        $.plot('#statspayments', [{
            color: '#DC554F',
            data: [ ".$jPlafond_." ]
        }], option);

        // Sparkline
        // ================================
        $('.sparklines').sparkline('html', {
            enableTagOptions: true
        });

    });
}));
</script>";

echo '<script type="text/javascript">
		window.onload = function () {
			var chart = new CanvasJS.Chart("chartContainer1", {
				theme: "theme2",
				title: {	text: "Debitur - Monthly"	},
				animationEnabled: true,
				axisX: {	valueFormatString: "MMM",
							interval: 1,
							intervalType: "month"
				},
				axisY: {	includeZero: false
				},
				data: [{
					type: "line",
					//lineThickness: 3,
					dataPoints: [
					{ x: new Date('.date(Y).', 00, 1), y: '.$memberJan.' },
					{ x: new Date('.date(Y).', 01, 1), y: '.$memberFeb.' },
					<!--{ x: new Date('.date(Y).', 02, 1), y: '.$memberMar.', indexLabel: "highest", markerColor: "red", markerType: "triangle" },-->
					{ x: new Date('.date(Y).', 02, 1), y: '.$memberMar.' },
					{ x: new Date('.date(Y).', 03, 1), y: '.$memberApr.' },
					{ x: new Date('.date(Y).', 04, 1), y: '.$memberMei.' },
					{ x: new Date('.date(Y).', 05, 1), y: '.$memberJun.' },
					{ x: new Date('.date(Y).', 06, 1), y: '.$memberJul.' },
					{ x: new Date('.date(Y).', 07, 1), y: '.$memberAgs.' },
					<!--{ x: new Date('.date(Y).', 08, 1), y: '.$memberSep.', indexLabel: "lowest", markerColor: "DarkSlateGrey", markerType: "cross" },-->
					{ x: new Date('.date(Y).', 08, 1), y: '.$memberSep.' },
					{ x: new Date('.date(Y).', 09, 1), y: '.$memberOkt.' },
					{ x: new Date('.date(Y).', 10, 1), y: '.$memberNov.' },
					{ x: new Date('.date(Y).', 11, 1), y: '.$memberDes.' }
					]
				}
				]
			});
			chart.render();
		}
	</script>';
?>
<script src="libraries/barchart/canvasjs.min.js"></script>
