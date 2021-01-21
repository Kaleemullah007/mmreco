@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/agencybanking/general.agency_banking_fee') }}
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
					<form id="create-search-form" class="" method="get" action="{{ route('agencybanking/fee') }}" autocomplete="off" role="form">
						{{ csrf_field() }}						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="Start Date" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>
						
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="End Date" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
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
						name="fee"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.agencybanking.fee.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="abFeeTableDisplay">
						<thead>
							<tr>
								
								<th data-sortable="true" data-searchable="true" data-field="BankingFeeId">{{ trans('admin/agencybanking/table.fee.banking_fee_id') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="AbId">{{ trans('admin/agencybanking/table.fee.ab_id') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/agencybanking/table.fee.settlement_date') }}</th>

								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/agencybanking/table.fee.desc') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/agencybanking/table.fee.card_pan') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_no" data-class="exportText">{{ trans('admin/agencybanking/table.fee.agency_account_no') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_sortcode">{{ trans('admin/agencybanking/table.fee.agency_account_sort_code') }}</th>	

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="AgencyAccount_bankacc">{{ trans('admin/agencybanking/table.fee.agency_account_bank_acc') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_name">{{ trans('admin/agencybanking/table.fee.agency_account_name') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Amt_direction">{{ trans('admin/agencybanking/table.fee.amt_direction') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Amt_value">{{ trans('admin/agencybanking/table.fee.amt_value') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/agencybanking/table.fee.created_at') }}</th>	

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/agencybanking/table.fee.file_date') }}</th>	
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="file_name">File Name</th>				
								
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
@include ('partials.bootstrap-table', ['exportFile' => 'agency-banking-fee-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('abFeeTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abFeeTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abFeeTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abFeeTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abFeeTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('agencybanking/fee') }}";
}

</script>
@stop
