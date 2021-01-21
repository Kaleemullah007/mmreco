@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/fpout/general.fpout') }}
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
					<form id="create-search-form" class="" method="get" action="{{ route('fpout') }}" autocomplete="off" role="form">
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
						name="fpout"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('api.fpout.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="fpoutTableDisplay">
						<thead>
							<tr>
								
								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/fpout/table.file_date') }}</th>							
								
								<th data-sortable="true" data-searchable="true" data-field="FileID">{{ trans('admin/fpout/table.file_id') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="FPID">{{ trans('admin/fpout/table.fpid') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="OrigCustomerSortCode">{{ trans('admin/fpout/table.orig_customer_sort_code') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="OrigCustomerAccountNumber">{{ trans('admin/fpout/table.orig_customer_account_number') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="BeneficiaryCreditInstitution">{{ trans('admin/fpout/table.beneficiary_credit_institution') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="BeneficiaryCustomerAccountNumber">{{ trans('admin/fpout/table.beneficiary_customer_account_number') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="Amount">{{ trans('admin/fpout/table.amount') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="ProcessedAsynchronously">{{ trans('admin/fpout/table.processed_asynchronously') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="ReferenceInformation">{{ trans('admin/fpout/table.reference_information') }}</th>

								<th data-sortable="true" data-searchable="true" data-field="OrigCustomerAccountName">{{ trans('admin/fpout/table.orig_customer_account_name') }}</th>
								
								<th data-sortable="true" data-searchable="true" data-field="ReportTitle">ReportTitle</th>
								<th data-sortable="true" data-searchable="true" data-field="CorporateID">CorporateID</th>
								<th data-sortable="true" data-searchable="true" data-field="SubmissionID">SubmissionID</th>

								
								<th data-sortable="true" data-searchable="true" data-field="FPSDocumentTitle">FPSDocumentTitle</th>
								<th data-sortable="true" data-searchable="true" data-field="FPSDocumentcreated">FPSDocumentcreated</th>
								<th data-sortable="true" data-searchable="true" data-field="FPSDocumentschemaVersion">FPSDocumentschemaVersion</th>
								<th data-sortable="true" data-searchable="true" data-field="SubmissionStatus">SubmissionStatus</th>
								<th data-sortable="true" data-searchable="true" data-field="Currency">Currency</th>
								<th data-sortable="true" data-searchable="true" data-field="FileStatus">FileStatus</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardAcceptedVolume">OutwardAcceptedVolume</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardAcceptedValue">OutwardAcceptedValue</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardAcceptedValueCur">OutwardAcceptedValueCur</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardRejectedVolume">OutwardRejectedVolume</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardRejectedValue">OutwardRejectedValue</th>
								<th data-sortable="true" data-searchable="true" data-field="OutwardRejectedValueCur">OutwardRejectedValueCur</th>
								<th data-sortable="true" data-searchable="true" data-field="Time">Time</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/fpout/table.created_at') }}</th>

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
@include ('partials.bootstrap-table', ['exportFile' => 'fpout-export', 'search' => true,'filterColumn'=>$filterColumn])
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
    $.removeCookie('fpoutTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('fpoutTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('fpoutTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('fpoutTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('fpoutTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('fpout') }}";
}

</script>
@stop
