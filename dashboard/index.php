<?php
    include "../param.php";
?>

<!DOCTYPE html>
<html lang="en">
	<!--<![endif]-->
	<?php
        _head($user, $namauser, $photo, $logo);
    ?>

	<body>
		<!-- begin #page-loader -->
		<div id="page-loader" class="page-loader fade in"><span class="spinner">Loading...</span></div>
		<!-- end #page-loader -->

		<!-- begin #page-container -->
		<div id="page-container" class="fade page-container page-header-fixed page-sidebar-fixed page-with-two-sidebar page-with-footer page-with-top-menu page-without-sidebar">
			<?php
            _header($user, $namauser, $photo, $logo, $logoklient);
            _sidebar($user, $namauser, '', '');
            ?>
			<!-- begin #content -->
			<div id="content" class="content">
				<div>
				<?php
				if($idas == ""){
				?>

				<h1 class="page-header"><a href="<?php echo $path; ?>myFiles/tutorial.pdf" target="_blank">Unduh Buku Panduan </a><font size="1" color="grey"><i>Last Update 21 - 05 - 2018</i></font></h1>
				</div>
				<!-- begin row -->
				<div class="row">
			    <!-- begin col-6 -->
			    <div class="col-lg-8">
				    <!-- begin panel -->
	          <div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
	              <!-- begin title -->
	              <h4 class="text-white m-t-0 m-b-10">
	                  <i class="fa fa-snowflake-o text-success-light"></i> Premi
	                  <small class="text-muted m-l-5">12 bulan</small>
	              </h4>
	              <!-- end title -->
	              <!-- begin chart -->
	              <canvas id="monthly-report-chart" height="100"></canvas>
	              <!-- end chart -->
	          </div>
	          <!-- end panel -->
			    </div>
			    <!-- end col-8 -->

					 <!-- begin col-4 -->
					<div class="col-lg-4">
		        <!-- begin panel -->
	          <div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
	            <!-- begin title -->
	            <h4 class="text-white m-t-0 m-b-10">
	                <i class="fa fa-snowflake-o text-success-light"></i> Status Peserta
	                <small class="text-muted m-l-5">12 bulan</small>
	            </h4>
	            <!-- end title -->
	            <!-- begin chart -->
	            <canvas id="statuspeserta" height="215px" class="width-full"></canvas>
	            <!-- end chart -->
	          </div>
	          <!-- end panel -->
			    </div>
			    <!-- end col-4 -->
				</div>
				<br>
				<div class="row">
					<!-- KALKULATOR BEGIN -->
					<div class="col-lg-4">
						<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
              <h4 class="text-white m-t-0 m-b-10">
                  <i class="fa fa-snowflake-o text-success-light"></i> Kalkulator Premi
              </h4>
              <form id="frm-calc" width="100%" action="javascript:hitung();" class="form-horizontal">
								<div class="form-group">
					        <label class="control-label col-md-3">Karpot</label>
					        <div class="col-md-9">
					          <select class="form-control" id="karpot" onchange="hidetabel(this)">
					          	<option value="">- Pilih -</option>
					          	<option value="4">Umum</option>
					          	<option value="5">Pensiunan</option>Fpre
					          	<option value="3.75">Komisaris,Direksi, dan pegawai Bank Jatim</option>
					          </select>
					        </div>
						    </div>
								<div class="form-group">
					        <label class="control-label col-md-3">Plafond</label>
					        <div class="col-md-9">
					          <input id="plafond" name="plafond" class="form-control" type="text" value="" placeholder="Plafond" autocomplete="off">
					        </div>
						    </div>
								<div class="form-group">
					        <label class="control-label col-md-3">Bunga (%)</label>
					        <div class="col-md-9">
					          <input id="bunga" name="bunga" class="form-control" type="number" step="0.01" value="" placeholder="Bunga" autocomplete="off">
					        </div>
						    </div>

								<div class="form-group">
					        <label class="control-label col-md-3">Tenor (Tahun) </label>
					        <div class="col-md-9">
					          <input id="tenor" name="tenor" class="form-control" type="number" min="1" max="20" placeholder="Tenor" autocomplete="off">
					        </div>
						    </div>

						    <hr>
								<div class="form-group">
					        <label class="control-label col-md-3">Asumsi Premi</label>
					        <div class="col-md-9">
					          <input id="premi" name="premi" class="form-control" type="text" disabled="">
					        </div>
						    </div>
						    <div class="text-center">
						    	<button type="submit" class="btn btn-primary">Hitung</button>
						    	<a href="javascript:;" id="btn-tbl-angsuran" name="btn-tbl-angsuran" onclick="tabel();" class="btn btn-success">Tabel Angsuran</a>
						  	</div>
							</form>
	          </div>
					</div>
					<!-- KALKULATOR END -->
					<!-- SHARE ASURANSI BEGIN-->
						<?php
                    if ($cabang == 1 and ($level == 99 or $level == 9)) {
                        ?>
							<div class="col-lg-8" style="height:342px;overflow-x:auto">
								<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
			              <!-- begin title -->
			              <h4 class="text-white m-t-0 m-b-10">
			                  <i class="fa fa-snowflake-o text-success-light"></i> Pembagian Asuransi
			              </h4>
			              <!-- end title -->
			              <div>
											<table id="tbl-share" name="tbl-share" class="table" width="100%">
												<thead>
													<tr>
														<th class="bg-inverse text-white">No</th>
														<th class="bg-inverse text-white text-center">Asuransi</th>
														<th class="bg-inverse text-white text-center">Premi All</th>
														<th class="bg-inverse text-white text-center">Premi This Month</th>
														<th class="bg-inverse text-white">Persentase This Month</th>
														<th class="bg-inverse text-white"></th>
													</tr>
												</thead>
												<tbody>
													<?php


														$query = "select *,FORMAT(count_all/total*100,2)as persentase_all,FORMAT(count_month/total_month*100,2)as persentase_month
																			from(
																			select nm_asuransi,
																						 count(*)as count_all,
																						 sum(totalpremi)as sum_all,
																						 sum(case when DATE_FORMAT(tglakad,'%Y%m') = DATE_FORMAT(now(),'%Y%m') then 1 else 0 end)as count_month,
																						 sum(case when DATE_FORMAT(tglakad,'%Y%m') = DATE_FORMAT(now(),'%Y%m') then totalpremi else 0 end)as sum_month,
																						 (select count(*) from vpeserta where statusaktif != 'Pending')as total,
			 																			(select count(*) from vpeserta where statusaktif != 'Pending' and DATE_FORMAT(tglakad,'%Y%m') = DATE_FORMAT(now(),'%Y%m'))as total_month
																			from vpeserta
																			where statusaktif = 'Inforce'
																			group by nm_asuransi
																			)as temp";

		                        $no= 1;
		                        $rshare = "";
		                        $equery = mysql_query($query);
		                        while ($share = mysql_fetch_array($equery)) {
		                            $targetpersen = $share['persentase_month'];
		                            // if ($targetpersen <= 50) {
		                            //     $flag = "danger";
		                            // } elseif ($targetpersen > 50 and $targetpersen <= 90) {
		                            //     $flag = "warning";
		                            // } else {
		                                $flag = "success";
		                            // }

		                            echo '<tr>
									<td class="text-center" width="1%">'.$no.'</td>
									<td class="text-center text-white" width="20%">'.$share['nm_asuransi'].'</td>
									<td class="text-center text-white" width="20%">'.duit($share['sum_all']).'</td>
									<td class="text-center text-white" width="20%">'.duit($share['sum_month']).'</td>
									<td class="text-center" width="1%">'.$share['persentase_month'].'%</td>
									<td class="text-center" width="10%"><div class="progress"><div class="progress-bar progress-bar-'.$flag.'" style="width:'.$targetpersen.'%"><font color="black">'.$targetpersen.'%</font></div></div</td>
								</tr>';
		                            $no++;
		                        } 
		                       ?>
						 						</tbody>
					 						</table>
			              </div>
			          </div>
							</div>
						<?php
                    }
                ?>
				</div>
        <br />
        <div class="row">
          <div class="col-lg-4">
						<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
              <div class="row">
                <div class="col-lg-6">
                  <h4 class="text-white m-t-0 m-b-10">
                      User Online
                      <a id="refresh-btn" class="btn btn-primary btn-xs"><i class="fa fa-refresh"></i></a>
                  </h4>
                </div>
                <div class="col-lg-6">
                  <div class="input-group">
                    <input class="form-control input-sm" type="text" id="search-online" placeholder="Cari...">
                    <div class="input-group-addon">
                      <a id="search-button" href="javascript:void(0)"><i class="fa fa-search"></i></a>
                    </div>
                  </div>

                </div>
              </div>

              <br />

              <div id="online-grid" class="list-group" style="height:342px;overflow-x:auto"></div>

	          </div>
					</div>
					<!-- ALERT KARY JATIM BEGIN-->
						<?php
              $cekCabang = mysql_fetch_array(mysql_query('SELECT * FROM ajkcabang WHERE idclient="'.$idclient.'" AND er="'.$cabang.'"'));
              if ($cekCabang['level'] == 1) {
                  $cabangverifikasi = '';
              } elseif ($cekCabang['level'] == 2) {
                  $cabangverifikasi = " uac.regional = '".$cekCabang['idreg']."' AND";
              } else {
                  $cabangverifikasi = " uac.branch = '".$scabang."' AND";
              }

              $querybak = "SELECT *, premitahunan*tahun as premiout
												FROM(
												SELECT nopinjaman,
																nama,
																tglakad,
																concat(year(now()),right(left(tglakad,10),6))as tglout,
																DATEDIFF(concat(year(now()),right(left(tglakad,10),6)),now())AS hari,
																(premi / (tenor/12))as premitahunan,
																IFNULL((SELECT sum(nilaibayar)
																 FROM ajkbayar
																 WHERE ajkbayar.idpeserta = ajkpeserta.id),0)as total_bayar,
																CASE WHEN TIMESTAMPDIFF(MONTH, tglakad, NOW()) - TIMESTAMPDIFF(YEAR, tglakad, NOW())*12 > 5 THEN
																	TIMESTAMPDIFF(YEAR, tglakad, NOW()) + 1
																	ELSE
																	TIMESTAMPDIFF(YEAR, tglakad, NOW())
																	END as tahun,
																	idpolicy,
																	(SELECT name
																	 FROM ajkcabang
																	 WHERE ajkcabang.er = ajkpeserta.cabang)as cabang
												FROM ajkpeserta
												)as temp
												WHERE hari <=30 AND
															idpolicy = 12 AND
															tahun > 0 AND
															".$cabangverifikasi."
															total_bayar != premitahunan*tahun
												ORDER BY tglout DESC ";

							$query = "SELECT a.idpeserta,
															 a.nopinjaman,
															 a.nama,
															 a.nmcabang,
															 ajkcadanganas.duedate,
															 statusaktif,idbroker,
															 ajkcadanganas.nilai_cicilan,
															 DATEDIFF(duedate,CURRENT_DATE())as hari
												FROM vpeserta a
												INNER JOIN ajkcadanganas on ajkcadanganas.idpeserta = a.idpeserta
												WHERE ((statusaktif='Pending' and idbroker = 2) or (statusaktif='Inforce' and idbroker = 1)) and 
															duedate <= DATE_ADD(CURRENT_DATE(),INTERVAL 30 DAY) AND 
															duedate >= '2018-07-01' and 
															".$cabangverifikasi."
															ajkcadanganas.del is null and 
															DATEDIFF(duedate,CURRENT_DATE()) >= 0
												order by duedate,a.nmcabang";
							$_SESSION['rekapjatuhtempo'] = $query;
	            $equery = mysql_query($query);	            
	            $countquery = mysql_num_rows($equery);
	            if ($countquery > 0) {
            ?>
						<div class="col-lg-8" style="height:342px;overflow-x:auto">
							<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
		              <!-- begin title -->
		              <h4 class="text-white m-t-0 m-b-10">
		                  <i class="fa fa-snowflake-o text-success-light"></i>Pengingat Jatuh Tempo Premi<a href="../modules/modEXLdl.php?Rxls=rekapjatuhtempo">..</a>
		              </h4>
		              <!-- end title -->
		              <div>
										<table id="tbl-outstanding" name="tbl-outstanding" class="table" width="100%">
											<tr>
												<th class="text-center" width="1%">No</th>
												<th class="text-center" width="5%">No Pinjaman</th>
												<th class="text-center" width="20%">Nama</th>
												<th class="text-center" width="10%">Cabang</th>
												<th class="text-center" width="10%">Tgl J.Tempo</th>
												<th class="text-center" width="1%">hari</th>
											</tr>
											<tbody>
									<?php
										$no= 1;
		                while ($query_ = mysql_fetch_array($equery)) {
                    echo '<tr>
																	<td class="text-center">'.$no.'</td>
																	<td class="text-center">'.$query_['nopinjaman'].'</td>
																	<td class="text-center">'.$query_['nama'].'</td>
																	<td class="text-center">'.$query_['nmcabang'].'</td>
																	<td class="text-center">'._convertDate($query_['duedate']).'</td>
																	<td class="text-center">'.$query_['hari'].'</td>
																</tr>';
                    $no++;
                		}
                	?>
				 							</tbody>
			 							</table>
	              	</div>
	          	</div>
						</div>
						<?php
            }
            ?>
					<!-- ALERT KARY JATIM END-->
        </div>
				<br>
				<?php 
					//cek tipe
					$new = "";
					$edit = "";
					$view = 0;
					$del = 0;
					$query = "SELECT ajkcabang.name AS nmcabang,
	ar.`cabang`,
	ar.`periode`,
	ar.`keterangan`,
	ar.`attachment`,
	SUM(ap.`plafond`) AS 'Total Plafond',
	SUM(ap.`premi`) AS 'Total Premi',
	IFNULL(ar.`nilai_bayar`,0) AS 'Total Resturno'
FROM `ajkhisresturno` ar 
INNER JOIN ajkcabang ON ajkcabang.er = ar.cabang
INNER JOIN `ajkpeserta` ap ON ap.`cabang`=ar.cabang
WHERE ar.del IS NULL 
GROUP BY ar.cabang,ar.periode
ORDER BY ar.input_date DESC";
					// echo $query;

					if($tipe=="Admin"){
						$new = '<a href="newresturno.php" class="btn btn-success">New</a>';
						$edit = 1;
						$view = 1;
						$del = 1;
						$result = mysql_query($query);
					}else{
						if ($levelcabang == 1) {
							$cabangverifikasi = '';
						// } elseif ($levelcabang == 2) {
						// 		$cabangverifikasi = " and idreg = '".$regional."'";
						} else {
								$cabangverifikasi = " and er = '".$cabang."'";
						}
						$query = $query.$cabangverifikasi;

						$result = mysql_query($query);
						if(mysql_num_rows($result)>0){
							$view = 1;
						}
					}					 
				?>		
				<?php 
					if($view == 1){
				?>
					<!-- RESTURNO -->
					<div class="row">
						<div class="col-lg-10" style="height:400px;overflow-x:auto">
							<div class="panel no-rounded-corner bg-inverse text-white wrapper m-b-0">
									<!-- begin title -->
									<h4 class="text-white m-t-0 m-b-10">
											<i class="fa fa-snowflake-o text-success-light"></i>Resturno <?php echo $new;?>
									</h4>
									<!-- end title -->
									<div>
										<table id="tbl-resturno" name="tbl-resturno" class="table" width="100%">
											<tr>
												<th class="text-center" width="8%">Cabang</th>
												
												<th class="text-right" width="8%">Total Plafond</th>
												<th class="text-right" width="8%">Total Premi</th>
												<th class="text-right" width="10%">Total Resturno</th>

												<th class="text-center" >Periode</th>
												<th class="text-center" width="8%">Keterangan</th>
												<th class="text-center" width="5%">Bukti Setor</th>
												<th class="text-center" width="3%"></th>
											</tr>
											<tbody>
												<?php
													while ($query_ = mysql_fetch_array($result)) {
														$data = explode('|',$query_['periode']);
														$periode = date('M y', strtotime(date('Y-d-m', strtotime('01/' . str_replace('-', '/', $data[0]))))).' - '.date('M y', strtotime(date('Y-d-m', strtotime('01/' . str_replace('-', '/', $data[1])))));
														$path = '../myFiles/_uploaddata/'.$query_['attachment'];
														$attachment = '<a href="'.$path.'" target="_blank" class="fa fa-file-text-o fa-lg"></a>';
														if($del == 1){
															$vdel = '<a href="../api/apijatim.php?han=delresturno&cab='.$query_['cabang'].'&periode='.$query_['periode'].'" class="btn btn-danger btn-xs">Delete</a>';
														}
												echo '<tr>
													<td class="text-center">'.$query_['nmcabang'].'</td>
													<td class="text-right">'.duit($query_['Total Plafond']).'</td>
													<td class="text-right">'.duit($query_['Total Premi']).'</td>
													<td class="text-right">'.duit($query_['Total Resturno']).'</td>

													<td class="text-center">'.$periode.'</td>
													<td class="text-center">'.$query_['keterangan'].'</td>
													<td class="text-center">'.$attachment.'</td>
													<td class="text-center">'.$vdel.'</td>
												</tr>';
													}
												?>
											</tbody>
										</table>
									</div>
							</div>
						</div>			
					</div>		
					<!-- END RESTURNO -->	
				<?php		
					}
				?>
			
				<!-- end row -->
				<br>
				<div class="alert alert-info">
					<h4><i class="fa fa-info-circle"> Contact</i></h4>
					<p>Telp : 021 - 2284 6900</p>
					<p>Email : cs.jatim@adonai.co.id</p>
				</div>
	      <!-- begin #footer -->
	      <?php
          _footer();
        ?>
	      <?php
	    	}else{
	      ?>
	      <h1 class="page-header"><a href="<?php echo $path; ?>myFiles/tutorialasuransi.pdf" target="_blank">Unduh Buku Panduan </a><font size="1" color="grey"><i>Last Update 19 - 07 - 2018</i></font></h1>
	      <?php
	    	}
	      ?>

	      <!-- end #footer -->
			</div>
			<!-- end #content -->
		</div>
		<!-- end page container -->

    <!-- <div id="chatbox_JTM029-1" class="chatbox panel" style="bottom: 0px; right: 252px; display: block;">
      <div class="chatboxhead panel-title">
        <div class="chatboxtitle">JTM029-1</div>
        <div class="chatboxoptions">
          <a href="javascript:void(0)" onclick="javascript:toggleChatBoxGrowth('JTM029-1')"> <i class="fa fa-minus"></i> </a>
          <a href="javascript:void(0)" onclick="javascript:closeChatBox('JTM029-1')"> <i class="fa fa-times"></i> </a>
        </div>
        <br clear="all">
      </div>
      <div class="chatboxcontent panel-body">
        <div class="media">
          <div class="media-left">
            <a href="#">
              <img alt="64x64" class="media-object" data-src="holder.js/64x64" src="dummy.svg" data-holder-rendered="true" style="width: 44px; height: 44px;">
            </a> </div>
            <div class="media-body">
              <p class="media-heading"><b>Media heading</b></p>
              Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo.
            </div>
          </div>
        <div class="chatboxmessage">
          <span class="chatboxmessagefrom">JTM000-1:&nbsp;&nbsp;</span>
          <span class="message chatboxmessagecontent">Test</span>
        </div>
      </div>
      <div class="chatboxinput">
        <textarea class="chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,'JTM029-1');" style="height: 44px;"></textarea>
      </div>
    </div> -->
		<?php
        _javascript();
        ?>

		<script>
      function getOnline(loader=true){
        $.ajax({
    			type  : 'GET',
    			dataType: 'html',
    			url   	: "<?= $_SERVER['REQUEST_URI'] ?>onlinegrid.php",
    			data  : {
    				"search"	: $('#search-online').val(),
    			},
    			success : function(msg) {
            $( "#online-grid" ).html(msg);
    			},
          beforeSend: function( xhr ) {
            if(loader==true){
              $( "#online-grid" ).html("<div class='text-center'><i class='fa fa-cog fa-spin fa-2x'></div>");
            }
          }
    		});
      }

			$(document).ready(function() {
				App.init();
			  Demo.init();
			  $("#btn-tbl-angsuran").hide();
				var idbro = <?php echo $idbro ?>  ;
				var idclient = <?php echo $idclient ?> ;
				$('#plafond').mask('000,000,000,000,000' , {reverse: true});

				function tipe(){
						$.ajax({
				 		url: 'data.php',
				 		global: false,
				 		type:"POST",
				 		data: {functionname:'tipepinjaman'},
				 		success: function(data){
				 			document.getElementById("tipepinjaman").innerHTML = data;
				 		}
				 	});
				}

				function input(val="viewsharedashboard",key=""){
					document.getElementById("target").innerHTML = '<div class="spinner"> Loading... </div>';
				 	$.ajax({
				 		url: '../shareas/data.php',
				 		global: false,
				 		type:"POST",
				 		data: {hn:val,id:key},
				 		success: function(data){
				 			document.getElementById("target").innerHTML = data;
							//$("#tbl-share").DataTable();
				 		}
				 	});
				}
				<?php
                    if ($cabang == 1) {
                        echo '//input();
									setInterval(function() {
										//input();
									}, 60000);';
                    }
                ?>


				function databulan(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grapbulan', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function datapremium(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grappremium', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function dataplafond(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grapplafon', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function datapeserta(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grappeserta', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function datapremipaid(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grappremiumpaid', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function datapremiunpaid(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'grappremiumunpaid', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				var randomScalingFactor = function() {
					return Math.round(100 * Math.random())
				}

				var renderBarChart = function() {
					var a = {
						labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
						datasets: [{
							borderWidth: 2,
							borderColor: "#FFFFFF",
							backgroundColor: "#17B6A4",

							data: JSON.parse(datapremium(idbro,idclient)),
							label: "Total Premi"
						},{
							borderWidth: 2,
							borderColor: "#FFFFFF",
							backgroundColor: "#F04B46",
							data: JSON.parse(datapremiunpaid(idbro,idclient)),
							label: "Premi Belum Dibayar"
				        },
						{
							borderWidth: 2,
							borderColor: "#FFFFFF",
							backgroundColor: "#2184DA",
							data: JSON.parse(datapremipaid(idbro,idclient)),
							label: "Premi Dibayar"
				        }
						]
					}
					b = document.getElementById("monthly-report-chart").getContext("2d");

					new Chart(b, {
						type: "bar",
						data: a,
						options: {
							legend: {
								display: !0,
								labels:{fontColor:"#FFF"}
							},

							scales: {
								scaleLabel: {
									fontColor: "#FFF"
								},
								gridLines: {
									color: "rgba(255,255,255,0.1)"
								},
								xAxes: [{
									ticks: {
									fontColor: "#FFF",
										beginAtZero: true
									}
								}],
								yAxes: [{
									ticks: {
									fontColor: "#FFF",
										beginAtZero: true,


										// Return an empty string to draw the tick line but hide the tick label
										// Return `null` or `undefined` to hide the tick line entirely
										userCallback: function(value, index, values) {
											// Convert the number to a string and splite the string every 3 charaters from the end
											value = value.toString();
											// value = value.split(/(?=(?:...)*$)/);
											value = (value/1000).toFixed(3);

											// Convert the array to a string and format the output
											// value = value.join('.');
											return  value;
										}
									}
								}]
							}
						}
					})
				}

				var visitorLineChart, handleRenderVisitorAnalyticsChart = function() {
					var t = "#premiumpeserta",
					o = $(t).closest(".panel").hasClass("panel-expand") ? $(t).closest(".panel-body").height() - 47 : $(t).attr("data-height");
					$(t).height(o);
					var i = document.getElementById("premiumpeserta").getContext("2d"),
					e = i.createLinearGradient(0, 0, 0, 500);
					e.addColorStop(0, "rgba(62, 71, 79, 0.3)");

					var l = {
						labels: JSON.parse(databulan(idbro,idclient)),
						datasets: [{
							label: "Premium",
							fillColor: e,
							strokeColor: "#333",
							pointColor: "#fff",
							pointStrokeColor: "#000",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(151,187,205,1)",
							data: JSON.parse(datapremium(idbro,idclient))
						}]
					};
					visitorLineChart = new Chart(i).Line(l, {
						animation: !1,
						scaleBeginAtZero: !1,
						pointDot: !0,
						pointDotStrokeWidth: 1.5,
						scaleLineWidth: 2,
						scaleLineColor: "rgba(0,0,0,.8)",
						scaleFontFamily: "'Nunito', sans-serif",
						scaleFontColor: "#333",
						scaleLabel: "<%=value%>",
						barStrokeWidth: 0,
						barValueSpacing: 10,
						barShowStroke: !1,
						responsive: !0,
						tooltipEvents: ["mousemove", "touchstart", "touchmove"],
						tooltipFillColor: "rgba(0,0,0,0.8)",
						tooltipFontFamily: '"Nunito", sans-serif',
						tooltipFontSize: 11,
						tooltipFontStyle: "300",
						tooltipFontColor: "#fff",
						tooltipTitleFontFamily: '"Nunito", sans-serif',
						tooltipTitleFontSize: 11,
						tooltipTitleFontStyle: "300",
						tooltipTitleFontColor: "#fff",
						tooltipYPadding: 8,
						tooltipXPadding: 8,
						tooltipCaretSize: 5,
						tooltipCornerRadius: 3,
						customTooltips: function(t) {
							var o = $("#visitor-analytics-tooltip");
							return t ? (o.removeClass("above below"), o.addClass(t.yAlign), o.html('<div class="chartjs-tooltip-section">' + t.text + "</div>"), void o.css({
								display: "block",
								left: t.chart.canvas.offsetLeft + t.x + "px",
								top: t.chart.canvas.offsetTop + t.y + "px",
								fontFamily: t.fontFamily,
								fontSize: t.fontSize,
								fontStyle: t.fontStyle
							})) : void o.hide()
						}
					})
				}

				$(window).load(function() {
					renderBarChart()
				})

				function piedata(broker,client){
					console.log("test:"+broker+"_"+client);
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'piedata', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;

					return data;
				}

				function pielabel(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'pielabel', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				function piebackground(broker,client){
					data = $.ajax({
						url: 'data.php',
						global: false,
						type: "POST",
						data: {functionname: 'piebg', idbro:broker, idclient:client},
						dataType: 'json',
						async:false
					}
					).responseText;
					return data;
				}

				pieData = JSON.parse(piedata(idbro,idclient));
				pieLabel = JSON.parse(pielabel(idbro,idclient));
				pieBg = JSON.parse(piebackground(idbro,idclient));
				var handleGenerateGraph = function(a) {
			        var a = a ? a : !1
					var i = document.getElementById("statuspeserta").getContext("2d");
					/*
					window.myPie = new Chart(i).Pie(pieData, {
						animation: a
					})
					*/
					h = {
						labels: ["Unique Visitor", "Page Views", "Total Page Views"],
						datasets: [{
							data: pieData,
							backgroundColor: pieBg,
							borderColor: ["#fff", "#fff", "#fff"],
							borderWidth: 2
						}]
					},
					g = {
						labels: pieLabel,
						datasets: [{
							data: pieData,
							backgroundColor: pieBg,
							borderColor: ["#fff"],
							borderWidth: 2,
							label: "My dataset"
						}]
					},
					window.myPie = new Chart(i, {
						type: "pie",
						data: g
					});
			  }

				handleChartJs = function() {
			        $(window).load(function() {
			            handleGenerateGraph(!0)
			        }), $(window).resize(function() {
			            handleGenerateGraph()
			        })
			  }
				handleChartJs();

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

			});

			function hidetabel(object){
				var value = object.value;

				if(value == "3.75"){
					$("#btn-tbl-angsuran").show();
				}else{
					$("#btn-tbl-angsuran").hide();
				}
			}

			function tabel(){

				var str = $('#plafond').val();
				var tenor = $('#tenor').val();
				var bunga = $('#bunga').val();
				var plafond = str.replace(/,/g,"");
			}

			function hitung(){
				var str = $('#plafond').val();
				var karpot = $('#karpot').val();
				var tenor = $('#tenor').val();
				var plafond = str.replace(/,/g,"");
				var hasil = (karpot*plafond)/1000;

				if(karpot == "3.75"){
					hasil = hasil.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
					hasil = "Rp. "+hasil + " / Tahun";
				}else{
					hasil = hasil*tenor;
					hasil = hasil.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
					hasil = "Rp. "+hasil;
				}
				document.getElementById("premi").value = hasil;
			}
		</script>
	</body>

</html>
