@extends('layouts/default')

{{-- Page title --}}
@section('title')
	Compared Transactions
@parent
@stop

@section('header_right')
	
@stop

{{-- Page content --}}
@section('content')
@include('notifications')

<div class="row">

	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body">
				<form id="create-search-form" class="" method="post" action="{{ route('autoComparedTransaction') }}" autocomplete="off" role="form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					 	</div>
					</div>

					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					 	</div>				 	
					</div>


					<div class="col-md-4">
					 	<div class="margin-top-30">
							<button type="submit" class="btn btn-success"><i class="fa fa-search icon-white"></i> {{ trans('general.search') }}</button>
						
	                        <a href="javascript:void(0)" onclick="resetAllTableData();" class="btn btn-danger " data-original-title="Reset Search" data-tooltip="tooltip" data-placement="top">
							<i class="fa fa-refresh"></i></a>
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
					<button class="btn btn-danger" type="button" onclick="deleteWarranty();">UnMatchAll</button>
					<form style="display: initial;" id="exportAll-form" class="" method="post" action="{{ route('autocomparetxn/exportall') }}" autocomplete="off" role="form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="exp_start_date" id="exp_start_date" />
						<input type="hidden" name="exp_end_date" id="exp_end_date" />
					<button class="btn btn-warning" type="button" onclick="exportComparedData();">exportAll</button>
					</form>

					<table
						name="bankstatement"
						data-height="500"
						data-toolbar="#toolbar"
						class="table table-striped snipe-table"
						id="table"
						data-toggle="table"						
						data-url="{{ route('autoComparedTransactionTable', array(
						
						'start_date'=>(Input::get('start_date'))?Input::get('start_date'):'',
						'end_date'=>(Input::get('end_date'))?Input::get('end_date'):''
						
						)) }}"
						data-cookie="true"
						data-click-to-select="true"
						data-cookie-id-table="bankstmtTableDisplay">
						<thead>
							<tr>
	
								<th data-searchable="false" data-sortable="false" data-field="chkBtn"><input type="checkbox" class="flowcheckall" id="flowcheckall" value="" onclick="checkAll($(this));" /></th>

								<th data-searchable="false" data-sortable="false" data-field="action">Action</th>

								<th data-searchable="true" data-sortable="false" data-field="txncmptype">TxnCmp Type</th>
								<th data-searchable="true" data-sortable="true" data-field="name">{{ trans('admin/bankstmt/table.bank_master_name') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="date">{{ trans('admin/bankstmt/table.date') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="description">{{ trans('admin/bankstmt/table.description') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="type">{{ trans('admin/bankstmt/table.type') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="debit">{{ trans('admin/bankstmt/table.debit') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="credit">{{ trans('admin/bankstmt/table.credit') }}</th>

								<th data-searchable="true" data-sortable="true" data-field="bal">{{ trans('admin/bankstmt/table.bal') }}</th>
								<th data-searchable="true" data-sortable="true" data-field="reco_date">Sattelment Date</th>

								<th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/bankstmt/table.created_at') }}</th>
								
							</tr>
						</thead>						
					</table>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
</div>

<div class="modal fade" id="relatedTransactionModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Related Transaction</h4>
			</div>
			<div class="modal-body">
				<div class="box box-default">
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bodered text-center">
			                    <thead>
			                      <tr>
			                        <th>Date</th>
			                        <th>Type</th>
			                        <th>Description</th>
			                        <th>Amount</th>
			                      </tr>
			                    </thead>
		                     	<tbody id="relatedTransactionDataTable">
		                     	</tbody>
							</table>
						</div>
					</div>
				</div>


			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('button.cancel') }}</button>
					
			</div>
		</div><!-- /.modal-content -->
	</div>
</div>

<div class="modal modal-danger fade" id="deleteModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Delete Matched Data</h4>
         </div>
         <div class="modal-body">
            <p>Are you sure you wish to delete Matched record?</p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-outline" id="yes" onclick="UnMatchAll();">Yes</button>
         </div>
      </div>
   </div>
</div>

@stop

@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'bank-statement-export', 'search' => true,'filterColumn'=>$filterColumn])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-editable.css') }}">
<script src="{{asset('assets/js/dropzone/dropzone.min.js')}}"></script>
<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
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
function deleteWarranty()
{
	var Id = $("input[name='selectchk']:checked").val();
	if(Id != undefined)
		$('#deleteModal').modal('show');
}

function changeChkBox()
{
	var chkCnt = 0;
	$('input[name="selectchk"]:checked').each(function() {
		chkCnt = chkCnt + 1;
	});

	if(chkCnt <= 1)
	{
		$("#editWarranty").removeClass("disabled");
	}
	else
	{
		$("#editWarranty").addClass("disabled");
	}
}

function resetAllTableData()
{
    $.removeCookie('bankstmtTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('bankstmtTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('bankstatement/bankstatement') }}";
}

function UnMatchAll()
{
	var deleteId = [];
	deleteId.length = 0;

	$('input[name="selectchk"]:checked').each(function() {
		deleteId.push($(this).val());
	});
	if(deleteId.length != 0)
	{
	   	var data1 = {deleteId:deleteId , _token:'{{csrf_token()}}'};
	   startLoading();
	   	$.ajax({
	       type : 'POST',
	       url : "{{config('app.url') }}/autocomparetxn/deletebulktxn", 

	       data : data1,
	       success :function( data ) 
	       { 
	       	stopLoading();
	           $('#deleteModal').modal('hide');
	           $('#table').bootstrapTable('refresh');
	       }
	   	}); 
	}
}

function fetchRelatedData(bstId)
{
	 $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

 	$.ajax({
	      type: 'POST',
	      url: "{{config('app.url') }}/autocomparetxn/fetchRelatedData", 
	      data: {bstId:bstId},
	      success:function( data ) 
	      { 
      	 	if($.trim(data) != "error")
          	{
          		$('#relatedTransactionDataTable').html(data);
	      		$('#relatedTransactionModal').modal('show');
          	}
	      	
  		  },
          error : function(xhr,status,error)
          {
               
          }
  	});
}

function removeMatchTxn(bstId)
{
	 $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

 	$.ajax({
	      type: 'GET',
	      url: "{{config('app.url') }}/autocomparetxn/unMatchTransaction", 
	      data: {bstId:bstId},
	      success:function( data ) 
	      { 
      	 	if($.trim(data) == "success")
          	{
          		$('#table').bootstrapTable('refresh');
          	}
          	else
          	{
          		alert("something wrong please try again");
          	}
	      	
  		  },
          error : function(xhr,status,error)
          {
               
          }
  	});
}

function exportComparedData()
{
	if($('#start_date').val() != '' && $('#end_date').val() != '')
	{
		$("#exp_start_date").val($('#start_date').val());
		$("#exp_end_date").val($('#end_date').val());
		$("#exportAll-form").submit();

	}
}
</script>
@stop
