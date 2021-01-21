@extends('layouts/basic')


{{-- Page content --}}
@section('content')
<div class="container" >
    <div class="row">
    	<form role="form" action="{{ url('/login') }}" method="POST" autocomplete="off">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	        <div class="col-md-4 col-md-offset-6" style="margin-top: 12%;">
	            <div class="box login-box" style="margin-top: 25%; border: 3px solid #D13C41;box-shadow: 0px 0px 20px 15px #6b2224;">
	                <div class="box-header">
	                    <h3 class="box-title"> {{ trans('auth/general.login_prompt')  }}</h3>
	                </div>
	                <div class="login-box-body">
		                    <div class="row">
		                        <!-- Notifications -->
		                        @include('notifications')

		                        <div class="col-md-12">
		                            <fieldset>
		                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
		                                    <input class="form-control" placeholder="User Name" name="username" value="{{@$_COOKIE['remember_me_u']}}" type="text" autofocus>
		                                    {!! $errors->first('username', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
		                                </div>
		                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		                                    <input class="form-control" placeholder="{{ trans('admin/users/table.password')  }}" name="password" value="{{@$_COOKIE['remember_me_pass']}}" type="password" autocomplete="off">
		                                    {!! $errors->first('password', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
		                                </div>
		                                <div class="checkbox">
		                                    <label>
		                                        <input name="remember-me" type="checkbox" value="1">{{ trans('auth/general.remember_me')  }}
		                                    </label>
		                                </div>
		                            </fieldset>
		                        </div> <!-- end col-md-12 -->

		                    </div> <!-- end row -->
	                </div>
	                <div class="box-footer">
	                    <button class="btn btn-lg btn-primary btn-block">{{ trans('auth/general.login')  }}</button>
	                    <div class="col-md-12 col-sm-12 col-xs-12 text-right" id="forgot">
	                    	<a href="{{ config('app.url') }}/password/reset">{{ trans('auth/general.forgot_password')  }}</a>
	                	</div>
	                </div>
	                
	            </div>
	        </div>
        </form>
    </div>
</div>
@stop
