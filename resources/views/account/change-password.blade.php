@extends('layouts/default')

{{-- Page title --}}
@section('title')
Change your Password
@stop

@section('header_right')
<a href="{{ route('home') }}" class="btn btn-primary pull-right">
  {{ trans('general.back') }}</a>
@stop

{{-- Account page content --}}
@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="box box-default">

            <div class="box-body">
                <form method="post" action="" class="form-horizontal" autocomplete="off">
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <!-- Old Password -->
                    <!-- <div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
                        <label for="old_password" class="col-md-3 control-label">Old Password
                        <i class='fa fa-asterisk'></i>
                        </label>
                        <div class="col-md-5">
                            <input class="form-control" type="password" name="old_password" id="old_password" {{ (config('app.lock_passwords') ? ' disabled' : '') }}>
                            {!! $errors->first('old_password', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                        </div>
                    </div> -->

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-3 control-label">New Password
                        <i class='fa fa-asterisk'></i></label>
                        <div class="col-md-5">
                            <input class="form-control" type="password" name="password" id="password" {{ (config('app.lock_passwords') ? ' disabled' : '') }}>
                            {!! $errors->first('password', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('password_confirm') ? ' has-error' : '' }}">
                        <label for="password_confirm" class="col-md-3 control-label">Confirm Password
                        <i class='fa fa-asterisk'></i>
                        </label>
                        <div class="col-md-5">
                            <input class="form-control" type="password" name="password_confirm" id="password_confirm"  {{ (config('app.lock_passwords') ? ' disabled' : '') }}>
                            {!! $errors->first('password_confirm', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                            @if (config('app.lock_passwords'))
                                <p class="help-block">{{ trans('admin/users/table.lock_passwords') }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Form actions -->
                    <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                        <div class="col-md-7">
                            <a class="btn btn-link" href="{{ route('home') }}">{{ trans('button.cancel') }}</a>
                            <button type="submit" class="btn btn-success" {{ ((config('app.lock_passwords') && ($user->id)) ? ' disabled' : '') }}><i class="fa fa-check icon-white"></i> {{ trans('general.save') }}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@stop
