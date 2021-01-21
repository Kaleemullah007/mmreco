<?php $__env->startSection('title'); ?>
	<?php echo e(trans('admin/agencybanking/general.declined_agency_banking')); ?>

##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('notifications', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<div class="row">
	<div class="col-md-12">		
		<div class="box box-default">

			<div class="box-body">
				<div class="table table-responsive">
					<form id="create-search-form" class="" method="get" action="<?php echo e(route('declined')); ?>" autocomplete="off" role="form" enctype="multipart/form-data">
						<?php echo e(csrf_field()); ?>

						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="Start Date" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="<?php echo e(Input::old('start_date', Input::get('start_date'))); ?>">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							 	<?php echo $errors->first('start_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>'); ?>

							</div>
						
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="End Date" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="<?php echo e(Input::old('end_date', Input::get('end_date'))); ?>">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							 	<?php echo $errors->first('end_date', '<span class="alert-msg"><i class="fa fa-times"></i> :message</span>'); ?>

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
						name="agencybanking"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="<?php echo e(route('api.agencybanking.declined.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):'')))); ?>"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="abdTableDisplay">
						<thead>
							<tr>
							
								<!-- <th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" ><?php echo e(trans('table.actions')); ?></th> -->
								
								<th data-sortable="true" data-searchable="true" data-field="CashType"><?php echo e(trans('admin/agencybanking/table.cash_type')); ?></th>
	
								<th data-searchable="true" data-sortable="true" data-field="BankingId"><?php echo e(trans('admin/agencybanking/table.banking_id')); ?></th>
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="File_filename">GPS FileName</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="File_filedate">GPS FileDate</th>

                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate"><?php echo e(trans('admin/agencybanking/table.settlement_date')); ?></th>
								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText"><?php echo e(trans('admin/agencybanking/table.card_pan')); ?></th>
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_productid"><?php echo e(trans('admin/agencybanking/table.Card_productid')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_product"><?php echo e(trans('admin/agencybanking/table.Card_product')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_programid"><?php echo e(trans('admin/agencybanking/table.Card_programid')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_branchcode"><?php echo e(trans('admin/agencybanking/table.Card_branchcode')); ?></th>	

								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_no" data-class="exportText"><?php echo e(trans('admin/agencybanking/table.agency_account_no')); ?></th>
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_type"><?php echo e(trans('admin/agencybanking/table.AgencyAccount_type')); ?></th>		
								<th data-searchable="true" data-sortable="true" data-field="AgencyAccount_sortcode"><?php echo e(trans('admin/agencybanking/table.agency_account_sort_code')); ?></th>							
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_bankacc"><?php echo e(trans('admin/agencybanking/table.AgencyAccount_bankacc')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_name"><?php echo e(trans('admin/agencybanking/table.AgencyAccount_name')); ?></th>

								<th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="External_sortcode"><?php echo e(trans('admin/agencybanking/table.external_sort_code')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="External_bankacc"><?php echo e(trans('admin/agencybanking/table.external_bank_acc')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="External_name"><?php echo e(trans('admin/agencybanking/table.external_name')); ?></th>

								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_direction"><?php echo e(trans('admin/agencybanking/table.CashCode_direction')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashType"><?php echo e(trans('admin/agencybanking/table.CashCode_CashType')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashGroup"><?php echo e(trans('admin/agencybanking/table.CashCode_CashGroup')); ?></th>


								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc"><?php echo e(trans('admin/agencybanking/table.desc')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="DeclineReason"><?php echo e(trans('admin/agencybanking/table.decline_reason')); ?></th>

								<th data-searchable="true" data-sortable="true" data-field="CashAmt_value"><?php echo e(trans('admin/agencybanking/table.cash_amt_value')); ?></th>
								<th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashAmt_currency"><?php echo e(trans('admin/agencybanking/table.CashAmt_currency')); ?></th>		

								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="DeclineReason"><?php echo e(trans('admin/agencybanking/table.DeclineReason')); ?></th>

								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_value"><?php echo e(trans('admin/agencybanking/table.OrigTxnAmt_value')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_currency"><?php echo e(trans('admin/agencybanking/table.OrigTxnAmt_currency')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_partial"><?php echo e(trans('admin/agencybanking/table.OrigTxnAmt_partial')); ?></th>								
								<th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_origItemId"><?php echo e(trans('admin/agencybanking/table.OrigTxnAmt_origItemId')); ?></th>


								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="reco_flg">Reco Flag</th>

								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="reco_date">Reco Date</th>

								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="file_name">File Name</th>
								<th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="file_date">File Date</th>

								<th  data-searchable="true" data-sortable="false" data-field="fpoutRecoDate">Fpout RecoDate</th>
								<th  data-searchable="true" data-sortable="false" data-field="OutwardAcceptedValue">Batch Value</th>							
								



								
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
<?php echo $__env->make('partials.bootstrap-table', ['exportFile' => 'agency-banking-export', 'search' => true,'filterColumn'=>$filterColumn], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
$('#table').bootstrapTable('refresh');
});
function resetAllTableData()
{
    $.removeCookie('abdTableDisplay.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('abdTableDisplay.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('abdTableDisplay.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('abdTableDisplay.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
    $.removeCookie('abdTableDisplay.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin' });
 
    window.location.href="<?php echo e(url('agencybanking/declined')); ?>";
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>