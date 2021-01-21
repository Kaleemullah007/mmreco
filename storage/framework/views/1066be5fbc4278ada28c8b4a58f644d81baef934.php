<?php $__env->startSection('content'); ?>
<p><?php echo e(trans('mail.hello')); ?> ,</p>

<p>Please find attachment for Bank Transaction UnKnownColumn. </p>

<p><?php echo e(trans('mail.best_regards')); ?></p>

<p>MMReco</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails/layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>