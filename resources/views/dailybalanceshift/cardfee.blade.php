<table
            name="agencybanking"
            data-height="500"
            data-toolbar="#toolbar"
            class="table table-striped snipe-table1"
            id="table1"
            data-toggle="table"           
            data-url="{{ route('dailybalanceshift/getCardfeeDatatable',array('dailybalanceshiftId'=>$dailybalanceshiftId , 'dailyBalanceShiftPanNum' => $dailyBalanceShiftPanNum)) }}"
            data-cookie="true"
            data-click-to-select="true"
            data-cookie-id-table="abTableDisplay">
            <thead>
              <tr>
              
                <th data-sortable="true" data-searchable="true" data-field="actions">Action</th>
                <th data-sortable="true" data-searchable="true" data-field="CardFeeId">{{ trans('admin/card/table.fee.card_fee_id') }}</th>
    
                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/card/table.fee.settlement_date') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="TxId">{{ trans('admin/card/table.fee.txn_id') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="Amt_value">{{ trans('admin/card/table.fee.amt_value') }}</th>
                
                <th data-sortable="true" data-searchable="true" data-field="Amt_direction">{{ trans('admin/card/table.fee.amt_direction') }}</th>
                
                <th class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/card/table.fee.desc') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="Card_PAN" data-class="exportText">{{ trans('admin/card/table.fee.card_pan') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="Account_no" data-class="exportText">{{ trans('admin/card/table.fee.account_no') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="TxnCode_direction">{{ trans('admin/card/table.fee.txn_code_direction') }}</th>  

                <th data-sortable="true" data-searchable="true" data-field="TxnCode_ProcCode">{{ trans('admin/card/table.fee.txn_code_proc_code') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="FeeClass_interchangeTransaction">{{ trans('admin/card/table.fee.fee_class_inter_change_txn') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="FeeAmt_direction">{{ trans('admin/card/table.fee.fee_amt_direction') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="FeeAmt_value">{{ trans('admin/card/table.fee.fee_amt_value') }}</th>



                <th data-searchable="true" data-sortable="true" data-field="created_at">{{ trans('admin/card/table.fee.created_at') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="file_date">{{ trans('admin/card/table.fee.file_date') }}</th>    

                <th data-searchable="true" data-sortable="true" data-field="file_name">file_name</th>
                <th data-searchable="true" data-sortable="true" data-field="LoadUnloadId">LoadUnloadId</th>
                <th data-searchable="true" data-sortable="true" data-field="LocalDate">LocalDate</th>
                <th data-searchable="true" data-sortable="true" data-field="MerchCode">MerchCode</th>
                <th data-searchable="true" data-sortable="true" data-field="ReasonCode">ReasonCode</th>
                <th data-searchable="true" data-sortable="true" data-field="FIID">FIID</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_productid">Card_productid</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_product">Card_product</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_programid">Card_programid</th>
                <th data-searchable="true" data-sortable="true" data-field="Card_branchcode">Card_branchcode</th>
                <th data-searchable="true" data-sortable="true" data-field="Account_type">Account_type</th>
                <th data-searchable="true" data-sortable="true" data-field="TxnCode_Type">TxnCode_Type</th>
                <th data-searchable="true" data-sortable="true" data-field="TxnCode_Group">TxnCode_Group</th>
                <th data-searchable="true" data-sortable="true" data-field="MsgSource_value">MsgSource_value</th>
                <th data-searchable="true" data-sortable="true" data-field="MsgSource_domesticMaestro">MsgSource_domesticMaestro</th>
                <th data-searchable="true" data-sortable="true" data-field="FeeClass_type">FeeClass_type</th>
                <th data-searchable="true" data-sortable="true" data-field="FeeClass_code">FeeClass_code</th>
                <th data-searchable="true" data-sortable="true" data-field="FeeAmt_currency">FeeAmt_currency</th>
                <th data-searchable="true" data-sortable="true" data-field="Amt_currency">Amt_currency</th>           
                
              </tr>
            </thead>            
          </table>