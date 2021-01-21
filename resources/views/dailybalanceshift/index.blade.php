@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.dailybalanceshift') }}
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
                    <form id="create-search-form" class="" method="get" action="{{ route('dailybalanceshift') }}" autocomplete="off" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}                      
                        <div class="col-md-8" style="margin-top:10px;">
                            
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/dailybalanceshift/table.repot_start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/dailybalanceshift/table.repot_end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" placeholder="{{ trans('admin/dailybalanceshift/table.pan') }}" class="form-control" style="margin-top:0px;" name="pan" id="pan" value="{{ Input::old('pan', Input::get('pan')) }}">
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
                        name="dailyBalanceShift"
                        data-height="500"
                        data-toolbar="#toolbar"
                        class="table table-striped snipe-table"
                        id="table"
                        data-toggle="table"                     
                        data-url="{{ route('api.dailybalanceshift.list', array('pan'=>(Input::get('pan'))?Input::get('pan'):'','start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
                        data-cookie="true"
                        data-click-to-select="true"
                        data-cookie-id-table="dbsTableDisplay"
                        data-fixed-columns="true"
                        data-fixed-number="2">
                        <thead>
                            <tr>                                
                                
                                <th data-class="fixed-sort" data-sortable="true" data-searchable="true" data-field="repot_date">{{ trans('admin/dailybalanceshift/table.repot_date') }}</th>
    
                                <th data-class="fixed-sort exportText" data-searchable="true" data-sortable="true" data-field="pan" >{{ trans('admin/dailybalanceshift/table.pan') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="opening_ac_bal">{{ trans('admin/dailybalanceshift/table.opening_ac_bal') }}</th>

                                <th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="ATM_Settled">{{ trans('admin/dailybalanceshift/table.atm_settled') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="POS_Settled">{{ trans('admin/dailybalanceshift/table.pos_settled') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="ATM_FEE">{{ trans('admin/dailybalanceshift/table.atm_fee') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="FPIN">{{ trans('admin/dailybalanceshift/table.fpin') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="Bacs_IN">Bacs IN</th>    

                                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="FP_out">{{ trans('admin/dailybalanceshift/table.fp_out') }}</th>

                                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="AB_DD">AB DD</th>

                                <th data-searchable="true" data-sortable="true" data-field="FP_out_fee">{{ trans('admin/dailybalanceshift/table.fp_out_fee') }}</th>
                                
                                <th data-searchable="true" data-sortable="true" data-field="charge_backs">{{ trans('admin/dailybalanceshift/table.charge_backs') }}</th>
                                <th data-searchable="true" data-sortable="true" data-field="representments">{{ trans('admin/dailybalanceshift/table.representments') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Other_fees">{{ trans('admin/dailybalanceshift/table.other_fees') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Load_Unload">{{ trans('admin/dailybalanceshift/table.load_unload') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Blocked_Amount">{{ trans('admin/dailybalanceshift/table.blocked_amount') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="Offline_Term_Trans">{{ trans('admin/dailybalanceshift/table.offline_term_trans') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Balance_Adj">{{ trans('admin/dailybalanceshift/table.balance_adj') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="closing_ac_bal_calc">{{ trans('admin/dailybalanceshift/table.closing_ac_bal_calc') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="closing_ac_bal_gps">{{ trans('admin/dailybalanceshift/table.closing_ac_bal_gps') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="trans_settled_not_adj_gps">{{ trans('admin/dailybalanceshift/table.trans_settled_not_adj_gps') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="trans_settled_not_adj_gps_2">{{ trans('admin/dailybalanceshift/table.trans_settled_not_adj_gps_2') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="diff">{{ trans('admin/dailybalanceshift/table.diff') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="actions">Action</th>    
                                                            
                            </tr>
                        </thead>                        
                    </table>
                
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>
</div>

<div class="modal fade" id="relateTransactionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Relate Transaction</h4>
            </div>
            <div class="modal-body">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="table-responsive">
                           <form id="txn-search-form" name="txn-search-form" class="" method="post" action="" autocomplete="off" role="form">
                            <div class="col-md-3">
                              {{ Form::select('TransactionDrop', $TransactionDrop, null, ['id' => 'TransactionDrop','class' => 'form-control']) }}
                            </div>
                            <input type="hidden" name="dailyBalanceShiftId" id="dailyBalanceShiftId" />
                            <input type="hidden" name="dailyBalanceShiftPanNum" id="dailyBalanceShiftPanNum" />
                            <div class="col-md-3 padding-0">
                              <div class="input-group">
                                <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="txn_start_date" id="txn_start_date" value="">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                              </div>
                            </div>

                            <div class="col-md-3 padding-0" >
                              <div class="input-group">
                                <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="txn_end_date" id="txn_end_date" value="">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                              </div>          
                            </div>
                            <div class="col-md-3">
                              <div class="">
                                <button type="button" class="btn btn-success" onclick="filterTransaction();"><i class="fa fa-search icon-white"></i> {{ trans('general.search') }}</button>
                              
                                            
                                        </div>
                            </div>
                            <div id="txnValidationError" class="col-md-12"></div>
                          </form>
                            <div id="transactionDataSection">
                    
                            </div>
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

@stop


@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'daily-balance-shift-export', 'search' => true,'filterColumn'=>$filterColumn])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-editable.css') }}">
<script src="{{asset('assets/js/dropzone/dropzone.min.js')}}"></script>
<link href="{{asset('assets/js/dropzone/dropzone.min.css')}}" type="text/css" rel="stylesheet" />
<style type="text/css">
    #addimg{
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
    table td{
        text-align: right;
    }
</style>
<script>
  $(document).ready(function(){

$("#start_date").datepicker({}).on('changeDate', function(ev){
  $("#end_date").datepicker( "setDate", $(this).val());
});

$("#txn_start_date").datepicker({}).on('changeDate', function(ev){
  $("#txn_end_date").datepicker( "setDate", $(this).val());
});

  });
$("#table").delegate("tr", "click", function()
{
      $("#table tr").removeClass('highlight_row');
      $('.fixed-table-body-columns table tr').removeClass('highlight_row');
      $('.fixed-table-body-columns table tr').eq($(this).index()).addClass('highlight_row');
      $(this).addClass('highlight_row');
});

function resetAllTableData()
{
    $.removeCookie('dbsTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('dailybalanceshift') }}";
}

function openPopupRelateData(dsid,panNum)
{
    $('#dailyBalanceShiftId').val(dsid);
    $('#dailyBalanceShiftPanNum').val(panNum);
    $('#transactionDataSection').html('');
    $('#txn_start_date').val($('#start_date').val());
    $('#txn_end_date').val($('#end_date').val());
    filterTransaction();
    $('#relateTransactionModal').modal('show');
}
function filterTransaction()
{
  if($("#TransactionDrop").val() != '' && $('#txn_start_date').val() != '' && $('#txn_end_date').val() != '')
  {
    $('#txnValidationError').text('');
    $.ajaxSetup({
          headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
      });
    $.ajax({
            type: 'POST',
            url: "{{config('app.url') }}/dailybalanceshift/filterTransaction", 
            data: {TransactionDrop : $('#TransactionDrop').val() , txn_start_date : $('#txn_start_date').val() , txn_end_date : $('#txn_end_date').val() , dailybalanceshiftId : $('#dailyBalanceShiftId').val() ,dailyBalanceShiftPanNum : $('#dailyBalanceShiftPanNum').val() },
            success:function( data ) 
            { 
              $('#transactionDataSection').html(data);
              bootstrapTableCall();
               
            },
            error : function(xhr,status,error)
            {
                 
            }
    });
  }
  else
  {
    $('#txnValidationError').text("All field are required to get Transaction data.");
  }
}

function removelinktxnData1(cardType , ids)
{
    if($("#dailyBalanceShiftId").val() != '')
      {
        
        $.ajaxSetup({
              headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
          });
        $.ajax({
                type: 'POST',
                url: "{{config('app.url') }}/dailybalanceshift/removelinktxnData", 
                data: {dailyBalanceShiftId : $('#dailyBalanceShiftId').val() , cardType : cardType , ids : ids },
                success:function( data ) 
                { 
                    $('#transactionDataSection').html('');
                    $('#table').bootstrapTable('refresh');
                    $('#relateTransactionModal').modal('hide');
                },
                error : function(xhr,status,error)
                {
                     
                }
        });
      }
      else
      {
       alert('something wrong please try again.');
      }
}

function linktxnData(cardType , ids)
{
    if($("#dailyBalanceShiftId").val() != '')
      {
        
        $.ajaxSetup({
              headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
          });
        $.ajax({
                type: 'POST',
                url: "{{config('app.url') }}/dailybalanceshift/linkTxnData", 
                data: {dailyBalanceShiftId : $('#dailyBalanceShiftId').val() , cardType : cardType , ids : ids },
                success:function( data ) 
                { 
                    $('#transactionDataSection').html('');
                    $('#table').bootstrapTable('refresh');
                    $('#relateTransactionModal').modal('hide');
                },
                error : function(xhr,status,error)
                {
                     
                }
        });
      }
      else
      {
       alert('something wrong please try again.');
      }
}



function bootstrapTableCall()
{
  var filterData = {};
  if($('#TransactionDrop').val() == 'Cardfinancial')
  {
    filterData = <?php echo json_encode($Cardfinancial); ?>;
  }
  else if($('#TransactionDrop').val() == 'Cardfee')
  {
    filterData = <?php echo json_encode($Cardfee); ?>;
  }

    $('.snipe-table1').bootstrapTable({
        classes: 'table table-responsive table-no-bordered',
        undefinedText: '',
        iconsPrefix: 'fa',
        showRefresh: true,
        cookie: false,
        search: true,
        pageSize: 20,
        // pageSize: 'All',
        pagination: true,
        sortOrder: 'desc',
        detailView: false,
        sidePagination: 'server',
        sortable: true,
        cookieExpire: '1440mi',
        mobileResponsive: true,
        @if (isset($multiSort))
        showMultiSort: true,
        @endif
        showExport: false,
        showColumns: true,
        //exportDataType: 'all',
        exportTypes: ['excel'],
        exportOptions: {
            fileName: '{{ "asdds" . "-" }}' + (new Date()).toISOString().slice(0,10),
            ignoreColumn: ['actions','radioedit'],
        },
        maintainSelected: true,
        paginationFirstText: "{{ trans('general.first') }}",
        paginationLastText: "{{ trans('general.last') }}",
        paginationPreText: "{{ trans('general.previous') }}",
        paginationNextText: "{{ trans('general.next') }}",
        pageList: ['10','25','50','100','150','All'],
        icons: {
            paginationSwitchDown: 'fa-caret-square-o-down',
            paginationSwitchUp: 'fa-caret-square-o-up',
            columns: 'fa-columns',
            @if( isset($multiSort))
            sort: 'fa fa-sort-amount-desc',
            plus: 'fa fa-plus',
            minus: 'fa fa-minus',
            @endif
            refresh: 'fa-refresh'
        },
        columns:filterData,
        filter: true,
        onAll: function() {

           $('.radioSelected').parent().parent().addClass('highlight_row');
        },
        onLoadSuccess: function() 
        {
    
        },

    });
}

</script>
@stop