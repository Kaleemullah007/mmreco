@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/card/general.card_chrgbackrepres') }}
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
					<form id="create-search-form" class="" method="get" action="{{ route('chrgbackrepres') }}" autocomplete="off" role="form">
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
						name="cardChrgbackrepres"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.card.chrgbackrepres.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="cardChrgbackrepresTableDisplay">
						<thead>
							<tr>
								
								<th data-sortable="true" data-searchable="true" data-field="RecordType">{{ trans('admin/card/table.chrgbackrepres.record_type') }}</th>
	
								<th data-searchable="true" data-sortable="true" data-field="ChgbackRepresId">{{ trans('admin/card/table.chrgbackrepres.charge_back_repres_id') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="LocalDate">{{ trans('admin/card/table.chrgbackrepres.local_date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.chrgbackrepres.settlement_date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.chrgbackrepres.card_pan') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="Account_no" data-class="exportText">{{ trans('admin/card/table.chrgbackrepres.account_no') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="TxnCode_direction">{{ trans('admin/card/table.chrgbackrepres.txn_code_direction') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="TxnCode_Type">{{ trans('admin/card/table.chrgbackrepres.txn_code_type') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="TxnAmt_value">{{ trans('admin/card/table.chrgbackrepres.txn_amt_value') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="CashbackAmt_value">{{ trans('admin/card/table.chrgbackrepres.cash_back_amt_value') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="BillAmt_value">{{ trans('admin/card/table.chrgbackrepres.bill_amt_value') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="Fee_direction">{{ trans('admin/card/table.chrgbackrepres.fee_direction') }}</th>
								
								<th data-sortable="true" data-searchable="true" data-field="Fee_value">{{ trans('admin/card/table.chrgbackrepres.fee_value') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.chrgbackrepres.created_at') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.chrgbackrepres.file_date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>
								<th data-searchable="true" data-sortable="true" data-field="ApprCode">ApprCode</th>
								<th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>
								<th data-searchable="true" data-sortable="true" data-field="Schema">Schema</th>
								<th data-searchable="true" data-sortable="true" data-field="Repeat">Repeat</th>
								<th data-searchable="true" data-sortable="true" data-field="ARN">ARN</th>
								<th data-searchable="true" data-sortable="true" data-field="FIID">FIID</th>
								<th data-searchable="true" data-sortable="true" data-field="RIID">RIID</th>
								<th data-searchable="true" data-sortable="true" data-field="ReasonCode">ReasonCode</th>
								<th data-searchable="true" data-sortable="true" data-field="PartialReversal">PartialReversal</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_programid">Card_programid</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_branchcode">Card_branchcode</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>
								<th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnCode_Group">TxnCode_Group</th>
								<th data-searchable="true" data-sortable="true" data-field="TxnAmt_currency">TxnAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="CashbackAmt_currency">CashbackAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="BillAmt_currency">BillAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="BillAmt_rate">BillAmt_rate</th>
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
								<th data-searchable="true" data-sortable="true" data-field="SettlementAmt_value">SettlementAmt_value</th>
								<th data-searchable="true" data-sortable="true" data-field="SettlementAmt_currency">SettlementAmt_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="SettlementAmt_rate">SettlementAmt_rate</th>
								<th data-searchable="true" data-sortable="true" data-field="SettlementAmt_date">SettlementAmt_date</th>
								<th data-searchable="true" data-sortable="true" data-field="Fee_currency">Fee_currency</th>
								<th data-searchable="true" data-sortable="true" data-field="Classification_RCC">Classification_RCC</th>
								<th data-searchable="true" data-sortable="true" data-field="Classification_MCC">Classification_MCC</th>
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
@include ('partials.bootstrap-table', ['exportFile' => 'card-charge-back-repres-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('cardChrgbackrepresTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardChrgbackrepresTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardChrgbackrepresTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardChrgbackrepresTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardChrgbackrepresTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('card/chrgbackrepres') }}";
}

</script>
@stop
