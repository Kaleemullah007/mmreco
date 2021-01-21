@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {{ trans('general.monthlybalanceshift') }}
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
                    <form id="create-search-form" class="" method="get" action="{{ route('monthlybalanceshift') }}" autocomplete="off" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}                      
                        <div class="col-md-8" style="margin-top:10px;">
                            
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="report_datepicker1 form-control" style="margin-top:0px;" placeholder="{{ trans('general.reportMonth') }}" data-date-format="mm-yyyy" name="reportMonth" id="reportMonth" value="{{ Input::old('reportMonth', Input::get('reportMonth')) }}">
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
                        data-url="{{ route('api.monthlybalanceshift.list', array('pan'=>(Input::get('pan'))?Input::get('pan'):'','reportMonth'=>(Input::get('reportMonth'))?Input::get('reportMonth'):'')) }}"
                        data-cookie="true"
                        data-click-to-select="true"
                        data-cookie-id-table="dbsTableDisplay">
                        <thead>
                            <tr>                                
                                
                                <th data-sortable="true" data-searchable="true" data-field="report_month">{{ trans('admin/monthlybalanceshift/table.report_month') }}</th>
    
                                <th data-searchable="true" data-sortable="true" data-field="pan">{{ trans('admin/monthlybalanceshift/table.pan') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="opening_ac_bal">{{ trans('admin/monthlybalanceshift/table.opening_ac_bal') }}</th>

                                <th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="ATM_Settled">{{ trans('admin/monthlybalanceshift/table.atm_settled') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="POS_Settled">{{ trans('admin/monthlybalanceshift/table.pos_settled') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="ATM_FEE">{{ trans('admin/monthlybalanceshift/table.atm_fee') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="FPIN">{{ trans('admin/monthlybalanceshift/table.fpin') }}</th>    

                                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="FP_out">{{ trans('admin/monthlybalanceshift/table.fp_out') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="FP_out_fee">{{ trans('admin/monthlybalanceshift/table.fp_out_fee') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Other_fees">{{ trans('admin/monthlybalanceshift/table.other_fees') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Load_Unload">{{ trans('admin/monthlybalanceshift/table.load_unload') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="Blocked_Amount">{{ trans('admin/monthlybalanceshift/table.blocked_amount') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="Balance_Adj">{{ trans('admin/monthlybalanceshift/table.balance_adj') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="closing_ac_bal_calc">{{ trans('admin/monthlybalanceshift/table.closing_ac_bal_calc') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="closing_ac_bal_gps">{{ trans('admin/monthlybalanceshift/table.closing_ac_bal_gps') }}</th>    

                                <th data-searchable="true" data-sortable="true" data-field="Transactions_in_Timing">{{ trans('admin/monthlybalanceshift/table.Transactions_in_Timing') }}</th>  

                                <th data-searchable="true" data-sortable="true" data-field="Transactions_in_Timing2">{{ trans('admin/monthlybalanceshift/table.Transactions_in_Timing2') }}</th>

                                <th data-searchable="true" data-sortable="true" data-field="diff">{{ trans('admin/monthlybalanceshift/table.diff') }}</th>    
                                                            
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
</style>
<script>
$(document).ready(function(){

    $('.report_datepicker1').datepicker({
    format: "mm-yyyy",
    startView: "year", 
    minViewMode: "months",
    autoclose: true
    });

});
function resetAllTableData()
{
    $.removeCookie('dbsTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/admin' });
    $.removeCookie('dbsTableDisplay.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin' });
 
    window.location.href="{{ url('monthlybalanceshift') }}";
}

</script>
@stop