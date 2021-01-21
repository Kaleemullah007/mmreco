@extends('layouts/default')

{{-- Page title --}}
@section('title')
	{{ trans('admin/card/general.card_baladjust') }}
@parent
@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">
	<div class="col-md-12">		
		<div class="box box-default">

			<div class="box-body">
				<div class="table table-responsive">
					<button class="btn btn-info" type="button" onclick="setFlag();">SetFlag</button>
					<form id="create-search-form" class="" method="get" action="{{ route('baladjust') }}" autocomplete="off" role="form">
						{{ csrf_field() }}						
						<div class="col-md-8" style="margin-top:10px;">
							
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>
						
							<div class="col-md-3">
								<div class="input-group">
									<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							 	</div>
							</div>

							<div class="col-md-2">
							 	<div class=" text-right">
								<button type="submit" class="btn btn-success"><i class="fa fa-search icon-white"></i> {{ trans('general.search') }}</button>
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
						data-url="{{ route('api.card.baladjust.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="cardBalAdjuTableDisplay">
						<thead>
							<tr>
								<th data-searchable="false" data-sortable="false" data-field="chkBtn"><input type="checkbox" class="flowcheckall" id="flowcheckall" value="" onclick="checkAll($(this));" /></th>
								<th data-sortable="true" data-searchable="true" data-field="extra_flags">{{ trans('admin/card/table.baladjust.extra_flg') }}</th>
								<th data-sortable="true" data-searchable="true" data-field="extra_flags_cr_drEdit">Flag CR/DR</th>
								<th data-sortable="true" data-searchable="true" data-field="RecType">{{ trans('admin/card/table.baladjust.rec_type') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="AdjustId">{{ trans('admin/card/table.baladjust.adjust_id') }}</th>
                                <th data-searchable="true" data-sortable="true" data-field="LocalDate">LocalDate</th>
                                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.baladjust.settlement_date') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.baladjust.card_pan') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>							
								<th data-searchable="true" data-sortable="true" data-field="Card_programid">Card_programid</th>							
								<th data-searchable="true" data-sortable="true" data-field="Card_branchcode">Card_branchcode</th>							
								<th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>

								<th data-searchable="true" data-sortable="true" data-field="Account_no" data-class="exportText">{{ trans('admin/card/table.baladjust.account_no') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>		

								<th data-searchable="true" data-sortable="true" data-field="Amount_direction">{{ trans('admin/card/table.baladjust.amount_direction') }}</th>	

								<th data-sortable="true" data-searchable="true" data-field="Amount_value">{{ trans('admin/card/table.baladjust.amount_value') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="Amount_currency">Amount_currency</th>
								
								<th class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/card/table.baladjust.desc') }}</th>

								


								<th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.baladjust.file_date') }}</th>			
								<th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>							
						
								<th data-searchable="true" data-sortable="true" data-field="reco_date">reco_date</th>

								<th data-searchable="true" data-sortable="true" data-field="MessageId">MessageId</th>							
								<th data-searchable="true" data-sortable="true" data-field="VoidedAdjustId">VoidedAdjustId</th>							
								<th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>							
															
								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.baladjust.created_at') }}</th>
															
															
								
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
         	<div class="row" style="margin-bottom: 5px;">
         		<div class="col-md-3">
         			Select Flag
         		</div>
         		<div class="col-md-9">
	         		<select name="extra_flg" id="extra_flg" class="form-control"> 
		            	<?php foreach ($balAdjExtraFlg as $key => $value) { ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?> 
					</select>
         		</div>
         	</div>
            <div class="row">
         		<div class="col-md-3">
         			Select DR|CR
         		</div>
         		<div class="col-md-9">
	         		<select name="extra_flags_cr_dr" id="extra_flags_cr_dr" class="form-control"> 
		            	<?php foreach ($flagDRCR as $key => $value) { ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?> 
					</select>
         		</div>
         	</div>
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="yes" onclick="setAllFlag();">Yes</button>
         </div>
      </div>
   </div>
</div>

@stop


@section('moar_scripts')
@include ('partials.bootstrap-editable-table', ['exportFile' => 'card-bal-adjust-export', 'search' => true,'filterColumn'=>$filterColumn,'isTableEdit' => true, 'editTableFunction' => 'editBalanceAdj'])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-editable.css') }}">
<script src="{{asset('assets/js/dropzone/dropzone.min.js')}}"></script>
<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
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
    $.removeCookie('cardBalAdjuTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardBalAdjuTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardBalAdjuTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardBalAdjuTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('cardBalAdjuTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('card/baladjust') }}";
}

function editBalanceAdj()
{
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $('.extra_flagsEdit').editable({
        
        ajaxOptions: {
                    dataType: 'json'
                },
        source : '<?php echo json_encode($balAdjExtraFlg); ?>',
        success: function(data) {
          $('.snipe-table').bootstrapTable('refresh');
        },

      });

    $('.extra_flags_cr_drEdit').editable({
        
        ajaxOptions: {
                    dataType: 'json'
                },
        source : '<?php echo json_encode($flagDRCR); ?>',
        success: function(data) {
          $('.snipe-table').bootstrapTable('refresh');
        },

      });

}

function setAllFlag()
{
	var adjIds = [];
	adjIds.length = 0;

	$('input[name="selectchk"]:checked').each(function() {
		adjIds.push($(this).val());
	});

	if(adjIds.length != 0 && $('#extra_flg').val() != '' && $('#extra_flags_cr_dr').val() != '')
	{
	   	var data1 = {adjIds:adjIds , extra_flg:$('#extra_flg').val() , extra_flags_cr_dr:$('#extra_flags_cr_dr').val() , _token:'{{csrf_token()}}'};
	   startLoading();
	   	$.ajax({
	       type : 'POST',
	       url : "{{config('app.url') }}/card/setmultiadjflag", 

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
@stop
