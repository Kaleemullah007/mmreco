@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@if ($user->id)
		{{ trans('admin/users/table.updateuser') }}
		{{ $user->fullName() }}
	@else
		{{ trans('admin/users/table.createuser') }}
	@endif

@parent
@stop

@section('header_right')
<a href="{{ route('users') }}" class="btn btn-primary pull-right">
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


<form class="form-horizontal" method="post" autocomplete="off" id="userForm">
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

					<!-- First Name -->
					<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
							<label class="col-md-3 control-label" for="first_name">{{ trans('general.first_name') }}</label>
							<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($user, 'first_name')) ? ' required' : '' }}">
								<input class="form-control" type="text" name="first_name" id="first_name" value="{{ Input::old('first_name', $user->first_name) }}" />
								{!! $errors->first('first_name', '<span class="alert-msg">:message</span>') !!}
							</div>
					</div>

					<!-- Last Name -->
					<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="last_name">{{ trans('general.last_name') }} </label>
						<div class="col-md-6 required {{  (\App\Helpers\Helper::checkIfRequired($user, 'last_name')) ? ' required' : '' }}">
							<input class="form-control" type="text" name="last_name" id="last_name" value="{{ Input::old('last_name', $user->last_name) }}" />
							{!! $errors->first('last_name', '<span class="alert-msg">:message</span>') !!}
						</div>
					</div>

					<!-- Email -->
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="email">{{ trans('admin/users/table.email') }} </label>
						<div class="col-md-6{{  (\App\Helpers\Helper::checkIfRequired($user, 'email')) ? ' required' : '' }}">
							<input
								class="form-control"
								type="text"
								name="email"
								id="email"
								value="{{ Input::old('email', $user->email) }}"
								{{ ((config('app.lock_passwords') && ($user->id)) ? ' disabled' : '') }}
								autocomplete="off"
								readonly
								onfocus="this.removeAttribute('readonly');"
							>
							@if (config('app.lock_passwords') && ($user->id))
							<p class="help-block">{{ trans('admin/users/table.lock_passwords') }}</p>
							@endif
							{!! $errors->first('email', '<span class="alert-msg">:message</span>') !!}
						</div>
					</div>
					
					<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="username">{{ trans('admin/users/table.username') }}</label>
						<div class="col-md-6 {{  (\App\Helpers\Helper::checkIfRequired($user, 'username')) ? ' required' : '' }}">
							@if ($user->ldap_import!='1')
								<input
									class="form-control"
									type="text"
									name="username"
									id="username"
									value="{{ Input::old('username', $user->username) }}"
									autocomplete="off"
									readonly
									onfocus="this.removeAttribute('readonly');"
									{{ ((config('app.lock_passwords') && ($user->id)) ? ' disabled' : '') }}
								>
								@if (config('app.lock_passwords') && ($user->id))
									<p class="help-block">{{ trans('admin/users/table.lock_passwords') }}</p>
								@endif
							@else
								(Managed via LDAP)
							@endif

							{!! $errors->first('username', '<span class="alert-msg">:message</span>') !!}
						</div>
					</div>

					<!-- Password -->
					<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="password">
							{{ trans('admin/users/table.password') }}
						</label>
						<div class="col-md-5{{  (\App\Helpers\Helper::checkIfRequired($user, 'password')) ? ' required' : '' }}">
							@if ($user->ldap_import!='1')
								<input
									type="password"
									name="password"
									class="form-control"
									id="password"
									value=""
									autocomplete="off"
									readonly
									onfocus="this.removeAttribute('readonly');"
									{{ ((config('app.lock_passwords') && ($user->id)) ? ' disabled' : '') }}
								>
							@else
								(Managed via LDAP)
							@endif
							<span id="generated-password"></span>
							{!! $errors->first('password', '<span class="alert-msg">:message</span>') !!}
						</div>
						<div class="col-md-4">
							@if ($user->ldap_import!='1')
								<a href="#" class="left" id="genPassword">Generate</a>
							@endif
						</div>
					</div>

