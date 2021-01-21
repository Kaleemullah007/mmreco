@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@if ($Directdebits->id)
		{{ trans('admin/users/table.updateuser') }}
		{{ $Directdebits->SUN() }}
	@else
		{{ trans('admin/directdebits/table.createdirectdebit') }}
	@endif
@parent
@stop

@section('header_right')
<a href="{{ route('directdebits') }}" class="btn btn-primary pull-right">
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

		<form class="form-horizontal" method="post" autocomplete="off" id="ddForm">
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
								<div class="form-group {{ $errors->has('Processing_Date') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="Processing_Date">{{ trans('admin/directdebits/table.processing_date') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'Processing_Date')) ? ' required' : '' }}">
										<input class="form-control datepicker" type="text" data-date-format="yyyy-mm-dd" name="Processing_Date" id="Processing_Date" value="{{ Input::old('Processing_Date', $Directdebits->Processing_Date) }}" autocomplete="off" />
										{!! $errors->first('Processing_Date', '<span class="alert-msg">:message</span>') !!}                                  
									</div>
								</div>

								<!-- Due Date -->
								<div class="form-group {{ $errors->has('Due_Date') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="Due_Date">{{ trans('admin/directdebits/table.due_date') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'Due_Date')) ? ' required' : '' }}">
										<input class="form-control datepicker" type="text" data-date-format="yyyy-mm-dd" name="Due_Date" id="Due_Date" value="{{ Input::old('Due_Date', $Directdebits->Due_Date) }}" autocomplete="off" />
										{!! $errors->first('Due_Date', '<span class="alert-msg">:message</span>') !!}                                  
									</div>
								</div>

								<!-- SUN Code -->
								<div class="form-group {{ $errors->has('SUN') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="SUN">{{ trans('admin/directdebits/table.sun') }}</label>
									<div class="col-md-6 ">
										<input class="form-control" type="text" name="SUN" id="SUN" value="{{ Input::old('SUN', $Directdebits->SUN) }}" />
										{!! $errors->first('SUN', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Sun Name -->
								<div class="form-group {{ $errors->has('Sun_Name') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="Sun_Name">{{ trans('admin/directdebits/table.sun_name') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'Sun_Name')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Sun_Name" id="Sun_Name" value="{{ Input::old('Sun_Name', $Directdebits->Sun_Name) }}" />
										{!! $errors->first('Sun_Name', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Trans Code -->
								<div class="form-group {{ $errors->has('Trans_Code') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="Trans_Code">{{ trans('admin/directdebits/table.trans_code') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'Trans_Code')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="Trans_Code" id="Trans_Code" value="{{ Input::old('Trans_Code', $Directdebits->Trans_Code) }}" />
										{!! $errors->first('Trans_Code', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- DReference -->
								<div class="form-group {{ $errors->has('DReference') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="DReference">{{ trans('admin/directdebits/table.d_reference') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'DReference')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="DReference" id="DReference" value="{{ Input::old('DReference', $Directdebits->DReference) }}" />
										{!! $errors->first('DReference', '<span class="alert-msg">:message</span>') !!}
									</div>
								</div>

								<!-- Diban -->
								<div class="form-group {{ $errors->has('diban') ? 'has-error' : '' }}">
									<label class="col-md-3 control-label" for="diban">{{ trans('admin/directdebits/table.diban') }}</label>
									<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($Directdebits, 'diban')) ? ' required' : '' }}">
										<input class="form-control" type="text" name="diban" id="diban" value="{{ Input::old('diban', $Directdebits->diban) }}" />
										{!! $errors->first('diban', '<span class="alert-msg">:message</span>') !!}
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

    $('#ddForm').validate({
        rules: {
            Due_Date: {
                required: true,                
            }
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
