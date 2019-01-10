<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
switch ($_REQUEST['re']) {
    case "home":			include_once('index.php');	break;
    case "access":			include_once('module/accessUser.php');	break;
    case "utilities":		include_once('module/ajkutilities.php');	break;
    case "general":			include_once('module/ajkgeneral.php');	break;
    case "uaccess":			include_once('module/ajkUser.php');	break;
    case "exl":				include_once('module/ajkExcel.php');	break;
    case "setdoc":			include_once('module/ajkSetDokumen.php');	break;
    case "setMobile":		include_once('module/ajkSetMobile.php');	break;
    case "medical":			include_once('module/ajkTblMedical.php');	break;
    case "signature":		include_once('module/ajkSignature.php');	break;
    case "regional":		include_once('module/ajkRegional.php');	break;
    case "cob":				include_once('module/ajkCoBroker.php');	break;
    case "client":			include_once('module/ajkClient.php');	break;
    case "ratepremi":		include_once('module/ajkRate.php');	break;
    case "rateclaim":		include_once('module/ajkRate.php');	break;
    case "fileupload":		include_once('module/ajkExcelUpload.php');	break;
    case "exsist":			include_once('module/ajkExisting.php');	break;
    case "dn":				include_once('module/ajkDebitnote.php');	break;
    case "ins":				include_once('module/ajkInsurance.php');	break;
    case "insrate":			include_once('module/ajkRateInsurance.php');	break;
    case "data":			include_once('module/ajkData.php');	break;
    case "dataGnr":			include_once('module/ajkDataGnr.php');	break;
    case "spk":				include_once('module/ajkSPK.php');	break;
    case "cclaim":			include_once('module/ajkcClaim.php');	break;
    case "rpt":				include_once('module/ajkReport.php');	break;
    case "arm":				include_once('module/ajkArm.php');	break;
    case "summary":			include_once('module/ajkOutstanding.php');	break;
    case "dlExcel":			include_once('../modules/modEXLdl.php');	break;
    case "dlPdf":			include_once('../modules/modPdfdl.php');	break;
    case "gpsbios":			include_once('module/ajkgps.php');	break;
    case "email":			include_once('module/ajkmail.php');	break;

    default:
    header("location:ajk.php?re=access&opp=SignOut");
;
} // switch
