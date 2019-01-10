<?php
/********************************************************************
 DESC  : Create by satrya;
 EMAIL : satryaharahap@gmail.com;
 Create Date : 2015-04-08

 ********************************************************************/
require_once('connect.php');

    switch($_POST["functionname"]){
        case 'produk':
			$qry = 'SELECT DISTINCT FT_payment_modes, PM_Desc FROM modal_factor, factor_table, payment_modes
														WHERE MF_table = FT_key AND MF_Product = "'.$_POST['id'].'"
														AND PM_code = FT_payment_modes
														AND MF_Version = (SELECT max(MF_Version) from modal_factor WHERE MF_Product ="'.$_POST['id'].'" )
														ORDER BY FT_payment_modes ASC';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
			$id = $row['FT_payment_modes'];
			$mode_des = $row['PM_Desc'];
			echo '<option value="'.$id.'">'.$id.' - '.$mode_des.'</option>';
			}
			break;

		case 'rider':
			?>
			<option value="">Produk Tambahan</option>
            <?php
			$qry = 'SELECT DISTINCT CE_product_no, Prod_Long_Desc
					FROM co_existing JOIN modal_factor ON CE_product = MF_Product AND CE_Version = MF_Version
					JOIN factor_table ON MF_table = FT_key
					LEFT JOIN product ON Prod_code = CE_product_no
					WHERE CE_Product = "'.$_POST['id'].'" AND CE_Version = (select max(ce_version) from co_existing where CE_product = "'.$_POST['id'].'") ORDER BY CE_product_no';

			print_r($qry);
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
			$ridercode = $row['CE_product_no'];
			$riderdesc = $row['Prod_Long_Desc'];
			?>
				<option value="<?php echo $ridercode; ?>"><?php echo $ridercode; ?> - <?php echo $riderdesc; ?></option>
			<?php } ?>
			<?php
			break;

		case 'ac_id':
			$qry = 'select AC_ID
					FROM agen_code
					WHERE AC_name = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			$row = mysql_fetch_array($sql);
			$ls_kodeagent = $row['AC_ID'];
            echo $ls_kodeagent;
			break;

		case 'branch':
			$qry = 'select AC_Branch_code
					FROM agen_code
					WHERE AC_name = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			$row = mysql_fetch_array($sql);
			$ls_kodecabang = $row['AC_Branch_code'];
            echo $ls_kodecabang;
			break;

		case 'branchname':
			$qry = 'select BC_Name
					FROM agen_code
					LEFT JOIN branch_code on BC_Code = AC_Branch_code
					WHERE AC_name = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			$row = mysql_fetch_array($sql);
			$ls_namacabang = $row['BC_Name'];
            echo $ls_namacabang;
			break;

    	case 'fund':
    		?>
			<option value="">--Produk Fund--</option>
            <?php
	    	$qry = 'SELECT PF_Fund, FC_Desc FROM product_fund
	    		LEFT JOIN fund_code ON FC_Code = PF_Fund
				WHERE PF_Product = "'.$_POST['produccode'].'" AND PF_Use = 1
				AND PF_Version = (SELECT prod_version FROM product Where prod_code = "'.$_POST['produccode'].'" AND prod_confirm = 1)';

	    	print_r($qry);
	    	$sql = mysql_query($qry);
	    	while($row = mysql_fetch_array($sql)){
				$fundcode = $row['PF_Fund'];
	    		$fudndesc = $row['FC_Desc']
	    		?>
					<option value="<?php echo $fundcode; ?>"><?php echo $fundcode; ?> - <?php echo $fudndesc; ?></option>
				<?php } ?>
				<?php
	    	break;

    	case 'premiumterm':
    		?>
            <?php
    		$qry = 'select distinct PR_Premium_Term from premium_rate where PR_key = "'.$_POST['id'].'" ORDER BY PR_Premium_Term ASC';

    		print_r($qry);
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$li_premiumterm = $row['PR_Premium_Term'];
    				?>
					<option value="<?php echo $li_premiumterm; ?>"><?php echo $li_premiumterm; ?></option>
				<?php } ?>
				<?php
    		break;

    	case 'benterm':
    		?>
            <?php
    		$qry = 'select distinct PR_Benefit_Term from premium_rate where PR_key = "'.$_POST['id'].'" ORDER BY PR_Benefit_Term ASC';

    		print_r($qry);
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$li_benterm = $row['PR_Benefit_Term'];
    			?>
					<option value="<?php echo $li_benterm; ?>"><?php echo $li_benterm; ?></option>
				<?php } ?>
				<?php
    		break;

    	case 'benefitterm':
    		?>
            <?php
    		$qry = 'select distinct PR_Benefit_Term from premium_rate where PR_key = "'.$_POST['produk'].'" and PR_Premium_Term = "'.$_POST['premterm'].'" ORDER BY PR_Benefit_Term ASC';

    		print_r($qry);
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$li_benefit = $row['PR_Benefit_Term'];
    			?>
					<option value="<?php echo $li_benefit; ?>"><?php echo $li_benefit; ?></option>
				<?php } ?>
				<?php
    		break;

    	case 'prembenefit':
    		?>
            <?php
    		$qry = 'select distinct PR_Premium_Term from premium_rate where PR_key = "'.$_POST['produk'].'" and PR_Benefit_Term = "'.$_POST['premterm'].'" ORDER BY PR_Premium_Term ASC';

    		print_r($qry);
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$li_benefit = $row['PR_Premium_Term'];
    			?>
					<option value="<?php echo $li_benefit; ?>"><?php echo $li_benefit; ?></option>
				<?php } ?>
				<?php
    		break;

    	case 'provinsi':
    		?>
            <?php
    		$qry = 'SELECT nama_propinsi, nama_kabupaten FROM master_propinsi
					LEFT JOIN master_kabupaten ON master_kabupaten.id_propinsi = master_propinsi.id_propinsi
					WHERE nama_propinsi = "'.$_POST['prov'].'"';

    		print_r($qry);
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$ls_kota = $row['nama_kabupaten'];
    			?>
					<option value="<?php echo $ls_kota; ?>"><?php echo $ls_kota; ?></option>
				<?php } ?>
				<?php
    		break;
    	case 'topup':
    		$qry = 'select BU_Min_TU from balance_unit where BU_Product = "'.$_POST['id'].'"';
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			$mintopup = $row['BU_Min_TU'];
    			echo $mintopup;
    		}
    		break;
		case 'riderup':
			$qry = 'SELECT DISTINCT CE_max_SA FROM co_existing WHERE CE_Product = "'.$_POST['id'].'" AND CE_product_no = "'.$_POST['rider'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['CE_max_SA'];
			}
			break;
		case 'riderupmin':
			$qry = 'SELECT DISTINCT CE_min_SA FROM co_existing WHERE CE_Product = "'.$_POST['id'].'" AND CE_product_no = "'.$_POST['rider'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['CE_min_SA'];
			}
			break;
		case 'rideragemin':
			$qry = 'SELECT DISTINCT CE_Age_from FROM co_existing WHERE CE_Product = "'.$_POST['id'].'" AND CE_product_no = "'.$_POST['rider'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['CE_Age_from'];
			}
			break;
		case 'rideragemax':
			$qry = 'SELECT DISTINCT CE_Age_to FROM co_existing WHERE CE_Product = "'.$_POST['id'].'" AND CE_product_no = "'.$_POST['rider'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['CE_Age_to'];
			}
			break;

		case 'premtype':
			$qry = 'select DISTINCT BI_Premium_type from basic_info where BI_Product = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['BI_Premium_type'];
			}
			break;

		case 'prodtype':
			$qry = 'select DISTINCT BI_Product_type from basic_info where BI_Product = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				echo $row['BI_Product_type'];
			}
			break;
		case 'covage':
    	$qry = 'select DISTINCT BI_cov_age from basic_info where BI_Product = "'.$_POST['id'].'"';
			$sql = mysql_query($qry);
			while($row = mysql_fetch_array($sql)){
				$covage = $row['BI_cov_age'];
				echo $covage;
			}
			break;

    	case 'payterm':
    		$qry = 'select DISTINCT BI_pay_term from basic_info where BI_Product = "'.$_POST['id'].'"';
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			echo $row['BI_pay_term'];
    		}
    		break;

    	case 'topreguler':
    		$qry = 'SELECT SM_Min_Modal FROM sa_mode_table WHERE SM_Key = "'.$_POST['id'].'" AND SM_Mode = "'.$_POST['mode'].'"';
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			echo $row['SM_Min_Modal'];
    		}
    		break;
    	case 'modefrequency':
    		$qry = 'SELECT * FROM payment_modes WHERE PM_code = "'.$_POST['mode'].'"';
    		$sql = mysql_query($qry);
    		while($row = mysql_fetch_array($sql)){
    			echo $row['PM_frequency'];
    		}
    		break;

		}

?>