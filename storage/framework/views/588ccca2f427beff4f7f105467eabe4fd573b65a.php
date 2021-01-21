<?php $__env->startSection('title'); ?>
Import DD , Advice And FPOut
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_right'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div class="row">

    <div class="col-md-8 col-md-offset-2">

        <?php if($errors->first('direct_debits_file')): ?>                    
        <p class="alert-danger">
            
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <i class="fa fa-exclamation-circle faa-pulse animated"></i>
                <strong>Error: </strong>
                 <?php echo e($errors->first('direct_debits_file')); ?>

            </div>
        </p>
        <?php endif; ?>

        <div class="box box-default">
            <div class="box-body">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="" onsubmit="startLoading();">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />

        		<?php if(Session::get('message')): ?>                    
        		<p class="alert-danger">
        			<?php echo e(trans('admin/directdebits/general.csv_error')); ?>:<br />
        			<?php echo e(Session::get('message')); ?>

        		</p>
        		<?php endif; ?>
                               
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
              <button type="submit" class="btn btn-default"><?php echo e(trans('button.submit')); ?></button>
            </div>

                </form>
        </div>
    </div>
</div>

<?php $__env->startSection('moar_scripts'); ?>
<script>
$(document).ready(function(){

});

</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>