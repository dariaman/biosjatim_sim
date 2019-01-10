<?php
include "../param.php";

// echo ini_get('display_errors');
// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }
// echo ini_get('display_errors');
// print_r($_POST);exit;

$keterangan = $_POST['keterangan'];
// $nameTo = 'AJK ADONAI';
// $emailTo = 'hansen@adonai.co.id';
// $emailTo = 'ajk@adonai.co.id';
// $emailTo = 'fahman@adonaits.co.id';
// $emailTo = 'chrismanuel@adonaits.co.id';

$sender = [
  $_POST['email'] => $_POST['email']
];

$recipients = [
  'hansen@adonai.co.id' => 'Hansen',
  'fahman@adonaits.co.id' => 'Fahman',
  'chrismanuel@adonaits.co.id' => 'Chris',
  'titis@adonaits.co.id' => 'Titis',
];

// print_r($_POST);exit;

$attachment = [$_FILES['attachment1']['tmp_name'],$_FILES['attachment1']['name']];

kirimemail($sender, 'Admin', $recipients, [], [], 'FAQ JATIM', $keterangan, $attachment, '/revisi/');
