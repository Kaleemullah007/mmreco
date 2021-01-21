@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('general.dashboard') }}
@parent
@stop


{{-- Page content --}}
@section('content')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/morris.css') }}">

<div class="row">

      @can('users.view')
       <div class="col-lg-3 col-xs-6 col-md-3" style="height: 150px;">
        <!-- small box -->
        <div class="small-box bg-light-blue">
          <div class="inner">
            <h3>{{ number_format(\App\Models\User::employeeCount()) }}</h3>
            <p>{{ trans('general.total_workers') }}</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
                <a href="{{ route('users') }}" class="small-box-footer">{{ trans('general.moreinfo') }} <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      @endcan

</div>


<!-- recent activity -->
@section('moar_scripts')


    <script src="{{ asset('assets/js/bootstrap-table.js') }}"></script>
    <script src="{{ asset('assets/js/extensions/mobile/bootstrap-table-mobile.js') }}"></script>
    <script type="text/javascript">
        //$('#table').bootstrapTable({
        //     classes: 'table table-responsive table-no-bordered',
        //     undefinedText: '',
        //     iconsPrefix: 'fa',
        //     showRefresh: false,
        //     search: false,
        //     pagination: false,
        //     sidePagination: 'server',
        //     sortable: false,
        //     showMultiSort: false,
        //     cookie: false,
        //     mobileResponsive: true,
        // });

    </script>
@stop


@stop
