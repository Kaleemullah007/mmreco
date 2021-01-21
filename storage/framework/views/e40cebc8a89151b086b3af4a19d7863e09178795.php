<?php $__env->startSection('title'); ?>
	<?php echo e(trans('admin/bankstmt/general.bankstatement_index')); ?>

##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_right'); ?>
	
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">

	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body">
				<form id="create-search-form" class="" method="get" action="<?php echo e(route('bankstatement')); ?>" autocomplete="off" role="form">

					<div class="col-md-4">
						<?php echo e(Form::select('Bankmaster', $Bankmaster, null, ['id' => 'Bankmaster','class' => 'form-control'])); ?>

					</div>

					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="<?php echo e(trans('general.start_date')); ?>" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="<?php echo e(Input::old('start_date', Input::get('start_date'))); ?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					 	</div>
					</div>

					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="<?php echo e(trans('general.end_date')); ?>" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="<?php echo e(Input::old('end_date', Input::get('end_date'))); ?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					 	</div>				 	
					</div>

					<div class="col-md-4">			
						<label class="control-label margin-top-5">Debit</label>			
						<div class="input-group input-large">
							<input type="number" class="form-control" name="minDebit" id="minDebit" value="<?php echo e(Input::old('minDebit', Input::get('minDebit'))); ?>">
							<span class="input-group-addon">
							to </span>
							<input type="number" class="form-control" name="maxDebit" id="maxDebit" value="<?php echo e(Input::old('maxDebit', Input::get('maxDebit'))); ?>">
						</div>
					</div>

					<div class="col-md-4">
						<label class="control-label margin-top-5">Credit</label>						
						<div class="input-group input-large">
							<input type="number" class="form-control" name="minCredit" id="minCredit" value="<?php echo e(Input::old('minCredit', Input::get('minCredit'))); ?>">
							<span class="input-group-addon">
							to </span>
							<input type="number" class="form-control" name="maxCredit" id="maxCredit" value="<?php echo e(Input::old('maxCredit', Input::get('maxCredit'))); ?>">
						</div>						
					</div>

					<div class="col-md-4">
					 	<div class="margin-top-30">
							<button type="submit" class="btn btn-success"><i class="fa fa-search icon-white"></i> <?php echo e(trans('general.search')); ?></button>
						
	                        <a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="top"><i class="fa fa-refresh"></i></a>
	                    </div>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body">
				<div class="table table-responsive">

					<button class="btn btn-info" type="button" onclick="setFlag();">SetFlag</button>

					<table
						name="bankstatement"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="<?php echo e(route('api.bankstatement.list', array(
						'bank_master_id'=>(Input::get('Bankmaster'))?Input::get('Bankmaster'):'',
						'start_date'=>(Input::get('start_date'))?Input::get('start_date'):'',
						'end_date'=>(Input::get('end_date'))?Input::get('end_date'):'',
						'minDebit'=>(Input::get('minDebit'))?Input::get('minDebit'):'',
						'maxDebit'=>(Input::get('maxDebit'))?Input::get('maxDebit'):'',
						'minCredit'=>(Input::get('minCredit'))?Input::get('minCredit'):'',
						'maxCredit'=>(Input::get('maxCredit'))?Input::get('maxCredit'):''
						))); ?>"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="bankstmtTableDisplay">
						<thead>
							<tr>
								<th data-searchable="false" data-sortable="false" data-field="chkBtn"><input type="checkbox" class="flowcheckall" id="flowcheckall" value="" onclick="checkAll($(this));" /></th>

								<th data-searchable="true" data-sortable="true" data-field="name"><?php echo e(trans('admin/bankstmt/table.bank_master_name')); ?></th>

                                <th data-searchable="true" data-sortable="true" data-field="date"><?php echo e(trans('admin/bankstmt/table.date')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="description"><?php echo e(trans('admin/bankstmt/table.description')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="type"><?php echo e(trans('admin/bankstmt/table.type')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="debit"><?php echo e(trans('admin/bankstmt/table.debit')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="credit"><?php echo e(trans('admin/bankstmt/table.credit')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="bal"><?php echo e(trans('admin/bankstmt/table.bal')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="reco_flg"><?php echo e(trans('admin/bankstmt/table.reco_flg')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="extra_flags"><?php echo e(trans('admin/bankstmt/table.extra_flags')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="reco_date">Sattelment Date</th>
								<th data-searchable="true" data-sortable="false" data-field="bankingType">AB Type</th>
								<th data-searchable="true" data-sortable="false" data-field="bankingPan">PAN</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at"><?php echo e(trans('admin/bankstmt/table.created_at')); ?></th>
								
							</tr>
						</thead>						
					</table>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>


<div class="modal fade" id="setBstFlags">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Set Flag for selected data</h4>
         </div>
         <div class="modal-body">
            <select name="extra_flg" id="extra_flg" class="form-control"> 
            	<?php foreach ($bankstatementExtraFlg as $key => $value) { ?>
					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php } ?> 
			</select>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="yes" onclick="setAllFlag();">Yes</button>
         </div>
      </div>
   </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('moar_scripts'); ?>
<?php echo $__env->make('partials.bootstrap-editable-table', ['exportFile' => 'bank-statement-export', 'search' => true,'filterColumn'=>$filterColumn,'isTableEdit' => true, 'editTableFunction' => 'editBalanceAdj'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
$('document').ready(function(){
	stopLoading();
$('#table').bootstrapTable('refresh');
});

function setFlag()
{
	$('#setBstFlags').modal('show');
}
function checkAll(obj)
{
	if(obj.prop('checked') == true)
	{
		$('input[name="selectchk"]').each(function() {		
			$(this).prop('checked',true);
		});
	}
	else
	{
		$('input[name="selectchk"]').each(function() {		
			$(this).prop('checked',false);
		});
	}
	
}
function resetAllTableData()
{
    $.removeCookie('bankstmtTableDisplay.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
 
    window.location.href="<?php echo e(url('bankstatement/bankstatement')); ?>";
}

function editBalanceAdj()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    });

    $('.extra_flagsEdit').editable({
        
        ajaxOptions: {
                    dataType: 'json'
                },
        source : '<?php echo json_encode($bankstatementExtraFlg); ?>',
        success: function(data) {
          $('.snipe-table').bootstrapTable('refresh');
        },

      });

}

function setAllFlag()
{
	var bstIds = [];
	bstIds.length = 0;

	$('input[name="selectchk"]:checked').each(function() {
		bstIds.push($(this).val());
	});

	if(bstIds.length != 0 && $('#extra_flg').val() != '')
	{
	   	var data1 = {bstIds:bstIds , extra_flg:$('#extra_flg').val() , _token:'<?php echo e(csrf_token()); ?>'};
	   startLoading();
	   	$.ajax({
	       type : 'POST',
	       url : "<?php echo e(config('app.url')); ?>/bankstatement/setbstflag", 

	       data : data1,
	       success :function( data ) 
	       { 
       			stopLoading();
				$('#setBstFlags').modal('hide');
				$('#table').bootstrapTable('refresh');
	       }
	   	}); 
	}
	else
	{
		alert("Plese select record and flag to set");
	}

}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>