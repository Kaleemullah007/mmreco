@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/agencybanking/general.approved_agency_banking') }}
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
					<form id="create-search-form" class="" method="get" action="{{ route('approved') }}" autocomplete="off" role="form" enctype="multipart/form-data">
						{{ csrf_field() }}						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="Start Date" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							 	{!! $errors->first('start_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
							</div>
						
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="End Date" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							 	{!! $errors->first('end_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
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
						name="agencybanking"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.agencybanking.approved.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="abTableDisplay">
						<thead>
							<tr>
							
								<!-- <th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" >{{ trans('table.actions') }}</th> -->
								
								<th data-sortable="true" data-searchable="true" data-field="CashType">{{ trans('admin/agencybanking/table.cash_type') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="BankingId">{{ trans('admin/agencybanking/table.banking_id') }}</th>

								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="File_filedate">GPS FileDate</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="File_filename">GPS FileName</th>

                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/agencybanking/table.settlement_date') }}</th>


								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/agencybanking/table.card_pan') }}</th>
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_productid">{{ trans('admin/agencybanking/table.Card_productid') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_product">{{ trans('admin/agencybanking/table.Card_product') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_programid">{{ trans('admin/agencybanking/table.Card_programid') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_branchcode">{{ trans('admin/agencybanking/table.Card_branchcode') }}</th>		

								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_no" data-class="exportText">{{ trans('admin/agencybanking/table.agency_account_no') }}</th>
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_type">{{ trans('admin/agencybanking/table.AgencyAccount_type') }}</th>		
								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_sortcode">{{ trans('admin/agencybanking/table.agency_account_sort_code') }}</th>						
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_bankacc">{{ trans('admin/agencybanking/table.AgencyAccount_bankacc') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_name">{{ trans('admin/agencybanking/table.AgencyAccount_name') }}</th>	

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="External_sortcode">{{ trans('admin/agencybanking/table.external_sort_code') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="External_bankacc">{{ trans('admin/agencybanking/table.external_bank_acc') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="External_name">{{ trans('admin/agencybanking/table.external_name') }}</th>
								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_direction">{{ trans('admin/agencybanking/table.CashCode_direction') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashType">{{ trans('admin/agencybanking/table.CashCode_CashType') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashGroup">{{ trans('admin/agencybanking/table.CashCode_CashGroup') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashAmt_currency">{{ trans('admin/agencybanking/table.CashAmt_currency') }}</th>	

								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/agencybanking/table.desc') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="CashAmt_value">{{ trans('admin/agencybanking/table.cash_amt_value') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Fee_direction">{{ trans('admin/agencybanking/table.fee_direction') }}</th>
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Fee_value">{{ trans('admin/agencybanking/table.Fee_value') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Fee_currency">{{ trans('admin/agencybanking/table.Fee_currency') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_value">{{ trans('admin/agencybanking/table.BillAmt_value') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_currency">{{ trans('admin/agencybanking/table.BillAmt_currency') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_rate">{{ trans('admin/agencybanking/table.BillAmt_rate') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_value">{{ trans('admin/agencybanking/table.OrigTxnAmt_value') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_currency">{{ trans('admin/agencybanking/table.OrigTxnAmt_currency') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_partial">{{ trans('admin/agencybanking/table.OrigTxnAmt_partial') }}</th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_origItemId">{{ trans('admin/agencybanking/table.OrigTxnAmt_origItemId') }}</th>

								
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="file_name">File Name</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="file_date">File Date</th>

								<th data-class="sowShort" data-searchable="true" data-sortable="false" data-field="reco_flg">Reco Flag</th>
								<th data-searchable="true" data-sortable="true" data-field="reco_date">Reco Date</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="false" data-field="fpoutRecoDate">Fpout RecoDate</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="false" data-field="OutwardAcceptedValue">Batch Value</th>

								

																
								
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
@include ('partials.bootstrap-table', ['exportFile' => 'agency-banking-export', 'search' => true,'filterColumn'=>$filterColumn])
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
	$('document').ready(function(){
$('#table').bootstrapTable('refresh');
});
function resetAllTableData()
{
    $.removeCookie('abTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('abTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('agencybanking/approved') }}";
}

</script>
@stop
