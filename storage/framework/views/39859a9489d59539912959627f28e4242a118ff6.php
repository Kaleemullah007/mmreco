<?php $__env->startSection('title'); ?>

<?php if(Input::get('status')=='deleted'): ?>
		<?php echo e(trans('general.deleted')); ?>

<?php else: ?>
		
<?php endif; ?>
 <?php echo e(trans('general.users')); ?>



##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_right'); ?>
	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.create')): ?>

		<a href="<?php echo e(route('create/user')); ?>" class="btn btn-primary pull-right" style="margin-right: 5px;">  <?php echo e(trans('general.create')); ?></a>

		<!-- <a href="#" data-toggle="modal" data-target="#imgupload" class="btn btn-primary pull-right" style="margin-right: 5px;">  <?php echo e(trans('admin/users/general.img_upload')); ?></a> -->
	<?php endif; ?>

		<?php if(Input::get('status')=='deleted'): ?>
			<a class="btn btn-default pull-right" href="<?php echo e(URL::to('admin/users')); ?>" style="margin-right: 5px;"><?php echo e(trans('admin/users/table.show_current')); ?></a>
		<?php else: ?>
		
		<?php endif; ?>
	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
		 <!--  <a class="btn btn-default pull-right" href="<?php echo e(URL::to('admin/users/export')); ?>" style="margin-right: 5px;">Export</a> -->
	<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

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
				<?php echo e(Form::open([
						 'method' => 'POST',
						 'route' => ['users/bulkedit'],
						 'class' => 'form-inline' ])); ?>



					<table
						name="users"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"
						data-url="<?php echo e(route('api.users.list', array(''=>e(Input::get('status'))))); ?>"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="userTableDisplay">
						<thead>
							<tr>
							
								<th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" ><?php echo e(trans('table.actions')); ?></th>
										 
							
								
								<th data-sortable="true" data-searchable="true" data-field="name"><?php echo e(trans('admin/users/table.name')); ?></th>

							
												
								
								
								<th data-searchable="true" data-sortable="true" data-field="email"><?php echo e(trans('admin/users/table.email')); ?></th>

                                <th data-searchable="true" data-sortable="true" data-field="address"><?php echo e(trans('admin/users/table.address')); ?></th>
								<th data-searchable="true" data-sortable="true" data-field="address2"><?php echo e(trans('admin/users/table.address2')); ?></th>
								<th data-searchable="true" data-sortable="true" data-field="city"><?php echo e(trans('admin/users/table.city')); ?></th>
								<th data-searchable="true" data-sortable="true" data-field="pin_code"><?php echo e(trans('admin/users/table.pin_code')); ?></th>
								<th data-searchable="true" data-sortable="true" data-field="phone"><?php echo e(trans('admin/users/table.phone')); ?></th>

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="status"><?php echo e(trans('admin/users/table.status')); ?></th>
								
								<th data-sortable="true" data-field="created_at" data-searchable="true" data-visible="false"><?php echo e(trans('general.created_at')); ?></th>
										
							</tr>
						</thead>
						
					</table>

				<?php echo e(Form::close()); ?>

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
           	<h4 class="modal-title"><?php echo e(trans('admin/users/general.img_upload')); ?>?</h4>
        </div>
        <form enctype="multipart/form-data" action="<?php echo e(route('users/saveimgupload')); ?>" method="POST" class="dropzone" id="my-awesome-dropzone">
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
        
        
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary pull-right" id="upload">Upload File</button>
        </div>
        </form>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('moar_scripts'); ?>
<?php echo $__env->make('partials.bootstrap-table', ['exportFile' => 'users-export', 'search' => true,'filterColumn'=>$filterColumn], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-table.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-editable.css')); ?>">
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.min.js')); ?>"></script>
<link href="<?php echo e(asset('assets/js/dropzone/dropzone.min.css')); ?>" type="text/css" rel="stylesheet" />
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
     $.removeCookie('userTableDisplay.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
     $.removeCookie('userTableDisplay.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
 
     window.location.href="<?php echo e(url('admin/users')); ?>";
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>