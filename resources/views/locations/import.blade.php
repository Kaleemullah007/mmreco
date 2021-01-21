@extends('layouts/default')

{{-- Page title --}}
@section('title')
Import Locations
@parent
@stop

@section('header_right')
<a href="{{ route('locations') }}" class="btn btn-default"> {{ trans('general.back') }}</a>
@stop

{{-- Page content --}}
@section('content')


<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-default">
      <div class="box-body">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

			@if (Session::get('message'))
			<p class="alert-danger">
				You have an error in your CSV file:<br />
				{{ Session::get('message') }}
			</p>
			@endif

			<p>
				Upload a CSV file with one or more locations. The CSV should have the <strong>first</strong> fields as: </p>

        <p><strong>name,city,state,address,address2,zip,parent_id,city type</strong>. </p>

        <p>Any additional fields to the right of those fields will be ignored.
			</p> <a href="{{ config('app.url')."/uploads/demoImport/locations.csv" }}" download> Demo File </a>

           

            <div class="form-group {!! $errors->first('manufacturers_import_csv', 'has-error') }}">
                <label for="first_name" class="col-sm-3 control-label">{{ trans('admin/suppliers/table.suppliercsv') }}</label>
        				<div class="col-sm-5">
        					<input type="file" name="supplier_import_csv" id="supplier_import_csv">
        				</div>
            </div>

            <!-- Has Headers -->
            <div class="form-group">
                <div class="col-sm-2 ">
                </div>
                <div class="col-sm-5">
                    {{ Form::checkbox('has_headers', '1', Input::old('has_headers')) }} This CSV has a header row
                </div>
            </div>


        </div>

    <!-- Form Actions -->
    <div class="box-footer text-right">
      <button type="submit" class="btn btn-default">{{ trans('button.submit') }}</button>
    </div>

</form>
</div></div></div></div>
<script>
$(document).ready(function(){

   
});

</script>
@stop
