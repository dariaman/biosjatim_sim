<?php
 /********************************************************************
 DESC  : Create by hansen;
 EMAIL : hansendputra@gmail.com;
 Create Date : 2018-02-22

 ********************************************************************/
 // echo ini_get('display_errors');
 // if (!ini_get('display_errors')) {
 //     ini_set('display_errors', '1');
 // }
 // echo ini_get('display_errors');

    include "../param.php";

    $path_upload ="../image/upload/";
    $now = date('YmdHis');
    $today = date('Y-m-d');

    $typeupload = AES::encrypt128CBC('uploadnonspk', ENCRYPTION_KEY);

    function insertpeserta($filname)
    {
        $sql = "INSERT INTO ajkpeserta SELECT * FROM ajkpeserta_temp where filename = '$filname';";
        return $sql;
    }

    function insertcms($idpeserta)
    {
        $sql = "INSERT INTO CMS_ArAp_Transaction(fArAp_TransactionCode,
																		fArAp_TransactionDate,
																		fArAp_Status,
																		fArAp_No,
																		fArAp_Customer_Id,
																		fArAp_Customer_Nm,
																		fArAp_Asuransi_Id,
																		fArAp_Asuransi_Nm,
																		fArAp_Produk_Nm,
																		fArAp_StatusPeserta,
																		fArAp_DateStatus,
																		fArAp_CoreCode,
																		fArAp_BMaterialCode,
																		fArAp_RefMemberID,
																		fArAp_RefMemberNm,
																		fArAp_RefCabang,
																		fArAp_RefDescription,
																		fArAp_RefAmount,
																		fArAp_RefAmount2,
																		fArAp_RefDOB,
																		fArAp_AssDate,
																		fArAp_RefTenor,
																		fArAp_RefPlafond,
																		fArAp_Return_Status,
																		fArAp_Return_Date,
																		fArAp_Return_Amount,
																		fArAp_SourceDB,
																		input_by,
																		input_date)
  			SELECT
  				'AR-01' as fArAp_TransactionCode,
  				ajkdebitnote.tgldebitnote as fArAp_TransactionDate,
  				'A' as fArAp_Status,
  				ajkdebitnote.nomordebitnote as fArAp_No,
  				'JATIM' as fArAp_Customer_Id,
  				'PT Bank Pembangunan Daerah Jawa Timur Tbk' as fArAp_Customer_Nm,
  				ajkinsurance.name as fArAp_Asuransi_Id,
  				ajkinsurance.companyname as fArAp_Asuransi_Nm,
  				ajkpolis.produk as fArAp_Produk_Nm,
  				CONCAT(ajkpeserta.statusaktif,ajkpeserta.statuspeserta)as fArAp_StatusPeserta,
  				DATE_FORMAT(NOW(),'%Y-%m-%d')as fArAp_DateStatus,
  				'PRM' as fArAp_CoreCode,
  				'PRM' as fArAp_BMaterialCode,
  				ajkpeserta.idpeserta as fArAp_RefMemberID,
  				ajkpeserta.nama as fArAp_RefMemberNm,
  				ajkcabang.name as fArAp_RefCabang,
  				null as fArAp_RefDescription,
  				ajkpeserta.totalpremi as fArAp_RefAmount,
  				null as fArAp_RefAmount2,
  				ajkpeserta.tgllahir as fArAp_RefDOB,
  				DATE_FORMAT(NOW(),'%Y-%m-%d')as fArAp_AssDate,
  				ajkpeserta.tenor as fArAp_RefTenor,
  				ajkpeserta.plafond as fArAp_RefPlafond,
  				CASE WHEN ajkpeserta.tgllunas != '' THEN 'C' ELSE null END as fArAp_Return_Status,
  				ajkpeserta.tgllunas as fArAp_Return_Date,
  				ajkpeserta.totalpremi as fArAp_Return_Amount,
  				'BIOSJATIM' as fArAp_SourceDB,
  				ajkpeserta.input_by as input_by,
  				now()as input_date
  			FROM ajkpeserta
  			INNER JOIN ajkcabang
  			ON ajkcabang.er = ajkpeserta.cabang
  			INNER JOIN ajkdebitnote
  			ON ajkdebitnote.id = ajkpeserta.iddn
  			INNER JOIN ajkpolis
  			ON ajkpolis.id = ajkpeserta.idpolicy
  			INNER JOIN ajkinsurance
  			ON ajkinsurance.id = ajkpeserta.asuransi
  			WHERE ajkpeserta.del is null and
  			ajkpeserta.idpeserta = '".$idpeserta."';";
          return $sql;
    }

    function insertcadangan($idpeserta, $tahun, $nilai_cadangan_klaim, $nilai_cadangan_refund, $input_by, $input_time, $bungabank=0, $nilai_cicilan=0, $due,$plafond_cicilan)
    {
        $sql = "INSERT INTO ajkcadanganas (idpeserta,tahun,nilai_cadangan_klaim,nilai_cadangan_refund,bunga_bank,nilai_cicilan,input_by,input_time,duedate,plafond_cicilan)
				 		VALUES('$idpeserta',
				 						'$tahun',
				 						'$nilai_cadangan_klaim',
				 						'$nilai_cadangan_refund',
				 						'$bungabank',
				 						'$nilai_cicilan',
				 						'$input_by',
				 						'$input_time',
				 						'$due',
                    '$plafond_cicilan');";
        return $sql;
    }

    function updatednpeserta($cabang, $asuransi, $iddn, $filename)
    {
        $sql = "UPDATE ajkpeserta_temp SET iddn = '$iddn' WHERE cabang = '$cabang' and asuransi = '$asuransi' and filename= '$filename';";
        return $sql;
    }

    function insertpeserta_temp($idC,$idbro, $idclient, $idpolicy, $idpeserta, $filename, $nomorktp, $nocif, $nomorpk, $nama, $gender, $tptlahir, $tgllahir, $usia, $pekerjaan, $plafond, $tglakad, $tenor, $tglakhir,$tgltransaksi, $premirate, $premirate_sys, $premi, $premi_sys, $totalpremi, $aspremirate, $aspremi, $astotalpremi, $alamat, $regional, $area, $cabang, $nopinjaman, $refpremi, $asuransi, $input_by, $input_time)
    {
        $nama = strtoupper($nama);
        $sql = "INSERT INTO ajkpeserta_temp (idC,idbroker,idclient,idpolicy,idpeserta,filename,nomorktp,nocif,nomorpk,nama,gender,tptlahir,tgllahir,usia,pekerjaan,plafond,tglakad,tenor,tglakhir,tgltransaksi,premirate,premirate_sys,premi,premi_sys,totalpremi,aspremirate,aspremi,astotalpremi,alamatobjek,statusaktif,regional,area,cabang,nopinjaman,noreflunas,asuransi,input_by,input_time,statuslunas)
						VALUES ('$idC','$idbro','$idclient','$idpolicy','$idpeserta','$filename','$nomorktp','$nocif','$nomorpk','$nama','$gender','$tptlahir','$tgllahir','$usia','$pekerjaan','$plafond','$tglakad','$tenor','$tglakhir','$tgltransaksi','$premirate','$premirate_sys','$premi','$premi_sys','$premi','$aspremirate','$aspremi','$aspremi','$alamat','Pending','$regional','$area','$cabang','$nopinjaman','$refpremi','$asuransi','$input_by','$input_time','0');";

        return $sql;
    }

    function insertdebitnote($idbro, $idclient, $idpolicy, $asuransi, $idaspolis, $iddn, $regional, $cabang, $nomordebitnote, $premiclient, $premiasuransi, $tgldebitnote, $input_by, $input_time)
    {
        $sql = "INSERT INTO ajkdebitnote (id,idbroker,idclient,idproduk,idas,idaspolis,iddn,idregional,idcabang,nomordebitnote,premiclient,premiasuransi,tgldebitnote,input_by,input_time,paidstatus,paidtanggal,premiclientdibayar)
						VALUES ($iddn,'$idbro','$idclient','$idpolicy','$asuransi','$idaspolis','$iddn','$regional','$cabang','$nomordebitnote','$premiclient','$premiasuransi','$tgldebitnote','$input_by','$input_time','Paid','$input_time',$premiclient);";

        return $sql;
    }

    function mysql_exec_batch($p_query, $p_transaction_safe = true)
    {
        if ($p_transaction_safe) {
            $p_query = 'START TRANSACTION;' . $p_query . '; COMMIT;';
        };
        $query_split = preg_split("/[;]+/", $p_query);
        foreach ($query_split as $command_line) {
            $command_line = trim($command_line);
            if ($command_line != '') {
                $query_result = mysql_query($command_line);
                if ($query_result == 0) {
                    break;
                };
            };
        };
        return $query_result;
    }


    switch ($_POST['han']) {
        case 'input':
            $idpolicy = $_POST['namaproduk'];
            $nama = $_POST['namatertanggung'];
            $gender = $_POST['jnsklmn'];
            $tgllahir = _convertDate2($_POST['tgllahir']);
            $nomorktp = $_POST['nomorktp'];
            $nomorpk = $_POST['nomorpk'];
            $alamat = $_POST['alamat'];
            $plafond = $_POST['plafon'];
            $tenor = $_POST['tenor'];
            $asuransi = $_POST['insurance'];

            $qpeserta = mysql_fetch_array(mysql_query("SELECT id+1 as id FROM ajkpeserta ORDER BY id DESC LIMIT 1 "));
            $qdn = mysql_fetch_array(mysql_query("SELECT ifnull(id,0)+1 as id FROM ajkdebitnote ORDER BY id DESC LIMIT 1 "));

            $metSetAuto = substr($metSetAutoNumber + $qpeserta['id'], 1);
            $idpeserta = $metSetAuto;

            $usia_diff = datediff($today, $tgllahir);
            $usia_ = explode(',', $usia_diff);

            if ($usia_[1] >= 6) {
                $usia = $usia_[0] + 1;
            } else {
                $usia = $usia_[0];
            }
            $tglakhir = date('Y-m-d', strtotime("+".$tenor." months", strtotime($today)));

            $qpolisas = mysql_fetch_array(mysql_query("SELECT *
																								FROM ajkpolisasuransi
																								WHERE idbroker = '".$idbro."' and
																											idcost = '".$idclient."' and
																											idproduk = '".$idpolicy."' and
																											idas = '".$asuransi."' and
																											del is null"));
            $nama = str_replace("'", "''", $nama);
            $plafond = str_replace(",", "", $plafond);

            $premirate = $qratebank['rate'];
            $premi = ($plafond * $premirate)/1000;
            $aspremirate = $qrateasuransi['rate'];
            $aspremi = ($plafond * $aspremirate)/1000;
            $idaspolis = $qpolisas['id'];

            if ($idbro < 9) {
                $kodeBroker = '0'.$idbro;
            } else {
                $kodeBroker = $idbro;
            }
            $fakcekdn = $qdn['id'];
            $idNumber = 100000000 + $fakcekdn;
            $autoNumber = substr($idNumber, 1);
            $nomordebitnote = "DN.".date(y)."".date(m).".".$kodeBroker.'.'.$autoNumber;
            $querypeserta = insertpeserta($idbro, $idclient, $idpolicy,  $idpeserta, "", $nomorktp, $nomorpk, $nama, $gender, $tgllahir, $usia, $plafond, $today, $tenor, $tglakhir, $premirate, $premi, $premi, $aspremirate, $aspremi, $aspremi, $regional, $area, $cabang, $iduser, $mamettoday);
            $querydebitnote =	insertdebitnote($idbro, $idclient, $idpolicy, $asuransi, $idaspolis, $regional, $cabang, $nomordebitnote, $premi, $aspremi, $today, $iduser, $mamettoday);
            $query= $querypeserta.$querydebitnote;
            if (mysql_exec_batch($query)) {
                echo "success";
            } else {
                echo mysql_error();
            }
        break;

        case 'upload':
            $query = '';
            $querydebitnote = '';
            $file_temp = $_SESSION['file_temp'];
            $file_name = $_SESSION['file_name'];
            $path = '../myFiles/_uploaddata/'.$foldername;

            if (!file_exists($path)) {
                mkdir($path, 0777);
                chmod($path, 0777);
            }

            $inputFileName = '../upload/temp/'.$file_temp;

            $query= "";
            try {
                PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
               // $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType)->setDelimiter("\t");
        
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $newfilename = date('ymd_his').'_'.$file_name;

            copy($inputFileName, $path.'/'.$newfilename) or die("Could not upload file!");

            $qpeserta = mysql_fetch_array(mysql_query("SELECT idC FROM ajkpeserta WHERE idbroker = '".$idbro."' and idclient='".$idclient."' ORDER BY idC DESC LIMIT 1 "));
            if($idbro != 1 ){
              $broker = $idbro;
            }else{
              $broker = "";
            }
            $baris = 0;
            for ($row = 1; $row <= $highestRow; $row++) {
                $baris++;
                $autonumber = ($qpeserta['idC']+$baris);
                $idpeserta = substr($metSetAutoNumber + $autonumber, 1);
                $idpeserta = $broker.$idpeserta;
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true);
                $i = 0;

                foreach ($rowData[0] as $v) {
                    $datatest[$i] = $v;
                    $i++;
                }

                $data = explode("|", $datatest[0]);

                $today = date('Y-m-d');

                $nocif = $data[0]; //no CIF
                $nopinjaman = $data[1];
                $cab = $data[2];
                $nama = $data[3];
                $produk = $data[4];
                $gender = $data[5];
                $ktp = $data[6];
                $tptlahir = $data[7];
                $tgllahir = $data[8];
                $alamat = $data[9];
                $pekerjaan = $data[10];
                $npk = $data[11];
                $tglakad = $data[12];
                $tglakhir = $data[13];
                $plafond = str_replace($_separatorsNumb, $_separatorsNumb_, $data[14]);
                $premi = str_replace($_separatorsNumb, $_separatorsNumb_, $data[15]);
                $refpremi = $data[16];
                $tipepenutupan = $data[17];
                $as = $data[18];

                $npk = str_replace("'", "''", $npk);
                $nama = str_replace("'", "''", $nama);
                $alamat = str_replace("'", "''", $alamat);
                $qproduk = mysql_fetch_array(mysql_query("SELECT * FROM ajkpolis WHERE idcost = '".$idclient."' AND (ref_mapping = '".$produk."' or produk = '".$produk."')"));
                $qcabang = mysql_fetch_array(mysql_query("SELECT * FROM ajkcabang WHERE idclient = '".$idclient."' AND (ref_mapping = '".$cab."' or name = '".$cab."')"));
                $qinsurance = mysql_fetch_array(mysql_query("SELECT * FROM ajkinsurance WHERE idc = '".$idclient."' AND (ref_mapping = '".$as."' or name = '".$as."')"));
                $qpekerjaan = mysql_fetch_array(mysql_query("SELECT * FROM ajkprofesi WHERE ref_mapping = '".$pekerjaan."' "));
                $qkatprofesi = mysql_fetch_array(mysql_query("SELECT * FROM ajkkategoriprofesi WHERE id = '".$qpekerjaan['idkategoriprofesi']."' "));
                $asuransi = $qinsurance['id'];

                $idpolicy = $qproduk['id'];
                $usia = birthday($tgllahir, $tglakad);
                $tenor = datediffmonth($tglakad, $tglakhir);

                $qpolisas = mysql_fetch_array(mysql_query("SELECT *
																									FROM ajkpolisasuransi
																									WHERE idbroker = '".$idbro."' and
																												idcost = '".$idclient."' and
																												idproduk = '".$idpolicy."' and
																												idas = '".$asuransi."' and
																												del is null"));
                $idaspolis = $qpolisas['id'];

                $regionalpeserta = $qcabang['idreg'];
                $cabangpeserta = $qcabang['er'];
                $areapeserta = $qcabang['idarea'];


                if ($idbro < 9) {
                    $kodeBroker = '0'.$idbro;
                } else {
                    $kodeBroker = $idbro;
                }

                if ($idpolicy == 12) {
                  $ratebank = $qkatprofesi['baserate'];
                  $premibank = $plafond /1000 * $ratebank;
                }else{ 
                  $ratebank = $tenor/12*$qkatprofesi['baserate'];
                  $premibank = round($plafond /1000 * $ratebank,3);
                }

                $ratebank_sys = $ratebank;
                $premibank_sys = $premibank;     
                $tglakad = substr($tglakad, 0, 4).'-'.substr($tglakad, -4, 2).'-'.substr($tglakad, -2, 2);
                $tglakhir = substr($tglakhir, 0, 4).'-'.substr($tglakhir, -4, 2).'-'.substr($tglakhir, -2, 2);

                $querypeserta = insertpeserta_temp($autonumber,$idbro, $idclient, $idpolicy, $idpeserta, $newfilename, $ktp, $nocif, $npk, $nama, $gender, $tptlahir, $tgllahir, $usia, $pekerjaan, $plafond, $tglakad, $tenor, $tglakhir, $tglakad,$ratebank, $ratebank_sys, $premibank, $premibank_sys, $premibank, $rateasuransi, $premiasuransi, $premiasuransi, $alamat, $regionalpeserta, $areapeserta, $cabangpeserta, $nopinjaman, $refpremi, $asuransi, $iduser, $mamettoday);
                mysql_query($querypeserta);

                //cadangan asuransi start
                $persentase_cadangan_klaim = $qinsurance['cad_klaim'];
                $persentase_cadangan_refund = $qinsurance['cad_premi'];
                $bunga = '6.5';
                $tenorsetahun = $tenor/12;
                $tahuncadangan = 1;
                $plafond_cadangan = $plafond;

                while ($tahuncadangan <= $tenorsetahun) {
                    if ($qproduk['id'] == 12) {
                        $angsuran = round($plafond*(($bunga/100)/12)/(1-(1/pow((1+($bunga/100)/12), $tenor))), 0);

                        $i = 1;
                        while ($i <= ($tahuncadangan*12) -11) {
                            if ($i == 1) {
                                $plafond_cadangan = $plafond;
                                $nilai_cicilan = round(($plafond_cadangan * 3.75/1000), 0);
                            } else {
                                $bungabulan = $plafond_cadangan * (($bunga/100)/12);
                                $cicilanpokok = $angsuran - $bungabulan;
                                $plafond_cadangan = round($plafond_cadangan - $cicilanpokok, 0);
                                $nilai_cicilan = round(($plafond_cadangan * 3.75/1000), 0);
                            }
                            // $due = date('Y-m-d', strtotime('+'.$tahuncadangan.' years'));
                            $tambah = $tahuncadangan - 1;
                            $newdate = strtotime('+'.$tambah.' years',$tglakad);
                            $due = date('Y-m-d', $newdate);                            
                            $i++;                            
                        }
                        $nilai_cadangan_klaim = ($nilai_cicilan * $persentase_cadangan_klaim/100);
                        $nilai_cadangan_refund = ($nilai_cicilan * $persentase_cadangan_refund/100);

                    } else {
                        $nilai_cicilan = 0;
                        $nilai_cadangan_klaim = ($premibank * $persentase_cadangan_klaim/100)/$tenorsetahun;
                        $nilai_cadangan_refund = ($premibank * $persentase_cadangan_refund/100)/$tenorsetahun;
                    }


                    $querycadangan = insertcadangan($idpeserta, $tahuncadangan, round($nilai_cadangan_klaim, 0), round($nilai_cadangan_refund, 0), $iduser, $mamettoday, $bunga, $nilai_cicilan, $due,$plafond_cadangan);
                    mysql_query($querycadangan);
                    $tahuncadangan++;
                }
                
                //cadangan asuransi end
            }
            
            $insertpes = insertpeserta($newfilename);
            mysql_query($insertpes);
                
            header("location:../upload?xq=".$typeupload."&pesan=Berhasil di Simpan");
        break;
        
        case 'uploadcsf':
          $typeupload = AES::encrypt128CBC('uploadcsf', ENCRYPTION_KEY);
          $file_temp = $_SESSION['file_temp'];
          $file_name = $_SESSION['file_name'];
          $path = '../myFiles/_uploaddata/'.$foldername;

          if (!file_exists($path)) {
              mkdir($path, 0777);
              chmod($path, 0777);
          }

          $inputFileName = '../upload/temp/'.$file_temp;

          $newfilename = date('ymd_his').'_'.$file_name;
          copy($inputFileName, $path.$newfilename) or die("Could not upload file!");

          

          $handle = fopen($path.$newfilename, "r");
          if ($handle) {
              while (($line = fgets($handle)) !== false) {
               $data = explode('|', $line);
               //echo $data[0];
               $query = "UPDATE ajkpeserta SET noasuransi = '".$data[1]."' WHERE idpeserta = '".$data[0]."'";
               // echo $query.'<br>';
               mysql_query($query);
             }
           }

           header("location:../upload?xq=".$typeupload."&pesan=Berhasil di Simpan");
        break;

        case 'newresturno':
          $file_name = $_FILES['attachment']['name'];          
          $file_name_tmp = $_FILES['attachment']['tmp_name'];
          $namefile = $file_info["attachment"].'.'.$file_extension;
          $inputFileName = $file_name;
          $newfilename = $foldername."resturno".date("Ymd").$file_name;

          $path = '../myFiles/_uploaddata/'.$foldername;
          
          if (!file_exists($path)) {
              mkdir($path, 0777);
              chmod($path, 0777);
          }
          // move_uploaded_file($_FILES["attachment"]["tmp_name"], $path.$newfilename) or die( "Could not upload file!");
          $periode = $_POST['startdate'].'|'.$_POST['enddate'];
          $cabang = $_POST['cabang'];
          $keterangan = $_POST['keterangan'];
          $query = "INSERT INTO ajkhisresturno 
                    SET cabang = '".$cabang."',
                        periode = '".$periode."',
                        keterangan='".$keterangan."',
                        attachment='".$newfilename."',
                        input_by='".$iduser."',
                        input_date='".$mamettoday."'";
            //   echo $query;
          $result = mysql_query($query);
          header("location:../dashboard");
        break;

        
    }

    if($_REQUEST['han'] == 'delresturno'){
      $cabang = $_REQUEST['cab'];
      $periode = $_REQUEST['periode'];
      $query = "UPDATE ajkhisresturno
                SET update_by = '".$iduser."',
                    update_date = '".$mamettoday."',
                    del = 1
                WHERE cabang = '".$cabang."' and 
                      periode = '".$periode."'";
      // echo $query;
      $result = mysql_query($query);
      header("location:../dashboard");
    }
    
       
       
        
