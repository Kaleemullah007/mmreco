@extends('layouts/default')

{{-- Page title --}}
@section('title')
Manual Reco
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
          <div class="col-sm-12">
            <div class="matching-table">
              <div class="col-sm-6">
                <h2 class="text-center table-title">Bank Statement Data</h2>
                <div class="table table-responsive">
                  <form id="bst-search-form" name="bst-search-form" class="" method="post" action="" autocomplete="off" role="form">
                    <div class="col-md-3">
                      {{ Form::select('Bankmaster', $Bankmaster, null, ['id' => 'Bankmaster','class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 padding-0">
                      <div class="input-group">
                        <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="bst_start_date" id="bst_start_date" value="">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>

                    <div class="col-md-3 padding-0" >
                      <div class="input-group">
                        <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="bst_end_date" id="bst_end_date" value="">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>          
                    </div>
                    <div class="col-md-3">
                      <div class="">
                        <button type="button" class="btn btn-success" onclick="filterBankStatement();"><i class="fa fa-search icon-white"></i> {{ trans('general.search') }}</button>
                      
                                    
                                </div>
                    </div>
                    <div id="bstValidationError" class="col-md-12"></div>
                  </form>
                <table
                  name="bankstatement"
                  data-height="500"
                  data-toolbar="#toolbar"
                  class="table table-striped snipe-table bankstatement"
                  id="table"
                  data-toggle="table"           
                  data-url="{{ route('manualtransaction/getBstDatatable') }}"
                  data-cookie="true"
                  data-click-to-select="true"
                  data-cookie-id-table="bankstmtTableDisplay">
                  <thead>
                    <tr>
        
                      <th data-searchable="false" data-sortable="false" data-field="actions"></th>

                      <th data-searchable="true" data-sortable="true" data-field="name">Bank</th>

                      <th  data-class="date_width" data-searchable="true" data-sortable="true" data-field="date">{{ trans('admin/bankstmt/table.date') }}</th>

                      <th data-class="sowShort-150" data-searchable="true" data-sortable="true" data-field="description">{{ trans('admin/bankstmt/table.description') }}</th>

                      <th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="type">{{ trans('admin/bankstmt/table.type') }}</th>

                      <th data-class="dataTable-text-right" data-searchable="true" data-sortable="true" data-field="debit">{{ trans('admin/bankstmt/table.debit') }}</th>

                      <th data-class="dataTable-text-right" data-searchable="true" data-sortable="true" data-field="credit">{{ trans('admin/bankstmt/table.credit') }}</th>
                      
                    </tr>
                  </thead>            
                </table>
                </div>
              </div>
              <div class="col-sm-6">
                <h2 class="text-center table-title">Transaction Data</h2>
                <div class="table table-responsive" >

                  <form id="txn-search-form" name="txn-search-form" class="" method="post" action="" autocomplete="off" role="form">
                    <div class="col-md-3">
                      {{ Form::select('TransactionDrop', $TransactionDrop, $txnDrop, ['id' => 'TransactionDrop','class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 padding-0">
                      <div class="input-group">
                        <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.start_date') }}" data-date-format="yyyy-mm-dd" name="txn_start_date" id="txn_start_date" value="{{$txn_start_date}}">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>

                    <div class="col-md-3 padding-0" >
                      <div class="input-group">
                        <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('general.end_date') }}" data-date-format="yyyy-mm-dd" name="txn_end_date" id="txn_end_date" value="{{$txn_end_date}}">
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
             <a class="btn btn-primary pull-right" href="javascript:void(0)" onclick="submitMatches();">Submit Matches</a>
                <a class="btn btn-primary bg-red pull-right" href="{{ route('manualtransaction/resetTxn') }}" style="margin-right: 5px;">Reset Matches</a>
          </div>
          <div class="col-sm-12">
            <div class="resulting-table">
              <div class="col-sm-12">
                <h2 class="text-center table-title">Result</h2>
                <div class="col-sm-12 text-center">
                  <h2 class="text-center table-title">Bank Statement</h2>
                  <table class="table table-bodered text-center">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Flag</th>
                      </tr>
                    </thead>
                    <tbody id="bst_selected_data">
                      <?php if(!empty($bankStatementData)) { ?>
                        <tr>
                          <td><button type="button" class="btn btn-danger btn-sm " onclick="removeBankStatement('bstRadio-<?php echo $bankStatementData->ids ?>','<?php echo $bankStatementData->ids ?>','bank_statement')"><i class="fa fa-close icon-white"></i></button></td>
                          <td><?php echo $bankStatementData->date; ?></td>
                          <td><?php echo $bankStatementData->description; ?></td>
                          <td><?php echo $bankStatementData->type; ?></td>
                          <td class="dataTable-text-right"><?php echo number_format($bankStatementData->debit,2,'.',''); ?></td>
                          <td class="dataTable-text-right"><?php echo number_format($bankStatementData->credit,2,'.',''); ?></td>
                          <td ><select name="extra_flg" id="extra_flg" class="form-control"> <?php foreach ($extra_Flg as $key => $value) { ?>
                              <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                           <?php } ?> </select> </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>

                <div class="col-sm-12 text-center">
                  <h2 class="text-center table-title">Selected Transaction</h2>
                  <table class="table table-bodered text-center">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody id="txn_selected_data">
                     <?php if(!empty($agencybankingApprovedData)) { foreach ($agencybankingApprovedData as $key => $value) { ?>
                        <tr id="agencybanking_approved-<?php echo $value->ids ?>">
                          <td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData('txnchkbox-<?php echo $value->ids ?>','<?php echo $value->ids ?>','agencybanking_approved')"><i class="fa fa-close icon-white"></i></button></td>
                          <td><?php echo $value->SettlementDate; ?></td>
                          <td>Approved Agency Banking</td>
                          <td><?php echo $value->External_name." ".$value->External_sortcode." ".$value->External_bankacc; ?></td>
                          <td><?php echo number_format($value->CashAmt_value,2,'.',''); ?></td>
                        </tr>
                      <?php } } ?>
                     <?php if(!empty($agencybankingDeclinedData)) { foreach ($agencybankingDeclinedData as $key => $value) { ?>
                        <tr id="agencybanking_declined-<?php echo $value->ids ?>">
                          <td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData('txnchkbox-<?php echo $value->ids ?>','<?php echo $value->ids ?>','agencybanking_declined')"><i class="fa fa-close icon-white"></i></button></td>
                          <td><?php echo $value->SettlementDate; ?></td>
                          <td>Declined Agency Banking</td>
                          <td><?php echo $value->External_name." ".$value->External_sortcode." ".$value->External_bankacc; ?></td>
                          <td><?php echo number_format($value->CashAmt_value,2,'.',''); ?></td>
                        </tr>
                      <?php } } ?>
                     <?php if(!empty($fp_outData)) { foreach ($fp_outData as $key => $value) { ?>
                        <tr id="fp_out-<?php echo $value->ids ?>">
                          <td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData('txnchkbox-<?php echo $value->ids ?>','<?php echo $value->ids ?>','fp_out')"><i class="fa fa-close icon-white"></i></button></td>
                          <td><?php echo $value->file_date; ?></td>
                          <td>Faster Payment Out</td>
                          <td><?php echo $value->OrigCustomerSortCode." ".$value->OrigCustomerAccountNumber; ?></td>
                          <td><?php echo number_format($value->Amount,2,'.',''); ?></td>
                        </tr>
                      <?php } } ?>
                     <?php if(!empty($balance_adjData)) { foreach ($balance_adjData as $key => $value) { ?>
                        <tr id="balance_adj-<?php echo $value->ids ?>">
                          <td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData('txnchkbox-<?php echo $value->ids ?>','<?php echo $value->ids ?>','balance_adj')"><i class="fa fa-close icon-white"></i></button></td>
                          <td><?php echo $value->SettlementDate; ?></td>
                          <td>Balance Adjustement</td>
                          <td><?php echo $value->Desc; ?></td>
                          <td><?php echo number_format($value->Amount_value,2,'.',''); ?></td>
                        </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>

                <a class="btn btn-primary pull-right" href="javascript:void(0)" onclick="submitMatches();">Submit Matches</a>
                <a class="btn btn-primary bg-red pull-right" href="{{ route('manualtransaction/resetTxn') }}" style="margin-right: 5px;">Reset Matches</a>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'users-export', 'search' => true])
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-table.css') }}">
<style type="text/css">
  .fixed-table-toolbar {
    display: inline-block;
    width: 100%;
    margin: 10px 0;
  }
  .padding-0{
    padding: 0px !important;
  }
  .highlight_row {
    background-color: #e5e8e8;
  }
  .dataTable-text-right{
    text-align: right;
  }

  #bstValidationError , #txnValidationError{
    color: #ff0000;
    font-weight: 800;
  }

</style>
<script>
$(document).ready(function(){
filterTransaction();

});
function filterBankStatement()
{
  if($("#Bankmaster").val() != '' && $('#bst_start_date').val() != '' && $('#bst_end_date').val() != '')
  {
    $("#bstValidationError").text('');
      var dataval = {Bankmaster : $("#Bankmaster").val() , bst_start_date : $('#bst_start_date').val() , bst_end_date : $('#bst_end_date').val() };

      var table = $('#table').bootstrapTable(
          'refresh', {
            url: "{{config('app.url') }}/manualtransaction/getBstDatatable",
            query: {filterData: JSON.stringify(dataval) }
          });
        
      return table;
  }
  else
  {
    $('#bstValidationError').text("All field are required to get bankstatement data.");
  }
  
}

function bstRadioSelect(obj,id,type)
{
  var extra_flg = $.parseJSON('<?php echo json_encode($extra_Flg); ?>');
  //bst_selected_data
  $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
  $.ajax({
          type: 'POST',
          url: "{{config('app.url') }}/manualtransaction/bstRadioSelect", 
          data: {id:id,type : type},
          success:function( data ) 
          { 
              if($.trim(data) == "success")
              {
                var trdata = $(obj).parent().parent();
                $('.bankstatement tr').removeClass('highlight_row');
                $(trdata).addClass('highlight_row');
                var trBstHtml = '<tr>';
                trBstHtml = trBstHtml + '<td><button type="button" class="btn btn-danger btn-sm " onclick="removeBankStatement(\''+$(obj).attr('id')+'\',\''+id+'\',\''+type+'\')"><i class="fa fa-close icon-white"></i></button></td>';
                trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(2)').text() + '</td>';
                trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(3)').text() + '</td>';
                trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(4)').text() + '</td>';
                trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(5)').text() + '</td>';
                trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(6)').text() + '</td>';
                var extraSelectHtml = '<select class="form-control" name="extra_flg" id="extra_flg">';
                var optionHtml ='';
                $.each(extra_flg , function(k,v){
                  optionHtml = optionHtml + '<option value="'+k+'">'+v+'</option>';
                });
                extraSelectHtml = extraSelectHtml + optionHtml + '</select>';
                trBstHtml = trBstHtml + '<td>' + extraSelectHtml + '</td>';
                trBstHtml = trBstHtml + '</tr>';

                $('#bst_selected_data').html(trBstHtml);

              }
              else
              {
                alert("error in selecting data");
              }
            
          },
          error : function(xhr,status,error)
          {
               
          }
  });

}

function removeBankStatement(InputId , id , type)
{
   $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
  $.ajax({
          type: 'POST',
          url: "{{config('app.url') }}/manualtransaction/bstRadioDelete", 
          data: {id:id,type : type},
          success:function( data ) 
          { 
              if($.trim(data) == "success")
              {
                
                $('#bst_selected_data').remove();
                 $('.bankstatement tr').removeClass('highlight_row');
                $("#"+InputId).prop("checked", false);

              }
              else
              {
                alert("error in selecting data");
              }
            
          },
          error : function(xhr,status,error)
          {
               
          }
  });
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
            url: "{{config('app.url') }}/manualtransaction/filterTransaction", 
            data: {TransactionDrop : $('#TransactionDrop').val() , txn_start_date : $('#txn_start_date').val() , txn_end_date : $('#txn_end_date').val()},
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

function txnCheckBoxSelect(obj,id,type)
{
  if($(obj).prop("checked") == true)
  {
  
      $.ajaxSetup({
            headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
      $.ajax({
              type: 'POST',
              url: "{{config('app.url') }}/manualtransaction/txnSelect", 
              data: {id:id,type : type},
              success:function( data ) 
              { 
                  if($.trim(data) == "success")
                  {

                    var trdata = $(obj).parent().parent();
                    $(trdata).addClass('highlight_row');
                    var trBstHtml = '<tr id="'+type+'-'+id+'">';
                    trBstHtml = trBstHtml + '<td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData(\''+$(obj).attr('id')+'\',\''+id+'\',\''+type+'\')"><i class="fa fa-close icon-white"></i></button></td>';

                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(1)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + $('#TransactionDrop option:selected').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(5)').text() +' '+ trdata.find('td:eq(4)').text() +' '+ trdata.find('td:eq(3)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(2)').text() + '</td>';

                    trBstHtml = trBstHtml + '</tr>';

                    $('#txn_selected_data').append(trBstHtml);

                  }
                  else
                  {
                    alert("error in selecting data");
                  }
                
              },
              error : function(xhr,status,error)
              {
                   
              }
      });
  }
  else
  {
      removeTransactionData($(obj).attr('id') , id , type);
  }
}

function removeTransactionData(InputId , id , type)
{
  $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
  $.ajax({
          type: 'POST',
          url: "{{config('app.url') }}/manualtransaction/transactionDelete", 
          data: {id:id,type : type},
          success:function( data ) 
          { 
              if($.trim(data) == "success")
              {
                
                $("#txn_selected_data #"+type+"-"+id).remove();
                 $('#'+InputId).parent().parent().removeClass('highlight_row');
                $("#"+InputId).prop("checked", false);

              }
              else
              {
                alert("error in selecting data");
              }
            
          },
          error : function(xhr,status,error)
          {
               
          }
  });
}

function txnFpoutCheckBoxSelect(obj,id,type)
{
  if($(obj).prop("checked") == true)
  {
  
      $.ajaxSetup({
            headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
      $.ajax({
              type: 'POST',
              url: "{{config('app.url') }}/manualtransaction/txnSelect", 
              data: {id:id,type : type},
              success:function( data ) 
              { 
                  if($.trim(data) == "success")
                  {

                    var trdata = $(obj).parent().parent();
                    $(trdata).addClass('highlight_row');
                    var trBstHtml = '<tr id="'+type+'-'+id+'">';
                    trBstHtml = trBstHtml + '<td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData(\''+$(obj).attr('id')+'\',\''+id+'\',\''+type+'\')"><i class="fa fa-close icon-white"></i></button></td>';

                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(1)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + $('#TransactionDrop option:selected').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(3)').text() +' '+ trdata.find('td:eq(4)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(5)').text() + '</td>';

                    trBstHtml = trBstHtml + '</tr>';

                    $('#txn_selected_data').append(trBstHtml);

                  }
                  else
                  {
                    alert("error in selecting data");
                  }
                
              },
              error : function(xhr,status,error)
              {
                   
              }
      });
  }
  else
  {
      removeTransactionData($(obj).attr('id') , id , type);
  }
}

function txnBalAdjCheckBoxSelect(obj,id,type)
{
  if($(obj).prop("checked") == true)
  {
  
      $.ajaxSetup({
            headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
      $.ajax({
              type: 'POST',
              url: "{{config('app.url') }}/manualtransaction/txnSelect", 
              data: {id:id,type : type},
              success:function( data ) 
              { 
                  if($.trim(data) == "success")
                  {

                    var trdata = $(obj).parent().parent();
                    $(trdata).addClass('highlight_row');
                    var trBstHtml = '<tr id="'+type+'-'+id+'">';
                    trBstHtml = trBstHtml + '<td><button type="button" class="btn btn-danger btn-sm " onclick="removeTransactionData(\''+$(obj).attr('id')+'\',\''+id+'\',\''+type+'\')"><i class="fa fa-close icon-white"></i></button></td>';

                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(1)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + $('#TransactionDrop option:selected').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(4)').text() + '</td>';
                    trBstHtml = trBstHtml + '<td>' + trdata.find('td:eq(3)').text() + '</td>';

                    trBstHtml = trBstHtml + '</tr>';

                    $('#txn_selected_data').append(trBstHtml);

                  }
                  else
                  {
                    alert("error in selecting data");
                  }
                
              },
              error : function(xhr,status,error)
              {
                   
              }
      });
  }
  else
  {
      removeTransactionData($(obj).attr('id') , id , type);
  }
}

function submitMatches()
{
  var bstAmt = 0;
  if($("#bst_selected_data tr").find('td:eq(4)').text() != '' &&  $("#bst_selected_data tr").find('td:eq(4)').text() != 0)
  {
    bstAmt = parseFloat($("#bst_selected_data tr").find('td:eq(4)').text());
  }
  else
  {
    bstAmt = parseFloat($("#bst_selected_data tr").find('td:eq(5)').text());
  }
  var destinationAmt = 0;
  $("#txn_selected_data tr").each( function(){
    destinationAmt = destinationAmt + parseFloat($(this).find('td:eq(4)').text());
  });


  if($('#extra_flg').val() == '' && bstAmt != destinationAmt)
  {
      alert("BankStatement and Transaction Amount not metched");
  }
  else
  {

    $.ajaxSetup({
        headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $.ajax({
      type: 'POST',
      url: "{{config('app.url') }}/manualtransaction/submitMatches", 
      data: {extra_flg: $('#extra_flg').val()},
      success:function( data ) 
      { 
        $('#bst_selected_data').remove();
        $('#txn_selected_data').remove();
        $('#table').bootstrapTable('refresh');
        $('#transactionDataSection #table').bootstrapTable('refresh');
      }
    });

  }

}

function bootstrapTableCall()
{
  var filterData = {};
  if($('#TransactionDrop').val() == 'agencybanking_approved')
  {
    filterData = <?php echo json_encode($abApprovedfilterColumn); ?>;
  }
  else if($('#TransactionDrop').val() == 'agencybanking_declined')
  {
    filterData = <?php echo json_encode($abDeclinedfilterColumn); ?>;
  }
  else if($('#TransactionDrop').val() == 'fp_out')
  {
    filterData = <?php echo json_encode($fp_outfilterColumn); ?>;
  }
  else if($('#TransactionDrop').val() == 'balance_adj')
  {
    filterData = <?php echo json_encode($balance_adjfilterColumn); ?>;
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
@stop
