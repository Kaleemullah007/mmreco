<?php if($errors->any()): ?>
<div class="col-md-12">
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-exclamation-circle faa-pulse animated"></i>
        <strong>Error: </strong>
         Please check the form below for errors
    </div>
</div>

<?php endif; ?>


<?php if($message = Session::get('status')): ?>
    <div class="col-md-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check faa-pulse animated"></i>
            <strong>Success: </strong>
            <?php echo e($message); ?>

        </div>
    </div>
<?php endif; ?>


<?php if($message = Session::get('success')): ?>
<div class="col-md-12">
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-check faa-pulse animated"></i>
        <strong>Success: </strong>
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>

<?php if($message = Session::get('error')): ?>
<div class="col-md-12">
    <div class="alert alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-exclamation-circle faa-pulse animated"></i>
        <strong>Error: </strong>
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>

<?php if($message = Session::get('warning')): ?>
<div class="col-md-12">
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-warning faa-pulse animated"></i>
        <strong>Warning: </strong>
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>

<?php if($message = Session::get('info')): ?>
<div class="col-md-12">
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-info-circle faa-pulse animated"></i>
        <strong>Info: </strong>
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>