<!-- address -->
                <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
				    {{ Form::label('address', trans('admin/users/table.address'), array('class' => 'col-md-3 control-label')) }}
				    <div class="col-md-6 ">
				        {{Form::text('address', Input::old('address', $user->address), array('class' => 'form-control')) }}
				        {!! $errors->first('address', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
				    </div>
				</div>
<!-- address2 -->
				<div class="form-group {{ $errors->has('address2') ? ' has-error' : '' }}">
				    {{ Form::label('address2', trans('admin/users/table.address2'), array('class' => 'col-md-3 control-label')) }}
				    <div class="col-md-6">
				        {{Form::text('address2', Input::old('address2', $user->address2), array('class' => 'form-control')) }}
				        {!! $errors->first('address2', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
				    </div>
				</div>
			


					
					<!-- city -->
                   <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
					    {{ Form::label('city', trans('admin/users/table.city'), array('class' => 'col-md-3 control-label')) }}
					    <div class="col-md-6 ">
					    {{Form::text('city', Input::old('city', $user->city), array('class' => 'form-control')) }}
					        {!! $errors->first('city', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
					    </div>
					</div>

				

					<!-- Pin Code -->
					<div class="form-group {{ $errors->has('pin_code') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="pin_code">{{ trans('admin/users/table.zip_code') }}</label>
						<div class="col-md-6 required">
							<input class="form-control" type="text" name="pin_code" id="pin_code" value="{{ Input::old('pin_code', $user->pin_code) }}" />
							{!! $errors->first('pin_code', '<span class="alert-msg">:message</span>') !!}
						</div>
					</div>

					<!-- contact_number -->
					<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
						<label class="col-md-3 control-label" for="phone">{{ trans('admin/users/table.contact_number') }}</label>
						<div class="col-md-6 required">
							<input class="form-control" type="text" name="phone" id="phone" value="{{ Input::old('phone', $user->phone) }}" />
							{!! $errors->first('phone', '<span class="alert-msg">:message</span>') !!}
						</div>
					</div>

				
                    @if($user->id)
					<!-- status -->

					<div class="form-group{!! $errors->has('status') ? ' has-error' : '' !!}">
						<label for="status" class="col-md-3 control-label">{{ trans('admin/users/table.status') }}</label>
						<div class="col-md-6">
							{{ Form::select('status', $status, Input::old('status', $user->status), array('class'=>'select2 form-control', 'style'=>'width:100%', 'id'=>'status')) }}
							
							{!! $errors->first('status', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
						</div>
					</div>
					@endif

				
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
	jQuery.validator.addMethod("dollarsscents", function (value, element) {
        return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
    }, "You must include two decimal places");

    $('#userForm').validate({
        rules: {
           
            phone: {
               number: true
            }, uplifts_t1: {
               // required: true,
                dollarsscents: true
            },
            uplifts_t2: {
                //required: true,
                dollarsscents: true
            },
             email: {
               email: true
            },
        },
        submitHandler: function (form) { // for demo
            //alert('valid form');
            //return false;
           $("form#userForm").submit();
        }
    });

$("form#uploadForm").submit(function(e) {
		e.preventDefault();    
		var formData = new FormData(this);

		$.ajax({
			url: '{{route('users/savedocfile')}}',
			type: 'POST',
			data: formData,
			success: function (data) {
				//console.log(data);
				//alert(data);
				 if(data=="Sorry, file already exists."){
					 alert("Sorry, file already exists");
				 }else{
					 $( "#userForm" ).submit();
					 //alert("file upload successfully");
				 }
				
			},
			error: function(jqXHR, exception) {
				alert(exception);
			},
			cache: false,
			contentType: false,
			processData: false
		});

	});
$(document).ready(function() 
{
	$('#email').on('keyup',function(){

			if(this.value.length > 0){
					$("#email_user").prop("disabled",false);
			$("#email_user_warn").html("");
			} else {
					$("#email_user").prop("disabled",true);
			$("#email_user").prop("checked",false);
			}

	});
	@if($user->id)
		$('#salarydiv').hide();
	@endif
});

$('#rmv_grp').click(function()
{
	$('#groups option:selected').prop("selected", false);
});

$('#salarybtn').click(function()
{
	var v = $(this).val();
	if(v == 1)
	{
		$('#salarydiv').show();
		$(this).val('0');
	}
	else
	{
		$('#salarydiv').hide();
		$(this).val('1');
	}
});
</script>

<script>
$('.datepicker').datepicker({
    "autoclose": true,
    'setDate': 'today',
    // "startDate": '+1d',
});
$('tr.header-row input:radio').click(function() {
	value = $(this).attr('value');
	$(this).parent().parent().siblings().each(function() {
		$(this).find('td input:radio[value='+value+']').prop("checked", true);
	})
});

$('.header-name').click(function() {
	$(this).parent().nextUntil('tr.header-row').slideToggle(500);
})
</script>

<script src="{{ asset('assets/js/pGenerator.jquery.js') }}"></script>

<script>
var sid = [];
$(document).ready(function()
{	

	$('#domain').trigger('change');
	$('.tooltip-base').tooltip({container: 'body'})
	$(".superuser").change(function() {
			var perms = $(this).val();
			if (perms =='1') {
					$("#nonadmin").hide();
			} else {
					$("#nonadmin").show();
			}
	});

	$('#genPassword').pGenerator({
			'bind': 'click',
			'passwordElement': '#password',
			'displayElement': '#generated-password',
			'passwordLength': 16,
			'uppercase': true,
			'lowercase': true,
			'numbers':   true,
			'specialChars': true,
			'onPasswordGenerated': function(generatedPassword) {
		 $('#password_confirm').val($('#password').val());
			}
	});
});

$("#two_factor_reset").click(function(){
		$("#two_factor_resetrow").removeClass('success');
		$("#two_factor_resetrow").removeClass('danger');
		$("#two_factor_resetstatus").html('');
		$("#two_factor_reseticon").html('<i class="fa fa-spinner spin"></i>');
		$.ajax({
				url: '{{ route('api.users.two_factor_reset', ['id'=> $user->id]) }}',
				type: 'POST',
				data: {},
				dataType: 'json',

				success: function (data) {
						$("#two_factor_reseticon").html('');
						$("#two_factor_resetstatus").html('<i class="fa fa-check text-success"></i>' + data.message);
				},

				error: function (data) {
						$("#two_factor_reseticon").html('');
						$("#two_factor_reseticon").html('<i class="fa fa-exclamation-triangle text-danger"></i>');
						$('#two_factor_resetstatus').text(data.message);
				}


		});
});

$("input:file").change(function ()
{
   var fileName = $(this).val();
});

// $('#domain').change(function () 
// {
// 	var did = $(this).val();
// 	$.ajax({
// 		url: '{{ route('skill/user') }}',
// 		type: 'GET',
// 		data: {did:did, token:'{{csrf_token()}}'},
// 		success: function (data) 
// 		{
// 			console.log(data);
// 			$.each(data, function(key, val)
// 			{				
// 				if(jQuery.inArray( key, sid ) != -1)
// 				{
// 					$('#skill').append('<option value="'+key+'" selected>'+val+'</option>');
// 				}
// 				else
// 				{
// 					$('#skill').append('<option value="'+key+'">'+val+'</option>');
// 				}
				
// 			});
// 			$('#skill').trigger('change');
// 		}
// 	});
// });
</script>
@stop
