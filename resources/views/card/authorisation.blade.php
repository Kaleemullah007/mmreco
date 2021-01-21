@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/card/general.card_authorisation') }}
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
					<form id="create-search-form" class="" method="get" action="{{ route('authorisation') }}" autocomplete="off" role="form">
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
						name="cardAuthorisation"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.card.authorisation.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="cardAuthTableDisplay">
						<thead>
							<tr>
								
								<th data-sortable="true" data-searchable="true" data-field="RecType">{{ trans('admin/card/table.authorisation.rec_type') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="AuthId">{{ trans('admin/card/table.authorisation.auth_id') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.authorisation.settlement_date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.authorisation.card_pan') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Account_no" data-class="exportText">{{ trans('admin/card/table.authorisation.account_no') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="TxnCode_direction">{{ trans('admin/card/table.authorisation.txn_code_direction') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="TxnCode_Type">{{ trans('admin/card/table.authorisation.txn_code_type') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="BillAmt_value">{{ trans('admin/card/table.authorisation.bill_amt_value') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="BillAmt_rate">{{ trans('admin/card/table.authorisation.bill_amt_rate') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.authorisation.created_at') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.authorisation.file_date') }}</th>


								<th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>
								<th data-searchable="true" data-sortable="true" data-field="LocalDate">LocalDate</th>
								<th data-searchable="true" data-sortable="true" data-field="ApprCode">ApprCode</th>
								<th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>
								<th data-searchable="true" data-sortable="true" data-field="Schema">Schema</th>
								<th data-searchable="true" data-sortable="true" data-field="ReversalReason">ReversalReason</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_programId">Card_programId</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_branchCode">Card_branchCode</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>
								<th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnCode_Group">TxnCode_Group</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnCode_ProcCode">TxnCode_ProcCode</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnAmt_value">TxnAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnAmt_currency">TxnAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="CashbackAmt_value">CashbackAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="CashbackAmt_currency">CashbackAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="BillAmt_currency">BillAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="BillAmt_clientfxrate">BillAmt_clientfxrate</th>
								<th data-searchable="true" data-sortable="true" data-field="Trace_auditno">Trace_auditno</th>
								<th data-searchable="true" data-sortable="true" data-field="Trace_origauditno">Trace_origauditno</th>
								<th data-searchable="true" data-sortable="true" data-field="Trace_Retrefno">Trace_Retrefno</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_code">Term_code</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_location">Term_location</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_street">Term_street</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_city">Term_city</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_country">Term_country</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_inputcapability">Term_inputcapability</th>
								<th data-searchable="true" data-sortable="true" data-field="Term_authcapability">Term_authcapability</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_cardholderpresent">Txn_cardholderpresent</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_cardpresent">Txn_cardpresent</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_cardinputmethod">Txn_cardinputmethod</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_cardauthmethod">Txn_cardauthmethod</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_cardauthentity">Txn_cardauthentity</th>
								<th data-searchable="true" data-sortable="true" data-field="Txn_TVR">Txn_TVR</th>
								<th data-searchable="true" data-sortable="true" data-field="MsgSource_value">MsgSource_value</th>
								<th data-searchable="true" data-sortable="true" data-field="MsgSource_domesticMaestro">MsgSource_domesticMaestro</th>
								<th data-searchable="true" data-sortable="true" data-field="PaddingAmt_value">PaddingAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="PaddingAmt_currency">PaddingAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="CommissionAmt_value">CommissionAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="CommissionAmt_currency">CommissionAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="Classification_RCC">Classification_RCC</th>
								<th data-searchable="true" data-sortable="true" data-field="Classification_MCC">Classification_MCC</th>
								<th data-searchable="true" data-sortable="true" data-field="Response_approved">Response_approved</th>
								<th data-searchable="true" data-sortable="true" data-field="Response_actioncode">Response_actioncode</th>
								<th data-searchable="true" data-sortable="true" data-field="Response_responsecode">Response_responsecode</th>
								<th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_value">OrigTxnAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_currency">OrigTxnAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_origItemId">OrigTxnAmt_origItemId</th>
								<th data-searchable="true" data-sortable="true" data-field="OrigTxnAmt_partial">OrigTxnAmt_partial</th>
		


								
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
@include ('partials.bootstrap-table', ['exportFile' => 'card-authorisation-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('cardAuthTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardAuthTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardAuthTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardAuthTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardAuthTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('card/authorisation') }}";
}

</script>
@stop
