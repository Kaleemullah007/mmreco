@extends('layouts/default')

{{-- Page title --}}
@section('title')
Notifications Import
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
      <div class="box-body">
        <form class="form-horizontal" name="bstForm" id="bstForm" role="form" method="post" enctype="multipart/form-data" action="" >
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          @if(!empty($fileNameArray))
          @foreach($fileNameArray as $myfile)
          <div class="alert alert-danger alert-dismissible" role="alert">
          File <b>{{$myfile}}</b> is already uploaded
          </div>
          @endforeach
          @endif
        <p><strong>Upload Notifications XML.</strong>. </p><br>

            <!-- <div class="form-group {{ $errors->has('bank_master_id') ? ' has-error' : '' }}">
                <div class="col-md-3 control-label">{{ Form::label('bank_master_id', trans('admin/bankstmt/general.bank_master_id')) }}</div>

            </div> -->

            <div class="form-group {!! $errors->first('user_import_csv', 'has-error') }}">
                <label for="first_name" class="col-sm-3 control-label">Notifications XML</label>
        				<div class="col-sm-5">
        					  <input type="file" name="notification_import_xml[]" id="notification_import_xml" multiple>
        				</div>
                
                <div class="col-sm-5" style="margin-top:10px;">
                     <label><input type="checkbox" value="1" name="overwrite" >&nbsp;&nbsp;Overwrite Existing Files</label>
                 </div>
            </div>


        </div>

    <!-- Form Actions -->
    <div class="box-footer text-right">
      <!-- <button type="submit" class="btn btn-default">{{ trans('button.submit') }}</button> -->
      <button type="submit" class="btn btn-default" >Submit</button>
    </div>

</form>
</div></div></div></div>



@section('moar_scripts')
@stop
@stop
