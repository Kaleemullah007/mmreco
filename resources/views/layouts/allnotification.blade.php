@extends('layouts/default')

{{-- Page title --}}
@section('title')
 View All Notifications
@stop

{{-- Right header --}}
@section('header_right')
    <a href="{{ url()->previous() }}" class="btn btn-primary pull-right">
    {{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')


<div class="row">
  	<div class="col-md-6 col-md-offset-3">
	    <div class="box box-default">
	       	<div class="box-body">
	         	<div class="navbar-custom-menu">
                  	<ul class="nav" id="notify1">
                     	<!-- Notifications: style can be found in dropdown.less -->
			          	<li class="dropdown notifications-menu">
			            	<h3 class=" text-aqua">
			              		<i class="fa fa-bell-o"></i>
			              		<span> You have {{count($result) + count($result1)}} notifications </span>
			            	</h3>
			            </li>
			            <!-- For Vendor Bill -->
	            		@foreach($result as $key=>$val)
	            		<?php 
	            			$status = $val['pstatus'];
							$status1;
							if($status == 'InProgress' || $status == 'HOLD')
							{
								$status1 = 'InProgress,HOLD';
							}
							else if($status == 'Completed')
							{
								$status1 = 'Completed';
							}
							else if($status == 'Executed')
							{
								$status1 = 'Executed';
							}
							else if($status == 'Active' && $val['service_category'] == 'OnlySupply')
							{
								$status1 = 'Active,OnlySupply';
							}
							else if($status == 'Active' && $val['service_category'] != 'OnlySupply')
							{
								$status1 = 'Active,Others';
							}
	            		?>
		              	<li>
		              		<a href="{{ url('vendorbill/viewAllVendorBill') }}/{{$status1}}/ {{$val['project_id']}}">
		                      	<i class="fa fa-file text-aqua"></i>
		                      	<span> {{$val['project_id']}} - {{$val['project_name']}} - Vendor Bill on {{date('d-m-Y', strtotime($val['invoice_date'])) }}</span>
		                   	</a>
		              	</li>
		              	@endforeach
		              	<!-- For Resource Allocation -->
		              	@foreach($result1 as $key1=>$val1)
		              	<li>
		              		<a href="{{ url('allocate/allocateResources', $val1['id']) }}">
		                      	<i class="fa fa-user text-green"></i>
		                      	<span> {{$val1['projectname']['id']}} - {{$val1['projectname']['project_name']}} - {{@val1['location']['name']}} - {{date('d-m-Y', strtotime($val1['start_date'])) }} to {{date('d-m-Y', strtotime($val1['end_date'])) }}</span>
		                   	</a>
		              	</li>
		              	@endforeach
                  	</ul>
               	</div>
	      	</div>
	    </div>
  	</div>
</div>

@section('moar_scripts')
<style type="text/css">
	#notify1
	{ font-size: 16px; }
	#notify1 li > a > i
    { width: 20px; }
</style>
@stop

@stop
