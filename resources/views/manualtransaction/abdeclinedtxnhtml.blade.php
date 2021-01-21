<table
            name="agencybanking"
            data-height="500"
            data-toolbar="#toolbar"
            class="table table-striped snipe-table1"
            id="table"
            data-toggle="table"           
            data-url="{{ route('manualtransaction/getAbDeclinedDatatable') }}"
            data-cookie="true"
            data-click-to-select="true"
            data-cookie-id-table="abTableDisplay">
            <thead>
              <tr>
              
                <th data-switchable="false" data-searchable="false" data-sortable="false" data-field="actions" ></th>
                
                <th data-searchable="true" data-sortable="true" data-field="SettlementDate">{{ trans('admin/agencybanking/table.settlement_date') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="CashAmt_value">{{ trans('admin/agencybanking/table.cash_amt_value') }}</th>

                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="External_sortcode">{{ trans('admin/agencybanking/table.external_sort_code') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="External_bankacc">{{ trans('admin/agencybanking/table.external_bank_acc') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="External_name">{{ trans('admin/agencybanking/table.external_name') }}</th>

                <th data-sortable="true" data-searchable="true" data-field="CashType">{{ trans('admin/agencybanking/table.cash_type') }}</th>

                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="File_filename">GPS filename</th>
                <th data-sortable="true" data-searchable="true" data-field="File_filedate">GPS filedate</th>

                <th data-sortable="true" data-searchable="true" data-field="file_date">Filedate</th>
                <th data-class="sowShort" data-sortable="true" data-searchable="true" data-field="file_name">Filename</th>
  
                <th data-searchable="true" data-sortable="true" data-field="BankingId">{{ trans('admin/agencybanking/table.banking_id') }}</th>

                <th data-class="sowShort" data-searchable="true" data-sortable="true" data-field="Desc">{{ trans('admin/agencybanking/table.desc') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="Card_PAN">{{ trans('admin/agencybanking/table.card_pan') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="AgencyAccount_no">{{ trans('admin/agencybanking/table.agency_account_no') }}</th>

                <th data-searchable="true" data-sortable="true" data-field="AgencyAccount_sortcode">{{ trans('admin/agencybanking/table.agency_account_sort_code') }}</th>  

                <th data-searchable="true" data-sortable="true" data-field="Fee_direction">{{ trans('admin/agencybanking/table.fee_direction') }}</th> 

                <th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_direction">{{ trans('admin/agencybanking/table.CashCode_direction') }}</th>                                
                <th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashType">{{ trans('admin/agencybanking/table.CashCode_CashType') }}</th>                              
                <th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashCode_CashGroup">{{ trans('admin/agencybanking/table.CashCode_CashGroup') }}</th>                                
                <th data-searchable="true" data-sortable="true" data-visible="true" data-field="CashAmt_currency">{{ trans('admin/agencybanking/table.CashAmt_currency') }}</th>        

                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_productid">{{ trans('admin/agencybanking/table.Card_productid') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_product">{{ trans('admin/agencybanking/table.Card_product') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_programid">{{ trans('admin/agencybanking/table.Card_programid') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Card_branchcode">{{ trans('admin/agencybanking/table.Card_branchcode') }}</th>                             
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_type">{{ trans('admin/agencybanking/table.AgencyAccount_type') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_bankacc">{{ trans('admin/agencybanking/table.AgencyAccount_bankacc') }}</th>                             
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="AgencyAccount_name">{{ trans('admin/agencybanking/table.AgencyAccount_name') }}</th>                               
                                        
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Fee_value">{{ trans('admin/agencybanking/table.Fee_value') }}</th>                             
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="Fee_currency">{{ trans('admin/agencybanking/table.Fee_currency') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_value">{{ trans('admin/agencybanking/table.BillAmt_value') }}</th>                             
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_currency">{{ trans('admin/agencybanking/table.BillAmt_currency') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="BillAmt_rate">{{ trans('admin/agencybanking/table.BillAmt_rate') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_value">{{ trans('admin/agencybanking/table.OrigTxnAmt_value') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_currency">{{ trans('admin/agencybanking/table.OrigTxnAmt_currency') }}</th>                             
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_partial">{{ trans('admin/agencybanking/table.OrigTxnAmt_partial') }}</th>                               
                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="OrigTxnAmt_origItemId">{{ trans('admin/agencybanking/table.OrigTxnAmt_origItemId') }}</th>

                <th data-searchable="true" data-sortable="true" data-visible="false" data-field="DeclineReason">{{ trans('admin/agencybanking/table.DeclineReason') }}</th>               
                
              </tr>
            </thead>            
          </table>