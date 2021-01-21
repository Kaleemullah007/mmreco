<?php $__env->startSection('title'); ?>
	<?php echo e(trans('admin/advice/general.advice')); ?>

##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">
	<div class="col-md-12">		
		<div class="box box-default">

			<div class="box-body">
				<div class="table table-responsive">
					<form id="create-search-form" class="" method="post" action="" autocomplete="off" role="form">
						<?php echo e(csrf_field()); ?>						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="<?php echo e(trans('general.start_date')); ?>" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="<?php echo e(Input::old('start_date', Input::get('start_date'))); ?>">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>
						
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="<?php echo e(trans('general.end_date')); ?>" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="<?php echo e(Input::old('end_date', Input::get('end_date'))); ?>">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>

							<div class="col-md-2">
							 	<div class=" text-right">
								<button type="submit" class="btn btn-success"><i class="fa fa-search icon-white"></i> <?php echo e(trans('general.search')); ?></button>
								</div>
							</div>

							<div class="col-md-1">
								<a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="right"><i class="fa fa-refresh"></i></a>
							</div>

						</div>
					</form>

					<table
						name="cardBalAdjust"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="<?php echo e(route('api.advice.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):'')))); ?>"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="cardBalAdjuTableDisplay">
						<thead>
							<tr>
								
								<th data-sortable="true" data-searchable="true" data-field="file_date"><?php echo e(trans('admin/advice/table.file_date')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="ab_sort_code"><?php echo e(trans('admin/advice/table.ab_sort_code')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="ab_account_number"><?php echo e(trans('admin/advice/table.ab_account_number')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="code"><?php echo e(trans('admin/advice/table.code')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="ext_bank_sort_code"><?php echo e(trans('admin/advice/table.ext_bank_sort_code')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="ext_bank_acc_number"><?php echo e(trans('admin/advice/table.ext_bank_acc_number')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="amount_in_cent"><?php echo e(trans('admin/advice/table.amount_in_cent')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="actual_amount"><?php echo e(trans('admin/advice/table.actual_amount')); ?></th>
								<th data-sortable="true" data-searchable="true" data-field="ext_name"><?php echo e(trans('admin/advice/table.ext_name')); ?></th>

								
								<th data-sortable="true" data-searchable="true" data-field="C">C</th>
								<th data-sortable="true" data-searchable="true" data-field="A">A</th>
								<th data-sortable="true" data-searchable="true" data-field="ref">ref</th>
								<th data-sortable="true" data-searchable="true" data-field="ab_name">ab_name</th>
								<th data-sortable="true" data-searchable="true" data-field="advice_number">advice_number</th>
								<th data-sortable="true" data-searchable="true" data-field="X">X</th>
								<th data-sortable="true" data-searchable="true" data-field="Y">Y</th>
								<th data-sortable="true" data-searchable="true" data-field="Z">Z</th>
												
								
							</tr>
						</thead>						
					</table>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('moar_scripts'); ?>
<?php echo $__env->make('partials.bootstrap-table', ['exportFile' => 'card-bal-adjust-export', 'search' => true,'filterColumn'=>$filterColumn], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-table.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-editable.css')); ?>">
<script src="<?php echo e(asset('assets/js/dropzone/dropzone.min.js')); ?>"></script>
<link href="<?php echo e(asset('assets/js/dropzone/dropzone.min.css')); ?>" type="text/css" rel="stylesheet" />
<style type="text/css">
 .table-responsive{
    overflow-x : inherit !important;
  }
  
  
	#addimg{
		max-height: calc(100vh - 210px);
    	overflow-y: auto;
	}
</style>
<script>

// function resetAllTableData()
// {
//     $.removeCookie('cardBalAdjuTableDisplay.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
//     $.removeCookie('cardBalAdjuTableDisplay.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
//     $.removeCookie('cardBalAdjuTableDisplay.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
//     $.removeCookie('cardBalAdjuTableDisplay.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
//     $.removeCookie('cardBalAdjuTableDisplay.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
 
//     window.location.href="<?php echo e(url('card/baladjust')); ?>";
// }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>