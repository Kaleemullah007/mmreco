<?php $__env->startSection('title'); ?>
Re-Generate Auto Compare
##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_right'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-default">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="" onsubmit="startLoading();">
            <div class="box-body">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group <?php echo e($errors->has('start_date') ? ' has-error' : ''); ?>">
                            <?php echo e(Form::label('start_date',"Date", array('class' => 'col-md-3 control-label'))); ?>

                            <div class="input-group col-md-9 ">
                                <input type="text" class="datepicker form-control" data-date-format="yyyy-mm-dd" placeholder="<?php echo e(trans('general.select_date')); ?>" name="start_date" id="start_date" value="<?php echo e(Input::old('start_date')); ?>" autocomplete="off">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                            <?php echo $errors->first('start_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>'); ?>

                        </div>
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