@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('general.directdebits') }}
@parent
@stop

@section('header_right')
	<!-- <a href="{{ route('create/directdebit') }}" class="btn btn-primary pull-right" style="margin-right: 5px;">  {{ trans('general.create') }}</a> -->
@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
	<div class="col-md-12">		
		<div class="box box-default">

			<div class="box-body">
				<div class="table table-responsive">
					<form id="create-search-form" class="" method="get" action="{{ route('directdebits') }}" autocomplete="off" role="form">
						{{ csrf_field() }}						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/fpout/table.file_start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>
						
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/fpout/table.file_end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>

							<div class="col-md-2">
							 	<div class=" text-right">
								<button type="submit" class="btn btn-success"><i class="fa fa-search icon-white"></i> {{ trans('general.search') }}</button>
								</div>
							</div>

							<div class="col-md-1">
								<a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="right"><i class="fa fa-refresh"></i></a>
							</div>

						</div>
					</form>


					<table
						name="directdebits"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"
						data-url="{{ route('api.directdebits.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="ddTableDisplay">
						<thead>
							<tr>
							
								<!-- <th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" >{{ trans('table.actions') }}</th> -->
								
								<th data-sortable="true" data-searchable="true" data-field="Processing_Date">{{ trans('admin/directdebits/table.processing_date') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="Due_Date">{{ trans('admin/directdebits/table.due_date') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="SUN">{{ trans('admin/directdebits/table.sun') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Sun_Name">{{ trans('admin/directdebits/table.sun_name') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Trans_Code">{{ trans('admin/directdebits/table.trans_code') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="DReference">{{ trans('admin/directdebits/table.d_reference') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="diban">{{ trans('admin/directdebits/table.diban') }}</th>	

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="status">{{ trans('admin/directdebits/table.status') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="amount">{{ trans('admin/directdebits/table.amount') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Token_Number">{{ trans('admin/directdebits/table.token_number') }}</th>
								
							</tr>
						</thead>						
					</table>
				
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->

	</div>
</div>

@stop


@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'direct-debits-by-gps-export', 'search' => true,'filterColumn'=>$filterColumn])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-editable.css') }}">
<script src="{{asset('assets/js/dropzone/dropzone.min.js')}}"></script>
<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
<style type="text/css">
	#addimg{
		max-height: calc(100vh - 210px);
    	overflow-y: auto;
	}
</style>
<script>

function resetAllTableData()
{
    $.removeCookie('ddTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ddTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ddTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ddTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ddTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('directdebits') }}";
}
</script>
@stop
