<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// @ Copyright 2016
// ----------------------------------------------------------------------------------
include_once('ui.php');
if (isset($_SESSION['username'])) {
$userOn = mysql_fetch_array($database->doQuery('SELECT * FROM useraccess WHERE username="'.$_SESSION['username'].'"'));
if ($userOn['idbroker'] == NULL) { $metstatusHead = "<code>ADONAI TOTAL SOLUTION</code>";	}	else	{
	$metBroker = mysql_fetch_array($database->doQuery('SELECT id, name, logo FROM ajkcobroker WHERE id="'.$userOn['idbroker'].'"'));
	//$metstatusHead = '<code>'.$metBroker['name'].'</code>';
	$metstatusHead = $metBroker['name'];
}

$_metheader .='<!-- START Template Header -->
        <header id="header" class="navbar">
            <!-- START navbar header -->
            <div class="navbar-header">
                <!-- Brand -->
                <a class="navbar-brand" href="javascript:void(0);">
'.$metstatusHead.'
                </a>
                <!--/ Brand -->
            </div>
            <!--/ END navbar header -->

            <!-- START Toolbar -->
            <div class="navbar-toolbar clearfix">
                <!-- START Left nav -->
                <ul class="nav navbar-nav navbar-left">
                    <!-- Sidebar shrink -->
                    <li class="hidden-xs hidden-sm">
                        <a href="javascript:void(0);" class="sidebar-minimize" data-toggle="minimize" title="Minimize sidebar">
                            <span class="meta">
                                <span class="icon"></span>
                            </span>
                        </a>
                    </li>
                    <!--/ Sidebar shrink -->

                    <!-- Offcanvas left: This menu will take position at the top of template header (mobile only). Make sure that only #header have the `position: relative`, or it may cause unwanted behavior -->
                    <li class="navbar-main hidden-lg hidden-md hidden-sm">
                        <a href="javascript:void(0);" data-toggle="sidebar" data-direction="ltr" rel="tooltip" title="Menu sidebar">
                            <span class="meta">
                                <span class="icon"><i class="ico-paragraph-justify3"></i></span>
                            </span>
                        </a>
                    </li>
                    <!--/ Offcanvas left -->

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dashboard</a>
							<ul class="dropdown-menu">
								<li><a href="#">Premium</a></li>
								<li><a href="#">Klaim</a></li>
								<li><a href="#">SPK</a></li>
							</ul>
					</li>

                </ul>
                <!--/ END Left nav -->

                <!-- START navbar form -->
                <div class="navbar-form navbar-left dropdown" id="dropdown-form">
                    <form action="#" role="search">
                        <div class="has-icon">
                            <input type="text" class="form-control" placeholder="Search application...">
                            <i class="ico-search form-control-icon"></i>
                        </div>
                    </form>
                </div>
                <!-- START navbar form -->

                <!-- START Right nav -->
                <ul class="nav navbar-nav navbar-right">
					<!-- Notification dropdown -->
                    <li class="dropdown custom" id="header-dd-notification">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="meta">
                                <span class="icon"><i class="ico-envelop"></i></span>
                            </span>
                        </a>

                        <!-- Dropdown menu -->
                        <div class="dropdown-menu" role="menu">
                            <div class="dropdown-header">
                                <span class="title">Notification <span class="count"></span></span>
                                <span class="option text-right"><a href="javascript:void(0);">Clear all</a></span>
                            </div>
                            <div class="dropdown-body slimscroll">

								<!-- Message list -->
                                <div class="media-list">
                                    <a href="javascript:void(0);" class="media read border-dotted">
                                        <span class="media-object pull-left">
                                            <i class="ico-basket2 bgcolor-info"></i>
                                        </span>
                                        <span class="media-body">
                                            <span class="media-text">..... <span class="text-primary semibold">Empty mail</span>
                                            <span class="media-meta pull-right">2d</span>
                                        </span>
                                    </a>
                                </div>
                                <!--/ Message list -->
                            </div>
                        </div>
                        <!--/ Dropdown menu -->
                    </li>
                    <!--/ Notification dropdown -->

                    <!-- Profile dropdown -->
                    <li class="dropdown profile">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="meta">
                                <span class="avatar"><img src="../'.$PathPhoto.''.$q['photo'].'" class="img-circle" alt="" /></span>
                                <span class="text hidden-xs hidden-sm pl5">'.$q['firstname'].' '.$q['lastname'].'</span>
                            </span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="javascript:void(0);"><span class="icon"><i class="ico-user-plus2"></i></span> My Accounts</a></li>
                            <li><a href="javascript:void(0);"><span class="icon"><i class="ico-cog4"></i></span> Profile Setting</a></li>
                            <li><a href="javascript:void(0);"><span class="icon"><i class="ico-question"></i></span> Help</a></li>
                            <li class="divider"></li>
                            <li><a href="ajk.php?re=access&opp=SignOut"><span class="icon"><i class="ico-exit"></i></span> Sign Out</a></li>
                        </ul>
                    </li>
                    <!-- Profile dropdown -->
					<!-- Offcanvas right This menu will take position at the top of template header (mobile only). Make sure that only #header have the `position: relative`, or it may cause unwanted behavior -->
                    <!--<li class="navbar-main">
                        <a href="javascript:void(0);" data-toggle="sidebar" data-direction="rtl" rel="tooltip" title="Feed / contact sidebar">
                        <span class="meta"><span class="icon"><i class="ico-users3"></i></span></span>
                        </a>
                    </li>-->
                    <!--/ Offcanvas right -->

                </ul>
                <!--/ END Right nav -->
            </div>
            <!--/ END Toolbar -->
        </header>
        <!--/ END Template Header -->';
if ($q['level']=="1") {	//UNDERWRITING
	$metHIDDENSubCN_Create = 'class="hidden"';
	$metHIDDENAccounting = 'class="hidden"';
	$metHIDDENSUMPayment = 'class="hidden"';
	$metHIDDENSubCN_CreateData = 'class="hidden"';
}elseif ($q['level']=="2") {	//ARM
	$metHIDDENBroker = 'class="hidden"';
	$metHIDDENSetup = 'class="hidden"';
	$metHIDDENCostumer = 'class="hidden"';
	$metHIDDENUpload = 'class="hidden"';
	$metHIDDENSubDataView_Edit = 'class="hidden"';
	$metHIDDENSubDataView_EditGen = 'class="hidden"';
	$metHIDDENSubDN_Create = 'class="hidden"';
	$metHIDDENSubCN_Create = 'class="hidden"';
	$metHIDDENSubCN_CreateData = 'class="hidden"';
	$metHIDDENInsurance = 'class="hidden"';
}elseif ($q['level']=="3") {	//CLAIM
	$metHIDDENBroker = 'class="hidden"';
	$metHIDDENSetup = 'class="hidden"';
	$metHIDDENCostumer = 'class="hidden"';
	$metHIDDENUpload = 'class="hidden"';
	$metHIDDENSubDataView_Edit = 'class="hidden"';
	$metHIDDENSubDataView_EditGen = 'class="hidden"';
	$metHIDDENSubDN_Create = 'class="hidden"';
	$metHIDDENAccounting = 'class="hidden"';
	$metHIDDENSUMPayment = 'class="hidden"';
	$metHIDDENInsurance = 'class="hidden"';
}elseif ($q['level']=="9" || $q['level']=="10") {	//CLAIM
	$metHIDDENBroker = 'class="hidden"';
	$metHIDDENSetup = 'class="hidden"';
	$metHIDDENCostumer = 'class="hidden"';
	$metHIDDENUpload = 'class="hidden"';
	$metHIDDENSubDataView_Edit = 'class="hidden"';
	$metHIDDENSubDataView_EditGen = 'class="hidden"';
	$metHIDDENDebitnote = 'class="hidden"';
	$metHIDDENCreditnote = 'class="hidden"';
	$metHIDDENSubDN_Create = 'class="hidden"';
	$metHIDDENAccounting = 'class="hidden"';
	$metHIDDENReportMember = 'class="hidden"';
	$metHIDDENReportDebitnote = 'class="hidden"';
	$metHIDDENReportCreditnote = 'class="hidden"';
	$metHIDDENSUMPayment = 'class="hidden"';
	$metHIDDENInsurance = 'class="hidden"';
	$metHIDDENReportSummary = 'class="hidden"';

}else{
	$metHIDDENBroker = '';
	$metHIDDENSetup = '';
	$metHIDDENCostumer = '';
	$metHIDDENUpload = '';
	$metHIDDENSubDataView_Edit = '';
	$metHIDDENSubDataView_EditGen = '';
	$metHIDDENDebitnote = '';
	$metHIDDENCreditnote = '';
	$metHIDDENSubCN_Create = '';
	$metHIDDENSubCN_CreateData = '';
	$metHIDDENAccounting = '';
	$metHIDDENReportMember = '';
	$metHIDDENReportDebitnote = '';
	$metHIDDENReportCreditnote = '';
	$metHIDDENSUMPayment = '';
	$metHIDDENInsurance = '';
	$metHIDDENReportSummary = '';
}
	$_metMenusLeft .='        <!-- START Template Sidebar (Left) -->
        <aside class="sidebar sidebar-left sidebar-menu">
			<!-- START Sidebar Content -->
            <section class="content slimscroll">
				<!-- START Template Navigation/Menu -->
                <ul class="topmenu topmenu-responsive" data-toggle="menu">
                    <li>
                        <a href="ajk.php?re=home" data-target="#dashboard" data-parent=".topmenu">
                            <span class="figure"><i class="ico-home2"></i></span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    <li '.$metHIDDENBroker.'><a href="ajk.php?re=cob"><span class="figure"><i class="ico-office"></i></span><span class="text">Broker</span></a></li>
					<li '.$metHIDDENSetup.'>
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#layout" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Setup</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="layout" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Setup</li>                            
                            <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#setupklaim" data-parent="#page">
                                    <span class="text">Claim</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="setupklaim" class="submenu collapse ">
    		                        <li><a href="ajk.php?re=setdoc&cl=claim"><span class="text">Document Claim</span><span class="number"><span class="label label-danger">N</span></span></a></li>
	                                <li><a href="ajk.php?re=setdoc&cl=pod"><span class="text">Place of death</span></a></li>
	                                <li><a href="ajk.php?re=setdoc&cl=cod"><span class="text">Cause of death</span></a></li>
                                </ul>
                            </li>
                            <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#setuputilities" data-parent="#page">
                                    <span class="text">Utilities</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="setuputilities" class="submenu collapse ">
    		                        <li>
    		                        	<a href="javascript:void(0);" data-toggle="submenu" data-target="#setuputilitiesmbl" data-parent="#page">
                                    	<span class="text">Mobile</span>
                                    	<span class="arrow"></span>
                                		</a>
                                		<ul id="setuputilitiesmbl" class="submenu collapse ">
                                		<li><a href="ajk.php?re=setMobile"><span class="text">Logo</span><span class="number"><span class="label label-teal">L</span></span></a></li>
                                		<li><a href="ajk.php?re=utilities&er=mobnotif"><span class="text">Notification</span><span class="number"><span class="label label-danger">N</span></span></a></li>
                                		</ul>
									</li>
    		                        <li><a href="ajk.php?re=utilities"><span class="text">Website</span><span class="number"><span class="label label-primary">W</span></span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li '.$metHIDDENCostumer.'>
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#page" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Customer</span>
                            <span class="arrow"></span>
                        </a>
                        <!-- START 2nd Level Menu -->
                        <ul id="page" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Customer</li>
                            <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#media" data-parent="#page">
                                    <span class="text">Partner</span>
                                    <span class="arrow"></span>
                                </a>
                                <!-- START 2nd Level Menu -->
                                <ul id="media" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=client&op=comp"><span class="text">Company Name</span></a></li>
                                    <li ><a href="ajk.php?re=client&op=policy"><span class="text">Product</span></a></li>
                                    <li ><a href="ajk.php?re=ratepremi"><span class="text">Rate Premium</span></a></li>
                                    <li ><a href="ajk.php?re=raterefund"><span class="text">Rate Refund</span></a></li>
                                    <li ><a href="ajk.php?re=rateclaim&op=rateclaim"><span class="text">Rate Claim</span></a></li>
                                    <li ><a href="ajk.php?re=setdoc"><span class="text">Document Claim</span></a></li>
                                    <li ><a href="ajk.php?re=medical"><span class="text">Table Medical</span></a></li>
                                    <!--<li ><a href="ajk.php?re=signature"><span class="text">Signature</span></a></li>-->
                                    <li >
                                		<a href="javascript:void(0);" data-toggle="submenu" data-target="#signature" data-parent="#page2">
                                    	<span class="text">Signature</span>
                                    	<span class="arrow"></span>
                                		</a>
                                		<!-- START 2nd Level Menu -->
                                		<ul id="signature" class="submenu collapse ">
                                    		<li ><a href="ajk.php?re=signature&op=signdnbank"><span class="text">Debitnote Bank</span></a></li>
                                    		<li ><a href="ajk.php?re=signature&op=signkwbank"><span class="text">Kwitansi Bank</span></a></li>
                                    		<li ><a href="ajk.php?re=signature&op=signcnbank"><span class="text">Creditnote Bank</span></a></li>
                                		</ul>
                            		</li>
                                    <li ><a href="ajk.php?re=regional"><span class="text">Regional</span></a></li>
                                    <li ><a href="ajk.php?re=fileupload"><span class="text">Format File Upload</span></a></li>
                                </ul>
                            </li>
                            <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#message" data-parent="#page">
                                    <span class="text">Insurance</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="message" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=ins"><span class="text">Company Name</span></a></li>
                                    <li ><a href="ajk.php?re=ins&er=policy"><span class="text">Policy</span></a></li>
                                    <li ><a href="ajk.php?re=insrate"><span class="text">Rate Premium</span></a></li>
                                    <li ><a href="ajk.php?re=insrefund"><span class="text">Rate Refund</span></a></li>
                                    <li ><a href="ajk.php?re=insclaim"><span class="text">Rate Claim</span></a></li>
                                </ul>
                            </li>
                            <li ><a href="ajk.php?re=uaccess"><span class="text">Useraccess</span></a></li>
                        </ul>
                    </li>
                    <li >
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#masterdata" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Data View</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="masterdata" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Setup</li>

							             <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#datAJK" data-parent="#page">
                                    <span class="text">AJK <span class="label label-success">A</span></span></span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="datAJK" class="submenu collapse ">
                            		<li><a href="ajk.php?re=data"><span class="text">Members Declaration</span></a></li>
                                <li><a href="ajk.php?re=data&dt=ApproveIns"><span class="text">Members View Insurance</span></a></li>
                            		<li '.$metHIDDENSubDataView_Edit.'><a href="ajk.php?re=data&dt=edtdata"><span class="text">Edit</span></a></li>
                                </ul>
                            </li>                           
                        </ul>
                    </li>


					<li '.$metHIDDENDebitnote.'>
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#masterinvoice" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Debit Note</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="masterinvoice" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Data</li>
                            <li '.$metHIDDENSubDN_Create.'><a href="ajk.php?re=dn&edn=dninv"><span class="text">Create Invoice</span></a></li>
                            <li><a href="ajk.php?re=dn"><span class="text">Data Debit Note</span></a></li>
                        </ul>
                    </li>
					<li '.$metHIDDENCreditnote.'>
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#masterclaim" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Credit Note</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="masterclaim" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Claim</li>
                            <li '.$metHIDDENSubCN_Create.'>
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#createclaim" data-parent="#page">
                                    <span class="text">Create Claim</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="createclaim" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=cclaim&cc=ncancel"><span class="text">Canceled</span></a></li>
                                    <li ><a href="ajk.php?re=cclaim&cc=ntopup"><span class="text">Refund</span></a></li>
                                    <li ><a href="ajk.php?re=cclaim&cc=nclaim"><span class="text">Claim</span></a></li>
                                </ul>
                            </li>
                            <li '.$metHIDDENSubCN_CreateData.'>
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#datacreditnote" data-parent="#page">
                                    <span class="text">Data Creditnote</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="datacreditnote" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=cclaim&cc=dbatal"><span class="text">Canceled</span></a></li>
                                    <li ><a href="ajk.php?re=cclaim&cc=drefund"><span class="text">Refund</span></a></li>
                                    <li ><a href="ajk.php?re=cclaim"><span class="text">Claim</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
					<li '.$metHIDDENAccounting.'>
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#arm" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Accounting</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="arm" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Claim</li>
                            <li >
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#payment" data-parent="#page">
                                    <span class="text">Payment</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="payment" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=arm&py=members"><span class="text">Member</span></a></li>
                                    <li ><a href="ajk.php?re=arm&py=ins"><span class="text">Insurance</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li >
                        <a href="javascript:void(0);" data-toggle="submenu" data-target="#masterreport" data-parent=".topmenu">
                            <span class="figure"><i class="ico-grid"></i></span>
                            <span class="text">Report</span>
                            <span class="arrow"></span>
                        </a>
                        <ul id="masterreport" class="submenu collapse ">
                            <li class="submenu-header ellipsis">Report</li>
                            <li><a href="ajk.php?re=rpt&ro=spk"><span class="text">SPK</span></a></li>
                            <li '.$metHIDDENReportMember.'><a href="ajk.php?re=rpt"><span class="text">Members</span></a></li>
                            <li '.$metHIDDENReportDebitnote.'><a href="ajk.php?re=rpt&ro=rptdebitnote"><span class="text">Debit Note</span></a></li>
                            <li '.$metHIDDENReportCreditnote.'><a href="ajk.php?re=rpt&ro=rptcreditnote"><span class="text">Credit Note</span></a></li>
                            <li '.$metHIDDENSUMPayment.'><a href="ajk.php?re=rpt&ro=rptpayment"><span class="text">Payment</span></a></li>
                            <li '.$metHIDDENInsurance.'><a href="ajk.php?re=rpt&ro=rptInsurance"><span class="text">Insurance</span></a></li>
							<li '.$metHIDDENReportSummary.'>
                                <a href="javascript:void(0);" data-toggle="submenu" data-target="#paymenta" data-parent="#page">
                                    <span class="text">Summary</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul id="paymenta" class="submenu collapse ">
                                    <li ><a href="ajk.php?re=summary&sum=outstanding"><span class="text">Outstanding</span></a></li>
                                    <li ><a href="ajk.php?re=summary&sum=claim"><span class="text">Claim</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="ajk.php?re=gpsbios"><span class="figure"><i class="ico-globe"></i></span><span class="text">Maps</span></a></li>
                    <li><a href="ajk.php?re=email"><span class="figure"><i class="ico-mail"></i></span><span class="text">Test Mail</span></a></li>
				</ul>

<!--                <h5 class="heading">Summary</h5>
                <div class="wrapper">
                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#00B1E1">1,5,3,2,4,5,3,3,2,4,5,3</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Server uptime</h5>
                            <small class="semibold">1876 days</small>
                        </div>
                    </div>

                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#91C854">2,5,3,6,4,2,4,7,8,9,7,6</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Disk usage</h5>
                            <small class="semibold">83.1%</small>
                        </div>
                    </div>

                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#ED5466">5,1,3,7,4,3,7,8,6,5,3,2</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Daily visitors</h5>
                            <small class="semibold">56.5%</small>
                        </div>
                    </div>
                </div> DISABLED -->
            </section>
        </aside>';


$_metMenusRight_Disabled = '<aside class="sidebar sidebar-right">
	<div class="offcanvas-container" data-toggle="offcanvas" data-options=\'{"openerClass":"offcanvas-opener", "closerClass":"offcanvas-closer"}\'>
		<div class="offcanvas-wrapper">
	    	<div class="offcanvas-left">
	        	<div class="header pl0 pr0">
	            	<ul class="list-table nm">
	                	<li style="width:50px;height:34px;" class="text-center">
	                    <a href="javascript:void(0);" class="text-default offcanvas-closer"><i class="ico-arrow-left6 fsize16"></i></a>
	                    </li>
	                    <li class="text-center"><h5 class="semibold nm">Settings</h5></li>
	                    <li style="width:50px;height:34px;" class="text-center">
	                    <a href="javascript:void(0);" class="text-default"><i class="ico-info22 fsize16"></i></a>
	                    </li>
	                </ul>
	            </div>
	            <!-- Content -->
	            <div class="content slimscroll">
	            <h5 class="heading">News Feed</h5>
	            <ul class="topmenu">
	            <li>
	            <a href="javascript:void(0);">
	            	<span class="figure"><i class="ico-plus"></i></span>
	                <span class="text">Add &amp; Manage Source</span>
	                <span class="arrow"></span>
	            </a>
	            </li>
	            <li>
	            <a href="javascript:void(0);">
	            	<span class="figure"><i class="ico-google-plus"></i></span>
	                <span class="text">Google Reader</span>
	                <span class="arrow"></span>
	            </a>
	            </li>
	            <li>
	            <a href="javascript:void(0);">
	                <span class="figure"><i class="ico-twitter2"></i></span>
	                <span class="text">Twitter Source</span>
	            	<span class="arrow"></span>
	            </a>
	            </li>
	            </ul>

	            <h5 class="heading">Friends</h5>
	            <ul class="topmenu">
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-search22"></i></span>
	                <span class="text">Find Friends</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-user-plus2"></i></span>
	                <span class="text">Add Friends</span>
	                <span class="arrow"></span>
	            	</a>
				</li>
	            </ul>

	            <h5 class="heading">Account</h5>
	            <ul class="topmenu">
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-user2"></i></span>
	                <span class="text">Edit Account</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-envelop"></i></span>
	                <span class="text">Manage Subscription</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-location6"></i></span>
	                <span class="text">Location Service</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            <li><a href="javascript:void(0);">
	                <span class="figure"><i class="ico-switch"></i></span>
	                <span class="text">Logout</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            <li><a href="javascript:void(0);" class="text-danger">
	                <span class="figure"><i class="ico-minus-circle2"></i></span>
	                <span class="text">Deactivate</span>
	                <span class="arrow"></span>
	                </a>
	            </li>
	            </ul>
	        </div>
	    </div>
	    <div class="offcanvas-content">
	    	<div class="content slimscroll">
	    		<div class="panel nm">
	            	<div class="thumbnail">
	                	<div class="media">
	                    	<div class="indicator"><span class="spinner"></span></div>
	                        <img data-toggle="unveil" src="templates/{template_name}/image/background/400x250/placeholder.jpg" data-src="templates/{template_name}/image/background/400x250/background3.jpg" alt="Cover" width="100%">
	                        </div>
	                    </div>
	                </div>
	                <div class="panel-body text-center" style="margin-top:-55px;z-index:11">
	                    <img class="img-circle mb5" src="templates/{template_name}/image/avatar/avatar7.jpg" alt="" width="75">
	                    <h5 class="bold mt0 mb5">Erich Reyes</h5>
	                    <p>Administrator</p>
	                    <button type="button" class="btn btn-primary offcanvas-opener offcanvas-open-ltr"><i class="ico-settings"></i> Settings</button>
	                </div>
					<!-- START contact -->
	                <div class="media-list media-list-contact">
	                    <h5 class="heading pa15 pb0">Family</h5>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar1.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Autumn Barker</span>
	                            <span class="media-meta ellipsis">Malaysia</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar2.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Giselle Horn</span>
	                            <span class="media-meta ellipsis">Bolivia</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar.png" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-danger mr5"></span> Austin Shields</span>
	                            <span class="media-meta ellipsis">Timor-Leste</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar.png" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-danger mr5"></span> Caryn Gibson</span>
	                            <span class="media-meta ellipsis">Libya</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar3.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Nash Evans</span>
	                            <span class="media-meta ellipsis">Honduras</span>
	                        </span>
	                    </a>

	                    <h5 class="heading pa15 pb0">Friends</h5>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar4.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Josiah Johnson</span>
	                            <span class="media-meta ellipsis">Belgium</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar.png" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Philip Hewitt</span>
	                            <span class="media-meta ellipsis">Bahrain</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar5.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Wilma Hunt</span>
	                            <span class="media-meta ellipsis">Dominica</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar6.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Noah Gill</span>
	                            <span class="media-meta ellipsis">Guatemala</span>
	                        </span>
	                    </a>

	                    <h5 class="heading pa15 pb0">Others</h5>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar8.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> David Fisher</span>
	                            <span class="media-meta ellipsis">French Guiana</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar9.jpg" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Samantha Avery</span>
	                            <span class="media-meta ellipsis">Jersey</span>
	                        </span>
	                    </a>
	                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
	                        <span class="media-object pull-left">
	                            <img src="templates/{template_name}/image/avatar/avatar.png" class="img-circle" alt="">
	                        </span>
	                        <span class="media-body">
	                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Madaline Medina</span>
	                            <span class="media-meta ellipsis">Finland</span>
	                        </span>
	                    </a>
	                </div>
	                            <!--/ END contact -->
	                        </div>
	                        <!--/ Content -->
	                    </div>
	                    <!--/ Offcanvas Content -->

	                    <!-- Offcanvas Right -->
	                    <div class="offcanvas-right">
	                        <!-- Header -->
	                        <div class="header pl0 pr0">
	                            <ul class="list-table nm">
	                                <li style="width:50px;height:34px;" class="text-center">
	                                    <a href="javascript:void(0);" class="text-default offcanvas-closer"><i class="ico-arrow-left6 fsize16"></i></a>
	                                </li>
	                                <li class="text-center">
	                                    <h5 class="semibold nm">Autumn Barker</h5>
	                                </li>
	                                <li style="width:50px;height:34px;" class="text-center">
	                                    <a href="javascript:void(0);" class="text-default"><i class="ico-info22 fsize16"></i></a>
	                                </li>
	                            </ul>
	                        </div>
	                        <!--/ Header -->

	                        <!-- Footer -->
	                        <div class="footer">
	                            <div class="has-icon">
	                                <input type="text" class="form-control" placeholder="Type message...">
	                                <i class="ico-paper-plane form-control-icon"></i>
	                            </div>
	                        </div>
	                        <!--/ Footer -->

	                        <!-- Content -->
	                        <div class="content slimscroll">
	                            <!-- START chat -->
	                            <ul class="media-list media-list-bubble">
	                            <li class="media media-right">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar7.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">eros non enim commodo hendrerit.</p>
	                                    <span class="clearfix"></span>
	                                    <p class="media-text">Suspendisse dui.</p>
	                                    <span class="clearfix"></span>
	                                    <p class="media-text">eu nulla at</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Sun, Mar 02</p>
	                                </div>
	                            </li>
	                            <li class="media">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar6.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat.</p>
	                                    <span class="clearfix"></span>
	                                    <p class="media-text">faucibus ut, nulla. Cras eu tellus</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Tue, Oct 01</p>
	                                </div>
	                            </li>
	                            <li class="media media-right">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar7.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">Duis a mi fringilla mi lacinia mattis. Integer</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Fri, Sep 27</p>
	                                </div>
	                            </li>
	                            <li class="media">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar6.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">Praesent interdum ligula eu enim. Etiam imperdiet dictum magna.</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Wed, Aug 28</p>
	                                </div>
	                            </li>
	                            <li class="media media-right">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar7.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna.</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Sat, Sep 27</p>
	                                </div>
	                            </li>
	                            <li class="media">
	                                <a href="javascript:void(0);" class="media-object">
	                                    <img src="templates/{template_name}/image/avatar/avatar6.jpg" class="img-circle" alt="">
	                                </a>
	                                <div class="media-body">
	                                    <p class="media-text">Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac</p>
	                                    <span class="clearfix"></span>
	                                    <p class="media-text">Nam porttitor scelerisque neque</p>
	                                    <!-- meta -->
	                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
	                                    <p class="media-meta">Sun, Feb 22</p>
	                                </div>
	                            </li>
	                        </ul>
	                            <!--/ END chat -->
	                        </div>
	                        <!--/ Content -->
	                    </div>
	                    <!--/ Offcanvas Right -->
	                </div>
	                <!--/ END Wrapper -->
	            </div>
	            <!--/ END Offcanvas -->
	        </aside>
	        <!--/ END Template Sidebar (right) -->';

	$_metfooter = '        <!-- START Template Footer -->
        <footer id="footer">
            <!-- START container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- copyright -->
                        <p class="nm text-muted">&copy; Copyright 2015. All Rights Reserved.</p>
                        <!--/ copyright -->
                    </div>
                    <div class="col-sm-6 text-right hidden-xs">
                        <a href="javascript:void(0);" class="semibold">Privacy Policy</a>
                        <span class="ml5 mr5">&#8226;</span>
                        <a href="javascript:void(0);" class="semibold">Terms of Service</a>
                    </div>
                </div>
            </div>
            <!--/ END container-fluid -->
        </footer>
        <!--/ END Template Footer -->';

$_headsearching .='<form id="searchform" action="search.html">
                    <input type="text" id="tipue_search_input" class="top-search" placeholder="Search here ..." />
                    <input type="submit" id="tipue_search_button" class="search-btn" value=""/>
                    </form>';
//define("_ER_PDF_", "<span class=\"badge badge-primary btn-success badge-stroke\"><i class=\"fa fa-file-pdf-o\"></i></span>");

//='<link rel="shortcut icon" href="https://optimisticdesigns.herokuapp.com/landerv2/templates/{template_name}/image/favicon.ico">';
$_metico = '<link rel="shortcut icon" href="../myFiles/_photo/'.$metBroker['logo'].'">';
}else{
	//header("location: ajk.php?re=access&opp=SignOut");
}


?>