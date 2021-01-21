@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/card/general.card_loadunload') }}
@parent
@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
	<div class="col-md-12">		
		<div class="box box-default">

			<div class="box-body">
				<div class="table table-responsive">
					<form id="create-search-form" class="" method="get" action="{{ route('loadunload') }}" autocomplete="off" role="form">
						{{ csrf_field() }}						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>
						
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
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
						name="cardLoadunload"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.card.loadunload.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="cardLoadunloadTableDisplay">
						<thead>
							<tr>
								
								<th data-sortable="true" data-searchable="true" data-field="RecordType">{{ trans('admin/card/table.loadunload.record_type') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="LoadUnloadId">{{ trans('admin/card/table.loadunload.load_unload_id') }}</th>

                                <th class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/card/table.loadunload.desc') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.loadunload.settlement_date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.loadunload.card_pan') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Account_no" data-class="exportText">{{ trans('admin/card/table.loadunload.account_no') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="Amount_direction">{{ trans('admin/card/table.loadunload.amount_direction') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="Amount_value">{{ trans('admin/card/table.loadunload.amount_value') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.loadunload.created_at') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.loadunload.file_date') }}</th>	

								<th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>
								<th data-searchable="true" data-sortable="true" data-field="LocalDate">LocalDate</th>
								<th data-searchable="true" data-sortable="true" data-field="MessageId">MessageId</th>
								<th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>
								<th data-searchable="true" data-sortable="true" data-field="LoadSource">LoadSource</th>
								<th data-searchable="true" data-sortable="true" data-field="LoadType">LoadType</th>
								<th data-searchable="true" data-sortable="true" data-field="VoidedLoadUnloadId">VoidedLoadUnloadId</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_programid">Card_programid</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_branchcode">Card_branchcode</th>
								<th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>
								<th data-searchable="true" data-sortable="true" data-field="Amount_currency">Amount_currency</th>
								
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
@include ('partials.bootstrap-table', ['exportFile' => 'card-loadunload-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('cardLoadunloadTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardLoadunloadTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardLoadunloadTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardLoadunloadTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardLoadunloadTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('card/loadunload') }}";
}

</script>
@stop
