@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('general.rejectedbacs') }}
@parent
@stop

@section('header_right')
	<a href="{{ route('create/rejectedbacs') }}" class="btn btn-primary pull-right" style="margin-right: 5px;">  {{ trans('general.create') }}</a>
@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body">
				<div class="table table-responsive">
					<div class="col-md-8" style="margin-top:10px;">
						<div class="col-md-1">
	                        <a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="top"><i class="fa fa-refresh"></i></a>
	                    </div>
                	</div>

					<table
						name="rejectedbacs"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"
						data-url="{{ route('api.rejectedbacs.list') }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="rejectedbacsTableDisplay">
						<thead>
							<tr>

								<th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" >{{ trans('table.actions') }}</th>
								
								<th data-sortable="true" data-searchable="true" data-field="Date">{{ trans('admin/rejectedbacs/table.date') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="Token">{{ trans('admin/rejectedbacs/table.token') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Sort_Code">{{ trans('admin/rejectedbacs/table.sort_code') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Account">{{ trans('admin/rejectedbacs/table.account') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Txn_Amt">{{ trans('admin/rejectedbacs/table.txn_amt') }}</th>
								
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
@include ('partials.bootstrap-table', ['exportFile' => 'rejected-bacs-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('rejectedbacsTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('rejectedbacsTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('rejectedbacsTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('rejectedbacsTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('rejectedbacsTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('rejectedbacs') }}";
}
</script>
@stop
