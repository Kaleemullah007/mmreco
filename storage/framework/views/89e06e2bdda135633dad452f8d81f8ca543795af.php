<?php $__env->startSection('content'); ?>
<div class="container" >
    <div class="row">
    	<form role="form" action="<?php echo e(url('/login')); ?>" method="POST" autocomplete="off">
			<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
	        <div class="col-md-4 col-md-offset-6" style="margin-top: 12%;">
	            <div class="box login-box" style="margin-top: 25%; border: 3px solid #D13C41;box-shadow: 0px 0px 20px 15px #6b2224;">
	                <div class="box-header">
	                    <h3 class="box-title"> <?php echo e(trans('auth/general.login_prompt')); ?></h3>
	                </div>
	                <div class="login-box-body">
		                    <div class="row">
		                        <!-- Notifications -->
		                        <?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		                        <div class="col-md-12">
		                            <fieldset>
		                                <div class="form-group<?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
		                                    <input class="form-control" placeholder="User Name" name="username" value="<?php echo e(@$_COOKIE['remember_me_u']); ?>" type="text" autofocus>
		                                    <?php echo $errors->first('username', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>'); ?>

		                                </div>
		                                <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
		                                    <input class="form-control" placeholder="<?php echo e(trans('admin/users/table.password')); ?>" name="password" value="<?php echo e(@$_COOKIE['remember_me_pass']); ?>" type="password" autocomplete="off">
		                                    <?php echo $errors->first('password', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>'); ?>

		                                </div>
		                                <div class="checkbox">
		                                    <label>
		                                        <input name="remember-me" type="checkbox" value="1"><?php echo e(trans('auth/general.remember_me')); ?>

		                                    </label>
		                                </div>
		                            </fieldset>
		                        </div> <!-- end col-md-12 -->

		                    </div> <!-- end row -->
	                </div>
	                <div class="box-footer">
	                    <button class="btn btn-lg btn-primary btn-block"><?php echo e(trans('auth/general.login')); ?></button>
	                    <div class="col-md-12 col-sm-12 col-xs-12 text-right" id="forgot">
	                    	<a href="<?php echo e(config('app.url')); ?>/password/reset"><?php echo e(trans('auth/general.forgot_password')); ?></a>
	                	</div>
	                </div>
	                
	            </div>
	        </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/basic', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>