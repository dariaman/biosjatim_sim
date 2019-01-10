<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
	include "../param.php";

	$today = date('Y-m-d');
	$todayapprove = date('Y-m-d H:i:s');
	
	if(!empty($_POST['approve'])) {
		foreach($_POST['approve'] as $check) {
			$queryspk = mysql_query("SELECT * FROM ajkspk WHERE id = '".$check."'");
			$rowspk = mysql_fetch_array($queryspk);
			$idbroker = $rowspk['idbroker'];
			$idpartner = $rowspk['idpartner'];
			$idproduk = $rowspk['idproduk'];
			$nomorspk = $rowspk['nomorspk'];
			$statusspk = $rowspk['statusspk'];
			$nomorktp = $rowspk['nomorktp'];
			$nama = $rowspk['nama'];
			$dob = $rowspk['dob'];
			$plafond = $rowspk['plafond'];
			$tenor = $rowspk['tenor'];
			$usia = birthday($dob,$today);

			$qpolis = mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idpartner."' AND id = '".$idproduk."'");
			$rpolis = mysql_fetch_array($qpolis);
			$levelval = $rpolis['levelvalidasi'];
			$lastdayinsurance = $rpolis['lastdayinsurance'];
			$ageend = $rpolis['ageend'];
			$agemin = $rpolis['agestart'];
			$byrate = $rpolis['byrate'];
			$calculaterate = $rpolis['calculatedrate'];
			$adminfee = $rpolis['adminfee'];
			$diskon = $rpolis['diskon'];
			$general = $rpolis['general'];
			$tipemedical = $rpolis['typemedical'];
			$tglakad = $today;

			if($tipemedical=="SKKT"){
				$qskkt = mysql_fetch_array(mysql_query("SELECT * FROM ajkskkt WHERE nomorspk = '".$check."'"));
				$quest2 = strpos($qskkt['question_2'],'P');

				if (strstr($qskkt['question_2'], 'P')) {
					$statusspk = 'PreApproval';
				}
				else
				{
					//$statusspk = 'Approve';
					$statusspk = 'Aktif';
				}
				$typedata = AES::encrypt128CBC("dataskkt",ENCRYPTION_KEY);				
			}else{
				$statusspk = 'Pending';
				$typedata = AES::encrypt128CBC("dataspk",ENCRYPTION_KEY);
			}
			//DATA MEDICAL
			if ($rpolis['freecover']=="Y") {
				$querymedical = mysql_query('SELECT * FROM ajkmedical WHERE idbroker="'.$idbroker.'" AND idpartner="'.$idpartner.'" AND idproduk="'.$idproduk.'" AND '.$usia.' BETWEEN agefrom AND ageto AND '.$plafond.' BETWEEN upfrom AND upto AND del IS NULL');
				$rowmedical = mysql_fetch_array($querymedical);
				$typemedical = $rowmedical['type'];
			}
			//DATA MEDICAL
			if($lastdayinsurance=1){
				$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month -1 Day"));
			}else{
				$tglakhir = Date("Y-m-d", strtotime($tglakad." +".$tenor." Month 0 Day"));;
			}
			if($byrate=="Age"){
				$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbroker."' AND idclient='".$idpartner."' AND idpolis='".$idproduk."' AND '".$usia."' BETWEEN agefrom AND ageto AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
			}else{
				$qrate = mysql_query("SELECT * FROM ajkratepremi WHERE idbroker='".$idbroker."' AND idclient='".$idpartner."' AND idpolis='".$idproduk."' AND '".$tenor."' BETWEEN tenorfrom AND tenorto AND status='Aktif'");
			}
			$rrate = mysql_fetch_array($qrate);
			$rate = $rrate['rate'];
			$premi = ($plafond * $rate) / $calculaterate;
			$extpremi = 0;
			$discountpremi = $premi * $diskon/100;
			$totalpremi = $premi - $discountpremi + $extpremi + $adminfee;

			$getdataspk = mysql_fetch_array(mysql_query("SELECT * FROM ajkspk WHERE id = '".$check."'"));

			if ($statusspk == 'Approve') {
			mysql_query("	UPDATE ajkspk 
										SET usia = '".$usia."',
												tglakad= '".$tglakad."',
												tglakhir= '".$tglakhir."',
												ratebank = '".$rate."',
												premi= '".$premi."',
												statusspk='".$statusspk."',
												nettpremi= '".$totalpremi."', 
												approve_by ='".$iduser."', 
												approve_date='".$todayapprove."' 
										WHERE id = '".$check."'");
			}else{
			mysql_query("UPDATE ajkspk 
									 SET 	usia = '".$usia."',
									 			tglakad= '".$tglakad."',
									 			tglakhir= '".$tglakhir."',
									 			ratebank = '".$rate."',
									 			premi= '".$premi."',
									 			nettpremi= '".$totalpremi."', 
									 			statusspk='".$statusspk."', 
									 			approve_by ='".$iduser."', 
									 			approve_date='".$todayapprove."' 
										WHERE id = '".$check."'");
			}
/*			$qtoken = mysql_query("SELECT UserToken FROM user_mobile_token WHERE UserID = '".$getdataspk['input_by']."' AND packagename='com.biosajk.marketing'");
			$regTokens = array();
			while ($rtoken = mysql_fetch_assoc($qtoken)) {
				$notoken = $rtoken['UserToken'];

				$nomorspk = $getdataspk['nomorspk'];
				$nama = $getdataspk['nama'];
				$idspk = $getdataspk['id'];

				$data = array("post_title" => "Nomor $nomorspk Telah diapprove",
					"post_msg" => "Data telah diapprove oleh supervisor dengan nomor $nomorspk atas nama $nama",
					"datamsg" =>"SPK",
					"datastatus" => $statusspk,
					"dataformid" => $idspk,
					"dataidspk" => $nomorspk);
				_sendnotif($notoken,$data);
			}*/
		}
	}
	header("location:../validasi/?type=".$typedata);
?>