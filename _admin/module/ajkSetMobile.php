<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
echo '<section id="main" role="main">
		<div class="container-fluid" style="min-height:1024px;"><!-- add min-height to simulate scrolling -->
		<div class="page-header page-header-block">';
switch ($_REQUEST['er']) {
	case "dd":
		;
		break;
	case "Ff":
		;
		break;
	default:
echo '<div class="page-header-section"><h2 class="title semibold">Modul Setup Mobile</h2></div>
      	<div class="page-header-section"><div class="toolbar"><a href="ajk.php?re=exl&op=new">'.BTN_NEW.'</a></div></div>
      </div>';
		echo '
                    <div class="col-lg-12">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <form class="panel form-horizontal form-bordered" name="form-profile">
                                    <div class="panel-body pt0 pb0">
                                        <div class="form-group header bgcolor-default">
                                            <div class="col-md-12">
                                                <h4 class="semibold text-primary mt0 mb5">Logo</h4>
                                                <p class="text-default nm">This information appears on your mobile logo and website frontend.</p>
                                            </div>
                                        </div>
                                        <center>
                                        <div class="form-group">
                                        <div class="col-md-4">
                                            Logo mobile halamn depan 1000 x 213
                                            <div class="col-sm-12">
                                                <div class="btn-group pr5"><img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="34px"></div>
                                                <div class="btn-group"><button type="button" class="btn btn-default">Change photo</button></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            Logo mobile halamn depan 1000 x 213
                                            <div class="col-sm-12">
                                                <div class="btn-group pr5"><img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="34px"></div>
                                                <div class="btn-group"><button type="button" class="btn btn-default">Change photo</button></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            Logo mobile halamn depan 1000 x 213
                                            <div class="col-sm-12">
                                                <div class="btn-group pr5"><img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="34px"></div>
                                                <div class="btn-group"><button type="button" class="btn btn-default">Change photo</button></div>
                                            </div>
                                        </div>
                                        </div>
										</center>
                                        <div class="form-group header bgcolor-default">
                                            <div class="col-md-12">
                                                <h4 class="semibold text-primary nm">Description</h4>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" rows="3" placeholder="Describe about aplication mobile"></textarea>
                                                <p class="help-block">Description for aplication mobile</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="panel-footer">
                                        <button type="reset" class="btn btn-default">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save change</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
		;
} // switch
echo '</div>
		<a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
    </section>';
?>