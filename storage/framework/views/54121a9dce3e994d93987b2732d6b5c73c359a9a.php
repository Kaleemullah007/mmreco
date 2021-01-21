
<script src="<?php echo e(asset('assets/js/bootstrap-table.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/mobile/bootstrap-table-mobile.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/export/bootstrap-table-export.js?v=1')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/cookie/bootstrap-table-cookie.js?v=1')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/export/tableExport.js?v=2')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/export/FileSaver.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/export/jquery.base64.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/extensions/multiple-sort/bootstrap-table-multiple-sort.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-table-editable.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-editable.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/plugins/filterControl/select2.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/plugins/filterControl/bootstrap-table-select2-filter.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/plugins/filterControl/bootstrap-table-fixed-columns.js')); ?>"></script>
<script>
$('.snipe-table').bootstrapTable({
        classes: 'table table-responsive table-no-bordered',
        undefinedText: '',
        iconsPrefix: 'fa',
        showRefresh: true,
        <?php if(isset($search)): ?>
        search: true,
        searchOnEnterKey: true,
        <?php endif; ?>
        pageSize: 20,
        pagination: true,
        detailView: false,
        sidePagination: 'server',
        sortOrder: 'desc',
        sortable: true,
        cookie: false,
        cookieExpire: '1440mi',
        mobileResponsive: true,
        <?php if(isset($multiSort)): ?>
        showMultiSort: true,
        <?php endif; ?>
        showExport: true,
        showColumns: true,
        //exportDataType: 'all',
        exportTypes: ['excel'],
        exportOptions: {
            fileName: '<?php echo e($exportFile . "-"); ?>' + (new Date()).toISOString().slice(0,10),
            ignoreColumn: ['actions','radioedit'],
        },
        maintainSelected: true,
        paginationFirstText: "<?php echo e(trans('general.first')); ?>",
        paginationLastText: "<?php echo e(trans('general.last')); ?>",
        paginationPreText: "<?php echo e(trans('general.previous')); ?>",
        paginationNextText: "<?php echo e(trans('general.next')); ?>",
        pageList: ['10','25','50','100','150','2000','All'],
        icons: {
            paginationSwitchDown: 'fa-caret-square-o-down',
            paginationSwitchUp: 'fa-caret-square-o-up',
            columns: 'fa-columns',
            <?php if( isset($multiSort)): ?>
            sort: 'fa fa-sort-amount-desc',
            plus: 'fa fa-plus',
            minus: 'fa fa-minus',
            <?php endif; ?>
            refresh: 'fa-refresh'
        },
        <?php if(isset($filterColumn) && !empty($filterColumn)): ?>
        columns:<?php echo json_encode($filterColumn); ?>,
        filter: true,
        <?php endif; ?>
         onAll: function() {
            
            $('.lowerQty').parent().parent().css("background-color","#f5abab");
            $('[data-tooltip="tooltip"]').tooltip();
           $('.recoYes').parent().parent().css({"backgroundColor":"#97e6c2"});
            $('.recoYesYellow').parent().parent().css({"backgroundColor":"#e6e697"});
        },
        <?php if(isset($isTableEdit)): ?>

            onLoadSuccess: function() {

                 $.removeCookie('projectMaterialTable.bs.table.searchText');
                 $.removeCookie('projectMaterialExecutedTable.bs.table.searchText');
                 $.removeCookie('ProjectcostTable.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin/projectcosts' });

                 $.removeCookie('salesProjectMaterialTable.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/boq' });
              $.removeCookie('salesProjectMaterialTable.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/boq' });
               $.removeCookie('salesOrderTable.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/view' });
            $.removeCookie('salesOrderTable.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales/view' });
             $.removeCookie('salesOrderTable.bs.table.searchText', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.sortName', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.sortOrder', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.pageNumber', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales' });
           $.removeCookie('salesOrderTable.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/sales' });
                
                $.removeCookie('ProjectcostTable.bs.table.pageList', { path: '/<?php echo e(config('app.project_name')); ?>/public/admin/projectcosts' });

                $('[data-tooltip="tooltip"]').tooltip();
                <?php echo e($editTableFunction); ?>();
                      
            },


        <?php endif; ?>

    });
</script>
