<?php $__env->startSection('title'); ?>
<?php echo e(trans('general.dashboard')); ?>

##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/morris.css')); ?>">

<div class="row">

      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users.view')): ?>
       <div class="col-lg-3 col-xs-6 col-md-3" style="height: 150px;">
        <!-- small box -->
        <div class="small-box bg-light-blue">
          <div class="inner">
            <h3><?php echo e(number_format(\App\Models\User::employeeCount())); ?></h3>
            <p><?php echo e(trans('general.total_workers')); ?></p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
                <a href="<?php echo e(route('users')); ?>" class="small-box-footer"><?php echo e(trans('general.moreinfo')); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <?php endif; ?>

</div>


<!-- recent activity -->
<?php $__env->startSection('moar_scripts'); ?>


    <script src="<?php echo e(asset('assets/js/bootstrap-table.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/extensions/mobile/bootstrap-table-mobile.js')); ?>"></script>
    <script type="text/javascript">
        //$('#table').bootstrapTable({
        //     classes: 'table table-responsive table-no-bordered',
        //     undefinedText: '',
        //     iconsPrefix: 'fa',
        //     showRefresh: false,
        //     search: false,
        //     pagination: false,
        //     sidePagination: 'server',
        //     sortable: false,
        //     showMultiSort: false,
        //     cookie: false,
        //     mobileResponsive: true,
        // });

    </script>
<?php $__env->stopSection(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>