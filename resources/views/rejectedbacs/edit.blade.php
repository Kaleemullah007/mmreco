@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@if ($rejactedbacs->id)
		{{ trans('admin/rejectedbacs/table.updaterejectedbacs') }}		
	@else
		{{ trans('admin/rejectedbacs/table.createrejectedbacs') }}
	@endif
@parent
@stop

@section('header_right')
<a href="{{ route('rejectedbacs') }}" class="btn btn-primary pull-right">
	{{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')

<style>
		.form-horizontal .control-label {
			padding-top: 0px;
		}

		input[type='text'][disabled], input[disabled], textarea[disabled], input[readonly], textarea[readonly], .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
			background-color: white;
			color: #555555;
			cursor:text;
		}
		table.permissions {
			display:flex;
			flex-direction: column;
		}

		.permissions.table > thead, .permissions.table > tbody {
			margin: 15px;
			margin-top: 0px;
		}
		.permissions.table > tbody+tbody {
			margin: 15px;
		}
		.header-row {
			border-bottom: 1px solid #ccc;
		}

		.header-row h3 {
			margin:0px;
		}
		.permissions-row {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
		}
		.table > tbody > tr > td.permissions-item {
			padding: 1px;
			padding-left: 8px;
		}
		table, tbody {
			border: 1px solid #ccc;
		}
		
		.header-name {
			cursor: pointer;
		}

</style>

<div class="row">
	<div class="col-md-10 col-md-offset-1">

		<form class="form-horizontal" method="post" autocomplete="off" id="rejactedbacsForm">
			<!-- CSRF Token -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab">Information</a></li>
					<!--<li><a href="#tab_2" data-toggle="tab">Permissions</a></li>--->
					 <div class=" text-right" style="padding: 5px;">
						<button type="submit" class="btn btn-success"><i class="fa fa-check icon-white"></i> {{ trans('general.save') }}</button>
					</div>
				</ul>

				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<div class="row">

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Date') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Date">{{ trans('admin/rejectedbacs/table.date') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Date')) ? ' required' : '' }}">
										<input class="form-control datepicker" type="text" data-date-format="yyyy-mm-dd" name="Date" id="Date" value="{{ Input::old('Date', $rejactedbacs->Date) }}" autocomplete="off" />
										{!! $errors->first('Date', '<span class="alert-msg">:message</span>') !!}                                  
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Token') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Token">{{ trans('admin/rejectedbacs/table.token') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Token')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Token" id="Token" value="{{ Input::old('Token', $rejactedbacs->Token) }}" />
										{!! $errors->first('Token', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Sort_Code') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Sort_Code">{{ trans('admin/rejectedbacs/table.sort_code') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Sort_Code')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Sort_Code" id="Sort_Code" value="{{ Input::old('Sort_Code', $rejactedbacs->Sort_Code) }}" />
										{!! $errors->first('Sort_Code', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Account') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Account">{{ trans('admin/rejectedbacs/table.account') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Account')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Account" id="Account" value="{{ Input::old('Account', $rejactedbacs->Account) }}" />
										{!! $errors->first('Account', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Txn_Amt') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Txn_Amt">{{ trans('admin/rejectedbacs/table.txn_amt') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Txn_Amt')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Txn_Amt" id="Txn_Amt" value="{{ Input::old('Txn_Amt', $rejactedbacs->Txn_Amt) }}" />
										{!! $errors->first('Txn_Amt', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Bacs_Return') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Bacs_Return">{{ trans('admin/rejectedbacs/table.bacs_return') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Bacs_Return')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Bacs_Return" id="Bacs_Return" value="{{ Input::old('Bacs_Return', $rejactedbacs->Bacs_Return) }}" />
										{!! $errors->first('Bacs_Return', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Txn_Code') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Txn_Code">{{ trans('admin/rejectedbacs/table.txn_code') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Txn_Code')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Txn_Code" id="Txn_Code" value="{{ Input::old('Txn_Code', $rejactedbacs->Txn_Code) }}" />
										{!! $errors->first('Txn_Code', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Error_Code') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Error_Code">{{ trans('admin/rejectedbacs/table.error_code') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Error_Code')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Error_Code" id="Error_Code" value="{{ Input::old('Error_Code', $rejactedbacs->Error_Code) }}" />
										{!! $errors->first('Error_Code', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('File_Description') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="File_Description">{{ trans('admin/rejectedbacs/table.file_description') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'File_Description')) ? ' required' : '' }}">
										<textarea class="form-control" type="text" name="File_Description" id="File_Description">{{ Input::old('File_Description', $rejactedbacs->File_Description) }}</textarea>
										{!! $errors->first('File_Description', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Failure_Reason') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Failure_Reason">{{ trans('admin/rejectedbacs/table.failure_reason') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Failure_Reason')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Failure_Reason" id="Failure_Reason" value="{{ Input::old('Failure_Reason', $rejactedbacs->Failure_Reason) }}" />
										{!! $errors->first('Failure_Reason', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('BNK_BankAccountNumbersRef') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="BNK_BankAccountNumbersRef">{{ trans('admin/rejectedbacs/table.bnk_bank_account_numbers_ref') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'BNK_BankAccountNumbersRef')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="BNK_BankAccountNumbersRef" id="BNK_BankAccountNumbersRef" value="{{ Input::old('BNK_BankAccountNumbersRef', $rejactedbacs->BNK_BankAccountNumbersRef) }}" />
										{!! $errors->first('BNK_BankAccountNumbersRef', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('BNK_IncomingOutgoingBankFilesRef') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="BNK_IncomingOutgoingBankFilesRef">{{ trans('admin/rejectedbacs/table.bnk_incoming_outgoing_bank_files_ref') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'BNK_IncomingOutgoingBankFilesRef')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="BNK_IncomingOutgoingBankFilesRef" id="BNK_IncomingOutgoingBankFilesRef" value="{{ Input::old('BNK_IncomingOutgoingBankFilesRef', $rejactedbacs->BNK_IncomingOutgoingBankFilesRef) }}" />
										{!! $errors->first('BNK_IncomingOutgoingBankFilesRef', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('PANT') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="PANT">{{ trans('admin/rejectedbacs/table.pant') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'PANT')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="PANT" id="PANT" value="{{ Input::old('PANT', $rejactedbacs->PANT) }}" />
										{!! $errors->first('PANT', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('PublicToken') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="PublicToken">{{ trans('admin/rejectedbacs/table.public_token') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'PublicToken')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="PublicToken" id="PublicToken" value="{{ Input::old('PublicToken', $rejactedbacs->PublicToken) }}" />
										{!! $errors->first('PublicToken', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('rej_bacs_id') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="rej_bacs_id">{{ trans('admin/rejectedbacs/table.rej_bacs_id') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'rej_bacs_id')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="rej_bacs_id" id="rej_bacs_id" value="{{ Input::old('rej_bacs_id', $rejactedbacs->rej_bacs_id) }}" />
										{!! $errors->first('rej_bacs_id', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('TransactionStatus') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="TransactionStatus">{{ trans('admin/rejectedbacs/table.transaction_status') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'TransactionStatus')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="TransactionStatus" id="TransactionStatus" value="{{ Input::old('TransactionStatus', $rejactedbacs->TransactionStatus) }}" />
										{!! $errors->first('TransactionStatus', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('BNKTransID') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="BNKTransID">{{ trans('admin/rejectedbacs/table.bnk_trans_id') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'BNKTransID')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="BNKTransID" id="BNKTransID" value="{{ Input::old('BNKTransID', $rejactedbacs->BNKTransID) }}" />
										{!! $errors->first('BNKTransID', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('DestAccName_BACS') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="DestAccName_BACS">{{ trans('admin/rejectedbacs/table.dest_acc_name_bacs') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'DestAccName_BACS')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="DestAccName_BACS" id="DestAccName_BACS" value="{{ Input::old('DestAccName_BACS', $rejactedbacs->DestAccName_BACS) }}" />
										{!! $errors->first('DestAccName_BACS', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('IssuerID') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="IssuerID">{{ trans('admin/rejectedbacs/table.issuer_id') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'IssuerID')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="IssuerID" id="IssuerID" value="{{ Input::old('IssuerID', $rejactedbacs->IssuerID) }}" />
										{!! $errors->first('IssuerID', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('Institution') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="Institution">{{ trans('admin/rejectedbacs/table.institution') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'Institution')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Institution" id="Institution" value="{{ Input::old('Institution', $rejactedbacs->Institution) }}" />
										{!! $errors->first('Institution', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

							<div class="col-md-12">
								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('ActionCode') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="ActionCode">{{ trans('admin/rejectedbacs/table.action_code') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'ActionCode')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="ActionCode" id="ActionCode" value="{{ Input::old('ActionCode', $rejactedbacs->ActionCode) }}" />
										{!! $errors->first('ActionCode', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Processing Date -->
								<div class="col-md-6 form-group {{ $errors->has('RecordType') ? 'has-error' : '' }}">
									<label class="col-md-5 control-label" for="RecordType">{{ trans('admin/rejectedbacs/table.record_type') }}</label>
									<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($rejactedbacs, 'RecordType')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="RecordType" id="RecordType" value="{{ Input::old('RecordType', $rejactedbacs->RecordType) }}" />
										{!! $errors->first('RecordType', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>
							</div>

						</div>


					</div><!-- /.tab-pane -->
				</div><!-- /.tab-content -->
				<div class="box-footer text-right">
					<button type="submit" class="btn btn-success"><i class="fa fa-check icon-white"></i> {{ trans('general.save') }}</button>
				</div>
			</div><!-- nav-tabs-custom -->
		</form>
	</div>
</div>
@stop
@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'docs-export'])
<script>

    $('#rejactedbacsForm1').validate({
        rules: {
            Date: {
                required: true,                
            },
            Token: {
                required: true,                
            },
            Txn_Amt: {
                required: true,                
            },
        },
        submitHandler: function (form) { // for demo
        	form.submit();
        }
    });


	$(document).ready(function() 
	{
		$('.datepicker').datepicker({
		    "autoclose": true,
		    'setDate': 'today',
		    // "startDate": '+1d',
		});
	});

</script>
<script src="{{ asset('assets/js/pGenerator.jquery.js') }}"></script>

@stop
