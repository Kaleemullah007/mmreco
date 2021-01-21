@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/bankbal/general.bankbal_index') }}
@parent
@stop

@section('header_right')

@stop

{{-- Page content --}}
@section('content')
@include('notifications')
<style type="text/css">
    td.details-control {
        background: url('../images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.details td.details-control {
        background: url('../images/details_close.png') no-repeat center center;
    }
    .dataTable .details {
         background-color: #eee; 
    }
</style>
<div class="row">
	 <div class="col-xs-12">
            	<div class="box">
                <div class="box-header" style="border-bottom: 1px solid #DDD;">
                	<div class="row">
						<div class="input-daterange form-group margin-top-15" id="m_datepicker_5">
							<label class="control-label col-md-2 margin-top-5">Date Range</label>
							<div class="col-md-4">
								<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="d/m/Y">
									<input type="text" class="form-control" name="from" value="<?php echo date('d-m-Y'); ?>">
									<span class="input-group-addon">
									to </span>
									<input type="text" class="form-control" name="to" value="<?php echo date('d-m-Y'); ?>">
								</div>
							</div>
						</div>
					 	<div class="col-md-6">
					 		<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2 margin-top-5">Amount</label>
									<div class="col-md-4">
										<div class="input-group input-large">
											<input type="number" class="form-control" name="min" value="">
											<span class="input-group-addon">
											to </span>
											<input type="number" class="form-control" name="max" value="">
										</div>
									</div>
									<input type="button" class="btn btn-primary" id="sumbit" value="Search">
								</div>
							</div>

					 	<!-- <input type="text" name="min" value="" placeholder=""><span class="input-group-addon">to </span><input type="text" name="max" value="" placeholder="">
					 	
					        <input type="button" class="btn btn-primary" id="sumbit" value="Search"> -->
					    </div>
					</div>
                  <!-- <h3 class="box-title">Data Table With Full Features</h3> -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="bankbalance" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th></th>
                        <th>{{ trans('admin/bankbal/table.id') }}</th>
                        <th>{{ trans('admin/bankbal/table.accno') }}</th>
                        <th>{{ trans('admin/bankbal/table.currcode') }}</th>
                        <th>{{ trans('admin/bankbal/table.acctype') }}</th>
                        <th>{{ trans('admin/bankbal/table.sortcode') }}</th>
                        <th>{{ trans('admin/bankbal/table.bankacc') }}</th>
                        <th>{{ trans('admin/bankbal/table.feeband') }}</th>
                        <th>{{ trans('admin/bankbal/table.finamt') }}</th>
                        <th>{{ trans('admin/bankbal/table.blkamt') }}</th>
                        <th>{{ trans('admin/bankbal/table.amtavl') }}</th>
                        <th>{{ trans('admin/bankbal/table.created_at') }}</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th>{{ trans('admin/bankbal/table.id') }}</th>
                        <th>{{ trans('admin/bankbal/table.accno') }}</th>
                        <th>{{ trans('admin/bankbal/table.currcode') }}</th>
                        <th>{{ trans('admin/bankbal/table.acctype') }}</th>
                        <th>{{ trans('admin/bankbal/table.sortcode') }}</th>
                        <th>{{ trans('admin/bankbal/table.bankacc') }}</th>
                        <th>{{ trans('admin/bankbal/table.feeband') }}</th>
                        <th>{{ trans('admin/bankbal/table.finamt') }}</th>
                        <th>{{ trans('admin/bankbal/table.blkamt') }}</th>
                        <th>{{ trans('admin/bankbal/table.amtavl') }}</th>
                        <th>{{ trans('admin/bankbal/table.created_at') }}</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
           </div>
       
</div>
@section('moar_scripts')
<script>
$(document).ready(function(){
	$('#bankbalance').hide();
	// $('#bankbalance').DataTable({
	// 	  "paging": true,
	// 	  "lengthChange": false,
	// 	  "searching": true,
	// 	  "ordering": true,
	// 	  "info": true,
	// 	  "autoWidth": false
	// });

	$("#m_datepicker_5").datepicker({
        todayHighlight: !0,
        format: 'dd-mm-yyyy',
        autoclose: true,
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });     
	$('#sumbit').click(function(){
		$('#bankbalance').DataTable().destroy();
		$('#bankbalance').show();
		startDate = $("input[name=from]").val();
    	endDate = $("input[name=to]").val();
    	minAmount = $("input[name=min]").val();
    	maxAmount = $("input[name=max]").val();
    	var dt = $('#bankbalance').DataTable({
		  "paging": true,
		  "lengthChange": false,
          "lengthMenu": [10, 25, 50, 75, 100],
		  "searching": true,
		  "ordering": true,
		  "info": true,
		  "autoWidth": false,
		  "serverSide": true,
            "ajax":{
                     "url": "{{ url('bankbalance/bankbalance') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}",startDate:startDate,endDate:endDate,minAmount:minAmount,maxAmount:maxAmount}
                   },
            "columns": [
                {
                    "class":"details-control",
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ""
                },
                { "data": "id" },
                { "data": "accno" },
                { "data": "currcode" },
                { "data": "acctype" },
                { "data": "sortcode" },
                { "data": "bankacc" },
                { "data": "feeband" },
                { "data": "finamt" },
                { "data": "blkamt" },
                { "data": "amtavl" },
                { "data": "created_at" }
            ],
            "order": [[1, 'asc']],
            dom: 'Bfrtip',
	        columnDefs: [
	            {
	                targets: 1,
	                className: 'noVis'
	            }
	        ],
	        buttons: [
	            {
	                extend: 'colvis',
	                columns: ':not(.noVis)'
	            }
	        ]	 	 
            
		});

        var detailRows = [];
        $('#bankbalance').find('tbody').off('click', 'tr td.details-control'); 
        $('#bankbalance tbody').on('click', 'tr td.details-control', function () {

            var tr = $(this).closest('tr');
            var bank_balance_id = $(this).next('td').find('input').val();            
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );
     
            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();
     
                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                row.child( format( row.data() , bank_balance_id ) ).show();
     
                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows.push( tr.attr('id') );
                }
            }
        } );

         // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );
    
	});
    
});

function format ( d , bank_balance_id ) {

    var card = '<tr class="details"><td class="details" colspan="12"><h4><b>&nbsp;Card Details</b></h4><table class="table table-striped table-bordered table-hover " width="100%"><thead><tr><th># No.</th><th>PAN</th><th>VIRTUAL</th><th>PRIMARY CARD</th><th>PROGRAMID</th><th>CUSTCODE</th><th>EXPDATE</th><th>CRDACCNO</th><th>CRDCURRCODE</th><th>PRODUCTID</th></tr></thead><tbody>';

    $.ajax({ 
        type: 'post', 
        url: "{{ url('bankbalance/cardlist') }}",
        data: {  _token: "{{csrf_token()}}",bank_balance_id: bank_balance_id }, 
        dataType: 'json',
        async : false,
        success: function (response) { 
            
            $.each(response.data, function(index, element) {
                card += '<tr><td>'+(index+1)+'</td><td>'+element.pan+'</td><td>'+element.virtual+'</td><td>'+element.primary+'</td><td>'+element.programid+'</td><td>'+element.custcode+'</td><td>'+element.expdate+'</td><td>'+element.crdaccno+'</td><td>'+element.crdcurrcode+'</td><td>'+element.productid+'</td></tr>';
            });
        }
    })

    card += '</tbody></table></td></tr>';
    return card;
}

</script>
@stop
@stop