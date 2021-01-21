{{-- This Will load our default bootstrap-table settings on any table with a class of "snipe-table" and export it to the passed 'exportFile' name --}}
<script src="{{ asset('assets/js/bootstrap-table.js?v=1') }}"></script>

<script src="{{ asset('assets/js/extensions/mobile/bootstrap-table-mobile.js') }}"></script>
<script src="{{ asset('assets/js/extensions/export/bootstrap-table-export.js?v=1') }}"></script>
<script src="{{ asset('assets/js/extensions/cookie/bootstrap-table-cookie.js?v=1') }}"></script>
<script src="{{ asset('assets/js/extensions/export/tableExport.js?v=2') }}"></script>
<script src="{{ asset('assets/js/extensions/export/FileSaver.min.js') }}"></script>
<script src="{{ asset('assets/js/extensions/export/jquery.base64.js') }}"></script>
<script src="{{ asset('assets/js/extensions/multiple-sort/bootstrap-table-multiple-sort.js') }}"></script>
<script src="{{ asset('assets/js/plugins/filterControl/select2.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/filterControl/bootstrap-table-select2-filter.js?1') }}"></script>
<script src="{{ asset('assets/js/plugins/filterControl/bootstrap-table-fixed-columns.js') }}"></script>

<script>

$('.snipe-table').bootstrapTable({
        classes: 'table table-responsive table-no-bordered',
        undefinedText: '',
        iconsPrefix: 'fa',
        showRefresh: true,
        cookie: false,
        @if (isset($search))
        search: true,
        @endif
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
        showExport: true,
        showColumns: true,
        //exportDataType: 'all',
        exportTypes: ['excel','csv'],
        exportOptions: {
            fileName: '{{ $exportFile . "-" }}' + (new Date()).toISOString().slice(0,10),
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
        @if(isset($filterColumn) && !empty($filterColumn))
        columns:<?php echo json_encode($filterColumn); ?>,
        filter: true,
        @endif
        onAll: function() {

            $('.lowCost').parent().css("background-color","#de2f2f");
            $('.warningCost').parent().css("background-color","#f39c12");

            $('.fixed-table-header-columns .extrafixedcall').hide();
            $('.prCost').find('input').attr("disabled",'disabled');
            $('[data-tooltip="tooltip"]').tooltip();
            $('.recoYes').parent().parent().css({"backgroundColor":"#97e6c2"});
            $('.recoYesYellow').parent().parent().css({"backgroundColor":"#e6e697"});

            $('.radioSelected').parent().parent().addClass('highlight_row');
        },
        onLoadSuccess: function() 
        {
        	$('.datepicker').datepicker({"autoclose": true});
             $.removeCookie('projectTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/project' });
            $.removeCookie('projectTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/admin/project' });   
            $.removeCookie('projectTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/project' });            
            
             $.removeCookie('warrantyTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public' });
             $.removeCookie('warrantyTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/warranty' });
             $.removeCookie('userTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
             $.removeCookie('userTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
             $.removeCookie('clientsTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin' });
            $.removeCookie('clientsTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin' });
             $.removeCookie('modelsTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/hardware' });
             $.removeCookie('categoriesTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings' });
             $.removeCookie('manufacturersTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings' });
             $.removeCookie('suppliersTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings' });
             $.removeCookie('locationsTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings' });

             $.removeCookie('projectProgramTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/project/projectprogram' });
            $.removeCookie('projectProgramTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/project/projectprogram' });
            
            $.removeCookie('warrantyTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public' });
            $.removeCookie('warrantyTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/warranty' });
            
             $.removeCookie('resourcerequest.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/resource' });
             $.removeCookie('resourceallocate.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/allocate/viewAllocateResources' });
             $.removeCookie('projectresourceallocate.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/allocate/viewProjectAllocateResources' });
             $.removeCookie('projectresourceallocate.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/allocate' });
             $.removeCookie('reallocate.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/allocate/reAllocateResources' });

             $.removeCookie('customerBillTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/customerbill/viewAllCustomerBill' });
    		$.removeCookie('customerBillTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/customerbill/viewAllCustomerBill' });

    		 $.removeCookie('lineItemTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/customerbill/addCustomerBill' });
    		$.removeCookie('lineItemTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/customerbill/addCustomerBill' });

    		 $.removeCookie('billTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/customerbill/viewCustomerBill' });
    		$.removeCookie('billTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/customerbill/viewCustomerBill' });

    		 $.removeCookie('userTableDisplay.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/user' });
     		$.removeCookie('userTableDisplay.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/user' });

     		 $.removeCookie('vbillTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/vendorbill/viewVendorBill' });
     		$.removeCookie('vbillTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/vendorbill/viewVendorBill' });

     		 $.removeCookie('vendorBillTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/vendorbill/viewAllVendorBill' });
     		$.removeCookie('vendorBillTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/vendorbill/viewAllVendorBill' });

     		 $.removeCookie('vlineItemTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/vendorbill/addVendorBill' });
     		$.removeCookie('vlineItemTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/vendorbill/addVendorBill' });

     		 $.removeCookie('vendorTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/vendor/viewVendor' });
     		$.removeCookie('vendorTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/vendor/viewVendor' });

     		 $.removeCookie('suppliersTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings/suppliers' });
     		$.removeCookie('suppliersTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/settings/suppliers' });

     		 $.removeCookie('warrantyTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/AmcWarranty' });
            $.removeCookie('warrantyTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/AmcWarranty' });

            $.removeCookie('locationsTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/settings/locations' });
		    $.removeCookie('locationsTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/settings/locations' });

		     $.removeCookie('projectMaterialTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/admin/project//projectmaterial/service' });
			 $.removeCookie('projectMaterialTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/admin/project//projectmaterial/service' });

			 $.removeCookie('customerBillTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/customerbill/viewAllCustomerBill' });
    		$.removeCookie('customerBillTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/customerbill/viewAllCustomerBill' });

    		 $.removeCookie('b2bserviceTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/b2bservice/view' });
     		$.removeCookie('b2bserviceTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/b2bservice/view' });

            $.removeCookie('warrantyExpireTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public' });
             $.removeCookie('warrantyExpireTable.bs.table.searchText', { path: '/{{config('app.project_name') }}/public/warranty' });
            $.removeCookie('warrantyExpireTable.bs.table.sortName', { path: '/{{config('app.project_name') }}/public' });
            $.removeCookie('warrantyExpireTable.bs.table.sortName', { path: '/{{config('app.project_name') }}/public/api/warranty' });
            $.removeCookie('warrantyExpireTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public' });
            $.removeCookie('warrantyExpireTable.bs.table.sortOrder', { path: '/{{config('app.project_name') }}/public/api/warranty' });
            $.removeCookie('warrantyExpireTable.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public' });
            $.removeCookie('warrantyExpireTable.bs.table.pageNumber', { path: '/{{config('app.project_name') }}/public/api/warranty' });
            $.removeCookie('warrantyExpireTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public' });
            $.removeCookie('warrantyExpireTable.bs.table.pageList', { path: '/{{config('app.project_name') }}/public/api/warranty' });

            $('.fixed-table-header-columns .extrafixedcall').hide();
            $('[data-tooltip="tooltip"]').tooltip();
        },

    });


</script>
