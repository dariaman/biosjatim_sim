<?php
    include('includes/fu6106.php');

    function _head($userid, $username, $userphoto, $logo)
    {
        echo '<head>
						<meta charset="utf-8" />
						<title>SIMULATION BIOS - Broker Insurance Online System</title>
						<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
						<meta content="" name="description" />
						<meta content="" name="author" />
						<link rel="icon" type="image/png" href="../myFiles/_photo/'.$logo.'">

						<!-- ================== BEGIN BASE CSS STYLE ================== -->
						<!-- <link href="http://fonts.googleapis.com/css?family=Nunito:400,300,700" rel="stylesheet" id="fontFamilySrc" /> -->
						<link href="../assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
						<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
						<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
						<link href="../assets/css/animate.min.css" rel="stylesheet" />
						<link href="../assets/css/style.min.css" rel="stylesheet" />
						<link href="../assets/plugins/bootstrap-validation/css/bootstrapValidator.min.css" rel="stylesheet" />
						<link href="../assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
						<link href="../assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
						<link href="../assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
						<link href="../assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
						<link href="../assets/plugins/isotope/isotope.css" rel="stylesheet" />
					 	<link href="../assets/plugins/lightbox/css/lightbox.css" rel="stylesheet" />
					  <link href="../assets/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet" />
						<link href="../assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
						<link href="../assets/plugins/powerange/powerange.min.css" rel="stylesheet" />
						<link href="../assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
						<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesome-bootstrap-checkbox/0.3.5/awesome-bootstrap-checkbox.min.css" />
            <link href="../assets/css/chat.css" rel="stylesheet" />
            <link href="../assets/plugins/summernote/dist/summernote.css" rel="stylesheet" />
						<!-- ================== END BASE CSS STYLE ================== -->

						<!-- ================== BEGIN BASE JS ================== -->
						<script src="../assets/plugins/pace/pace.min.js"></script>
						<!-- ================== END BASE JS ================== -->

						<!--[if lt IE 9]>
						    <script src="../assets/crossbrowserjs/excanvas.min.js"></script>
						<![endif]-->
					</head>';
    }

    function _header($userid, $username, $userphoto, $logo, $logoklient)
    {
        $quser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$userid."'");
        $ruser = mysql_fetch_array($quser);
        $gender = $ruser['gender'];
        $idas = $ruser['idas'];
        $idbro = $ruser['idbroker'];
        if ($userphoto=="") {
            if ($gender=="L") {
                $userphoto = 'male.png';
            } else {
                $userphoto = 'female.png';
            }
        }
        if ($logo=="") {
            $logo ='adonai pialang asuransilogo-figure-inverse11.png';
        }
        $querybroker = mysql_query("SELECT * FROM ajkcobroker WHERE id ='".$idbro."'");
        $rowbroker = mysql_fetch_array($querybroker);
        $namebro = $rowbroker['name'];
        $logo = $rowbroker['logothumb'];
        $address1 = $rowbroker['address1'];
        $address2 = $rowbroker['address2'];
        $city = $rowbroker['city'];
        $postcode = $rowbroker['postcode'];
        if ($idbro=="") {
            $logo = '';
        }

        if($idas == ""){
          $queryclient = mysql_fetch_array(mysql_query("SELECT * FROM ajkclient WHERE idc ='".$idbro."' AND id='".$ruser['idclient']."'"));
          $logoklient = $queryclient['logothumb'];
        }else{
          $queryins = mysql_fetch_array(mysql_query("SELECT * FROM ajkinsurance WHERE id='".$idas."'"));
          $logoklient = $queryins['logo'];
        }

        echo '<!-- begin #header -->
					<div id="header" class="header navbar navbar-default navbar-fixed-top">
						<!-- begin container-fluid -->
						<div class="container-fluid">
							<!-- begin mobile sidebar expand / collapse button -->
							<div class="navbar-header">
								<a href="../dashboard" class="navbar-brand"><img src="../myFiles/_photo/'.$logo.'" width="200px" height="30px"  alt="" /> </a>
								<button type="button" class="navbar-toggle" data-click="top-menu-toggled">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<!-- end mobile sidebar expand / collapse button -->

							<!-- begin navbar-right -->
							<ul class="nav navbar-nav navbar-right">
								<a href="javascript:;" class="navbar-brand"><img src="../myFiles/_photo/'.$logoklient.'" width="200px" height="30px" alt="" /> </a>
								<li class="dropdown navbar-user">
									<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
										<span class="image"><img src="../myFiles/_photo/'.$userphoto.'" alt="'.$username.'" /></span>
										<span class="hidden-xs">'.$ruser['firstname'].' '.$ruser['lastname'].'</span> <b class="caret"></b>
									</a>
									<ul class="dropdown-menu pull-right">
										<li><a id="logout" href="../changepassword/index.php?param1='.base64_encode($ruser['id']).'&param2='.$_SESSION['User'].'">Change Password</a></li>
										<li><a id="logout" href="../dologout.php">Log Out</a></li>
									</ul>
								</li>

							</ul>
							<!-- end navbar-right -->
						</div>
						<!-- end container-fluid -->
					</div>
					<!-- end #header -->';
    }

    function _sidebar($userid, $username, $userphoto, $dept)
    {
        $peserta = AES::encrypt128CBC('peserta', ENCRYPTION_KEY);
        $debitnote = AES::encrypt128CBC('debitnote', ENCRYPTION_KEY);
        $pesertaspk = AES::encrypt128CBC('pesertaSPK', ENCRYPTION_KEY);
        $pesertakpr = AES::encrypt128CBC('pesertaKPR', ENCRYPTION_KEY);
        $masterkpr = AES::encrypt128CBC('masterKPR', ENCRYPTION_KEY);
        $masterkkb = AES::encrypt128CBC('masterKKB', ENCRYPTION_KEY);
        $pesertakkb = AES::encrypt128CBC('pesertaKKB', ENCRYPTION_KEY);
        $verDeklarasi = AES::encrypt128CBC('verifikasideklarasi', ENCRYPTION_KEY);
        $spk = AES::encrypt128CBC('dataspk', ENCRYPTION_KEY);
        $skkt = AES::encrypt128CBC('dataskkt', ENCRYPTION_KEY);
        $klaimbatal = AES::encrypt128CBC('klaimBatal', ENCRYPTION_KEY);
        $klaimbatalVer = AES::encrypt128CBC('klaimBatalVerifikasi', ENCRYPTION_KEY);
        $klaimrefund = AES::encrypt128CBC('klaimRefund', ENCRYPTION_KEY);
        $klaimrefundVer = AES::encrypt128CBC('klaimRefundVerifikasi', ENCRYPTION_KEY);
        $klaimtopup = AES::encrypt128CBC('klaimTopup', ENCRYPTION_KEY);
        $klaimtopupVer = AES::encrypt128CBC('klaimTopupVerifikasi', ENCRYPTION_KEY);
        $klaimklaim = AES::encrypt128CBC('klaimKlaim', ENCRYPTION_KEY);
        $klaimklaimVer = AES::encrypt128CBC('klaimKlaimVerifikasi', ENCRYPTION_KEY);
        $klaimData = AES::encrypt128CBC('klaimData', ENCRYPTION_KEY);
        $typeupload = AES::encrypt128CBC('uploadnonspk', ENCRYPTION_KEY);
        $uploadcsf = AES::encrypt128CBC('uploadcsf', ENCRYPTION_KEY);
        $typeuploadspk = AES::encrypt128CBC('uploadspk', ENCRYPTION_KEY);
        $deklarasi = AES::encrypt128CBC('deklarasi', ENCRYPTION_KEY);
        $creditnotebatal = AES::encrypt128CBC('cnbatal', ENCRYPTION_KEY);
        $creditnoterefund = AES::encrypt128CBC('cnrefund', ENCRYPTION_KEY);
        $creditnoteklaim = AES::encrypt128CBC('cnklaim', ENCRYPTION_KEY);
        $sharetarget = AES::encrypt128CBC('sharetarget', ENCRYPTION_KEY);
        $lapmemins = AES::encrypt128CBC('lapmemins', ENCRYPTION_KEY);
        $shareas = AES::encrypt128CBC('shareas', ENCRYPTION_KEY);
        $quserLevel = mysql_fetch_array(mysql_query("SELECT * FROM  useraccess WHERE  username = '".$userid."'"));

        echo '<!-- begin #top-menu -->
					<div id="top-menu" class="top-menu">
			    	<div class="container-fluid">
	            <!-- begin top-menu nav -->
	            <ul class="nav">
	              <li class="active" id="has_dashbord">
	                  <a href="../dashboard" id="sub_dashbord"><i class="fa fa-home"></i><span>Dashboard</span></a>
	              </li>';
        if($quserLevel['idas'] == ""){
          if ($quserLevel['level'] == 6 and $quserLevel['branch'] != 1) {
              echo '<!--<li class="active" id="has_input">
  	                  <a href="../scase" id="sub_scase"><i class="fa fa-home"></i><span>Special Case</span></a>
  	              </li>-->';
          } elseif ($quserLevel['level'] == 99) {
              echo '<li class="has-sub" id="has_input">
  		              <a href="javascript:;"><i class="fa fa-upload"></i><span>Upload Data Penutupan</span></a>
  		              <ul class="sub-menu" id="idhas_input">
  		                  <li id="idsub_databaruajk"><a href="../upload?xq='.$typeupload.'">Upload Data</a></li>
                            <li id=""><hr></li>
                            <li id="idsub_databaruajk"><a href="../modules/modTxt.php?Rtxt=putjatim03" target="_blank">Send Data Peserta (03)</a></li>
                            <li id="idsub_databaruajk"><a href="../modules/modTxt.php?Rtxt=putjatim04" target="_blank">Send Data Restitusi (04)</a></li>
                            <li id="idsub_databaruajk"><a href="../modules/modTxt.php?Rtxt=putjatim05" target="_blank">Send Data Klaim (05)</a></li>
  		              </ul>
  	              </li>';
          }

          echo '<!--<li class="has-sub" id="has_view">
  	                <a href="javascript:;"><i class="fa fa-eye"></i><span>Preview Data</span></a>
  	                <ul class="sub-menu" id="idhas_view">
  	                  <li id="idsub_viewdatabaruajk"><a href="../view?op=vAJK">View AJK</a></li>
  	                </ul>
  	              </li>-->';

          if ($quserLevel['level'] == 8 and $quserLevel['branch'] != 1) {
              echo '<!--<li class="has-sub" id="has_verification">
  	                <a href="javascript:;"><i class="fa fa-upload"></i><span>Verifikasi Data</span></a>
  	                <ul class="sub-menu" id="idsub_verification">
  	                  <li id="idsub_datavalidasi_deklarasi"><a href="../validasi?type='.$verDeklarasi.'"">Verifikasi Deklarasi AJK</a></li>
  	                  <li id=""><hr></li>
  	                  <li id="idsub_datavalidasi_skkt"><a href="../validasi?type='.$skkt.'">Verifikasi SPK</a></li>
  	                </ul>
  	              </li>-->';
          }
          echo '<li class="has-sub" id="has_master">
                <a href="javascript:;"><i class="fa fa-database"></i><span>Master Data<b class="caret"></b></span></a>
                <ul class="sub-menu" id="idsub_master">
                  <li id="idsub_peserta"><a href="../masterdata?type='.$peserta.'">Data Peserta</a></li>
                  <li id="idsub_debitnote"><a href="../masterdata?type='.$debitnote.'">Data Nota Debit</a></li>
                </ul>
              </li>';
          if ($quserLevel['level'] == 6  and $quserLevel['branch'] != 1) {
              echo '<li class="has-sub" id="has_klaim">
  										<a href="javascript:;"><i class="fa fa-flag"></i><span>Klaim <b class="caret"></b></span></a>
  										<ul class="sub-menu" id="sub_klaim">
  											<li id="idsub_klaimphk"><a href="../klaim?type='.$klaimklaim.'&er=klaimphk">Pengajuan Klaim PHK</a></li>
                        <li id="idsub_klaimpaw"><a href="../klaim?type='.$klaimklaim.'&er=klaimpaw">Pengajuan Klaim PAW</a></li>
                        <li id="idsub_klaimklaim"><a href="../klaim?type='.$klaimklaim.'&er=klaimklaim">Pengajuan Klaim Meninggal</a></li>
                        <li id="idsub_klaimpaw"><a href="../klaim?type='. $klaimklaim.'&er=klaimmacet">Pengajuan Klaim Kredit Macet</a></li>
                        <li id=""><hr></li>
                        <li id="idsub_klaimdata"><a href="../klaim?type='.$klaimData.'">Data Klaim</a></li>
  										</ul>
  									</li>
  									<li class="has-sub" id="has_refund">
  										<a href="javascript:;"><i class="fa fa-flag"></i><span>Restitusi <b class="caret"></b></span></a>
  										<ul class="sub-menu" id="sub_refund">
  											<!--<li id="idsub_klaimbatal"><a href="../klaim?type='.$klaimbatal.'">Batal</a></li>-->
  											<li id="idsub_klaimrefund"><a href="../klaim?type='.$klaimrefund.'">Refund</a></li>
  										</ul>
  									</li>									';
          } elseif ($quserLevel['level'] == 8  and $quserLevel['branch'] != 1) {
              echo '<li class="has-sub" id="has_klaim">
  										<a href="javascript:;"><i class="fa fa-flag"></i><span>Verifikasi Data<b class="caret"></b></span></a>
  										<ul class="sub-menu" id="sub_klaim">
  											<!--<li id="idsub_klaimbatalver"><a href="../klaim?type='.$klaimbatalVer.'">Verifikasi Batal</a></li>-->
  											<li id="idsub_klaimrefundver"><a href="../klaim?type='.$klaimrefundVer.'">Verifikasi Refund</a></li>
  											<!--<li id="idsub_klaimtopupver"><a href="../klaim?qtype=topup&type='.$klaimrefundVer.'">Verifikasi Topup</a></li>-->
  											<li id="idsub_klaimklaimver"><a href="../klaim?type='.$klaimklaimVer.'">Verifikasi Klaim</a></li>
  											<li id="#"><hr></li>
  											<li id="idsub_klaimdata"><a href="../klaim?type='.$klaimData.'">Data Klaim</a></li>
  										</ul>
  									</li>';
          }
          echo '<li class="has-sub" id="has_laporan">
  						<a href="javascript:;"><i class="fa fa-flag"></i><span>Laporan <b class="caret"></b></span></a>
  						<ul class="sub-menu" id="sub_laporan">
  							<li id="idsub_lappeserta"><a href="../report?type='.$peserta.'">Peserta</a></li>
  							<li id="idsub_lapdebitnote"><a href="../report?type='.$debitnote.'">Debitnote (DN)</a></li>
  							<li id="#"><hr></li>
  							<!--<li id="idsub_lapcreditnotebatal"><a href="../report?type='.$creditnotebatal.'">Batal</a></li>-->
  							<li id="idsub_lapcreditnoterefund"><a href="../report?type='.$creditnoterefund.'">Refund</a></li>
  							<li id="idsub_lapcreditnoteklaim"><a href="../report?type='.$creditnoteklaim.'">Klaim</a></li>
  						</ul>
  					</li>';
          if ($quserLevel['level'] == 99) {
              echo '<!--<li id="has_case">
              				<a href="../scase" id="sub_scase"><i class="fa fa-home"></i><span>Special Case</span></a>
              			</li>-->
              			<li id="has_revisi">
              				<a href="../revisi"><i class="fa fa-share-alt"></i><span>FAQ</span></a>
              			</li>';
          }

          echo '<li id="has_forum">
                  <a href="../discussion" id="sub_forum"><i class="fa fa-comment"></i><span>Forum</span></a>
                </li>';
        }else{
          echo '<li class="has-sub" id="has_upload">
                  <a href="javascript:;"><i class="fa fa-flag"></i><span>Upload<b class="caret"></b></span></a>
                  <ul class="sub-menu" id="sub_upload">
                    <li id="idsub_uploadcrf"><a href="../upload?xq='.$uploadcsf.'">Upload Certificate</a></li>
                  </ul>
                </li>
                <li class="has-sub" id="has_laporan">
                  <a href="javascript:;"><i class="fa fa-flag"></i><span>Laporan Asuransi<b class="caret"></b></span></a>
                  <ul class="sub-menu" id="sub_laporan">
                    <li id="idsub_lappeserta"><a href="../report?type='.$lapmemins.'">Peserta</a></li>
                  </ul>
                </li>';
        }
        // if ($quserLevel['level'] == 8) {
        // 	echo '<li class="has-sub" id="has_shareas">
        // 					<a href="javascript:;"><i class="fa fa-share-alt"></i><span>Share Asuransi <b class="caret"></b></span></a>
        // 						<ul class="sub-menu" id="sub_share">
        // 							<li id="sub_sharetarget"><a href="../shareas?a='.$sharetarget.'" >Set Target</a></li>
        //               		<li id="sub_shareas"><a href="../shareas?a='.$shareas.'" >Set Share</a></li>
        //               	</ul>
        //           	</li>';
        // }
        echo '<li class="menu-control menu-control-left">
						<a href="#" data-click="prev-menu"><i class="fa fa-angle-left"></i></a>
					</li>
					<li class="menu-control menu-control-right">
						<a href="#" data-click="next-menu"><i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
				<!-- end top-menu nav -->
				</div>
			</div>
			<!-- end #top-menu -->';
    }

    function detectDevice()
    {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $devicesTypes = array(
            "komputer" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
            "tablet"   => array("tablet", "tablet.*firefox"),
            "mobile"   => array("mobile ", "opera mobi", "opera mini"),
            "android"   => array("android.*mobile", "android"),
            "iphone"   => array("iphone"),
            "ipod"   => array("ipod"),
            "ipad"   => array("ipad"),
            "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
        );
        foreach ($devicesTypes as $deviceType => $devices) {
            foreach ($devices as $device) {
                if (preg_match("/" . $device . "/i", $userAgent)) {
                    $deviceName = $deviceType;
                }
            }
        }
        return ucfirst($deviceName);
    }

    function GetIP()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    function getBrowser()
    {
        // check if IE 8 - 11+
        preg_match('/Trident\/(.*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
        if ($matches) {
            $version = intval($matches[1]) + 4;     // Trident 4 for IE8, 5 for IE9, etc
            return 'Internet Explorer '.($version < 11 ? $version : 'Edge');
        }
        // check if Firefox, Opera, Chrome, Safari
        foreach (array('Firefox', 'OPR', 'Chrome', 'Safari') as $browser) {
            preg_match('/'.$browser.'/', $_SERVER['HTTP_USER_AGENT'], $matches);
            if ($matches) {
                return str_replace('OPR', 'Opera', $browser);   // we don't care about the version, because this is a modern browser that updates itself unlike IE
            }
        }
    }

    function _footer()
    {
        $user = $_SESSION['User'];
        $queryuser = mysql_query("SELECT * FROM  useraccess WHERE  username = '".$user."'");
        $rowuser = mysql_fetch_array($queryuser);
        $idbro = $rowuser['idbroker'];
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
        echo '<!-- begin #footer -->
            <div id="footer" class="footer">
                <span class="pull-right">
                    <a href="javascript:;" class="btn-scroll-to-top" data-click="scroll-top">
                        <i class="fa fa-arrow-up"></i> <span class="hidden-xs">Back to Top</span>
                    </a>
                </span>
                &copy; '.date('Y').' <b>'.$namebro.'</b> '.$alamat.' All Right Reserved
            </div>
            <!-- end #footer -->';
    }

    function _javascript()
    {
        echo '
		<!-- ================== BEGIN BASE JS ================== -->
		<script src="../assets/plugins/jquery/jquery-1.9.1.min.js"></script>
		<script src="../assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
		<script src="../assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
		<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<!--[if lt IE 9]>
		<script src="../assets/crossbrowserjs/html5shiv.js"></script>
		<script src="../assets/crossbrowserjs/respond.min.js"></script>
		<![endif]-->
		<script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="../assets/plugins/jquery-cookie/jquery.cookie.js"></script>
		<!-- ================== END BASE JS ================== -->

		<!-- ================== BEGIN PAGE LEVEL JS ================== -->

		<script src="../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	  <script src="../assets/js/demo.min.js"></script>
	  <script src="../assets/js/apps.min.js"></script>
	  <script src="../assets/plugins/bootstrap-validation/js/bootstrapValidator.min.js"></script>
	  <script src="../assets/plugins/bootstrap-validation/js/formValidation.min.js"></script>
	  <script src="../assets/plugins/bootstrap-validation/js/tooltipbootstrap.min.js"></script>
	  <script src="../assets/js/jquery.form.js"></script>

	  <script src="../assets/plugins/chart-js/chart.min.js"></script>
	  <script src="../assets/js/page-index-v2.demo.min.js"></script>
		<script src="../assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
		<script src="../assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
		<script src="../assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
		<script src="../assets/js/jquery.mask.min.js"></script>
		<script src="../assets/plugins/isotope/jquery.isotope.min.js"></script>
	 	<script src="../assets/plugins/lightbox/js/lightbox-2.6.min.js"></script>
		<script src="../assets/plugins/switchery/switchery.min.js"></script>
		<script src="../assets/plugins/powerange/powerange.min.js"></script>
		<script src="../assets/js/page-form-slider-switcher.demo.min.js"></script>
		<script src="../assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="../assets/js/chat.js"></script>
    <script src="../assets/js/close.js"></script>
    <script src="../assets/plugins/summernote/dist/summernote.min.js"></script>


		<!-- ================== END PAGE LEVEL JS ================== -->

		<script type="text/javascript">
			//document.addEventListener("contextmenu", event => event.preventDefault());
			function msgbox(psn,tipe="success"){
				swal({
					title: "Information",
					text: psn,
					type: tipe,
					confirmButtonColor: "#DD6B55",
					showConfirmButton:true
     		});
     		 setTimeout(function() {
				    window.location = "../dashboard";
				  }, 2000);
			}';

        if ($_SESSION["level"]<99) {
            echo 'var idleTime = 0;

        function idleTimer() {
            console.log("timer: "+idleTime)
            idleTime = idleTime + 1;
            if (idleTime > 600) {
                window.location="../dologout.php";
            }
        }

        $(document).ready(function () {
            var idleInterval = setInterval(idleTimer, 1000);
            $(this).mousemove(function (e) {idleTime = 0;});
            $(this).keypress(function (e) {idleTime = 0;});
        });';
        }


        echo '</script>';
    }

    define('ENCRYPTION_KEY', 'creditlifeinsura');

    class AES
    {
        public static function encrypt128CBC($data, $key)
        {
            return self::encryptCBC($data, $key, 16);
        }
        public static function encrypt192CBC($data, $key)
        {
            return self::encryptCBC($data, $key, 24);
        }
        public static function encrypt256CBC($data, $key)
        {
            return self::encryptCBC($data, $key, 32);
        }

        public static function decrypt128CBC($data, $key)
        {
            return self::decryptCBC($data, $key, 16);
        }
        public static function decrypt192CBC($data, $key)
        {
            return self::decryptCBC($data, $key, 24);
        }
        public static function decrypt256CBC($data, $key)
        {
            return self::decryptCBC($data, $key, 32);
        }

        public static function encryptFile128CBC($ifname, $ofname, $key)
        {
            return self::encryptFileCBC($ifname, $ofname, $key, 16);
        }
        public static function encryptFile192CBC($ifname, $ofname, $key)
        {
            return self::encryptFileCBC($ifname, $ofname, $key, 24);
        }
        public static function encryptFile256CBC($ifname, $ofname, $key)
        {
            return self::encryptFileCBC($ifname, $ofname, $key, 32);
        }

        public static function decryptFile128CBC($ifname, $ofname, $key)
        {
            return self::decryptFileCBC($ifname, $ofname, $key, 16);
        }
        public static function decryptFile192CBC($ifname, $ofname, $key)
        {
            return self::decryptFileCBC($ifname, $ofname, $key, 24);
        }
        public static function decryptFile256CBC($ifname, $ofname, $key)
        {
            return self::decryptFileCBC($ifname, $ofname, $key, 32);
        }

        private static function encryptCBC($data, $key, $key_size)
        {
            $cipher = MCRYPT_RIJNDAEL_128;
            $mode = MCRYPT_MODE_CBC;
            $iv_size = mcrypt_get_iv_size($cipher, $mode);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
            $block_size = mcrypt_get_block_size($cipher, $mode);
            $padding = $block_size - (strlen($data) % $block_size);
            $data .= str_repeat(chr($padding), $padding);//PKCS7 Padding
            $encrypted = mcrypt_encrypt($cipher, $key, $data, $mode, $iv);
            $hmac = hash_hmac('sha256', $encrypted, $key, $raw=true);
            $encoded = strtr(base64_encode($hmac.$iv.$encrypted), '+/=', '._-');
            return strlen($key)==$key_size ? $encoded : '';
        }

        private static function decryptCBC($data, $key, $key_size)
        {
            $hash_size = 32;
            $cipher = MCRYPT_RIJNDAEL_128;
            $mode = MCRYPT_MODE_CBC;
            $iv_size = mcrypt_get_iv_size($cipher, $mode);
            $block_size = mcrypt_get_block_size($cipher, $mode);
            $decoded = base64_decode(strtr($data, '._-', '+/='));
            $hmac = substr($decoded, 0, $hash_size);
            $iv = substr($decoded, $hash_size, $iv_size);
            $cmac = hash_hmac('sha256', substr($decoded, $iv_size+$hash_size), $key, $raw=true);
            $decrypted = mcrypt_decrypt($cipher, $key, substr($decoded, $iv_size+$hash_size), $mode, $iv);
            $padding = ord($decrypted[strlen($decrypted) - 1]);
            return $hmac==$cmac ? substr($decrypted, 0, 0-$padding) : '';
        }

        private static function encryptFileCBC($input_stream, $aes_filename, $key, $key_size)
        {
            $hash_size = 32;
            $cipher = MCRYPT_RIJNDAEL_128;
            $mode = MCRYPT_MODE_CBC;
            $block_size = mcrypt_get_block_size($cipher, $mode);
            $iv_size = mcrypt_get_iv_size($cipher, $mode);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
            $opts= array('mode'=>$mode, 'iv'=>$iv, 'key'=>$key);

            $infilesize = 0;
            $fin = fopen($input_stream, "rb");
            $fcrypt = fopen($aes_filename, "wb+");
            if (!empty($fin) && !empty($fcrypt) && strlen($key)==$key_size) {
                fwrite($fcrypt, str_repeat("_", $hash_size));//placeholder, HMAC will go here later
                fwrite($fcrypt, $iv);
                stream_filter_append($fcrypt, 'mcrypt.'.$cipher, STREAM_FILTER_WRITE, $opts);
                while (!feof($fin)) {
                    $block = fread($fin, 8192);
                    $infilesize+=strlen($block);
                    fwrite($fcrypt, $block);
                }
                $padding = $block_size - ($infilesize % $block_size);//$padding is a number from 0-15
                if (feof($fin) && $padding>0) {
                    fwrite($fcrypt, str_repeat(chr($padding), $padding));//perform PKCS7 padding
                }
                fclose($fin);
                fclose($fcrypt);

                $stream = 'php://filter/read=user-filter.ignorefirst32bytes/resource=' . $aes_filename;
                $hmac_raw = hash_hmac_file('sha256', $stream, $key, $raw=true);
                $fcrypt = fopen($aes_filename, "rb+");
                fwrite($fcrypt, $hmac_raw);
                fclose($fcrypt);
                return 1;
            }
            return 0;
        }

        private static function decryptFileCBC($aes_filename, $out_stream, $key, $key_size)
        {
            $hash_size = 32;
            $cipher = MCRYPT_RIJNDAEL_128;
            $mode = MCRYPT_MODE_CBC;
            $iv_size = mcrypt_get_iv_size($cipher, $mode);

            $stream = 'php://filter/read=user-filter.ignorefirst32bytes/resource=' . $aes_filename;
            $hmac_calc = hash_hmac_file('sha256', $stream, $key, $raw=true);

            $fcrypt = fopen($aes_filename, "rb");
            $fout = fopen($out_stream, 'wb');
            if (!empty($fout) && !empty($fcrypt) && strlen($key)==$key_size) {
                $hmac_raw = fread($fcrypt, $hash_size);
                $iv = fread($fcrypt, $iv_size);
                $opts = $hmac_calc==$hmac_raw ? array('mode'=>$mode, 'iv'=>$iv, 'key'=>$key) : array();
                stream_filter_append($fcrypt, 'mdecrypt.'.$cipher, STREAM_FILTER_READ, $opts);
                while (!feof($fcrypt)) {
                    $block = fread($fcrypt, 8192);
                    if (feof($fcrypt)) {
                        $padding = ord($block[strlen($block) - 1]);//assume PKCS7 padding
                        $block = substr($block, 0, 0-$padding);
                    }
                    fwrite($fout, $block);
                }
                fclose($fout);
                fclose($fcrypt);
                return 1;
            }
            return 0;
        }
    }

    function _sendnotif($registatoin_ids, $data)
    {
        //Google cloud messaging GCM-API url
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            //'to' => "/topics/global",
            'to' => $registatoin_ids,
            'data' => $data
        );

        // Google Cloud Messaging GCM API Key
        define("GOOGLE_API_KEY", "AIzaSyCaRuBxKGCnya7dRTiuPph7q0sCv2Nc9sY");
        $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    function _thamepanel()
    {
        echo '<!-- begin theme-panel -->
    		<div class="theme-panel">
        <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-tint"></i></a>
		    <div class="theme-panel-content">
		        <h5 class="m-t-0">Font Family</h5>
		        <div class="row row-space-10">
		            <div class="col-md-6">
		                <a href="#" class="btn btn-default btn-block btn-sm m-b-10 active" data-value="" data-src="http://fonts.googleapis.com/css?family=Nunito:400,300,700" data-click="body-font-family">
		                    Nunito (Default)
		                </a>
		            </div>
		            <div class="col-md-6">
		                <a href="#" class="btn btn-default btn-block btn-sm m-b-10" data-value="font-open-sans" data-src="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" data-click="body-font-family">
		                    Open Sans
		                </a>
		            </div>
		            <div class="col-md-6">
		                <a href="#" class="btn btn-default btn-block btn-sm m-b-10" data-value="font-roboto" data-src="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900" data-click="body-font-family">
		                    Roboto
		                </a>
		            </div>
		            <div class="col-md-6">
		                <a href="#" class="btn btn-default btn-block btn-sm m-b-10" data-value="font-lato" data-src="https://fonts.googleapis.com/css?family=Lato:400,100,300,700,900" data-click="body-font-family">
		                    Lato
		                </a>
		            </div>
		            <div class="col-md-12">
		                <a href="#" class="btn btn-default btn-block btn-sm" data-value="font-helvetica-arial" data-src="" data-click="body-font-family">
		                    Helvetica Neue, Helvetica , Arial
		                </a>
		            </div>
		        </div>
		        <div class="horizontal-divider"></div>
		        <h5 class="m-t-0">Header Theme</h5>
		            <ul class="theme-list clearfix">
		                <li><a href="javascript:;" class="bg-inverse" data-value="navbar-inverse" data-click="header-theme-selector" data-toggle="tooltip" data-title="Default">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-grey" data-value="navbar-grey" data-click="header-theme-selector" data-toggle="tooltip" data-title="Grey">&nbsp;</a></li>
		                <li class="active"><a href="javascript:;" class="bg-white" data-value="navbar-default" data-click="header-theme-selector" data-toggle="tooltip" data-title="Light">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-purple" data-value="navbar-purple" data-click="header-theme-selector" data-toggle="tooltip" data-title="Purple">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-primary" data-value="navbar-primary" data-click="header-theme-selector" data-toggle="tooltip" data-title="Primary">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-success" data-value="navbar-success" data-click="header-theme-selector" data-toggle="tooltip" data-title="Success">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-lime" data-value="navbar-lime" data-click="header-theme-selector" data-toggle="tooltip" data-title="Lime">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-warning" data-value="navbar-warning" data-click="header-theme-selector" data-toggle="tooltip" data-title="Warning">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-danger" data-value="navbar-danger" data-click="header-theme-selector" data-toggle="tooltip" data-title="Danger">&nbsp;</a></li>
		            </ul>
		            <div class="horizontal-divider"></div>
		            <h5 class="m-t-0">Sidebar Highlight Color</h5>
		            <ul class="theme-list clearfix">
		                <li><a href="javascript:;" class="bg-inverse" data-value="sidebar-highlight-inverse" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Inverse">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-grey" data-value="sidebar-highlight-grey" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Grey">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-white" data-value="sidebar-highlight-light" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Light">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-purple" data-value="sidebar-highlight-purple" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Purple">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-primary" data-value="sidebar-highlight-primary" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Primary">&nbsp;</a></li>
		                <li class="active"><a href="javascript:;" class="bg-success" data-value="" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Default">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-lime" data-value="sidebar-highlight-lime" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Lime">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-warning" data-value="sidebar-highlight-warning" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Warning">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-danger" data-value="sidebar-highlight-danger" data-click="sidebar-highlight-selector" data-toggle="tooltip" data-title="Danger">&nbsp;</a></li>
		            </ul>
		            <div class="horizontal-divider"></div>
		            <h5 class="m-t-0">Sidebar Theme</h5>
		            <ul class="theme-list clearfix">
		                <li class="active"><a href="javascript:;" class="bg-inverse" data-value="" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Default">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-grey" data-value="sidebar-grey" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Grey">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-white" data-value="sidebar-light" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Light">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-purple" data-value="sidebar-purple" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Purple">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-primary" data-value="sidebar-primary" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Primary">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-success" data-value="sidebar-success" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Success">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-lime" data-value="sidebar-lime" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Lime">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-warning" data-value="sidebar-warning" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Warning">&nbsp;</a></li>
		                <li><a href="javascript:;" class="bg-danger" data-value="sidebar-danger" data-click="sidebar-theme-selector" data-toggle="tooltip" data-title="Danger">&nbsp;</a></li>
		            </ul>
		        </div>
		    </div>
		    <!-- end theme-panel -->';
    }
