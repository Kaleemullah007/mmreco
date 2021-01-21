@extends('layouts/default')

{{-- Page title --}}
@section('title')
Re-Generate Report
@parent
@stop

@section('header_right')

@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-default">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="" onsubmit="startLoading();">
            <div class="box-body">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
                            {{ Form::label('start_date',"From", array('class' => 'col-md-3 control-label')) }}
                            <div class="input-group col-md-9 ">
                                <input type="text" class="datepicker form-control" data-date-format="yyyy-mm-dd" placeholder="{{ trans('general.select_date') }}" name="start_date" id="start_date" value="{{Input::old('start_date')}}" autocomplete="off">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                            {!! $errors->first('start_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
                            {{ Form::label('end_date',"To", array('class' => 'col-md-3 control-label')) }}
                            <div class="input-group col-md-9 ">
                                
                                <input type="text" class="datepicker form-control" data-date-format="yyyy-mm-dd" placeholder="{{ trans('general.select_date') }}" name="end_date" id="end_date" value="{{Input::old('end_date')}}" autocomplete="off">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                            {!! $errors->first('end_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>') !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div><b>Select Type :</b></div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="col-md-12 ">
                                {{ Form::checkbox('sattelment', 1, Input::old('sattelment'), ['class' => 'chkbox', 'id' => 'sattelment']) }} <label for="sattelment"> Settelement Summary</label>
                        </div>
                        <div class="col-md-12 ">
                                {{ Form::checkbox('autocmpr', 1, Input::old('autocmpr'), ['class' => 'chkbox', 'id' => 'autocmpr']) }} <label for="autocmpr"> Auto Compare</label>
                        </div>
                        <div class="col-md-12 ">
                                {{ Form::checkbox('mainreco', 1, Input::old('mainreco'), ['class' => 'chkbox', 'id' => 'mainreco']) }} <label for="mainreco"> Main Reco</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="box-footer text-right">
              <button type="submit" class="btn btn-default">{{ trans('button.submit') }}</button>
            </div>

        </form>
    </div>
  </div>
  </div>
@section('moar_scripts')
<script>
$(document).ready(function(){

});

</script>
@stop
@stop
