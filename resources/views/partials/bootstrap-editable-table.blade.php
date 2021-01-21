{{-- This Will load our default bootstrap-table settings on any table with a class of "snipe-table" and export it to the passed 'exportFile' name --}}
<script src="{{ asset('assets/js/bootstrap-table.js') }}"></script>
<script src="{{ asset('assets/js/extensions/mobile/bootstrap-table-mobile.js') }}"></script>
<script src="{{ asset('assets/js/extensions/export/bootstrap-table-export.js?v=1') }}"></script>
<script src="{{ asset('assets/js/extensions/cookie/bootstrap-table-cookie.js?v=1') }}"></script>
<script src="{{ asset('assets/js/extensions/export/tableExport.js?v=2') }}"></script>
<script src="{{ asset('assets/js/extensions/export/FileSaver.min.js') }}"></script>
<script src="{{ asset('assets/js/extensions/export/jquery.base64.js') }}"></script>
<script src="{{ asset('assets/js/extensions/multiple-sort/bootstrap-table-multiple-sort.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-table-editable.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-editable.js') }}"></script>
<script src="{{ asset('assets/js/plugins/filterControl/select2.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/filterControl/bootstrap-table-select2-filter.js') }}"></script>
<script src="{{ asset('assets/js/plugins/filterControl/bootstrap-table-fixed-columns.js') }}"></script>
<script>
$('.snipe-table').bootstrapTable({
        classes: 'table table-responsive table-no-bordered',
        undefinedText: '',
        iconsPrefix: 'fa',
        showRefresh: true,
        @if (isset($search))
        search: true,
        searchOnEnterKey: true,
        @endif
        pageSize: 20,
        pagination: true,
        detailView: false,
        sidePagination: 'server',
        sortOrder: 'desc',
        sortable: true,
        cookie: false,
        cookieExpire: '1440mi',
        mobileResponsive: true,
        @if (isset($multiSort))
        showMultiSort: true,
        @endif
        showExport: true,
        showColumns: true,
        //exportDataType: 'all',
        exportTypes: ['excel'],
        exportOptions: {
            fileName: '{{ $exportFile . "-" }}' + (new Date()).toISOString().slice(0,10),
            ignoreColumn: ['actions','radioedit'],
        },
        maintainSelected: true,
        paginationFirstText: "{{ trans('general.first') }}",
        paginationLastText: "{{ trans('general.last') }}",
        paginationPreText: "{{ trans('general.previous') }}",
        paginationNextText: "{{ trans('general.next') }}",
        pageList: ['10','25','50','100','150','2000','All'],
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
        @if(isset($filterColumn) && !empty($filterColumn))
        columns:<?php echo json_encode($filterColumn); ?>,
        filter: true,
        @endif
         onAll: function() {
            
            $('.lowerQty').parent().parent().css("background-color","#f5abab");
            $('[data-tooltip="tooltip"]').tooltip();
           $('.recoYes').parent().parent().css({"backgroundColor":"#97e6c2"});
            $('.recoYesYellow').parent().parent().css({"backgroundColor":"#e6e697"});
        },
        @if (isset($isTableEdit))

            onLoadSuccess: function() {

                 $.removeCookie('projectMaterialTable.bs.table.searchText');
                 $.removeCookie('projectMaterialExecutedTable.bs.table.searchText');
                 $.removeCookie('ProjectcostTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/projectcosts' });

                 $.removeCookie('salesProjectMaterialTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/sales/boq' });
               $.removeCookie('salesOrderTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/sales/view' });
            $.removeCookie('salesOrderTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/sales/view' });
             $.removeCookie('salesOrderTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/sales' });
                
                $.removeCookie('ProjectcostTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin/projectcosts' });

                $('[data-tooltip="tooltip"]').tooltip();
                {{$editTableFunction}}();
                      
            },


        @endif

    });
</script>
