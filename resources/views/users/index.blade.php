@extends('layouts/default')

{{-- Page title --}}
@section('title')

@if (Input::get('status')=='deleted')
		{{ trans('general.deleted') }}
@else
		
@endif
 {{ trans('general.users') }}


@parent
@stop

@section('header_right')
	@can('users.create')

		<a href="{{ route('create/user') }}" class="btn btn-primary pull-right" style="margin-right: 5px;">  {{ trans('general.create') }}</a>

		<!-- <a href="#" data-toggle="modal" data-target="#imgupload" class="btn btn-primary pull-right" style="margin-right: 5px;">  {{ trans('admin/users/general.img_upload') }}</a> -->
	@endcan

		@if (Input::get('status')=='deleted')
			<a class="btn btn-default pull-right" href="{{ URL::to('admin/users') }}" style="margin-right: 5px;">{{ trans('admin/users/table.show_current') }}</a>
		@else
		
		@endif
	@can('users.view')
		 <!--  <a class="btn btn-default pull-right" href="{{ URL::to('admin/users/export') }}" style="margin-right: 5px;">Export</a> -->
	@endcan

@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success alert-dismissible" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fa fa-check"></i>Image Upload Successful</h5>
        </div>
		<div class="box box-default">

			<div class="box-body">
				<div class="table-responsive">
					<div class="col-md-8" style="margin-top:10px;">
						<div class="col-md-1">
	                        <a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="top"><i class="fa fa-refresh"></i></a>
	                    </div>
                	</div>
				{{ Form::open([
						 'method' => 'POST',
						 'route' => ['users/bulkedit'],
						 'class' => 'form-inline' ]) }}


					<table
						name="users"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"
						data-url="{{ route('api.users.list', array(''=>e(Input::get('status')))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="userTableDisplay">
						<thead>
							<tr>
							
								<th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" >{{ trans('table.actions') }}</th>
										 
							
								
								<th data-sortable="true" data-searchable="true" data-field="name">{{ trans('admin/users/table.name') }}</th>

							
												
								
								
								<th data-searchable="true" data-sortable="true" data-field="email">{{ trans('admin/users/table.email') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="address">{{ trans('admin/users/table.address') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="address2">{{ trans('admin/users/table.address2') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="city">{{ trans('admin/users/table.city') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="pin_code">{{ trans('admin/users/table.pin_code') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="phone">{{ trans('admin/users/table.phone') }}</th>

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="status">{{ trans('admin/users/table.status') }}</th>
								
								<th data-sortable="true" data-field="created_at" data-searchable="true" data-visible="false">{{ trans('general.created_at') }}</th>
										
							</tr>
						</thead>
						{{-- <tfoot>
								 <tr>
										 <td colspan="12">
												 <select name="bulk_actions" class="form-control">
														 <option value="delete">Bulk Delete</option>
												 </select>
												 <button class="btn btn-default" id="bulkEdit" disabled>Go</button>
										 </td>
								 </tr>
						</tfoot> --}}
					</table>

				{{ Form::close() }}
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->

	</div>
</div>

<div class="modal modal-default fade" id="imgupload">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
           	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
           	<span aria-hidden="true">&times;</span></button>
           	<h4 class="modal-title">{{trans('admin/users/general.img_upload')}}?</h4>
        </div>
        <form enctype="multipart/form-data" action="{{route('users/saveimgupload')}}" method="POST" class="dropzone" id="my-awesome-dropzone">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary pull-right" id="upload">Upload File</button>
        </div>
        </form>
    </div>
    </div>
</div>
@stop


@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'users-export', 'search' => true,'filterColumn'=>$filterColumn])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-editable.css') }}">
<script src="{{asset('assets/js/dropzone/dropzone.min.js')}}"></script>
<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
<style type="text/css">
	#addimg{
		max-height: calc(100vh - 210px);
    	overflow-y: auto;
	}
</style>
<script>

Dropzone.options.myAwesomeDropzone = {
	
  	maxFilesize: 20, // Size in MB
  	addRemoveLinks: true,
  	autoProcessQueue : false,
  	parallelUploads: 10000,
    // uploadMultiple: true,
    removedfile: function(file) 
    { 
      	var fileRef;
      	return (fileRef = file.previewElement) != null ? 
		      fileRef.parentNode.removeChild(file.previewElement) : void 0;
    },
	success: function(file, response) {
		    	  console.log(response);
		    	},

	error: function(file, response) {
			    	  console.log(response);
			    },
	init: function() 
	{
	    var myDropzone = this;

	    this.element.querySelector("#upload").addEventListener("click", function(e) 
	    {
	      	e.preventDefault();
	      	e.stopPropagation();
	      	myDropzone.processQueue();
	    });

	    this.on('queuecomplete', function()
	    {
          	setTimeout(function()
          	{
              	myDropzone.removeAllFiles();
              	$('#imgupload').modal('hide');
              	$(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
				    $(".alert-success").slideUp(500);
				});
          	},500);
      	});
	}   	
};


$(function() {
	
	function checkForChecked() {

				var check_checked = $('input.one_required:checked').length;

				if (check_checked > 0) {
						$('#bulkEdit').removeAttr('disabled');
				}
				else {
						$('#bulkEdit').attr('disabled', 'disabled');
				}
		}

		$('table').on('change','input.one_required',checkForChecked);

		$("#checkAll").change(function () {
		$("input:checkbox").prop('checked', $(this).prop("checked"));
		checkForChecked();
	});

});

function resetAllTableData()
{
     $.removeCookie('userTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
     window.location.href="{{ url('admin/users') }}";
}
</script>
@stop
