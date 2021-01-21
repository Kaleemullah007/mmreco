<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      
      <title>
         <?php $__env->startSection('title'); ?>
         <?php echo $__env->yieldSection(); ?>
         MMReco
      </title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.5 -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/css/font-awesome.min.css')); ?>">
      <!-- Select2 -->
      <!-- <link rel="stylesheet" href="<?php echo e(asset('assets/js/plugins/select2/select2.min.css')); ?>"> -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/js/plugins/filterControl/select2.min.css')); ?>">
      <link rel="stylesheet" href="<?php echo e(asset('assets/js/plugins/datepicker/bootstrap-datepicker.css')); ?>">
      <!-- iCheck for checkboxes and radio inputs -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/js/plugins/iCheck/all.css')); ?>">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/css/skins/skin-blue.css')); ?>">
      <!-- bootstrap tables CSS -->
      <link rel="stylesheet" href="<?php echo e(asset('assets/bootstrap-datatables/dataTables.bootstrap.css')); ?>">
      <link rel="stylesheet" href="<?php echo e(asset('assets/bootstrap-datatables/extensions/ColVis/css/buttons.dataTables.min.css')); ?>">
      <link rel="stylesheet" href="<?php echo e(asset(elixir('assets/css/app.css'))); ?>">
      <link rel="stylesheet" href="http://rawgit.com/wenzhixin/bootstrap-table-fixed-columns/master/bootstrap-table-fixed-columns.css">
      <link rel="shortcut icon" type="image/ico" href="<?php echo e(asset('favicon.ico')); ?>">
      <link rel="stylesheet" href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>">
      <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom.css')); ?>">
      <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')); ?>"/>
      <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/bootstrap-datepicker/css/bootstrap-datepicker.css')); ?>"/>
      <style>
        
         .request
         {
         margin-bottom: 2%;
         }
         @media (max-width: 400px) {
         .navbar-left {
         margin: 2px;
         }
         .nav::after {
         clear: none;
         }
         }
         .sowShort{
			overflow-x: hidden;
			max-width: 200px;
		}
      .sowShort-150{
         overflow-x: hidden;
         max-width: 150px;
      }
      .error{
         color: red;
      }
      .content-wrapper{
         /*min-height: 650px !important;*/
      }
      </style>
      <script>
        
		function addCommas(xyz)
		{
			//alert(xyz);
			var xy="";
			xy=parseFloat(xyz).toFixed(2);
			x=xy.toString();
			var afterPoint = '';
			if(x.indexOf('.') > 0)
			   afterPoint = x.substring(x.indexOf('.'),x.length);
			x = Math.floor(x);
			x=x.toString();
			var lastThree = x.substring(x.length-3);
			var otherNumbers = x.substring(0,x.length-3);
			if(otherNumbers != '')
				lastThree = ',' + lastThree;
			var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
			var n = parseInt(res).toFixed(2);
			//var n = Number(res);//.toFixed(2);
			return res;
		}
  
      </script>
     
   </head>
   <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
      <div class="wrapper">
         <header class="main-header">
            <!-- Logo -->
            
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
               	<!-- Sidebar toggle button above the compact sidenav -->
               	<a href="#" style="color: black" class="sidebar-toggle btn btn-white" data-toggle="offcanvas" role="button">
               		<span class="sr-only">Toggle navigation</span>
               	</a>
               	<div class="navbar-custom-menu">
        			<ul class="nav navbar-nav">
			          	
				    </ul>
				</div>
               <ul class="nav navbar-nav navbar-left">
                  <!-- <li><img src="<?php echo e(config('app.url')); ?>/assets/img/logo.png"  width="150px" ></li> -->
                  <li class="left-navblock">
                    
                     
                     <a class="logo no-hover" href="<?php echo e(config('app.url')); ?>" style="padding: 0px 5px 0px 10px;">
                     <img src="<?php echo e(config('app.url')); ?>/images/logo.png"  width="40px" >
                     <!-- <?php echo app('translator')->getFromJson('general.title_of_project'); ?> -->
                     </a>
                  </li>
               </ul>
               <!-- Navbar Right Menu -->
               <div class="navbar-custom-menu">
                  	<ul class="nav navbar-nav">

                     	

                     <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                       
                        <i class="fa fa-user fa-fws"></i>
                    
                        <span class="hidden-xs"><?php echo e(Auth::user()->first_name); ?> <b class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu">
                           <!-- User image -->
                           <!-- <li <?php echo (Request::is('account/profile') ? ' class="active"' : ''); ?>>
                          
                          <a href="<?php echo e(route('profile')); ?>">
                           <i class="fa fa-user fa-fw"></i> <?php echo app('translator')->getFromJson('general.editprofile'); ?>
                           </a>
                           </li>-->
                           <li <?php echo (Request::is('account/changepassword') ? ' class="active"' : ''); ?>>
                           <a href="<?php echo e(route('changepassword')); ?>">
                           <i class="fa fa-lock"></i> <?php echo app('translator')->getFromJson('general.changepassword'); ?>
                           </a>
                           </li>
                           <li class="divider"></li>
                           <li>
                              <a href="<?php echo e(url('/logout')); ?>">
                              <i class="fa fa-sign-out fa-fw"></i>
                              <?php echo app('translator')->getFromJson('general.logout'); ?>
                              </a>
                           </li>
                        </ul>
                    </li>
                    
                  </ul>
               </div>
            </nav>
            <a href="#" style="float:left" class="sidebar-toggle-mobile visible-xs btn" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars"></i>
            </a>
            <!-- Sidebar toggle button-->
         </header>
         <!-- Left side column. contains the logo and sidebar -->
         <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
               <!-- sidebar menu: : style can be found in sidebar.less -->
               <ul class="sidebar-menu">
                  <li <?php echo (\Request::route()->getName()=='home' ? ' class="active"' : ''); ?>>
                  <a href="<?php echo e(route('home')); ?>">
                  <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                  </a>
                  </li>

                  <li <?php echo (\Request::route()->getName()=='regeneratereport' ? ' class="active"' : ''); ?>>
                  <a href="<?php echo e(route('regeneratereport')); ?>">
                  <i class="fa fa-file"></i> <span>ReCalculate Report</span>
                  </a>
                  </li>
               
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
                  <li<?php echo (Request::is('admin/users*') ? ' class="active"' : ''); ?>>
                  <a href="javascript:void(0)">
                  <i class="fa fa-users"></i>
                  <span><?php echo app('translator')->getFromJson('general.people'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <li>
                        <a href="<?php echo e(URL::to('admin/users')); ?>">View</a>
                     </li>
                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.create')): ?>
                      <li>
                        <a href="<?php echo e(URL::to('admin/users/create')); ?>">Create</a>
                     </li> 
                     <?php endif; ?>  
                  </ul>
                  </li>
                  <?php endif; ?>

                  <li <?php echo (Request::is('bankstatement/*') ? ' class="active"' : ''); ?>>
                  <a href="javascript:void(0)">
                  <i class="fa fa-bank"></i>
                  <span><?php echo app('translator')->getFromJson('general.bankstatement'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <li>
                        <a href="<?php echo e(URL::to('bankstatement/bankstatement')); ?>">View</a>
                     </li>
                     <li>
                        <a href="<?php echo e(URL::to('bankstatement/import')); ?>">Import</a>
                     </li>
                  </ul>
                  </li>
                  
                  <li <?php echo (Request::is('bankbalance/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-balance-scale"></i>
                  <span><?php echo app('translator')->getFromJson('general.bankbalance'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <li>
                        <a href="<?php echo e(URL::to('bankbalance/bankbalance')); ?>">View</a>
                     </li>
                     <li>
                        <a href="<?php echo e(URL::to('bankbalance/import')); ?>">Import</a>
                     </li>
                  </ul>
                  </li>

                  <li <?php echo (Request::is('agencybanking/*') ? ' class="active"' : ''); ?>  style="margin: -5px 0px;">
                     <a href="javascript:void(0)">
                     <i class="fa fa-building-o"></i>
                     <span><?php echo app('translator')->getFromJson('general.agencybanking'); ?></span>
                     <i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">                     
                        <li><a href="<?php echo e(URL::to('agencybanking/approved')); ?>"><?php echo app('translator')->getFromJson('general.approved'); ?></a></li>
                        <li><a href="<?php echo e(URL::to('agencybanking/declined')); ?>"><?php echo app('translator')->getFromJson('general.declined'); ?></a></li>
                        <li><a href="<?php echo e(URL::to('agencybanking/fee')); ?>"><?php echo app('translator')->getFromJson('general.fee'); ?></a></li>
                     </ul>
                  </li>

                  <li <?php echo (Request::is('card/*') ? ' class="active"' : ''); ?>  style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-credit-card"></i>
                  <span><?php echo app('translator')->getFromJson('general.card'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                     
                     <li><a href="<?php echo e(URL::to('card/authorisation')); ?>"><?php echo app('translator')->getFromJson('general.card_authorisation'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/baladjust')); ?>"><?php echo app('translator')->getFromJson('general.card_baladjust'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/fee')); ?>"><?php echo app('translator')->getFromJson('general.card_fee'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/financial')); ?>"><?php echo app('translator')->getFromJson('general.card_financial'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/loadunload')); ?>"><?php echo app('translator')->getFromJson('general.card_loadunload'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/chrgbackrepres')); ?>"><?php echo app('translator')->getFromJson('general.card_chrgbackrepres'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('card/event')); ?>"><?php echo app('translator')->getFromJson('general.card_event'); ?></a></li>
                  </ul>
                  </li>

                  <li style="margin: -5px 0px;"style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-credit-card custom"></i>
                  <span>DD, Advice, FPOut</span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <li><a href="<?php echo e(URL::to('directdebits')); ?>">DD View</a></li>
                     <li><a href="<?php echo e(URL::to('advice')); ?>"><?php echo app('translator')->getFromJson('general.advice'); ?> View</a></li>  
                     <li><a href="<?php echo e(URL::to('fpout')); ?>"><?php echo app('translator')->getFromJson('general.fpout'); ?> View</a></li>  
                     <li><a href="<?php echo e(URL::to('directdebits/import')); ?>">Import DD,Advice,FPOut</a></li>
                  </ul>
                  </li>

                  <li <?php echo (Request::is('rejectedbacs/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-close"></i>
                  <span><?php echo app('translator')->getFromJson('general.rejectedbacs'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                     
                     <li><a href="<?php echo e(URL::to('rejectedbacs')); ?>">View</a></li>
                     <li><a href="<?php echo e(URL::to('rejectedbacs/create')); ?>"><?php echo app('translator')->getFromJson('general.create_title'); ?></a></li>
                     <li><a href="<?php echo e(URL::to('rejectedbacs/import')); ?>">Import</a></li>
                  </ul>
                  </li>


                  <li <?php echo (Request::is('settelementsummary/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-adjust"></i>
                  <span><?php echo app('translator')->getFromJson('general.settelementsummary'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                     
                     <li><a href="<?php echo e(URL::to('settelementsummary')); ?>">View</a></li>                     
                     <li><a href="<?php echo e(URL::to('settelementsummary/recalculate')); ?>">Re-Calculate</a></li>                     
                  </ul>
                  </li>

                  <li <?php echo (Request::is('dailybalanceshift/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-calendar"></i>
                  <span><?php echo app('translator')->getFromJson('general.dailybalanceshift'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                     
                     <li><a href="<?php echo e(URL::to('dailybalanceshift')); ?>">View</a></li>                     
                     <li><a href="<?php echo e(URL::to('dailybalanceshift/recalculate')); ?>">Re-Calculate</a></li>                     
                  </ul>
                  </li>

                  <li <?php echo (Request::is('monthlybalanceshift/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-calendar"></i>
                  <span><?php echo app('translator')->getFromJson('general.monthlybalanceshift'); ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">                     
                     <li><a href="<?php echo e(URL::to('monthlybalanceshift')); ?>">View</a></li>                     
                     <li><a href="<?php echo e(URL::to('monthlybalanceshift/recalculate')); ?>">Re-Calculate</a></li>                     
                  </ul>
                  </li>

                  <li <?php echo (Request::is('mainrecodaily/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                     <a href="javascript:void(0)">
                     <i class="fa fa-calendar"></i>
                     <span><?php echo app('translator')->getFromJson('general.mainreco'); ?></span>
                     <i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">                     
                        <li><a href="<?php echo e(URL::to('mainrecodaily')); ?>">View</a></li>                     
                        <li><a href="<?php echo e(URL::to('mainrecodaily/recalculate')); ?>">Re-Calculate</a></li>                     
                     </ul>
                  </li>

                  <li <?php echo (Request::is('banktransactions/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                     <a href="javascript:void(0)">
                     <i class="fa fa-money"></i>
                     <span><?php echo app('translator')->getFromJson('general.banktransactions'); ?></span>
                     <i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">
                        <li>
                           <a href="<?php echo e(URL::to('autocomparetxn/recompare')); ?>">Re-Calculate AutoCompare</a>
                        </li>
                        <li>
                           <a href="<?php echo e(URL::to('manualtransaction/comparetxn')); ?>">Manual Compare Txn</a>
                        </li>
                        <li>
                           <a href="<?php echo e(URL::to('banktransactions/import')); ?>">Import</a>
                        </li>
                     </ul>
                  </li>
                  
                  <li style="margin: -5px 0px;">
                     <a href="javascript:void(0)">
                     <i class="fa fa-money"></i>
                     <span>Compared Transaction</span>
                     <i class="fa fa-angle-left pull-right"></i>
                     </a>
                     <ul class="treeview-menu">
                        <li>
                           <a href="<?php echo e(URL::to('autocomparetxn/autoComparedTransaction')); ?>">Compared Transactions</a>
                        </li>
                     </ul>
                  </li>

                  <li <?php echo (Request::is('bankstatement/*') ? ' class="active"' : ''); ?> style="margin: -5px 0px;">
                  <a href="javascript:void(0)">
                  <i class="fa fa-bell-o"></i>
                  <span>Notifications Details</span>
                  <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                     <li>
                        <a href="<?php echo e(URL::to('notifications/showentries')); ?>">View</a>
                     </li>
                     <li>
                        <a href="<?php echo e(URL::to('notifications/import')); ?>">Import</a>
                     </li>
                  </ul>
                  </li>
                  
               </ul>
            </section>
            <!-- /.sidebar -->
         </aside>
         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <?php if($debug_in_production): ?>
            <div class="row" style="margin-bottom: 0px; background-color: red; color: white; font-size: 15px;">
               <div class="col-md-12" style="margin-bottom: 0px; background-color: red; color: white; padding: 10px 20px 10px 30px; font-size: 16px;">
                  <i class="fa fa-warning fa-3x pull-left"></i> <strong><?php echo e(strtoupper(trans('general.debug_warning'))); ?>:</strong>
                  <?php echo trans('general.debug_warning_text'); ?>

               </div>
            </div>
            <?php endif; ?>
            <!-- Content Header (Page header) -->
            <section class="content-header" style="padding-bottom: 30px;">
               <div class="brdcrum">
                  <?php echo $__env->yieldContent('brdcrum'); ?>
               </div>
               <h1 class="pull-left">
                  <?php echo $__env->yieldContent('title'); ?>
               </h1>
               <div class="pull-right">
                  <?php echo $__env->yieldContent('header_right'); ?>
               </div>
            </section>
            <section class="content">
               <!-- Notifications -->
               <div class="row">
                  <?php if(config('app.lock_passwords')): ?>
                  <div class="col-md-12">
                     <div class="callout callout-info">
                        <?php echo e(trans('general.some_features_disabled')); ?>

                     </div>
                  </div>
                  <?php endif; ?>
                  <!-- <?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> -->
               </div>
               <!-- Content -->
               <?php echo $__env->yieldContent('content'); ?>
            </section>
         </div>
         <!-- /.content-wrapper -->
         <footer class="main-footer">
         </footer>
      </div>
      <!-- ./wrapper -->
      <!-- end main container -->
      <div class="modal  modal-danger fade" id="dataConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel"></h4>
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default  pull-left" data-dismiss="modal">Close</button>
                  <a class="btn btn-outline" id="dataConfirmOK"><?php echo app('translator')->getFromJson('general.yes'); ?></a>
               </div>
            </div>
         </div>
      </div>
      <script src="<?php echo e(asset(elixir('assets/js/all.js'))); ?>"></script>
      <script src="<?php echo e(asset('assets/js/plugins/jqueryblockui/jquery.blockui.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/js/jquery.cookie.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/js/jquery.validate.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/jquery.dataTables.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/bootstrap-datatables/dataTables.bootstrap.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/js/bootstrap-table-editable.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/js/bootstrap-editable.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/dataTables.buttons.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/bootstrap-datatables/extensions/ColVis/js/	buttons.colVis.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/bootstrap-datatables/extensions/ColVis/js/dataTables.colVis.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/buttons.flash.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/jszip.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/pdfmake.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/vfs_fonts.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/buttons.html5.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/datatable/buttons.print.min.js')); ?>"></script>
      <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')); ?>"/>
      <script>
	  $('.datepicker').each(
		function(){
			var val = $(this).val();
			if (val == '0000-00-00'){
				$(this).val("");
				// do stuff with the non-valued element
			}
		});
         $(function () {
           //Initialize Select2 Elements
           var iOS = /iPhone|iPad|iPod/.test(navigator.userAgent)  && !window.MSStream;
           if(!iOS)
           {
            $(".select2").select2();
           }
           //$('.datepicker').datepicker();
            $('.datepicker').datepicker({
              autoclose: true
            });
            $('.report_datepicker').datepicker({
               autoclose: true,
               endDate: "today"
             });
         });
         
         //Flat blue color scheme for iCheck
          $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
          });
      </script>
      <script type="text/javascript">
        $(document).ready(function () 
        {
        	getNotification();
          	imageLogo();
          	$('.sidebar-toggle').on('click',function(){
              imageLogo();
         	});
         
         	function imageLogo()
         	{
	            // if(!$('body').hasClass('sidebar-collapse'))
	          //   {
	                $('#imgLogo').html('<img src="<?php echo e(config('app.url')); ?>/assets/img/logo.png"  width="150px" >');
	          //   }
	         //   else
	        //    {
	         //     $('#imgLogo').html('');
	         //   }
	        }
         
            $('.slideout-menu-toggle').on('click', function(event)
            {
               console.log('clicked');
             	event.preventDefault();
             	// create menu variables
             	var slideoutMenu = $('.slideout-menu');
             	var slideoutMenuWidth = $('.slideout-menu').width();
         
             	// toggle open class
             	slideoutMenu.toggleClass("open");
         
             	// slide menu
             	if (slideoutMenu.hasClass("open")) {
                 slideoutMenu.show();
         	    	slideoutMenu.animate({
         		    	right: "0px"
         	    	});
             	} else {
         	    	slideoutMenu.animate({
         		    	right: -slideoutMenuWidth
         	    	}, "-350px");
                 slideoutMenu.fadeOut();
             	}
             });
        
        });

        function getNotification()
        {
        	var len = 0;
<?php if(Auth::user()->user_role == 'cost' || Auth::user()->user_role == 'hod_pm'): ?>
        	$.ajax(
        	{
        		url: '<?php echo e(route('vendorbill/notifyBill')); ?>',
        		type: 'GET',
        		data: {token:'<?php echo e(csrf_token()); ?>'},
        		success: function(data)
        		{
        			// console.log(data);
        			len = +len + +data.length;
        			$('#total').text(len);
        			$('#header').text('You have '+len+' notifications');
        			$.each(data, function(key, val)
					{
						var status = val['pstatus'];
						var status1;
						if(status == 'InProgress' || status == 'HOLD')
						{
							status1 = 'InProgress,HOLD';
						}
						else if(status == 'Completed')
						{
							status1 = 'Completed';
						}
						else if(status == 'Executed')
						{
							status1 = 'Executed';
						}
						else if(status == 'Active' && val['service_category'] == 'OnlySupply')
						{
							status1 = 'Active,OnlySupply';
						}
						else if(status == 'Active' && val['service_category'] != 'OnlySupply')
						{
							status1 = 'Active,Others';
						}

						var d = new Date(val['invoice_date']);
						var date1 = d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear();
						
						if(key < 5)
						{
							$('#notify').append(
								'<li>'+
				                    '<a href="<?php echo e(url('vendorbill/viewAllVendorBill')); ?>/'+status1+'/'+val['project_id']+'">'+
				                      	'<i class="fa fa-file text-aqua"></i>'+val['project_id']+' - '+val['project_name']+' - Vendor Bill on '+date1+
				                    '</a>'+
				                '</li>'
				            );
						}
						else
						{
							$('#nfooter a').prop('disabled', false);
				        }
					});
        		},
        	});
<?php endif; ?>
        }

         //setInterval(function(){getNotification();}, 30000);
         
        function ckeckAllColumn(obj)
        {
           if($(obj).is(":checked"))
           {
              $(obj).parent().parent().parent().find('input:checkbox').prop('checked', true);
         
              $(obj).parent().parent().parent().find('li').each(function(e){
                  $('.snipe-table').bootstrapTable('showColumn', $(this).find('input').attr('data-field'));
                  
              });
           }
           else
           {
              $(obj).parent().parent().parent().find('input:checkbox').prop('checked', false);
         
              $(obj).parent().parent().parent().find('li').each(function(e){
                $('.snipe-table').bootstrapTable('hideColumn', $(this).find('input').attr('data-field'));
              });
           }
            $('.prCost').find('input').attr("disabled",'disabled');
        }
         
        function startLoading()
        {
              $.blockUI({ 
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#444349', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .6, 
                    color: '#fff' 
                },
                overlayCSS:  { 
                      backgroundColor: '#444349', 
                      opacity:         0.6 
                  },
                message: '<div align="center"><img src="<?php echo e(config('app.url')); ?>/assets/img/loading.gif" alt="Loading" /></div>'
              });
        }

        function stopLoading() {
            //.unblockUI();
             setTimeout($.unblockUI, 20); 
         }
         
         
      </script>
      <?php $__env->startSection('moar_scripts'); ?>
      <?php echo $__env->yieldSection(); ?>
      <?php if((Session::get('topsearch')=='true') || (Request::is('/'))): ?>
      <script>
         $("#tagSearch").focus();
      </script>
      <?php endif; ?>
      <script type="text/javascript">
         $(document).ready(function () {
            
            $("#bst_start_date").datepicker({}).on('changeDate', function(ev){
              $("#bst_end_date").datepicker( "setDate", $(this).val());
            });

            $("#start_date").datepicker({}).on('changeDate', function(ev){
              $("#end_date").datepicker( "setDate", $(this).val());
            });

            $("#txn_start_date").datepicker({}).on('changeDate', function(ev){
              $("#txn_end_date").datepicker( "setDate", $(this).val());
            });

  // setTimeout(function () {
  //    $(".alert-success").hide();
  // }, 3000);
});
      </script>
   </body>
</html>