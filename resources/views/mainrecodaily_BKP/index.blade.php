@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.mainreco') }}
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
                    <form id="create-search-form" class="" method="post" action="{{ route('mainrecodaily') }}" autocomplete="off" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}                      
                        <div class="col-md-8" style="margin-top:10px;">
                            
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/dailybalanceshift/table.repot_start_date') }}" data-date-format="dd-mm-yyyy" name="start_date" id="start_date" value="{{ Input::old('start_date', Input::get('start_date')) }}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>                                
                            </div>
                        
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker form-control" style="margin-top:0px;" placeholder="{{ trans('admin/dailybalanceshift/table.repot_end_date') }}" data-date-format="dd-mm-yyyy" name="end_date" id="end_date" value="{{ Input::old('end_date', Input::get('end_date')) }}">
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
                        name="mainRecoDaily"
                        data-height="500"
                        data-toolbar="#toolbar"
                        class="table table-striped snipe-table"
                        id="table"
                        data-toggle="table"                     
                        data-url="{{ route('api.mainrecodaily.list', array('start_date'=>(Input::get('start_date'))?Input::get('start_date'):'','end_date'=>(Input::get('end_date')?Input::get('end_date'):''))) }}"
                        data-cookie="true"
                        data-click-to-select="true"
                        data-cookie-id-table="dbsTableDisplay"
                        data-fixed-columns="true"
                        data-fixed-number="1">
                        <thead>
                            <tr>                                
                                
                                <th data-class="fixed-sort" data-sortable="true" data-searchable="true" data-field="report_date">{{ trans('admin/mainrecodaily/table.repot_date') }}</th>

                                <th data-sortable="true" data-searchable="true" data-field="diff_amt">{{ trans('admin/mainrecodaily/table.diff_amt') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="not_loaded_card_opening">{{ trans('admin/mainrecodaily/table.not_loaded_card_opening') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="ab_declined_not_loaded">{{ trans('admin/mainrecodaily/table.ab_declined_not_loaded') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="not_matched_to_load">{{ trans('admin/mainrecodaily/table.not_matched_to_load') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="bacs_not_loaded_card">{{ trans('admin/mainrecodaily/table.bacs_not_loaded_card') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="dca_adjustment">{{ trans('admin/mainrecodaily/table.dca_adjustment') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="not_loaded_card_closing">{{ trans('admin/mainrecodaily/table.not_loaded_card_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="app_fp_in_not_rec_current">{{ trans('admin/mainrecodaily/table.app_fp_in_not_rec_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="app_fp_in_not_rec_closing">{{ trans('admin/mainrecodaily/table.app_fp_in_not_rec_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="remain_fp_out_current">{{ trans('admin/mainrecodaily/table.remain_fp_out_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="remain_fp_out_closing">{{ trans('admin/mainrecodaily/table.remain_fp_out_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="bank_debits_not_match_current">{{ trans('admin/mainrecodaily/table.bank_debits_not_match_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="bank_debits_not_match_closing">{{ trans('admin/mainrecodaily/table.bank_debits_not_match_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="unmatch_bacs_ddr_current">{{ trans('admin/mainrecodaily/table.unmatch_bacs_ddr_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="unmatch_bacs_ddr_closing">{{ trans('admin/mainrecodaily/table.unmatch_bacs_ddr_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="unauthorized_dd_current">{{ trans('admin/mainrecodaily/table.unauthorized_dd_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="unauthorized_dd_recovered">{{ trans('admin/mainrecodaily/table.unauthorized_dd_recovered') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="unauthorized_dd_closing">{{ trans('admin/mainrecodaily/table.unauthorized_dd_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="balance_adj_current">{{ trans('admin/mainrecodaily/table.balance_adj_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="balance_adj_closing">{{ trans('admin/mainrecodaily/table.balance_adj_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="charge_back_current">{{ trans('admin/mainrecodaily/table.charge_back_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="charge_back_bal">{{ trans('admin/mainrecodaily/table.charge_back_bal') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="return_to_source_adj">{{ trans('admin/mainrecodaily/table.return_to_source_adj') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="return_to_source_fp_out_sent">{{ trans('admin/mainrecodaily/table.return_to_source_fp_out_sent') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="return_to_source_bal">{{ trans('admin/mainrecodaily/table.return_to_source_bal') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="blocked_amt_pos">{{ trans('admin/mainrecodaily/table.blocked_amt_pos') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="blocked_amt_atm">{{ trans('admin/mainrecodaily/table.blocked_amt_atm') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="blocked_amt_ofline_tt">{{ trans('admin/mainrecodaily/table.blocked_amt_ofline_tt') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="blocked_amt_fee">{{ trans('admin/mainrecodaily/table.blocked_amt_fee') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="blocked_amt_closing">{{ trans('admin/mainrecodaily/table.blocked_amt_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="net_diff">{{ trans('admin/mainrecodaily/table.net_diff') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="pos_interchang_current">{{ trans('admin/mainrecodaily/table.pos_interchang_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="pos_interchang_closing">{{ trans('admin/mainrecodaily/table.pos_interchang_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="atm_interchang_current">{{ trans('admin/mainrecodaily/table.atm_interchang_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="atm_interchang_closing">{{ trans('admin/mainrecodaily/table.atm_interchang_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="fp_out_fee">{{ trans('admin/mainrecodaily/table.fp_out_fee') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="atm_fee">{{ trans('admin/mainrecodaily/table.atm_fee') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="others_fee">{{ trans('admin/mainrecodaily/table.others_fee') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="closing_fee">{{ trans('admin/mainrecodaily/table.closing_fee') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="txn_sattled_not_adj_curr">{{ trans('admin/mainrecodaily/table.txn_sattled_not_adj_curr') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="txn_sattled_not_adj_closing">{{ trans('admin/mainrecodaily/table.txn_sattled_not_adj_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="adj_from_phy_vir_current">{{ trans('admin/mainrecodaily/table.adj_from_phy_vir_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="adj_from_phy_vir_closing">{{ trans('admin/mainrecodaily/table.adj_from_phy_vir_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="missing_gps_bal_current">{{ trans('admin/mainrecodaily/table.missing_gps_bal_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="missing_gps_bal_closing">{{ trans('admin/mainrecodaily/table.missing_gps_bal_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="bank_charges_current">{{ trans('admin/mainrecodaily/table.bank_charges_current') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="bank_charges_closing">{{ trans('admin/mainrecodaily/table.bank_charges_closing') }}</th>
                                <th data-sortable="true" data-searchable="true" data-field="ultra_net">{{ trans('admin/mainrecodaily/table.ultra_net') }}</th>
    
                                
                                                            
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

</script>
@stop