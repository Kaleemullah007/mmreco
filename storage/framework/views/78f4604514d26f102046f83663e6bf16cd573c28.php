<?php $__env->startSection('title'); ?>
Notifications Import
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_right'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-default">
      <div class="box-body">
        <form class="form-horizontal" name="bstForm" id="bstForm" role="form" method="post" enctype="multipart/form-data" action="" >
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
          <?php if(!empty($fileNameArray)): ?>
          <?php $__currentLoopData = $fileNameArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $myfile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
          File <b><?php echo e($myfile); ?></b> is already uploaded
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        <p><strong>Upload Notifications XML.</strong>. </p><br>

            <!-- <div class="form-group <?php echo e($errors->has('bank_master_id') ? ' has-error' : ''); ?>">
                <div class="col-md-3 control-label"><?php echo e(Form::label('bank_master_id', trans('admin/bankstmt/general.bank_master_id'))); ?></div>

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
      <!-- <button type="submit" class="btn btn-default"><?php echo e(trans('button.submit')); ?></button> -->
      <button type="submit" class="btn btn-default" >Submit</button>
    </div>

</form>
</div></div></div></div>



<?php $__env->startSection('moar_scripts'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>