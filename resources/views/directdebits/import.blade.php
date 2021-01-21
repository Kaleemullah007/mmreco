@extends('layouts/default')

{{-- Page title --}}
@section('title')
Import DD , Advice And FPOut
@parent
@stop

@section('header_right')

@stop

{{-- Page content --}}
@section('content')
@include('notifications')
<div class="row">

    <div class="col-md-8 col-md-offset-2">

        @if ($errors->first('direct_debits_file'))                    
        <p class="alert-danger">
            
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="fa fa-exclamation-circle faa-pulse animated"></i>
                <strong>Error: </strong>
                 {{ $errors->first('direct_debits_file') }}
            </div>
        </p>
        @endif

        <div class="box box-default">
            <div class="box-body">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="" onsubmit="startLoading();">
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
    					<input type="file" name="direct_debits_file[]" id="direct_debits_file" multiple>
    				</div>
                </div>

                <div class="form-group ">
                    <label for="importType" class="col-sm-3 control-label">Import Type</label>
                    <div class="col-sm-5">
                        <select class="form-control" id="importType" name="importType">
                            <option value="">Select Type</option>
                            <option value="dd">Direct Debits</option>
                            <option value="fpout">FP Out</option>
                            <option value="adv">Advice</option>
                        </select>
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
