<?php
/* ----------------------------------------------------------------------------------
   Copyright (C) JANUARI 2016 APLIKASI AJK PENSIUN
   Original Author Of File : Rahmad
   E-mail :kepodank@gmail.com
   ---------------------------------------------------------------------------------- */
# date and time
date_default_timezone_set('Asia/Jakarta');
$dateY = date("Y");
$datelog = date("Y-m-d");
$futgl = date("Y-m-d H:i:s");
$futoday  = date("Y-m-d");
$futgldn = date("d/m/Y");
$timelog = date("G:i:s");
$timelog2 = date("G_i_s");
$DatePolis = date("dmy");
$DatePolis1 = date("Ymd");
$tglIndo = date("d m Y");
$_blnIndo = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
$_blnIndo_ = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");


# file size upload data
$FILESIZE_1 = 1000000; // max file size (1MB)
$FILESIZE_2 = 2000000; // max file size (2MB)
$FILESIZE_3 = 3000000; // max file size (3MB)
$FILESIZE_4 = 4000000; // max file size (4MB)
$FILESIZE_5 = 5000000; // max file size (5MB)
# file size upload data

# file extension yang diijinkan
$AllowedExtsXLS		= array("application/xls");
$AllowedExtsPDF		= array("application/pdf");
$AllowedExtsIMG		= array("image/jpg", "image/jpeg");
$AllowedExtsPDFIMG	= array("application/pdf", "image/jpg", "image/jpeg");
# file extension yang diijinkan

# file penyimpanan data
$foldername = date("y", strtotime($futgl)).date("m", strtotime($futgl)).'/';	//PENAMABAN FOLDER SESUAI TGL DIBUAT SISTEM
$PathDokumen		= "myFiles/_docs/";
$PathUploadExcel	= "myFiles/_uploaddata/";
$PathPhoto			= "myFiles/_photo/";
$PathRate			= "myFiles/_rate/";
$PathTblMedical		= "myFiles/_medical/";
$PathPhotoGeneral	= "myFiles/_photogeneral/";
$PathSignature		= "myFiles/_signature/";
$PhotoGeneral_F		= "myFiles/_general/_flexas/";
$PhotoGeneralDebitur = "myFiles/_general/_debitur/";
$PhotoGeneralSurvey = "myFiles/_general/_survey/";
# file penyimpanan data

# info
$alamat_ip = $_SERVER['REMOTE_ADDR'];
// $nama_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$nama_host = $_SERVER['REMOTE_ADDR'];
$useragent = $_SERVER ['HTTP_USER_AGENT'];
$referrer = getenv('HTTP_REFERER');
$base_url_user="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

$metSetAutoNumber = "10000000000";

# SEPARATOR
$_separatorsNumb 	 = array(",", ".", "*", ".00", " ", "?", "?**", "'");		$_separatorsNumb_ 	  = array("");
$_separatorsChar 	 = array(",", "/", "\"", "'", ";", ":", " ","\"");			$_separatorsChar_ 	  = array("");
$_separatorsRate 	 = array("*", " ", "?", "?**");								$_separatorsRate_ 	  = array("");
$_separatorsFilename = array("/", " ", "  ", "");								$_separatorsFilename_ = array("_");

