<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// YM(Yahoo Messenger) : penting_kaga
// @ Copyright 2016 on January
// ----------------------------------------------------------------------------------
error_reporting(0);
session_start();
include_once('../includes/jjt1502.php');
include_once('../includes/db.php');
include_once('../includes/functions.php');
$database = new db();

if (isset($_REQUEST['op']) == 'Sign In') {
    $username = anti_injection($_REQUEST['username']);
    $pass     = anti_injection(md5($_REQUEST['passw']));
    if (!ctype_alnum($username) or !ctype_alnum($pass)) {
        $xxx = '<div class="alert alert-danger alert-dismissable">Injection pada username atau password, sistem otomatis telah mencatat IP anda.</div>';
        $metInj = $database->doQuery('INSERT INTO loginakses SET ip="'.$alamat_ip.'", pcname="'.$nama_host.'", browser="'.$useragent.'", username="'.$_REQUEST['username'].'", password="'.$_REQUEST['passw'].'", datetime="'.$futgl.'"');
    } else {
        $database->doQuery('SELECT * FROM useraccess WHERE username="' . mysql_real_escape_string($username) . '" AND idclient IS NULL AND aktif="Y" ');
        if (mysql_num_rows($database->dbQuery)) {
            $r = mysql_fetch_array($database->dbQuery);
            if ($pass == $r['passw']) {
                //session_register('usernama');
                isset($_SESSION['username']);
                $_SESSION['username'] = $username;
                header('Location: ajk.php?re=home');
            } else {
                $xxx = '<div class="alert alert-danger alert-dismissable"><strong>Error!</strong> Username atau password anda tidak dikenal.</div>';
            }
        } else {
            $xxx = '<div class="alert alert-danger alert-dismissable"><strong>Error!</strong> Username atau password anda tidak dikenal.</div>';
        }
    }
} elseif (isset($_REQUEST['opp']) == 'SignOut') {
    session_destroy();
    header("location: ajk.php?re=access");
}

?>
<!DOCTYPE html>
<html class="backend">
    <!-- START Head -->

<!-- Mirrored from optimisticdesigns.herokuapp.com/landerv2/html/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 08 Jul 2015 07:30:46 GMT -->
<head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Credit Life Insurance online System</title>
        <meta name="author" content="asuransijiwakredit">
        <meta name="description" content="Asuransi Jiwa Kredit">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="templates/{template_name}/image/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="templates/{template_name}/image/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="templates/{template_name}/image/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="templates/{template_name}/image/touch/apple-touch-icon-57x57-precomposed.png">
        <!--{_metico}-->
        <!--/ END META SECTION -->

        <!-- START STYLESHEETS -->
        <!-- Plugins stylesheet : optional -->
        <!--/ Plugins stylesheet : optional -->

        <!-- Application stylesheet : mandatory -->
        <link rel="stylesheet" href="templates/themeAdmin/stylesheet/bootstrap.css">
        <link rel="stylesheet" href="templates/themeAdmin/stylesheet/layout.css">
        <link rel="stylesheet" href="templates/themeAdmin/stylesheet/uielement.css">
        <!--/ Application stylesheet -->

        <!-- Theme stylesheet -->
		<link rel="stylesheet" href="templates/themeAdmin/stylesheet/themes/theme.css">
        <!--/ Theme stylesheet -->

        <!-- modernizr script -->
        <script type="text/javascript" src="templates/themeAdmin/plugins/modernizr/js/modernizr.js"></script>
        <!--/ modernizr script -->
        <!-- END STYLESHEETS -->
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body>
        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START Template Container -->
            <section class="container">
                <!-- START row -->
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <!-- Brand -->
                        <div class="text-center" style="margin-bottom:40px;">
                            <!--<span class="logo-figure inverse"></span>
                            <span class="logo-text inverse"></span>-->
                            <h5 class="semibold text-muted mt-5">Login to your account.</h5>
                        </div>
                        <!--/ Brand -->

                        <hr><!-- horizontal line -->
						<?php echo $xxx;	?>
                        <!-- Login form -->
                        <form class="panel" name="form-login" action="" method="post">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="form-stack has-icon pull-left">
                                        <input name="username" type="text" class="form-control input-lg" placeholder="Username / email" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your username / email" data-parsley-required>
                                        <i class="ico-user2 form-control-icon"></i>
                                    </div>
                                    <div class="form-stack has-icon pull-left">
                                        <input name="passw" type="password" class="form-control input-lg" placeholder="Password" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your password" data-parsley-required>
                                        <i class="ico-lock2 form-control-icon"></i>
                                    </div>
                                </div>

                                <!-- Error container -->
                                <div id="error-container"class="mb15"></div>
                                <!--/ Error container -->

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="checkbox custom-checkbox">
                                                <input type="checkbox" name="remember" id="remember" value="1">
                                                <label for="remember">&nbsp;&nbsp;Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="javascript:void(0);">Lost password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group nm">
                                	<input type="submit" class="btn btn-block btn-success" name="op" value="Sign In"/>
<!--                                <button type="submit" class="btn btn-block btn-success"><span class="semibold">Sign In</span></button>-->
                                </div>
                            </div>
                        </form>
                        <!-- Login form -->

                        <hr><!-- horizontal line -->
                    </div>
                </div>
                <!--/ END row -->
            </section>
            <!--/ END Template Container -->
        </section>
        <!--/ END Template Main -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <!-- Application and vendor script : mandatory -->
        <script type="text/javascript" src="templates/themeAdmin/javascript/vendor.js"></script>
        <script type="text/javascript" src="templates/themeAdmin/javascript/core.js"></script>
        <script type="text/javascript" src="templates/themeAdmin/javascript/backend/app.js"></script>
        <!--/ Application and vendor script : mandatory -->

        <!-- Plugins and page level script : optional -->
        <script type="text/javascript" src="templates/themeAdmin/javascript/pace.min.js"></script>
		<script type="text/javascript" src="templates/themeAdmin/plugins/parsley/js/parsley.js"></script>
        <script type="text/javascript" src="templates/themeAdmin/javascript/backend/pages/login.js"></script>
        <!--/ Plugins and page level script : optional -->
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>
