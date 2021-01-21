@extends('layouts/default')

{{-- Page title --}}
@section('title')
Rejected BACS
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
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="" onsubmit="">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        		@if (Session::get('message'))                    
        		<p class="alert-danger">
        			{{ trans('admin/directdebits/general.csv_error') }}:<br />
        			{{ Session::get('message') }}
        		</p>
        		@endif
                               
                <div class="form-group ">
                    <label for="direct_debits_file" class="col-sm-3 control-label">Upload File</label>
    				<div class="col-sm-5">
    					<input type="file" name="files" id="files">
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
