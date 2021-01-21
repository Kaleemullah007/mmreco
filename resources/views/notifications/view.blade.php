@extends('layouts/default')

{{-- Page title --}}
@section('title')
FPS Files View
@parent
@stop

@section('header_right')
@stop

{{-- Page content --}}
@section('content')
@include('notifications')
<div class="row">
 <div class="col-xs-12">
    <div class="box box-default">
        <div class="box-body">     
            <form id="create-search-form" class="" method="get" action="{{route('showentries')}}" autocomplete="off" role="form" >
                {{ csrf_field() }}                      
                <div class="col-md-8" >
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="report_datepicker form-control" placeholder="From Date" data-date-format="yyyy-mm-dd" name="fromdate" id="fromdate" value="{{ Input::old('fromdate', Input::get('fromdate')) }}">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="report_datepicker form-control" placeholder="To Date" data-date-format="yyyy-mm-dd" name="todate" id="todate" value="{{ Input::old('todate', Input::get('todate')) }}">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">   
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search icon-white"></i> Search</button>  
                    </div>
                </div>
            </form>      
         <table id="notification_entries" class="display stripe row-border order-column" style="width:100%" >
                <thead>
                    <tr>
                        <th>Import File</th>
                        <th>Date</th>
                        <th>Total Entries</th>
                        <th>Entries Sum</th>
                        <th>Credit Entries</th>
                        <th>Credit Entries Sum</th>
                        <th>Debit Entries</th>
                        <th>Debit Entries Sum</th>
                        <th>Party Account</th>
                        <th>Party Account Currency</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Amount</th>
                        <th>Credit Debit Indicator</th>
                        <th>Status</th>
                        <th>Booking Date</th>
                        <th>Booking Transaction Code</th>
                        <th>TxId/th>
                        <th>EndToEndId</th>
                        <th>Dbtr_Nm</th>
                        <th>DbtrAcct_Id</th>
                        <th>Cdtr_Nm</th>
                        <th>CdtrAcct_Id</th>
                        <th>Prtry_Tp</th>
                        <th>AddtlRmtInf</th>
                        <th>TxDtTm</th>
                        <th>AddtlTxInf</th>
                        <th>AddtlNtryInf</th>
                        <th>AddtlRmtInf_ref</th>
                    </tr>
                </thead>
                <tbody>               
                   @foreach($entries as $entry)
                         <tr>
                        <td>{{ $entry->importfile_name }}</td>
                        <td>{{ $entry->created_time }}</td>
                        <td>{{ $entry->entries }}</td>
                        <td>{{ $entry->entries_sum }}</td>
                        <td>{{ $entry->credit_entries }}</td>
                        <td>{{ $entry->credit_entries_sum }}</td>
                        <td>{{ $entry->debit_entries }}</td>
                        <td>{{ $entry->debit_entries_sum }}</td>
                        <td>{{ $entry->party_account }}</td>
                        <td>{{ $entry->currency }}</td>
                        <td>{{ $entry->created_at }}</td>
                        <td>{{ $entry->updated_at }}</td>
                        <td>{{ $entry->amount }}</td>
                        <td>{{ $entry->credit_debit_indicator }}</td>
                        <td>{{ $entry->status }}</td>
                        <td>{{ $entry->booking_date }}</td>
                        <td>{{ $entry->booking_transaction_code }}</td>
                        <td>{{ $entry->TxId  }}</td>
                        <td>{{ $entry->EndToEndId  }}</td>
                        <td>{{ $entry->Dbtr_Nm  }}</td>   
                        <td>{{ $entry->DbtrAcct_Id  }}</td>
                        <td>{{ $entry->Cdtr_Nm }}</td>
                        <td>{{ $entry->CdtrAcct_Id  }}</td>
                        <td>{{ $entry->Prtry_Tp  }}</td>
                        <td>{{ $entry->AddtlRmtInf }}</td>
                        <td>{{ $entry->TxDtTm  }}</td>
                        <td>{{ $entry->AddtlTxInf  }}</td>
                        <td>{{ $entry->AddtlNtryInf  }}</td>
                        <td>{{ $entry->AddtlRmtInf_ref  }}</td>
                    </tr>
                        @endforeach                   
                </tbody>                
            </table>          
            <div class="row">
            <div class="col-md-6 ">  
                
                @if(!empty($entries))
               
                <p class="text-center">{{$entries->appends(Input::except('page'))->links()}}</p>
                <p > Records {{ $entries->firstItem() }} - {{ $entries->lastItem() }} of {{ $entries->total() }} (for page {{ $entries->currentPage() }} )
                        </p>
                @endif
            </div>
        </div>
     </div><!-- /.box-body -->
   </div><!-- /.box --> 
</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
<!-- <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>

<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
<script type="text/javascript">
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $("#fromdate").datepicker({}).on('changeDate', function(ev){
          $("#todate").datepicker( "setDate", $(this).val());
        });
        $('#notification_entries thead tr').clone(true).appendTo( '#notification_entries thead' );
        $('#notification_entries thead tr:eq(1) th').each( function (i) {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="" />' );
     
            $( 'input', this ).on( 'keyup change', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            } );
        }); 
      
        var table = $('#notification_entries').DataTable({
            
            orderCellsTop: true,
            "ordering": false,
            "paging":false,
            fixedHeader: true,
            // deferRender:    true,
            scrollCollapse: true,
            scroller:       true,
            scrollX:        true,
            scrollY:        200,
            "sDom": '<"top"lBf>rt<"bottom"ip><"clear">',
            // "dom": 'rt<"bottom"flp><"clear">',
            // "searching": false,
            fixedColumns:   {
                leftColumns: 1,
                rightColumns: 1
            },
            "lengthMenu": [ [20, 40, 100, -1], [20, 40, 100, "All"] ],
            // dom: 'Bfrtip',
             buttons: [ {
            extend: 'excelHtml5',
            title: 'Notification-entries-'+(new Date()).toISOString().slice(0,10),
            customize: function ( xlsx ){
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                // jQuery selector to add a border
                $('row c[r*="10"]', sheet).attr( 's', '25' );
            }
        } ]
            
        });
        
        

      
    } );
    
</script>

@stop

