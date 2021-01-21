@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.settelementsummary') }}
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
                    <form id="create-search-form" class="" method="get" action="{{ route('settelementsummary') }}" autocomplete="off" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}                      
                        <div class="col-md-8" style="margin-top:10px;">
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/settelementsummary/table.settlement_start_date') }}" data-date-format="yyyy-mm-dd" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/settelementsummary/table.settlement_end_date') }}" data-date-format="yyyy-mm-dd" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
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
                        name="settelementsummary"
                        data-height="500"
                        data-toolbar="#toolbar"
                        class="table table-striped snipe-table"
                        id="table"
                        data-toggle="table"                     
                        data-url="{{ route('api.settelementsummary.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
                        data-cookie="true"
                        data-click-to-select="true"
                        data-cookie-id-table="ssTableDisplay"
                        data-fixed-columns="true"
                        data-fixed-number="1">
                        <thead>
                            <tr>                                
                                
                                <th data-class="fixed-sort" data-switchable="false" data-sortable="true" data-searchable="true" data-field="settlement_date">{{ trans('admin/settelementsummary/table.settlement_date') }}</th>
    
                                <th data-searchable="true" data-sortable="true" data-field="opening_ac_bal">{{ trans('admin/settelementsummary/table.opening_ac_bal') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="scheme_to_settlement_transfer">{{ trans('admin/settelementsummary/table.scheme_to_settlement_transfer') }}</th>

                                <th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="charges">{{ trans('admin/settelementsummary/table.charges') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="deposits_into_settlement_ac">{{ trans('admin/settelementsummary/table.deposits_into_settlement_ac') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="monthly_interest_settlement_ac">{{ trans('admin/settelementsummary/table.monthly_interest_settlement_ac') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="no_of_pos_txn">{{ trans('admin/settelementsummary/table.no_of_pos_txn') }}</th>    

                                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="value_of_pos_txn">{{ trans('admin/settelementsummary/table.value_of_pos_txn') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="value_of_pos_interchange">{{ trans('admin/settelementsummary/table.value_of_pos_interchange') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="total_value_of_pos_txn">{{ trans('admin/settelementsummary/table.total_value_of_pos_txn') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="number_of_atm_txn">{{ trans('admin/settelementsummary/table.number_of_atm_txn') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="value_of_atm_txn">{{ trans('admin/settelementsummary/table.value_of_atm_txn') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="value_of_atm_interchange">{{ trans('admin/settelementsummary/table.value_of_atm_interchange') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="total_value_of_atm_txn">{{ trans('admin/settelementsummary/table.total_value_of_atm_txn') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="total_value_of_txn_settled">{{ trans('admin/settelementsummary/table.total_value_of_txn_settled') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="settlement_closing_bal_adj">{{ trans('admin/settelementsummary/table.settlement_closing_bal_adj') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="closing_ac_bal">{{ trans('admin/settelementsummary/table.closing_ac_bal') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="scheme_closing_bal">{{ trans('admin/settelementsummary/table.scheme_closing_bal') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="dr_cr_bank">{{ trans('admin/settelementsummary/table.dr_cr_bank') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="prefund">{{ trans('admin/settelementsummary/table.prefund') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="total_bal_available_to_cust_bal">{{ trans('admin/settelementsummary/table.total_bal_available_to_cust_bal') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="available_cust_bal_credit">{{ trans('admin/settelementsummary/table.available_cust_bal_credit') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="available_cust_bal_debit">{{ trans('admin/settelementsummary/table.available_cust_bal_debit') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="overall_cash_position">{{ trans('admin/settelementsummary/table.overall_cash_position') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="live_pans">{{ trans('admin/settelementsummary/table.live_pans') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="transactional_fees">{{ trans('admin/settelementsummary/table.transactional_fees') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="month">{{ trans('admin/settelementsummary/table.month') }}</th>    
                                                            
                            </tr>
                        </thead>                        
                    </table>
                
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div>
</div>

@stop


@section('moar_scripts')
@include ('partials.bootstrap-table', ['exportFile' => 'settelement-summary-export', 'search' => true,'filterColumn'=>$filterColumn])
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
$("#table").delegate("tr", "click", function()
{
      $("#table tr").removeClass('highlight_row');
      $('.fixed-table-body-columns table tr').removeClass('highlight_row');
      $('.fixed-table-body-columns table tr').eq($(this).index()).addClass('highlight_row');
      $(this).addClass('highlight_row');
});

function resetAllTableData()
{
    $.removeCookie('ssTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ssTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ssTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ssTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('ssTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('settelementsummary') }}";
}

</script>
@stop