# BUTTON
define("BTN_NEW", "<button type=\"button\" class=\"btn btn-primary mb5 btn-xs\"><i class=\"ico-file\"></i> New</button>");
define("BTN_ADDGUARANTEE", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-file\"></i> Add</button>");
define("BTN_ADDGUARANTEEVIEW", "<button type=\"button\" class=\"btn btn-info mb5 btn-xs\"><i class=\"ico-eye\"></i> View</button>");
define("BTN_NEWREGIONAL", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-file\"></i> New Regional</button>");
define("BTN_NEWAREA", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-file\"></i> New Area</button>");
define("BTN_NEWBRANCH", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-file\"></i> New Branch</button>");
define("BTN_EDIT", "<button type=\"button\" class=\"btn btn-warning mb5 btn-xs\"><i class=\"ico-edit\"></i> Edit</button>");
define("BTN_DEL", "<button type=\"button\" class=\"btn btn-danger mb5 btn-xs\"><i class=\"ico-cancel\"></i> Delete</button>");
define("BTN_CANCEL", "<button type=\"button\" class=\"btn btn-danger mb5 btn-xs\"><i class=\"ico-cancel\"></i> Cancel</button>");
define("BTN_VIEW", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-eye\"></i> View</button>");
define("BTN_VIEW2", "<button type=\"button\" class=\"btn btn-warning mb5 btn-xs\"><i class=\"ico-eye\"></i> View</button>");
define("BTN_BACK", "<button type=\"button\" class=\"btn btn-teal mb5 btn-xs\"><i class=\"ico-arrow-left10\"></i> Back</button>");
define("BTN_BACK2", "<button type=\"button\" class=\"btn btn-danger mb5 btn-xs\"><i class=\"ico-arrow-left10\"></i> Back</button>");
define("BTN_UPLOADERROR", "<button type=\"button\" class=\"btn btn-danger mb5 btn-xs\"><i class=\"ico-arrow-left10\"></i> Upload Data Error !</button>");
define("BTN_SUBMIT", "<button type=\"submit\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-save\"></i> Submit</button>");
define("BTN_SUBMITMEDICAL", "<button type=\"button\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-save\"></i> Submit</button>");
define("BTN_SHAREINS", "<button type=\"submit\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-umbrella\"></i> Share Insurance</button>");
define("BTN_CREATEDN", "<button type=\"submit\" class=\"btn btn-success mb5 btn-xl\"><i class=\"ico-file8\"></i> Create Debitnote</button>");
define("BTN_NEWUPLOAD", "<button type=\"submit\" class=\"btn btn-danger mb5 btn-xl\"><i class=\"ico-file7\"></i> Data Uplaoding</button>");
define("BTN_KLAIM", "<button type=\"submit\" class=\"btn btn-info mb5 btn-xs\"><i class=\"ico-file7\"></i> Claim</button>");
define("BTN_UPLOADCLAIM", "<button type=\"button\" class=\"btn btn-info mb5 btn-xs\"><i class=\"ico-file7\"></i> Upload Claim</button>");
define("BTN_UPLOADCLAIM2", "<button type=\"submit\" class=\"btn btn-danger mb5 btn-xs\"><i class=\"ico-file7\"></i> No File Claim</button>");
define("BTN_UPLRATEGENERAL", "<button type=\"submit\" class=\"btn btn-primary mb5 btn-xl\"><i class=\"ico-file8\"></i> Upload Rate General</button>");
define("BTN_GOTOCREDITNOTE", "<button type=\"button\" class=\"btn btn-success mb5 btn-xl\"><i class=\"ico-file8\"></i> Go to Creditnote Validation</button>");
define("BTN_UPLOADDOC", "<button type=\"button\" class=\"btn btn-success mb5 btn-xl\"><i class=\"ico-file8\"></i> Upload Document Medical</button>");
define("BTN_SEARCHING", "<button type=\"submit\" class=\"btn btn-warning mb5 btn-xs\"><i class=\"ico-file8\"></i> Searching</button>");
define("BTN_APPROVESPK", "<button type=\"submit\" class=\"btn btn-success mb5 btn-xs\"><i class=\"ico-file8\"></i> Approved</button>");
define("BTN_APPROVESPKAktif", "<button type=\"submit\" class=\"btn btn-primary mb5 btn-xs\"><i class=\"ico-file8\"></i> Approve SPK</button>");
define("BTN_EDITSPK", "<button type=\"submit\" class=\"btn btn-primary mb5 btn-xs\"><i class=\"ico-file8\"></i> Edit SPK</button>");

# BUTTON
