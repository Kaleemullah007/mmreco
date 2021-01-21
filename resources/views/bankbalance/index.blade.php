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
            	<div class="box box-default">

                    <div class="box-body">
                        <div class="table table-responsive">
                            <form id="create-search-form" class="" method="post" action="" autocomplete="off" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}   
                                    <div class="input-daterange form-group margin-top-15" id="m_datepicker_5">
                                        <label class="control-label col-md-2 margin-top-5">Date Range</label>
                                        <div class="col-md-4">
                                            <div class="input-group input-large date-picker input-daterange" data-date-format="Y-m-d">
                                                <input type="text" class="form-control" name="from" value="{{ Input::old('from', Input::get('from')) }}">
                                                <span class="input-group-addon">
                                                to </span>
                                                <input type="text" class="form-control" name="to" value="{{ Input::old('to', Input::get('to')) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-md-2 margin-top-5">Amount</label>
                                                <div class="col-md-4">
                                                    <div class="input-group input-large">
                                                        <input type="number" class="form-control" name="min" value="{{ Input::old('min', Input::get('min')) }}">
                                                        <span class="input-group-addon">
                                                        to </span>
                                                        <input type="number" class="form-control" name="max" value="{{ Input::old('max', Input::get('max')) }}">
                                                    </div>
                                                </div>
                                                <input type="submit" class="btn btn-primary" id="sumbit" value="Search">
                                            </div>
                                        </div>

                                    <!-- <input type="text" name="min" value="" placeholder=""><span class="input-group-addon">to </span><input type="text" name="max" value="" placeholder="">
                                    
                                        <input type="button" class="btn btn-primary" id="sumbit" value="Search"> -->
                                    </div>
                            </form>

                            <table
                                name="agencybanking"
                                data-height="500"
                                data-toolbar="#toolbar"
                                class="table table-striped snipe-table"
                                id="table"
                                data-toggle="table"                     
                                data-url="{{ route('bankbalance/bankbalanceList', array('from'=>(Input::get('from'))?Input::get('from'):'','to'=>(Input::get('to')?Input::get('to'):''),'min'=>(Input::get('min')?Input::get('min'):''),'max'=>(Input::get('max')?Input::get('max'):''))) }}"
                                data-cookie="true"
                                data-click-to-select="true"
                                data-cookie-id-table="abTableDisplay">
                                <thead>
                                    <tr>
                                    
                                        <th data-sortable="true" data-searchable="true" data-field="bankbal_date">Balance Date</th>
                                        <th data-sortable="true" data-searchable="true" data-field="accno" data-class="exportText">{{ trans('admin/bankbal/table.accno') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="currcode">{{ trans('admin/bankbal/table.currcode') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="acctype">{{ trans('admin/bankbal/table.acctype') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="sortcode">{{ trans('admin/bankbal/table.sortcode') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="bankacc">{{ trans('admin/bankbal/table.bankacc') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="feeband">{{ trans('admin/bankbal/table.feeband') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="finamt">{{ trans('admin/bankbal/table.finamt') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="blkamt">{{ trans('admin/bankbal/table.blkamt') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="amtavl">{{ trans('admin/bankbal/table.amtavl') }}</th>
                                        <th data-sortable="true" data-searchable="true" data-field="pan" data-class="exportText">PAN</th>
                                        <th data-sortable="true" data-searchable="true" data-field="virtual">VIRTUAL</th>
                                        <th data-sortable="true" data-searchable="true" data-field="primary">PRIMARY</th>
                                        <th data-sortable="true" data-searchable="true" data-field="crdproduct">CARD PRODUCT</th>
                                        <th data-sortable="true" data-searchable="true" data-field="programid">PROGRAMID</th>
                                        <th data-sortable="true" data-searchable="true" data-field="custcode">CUSTCODE</th>
                                        <th data-sortable="true" data-searchable="true" data-field="statcode">StatCode</th>
                                        <th data-sortable="true" data-searchable="true" data-field="expdate">EXPDATE</th>
                                        <th data-sortable="true" data-searchable="true" data-field="crdaccno" data-class="exportText">CRDACCNO</th>
                                        <th data-sortable="true" data-searchable="true" data-field="crdcurrcode">CRDCURRCODE</th>
                                        <th data-sortable="true" data-searchable="true" data-field="productid">PRODUCTID</th>
                                        <th data-sortable="true" data-searchable="true" data-field="file_name">File Name</th>

                                        <th data-sortable="true" data-searchable="true" data-field="created_at">{{ trans('admin/bankbal/table.created_at') }}</th>
            
                                                  
                                        
                                    </tr>
                                </thead>                        
                            </table>
                        
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
           </div>
       
</div>
@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'agency-banking-export', 'search' => true,'filterColumn'=>$filterColumn])
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
$(document).ready(function(){
    $('#table').bootstrapTable('refresh');
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
        format: 'yyyy-mm-dd',
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