<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// @copyright Januari 2016
// ----------------------------------------------------------------------------------
error_reporting(0);
session_start();
ob_start("ui_output_callback");
include_once('../includes/jjt1502.php');
include_once('../includes/db.php');
include_once("../includes/Encrypter.class.php");
$thisEncrypter = new textEncrypter();
$today = date('Y-m-d h:i:s');
include_once('../includes/functions.php');
include_once('../includes/excel_reader2.php');
include_once('../sendmail/sendmail.php');

global $database;
$database = new db();
$ui_template = dirname(__FILE__)."/templates/". Atheme . "/index.html";
out('site_title', 'BIOS - Broker Insurance Online System');
out('template_name', Atheme);

checkLogin();
getLogin();
debug();

if (isset($_SESSION['username'])) {
    $q=mysql_fetch_array($database->doQuery('SELECT * FROM useraccess WHERE username="'.$_SESSION['username'].'"'));
}
if ($q['idbroker']==null) {
    $q_ = '';
    $q__ = '';
    $q___ = '';
    $q___1 = '';
    $q___2 = '';
    $q___3 = '';
    $q___4 = '';
    $q___5 = '';
    $q___6 = '';
} else {
    $q_ = 'AND id="'.$q['idbroker'].'"';
    $q__ = 'AND idc="'.$q['idbroker'].'"';
    $q___ = 'AND idbroker="'.$q['idbroker'].'"';
    $q___1 = 'AND ajkpeserta.idbroker="'.$q['idbroker'].'"';
    $q___2 = 'AND ajkratepremiins.idbroker="'.$q['idbroker'].'"';
    $q___3 = 'AND ajkdebitnote.idbroker="'.$q['idbroker'].'"';
    $q___4 = 'AND ajkpeserta_temp.idbroker="'.$q['idbroker'].'"';
    $q___5 = 'AND ajkumum.idbroker="'.$q['idbroker'].'"';
    $q___6 = 'AND ajkcobroker.id="'.$q['idbroker'].'"';
    $q___SPK = 'AND ajkspk.idbroker="'.$q['idbroker'].'"';
    $q___User = 'AND useraccess.idbroker="'.$q['idbroker'].'"';
    $metBroker_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM ajkcobroker WHERE id="'.$q['idbroker'].'"'));
}

function fget_contents($file)
{
    $fd = fopen($file, "r");
    $content = "";
    while (!feof($fd)) {
        $content .= fgets($fd, 4096);
    }
    fclose($fd);
    return $content;
}
function ui_output_callback($buffer)
{
    global $ui_template;
    global $ui_vars;
    global $authenticate, $public;
    global $amp;

    session_cache_limiter('private');
    session_cache_expire(30);

    session_start();
    $script_name = explode('/', $_SERVER["SCRIPT_NAME"]);

    if (checkPrivileges()) {
        $template = fget_contents($ui_template);
        $ui_vars["content"] = ui_build($buffer, $ui_vars);
        $html = ui_build($template, $ui_vars);
    } else {
        $html = '<script language="JavaScript">
			     	alert(\'Maaf tidak sesuai dengan hak akses program!!!!\');
			    	history.back(1);
		    	</script>';
    }
    return $html;
}
function ui_build($template, $vars)
{
    if (preg_match_all('/\{([\w]+)\}/', $template, $matches) > 0) {
        $patterns = array();
        $replacements = array();
        for ($i = 0; $i < count($matches[0]); $i++) {
            $var = $matches[1][$i];
            $patterns[] = '/\{' . $var . '\}/';
            $replacements[] = $vars[$var];
        }
        return preg_replace($patterns, $replacements, $template);
    }

    return $template;
}
function out($var, $content)
{
    global $ui_vars;
    $ui_vars[$var] = $content;
}
function redirect($page)
{
    ob_end_clean();
    header("Location: $page");
    exit;
}
function notify_session()
{
    if (!isset($_SESSION['username'])) {
        return "Illegal User";
    }
    return $_SESSION["username"];
}
function debug()
{
    if (isset($_REQUEST['debug']) == 1) {
        foreach ($_REQUEST as $k => $v) {
            echo $k . ' : ' . $v . '<br />';
        }
    }
}
function checkLogin()
{
    if (!isset($_SESSION['username'])) {
        if (!eregi('module/accessUser.php', $_SERVER['SCRIPT_NAME'])) {
            header('location: ajk.php?re=access');
        }
    }
    // return true;
}
function getLogin()
{
    if (isset($_SESSION['username'])) {
        $database = new db();
        $q=mysql_fetch_array($database->doQuery('SELECT * FROM useraccess WHERE username="'.$_SESSION['username'].'"'));
        out('user', 'Login : <b>'.$q['firstname'] . '</b>');
        $timeout = 1800; // Number of seconds until it times out.
        if (isset($_SESSION['timeout'])) { 		// Check if the timeout field exists.
            // See if the number of seconds since the last
            // visit is larger than the timeout period.
            $duration = time() - (int)$_SESSION['timeout'];
            if ($duration > $timeout) {
                // Destroy the session and restart it.
                //session_destroy();
                header('location: ajk.php?re=access&opp=SignOut');
            }
        }
        // Update the timout field with the current time.
        $_SESSION['timeout'] = time();
    }
}
function checkPrivileges()
{
    global $ui_vars;
    if (isset($ui_vars['mod'])) {
        $q = mysql_query('SELECT ' . $ui_vars['privilege'] . '_PRIV FROM v_privileges WHERE GROUP_ID="' . $_SESSION['id_group'] . '" AND id_level="' . $_SESSION['id_level'] . '" AND MOD_CODE="' . $ui_vars['mod'] . '"');
        $r = mysql_fetch_array($q);
        if (($r[0]==1)) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function valid_date($str)
{
    $stamp = strtotime($str);
    if (!is_numeric($stamp)) {
        return false;
    }
    $month = date('m', $stamp);
    $day   = date('d', $stamp);
    $year  = date('Y', $stamp);
    if (checkdate($month, $day, $year)) {
        return $year.'-'.$month.'-'.$day;
    }
    return false;
}

include_once('module/metMenus.php');
out(_metmenusheader, $_metheader);
out(_metmenusleft, $_metMenusLeft);
out(_metmenusright, $_metMenusRight);
out(_metmenusfooter, $_metfooter);
out(_content, $_metContent);
out(_metico, $_metico);


if (isset($_REQUEST['re'])=="home") {
    echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/core.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/app.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/chat.js"></script>';
} else {
    echo '<script type="text/javascript" src="templates/{template_name}/javascript/vendor.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/core.js"></script>
      <script type="text/javascript" src="templates/{template_name}/javascript/backend/app.js"></script>
  	<script type="text/javascript" src="templates/{template_name}/plugins/parsley/js/parsley.js"></script>';
}
